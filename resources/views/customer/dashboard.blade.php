<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration vCard - {{ $customer->firstname }} {{ $customer->lastname }}</title>
    
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* --- Variables & Thème --- */
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
        }

        h1, h2, h3, .font-outfit {
            font-family: 'Outfit', sans-serif;
        }

        /* --- Composants --- */
        .glass-card {
            background: var(--bg-card);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 24px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.3);
        }

        .input-group {
            background: var(--bg-input);
            border: 1px solid var(--border);
            border-radius: 14px;
            transition: var(--transition);
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
            padding: 12px 16px;
            color: var(--text-main);
            outline: none;
        }

        .btn-gradient {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            transition: var(--transition);
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .photo-preview-wrapper {
            width: 160px;
            height: 160px;
            border-radius: 30px;
            overflow: hidden;
            border: 4px solid var(--bg-main);
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            position: relative;
            background: var(--primary);
        }

        .photo-preview-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        /* --- NOUVEAU : Bouton flottant pour mobile --- */
        .mobile-fab {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 50;
            display: none; /* Caché par défaut */
        }
        
        @media (max-width: 768px) {
            .mobile-fab {
                display: flex; /* Affiché uniquement sur mobile */
            }
        }

        /* --- NOUVEAU : Menu Navigation Mobile --- */
        .mobile-nav {
            display: none;
        }
        @media (max-width: 768px) {
            .mobile-nav {
                display: flex;
            }
        }
        .desktop-nav {
            display: flex;
        }
        @media (max-width: 768px) {
            .desktop-nav {
                display: none;
            }
        }

        /* --- Animation --- */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade { animation: fadeIn 0.6s ease-out; }

        .sidebar-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-muted);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        /* --- NOUVEAU : Animation de chargement pour le bouton --- */
        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }
        .btn-gradient.loading .spinner {
            display: inline-block;
        }
        .btn-gradient.loading .btn-text {
            display: none;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="p-4 md:p-8">

    <!-- Bouton de changement de thème (fixe) -->
    <button onclick="toggleTheme()" class="theme-toggle w-12 h-12 glass-card flex items-center justify-center text-xl hover:scale-110 transition-transform fixed top-4 right-4 z-50">
        <i class="fas fa-sun" id="theme-icon"></i>
    </button>

    <!-- NOUVEAU : Navigation Mobile (cachée par défaut) -->
    <div id="mobile-menu" class="mobile-nav fixed inset-0 bg-black/50 z-40 hidden">
        <div class="glass-card p-6 m-4 rounded-2xl">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-bold">Menu</h3>
                <button onclick="toggleMobileMenu()" class="text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <a href="{{ route('vcard.show', $customer->slug) }}" target="_blank" class="block w-full p-3 text-left hover:bg-white/10 rounded-xl transition-colors">
                <i class="fas fa-eye mr-3"></i> Aperçu Public
            </a>
            <a href="/admin" class="block w-full p-3 text-left hover:bg-white/10 rounded-xl transition-colors">
                <i class="fas fa-sign-out-alt mr-3"></i> Quitter
            </a>
        </div>
    </div>

    <div class="max-w-6xl mx-auto animate-fade">
        
        <!-- Header -->
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight">Espace Client</h1>
                <p class="text-muted text-sm mt-1">Gérez votre vCard et vos informations publiques</p>
            </div>
            
            <!-- Navigation Desktop -->
            <div class="desktop-nav flex gap-3">
                <a href="{{ route('vcard.show', $customer->slug) }}" target="_blank" class="px-5 py-3 glass-card flex items-center gap-2 hover:bg-white/10 transition-colors">
                    <i class="fas fa-eye"></i> Aperçu Public
                </a>
                <a href="/admin" class="px-5 py-3 bg-slate-800 text-white rounded-2xl flex items-center gap-2 hover:bg-slate-700 transition-colors">
                    <i class="fas fa-sign-out-alt"></i> Quitter
                </a>
            </div>

            <!-- NOUVEAU : Bouton Menu Mobile -->
            <button onclick="toggleMobileMenu()" class="mobile-nav md:hidden p-3 glass-card rounded-xl">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </header>

        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="glass-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-indigo-500/10 flex items-center justify-center text-indigo-500 text-2xl">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-muted uppercase tracking-wider">Vues totales</p>
                    <p class="text-2xl font-extrabold">{{ $customer->views ?? 0 }}</p>
                </div>
            </div>
            <div class="glass-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-2xl">
                    <i class="fas fa-check-double"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-muted uppercase tracking-wider">Complétude</p>
                    <p class="text-2xl font-extrabold">@php 
                        $fields = ['email', 'phone', 'website', 'instagram', 'linkedin'];
                        $count = 0;
                        foreach($fields as $f) if($customer->$f) $count++;
                        echo floor(($count / count($fields)) * 100);
                    @endphp%</p>
                </div>
            </div>
            <div class="glass-card p-6 flex items-center gap-5">
                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 flex items-center justify-center text-amber-500 text-2xl">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-muted uppercase tracking-wider">Statut</p>
                    <p class="text-2xl font-extrabold">Actif</p>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div id="success-alert" class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 p-4 rounded-2xl mb-8 flex items-center gap-3 animate-fade">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <!-- Main Form -->
        <form id="update-form" action="{{ route('customer.update', $customer->slug) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Identité & Photo -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="glass-card p-8 text-center">
                        <div class="sidebar-title">Photo de profil</div>
                        <div class="flex flex-col items-center">
                            <div class="photo-preview-wrapper mb-6" id="photo-preview">
                                @if($customer->photo)
                                    <img src="{{ asset('storage/' . $customer->photo) }}" alt="Profil">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-4xl font-bold text-white uppercase">
                                        {{ substr($customer->firstname, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                            <label for="photo-input" class="btn-gradient px-6 py-3 rounded-xl cursor-pointer text-sm font-semibold">
                                <i class="fas fa-camera mr-2"></i> Changer la photo
                            </label>
                            <input type="file" id="photo-input" name="photo" class="hidden" onchange="previewImage(this)">
                            @if($customer->photo)
                                <label class="mt-4 flex items-center gap-2 text-red-500 text-xs cursor-pointer">
                                    <input type="checkbox" name="delete_photo" class="rounded border-none bg-red-500/10"> 
                                    Supprimer la photo actuelle
                                </label>
                            @endif
                        </div>
                    </div>

                    <div class="glass-card p-8">
                        <div class="sidebar-title">Identité fixe</div>
                        <div class="space-y-4">
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1">Prénom & Nom</label>
                                <div class="input-group mt-1 opacity-60">
                                    <input type="text" class="custom-input cursor-not-allowed" value="{{ $customer->firstname }} {{ $customer->lastname }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informations détaillées -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Groupe 1: Pro & Principal -->
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <i class="fas fa-user-tie text-primary"></i> Informations Professionnelles
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-1 md:col-span-2">
                                <label class="text-xs font-semibold text-muted ml-1">Titre / Profession</label>
                                <div class="input-group mt-1">
                                    <input type="text" name="profession" class="custom-input" value="{{ $customer->profession }}" placeholder="Ex: Architecte DPLG">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1">Téléphone Principal</label>
                                <div class="input-group mt-1">
                                    <input type="tel" name="phone" class="custom-input" value="{{ $customer->phone }}" placeholder="+33 6 ...">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1">Email</label>
                                <div class="input-group mt-1">
                                    <input type="email" name="email" class="custom-input" value="{{ $customer->email }}" placeholder="nom@domaine.com">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1">Téléphone Secondaire</label>
                                <div class="input-group mt-1">
                                    <input type="tel" name="phone_secondary" class="custom-input" value="{{ $customer->phone_secondary }}" placeholder="+33 1 ...">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1">Site Web</label>
                                <div class="input-group mt-1">
                                    <input type="url" name="website" class="custom-input" value="{{ $customer->website }}" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Groupe 2: Réseaux Sociaux -->
                    <div class="glass-card p-8">
                        <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                            <i class="fas fa-share-alt text-primary"></i> Réseaux Sociaux
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-linkedin mr-1"></i> LinkedIn</label>
                                <div class="input-group mt-1">
                                    <input type="url" name="linkedin" class="custom-input" value="{{ $customer->linkedin }}" placeholder="Lien complet profil">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-instagram mr-1"></i> Instagram</label>
                                <div class="input-group mt-1 flex items-center">
                                    <span class="pl-4 text-muted">@</span>
                                    <input type="text" name="instagram" class="custom-input pl-1" value="{{ ltrim($customer->instagram, '@') }}" placeholder="pseudo">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-x-twitter mr-1"></i> X (Twitter)</label>
                                <div class="input-group mt-1 flex items-center">
                                    <span class="pl-4 text-muted">@</span>
                                    <input type="text" name="twitter" class="custom-input pl-1" value="{{ ltrim($customer->twitter, '@') }}" placeholder="pseudo">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-tiktok mr-1"></i> TikTok</label>
                                <div class="input-group mt-1 flex items-center">
                                    <span class="pl-4 text-muted">@</span>
                                    <input type="text" name="tiktok" class="custom-input pl-1" value="{{ ltrim($customer->tiktok, '@') }}" placeholder="pseudo">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-facebook mr-1"></i> Facebook</label>
                                <div class="input-group mt-1">
                                    <input type="url" name="facebook" class="custom-input" value="{{ $customer->facebook }}" placeholder="Lien page">
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-muted ml-1"><i class="fab fa-youtube mr-1"></i> YouTube</label>
                                <div class="input-group mt-1">
                                    <input type="url" name="youtube" class="custom-input" value="{{ $customer->youtube }}" placeholder="Lien chaîne">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end pt-4">
                        <button type="submit" id="submit-btn" class="btn-gradient px-10 py-4 rounded-2xl font-bold text-lg flex items-center gap-3">
                            <span class="btn-text"><i class="fas fa-save"></i> Enregistrer les modifications</span>
                            <div class="spinner"></div>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- NOUVEAU : Bouton Flottant (FAB) pour mobile -->
    <button id="mobile-fab" class="mobile-fab btn-gradient w-16 h-16 rounded-full text-2xl shadow-2xl flex items-center justify-center">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // --- Theme Toggle ---
        function toggleTheme() {
            const body = document.body;
            const icon = document.getElementById('theme-icon');
            body.classList.toggle('light-mode');
            
            if(body.classList.contains('light-mode')) {
                icon.className = 'fas fa-moon';
                localStorage.setItem('theme_admin', 'light');
            } else {
                icon.className = 'fas fa-sun';
                localStorage.setItem('theme_admin', 'dark');
            }
        }

        if(localStorage.getItem('theme_admin') === 'light') {
            document.body.classList.add('light-mode');
            document.getElementById('theme-icon').className = 'fas fa-moon';
        }

        // --- NOUVEAU : Gestion du menu mobile ---
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // --- Image Preview ---
        function previewImage(input) {
            if (input.files && input.files[0]) {
                // NOUVEAU : Validation simple de la taille du fichier
                if (input.files[0].size > 2 * 1024 * 1024) { // 2MB
                    alert('Le fichier est trop volumineux. La taille maximale est de 2MB.');
                    input.value = ''; // Réinitialiser l'input
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photo-preview');
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- NOUVEAU : Gestion de la soumission du formulaire ---
        document.getElementById('update-form').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submit-btn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });

        // --- NOUVEAU : Auto-masquage de l'alerte de succès ---
        window.addEventListener('load', () => {
            const alert = document.getElementById('success-alert');
            if (alert) {
                setTimeout(() => {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s ease-out';
                    setTimeout(() => alert.remove(), 500);
                }, 5000); // Disparaît après 5 secondes
            }
        });

        // --- NOUVEAU : Bouton FAB pour remonter en haut (mobile) ---
        const mobileFab = document.getElementById('mobile-fab');
        mobileFab.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

    </script>
</body>
</html>