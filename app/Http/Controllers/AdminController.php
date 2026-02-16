<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Mail\WelcomeVCardMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->get();
        return view('admin.index', compact('customers'));
    }

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
        //$validated['admin_code'] = '54687454';
        //do {
            // Génère un code numérique aléatoire de 6 à 8 chiffres
           //$adminCode = random_int(100000, 99999999);
       // } while (Customer::where('admin_code', $adminCode)->exists());
        do {
            // Génère un code alphanumérique de 8 caractères
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
                // Optionally delete the customer if needed
                return response()->json([
                    'success' => true, 
                    'message' => 'Action annulée !'
                ]);
                
            default:
                return response()->json(['success' => false, 'message' => 'Action invalide'], 400);
        }
    }

    // Supprimer un client
public function destroy(Customer $customer)
{
    // Supprimer la photo si elle existe
    if ($customer->photo) {
        Storage::disk('public')->delete($customer->photo);
    }

    // Supprimer le client (Soft Delete)
    $customer->delete();

    return response()->json([
        'success' => true,
        'message' => 'Client supprimé avec succès !'
    ]);
}

// Activer / Désactiver la carte
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

    // Ici tu compares avec un admin fixe, ou avec la table Customer si tu veux
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

}