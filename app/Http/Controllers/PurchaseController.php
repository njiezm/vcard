<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Http;


class PurchaseController extends Controller
{
    /**
     * Affiche la page d'achat avec le choix du paiement.
     */
    public function show()
    {
        // Définir les prix par devise pour éviter les erreurs "Undefined array key"
        $prices = [
            'EUR' => 29.99,
            'USD' => 32.99,
            'GBP' => 25.99,
            'CHF' => 30.99,
            'CAD' => 41.99,
        ];

        // Simuler une commande pour la référence
        $order = [
            'id' => 'ORD-' . strtoupper(uniqid()),
            'email' => Session::get('user_email', 'client@digitcard.com'),
        ];

        return view('purchase', compact('prices', 'order'));
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

        // Ici, vous devriez vérifier le statut du paiement auprès de SumUp
        // avec la référence pour être 100% sûr.
        // Pour cet exemple, nous considérons que le paiement est réussi.

        // Logique métier : Créer la commande, envoyer un email, etc.
        Log::info("Paiement SumUp confirmé pour la référence : " . $checkoutReference);

        // Nettoyer la session
        session()->forget('sumup_checkout_reference');

        return response()->json(['message' => 'Paiement confirmé avec succès.']);
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