<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Payer par carte - DIGITCARD Elite</title>
<link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<script src="https://cdn.tailwindcss.com"></script>

<style>
:root {
    --obsidian-dark:#050505;
    --obsidian-card:#0c0c0c;
    --obsidian-border:#1a1a1a;
    --primary:#6366f1;
    --primary-glow:rgba(99,102,241,0.2);
    --transition:all .3s cubic-bezier(.23,1,.32,1);
}
body{
    background:var(--obsidian-dark);
    color:white;
    font-family:'Inter',sans-serif;
}
h1{font-family:'Outfit',sans-serif}

.payment-container{
    padding:80px 20px;
    max-width:600px;
    margin:auto;
}

.payment-card{
    background:var(--obsidian-card);
    border:1px solid var(--obsidian-border);
    padding:48px;
    border-radius:28px;
}

.btn-sumup{
    width:100%;
    padding:16px;
    border-radius:14px;
    font-weight:600;
    background:linear-gradient(135deg,#6366f1,#4f46e5);
    color:white;
    transition:var(--transition);
    display:flex;
    justify-content:center;
    align-items:center;
    gap:10px;
}
.btn-sumup:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px var(--primary-glow);
}
.btn-sumup:disabled{
    opacity:.6;
    cursor:not-allowed;
}

.loading-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.85);
    display:none;
    align-items:center;
    justify-content:center;
    z-index:9999;
    flex-direction:column;
}

.spinner{
    border:4px solid rgba(255,255,255,.1);
    border-top:4px solid white;
    border-radius:50%;
    width:40px;
    height:40px;
    animation:spin 1s linear infinite;
}
@keyframes spin{
    0%{transform:rotate(0deg)}
    100%{transform:rotate(360deg)}
}

#sumup-card-button{
    margin-top:20px;
}
</style>
</head>

<body>

<div class="loading-overlay" id="loader">
    <div class="spinner"></div>
    <p class="mt-4 text-sm text-slate-300">Initialisation sécurisée du paiement…</p>
</div>

<div class="payment-container">
    <div class="payment-card">

        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-purple-500/10 text-purple-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <h1 class="text-2xl font-bold mb-2">Paiement sécurisé</h1>
            <p class="text-slate-400 text-sm">
                Paiement traité via SumUp (3D Secure activé)
            </p>
        </div>

        <div class="bg-white/5 border border-slate-700 rounded-xl p-4 mb-6">
            <div class="flex justify-between items-center">
                <span class="text-slate-400">DIGITCARD Elite</span>
                <span class="text-2xl font-bold">{{ number_format($amount, 2) }} €</span>
            </div>
        </div>

        <!-- Bouton amélioré -->
        <button id="sumup-button" class="btn-sumup" type="button">
            <i class="fas fa-lock"></i>
            <span>Payer maintenant</span>
        </button>

        <!-- Widget monté ici -->
        <div id="sumup-card-button"></div>

        <div id="sumup-status" class="text-center text-sm mt-4 text-slate-400"></div>

        <div class="text-center mt-6">
            <a href="{{ route('purchase') }}" class="text-sm text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>

    </div>
</div>

<script src="https://gateway.sumup.com/gateway/ecom/card/v2/sdk.js"></script>

<script>
const button = document.getElementById('sumup-button');
const loader = document.getElementById('loader');
const statusDiv = document.getElementById('sumup-status');

let isProcessing = false;

button.addEventListener('click', async () => {

    if (isProcessing) return; // anti double click
    isProcessing = true;

    button.disabled = true;
    loader.style.display = "flex";
    statusDiv.textContent = "";

    try {

        const response = await fetch("{{ route('payment.sumup.create') }}", {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error("Erreur serveur");

        const data = await response.json();

        if (!data.checkout_id) throw new Error("Checkout invalide");

        loader.style.display = "none";

        SumUpCard.mount({
            id: 'sumup-card-button',
            checkoutId: data.checkout_id,
            showInstallments: false,
            onResponse: function (type, body) {

                if (type === 'success') {
                    loader.style.display = "flex";
                    statusDiv.textContent = "Paiement validé. Redirection…";
                    window.location.href = "{{ route('purchase.success') }}";
                }

                if (type === 'error') {
                    statusDiv.textContent = "Erreur de paiement. Réessayez.";
                    button.disabled = false;
                    isProcessing = false;
                }
            }
        });

    } catch (error) {
        console.error(error);
        loader.style.display = "none";
        statusDiv.textContent = "Impossible d'initialiser le paiement.";
        button.disabled = false;
        isProcessing = false;
    }
});
</script>

</body>
</html>
