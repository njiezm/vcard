<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // Import manquant
use App\Models\Order; // Import manquant

class PurchaseController extends Controller
{
    /**
     * Affiche la page d'achat avec le choix du paiement.
     */
    public function show()
    {
        // Prix pour chaque devise
        $prices = [
            'EUR' => 29.99,
            'USD' => 32.99,
            'GBP' => 24.99,
            'CHF' => 28.99,
            'CAD' => 39.99
        ];

        return view('purchase', compact('prices'));
    }

    /**
     * Traite le formulaire d'achat et redirige vers la méthode de paiement choisie
     */
/**
 * Traite le formulaire d'achat et redirige vers la méthode de paiement choisie
 */
public function processPurchase(Request $request)
{
    // Validation des données reçues
    $validated = $request->validate([
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'currency' => 'required|string|in:EUR,USD,GBP,CHF,CAD',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:bank_transfer,sumup,paypal',
    ]);

    // Définir les prix directement dans le contrôleur (plus sûr)
    $prices = [
        'EUR' => 29.99,
        'USD' => 32.99,
        'GBP' => 24.99,
        'CHF' => 28.99,
        'CAD' => 39.99
    ];

    // Mesure de sécurité : vérifier que le montant envoyé correspond au montant attendu
    $expectedAmount = $prices[$validated['currency']] ?? 29.99;
    if (abs($validated['amount'] - $expectedAmount) > 0.01) {
        // Si les montants ne correspondent pas, on loggue l'alerte
        Log::warning('Tentative de modification de montant détectée', [
            'email' => $validated['email'],
            'received_amount' => $validated['amount'],
            'expected_amount' => $expectedAmount,
            'currency' => $validated['currency']
        ]);
        // On utilise le montant attendu pour la commande
        $finalAmount = $expectedAmount;
    } else {
        $finalAmount = $validated['amount'];
    }

    try {
        // Création de la commande en base de données
        $order = Order::create([
            'order_id' => 'ORD-' . strtoupper(uniqid()),
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'amount' => $finalAmount,
            'currency' => $validated['currency'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
            'payment_details' => [], // Initialiser en tableau vide
        ]);

        // Stocker l'ID de la commande en session pour les étapes suivantes
        session(['order_id' => $order->order_id]);

        // Retourner une réponse JSON de succès
        return response()->json([
            'success' => true,
            'order_id' => $order->order_id,
            'redirect_url' => route('payment.' . $validated['payment_method'])
        ]);

    } catch (\Exception $e) {
        // En cas d'erreur, on la loggue pour le débogage
        Log::error('Erreur lors de la création de la commande : ' . $e->getMessage());
        
        // On retourne une réponse d'erreur JSON propre
        return response()->json([
            'success' => false,
            'message' => 'Une erreur est survenue. Veuillez réessayer.'
        ], 500); // Code 500 pour erreur serveur
    }
}
    /**
     * Affiche la page d'instructions pour le virement bancaire.
     */
    public function showBankTransfer()
    {
        // Récupérer la commande depuis la base de données
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('purchase')->with('error', 'Commande non trouvée');
        }
        
        return view('payment.bank_transfer', compact('order'));
    }

    /**
     * Traite la confirmation du virement bancaire.
     */
    public function confirmBankTransfer(Request $request)
    {
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('purchase')->with('error', 'Commande non trouvée');
        }
        
        // Mettre à jour le statut de la commande
        $order->status = 'awaiting_payment'; // Statut en attente de validation du virement
        $order->payment_details = array_merge($order->payment_details ?? [], [
            'bank_transfer_confirmed_at' => now()->toISOString(),
            'confirmation_ip' => $request->ip()
        ]);
        $order->save();
        
        // Envoyer un email de notification à l'administrateur
        // TODO: Implémenter l'envoi d'email
        
