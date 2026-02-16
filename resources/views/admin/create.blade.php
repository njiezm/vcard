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

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-1">Créer un nouveau client</h1>
        <p class="text-muted mb-0">Remplissez les informations pour créer une carte VCard</p>
    </div>
    <a href="{{ route('admin.index') }}" class="btn btn-phoenix">
        <i class="fas fa-arrow-left me-2"></i>Retour
    </a>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Main Content -->
<div class="row">
    <!-- Form Column -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <!-- Progress Steps -->
                <div class="d-flex justify-content-between mb-5 position-relative">
                    <div class="progress position-absolute" style="top: 20px; left: 0; right: 0; height: 2px; background: var(--phoenix-border-color); z-index: 0;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 0%; transition: width 0.3s;" id="progressBar"></div>
                    </div>
                    
                    <div class="step-indicator active" id="step1">
                        <div class="step-circle">1</div>
                        <div class="step-label">Informations</div>
                    </div>
                    
                    <div class="step-indicator" id="step2">
                        <div class="step-circle">2</div>
                        <div class="step-label">Réseaux</div>
                    </div>
                    
                    <div class="step-indicator" id="step3">
                        <div class="step-circle">3</div>
                        <div class="step-label">Finalisation</div>
                    </div>
                </div>

                <form id="createCustomerForm" action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Step 1: Basic Information -->
                    <div id="formStep1">
                        <h4 class="mb-4"><i class="fas fa-user me-2 text-primary"></i>Informations de base</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstname" class="form-label">Prénom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('firstname') is-invalid @enderror" 
                                       id="firstname" 
                                       name="firstname" 
                                       value="{{ old('firstname') }}" 
                                       placeholder="Jean"
                                       required>
                                @error('firstname')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastname" class="form-label">Nom <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('lastname') is-invalid @enderror" 
                                       id="lastname" 
                                       name="lastname" 
                                       value="{{ old('lastname') }}" 
                                       placeholder="Dupont"
                                       required>
                                @error('lastname')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       placeholder="jean.dupont@example.com"
                                       required>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}" 
                                       placeholder="+33 6 12 34 56 78"
                                       required>
                                @error('phone')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="job" class="form-label">Poste</label>
                                <input type="text" 
                                       class="form-control @error('job') is-invalid @enderror" 
                                       id="job" 
                                       name="job" 
                                       value="{{ old('job') }}" 
                                       placeholder="Développeur Web">
                                @error('job')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="company" class="form-label">Entreprise</label>
                                <input type="text" 
                                       class="form-control @error('company') is-invalid @enderror" 
                                       id="company" 
                                       name="company" 
                                       value="{{ old('company') }}" 
                                       placeholder="Tech Corp">
                                @error('company')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Biographie</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="3" 
                                      placeholder="Décrivez-vous en quelques mots...">{{ old('bio') }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">
                                Suivant <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 2: Social Networks -->
                    <div id="formStep2" style="display: none;">
                        <h4 class="mb-4"><i class="fas fa-share-alt me-2 text-primary"></i>Réseaux sociaux</h4>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="linkedin" class="form-label">
                                    <i class="fab fa-linkedin text-primary"></i> LinkedIn
                                </label>
                                <input type="url" 
                                       class="form-control @error('linkedin') is-invalid @enderror" 
                                       id="linkedin" 
                                       name="linkedin" 
                                       value="{{ old('linkedin') }}" 
                                       placeholder="https://linkedin.com/in/pseudo">
                                @error('linkedin')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instagram" class="form-label">
                                    <i class="fab fa-instagram text-danger"></i> Instagram
                                </label>
                                <input type="text" 
                                       class="form-control @error('instagram') is-invalid @enderror" 
                                       id="instagram" 
                                       name="instagram" 
                                       value="{{ old('instagram') }}" 
                                       placeholder="@pseudo">
                                @error('instagram')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="twitter" class="form-label">
                                    <i class="fab fa-twitter text-info"></i> Twitter / X
                                </label>
                                <input type="text" 
                                       class="form-control @error('twitter') is-invalid @enderror" 
                                       id="twitter" 
                                       name="twitter" 
                                       value="{{ old('twitter') }}" 
                                       placeholder="@pseudo">
                                @error('twitter')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="facebook" class="form-label">
                                    <i class="fab fa-facebook text-primary"></i> Facebook
                                </label>
                                <input type="url" 
                                       class="form-control @error('facebook') is-invalid @enderror" 
                                       id="facebook" 
                                       name="facebook" 
                                       value="{{ old('facebook') }}" 
                                       placeholder="https://facebook.com/pseudo">
                                @error('facebook')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="youtube" class="form-label">
                                    <i class="fab fa-youtube text-danger"></i> YouTube
                                </label>
                                <input type="url" 
                                       class="form-control @error('youtube') is-invalid @enderror" 
                                       id="youtube" 
                                       name="youtube" 
                                       value="{{ old('youtube') }}" 
                                       placeholder="https://youtube.com/@chaine">
                                @error('youtube')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="tiktok" class="form-label">
                                    <i class="fab fa-tiktok text-dark"></i> TikTok
                                </label>
                                <input type="text" 
                                       class="form-control @error('tiktok') is-invalid @enderror" 
                                       id="tiktok" 
                                       name="tiktok" 
                                       value="{{ old('tiktok') }}" 
                                       placeholder="@pseudo">
                                @error('tiktok')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="previousStep(1)">
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">
                                Suivant <i class="fas fa-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Step 3: Finalization -->
                    <div id="formStep3" style="display: none;">
                        <h4 class="mb-4"><i class="fas fa-camera me-2 text-primary"></i>Photo de profil</h4>
                        
                        <div class="mb-4">
                            <div class="text-center p-4 border-2 border-dashed rounded-3 bg-light" id="dropZone">
                                <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                                <p class="mb-2 fw-semibold">Glissez-déposez une photo ici</p>
                                <p class="text-muted small mb-3">ou</p>
                                <label for="photo" class="btn btn-outline-primary">
                                    <i class="fas fa-folder-open me-2"></i>Parcourir
                                </label>
                                <input type="file" 
                                       class="d-none @error('photo') is-invalid @enderror" 
                                       id="photo" 
                                       name="photo" 
                                       accept="image/*"
                                       onchange="previewImage(event)">
                                <p class="text-muted small mt-3 mb-0">PNG, JPG jusqu'à 5MB</p>
                            </div>
                            
                            <div id="imagePreview" class="mt-3 text-center" style="display: none;">
                                <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 200px;">
                                <br>
                                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                    <i class="fas fa-trash me-1"></i>Supprimer
                                </button>
                            </div>
                            
                            @error('photo')
                                <div class="text-danger mt-2">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <h4 class="mb-4"><i class="fas fa-cog me-2 text-primary"></i>Informations générées</h4>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <div class="small">
                                <strong>Slug :</strong> sera généré automatiquement à partir du nom<br>
                                <strong>Code admin :</strong> sera généré automatiquement pour l'accès client
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Slug prévisualisé</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="slugPreview" readonly value="-">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('slugPreview')" title="Copier">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Code admin prévisualisé</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="codePreview" readonly value="-">
                                    <button class="btn btn-outline-secondary" type="button" onclick="copyToClipboard('codePreview')" title="Copier">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="previousStep(2)">
                                <i class="fas fa-arrow-left me-2"></i> Précédent
                            </button>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>
                                <span id="submitText">Créer le client</span>
                                <span class="spinner-border spinner-border-sm ms-2 d-none" id="submitSpinner"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Live Preview Column -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 90px;">
            <div class="card-body">
                <h5 class="card-title mb-3">
                    <i class="fas fa-eye me-2 text-primary"></i>Aperçu en direct
                </h5>
                
                <div class="text-center p-4 bg-gradient rounded-3 mb-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="preview-avatar mb-3" id="previewAvatar">
                        <i class="fas fa-user fa-3x text-white"></i>
                    </div>
                    <h5 class="text-white mb-1" id="previewName">Nom Prénom</h5>
                    <p class="text-white-50 small mb-0" id="previewJob">Poste</p>
                </div>

                <div class="preview-social d-flex justify-content-center gap-3 mb-3" id="previewSocial">
                    <a href="#" class="text-muted" title="Email"><i class="fas fa-envelope fa-lg"></i></a>
                    <a href="#" class="text-muted" title="Téléphone"><i class="fas fa-phone fa-lg"></i></a>
                    <a href="#" class="text-muted" title="LinkedIn"><i class="fab fa-linkedin fa-lg"></i></a>
                    <a href="#" class="text-muted" title="Instagram"><i class="fab fa-instagram fa-lg"></i></a>
                </div>

                <div class="text-center">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt me-1"></i>
                        Mise à jour automatique
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.step-indicator {
    position: relative;
    z-index: 1;
    text-align: center;
    flex: 1;
}

.step-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--phoenix-border-color);
    color: var(--phoenix-text-muted);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 8px;
    font-weight: 600;
    transition: all 0.3s;
}

