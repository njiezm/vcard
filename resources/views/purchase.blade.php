<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acheter DIGITCARD Elite</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        :root {
            --obsidian-dark: #050505;
            --obsidian-card: #0c0c0c;
            --obsidian-border: #1a1a1a;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.2);
            --text-primary: #ffffff;
            --text-secondary: #94a3b8;
            --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        body {
            background-color: var(--obsidian-dark);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            overflow-x: hidden;
        }

        h1, h2, h3, .font-outfit { font-family: 'Outfit', sans-serif; }

        /* Background Effects */
        .bg-glow {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 10% 20%, rgba(99, 102, 241, 0.08) 0%, transparent 50%),
                radial-gradient(circle at 90% 80%, rgba(99, 102, 241, 0.05) 0%, transparent 50%);
            z-index: -1;
        }

        .grid-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(var(--obsidian-border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.2;
            z-index: -1;
        }

        /* Navigation */
        nav {
            padding: 20px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.2rem;
            letter-spacing: -0.04em;
        }

        .logo-square {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border-radius: 8px;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        /* Purchase Container */
        .purchase-container {
            padding: 40px 24px 60px;
            max-width: 1400px;
            margin: 0 auto;
        }

        .purchase-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        /* Product Showcase */
        .product-showcase {
            background: var(--obsidian-card);
            border: 1px solid var(--obsidian-border);
            border-radius: 24px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .product-showcase::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, var(--primary-glow) 0%, transparent 70%);
            opacity: 0.1;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .phone-mockup {
            position: relative;
            width: 100%;
            max-width: 320px;
            margin: 0 auto 40px;
            transform: perspective(1000px) rotateY(-10deg);
            transition: transform 0.6s ease;
        }

        .phone-mockup:hover {
            transform: perspective(1000px) rotateY(0deg);
        }

        .phone-frame {
            background: linear-gradient(145deg, #2a2a2a, #1a1a1a);
            border-radius: 40px;
            padding: 12px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
        }

        .phone-screen {
            background: linear-gradient(135deg, #1e1e1e, #0a0a0a);
            border-radius: 30px;
            aspect-ratio: 9/19.5;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .digitcard-preview {
            width: 80%;
            height: 50%;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        .features-list {
            list-style: none;
            padding: 0;
            margin: 30px 0;
        }

        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid var(--obsidian-border);
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            width: 32px;
            height: 32px;
            background: rgba(99, 102, 241, 0.1);
            border: 1px solid var(--primary);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        /* Form Card */
        .form-card {
            background: var(--obsidian-card);
            border: 1px solid var(--obsidian-border);
            padding: 40px;
            border-radius: 24px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: var(--text-secondary);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--obsidian-border);
            border-radius: 12px;
            color: var(--text-primary);
            font-size: 15px;
            transition: var(--transition);
        }

        .form-input:focus {
            outline: none;
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .form-input::placeholder {
            color: var(--text-secondary);
            opacity: 0.5;
        }

        /* Currency Selector */
        .currency-selector {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
            gap: 12px;
            margin-bottom: 24px;
        }

        .currency-option {
            padding: 16px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--obsidian-border);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .currency-option:hover {
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        .currency-option.active {
            background: rgba(99, 102, 241, 0.1);
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }

        .currency-value {
            font-size: 1.5rem;
            font-weight: 800;
            margin-top: 4px;
        }

        /* Payment Methods */
        .payment-method {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--obsidian-border);
            border-radius: 16px;
            margin-bottom: 12px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            color: inherit;
        }

        .payment-method:hover {
            border-color: var(--primary);
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .payment-method-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .payment-method-content {
            flex: 1;
        }

        .payment-method-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 4px;
        }

        .payment-method-desc {
            font-size: 13px;
            color: var(--text-secondary);
        }

        /* Price Display */
        .price-section {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(99, 102, 241, 0.05));
            border: 1px solid var(--primary);
            border-radius: 16px;
            padding: 24px;
            margin-bottom: 24px;
            text-align: center;
        }

        .price-label {
            font-size: 14px;
            color: var(--text-secondary);
            margin-bottom: 8px;
        }

        .price-amount {
            font-size: 2.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Buttons */
        .btn-primary {
            background: white;
            color: black;
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 700;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            width: 100%;
            justify-content: center;
            font-size: 15px;
            cursor: pointer;
            border: none;
        }

        .btn-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .purchase-grid {
                grid-template-columns: 1fr;
                gap: 30px;
            }

            .product-showcase {
                padding: 30px;
            }

            .form-card {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            nav {
                padding: 16px 20px;
            }

            .logo-box {
                font-size: 1rem;
            }

            .purchase-container {
                padding: 30px 20px 50px;
            }

            .product-showcase {
                padding: 24px 20px;
            }

            .form-card {
                padding: 24px 20px;
            }

            .phone-mockup {
                max-width: 280px;
            }

            .price-amount {
                font-size: 2rem;
            }

            .payment-method {
                padding: 16px;
            }

            .payment-method-icon {
                width: 40px;
                height: 40px;
                font-size: 18px;
            }
        }

        @media (max-width: 480px) {
            .purchase-container {
                padding: 20px 16px 40px;
            }

            .product-showcase {
                padding: 20px 16px;
            }

            .form-card {
                padding: 20px 16px;
            }

            .phone-mockup {
                max-width: 240px;
            }

            .price-amount {
                font-size: 1.8rem;
            }

            .payment-method {
                padding: 12px;
                gap: 12px;
            }

            .payment-method-icon {
                width: 36px;
                height: 36px;
                font-size: 16px;
            }

            .payment-method-title {
                font-size: 14px;
            }

            .payment-method-desc {
                font-size: 12px;
            }

            .currency-option {
                padding: 12px;
            }

            .currency-value {
                font-size: 1.2rem;
            }

            .btn-primary {
                padding: 14px 20px;
                font-size: 14px;
            }
        }

        /* Form Validation */
        .form-input.error {
            border-color: #ef4444;
            box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
        }

        .error-message {
            color: #ef4444;
            font-size: 12px;
            margin-top: 4px;
            display: none;
        }

        .error-message.show {
            display: block;
        }

        /* Loading State */
        .loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
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
        <div class="purchase-grid">
            <!-- Product Showcase -->
            <div class="product-showcase">
                <div class="phone-mockup">
                    <div class="phone-frame">
                        <div class="phone-screen">
                            <div class="digitcard-preview">
                                <div class="text-center">
                                    <i class="fas fa-id-card text-4xl text-white mb-2"></i>
                                    <div class="text-white font-bold text-sm">DIGITCARD</div>
                                    <div class="text-white/80 text-xs">ELITE</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <h2 class="font-outfit text-2xl font-bold mb-4">Votre carte d'identité numérique</h2>
                <p class="text-slate-400 mb-6">Obtenez instantanément votre carte d'identité numérique sécurisée et vérifiée.</p>

                <ul class="features-list">
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <div>
                            <div class="font-semibold">Sécurité maximale</div>
                            <div class="text-sm text-slate-400">Protection de vos données personnelles</div>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <div>
                            <div class="font-semibold">Activation instantanée</div>
                            <div class="text-sm text-slate-400">Utilisez votre carte immédiatement</div>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <div>
                            <div class="font-semibold">Acceptation mondiale</div>
                            <div class="text-sm text-slate-400">Valide dans plus de 150 pays</div>
                        </div>
                    </li>
                    <li class="feature-item">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <div>
                            <div class="font-semibold">100% numérique</div>
                            <div class="text-sm text-slate-400">Accessible depuis votre smartphone</div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <h1 class="font-outfit text-2xl font-bold mb-6">Finaliser votre achat</h1>
                
                <form id="purchaseForm" action="{{ route('purchase.process') }}" method="POST">
                    @csrf
                    
                    <!-- User Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div class="form-group">
                            <label class="form-label" for="firstname">Prénom *</label>
                            <input type="text" id="firstname" name="firstname" class="form-input" placeholder="Jean" required>
                            <div class="error-message">Veuillez entrer votre prénom</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="lastname">Nom *</label>
                            <input type="text" id="lastname" name="lastname" class="form-input" placeholder="Dupont" required>
                            <div class="error-message">Veuillez entrer votre nom</div>
                        </div>
                    </div>

                    <div class="form-group mb-6">
                        <label class="form-label" for="email">Adresse email *</label>
                        <input type="email" id="email" name="email" class="form-input" placeholder="jean.dupont@example.com" required>
                        <div class="error-message">Veuillez entrer une adresse email valide</div>
                    </div>

                    <!-- Price Section -->
                    <div class="price-section">
                        <div class="price-label">Total à payer</div>
                        <div class="price-amount">
                            <span class="currency-symbol">€</span>
                            <span class="currency-value" id="price-amount">29.99</span>
                        </div>
                    </div>

                    <!-- Currency Selector -->
                    <div class="form-group">
                        <label class="form-label">Choisissez votre devise</label>
                        <div class="currency-selector">
                            @foreach($prices as $currency => $price)
                                <div class="currency-option {{ $currency == 'EUR' ? 'active' : '' }}" data-currency="{{ $currency }}" data-price="{{ $price }}">
                                    <div>{{ $currency }}</div>
                                    <div class="currency-value">{{ number_format($price, 2) }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="form-group">
                        <label class="form-label">Méthode de paiement</label>
                        
                        <a href="{{ route('payment.bank_transfer') }}" class="payment-method" onclick="setPaymentMethod('bank_transfer')">
                            <div class="payment-method-icon bg-blue-500/10 text-blue-500">
                                <i class="fas fa-university"></i>
                            </div>
                            <div class="payment-method-content">
                                <div class="payment-method-title">Virement bancaire</div>
                                <div class="payment-method-desc">Paiement sécurisé via votre banque (2-3 jours ouvrés)</div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-500"></i>
                        </a>

                        <a href="{{ route('payment.sumup') }}" class="payment-method" onclick="setPaymentMethod('sumup')">
                            <div class="payment-method-icon bg-purple-500/10 text-purple-500">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="payment-method-content">
                                <div class="payment-method-title">Carte bancaire</div>
                                <div class="payment-method-desc">Paiement immédiat et sécurisé via SumUp</div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-500"></i>
                        </a>

                        <a href="{{ route('payment.paypal') }}" class="payment-method" onclick="setPaymentMethod('paypal')">
                            <div class="payment-method-icon bg-yellow-500/10 text-yellow-500">
                                <i class="fab fa-paypal"></i>
                            </div>
                            <div class="payment-method-content">
                                <div class="payment-method-title">PayPal</div>
                                <div class="payment-method-desc">Payer en toute sécurité avec votre compte PayPal</div>
                            </div>
                            <i class="fas fa-chevron-right text-slate-500"></i>
                        </a>
                    </div>

                    <input type="hidden" name="payment_method" id="payment_method" value="">
                    <input type="hidden" name="currency" id="currency" value="EUR">
                    <input type="hidden" name="amount" id="amount" value="29.99">
                </form>

                <div class="mt-6 p-4 bg-green-500/10 border border-green-500/20 rounded-lg">
                    <div class="flex items-center gap-2 text-green-500 text-sm">
                        <i class="fas fa-lock"></i>
                        <span>Paiement 100% sécurisé et crypté</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Currency switching
        const priceElement = document.getElementById('price-amount');
        const currencySymbolElement = document.querySelector('.currency-symbol');
        const currencyOptions = document.querySelectorAll('.currency-option');
        const symbols = { 
            'EUR': '€', 
            'USD': '$', 
            'GBP': '£', 
            'CHF': 'CHF ', 
            'CAD': 'CAD ' 
        };

        currencyOptions.forEach(option => {
            option.addEventListener('click', function() {
                currencyOptions.forEach(opt => opt.classList.remove('active'));
                this.classList.add('active');

                const price = this.dataset.price;
                const currency = this.dataset.currency;

                priceElement.textContent = parseFloat(price).toFixed(2);
                currencySymbolElement.textContent = symbols[currency] || '€';
                
                // Update hidden inputs
                document.getElementById('currency').value = currency;
                document.getElementById('amount').value = price;
            });
        });

        // Form validation
        const form = document.getElementById('purchaseForm');
        const inputs = form.querySelectorAll('.form-input');

        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateInput(this);
            });

            input.addEventListener('input', function() {
                if (this.classList.contains('error')) {
                    validateInput(this);
                }
            });
        });

        function validateInput(input) {
            const errorMessage = input.nextElementSibling;
            
            if (input.hasAttribute('required') && !input.value.trim()) {
                input.classList.add('error');
                errorMessage.classList.add('show');
                return false;
            }
            
            if (input.type === 'email' && input.value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(input.value)) {
                    input.classList.add('error');
                    errorMessage.classList.add('show');
                    return false;
                }
            }
            
            input.classList.remove('error');
            errorMessage.classList.remove('show');
            return true;
        }

        // Payment method selection
        function setPaymentMethod(method) {
            document.getElementById('payment_method').value = method;
            
            // Store form data in session storage before redirect
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });
            sessionStorage.setItem('purchaseFormData', JSON.stringify(data));
        }

        // Restore form data on page load
        window.addEventListener('DOMContentLoaded', function() {
            const savedData = sessionStorage.getItem('purchaseFormData');
            if (savedData) {
                const data = JSON.parse(savedData);
                Object.keys(data).forEach(key => {
                    const input = document.getElementById(key);
                    if (input) {
                        input.value = data[key];
                    }
                });
            }
        });

        // Add touch feedback for mobile
        document.querySelectorAll('button, .payment-method, .currency-option').forEach(element => {
            element.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });
            element.addEventListener('touchend', function() {
                this.style.transform = '';
            });
        });
    </script>
</body>
</html>