        return redirect()->route('purchase.success')->with('status', 'Votre confirmation de virement a bien été enregistrée. Nous traiterons votre paiement dès sa réception.');
    }
    
    /**
     * Affiche la page de succès après paiement.
     */
    public function showSuccess() {
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        return view('payment.success', compact('order'));
    }

    /**
     * Affiche la page de paiement PayPal.
     */
    public function showPayPal()
    {
        // Logique pour préparer le paiement PayPal si nécessaire
        return view('payment.payment_paypal');
    }

    /**
     * Affiche la page de paiement SumUp.
     */
    public function showSumUp()
    {
        // Récupérer la commande depuis la base de données
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return redirect()->route('purchase')->with('error', 'Commande non trouvée');
        }

        return view('payment.payment_sumup', compact('order'));
    }

    /**
     * Crée un checkout ID SumUp via une requête API sécurisée.
     */
    public function createSumUpCheckout(Request $request)
    {
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return response()->json(['error' => 'Commande non trouvée'], 404);
        }
        
        $checkoutReference = $order->order_id; // Utiliser l'ID de commande comme référence

        try {
            $response = Http::withToken(config('sumup.api_key'))
                ->post(config('sumup.checkout_url'), [
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                    'checkout_reference' => $checkoutReference,
                    'pay_to_email' => 'njiezamon10@gmail.com', 
                    'description' => 'Achat DIGITCARD Elite',
                ]);

            $data = $response->json();

            if ($response->successful() && isset($data['id'])) {
                // Mettre à jour la commande avec l'ID de checkout SumUp
                $order->payment_details = array_merge($order->payment_details ?? [], [
                    'sumup_checkout_id' => $data['id'],
                    'sumup_checkout_created_at' => now()->toISOString()
                ]);
                $order->save();
                
                // Stockez la référence en session pour la confirmation ultérieure
                session(['sumup_checkout_reference' => $checkoutReference]);
                return response()->json(['checkout_id' => $data['id']]);
            } else {
                Log::error('Erreur création checkout SumUp: ' . $response->body());
                return response()->json([
                    'status' => $response->status(),
                    'body' => $response->body(),
                ], 400);
            }
        } catch (\Exception $e) {
            Log::error('Exception création checkout SumUp: ' . $e->getMessage());
            return response()->json(['error' => 'Erreur serveur.'], 500);
        }
    }

    /**
     * Confirme le paiement après le succès sur SumUp.
     */
    public function confirmSumUpPayment(Request $request)
    {
        $checkoutReference = session('sumup_checkout_reference');

        if (!$checkoutReference) {
            return response()->json(['error' => 'Session de paiement invalide.'], 400);
        }

        // Récupérer la commande
        $order = Order::where('order_id', $checkoutReference)->first();
        
        if (!$order) {
            return response()->json(['error' => 'Commande non trouvée.'], 404);
        }

        // Vérifier le paiement via l'API SumUp
        try {
            $checkoutId = $order->payment_details['sumup_checkout_id'] ?? null;
            
            if (!$checkoutId) {
                return response()->json(['error' => 'ID de checkout SumUp non trouvé.'], 400);
            }
            
            $response = Http::withToken(config('sumup.api_key'))
                ->get(config('sumup.checkout_url') . '/' . $checkoutId);

            $data = $response->json();

            if(isset($data['status']) && $data['status'] === 'processed') {
                // Paiement réussi
                $order->status = 'paid';
                $order->payment_details = array_merge($order->payment_details ?? [], [
                    'sumup_response' => $data,
                    'paid_at' => now()->toISOString()
                ]);
                $order->save();

                session()->forget('sumup_checkout_reference');

                return response()->json(['status' => 'success', 'redirect' => route('purchase.success')]);
            } else {
                return response()->json(['status' => 'error', 'message' => 'Paiement non confirmé'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Erreur confirmation paiement SumUp : '.$e->getMessage());
            return response()->json(['error' => 'Impossible de vérifier le paiement'], 500);
        }
    }

    private function getPayPalAccessToken()
    {
        $response = Http::withBasicAuth(
            config('paypal.client_id'),
            config('paypal.secret')
        )->asForm()->post(config('paypal.base_url') . '/v1/oauth2/token', [
            'grant_type' => 'client_credentials'
        ]);

        return $response['access_token'];
    }

    public function createPayPalOrder()
    {
        $accessToken = $this->getPayPalAccessToken();
        
        // Récupérer la commande
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return response()->json(['error' => 'Commande non trouvée'], 404);
        }

        $response = Http::withToken($accessToken)
            ->post(config('paypal.base_url') . '/v2/checkout/orders', [
                "intent" => "CAPTURE",
                "purchase_units" => [[
                    "reference_id" => $order->order_id,
                    "amount" => [
                        "currency_code" => $order->currency,
                        "value" => (string)$order->amount
                    ]
                ]]
            ]);

        $data = $response->json();
        
        // Mettre à jour la commande avec l'ID de commande PayPal
        if (isset($data['id'])) {
            $order->payment_details = array_merge($order->payment_details ?? [], [
                'paypal_order_id' => $data['id'],
                'paypal_order_created_at' => now()->toISOString()
            ]);
            $order->save();
        }

        return response()->json($data);
    }

    public function capturePayPalOrder(Request $request)
    {
        $accessToken = $this->getPayPalAccessToken();
        
        // Récupérer la commande
        $orderId = Session::get('order_id');
        $order = Order::where('order_id', $orderId)->first();
        
        if (!$order) {
            return response()->json(['error' => 'Commande non trouvée'], 404);
        }

        $response = Http::withToken($accessToken)
            ->post(config('paypal.base_url') . "/v2/checkout/orders/{$request->orderID}/capture");

        $data = $response->json();

        if(isset($data['status']) && $data['status'] === "COMPLETED") {
            // Mettre à jour le statut de la commande
            $order->status = 'paid';
            $order->payment_details = array_merge($order->payment_details ?? [], [
                'paypal_capture_response' => $data,
                'paid_at' => now()->toISOString()
            ]);
            $order->save();

            return response()->json(['status' => 'success', 'redirect' => route('purchase.success')]);
        }

        return response()->json(['status' => 'error', 'message' => 'Paiement PayPal non complété'], 400);
    }
}