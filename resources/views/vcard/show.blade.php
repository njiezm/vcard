<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $customer->firstname }} {{ $customer->lastname }} - vCard</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <style>
        :root {
            /* Couleurs de base constantes */
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #10b981;
            --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
            
            /* Variables de thème (Défaut Nuit) */
            --bg-main: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --bg-item: rgba(255, 255, 255, 0.03);
            --bg-floating: rgba(15, 23, 42, 0.85);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --hero-gradient: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            --glass: blur(20px);
        }

        /* Mode Jour Overrides */
        body.light-mode {
            --bg-main: #f1f5f9;
            --bg-card: rgba(255, 255, 255, 0.9);
            --bg-item: rgba(0, 0, 0, 0.04);
            --bg-floating: rgba(255, 255, 255, 0.9);
            --border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-muted: #64748b;
            --hero-gradient: linear-gradient(135deg, #e2e8f0 0%, #cbd5e1 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            -webkit-tap-highlight-color: transparent;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 0;
            overflow-x: hidden;
            transition: background-color 0.4s ease;
        }

        /* Dynamic Background Orbs */
        .bg-orb {
            position: fixed;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(80px);
            z-index: -2;
            opacity: 0.15;
            pointer-events: none;
            animation: float 20s infinite ease-in-out;
        }
        .orb-1 { top: -100px; left: -100px; background: var(--primary); animation-delay: 0s; }
        .orb-2 { bottom: -100px; right: -100px; background: var(--accent); animation-delay: 10s; }

        @keyframes float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33% { transform: translate(30px, -30px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
        }

        .hero-banner {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 220px;
            background: var(--hero-gradient);
            overflow: hidden;
            z-index: -1;
            transition: background 0.4s ease;
        }

        .hero-banner::after {
            content: '';
            position: absolute;
            width: 200%; height: 200%;
            background: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noiseFilter'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.65' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noiseFilter)'/%3E%3C/svg%3E");
            opacity: 0.04;
            top: -50%; left: -50%;
        }

        .app-container {
            width: 100%;
            max-width: 450px;
            padding: 60px 20px 140px;
            position: relative;
        }

        /* Profile Card */
        .profile-card {
            background: var(--bg-card);
            backdrop-filter: var(--glass);
            -webkit-backdrop-filter: var(--glass);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 40px 24px 30px;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            margin-bottom: 24px;
            animation: cardAppear 0.8s ease-out;
        }

        @keyframes cardAppear {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .photo-container {
            position: relative;
            width: 130px;
            height: 130px;
            margin: -100px auto 20px;
        }

        .profile-img, .profile-placeholder {
            width: 130px;
            height: 130px;
            border-radius: 40px;
            object-fit: cover;
            border: 4px solid var(--bg-main);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            transition: border-color 0.4s ease, transform 0.3s ease;
        }

        .profile-img:hover, .profile-placeholder:hover {
            transform: scale(1.05);
        }

        .profile-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            font-weight: 800;
            color: white;
            font-family: 'Outfit', sans-serif;
        }

        .status-dot {
            position: absolute;
            bottom: 8px; right: 8px;
            width: 20px; height: 20px;
            background: var(--accent);
            border: 4px solid var(--bg-card);
            border-radius: 50%;
            box-shadow: 0 0 15px var(--accent);
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { box-shadow: 0 0 15px var(--accent); }
            50% { box-shadow: 0 0 25px var(--accent); }
            100% { box-shadow: 0 0 15px var(--accent); }
        }

        .name-wrapper h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.02em;
            margin-bottom: 4px;
            color: var(--text-main);
        }

        .name-wrapper p {
            color: var(--text-muted);
            font-size: 0.95rem;
            margin-bottom: 20px;
        }

        /* Social Quick Access Bar */
        .social-quick-access {
            display: flex;
            justify-content: center;
            gap: 12px;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid var(--border);
        }

        .social-icon-btn {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            background: var(--bg-item);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-main);
            text-decoration: none;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .social-icon-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: var(--primary);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .social-icon-btn:hover::before {
            width: 100px;
            height: 100px;
        }

        .social-icon-btn:hover {
            color: white;
            transform: translateY(-5px);
            border-color: var(--primary);
            z-index: 1;
        }

        .social-icon-btn i {
            position: relative;
            z-index: 2;
        }

        /* Section Titles */
        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-muted);
            margin: 35px 0 15px 10px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title::before {
            content: '';
            width: 20px;
            height: 2px;
            background: var(--primary);
            border-radius: 2px;
        }

        /* Contact Cards Grid */
        .contact-grid {
            display: grid;
            gap: 12px;
        }

        .contact-card {
            background: var(--bg-item);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 16px;
            display: flex;
            align-items: center;
            text-decoration: none;
            color: var(--text-main);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .contact-card::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.05), transparent);
            transition: left 0.5s;
        }

        .contact-card:hover::after {
            left: 100%;
        }

        .contact-card:hover {
            background: var(--bg-card);
            border-color: var(--primary);
            transform: scale(1.02);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .icon-box {
            width: 46px;
            height: 46px;
            border-radius: 14px;
            background: rgba(99, 102, 241, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            margin-right: 16px;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .contact-card:hover .icon-box {
            background: var(--primary);
            color: white;
            transform: rotate(10deg) scale(1.1);
        }

        .contact-info {
            flex-grow: 1;
            overflow: hidden;
        }

        .contact-info .label {
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 2px;
            font-weight: 500;
        }

        .contact-info .value {
            font-weight: 600;
            font-size: 0.95rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Floating Actions Container */
        .floating-actions {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: calc(100% - 40px);
            max-width: 420px;
            background: var(--bg-floating);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid var(--border);
            padding: 10px;
            border-radius: 24px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 8px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            z-index: 100;
            transition: var(--transition);
        }

        .btn-main {
            background: var(--primary);
            color: white;
            border: none;
            padding: 14px;
            border-radius: 16px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
        }

        .btn-main::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-main:hover::before {
            width: 300px;
            height: 300px;
        }

        .btn-main:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-sub {
            background: var(--bg-item);
            color: var(--text-main);
            border: 1px solid var(--border);
            padding: 14px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-sub:hover { 
            background: var(--bg-card); 
            color: var(--primary);
            transform: translateY(-2px);
            border-color: var(--primary);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.85);
            backdrop-filter: blur(10px);
            z-index: 1000;
            display: none;
            align-items: center;
            justify-content: center;
            padding: 20px;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: var(--bg-card);
            border-radius: 32px;
            padding: 30px;
            width: 100%;
            max-width: 360px;
            text-align: center;
            border: 1px solid var(--border);
            transform: scale(0.9);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: scale(1);
        }

        #qrcode {
            background: white;
            padding: 16px;
            border-radius: 24px;
            display: inline-block;
            margin: 24px 0;
        }

        /* Share Modal Styles */
        .share-options {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin: 20px 0;
        }

        .share-option {
            background: var(--bg-item);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 16px 8px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition);
            text-decoration: none;
            color: var(--text-main);
        }

        .share-option:hover {
            background: var(--bg-card);
            border-color: var(--primary);
            transform: translateY(-3px);
        }

        .share-option i {
            font-size: 1.5rem;
        }

        .share-option span {
            font-size: 0.7rem;
            font-weight: 500;
        }

        .share-native {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            grid-column: span 3;
        }

        .share-native:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%) translateY(-100px);
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 12px 24px;
            border-radius: 12px;
            color: var(--text-main);
            font-weight: 500;
            z-index: 2000;
            opacity: 0;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
        }

        .toast.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* Specific Colors for Icons */
        .color-ig { color: #E4405F !important; }
        .color-ln { color: #0A66C2 !important; }
        .color-tw { color: #1DA1F2 !important; }
        .color-fb { color: #1877F2 !important; }
        .color-tt { color: #000000 !important; }
        .color-yt { color: #FF0000 !important; }
        .color-wa { color: #25D366 !important; }
        .color-telegram { color: #0088cc !important; }
        body.light-mode .color-tt { color: #000 !important; }

        @media (max-width: 380px) {
            .btn-main span { display: none; }
            .floating-actions { grid-template-columns: 1fr 1fr 1fr 1fr; }
            .share-options { grid-template-columns: repeat(2, 1fr); }
            .share-native { grid-column: span 2; }
        }
    </style>
</head>
<body id="body">
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="hero-banner"></div>

    <div class="app-container">
        <!-- Profile Header -->
        <div class="profile-card">
            <div class="photo-container">
                @if($customer->photo)
                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Photo" class="profile-img">
                @else
                    <div class="profile-placeholder">
                        {{ strtoupper(substr($customer->firstname, 0, 1)) }}{{ strtoupper(substr($customer->lastname, 0, 1)) }}
                    </div>
                @endif
                <div class="status-dot"></div>
            </div>

            <div class="name-wrapper">
                <h1>{{ $customer->firstname }} {{ $customer->lastname }}</h1>
                <p>@if($customer->profession) {{ $customer->profession }} @else Professionnel @endif</p>
            </div>

            <!-- Quick Access (Top 4) -->
            <div class="social-quick-access">
                @if($customer->linkedin)
                    <a href="{{ $customer->linkedin }}" target="_blank" class="social-icon-btn color-ln"><i class="fab fa-linkedin-in"></i></a>
                @endif
                @if($customer->instagram)
                    <a href="https://instagram.com/{{ ltrim($customer->instagram, '@') }}" target="_blank" class="social-icon-btn color-ig"><i class="fab fa-instagram"></i></a>
                @endif
                @if($customer->twitter)
                    <a href="https://x.com/{{ ltrim($customer->twitter, '@') }}" target="_blank" class="social-icon-btn color-tw"><i class="fab fa-x-twitter"></i></a>
                @endif
                @if($customer->website)
                    <a href="{{ $customer->website }}" target="_blank" class="social-icon-btn"><i class="fas fa-globe"></i></a>
                @endif
            </div>
        </div>

        <!-- Section: Coordonnées -->
        <div class="section-title">Coordonnées</div>
        <div class="contact-grid">
            @if($customer->phone)
            <a href="tel:{{ $customer->phone }}" class="contact-card">
                <div class="icon-box"><i class="fas fa-phone"></i></div>
                <div class="contact-info">
                    <div class="label">Téléphone principal</div>
                    <div class="value">{{ $customer->phone }}</div>
                </div>
                <i class="fas fa-chevron-right" style="opacity: 0.2"></i>
            </a>
            @endif

            @if($customer->phone_secondary)
            <a href="tel:{{ $customer->phone_secondary }}" class="contact-card">
                <div class="icon-box" style="color: var(--accent); background: rgba(16, 185, 129, 0.1);"><i class="fas fa-phone-volume"></i></div>
                <div class="contact-info">
                    <div class="label">Téléphone secondaire</div>
                    <div class="value">{{ $customer->phone_secondary }}</div>
                </div>
                <i class="fas fa-chevron-right" style="opacity: 0.2"></i>
            </a>
            @endif

            @if($customer->email)
            <a href="mailto:{{ $customer->email }}" class="contact-card">
                <div class="icon-box"><i class="fas fa-envelope"></i></div>
                <div class="contact-info">
                    <div class="label">Email Professionnel</div>
                    <div class="value">{{ $customer->email }}</div>
                </div>
                <i class="fas fa-chevron-right" style="opacity: 0.2"></i>
            </a>
            @endif
        </div>

        <!-- Section: Réseaux Sociaux -->
        <div class="section-title">Réseaux Sociaux</div>
        <div class="contact-grid">
            @if($customer->tiktok)
            <a href="https://tiktok.com/@{{ ltrim($customer->tiktok, '@') }}" target="_blank" class="contact-card">
                <div class="icon-box color-tt"><i class="fab fa-tiktok"></i></div>
                <div class="contact-info">
                    <div class="label">TikTok</div>
                    <div class="value">{{ '@' . ltrim($customer->tiktok, '@') }}</div>
                </div>
            </a>
            @endif

            @if($customer->youtube)
            <a href="{{ $customer->youtube }}" target="_blank" class="contact-card">
                <div class="icon-box color-yt"><i class="fab fa-youtube"></i></div>
                <div class="contact-info">
                    <div class="label">YouTube</div>
                    <div class="value">Regarder la chaîne</div>
                </div>
            </a>
            @endif

            @if($customer->facebook)
            <a href="{{ $customer->facebook }}" target="_blank" class="contact-card">
                <div class="icon-box color-fb"><i class="fab fa-facebook-f"></i></div>
                <div class="contact-info">
                    <div class="label">Facebook</div>
                    <div class="value">Suivre sur Facebook</div>
                </div>
            </a>
            @endif
        </div>
    </div>

    <!-- Sticky Bottom Bar -->
    <div class="floating-actions">
        <button onclick="saveContact()" class="btn-main">
            <i class="fas fa-user-plus"></i> <span>Ajouter</span>
        </button>
        <button onclick="openShareModal()" class="btn-sub" title="Partager">
            <i class="fas fa-share-nodes"></i>
        </button>
        <button onclick="toggleQR()" class="btn-sub" title="QR Code">
            <i class="fas fa-qrcode"></i>
        </button>
        <button onclick="toggleTheme()" class="btn-sub" id="theme-btn" title="Mode Jour/Nuit">
            <i class="fas fa-sun" id="theme-icon"></i>
        </button>
    </div>

    <!-- QR Modal -->
    <div class="modal-overlay" id="qrModal">
        <div class="modal-content">
            <h2 style="font-family: 'Outfit'; margin-bottom: 8px;">Scanner</h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Partagez votre profil instantanément</p>
            <div id="qrcode"></div>
            <button onclick="toggleQR()" class="btn-main" style="width: 100%; background: var(--bg-item); color: var(--text-main); border: 1px solid var(--border);">Fermer</button>
        </div>
    </div>

    <!-- Share Modal -->
    <div class="modal-overlay" id="shareModal">
        <div class="modal-content">
            <h2 style="font-family: 'Outfit'; margin-bottom: 8px;">Partager</h2>
            <p style="color: var(--text-muted); font-size: 0.85rem;">Choisissez une plateforme</p>
            
            <div class="share-options">
                <a href="#" onclick="shareToWhatsApp(); return false;" class="share-option">
                    <i class="fab fa-whatsapp color-wa"></i>
                    <span>WhatsApp</span>
                </a>
                <a href="#" onclick="shareToTelegram(); return false;" class="share-option">
                    <i class="fab fa-telegram color-telegram"></i>
                    <span>Telegram</span>
                </a>
                <a href="#" onclick="shareToFacebook(); return false;" class="share-option">
                    <i class="fab fa-facebook-f color-fb"></i>
                    <span>Facebook</span>
                </a>
                <a href="#" onclick="shareToTwitter(); return false;" class="share-option">
                    <i class="fab fa-x-twitter color-tw"></i>
                    <span>X (Twitter)</span>
                </a>
                <a href="#" onclick="shareToLinkedIn(); return false;" class="share-option">
                    <i class="fab fa-linkedin-in color-ln"></i>
                    <span>LinkedIn</span>
                </a>
                <a href="#" onclick="copyLink(); return false;" class="share-option">
                    <i class="fas fa-link"></i>
                    <span>Copier</span>
                </a>
                <button onclick="shareNative()" class="share-option share-native">
                    <i class="fas fa-share-nodes"></i>
                    <span>Partager nativement</span>
                </button>
            </div>
            
            <button onclick="closeShareModal()" class="btn-main" style="width: 100%; background: var(--bg-item); color: var(--text-main); border: 1px solid var(--border); margin-top: 10px;">Fermer</button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast"></div>

    <script>
        // --- Theme Toggle Logic ---
        function toggleTheme() {
            const body = document.getElementById('body');
            const icon = document.getElementById('theme-icon');
            body.classList.toggle('light-mode');
            
            if(body.classList.contains('light-mode')) {
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme', 'light');
            } else {
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        if(localStorage.getItem('theme') === 'light') {
            document.getElementById('body').classList.add('light-mode');
            document.getElementById('theme-icon').className = 'fas fa-moon';
        }

        // --- Toast Notification ---
        function showToast(message) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.classList.add('show');
            
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // --- QR Code ---
        let qrcode = null;
        function toggleQR() {
            const modal = document.getElementById('qrModal');
            modal.classList.toggle('active');
            
            if (modal.classList.contains('active') && !qrcode) {
                setTimeout(() => {
                    qrcode = new QRCode(document.getElementById("qrcode"), {
                        text: window.location.href,
                        width: 220,
                        height: 220,
                        colorDark : "#0f172a",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                }, 100);
            }
        }

        // --- Share Modal ---
        function openShareModal() {
            document.getElementById('shareModal').classList.add('active');
        }

        function closeShareModal() {
            document.getElementById('shareModal').classList.remove('active');
        }

        // --- Share Functions ---
        const shareUrl = window.location.href;
        const shareText = `Découvrez le profil de {{ $customer->firstname }} {{ $customer->lastname }}`;

        function shareToWhatsApp() {
            window.open(`https://wa.me/?text=${encodeURIComponent(shareText + ' ' + shareUrl)}`, '_blank');
            closeShareModal();
        }

        function shareToTelegram() {
            window.open(`https://t.me/share/url?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(shareText)}`, '_blank');
            closeShareModal();
        }

        function shareToFacebook() {
            window.open(`https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`, '_blank');
            closeShareModal();
        }

        function shareToTwitter() {
            window.open(`https://twitter.com/intent/tweet?text=${encodeURIComponent(shareText)}&url=${encodeURIComponent(shareUrl)}`, '_blank');
            closeShareModal();
        }

        function shareToLinkedIn() {
            window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`, '_blank');
            closeShareModal();
        }

        function copyLink() {
            navigator.clipboard.writeText(shareUrl).then(() => {
                showToast('Lien copié dans le presse-papiers !');
                closeShareModal();
            });
        }

        async function shareNative() {
            if (navigator.share) {
                try {
                    await navigator.share({
                        title: '{{ $customer->firstname }} {{ $customer->lastname }}',
                        text: shareText,
                        url: shareUrl
                    });
                    closeShareModal();
                } catch (err) {
                    if (err.name !== 'AbortError') {
                        showToast('Partage annulé');
                    }
                }
            } else {
                copyLink();
            }
        }

        // --- vCard Export ---
        function saveContact() {
            const vcardData = `BEGIN:VCARD
VERSION:3.0
FN:{{ $customer->firstname }} {{ $customer->lastname }}
TITLE:{{ $customer->title ?? 'Professionnel' }}
TEL;TYPE=CELL:{{ $customer->phone ?? '' }}
TEL;TYPE=WORK:{{ $customer->phone_secondary ?? '' }}
EMAIL:{{ $customer->email ?? '' }}
URL:{{ $customer->website ?? '' }}
NOTE:vCard générée par votre solution
END:VCARD`;

            const blob = new Blob([vcardData], { type: 'text/vcard' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = '{{ $customer->firstname }}_{{ $customer->lastname }}.vcf';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            showToast('Contact ajouté avec succès !');
        }

        // Close modals on background click
        document.getElementById('qrModal').addEventListener('click', function(e) {
            if (e.target === this) toggleQR();
        });

        document.getElementById('shareModal').addEventListener('click', function(e) {
            if (e.target === this) closeShareModal();
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('qrModal').classList.remove('active');
                document.getElementById('shareModal').classList.remove('active');
            }
        });
    </script>
</body>
</html>