<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un client - vCard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #1e3a8a;
            --sidebar-hover: #1e40af;
            --topbar-bg: #f8fafc;
            --card-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            --primary-color: #3b82f6;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f1f5f9;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #1e40af 100%);
            z-index: 1000;
            transition: all 0.3s;
            box-shadow: 4px 0 6px rgba(0, 0, 0, 0.1);
        }

        .sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .sidebar-brand {
            color: white;
            font-size: 1.25rem;
            font-weight: 700;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            display: block;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-item:hover {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: #60a5fa;
        }

        .sidebar-item.active {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: #60a5fa;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s;
        }

        /* Topbar */
        .topbar {
            background-color: var(--topbar-bg);
            padding: 1rem 2rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .breadcrumb-phoenix {
            background: transparent;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-phoenix .breadcrumb-item {
            color: #64748b;
        }

        .breadcrumb-phoenix .breadcrumb-item.active {
            color: #1e293b;
        }

        /* Content */
        .content {
            padding: 2rem;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            overflow: hidden;
        }

        .form-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 0.5rem;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 0.875rem;
        }

        .form-body {
            padding: 2rem;
        }

        /* Photo Upload */
        .photo-upload-card {
            background: white;
            border-radius: 0.75rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            padding: 1.5rem;
            text-align: center;
        }

        .photo-preview {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #f1f5f9;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .photo-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 1.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Form Controls */
        .form-label-phoenix {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control-phoenix {
            border: 1px solid #d1d5db;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }

        .form-control-phoenix:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* Buttons */
        .btn-phoenix {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 500;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-phoenix-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-phoenix-primary:hover {
            background-color: #2563eb;
            transform: translateY(-1px);
            box-shadow: 0 4px 6px rgba(59, 130, 246, 0.3);
        }

        .btn-phoenix-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-phoenix-secondary:hover {
            background-color: #4b5563;
            transform: translateY(-1px);
        }

        .btn-phoenix-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-phoenix-danger:hover {
            background-color: #dc2626;
            transform: translateY(-1px);
        }

        /* Mobile Toggle */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.5rem;
            box-shadow: var(--card-shadow);
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-toggle {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar Toggle Button (Mobile) -->
    <button class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="#" class="sidebar-brand">
                <i class="fas fa-id-card"></i>
                vCard Admin
            </a>
        </div>
        <nav class="sidebar-menu">
            <a href="{{ route('admin.index') }}" class="sidebar-item">
                <i class="fas fa-tachometer-alt me-2"></i>
                Tableau de bord
            </a>
            <a href="{{ route('admin.create') }}" class="sidebar-item">
                <i class="fas fa-user-plus me-2"></i>
                Nouveau client
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-chart-bar me-2"></i>
                Statistiques
            </a>
            <a href="#" class="sidebar-item">
                <i class="fas fa-cog me-2"></i>
                Paramètres
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-phoenix">
                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.index') }}" class="text-decoration-none">
                            <i class="fas fa-home me-1"></i>
                            Accueil
                        </a>
                    </li>
                    <li class="breadcrumb-item active">Modifier {{ $customer->firstname }} {{ $customer->lastname }}</li>
                </ol>
            </nav>
            <div class="topbar-user">
                <span class="text-muted">Administrateur</span>
                <div class="user-avatar" style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: 600;">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <div class="row">
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="fas fa-user-edit me-2 text-primary"></i>
                                Modifier les informations
                            </h2>
                            <p class="form-subtitle">Mettez à jour les informations du client</p>
                        </div>
                        <div class="form-body">
                            <form action="{{ route('admin.update', $customer->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="firstname" class="form-label-phoenix">
                                            Prénom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-phoenix" 
                                               id="firstname" 
                                               name="firstname" 
                                               value="{{ $customer->firstname }}" 
                                               required>
                                        @if($errors->has('firstname'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('firstname') }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="lastname" class="form-label-phoenix">
                                            Nom <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-phoenix" 
                                               id="lastname" 
                                               name="lastname" 
                                               value="{{ $customer->lastname }}" 
                                               required>
                                        @if($errors->has('lastname'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('lastname') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label-phoenix">
                                            <i class="fas fa-envelope me-1 text-muted"></i>
                                            Email
                                        </label>
                                        <input type="email" 
                                               class="form-control form-control-phoenix" 
                                               id="email" 
                                               name="email" 
                                               value="{{ $customer->email }}"
                                               placeholder="email@exemple.com">
                                        @if($errors->has('email'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('email') }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label-phoenix">
                                            <i class="fas fa-phone me-1 text-muted"></i>
                                            Téléphone
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-phoenix" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ $customer->phone }}"
                                               placeholder="+33 6 12 34 56 78">
                                        @if($errors->has('phone'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('phone') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="website" class="form-label-phoenix">
                                            <i class="fas fa-globe me-1 text-muted"></i>
                                            Site web
                                        </label>
                                        <input type="url" 
                                               class="form-control form-control-phoenix" 
                                               id="website" 
                                               name="website" 
                                               value="{{ $customer->website }}"
                                               placeholder="https://exemple.com">
                                        @if($errors->has('website'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('website') }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <label for="instagram" class="form-label-phoenix">
                                            <i class="fab fa-instagram me-1 text-muted"></i>
                                            Instagram
                                        </label>
                                        <input type="text" 
                                               class="form-control form-control-phoenix" 
                                               id="instagram" 
                                               name="instagram" 
                                               value="{{ $customer->instagram }}"
                                               placeholder="@pseudo">
                                        @if($errors->has('instagram'))
                                            <div class="text-danger mt-1">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                {{ $errors->first('instagram') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="row">
    <div class="col-md-6 mb-3">
        <label for="twitter" class="form-label-phoenix">
            <i class="fab fa-twitter"></i>
            Twitter / X
        </label>
        <input type="text"
               class="form-control form-control-phoenix @error('twitter') is-invalid @enderror"
               id="twitter"
               name="twitter"
               value="{{ old('twitter') }}"
               placeholder="@pseudo">
        @error('twitter')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="youtube" class="form-label-phoenix">
            <i class="fab fa-youtube"></i>
            YouTube
        </label>
        <input type="url"
               class="form-control form-control-phoenix @error('youtube') is-invalid @enderror"
               id="youtube"
               name="youtube"
               value="{{ old('youtube') }}"
               placeholder="https://youtube.com/@chaine">
        @error('youtube')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="tiktok" class="form-label-phoenix">
            <i class="fab fa-tiktok"></i>
            TikTok
        </label>
        <input type="text"
               class="form-control form-control-phoenix @error('tiktok') is-invalid @enderror"
               id="tiktok"
               name="tiktok"
               value="{{ old('tiktok') }}"
               placeholder="@pseudo">
        @error('tiktok')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="phone_secondary" class="form-label-phoenix">
            <i class="fas fa-phone-alt"></i>
            Téléphone secondaire
        </label>
        <input type="tel"
               class="form-control form-control-phoenix @error('phone_secondary') is-invalid @enderror"
               id="phone_secondary"
               name="phone_secondary"
               value="{{ old('phone_secondary') }}"
               placeholder="+33 1 23 45 67 89">
        @error('phone_secondary')
            <div class="text-danger mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

                                
                                <div class="d-flex gap-2 mt-4">
                                    <button type="submit" class="btn btn-phoenix btn-phoenix-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Mettre à jour
                                    </button>
                                    <a href="{{ route('admin.index') }}" class="btn btn-phoenix btn-phoenix-secondary">
                                        <i class="fas fa-times me-2"></i>
                                        Annuler
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="photo-upload-card">
                        <h5 class="mb-3">
                            <i class="fas fa-camera me-2"></i>
                            Photo de profil
                        </h5>
                        
                        <div id="photoContainer">
                            @if($customer->photo)
                                <img src="{{ asset('storage/' . $customer->photo) }}" 
                                     alt="Photo actuelle" 
                                     class="photo-preview" 
                                     id="currentPhoto">
                            @else
                                <div class="photo-placeholder">
                                    {{ substr($customer->firstname, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <label for="photo" class="form-label-phoenix">Changer la photo</label>
                            <input type="file" 
                                   class="form-control form-control-phoenix" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*" 
                                   onchange="previewPhoto(event)">
                            @if($errors->has('photo'))
                                <div class="text-danger mt-1">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $errors->first('photo') }}
                                </div>
                            @endif
                            <small class="text-muted">Formats: JPG, PNG, GIF (Max: 2MB)</small>
                        </div>
                        
                        @if($customer->photo)
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="delete_photo" name="delete_photo">
                                <label class="form-check-label" for="delete_photo">
                                    <i class="fas fa-trash me-1"></i>
                                    Supprimer la photo actuelle
                                </label>
                            </div>
                        @endif

                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('vcard.show', $customer->slug) }}" 
                               target="_blank" 
                               class="btn btn-phoenix btn-phoenix-primary">
                                <i class="fas fa-eye me-2"></i>
                                Voir la vCard
                            </a>
                            <a href="{{ route('customer.dashboard', ['adminCode' => $customer->admin_code, 'slug' => $customer->slug]) }}" 
                               target="_blank" 
                               class="btn btn-phoenix btn-phoenix-secondary">
                                <i class="fas fa-user-cog me-2"></i>
                                Admin client
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function previewPhoto(event) {
            const file = event.target.files[0];
            const photoContainer = document.getElementById('photoContainer');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    photoContainer.innerHTML = `<img src="${e.target.result}" alt="Aperçu" class="photo-preview" id="previewPhoto">`;
                }
                reader.readAsDataURL(file);
            } else {
                // Restaurer la photo actuelle si on annule la sélection
                @if($customer->photo)
                    photoContainer.innerHTML = `<img src="{{ asset('storage/' . $customer->photo) }}" alt="Photo actuelle" class="photo-preview" id="currentPhoto">`;
                @else
                    photoContainer.innerHTML = `<div class="photo-placeholder">{{ substr($customer->firstname, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}</div>`;
                @endif
            }
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });

        
        ['instagram', 'twitter', 'tiktok'].forEach(id => {
    const input = document.getElementById(id);
    if (!input) return;

    input.addEventListener('input', e => {
        let v = e.target.value;
        if (v && !v.startsWith('@')) {
            e.target.value = '@' + v.replace('@', '');
        }
    });
});
    </script>
</body>
</html>