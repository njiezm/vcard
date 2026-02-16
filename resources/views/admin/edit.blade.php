@extends('layouts.admin')

@section('title', 'Creer des Clients - Phoenix Admin')

@push('styles')
    <style>
        /* Styles spécifiques à la page clients */
        .customer-avatar {
            object-fit: cover;
            transition: transform 0.2s ease;
        }

        .customer-avatar:hover {
            transform: scale(1.05);
        }

        .customer-info {
            min-width: 0;
        }

        .status-badge {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
            font-weight: 500;
        }

        .action-btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .copy-btn {
            padding: 0.25rem 0.375rem;
            font-size: 0.75rem;
            transition: all 0.2s ease;
        }

        .copy-btn:hover {
            background-color: var(--phoenix-primary);
            color: white;
        }

        .copy-btn.copied {
            background-color: var(--phoenix-success);
            color: white;
        }

        .filter-chip {
            padding: 0.5rem 1rem;
            border: 1px solid #dee2e6;
            background: white;
            border-radius: 50px;
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .filter-chip:hover {
            background-color: #f8f9fa;
            border-color: #adb5bd;
        }

        .filter-chip.active {
            background-color: var(--phoenix-primary);
            color: white;
            border-color: var(--phoenix-primary);
        }

        .search-wrapper {
            position: relative;
        }

        .search-input {
            padding-left: 2.5rem;
            padding-right: 2.5rem;
        }

        .search-wrapper .input-group-text {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: transparent;
            border: none;
        }

        #clearSearch {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: transparent;
            border: none;
        }

        .table-container {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            overflow: hidden;
        }

        .customers-table {
            margin-bottom: 0;
        }

        .customers-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
            color: #495057;
            padding: 1rem;
            white-space: nowrap;
        }

        .customers-table td {
            padding: 1rem;
            vertical-align: middle;
            border-bottom: 1px solid #dee2e6;
        }

        .customers-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.5rem !important;
        }

        .sortable:hover {
            background-color: #e9ecef;
        }

        .sortable i {
            position: absolute;
            right: 0.5rem;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.5;
        }

        .sortable.sorted-asc i,
        .sortable.sorted-desc i {
            opacity: 1;
            color: var(--phoenix-primary);
        }

        .empty-state {
            padding: 3rem 1rem;
        }

        .stat-card {
            background: white;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }

        .stat-card-body {
            padding: 1.5rem;
        }

        .stat-card-title {
            font-size: 0.875rem;
            color: #6c757d;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }

        .stat-card-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #212529;
        }

        .stat-card-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.25rem;
        }

        .animate-slideInLeft {
            animation: slideInLeft 0.3s ease-out;
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Mobile Responsive Styles */
        @media (max-width: 768px) {
            .table-responsive {
                display: none !important;
            }

            .mobile-cards-container {
                display: block !important;
            }
        }

        @media (min-width: 769px) {
            .mobile-cards-container {
                display: none !important;
            }
        }

        /* Styles pour les modales personnalisées */
        .custom-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1050;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease, visibility 0.3s ease;
        }

        .custom-modal.active {
            opacity: 1;
            visibility: visible;
        }

        .custom-modal-content {
            background-color: #f8f9fa;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            width: 90%;
            max-width: 500px;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .custom-modal.active .custom-modal-content {
            transform: translateY(0);
        }

        .custom-modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #dee2e6;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .custom-modal-title {
            margin: 0;
            font-size: 1.25rem;
            color: #495057;
        }

        .custom-modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            line-height: 1;
        }

        .custom-modal-body {
            padding: 1.5rem;
            color: #495057;
        }

        .custom-modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #dee2e6;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn-custom {
            padding: 0.5rem 1rem;
            border-radius: 0.25rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }

        .btn-custom-secondary {
            background-color: #6c757d;
            color: white;
            border-color: #6c757d;
        }

        .btn-custom-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }

        .btn-custom-primary {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }

        .btn-custom-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }

        .btn-custom-success {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        .btn-custom-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }

        .btn-custom-danger {
            background-color: #dc3545;
            color: white;
            border-color: #dc3545;
        }

        .btn-custom-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 1060;
            transform: translateX(120%);
            transition: transform 0.3s ease;
            max-width: 350px;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .notification-error {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .notification-info {
            background-color: #d1ecf1;
            color: #0c5460;
            border-left: 4px solid #17a2b8;
        }

        .notification-icon {
            font-size: 1.25rem;
        }

        .notification-close {
            margin-left: auto;
            background: none;
            border: none;
            font-size: 1.25rem;
            cursor: pointer;
            opacity: 0.7;
            transition: opacity 0.2s;
        }

        .notification-close:hover {
            opacity: 1;
        }

        .loading-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
@endpush

@section('content')
<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb bg-transparent p-0 mb-0">
        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}" class="text-decoration-none text-muted">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="#" class="text-decoration-none text-muted">Clients</a></li>
        <li class="breadcrumb-item active text-primary" aria-current="page">Nouveau Client</li>
    </ol>
</nav>

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
@endsection
@push('scripts')
    

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


@endpush