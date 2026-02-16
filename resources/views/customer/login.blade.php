<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Connexion Client - vCard</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #10b981;
            --bg-main: #0f172a;
            --bg-card: rgba(30, 41, 59, 0.7);
            --bg-input: rgba(255, 255, 255, 0.03);
            --border: rgba(255, 255, 255, 0.1);
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --transition: all 0.3s cubic-bezier(0.23, 1, 0.32, 1);
        }

        body.light-mode {
            --bg-main: #f1f5f9;
            --bg-card: rgba(255, 255, 255, 0.9);
            --bg-input: rgba(0, 0, 0, 0.03);
            --border: rgba(0, 0, 0, 0.08);
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-main);
            color: var(--text-main);
            transition: background-color 0.4s ease;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* Background Orbs */
        .bg-orb {
            position: fixed;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            filter: blur(100px);
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }
        .orb-1 { top: -200px; left: -100px; background: var(--primary); }
        .orb-2 { bottom: -200px; right: -100px; background: var(--accent); }

        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.4);
        }

        .input-group {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 16px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            padding: 0 16px;
        }

        .input-group:focus-within {
            border-color: var(--primary);
            background: var(--bg-card);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .custom-input {
            background: transparent;
            border: none;
            width: 100%;
            padding: 14px 12px;
            color: var(--text-main);
            outline: none;
            font-size: 0.95rem;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transition: var(--transition);
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(99, 102, 241, 0.5);
        }

        .btn-gradient:active {
            transform: translateY(0);
        }

        .theme-toggle {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 100;
        }

        .logo-circle {
            width: 70px;
            height: 70px;
            border-radius: 22px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            margin: 0 auto 20px;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-login { animation: slideUp 0.8s cubic-bezier(0.23, 1, 0.32, 1); }
    </style>
</head>
<body id="body">
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <button onclick="toggleTheme()" class="theme-toggle w-12 h-12 glass-card flex items-center justify-center text-xl hover:scale-110 transition-transform">
        <i class="fas fa-sun" id="theme-icon"></i>
    </button>

    <div class="w-full max-w-[420px] p-6 animate-login">
        <div class="glass-card p-8 md:p-10">
            <div class="text-center mb-8">
                <div class="logo-circle">
                    <i class="fas fa-id-card"></i>
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2 font-outfit">Espace Client</h1>
                <p class="text-muted text-sm font-medium opacity-80">Gérez votre profil professionnel</p>
            </div>

            @if(session('error'))
                <div class="bg-red-500/10 border border-red-500/20 text-red-500 p-4 rounded-2xl mb-6 text-sm flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-lg"></i>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('customer.login.post', ['slug' => $slug]) }}" method="POST" class="space-y-6">
                @csrf
                
                <div class="space-y-2">
                    <label class="text-xs font-bold text-muted uppercase tracking-widest ml-1">Identifiant</label>
                    <div class="input-group">
                        <i class="fas fa-link text-muted text-sm"></i>
                        <input type="text" name="slug" class="custom-input" required value="{{ $slug }}" placeholder="votre-identifiant">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-xs font-bold text-muted uppercase tracking-widest ml-1">Code Admin</label>
                    <div class="input-group">
                        <i class="fas fa-key text-muted text-sm"></i>
                        <input type="password" name="admin_code" class="custom-input" required placeholder="••••••">
                    </div>
                    <p class="text-[10px] text-muted italic ml-1">Ce code vous a été transmis lors de la création.</p>
                </div>

                <button type="submit" class="w-full btn-gradient py-4 rounded-2xl font-bold text-lg flex items-center justify-center gap-3 mt-8">
                    Se connecter <i class="fas fa-arrow-right text-sm"></i>
                </button>
            </form>

            <div class="mt-8 pt-6 border-top border-white/5 text-center">
                <a href="/" class="text-sm text-muted hover:text-primary transition-colors flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i> Retour à l'accueil
                </a>
            </div>
        </div>
    </div>

    <script>
        // --- Theme Toggle ---
        function toggleTheme() {
            const body = document.getElementById('body');
            const icon = document.getElementById('theme-icon');
            body.classList.toggle('light-mode');
            
            if(body.classList.contains('light-mode')) {
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme_login', 'light');
            } else {
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme_login', 'dark');
            }
        }

        // Persistent theme
        if(localStorage.getItem('theme_login') === 'light') {
            document.getElementById('body').classList.add('light-mode');
            document.getElementById('theme-icon').className = 'fas fa-moon';
        }

        // Simple animation logic for the form on load
        document.addEventListener('DOMContentLoaded', () => {
            console.log("Login page ready.");
        });
    </script>
</body>
</html>