.step-indicator.active .step-circle {
    background: var(--phoenix-text-main);
    color: white;
    box-shadow: 0 0 0 4px rgba(56, 116, 255, 0.1);
}

.step-indicator.completed .step-circle {
    background: #10b981;
    color: white;
}

.step-label {
    font-size: 0.875rem;
    color: var(--phoenix-text-muted);
    font-weight: 500;
}

.step-indicator.active .step-label {
    color: var(--phoenix-text-main);
    font-weight: 600;
}

#dropZone {
    transition: all 0.3s;
    cursor: pointer;
}

#dropZone:hover {
    background-color: #f8f9fa;
    border-color: var(--phoenix-text-main);
}

#dropZone.dragover {
    background-color: rgba(56, 116, 255, 0.05);
    border-color: var(--phoenix-text-main);
}

.preview-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    overflow: hidden;
}

.preview-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.preview-social a {
    transition: all 0.2s;
}

.preview-social a:hover {
    color: var(--phoenix-text-main) !important;
    transform: translateY(-2px);
}

.card {
    transition: all 0.3s;
}

.card:hover {
    box-shadow: 0 10px 40px rgba(0,0,0,0.1) !important;
}

.btn {
    transition: all 0.2s;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-control:focus {
    border-color: var(--phoenix-text-main);
    box-shadow: 0 0 0 0.2rem rgba(56, 116, 255, 0.25);
}

.invalid-feedback {
    display: flex;
    align-items: center;
    margin-top: 0.25rem;
    font-size: 0.875rem;
}
</style>
@endpush

@push('scripts')
<script>
let currentStep = 1;

// Navigation between steps
function nextStep(step) {
    if (!validateStep(currentStep)) {
        return;
    }

    // Hide current step
    document.getElementById(`formStep${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep}`).classList.remove('active');
    document.getElementById(`step${currentStep}`).classList.add('completed');

    // Show next step
    currentStep = step;
    document.getElementById(`formStep${currentStep}`).style.display = 'block';
    document.getElementById(`step${currentStep}`).classList.add('active');

    // Update progress bar
    updateProgressBar();

    // Generate preview values if step 3
    if (step === 3) {
        generatePreviewValues();
    }

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function previousStep(step) {
    // Hide current step
    document.getElementById(`formStep${currentStep}`).style.display = 'none';
    document.getElementById(`step${currentStep}`).classList.remove('active');

    // Show previous step
    currentStep = step;
    document.getElementById(`formStep${currentStep}`).style.display = 'block';
    document.getElementById(`step${currentStep}`).classList.add('active');
    document.getElementById(`step${currentStep}`).classList.remove('completed');

    // Update progress bar
    updateProgressBar();

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateProgressBar() {
    const progress = ((currentStep - 1) / 2) * 100;
    document.getElementById('progressBar').style.width = progress + '%';
}

function validateStep(step) {
    if (step === 1) {
        const firstname = document.getElementById('firstname').value;
        const lastname = document.getElementById('lastname').value;
        const email = document.getElementById('email').value;
        const phone = document.getElementById('phone').value;

        if (!firstname || !lastname || !email || !phone) {
            showToast('Veuillez remplir tous les champs obligatoires', 'warning');
            return false;
        }

        // Email validation
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showToast('Veuillez entrer une adresse email valide', 'warning');
            return false;
        }
    }
    return true;
}

// Image preview
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    
    if (file) {
        // Check file size (5MB max)
        if (file.size > 5 * 1024 * 1024) {
            showToast('L\'image ne doit pas dépasser 5MB', 'error');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            preview.style.display = 'block';
            
            // Update avatar in live preview
            document.getElementById('previewAvatar').innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover;">`;
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    document.getElementById('photo').value = '';
    document.getElementById('imagePreview').style.display = 'none';
    document.getElementById('previewAvatar').innerHTML = '<i class="fas fa-user fa-3x text-white"></i>';
}

// Drag and drop
const dropZone = document.getElementById('dropZone');

dropZone.addEventListener('dragover', (e) => {
    e.preventDefault();
    dropZone.classList.add('dragover');
});

dropZone.addEventListener('dragleave', () => {
    dropZone.classList.remove('dragover');
});

dropZone.addEventListener('drop', (e) => {
    e.preventDefault();
    dropZone.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0) {
        document.getElementById('photo').files = files;
        previewImage({ target: { files: files } });
    }
});

// Form submission
document.getElementById('createCustomerForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    submitBtn.disabled = true;
    submitText.textContent = 'Création en cours...';
    submitSpinner.classList.remove('d-none');
});

// Update live preview
function updatePreview() {
    const firstname = document.getElementById('firstname').value || 'Nom';
    const lastname = document.getElementById('lastname').value || 'Prénom';
    const job = document.getElementById('job').value || 'Poste';
    
    document.getElementById('previewName').textContent = `${firstname} ${lastname}`;
    document.getElementById('previewJob').textContent = job;
}

// Generate preview values
function generatePreviewValues() {
    const firstname = document.getElementById('firstname').value || 'john';
    const lastname = document.getElementById('lastname').value || 'doe';
    
    // Generate slug
    const slug = `${firstname.toLowerCase()}-${lastname.toLowerCase()}`.replace(/[^a-z0-9-]/g, '');
    document.getElementById('slugPreview').value = slug;
    
    // Generate admin code
    const code = Math.random().toString(36).substring(2, 8).toUpperCase();
    document.getElementById('codePreview').value = code;
}

// Copy to clipboard
function copyToClipboard(elementId) {
    const element = document.getElementById(elementId);
    element.select();
    document.execCommand('copy');
    showToast('Copié dans le presse-papiers', 'success');
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-success' : type === 'warning' ? 'bg-warning' : 'bg-danger';
    const icon = type === 'success' ? 'fa-check-circle' : type === 'warning' ? 'fa-exclamation-triangle' : 'fa-times-circle';
    
    toast.className = `toast align-items-center text-white ${bgColor} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');
    toast.style.cssText = 'position: fixed; top: 20px; right: 20px; z-index: 9999;';
    
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas ${icon} me-2"></i>${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    toast.addEventListener('hidden.bs.toast', () => {
        toast.remove();
    });
}

// Format phone number
document.getElementById('phone').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value;
    
    if (value.startsWith('+33')) {
        formattedValue = '+33 ' + value.substring(3).replace(/(.{2})/g, '$1 ').trim();
    } else if (value.startsWith('0')) {
        formattedValue = value.replace(/(.{2})/g, '$1 ').trim();
    }
    
    e.target.value = formattedValue;
});

// Format URLs
document.querySelectorAll('input[type="url"]').forEach(input => {
    input.addEventListener('blur', function() {
        if (this.value && !this.value.startsWith('http')) {
            this.value = 'https://' + this.value;
        }
    });
});

// Format usernames
['instagram', 'twitter', 'tiktok'].forEach(id => {
    document.getElementById(id)?.addEventListener('input', function() {
        let value = this.value;
        if (value && !value.startsWith('@')) {
            this.value = '@' + value.replace('@', '');
        }
    });
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updatePreview();
    
    // Update preview on input
    ['firstname', 'lastname', 'job'].forEach(id => {
        document.getElementById(id)?.addEventListener('input', updatePreview);
    });
});
</script>
@endpush