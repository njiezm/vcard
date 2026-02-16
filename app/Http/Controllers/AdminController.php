<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\WelcomeVCardMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(10);
        $stats = [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::where('is_active', true)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'paid')->count(),
        ];
        return view('admin.index', compact('customers', 'stats'));
    }

    // ===== GESTION DES COMMANDES =====
    
    public function orders(Request $request)
    {
        $query = Order::latest();
        
        // Filtres
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }
        
        if ($request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('firstname', 'like', "%{$search}%")
                  ->orWhere('lastname', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $orders = $query->paginate(15);
       $stats = [
    'total_orders' => Order::count(),
    'pending_orders' => Order::where('status', 'pending')->count(),
    'paid_orders' => Order::where('status', 'paid')->count(),
    'cancelled_orders' => Order::where('status', 'cancelled')->count(),
];

        
        return view('admin.orders', compact('orders', 'stats'));
    }
    
    public function createCustomerFromOrder(Order $order)
    {
        // Vérifier si un client existe déjà avec cet email
        $existingCustomer = Customer::where('email', $order->email)->first();
        if ($existingCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Un client existe déjà avec cet email !'
            ], 400);
        }
        
        // Générer slug unique
        $slug = Str::slug($order->firstname . '-' . $order->lastname);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Customer::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        // Générer admin code unique
        do {
            $adminCode = strtoupper(Str::random(8)); 
        } while (Customer::where('admin_code', $adminCode)->exists());
        
        // Créer le client
        $customer = Customer::create([
            'firstname' => $order->firstname,
            'lastname' => $order->lastname,
            'email' => $order->email,
            'slug' => $slug,
            'admin_code' => $adminCode,
            'is_active' => true,
        ]);
        
        // Mettre à jour le statut de la commande
        $order->update([
            'status' => 'completed',
            'payment_details' => array_merge($order->payment_details ?? [], [
                'customer_created_at' => now()->toISOString(),
                'customer_id' => $customer->id
            ])
        ]);
        
        // Envoyer l'email de bienvenue
        if ($customer->email) {
            try {
                $password = 'TempPass123!';
                Mail::to($customer->email)->send(new WelcomeVCardMail($customer, $password));
            } catch (\Exception $e) {
                \Log::error('Erreur envoi email: ' . $e->getMessage());
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Client créé avec succès et email envoyé !',
            'customer' => $customer,
            'redirect_url' => route('admin.edit', $customer)
        ]);
    }
    
    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,completed,cancelled'
        ]);
        
        $order->update([
            'status' => $request->status,
            'payment_details' => array_merge($order->payment_details ?? [], [
                'status_updated_at' => now()->toISOString(),
                'status_updated_by' => 'admin'
            ])
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Statut de la commande mis à jour !'
        ]);
    }
    
    public function deleteOrder(Order $order)
    {
        $order->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Commande supprimée avec succès !'
        ]);
    }

    // ===== GESTION DES CLIENTS =====
    
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'youtube' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Generate unique slug and admin code
        $slug = Str::slug($validated['firstname'] . '-' . $validated['lastname']);
        $originalSlug = $slug;
        $counter = 1;
        
        while (Customer::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        $validated['slug'] = $slug;
        
        do {
            $adminCode = strtoupper(Str::random(8)); 
        } while (Customer::where('admin_code', $adminCode)->exists());

        $validated['admin_code'] = $adminCode;

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/photos', $photoName);
            $validated['photo'] = 'photos/' . $photoName;
        }

        $customer = Customer::create($validated);

        // Flash customer data for modal
        session()->flash('new_customer', $customer);
        session()->flash('show_validation_modal', true);

        return redirect()->route('admin.index')->with('success', 'Client créé avec succès !');
    }

    public function edit(Customer $customer)
    {
        return view('admin.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|string|max:255',
            'twitter' => 'nullable|string|max:255',
            'tiktok' => 'nullable|string|max:255',
            'youtube' => 'nullable|url|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($customer->photo) {
                Storage::disk('public')->delete($customer->photo);
            }
            
            $photo = $request->file('photo');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/photos', $photoName);
            $validated['photo'] = 'photos/' . $photoName;
        }

        $customer->update($validated);

        return redirect()->route('admin.index')->with('success', 'Client mis à jour avec succès !');
    }

    public function sendWelcomeEmail(Customer $customer)
    {
        $password = 'TempPass123!';
        Mail::to($customer->email)->send(new WelcomeVCardMail($customer, $password));

        return response()->json([
            'success' => true,
            'message' => 'Email de bienvenue envoyé avec succès !'
        ]);
    }

    public function validateAndSend(Request $request)
    {
        $customer = Customer::find($request->customer_id);
        $action = $request->action;

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Client non trouvé'], 404);
        }

        switch ($action) {
            case 'validate_send':
                if ($customer->email) {
                    try {
                        $password = 'TempPass123!';
                        Mail::to($customer->email)->send(new WelcomeVCardMail($customer, $password));
                        return response()->json([
                            'success' => true, 
                            'message' => 'Client validé et email envoyé !'
                        ]);
                    } catch (\Exception $e) {
                        return response()->json([
                            'success' => false, 
                            'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Le client n\'a pas d\'email !'
                    ], 400);
                }
                
            case 'validate_only':
                return response()->json([
                    'success' => true, 
                    'message' => 'Client validé avec succès !'
                ]);
                
            case 'cancel':
                return response()->json([
                    'success' => true, 
                    'message' => 'Action annulée !'
                ]);
                
            default:
                return response()->json(['success' => false, 'message' => 'Action invalide'], 400);
        }
    }

    public function destroy(Customer $customer)
    {
        if ($customer->photo) {
            Storage::disk('public')->delete($customer->photo);
        }

        $customer->delete();

        return response()->json([
            'success' => true,
            'message' => 'Client supprimé avec succès !'
        ]);
    }

    public function toggleActive(Customer $customer)
    {
        $customer->update([
            'is_active' => !$customer->is_active
        ]);

        return response()->json([
            'success' => true,
            'message' => $customer->is_active ? 'Carte activée' : 'Carte désactivée',
            'status' => $customer->is_active
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($request->email === 'admin@example.com' && $request->password === 'secret123') {
            session(['admin_auth' => true]);
            return redirect()->route('admin.index');
        }

        return back()->with('error', 'Identifiants incorrects');
    }

    public function logout()
    {
        session()->forget('admin_auth');
        return redirect()->route('admin.login.page');
    }

    /**
 * Exporte les commandes dans différents formats (CSV, Excel, PDF).
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Symfony\Component\HttpFoundation\StreamedResponse|\Illuminate\Http\RedirectResponse
 */
public function exportOrders(Request $request)
{
    $format = $request->get('format', 'csv'); // Récupère le format, par défaut 'csv'

    // Récupère toutes les commandes avec les informations du client
    $orders = Order::with('customer')->latest()->get();

    if ($format === 'csv') {
        $fileName = 'commandes_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // En-tête du CSV
            fputcsv($file, ['ID Commande', 'Nom du Client', 'Email Client', 'Montant (€)', 'Statut', 'Date de création']);

            // Données
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->id,
                    $order->customer ? $order->customer->name : 'N/A',
                    $order->customer ? $order->customer->email : 'N/A',
                    number_format($order->amount, 2, ',', ' '),
                    $order->status,
                    $order->created_at->format('d/m/Y H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);

    } elseif ($format === 'excel') {
        // TODO: Implémenter l'export Excel (nécessite un package comme "laravel-excel")
        return redirect()->back()->with('info', "L'export Excel est en cours de développement.");

    } elseif ($format === 'pdf') {
        // TODO: Implémenter l'export PDF (nécessite un package comme "dompdf")
        return redirect()->back()->with('info', "L'export PDF est en cours de développement.");
    }

    // Si le format n'est pas reconnu
    return redirect()->back()->with('error', 'Format d\'export non valide.');
}

public function showOrder(Order $order)
{
    return view('admin.order_show', compact('order'));
}

public function sendInvoice(Order $order)
{
    // Exemple : envoyer la facture par email
    if ($order->customer && $order->customer->email) {
        try {
            Mail::to($order->customer->email)->send(new \App\Mail\OrderInvoiceMail($order));
            return response()->json([
                'success' => true,
                'message' => 'Facture envoyée avec succès !'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage()
            ], 500);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'Le client associé à cette commande n\'a pas d\'email.'
    ], 400);
}
public function downloadInvoice(Order $order)
{
    // Exemple avec un PDF stocké ou généré à la volée
    $pdf = \PDF::loadView('admin.invoices.invoice', compact('order')); // si tu utilises barryvdh/laravel-dompdf
    return $pdf->download('facture_'.$order->id.'.pdf');
}

public function bulkUpdateStatus(Request $request)
{
    $request->validate([
        'order_ids' => 'required|array',
        'status' => 'required|string|in:pending,paid,cancelled',
    ]);

    Order::whereIn('id', $request->order_ids)
        ->update(['status' => $request->status]);

    return redirect()->back()->with('success', 'Statut mis à jour pour les commandes sélectionnées.');
}

public function bulkCreateCustomers(Request $request)
{
    $request->validate([
        'order_ids' => 'required|array',
    ]);

    $orders = Order::whereIn('id', $request->order_ids)->get();

    foreach ($orders as $order) {
        Customer::firstOrCreate([
            'email' => $order->customer_email,
        ], [
            'name' => $order->customer_name,
        ]);
    }

    return redirect()->back()->with('success', 'Clients créés pour les commandes sélectionnées.');
}

public function bulkDelete(Request $request)
{
    $request->validate([
        'order_ids' => 'required|array',
    ]);

    Order::whereIn('id', $request->order_ids)->delete();

    return redirect()->back()->with('success', 'Commandes supprimées avec succès.');
}

public function editO(Order $order)
{
    return view('admin.orders.edit', compact('order'));
}

}