<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>vCard Elite - La Carte de Visite Nouvelle Génération</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --obsidian-dark: #050505;
            --obsidian-card: #0c0c0c;
            --obsidian-border: #1a1a1a;
            --primary: #6366f1; /* Bleu Indigo */
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
            padding: 24px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            max-width: 1300px;
            margin: 0 auto;
        }

        .logo-box {
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 1.4rem;
            letter-spacing: -0.04em;
        }

        .logo-square {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border-radius: 8px;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        /* Hero Section */
        .hero {
            padding: 100px 40px;
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 80px;
        }

        .hero-content h1 {
            font-size: clamp(2.8rem, 6vw, 4.5rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.05em;
            margin-bottom: 24px;
        }

        .hero-content p {
            color: var(--text-secondary);
            font-size: 1.2rem;
            line-height: 1.6;
            margin-bottom: 40px;
            max-width: 580px;
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
        }

        .btn-primary:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        /* --- Floating Phone Mockup --- */
        .phone-container {
            perspective: 2000px;
            display: flex;
            justify-content: center;
            position: relative;
        }

        .phone-mockup {
            width: 300px;
            height: 610px;
            background: #000;
            border-radius: 48px;
            border: 10px solid #1a1a1a;
            box-shadow: 
                0 50px 100px -20px rgba(0,0,0,0.6), 
                0 0 20px rgba(99, 102, 241, 0.15);
            position: relative;
            overflow: hidden;
            animation: float 8s ease-in-out infinite;
            transform: rotateX(5deg) rotateY(-10deg) rotateZ(2deg);
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotateX(5deg) rotateY(-10deg) rotateZ(2deg); }
            50% { transform: translateY(-30px) rotateX(8deg) rotateY(5deg) rotateZ(-1deg); }
        }

        /* --- Recreating the vCard inside Phone --- */
        .phone-screen {
            width: 100%;
            height: 100%;
            background: #0f172a; /* Same as vCard bg */
            overflow-y: auto;
            position: relative;
            padding: 0;
            scrollbar-width: none;
        }
        .phone-screen::-webkit-scrollbar { display: none; }

        .phone-island {
            position: sticky; top: 12px; left: 50%;
            transform: translateX(-50%);
            width: 90px; height: 26px;
            background: #000; border-radius: 20px;
            z-index: 10;
        }

        .vcard-preview-header {
            height: 120px;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            position: relative;
        }

        .vcard-preview-card {
            margin: -60px 15px 0;
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 25px 15px 20px;
            text-align: center;
        }

        .preview-avatar {
            width: 80px; height: 80px;
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            border-radius: 24px;
            margin: -65px auto 12px;
            border: 4px solid #0f172a;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 1.5rem; color: white;
        }

        .preview-socials {
            display: flex; justify-content: center; gap: 8px;
            margin-top: 15px; border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 15px;
        }

        .preview-social-icon {
            width: 32px; height: 32px;
            border-radius: 10px; background: rgba(255,255,255,0.05);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; border: 1px solid rgba(255,255,255,0.1);
        }

        .preview-contact-item {
            margin: 10px 15px; padding: 12px;
            background: rgba(255,255,255,0.03);
            border-radius: 14px;
            display: flex; align-items: center; gap: 10px;
            border: 1px solid rgba(255,255,255,0.05);
        }

        .preview-icon-box {
            width: 32px; height: 32px; border-radius: 10px;
            background: rgba(99, 102, 241, 0.1); color: var(--primary);
            display: flex; align-items: center; justify-content: center; font-size: 0.8rem;
        }

        /* Features Section */
        .features {
            padding: 80px 40px;
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 24px;
        }

        .feature-card {
            background: var(--obsidian-card);
            border: 1px solid var(--obsidian-border);
            padding: 48px;
            border-radius: 32px;
            transition: var(--transition);
        }

        .feature-card:hover {
            border-color: var(--primary);
            transform: translateY(-8px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
        }

        /* Footer */
        footer {
            border-top: 1px solid var(--obsidian-border);
            padding: 80px 40px;
            text-align: center;
        }

        .admin-btn {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.75rem;
            color: var(--text-secondary);
            border: 1px solid var(--obsidian-border);
            padding: 10px 20px;
            border-radius: 100px;
            text-decoration: none;
            transition: var(--transition);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .admin-btn:hover {
            color: var(--primary);
            border-color: var(--primary);
            background: var(--primary-glow);
        }

        @media (max-width: 1024px) {
            .hero { grid-template-columns: 1fr; text-align: center; }
            .hero-content { display: flex; flex-direction: column; align-items: center; order: 2; }
            .phone-container { order: 1; margin-bottom: 60px; }
        }
    </style>
</head>
<body>

    <div class="bg-glow"></div>
    <div class="grid-overlay"></div>

    <nav>
        <div class="logo-box">
            <div class="logo-square"></div>
            <span class="font-outfit">VCARD ELITE</span>
        </div>
        <div>
            <a href="{{ route('admin.login.page') }}" class="text-sm font-semibold text-slate-400 hover:text-white transition-colors">Client Space</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-slate-900 border border-slate-800 text-[10px] font-bold text-indigo-400 mb-6 uppercase tracking-widest">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                </span>
                Pro Connectivity System v2.0
            </div>
            <h1 class="font-outfit">L'élégance du <br><span class="text-indigo-500">networking</span> digital.</h1>
            <p>
                Propulsez votre identité professionnelle dans une nouvelle dimension. Une vCard intelligente, design et ultra-rapide pour convertir chaque rencontre en opportunité.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="#" class="btn-primary">
                    Démarrer maintenant <i class="fas fa-arrow-right text-xs"></i>
                </a>
                <a href="#features" class="px-8 py-4 rounded-xl border border-white/5 font-bold text-sm hover:bg-white/5 transition-all">
                    Explorer
                </a>
            </div>
        </div>

        <!-- Floating Phone Preview -->
        <div class="phone-container">
            <div class="phone-mockup">
                <div class="phone-screen">
                    <div class="phone-island"></div>
                    
                    <!-- Inside Phone: vCard Design -->
                    <div class="vcard-preview-header"></div>
                    <div class="vcard-preview-card">
                        <div class="preview-avatar">JS</div>
                        <h2 class="text-white font-bold text-sm font-outfit">Jean Stevens</h2>
                        <p class="text-[10px] text-slate-400">Directeur Stratégie</p>
                        
                        <div class="preview-socials">
                            <div class="preview-social-icon text-blue-400"><i class="fab fa-linkedin-in"></i></div>
                            <div class="preview-social-icon text-pink-400"><i class="fab fa-instagram"></i></div>
                            <div class="preview-social-icon text-white"><i class="fab fa-x-twitter"></i></div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <div class="preview-contact-item">
                            <div class="preview-icon-box"><i class="fas fa-phone"></i></div>
                            <div class="w-20 h-1.5 bg-slate-700 rounded"></div>
                        </div>
                        <div class="preview-contact-item">
                            <div class="preview-icon-box"><i class="fas fa-envelope"></i></div>
                            <div class="w-24 h-1.5 bg-slate-700 rounded"></div>
                        </div>
                        <div class="preview-contact-item">
                            <div class="preview-icon-box"><i class="fas fa-globe"></i></div>
                            <div class="w-16 h-1.5 bg-slate-700 rounded"></div>
                        </div>
                    </div>

                    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 w-3/4 h-10 bg-indigo-600 rounded-xl flex items-center justify-center gap-2">
                        <div class="w-3 h-3 bg-white/20 rounded-full"></div>
                        <div class="w-12 h-1.5 bg-white/40 rounded"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="feature-card">
            <div class="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center mb-6 text-xl">
                <i class="fas fa-bolt"></i>
            </div>
            <h3 class="text-xl font-bold mb-4">Transmission Instantanée</h3>
            <p class="text-slate-500 text-sm leading-relaxed">
                QR Code haute résolution et technologie NFC. Vos contacts enregistrent vos coordonnées en une seconde, sans application.
            </p>
        </div>
        <div class="feature-card">
            <div class="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center mb-6 text-xl">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3 class="text-xl font-bold mb-4">Analytics & Portée</h3>
            <p class="text-slate-500 text-sm leading-relaxed">
                Suivez la performance de votre carte. Nombre de vues, clics sur vos réseaux et enregistrements dans les répertoires.
            </p>
        </div>
        <div class="feature-card">
            <div class="w-12 h-12 bg-indigo-500/10 text-indigo-500 rounded-xl flex items-center justify-center mb-6 text-xl">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3 class="text-xl font-bold mb-4">Cloud Management</h3>
            <p class="text-slate-500 text-sm leading-relaxed">
                Mettez à jour votre numéro ou votre poste depuis votre espace membre. Votre lien public reste identique, vos données changent.
            </p>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="max-w-4xl mx-auto flex flex-col items-center gap-8">
            <div class="logo-box opacity-30 scale-75">
                <div class="logo-square"></div>
                <span>VCARD ELITE SYSTEM</span>
            </div>
            
            <p class="text-slate-500 text-sm max-w-lg">
                La solution digitale de référence pour les leaders et innovateurs.
            </p>

            <a href="{{ route('admin.login.page') }}" class="admin-btn">
                <i class="fas fa-lock me-2"></i> Authorized_Personnel_Only
            </a>

            <div class="mt-12 text-[10px] font-mono text-slate-700 tracking-widest uppercase">
                © 2024-2026 // Obsidian Elite // Encrypted Session
            </div>
        </div>
    </footer>

</body>
</html>