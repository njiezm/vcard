<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;


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
    public function processPurchase(Request $request)
{
    $validator = Validator::make($request->all(), [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'currency' => 'required|string|in:EUR,USD,GBP,CHF,CAD',
        'amount' => 'required|numeric|min:0',
        'payment_method' => 'required|string|in:bank_transfer,sumup,paypal'
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    // Créer une commande en statut "pending"
    $order = \App\Models\Order::create([
        'order_id' => 'ORD-' . strtoupper(Str::random(10)),
        'firstname' => $request->firstname,
        'lastname' => $request->lastname,
        'email' => $request->email,
        'amount' => $request->amount,
        'currency' => $request->currency,
        'payment_method' => $request->payment_method,
        'status' => 'pending',
        'payment_details' => [
            'created_at' => now()->toISOString(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]
    ]);

    // Stocker l'ID de commande en session
    Session::put('order_id', $order->order_id);

    // Rediriger vers la méthode de paiement
    switch ($request->payment_method) {
        case 'bank_transfer':
            return redirect()->route('payment.bank_transfer');
        case 'sumup':
            return redirect()->route('payment.sumup');
        case 'paypal':
            return redirect()->route('payment.paypal');
        default:
            return redirect()->back()->with('error', 'Méthode de paiement non valide');
    }
}
    /**
     * Affiche la page d'instructions pour le virement bancaire.
     */
    public function showBankTransfer()
    {
        // Simuler une commande pour la référence de paiement
        $order = [
            'email' => Session::get('user_email', 'client@digitcard.com'),
        ];
        
        return view('payment.bank_transfer', compact('order'));
    }

    /**
     * Traite la confirmation du virement bancaire (logique à implémenter).
     */
    public function confirmBankTransfer(Request $request)
    {
        // Logique pour enregistrer que l'utilisateur a confirmé le virement
        // Par exemple, envoyer un email de notification, changer le statut de la commande en BDD, etc.
        
        return redirect()->route('purchase.success')->with('status', 'Votre confirmation de virement a bien été enregistrée. Nous traiterons votre paiement dès sa réception.');
    }
    
    // Vous pouvez ajouter une page de succès
    public function showSuccess() {
        return view('payment.success');
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
        // Dans une vraie application, vous récupéreriez ces infos depuis une session ou une base de données
        $orderDetails = [
            'amount' => 29.99,
            'currency' => 'EUR',
            'email' => 'client@example.com', 
        ];

        return view('payment.payment_sumup', $orderDetails);
    }

    /**
     * Crée un checkout ID SumUp via une requête API sécurisée.
     */
    public function createSumUpCheckout(Request $request)
    {
        $amount = 29.99; 
        $checkoutReference = 'DC-' . uniqid(); // Référence unique pour la transaction

        try {
            $response = Http::withToken(config('sumup.api_key'))
                ->post(config('sumup.checkout_url'), [
                    'amount' => $amount,
                    'currency' => 'EUR',
                    'checkout_reference' => $checkoutReference,
                    'pay_to_email' => 'njiezamon10@gmail.com', 
                    'description' => 'Achat DIGITCARD Elite',
                ]);

            $data = $response->json();

           if ($response->successful() && isset($data['id'])) {
               
                // Stockez la référence en session pour la confirmation ultérieure
                session(['sumup_checkout_reference' => $checkoutReference]);
                return response()->json(['checkout_id' => $data['id']]);
            } else {
                Log::error('Erreur création checkout SumUp: ' . $response->body());
                return response()->json([
    'status' => $response->status(),
    'body' => $response->body(),
]);

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

    // Vérifier le paiement via l'API SumUp
    try {
        $response = Http::withToken(config('sumup.api_key'))
            ->get(config('sumup.checkout_url') . '/' . $checkoutReference);

        $data = $response->json();

        if(isset($data['status']) && $data['status'] === 'processed') {
            // Paiement réussi
            $orderId = Session::get('order_id');
            $order = Order::where('order_id', $orderId)->first();

            if($order){
                $order->status = 'paid';
                $order->payment_details = array_merge($order->payment_details ?? [], [
                    'sumup_response' => $data,
                    'paid_at' => now()->toISOString()
                ]);
                $order->save();
            }

            session()->forget('sumup_checkout_reference');

            return response()->json(['status' => 'success']);
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

    $response = Http::withToken($accessToken)
        ->post(config('paypal.base_url') . '/v2/checkout/orders', [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "amount" => [
                    "currency_code" => "EUR",
                    "value" => "29.99"
                ]
            ]]
        ]);

    return response()->json($response->json());
}

public function capturePayPalOrder(Request $request)
{
    $accessToken = $this->getPayPalAccessToken();

    $response = Http::withToken($accessToken)
        ->post(config('paypal.base_url') . "/v2/checkout/orders/{$request->orderID}/capture");

    $data = $response->json();

    if(isset($data['status']) && $data['status'] === "COMPLETED") {

        // Ici tu mets :
        // - création commande BDD
        // - email
        // - etc

        return response()->json(['status' => 'success']);
    }

    return response()->json(['status' => 'error'], 400);
}

}