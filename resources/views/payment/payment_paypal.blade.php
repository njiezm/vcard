<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payer avec PayPal - DIGITCARD Elite</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root { --obsidian-dark:#050505; --obsidian-card:#0c0c0c; --obsidian-border:#1a1a1a; --primary:#6366f1; }
        body { background:#050505; color:white; font-family:'Inter',sans-serif; margin:0 }
        .payment-container { padding:80px 40px; max-width:600px; margin:0 auto }
        .payment-card { background:#0c0c0c; border:1px solid #1a1a1a; padding:48px; border-radius:32px }
        .loading-overlay {
            position:fixed; inset:0; background:rgba(0,0,0,0.8);
            display:none; align-items:center; justify-content:center;
            z-index:9999; flex-direction:column;
        }
        .spinner {
            width:50px; height:50px;
            border:5px solid rgba(255,255,255,0.2);
            border-top:5px solid white;
            border-radius:50%;
            animation:spin 1s linear infinite;
        }
        @keyframes spin { to { transform:rotate(360deg); } }
    </style>
</head>
<body>

<div class="loading-overlay" id="loader">
    <div class="spinner mb-4"></div>
    <p>Traitement du paiement sécurisé...</p>
</div>

<div class="payment-container">
    <div class="payment-card">

        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-2">Paiement sécurisé PayPal</h1>
            <p class="text-slate-400">Vous allez être redirigé vers PayPal.</p>
        </div>

        <div class="bg-white/5 border border-slate-700 rounded-xl p-4 mb-6">
            <div class="flex justify-between items-center">
                <span>Total à payer</span>
                <span class="text-2xl font-bold">{{ number_format(29.99,2) }} €</span>
            </div>
        </div>

        <div id="paypal-button-container"></div>

    </div>
</div>

<!-- SDK PayPal -->
<script src="https://www.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=EUR"></script>

<script>
const loader = document.getElementById('loader');

paypal.Buttons({

    style: {
        layout: 'vertical',
        color: 'gold',
        shape: 'rect',
        label: 'paypal'
    },

    async createOrder() {

        loader.style.display = "flex";

        const response = await fetch("{{ route('paypal.create') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            }
        });

        const data = await response.json();
        loader.style.display = "none";

        if(data.id){
            return data.id;
        } else {
            alert("Erreur création commande.");
        }
    },

    async onApprove(data) {

        loader.style.display = "flex";

        const response = await fetch("{{ route('paypal.capture') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content")
            },
            body: JSON.stringify({
                orderID: data.orderID
            })
        });

        const result = await response.json();
        loader.style.display = "none";

        if(result.status === "success"){
            window.location.href = "{{ route('purchase.success') }}";
        } else {
            alert("Paiement refusé.");
        }
    },

    onError(err){
        loader.style.display = "none";
        alert("Erreur PayPal.");
        console.error(err);
    }

}).render('#paypal-button-container');
</script>

</body>
</html>
