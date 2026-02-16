<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acheter DIGITCARD Elite</title>
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
        nav { padding: 24px 40px; display: flex; justify-content: space-between; align-items: center; max-width: 1300px; margin: 0 auto; }
        .logo-box { display: flex; align-items: center; gap: 12px; font-weight: 800; font-size: 1.4rem; letter-spacing: -0.04em; }
        .logo-square { width: 32px; height: 32px; background: linear-gradient(135deg, var(--primary), #4f46e5); border-radius: 8px; box-shadow: 0 0 15px var(--primary-glow); }
        .purchase-container { padding: 80px 40px; max-width: 800px; margin: 0 auto; }
        .form-card { background: var(--obsidian-card); border: 1px solid var(--obsidian-border); padding: 48px; border-radius: 32px; }
        .price-display { font-size: 2rem; font-weight: 800; margin-bottom: 24px; }
        .payment-method { display: flex; align-items: center; gap: 12px; padding: 16px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--obsidian-border); border-radius: 12px; margin-bottom: 12px; cursor: pointer; transition: var(--transition); }
        .payment-method:hover { border-color: var(--primary); }
        .payment-method.selected { background: rgba(99, 102, 241, 0.1); border-color: var(--primary); }
        .btn-primary { background: white; color: black; padding: 16px 36px; border-radius: 12px; font-weight: 700; transition: var(--transition); display: inline-flex; align-items: center; gap: 10px; width: 100%; justify-content: center; }
        .btn-primary:hover { background-color: var(--primary); color: white; transform: translateY(-3px); box-shadow: 0 15px 30px var(--primary-glow); }
        .currency-selector { display: flex; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .currency-option { flex: 1; min-width: 100px; padding: 12px; background: rgba(255, 255, 255, 0.05); border: 1px solid var(--obsidian-border); border-radius: 12px; text-align: center; cursor: pointer; transition: var(--transition); }
        .currency-option:hover { border-color: var(--primary); }
        .currency-option.active { background: rgba(99, 102, 241, 0.1); border-color: var(--primary); }
        .currency-symbol { font-size: 1.5rem; font-weight: 300; }
        .currency-value { font-size: 1.8rem; font-weight: 800; }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="grid-overlay"></div>

    <nav>
        <div class="logo-box">
            <div class="logo-square"></div>
            <span class="font-outfit">DIGITCARD ELITE</span>
        </div>
        <div>
            <a href="{{ route('home') }}" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Retour
            </a>
        </div>
    </nav>

    <div class="purchase-container">
        <div class="form-card">
            <h1 class="font-outfit text-3xl font-bold mb-6">Finaliser votre achat</h1>
            
            <div class="price-display">
                <span class="currency-symbol">€</span>
                <span class="currency-value" id="price-amount">{{ $prices['EUR'] }}</span>
            </div>

            <div class="form-group">
                <label class="form-label text-slate-400 text-sm">Choisissez votre devise</label>
                <div class="currency-selector">
                    @foreach($prices as $currency => $price)
                        <div class="currency-option {{ $currency == 'EUR' ? 'active' : '' }}" data-currency="{{ $currency }}" data-price="{{ $price }}">
                            <div>{{ $currency }}</div>
                            <div class="text-sm text-slate-400">{{ number_format($price, 2) }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- ... (le reste du fichier purchase.blade.php reste inchangé) ... -->

            <div class="form-group">
                <label class="form-label text-slate-400 text-sm">Méthode de paiement</label>
                
                <!-- Option 1: Virement Bancaire -->
                <a href="{{ route('payment.bank_transfer') }}" class="payment-method">
                    <div class="w-10 h-10 bg-blue-500/10 text-blue-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-university"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold">Virement bancaire</div>
                        <div class="text-sm text-slate-400">Paiement sécurisé via votre banque (2-3 jours ouvrés)</div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-500"></i>
                </a>

                <!-- Option 2: Carte (SumUp) -->
                <a href="{{ route('payment.sumup') }}" class="payment-method">
                    <div class="w-10 h-10 bg-purple-500/10 text-purple-500 rounded-lg flex items-center justify-center">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold">Carte bancaire</div>
                        <div class="text-sm text-slate-400">Paiement immédiat et sécurisé via SumUp</div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-500"></i>
                </a>

                <!-- Option 3: PayPal -->
                <a href="{{ route('payment.paypal') }}" class="payment-method">
                    <div class="w-10 h-10 bg-yellow-500/10 text-yellow-500 rounded-lg flex items-center justify-center">
                        <i class="fab fa-paypal"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-semibold">PayPal</div>
                        <div class="text-sm text-slate-400">Payer en toute sécurité avec votre compte PayPal</div>
                    </div>
                    <i class="fas fa-chevron-right text-slate-500"></i>
                </a>
            </div>

<!-- ... (le reste du fichier purchase.blade.php reste inchangé) ... -->
        </div>
    </div>

    <script>
        // Script pour mettre à jour le prix quand on change de devise
        document.addEventListener('DOMContentLoaded', function() {
            const priceElement = document.getElementById('price-amount');
            const currencySymbolElement = document.querySelector('.currency-symbol');
            const currencyOptions = document.querySelectorAll('.currency-option');
            const symbols = { 'EUR': '€', 'USD': '$', 'GBP': '£', 'CHF': 'CHF ', 'CAD': 'CAD ' };

            currencyOptions.forEach(option => {
                option.addEventListener('click', function() {
                    // Retirer la classe 'active' de toutes les options
                    currencyOptions.forEach(opt => opt.classList.remove('active'));
                    // Ajouter la classe 'active' à l'option cliquée
                    this.classList.add('active');

                    const price = this.dataset.price;
                    const currency = this.dataset.currency;

                    priceElement.textContent = parseFloat(price).toFixed(2);
                    currencySymbolElement.textContent = symbols[currency] || '€';
                });
            });
        });
    </script>
</body>
</html>