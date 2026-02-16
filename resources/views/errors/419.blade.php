@extends('errors::minimal')

@section('title', __('Session Expirée - vCard Elite'))

@section('message')
    <!-- Configuration Style Obsidian -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        :root {
            --obsidian-dark: #050505;
            --obsidian-card: #0c0c0c;
            --obsidian-border: #1a1a1a;
            --primary: #6366f1;
            --primary-glow: rgba(99, 102, 241, 0.2);
            --text-secondary: #94a3b8;
            --transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
        }

        /* Wrapper fixe pour garantir le centrage absolu sur l'écran */
        .full-center-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--obsidian-dark);
            z-index: 9999;
            overflow: hidden;
        }

        .bg-glow {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: 
                radial-gradient(circle at 50% 50%, rgba(99, 102, 241, 0.15) 0%, transparent 60%);
            z-index: -1;
        }

        .grid-overlay {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: radial-gradient(var(--obsidian-border) 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.2;
            z-index: -1;
        }

        .error-container {
            text-align: center;
            padding: 20px;
            max-width: 500px;
            position: relative;
        }

        .glitch-code {
            font-size: clamp(5rem, 15vw, 8rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.05em;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
            background: linear-gradient(to bottom, #fff, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            filter: drop-shadow(0 0 15px var(--primary-glow));
        }

        .error-message {
            color: var(--text-secondary);
            font-size: 1.1rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            font-family: 'Outfit', sans-serif;
        }

        .btn-elite {
            background-color: white;
            color: black;
            padding: 14px 32px;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Outfit', sans-serif;
        }

        .btn-elite:hover {
            background-color: var(--primary);
            color: white;
            transform: translateY(-3px);
            box-shadow: 0 15px 30px var(--primary-glow);
        }

        .terminal-text {
            font-family: 'JetBrains Mono', monospace;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: var(--primary);
            margin-bottom: 10px;
            display: block;
            opacity: 0.8;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .float-anim { animation: float 4s ease-in-out infinite; }
    </style>

    <div class="full-center-wrapper">
        <div class="bg-glow"></div>
        <div class="grid-overlay"></div>

        <div class="error-container">
            <span class="terminal-text">Error_Code: 419 // Auth_Timeout</span>
            
            <div class="glitch-code float-anim">
                419
            </div>
            
            <h2 class="text-3xl font-bold mb-4 text-white">Session Expirée</h2>
            
            <p class="error-message">
                Votre jeton de sécurité a expiré par mesure de protection. Veuillez rafraîchir la page ou retourner à l'étape précédente.
            </p>
            
            <a href="{{ url()->previous() }}" class="btn-elite">
                <i class="fas fa-arrow-left text-xs"></i> Retourner
            </a>

            <div class="mt-16 opacity-20">
                <div class="flex justify-center items-center gap-3 text-white">
                    <div class="w-4 h-4 bg-indigo-500 rounded-sm"></div>
                    <span class="font-mono text-[10px] tracking-widest uppercase">Obsidian_Security_Protocol</span>
                </div>
            </div>
        </div>
    </div>
@endsection