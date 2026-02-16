<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virement bancaire - DIGITCARD Elite</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <!-- Fonts et CSS (identique à votre code original) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Copiez tout le CSS de votre fichier original ici */
        :root { --obsidian-dark: #050505; --obsidian-card: #0c0c0c; --obsidian-border: #1a1a1a; --primary: #6366f1; --primary-glow: rgba(99, 102, 241, 0.2); --text-primary: #ffffff; --text-secondary: #94a3b8; --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1); }
        body { background-color: var(--obsidian-dark); color: var(--text-primary); font-family: 'Inter', sans-serif; margin: 0; overflow-x: hidden; }
        h1, h2, h3, .font-outfit { font-family: 'Outfit', sans-serif; }
        .bg-glow { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 50%), radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 50%); z-index: -1; }
        .grid-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-image: radial-gradient(var(--obsidian-border) 1px, transparent 1px); background-size: 40px 40px; opacity: 0.2; z-index: -1; }
        .payment-container { padding: 80px 40px; max-width: 600px; margin: 0 auto; }
        .payment-card { background: var(--obsidian-card); border: 1px solid var(--obsidian-border); padding: 48px; border-radius: 32px; }
        .bank-info { background: rgba(255, 255, 255, 0.05); border: 1px solid var(--obsidian-border); border-radius: 16px; padding: 24px; margin: 24px 0; }
        .bank-info-item { display: flex; justify-content: space-between; align-items: center; padding: 12px 0; border-bottom: 1px solid var(--obsidian-border); }
        .bank-info-item:last-child { border-bottom: none; }
        .bank-info-label { color: var(--text-secondary); font-size: 14px; }
        .bank-info-value { font-weight: 600; font-family: 'monospace'; }
        .copy-button { background: rgba(99, 102, 241, 0.1); border: 1px solid var(--primary); color: var(--primary); padding: 8px 16px; border-radius: 8px; font-size: 12px; cursor: pointer; transition: var(--transition); }
        .copy-button:hover { background: rgba(99, 102, 241, 0.2); }
        .info-box { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 12px; padding: 16px; margin: 24px 0; }
        .info-box-title { color: #10b981; font-weight: 600; margin-bottom: 8px; display: flex; align-items: center; gap: 8px; }
        .steps-list { list-style: none; padding: 0; margin: 16px 0; }
        .step-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 16px; }
        .step-number { width: 24px; height: 24px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 700; flex-shrink: 0; }
        .btn-primary { background: white; color: black; padding: 16px 36px; border-radius: 12px; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 10px; width: 100%; justify-content: center; }
        .btn-primary:hover { background-color: var(--primary); color: white; transform: translateY(-3px); box-shadow: 0 15px 30px var(--primary-glow); }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="grid-overlay"></div>

    <div class="payment-container">
        <div class="payment-card">
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-green-500/10 text-green-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-university text-2xl"></i>
                </div>
                <h1 class="font-outfit text-2xl font-bold mb-2">Virement bancaire</h1>
                <p class="text-slate-400">Effectuez un virement pour finaliser votre achat</p>
            </div>

            <div class="bank-info">
                <div class="bank-info-item">
                    <span class="bank-info-label">Bénéficiaire</span>
                    <div class="flex items-center gap-2">
                        <span class="bank-info-value">DIGITCARD ELITE SYSTEM</span>
                        <button class="copy-button" onclick="copyToClipboard('DIGITCARD ELITE SYSTEM')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="bank-info-item">
                    <span class="bank-info-label">IBAN</span>
                    <div class="flex items-center gap-2">
                        <span class="bank-info-value">FR76 3000 6000 0123 4567 8901 234</span>
                        <button class="copy-button" onclick="copyToClipboard('FR76 3000 6000 0123 4567 8901 234')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="bank-info-item">
                    <span class="bank-info-label">BIC</span>
                    <div class="flex items-center gap-2">
                        <span class="bank-info-value">BNPAFRPP</span>
                        <button class="copy-button" onclick="copyToClipboard('BNPAFRPP')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
                <div class="bank-info-item">
                    <span class="bank-info-label">Montant</span>
                    <span class="bank-info-value">29.99 €</span>
                </div>
                <div class="bank-info-item">
                    <span class="bank-info-label">Référence</span>
                    <div class="flex items-center gap-2">
                        <span class="bank-info-value">DC-{{ strtoupper(substr(md5($order['email'] ?? 'default@email.com'), 0, 8)) }}</span>
                        <button class="copy-button" onclick="copyToClipboard('DC-{{ strtoupper(substr(md5($order['email'] ?? 'default@email.com'), 0, 8)) }}')">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="info-box">
                <div class="info-box-title">
                    <i class="fas fa-info-circle"></i>
                    <span>Instructions importantes</span>
                </div>
                <p class="text-sm text-slate-300 mb-4">
                    Veuillez suivre ces étapes pour finaliser votre commande :
                </p>
                <ul class="steps-list">
                    <li class="step-item">
                        <div class="step-number">1</div>
                        <div class="text-sm">
                            Effectuez un virement de <strong>29.99 €</strong> vers le compte bancaire ci-dessus
                        </div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">2</div>
                        <div class="text-sm">
                            Indiquez obligatoirement la référence <strong>DC-{{ strtoupper(substr(md5($order['email'] ?? 'default@email.com'), 0, 8)) }}</strong> dans votre virement
                        </div>
                    </li>
                    <li class="step-item">
                        <div class="step-number">3</div>
                        <div class="text-sm">
                            Le délai de traitement est de <strong>2-3 jours ouvrés</strong> après réception du virement
                        </div>
                    </li>
                </ul>
            </div>

            <form action="{{ route('payment.confirm.bank_transfer') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check mr-2"></i>
                    J'ai effectué le virement
                </button>
            </form>

            <div class="text-center mt-6">
                <a href="{{ route('purchase') }}" class="text-sm text-slate-400 hover:text-white transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux options de paiement
                </a>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                const notification = document.createElement('div');
                notification.className = 'fixed bottom-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.innerHTML = '<i class="fas fa-check mr-2"></i>Copié !';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            });
        }
    </script>
</body>
</html>