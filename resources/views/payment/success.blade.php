<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement réussi - DIGITCARD Elite</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    
    <!-- Fonts et styles similaires -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        /* Réutiliser les styles des pages précédentes */
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

        .success-container {
            padding: 80px 40px;
            max-width: 600px;
            margin: 0 auto;
            text-align: center;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 24px;
        }

        .success-icon i {
            color: #10b981;
            font-size: 36px;
        }

        .btn-primary {
            background: white;
            color: black;
            padding: 16px 36px;
            border-radius: 12px;
            font-weight: 700;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            margin: 8px;
        }

        .btn-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 16px 36px;
            border-radius: 12px;
            font-weight: 700;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            border: 1px solid var(--obsidian-border);
            margin: 8px;
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-3px);
        }

        .info-box {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--obsidian-border);
            border-radius: 16px;
            padding: 24px;
            margin: 32px 0;
            text-align: left;
        }

        .info-box-title {
            font-weight: 600;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .next-steps {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .next-step-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 16px;
        }

        .step-icon {
            width: 24px;
            height: 24px;
            background: var(--primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
        }

        @media (max-width: 768px) {
            .success-container {
                padding: 40px 20px;
            }
            
            .btn-primary, .btn-secondary {
                width: 100%;
                margin: 8px 0;
            }
        }
    </style>
</head>
<body>
    <div class="bg-glow"></div>
    <div class="grid-overlay"></div>

    <div class="success-container">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>
        
        <h1 class="font-outfit text-3xl font-bold mb-4">
            @if(session('bank_transfer'))
                Virement enregistré
            @else
                Paiement réussi
            @endif
        </h1>
        
        <p class="text-slate-400 mb-8">
            @if(session('bank_transfer'))
                Merci ! Votre virement a bien été enregistré. Nous vous enverrons un email dès que votre paiement sera validé (2-3 jours ouvrés).
            @else
                Merci pour votre achat ! Votre paiement a été traité avec succès.
            @endif
        </p>

        <div class="info-box">
            <div class="info-box-title">
                <i class="fas fa-rocket"></i>
                <span>Prochaines étapes</span>
            </div>
            <ul class="next-steps">
                <li class="next-step-item">
                    <div class="step-icon">1</div>
                    <div>
                        <strong>Confirmation email</strong><br>
                        <span class="text-sm text-slate-400">
                            Vous recevrez un email de confirmation dans les prochaines minutes.
                        </span>
                    </div>
                </li>
                <li class="next-step-item">
                    <div class="step-icon">2</div>
                    <div>
                        <strong>Création de votre DIGITCARD</strong><br>
                        <span class="text-sm text-slate-400">
                            @if(session('bank_transfer'))
                                Dès validation de votre virement, nous créerons votre DIGITCARD personnalisée.
                            @else
                                Nous créons actuellement votre DIGITCARD personnalisée.
                            @endif
                        </span>
                    </div>
                </li>
                <li class="next-step-item">
                    <div class="step-icon">3</div>
                    <div>
                        <strong>Accès à votre espace</strong><br>
                        <span class="text-sm text-slate-400">
                            Vous recevrez un lien pour accéder à votre tableau de bord et personnaliser votre carte.
                        </span>
                    </div>
                </li>
            </ul>
        </div>

        <div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
            <a href="{{ route('home') }}" class="btn-primary">
                <i class="fas fa-home mr-2"></i>
                Retour à l'accueil
            </a>
            <a href="mailto:support@digitcard.com" class="btn-secondary">
                <i class="fas fa-headset mr-2"></i>
                Contacter le support
            </a>
        </div>
    </div>
</body>
</html>