<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un client - vCard</title>
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
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            transition: transform 0.3s;
        }

        .sidebar-brand:hover {
            transform: translateX(5px);
        }

        .sidebar-menu {
            padding: 1rem 0;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.5rem;
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 3px solid transparent;
            position: relative;
            overflow: hidden;
        }

        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .sidebar-item:hover::before {
            left: 100%;
        }

        .sidebar-item:hover {
            background-color: var(--sidebar-hover);
            color: white;
            border-left-color: #60a5fa;
            transform: translateX(5px);
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Topbar */
        .topbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
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
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border: 1px solid #e2e8f0;
            overflow: hidden;
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .form-header {
            padding: 2rem;
            border-bottom: 1px solid #e2e8f0;
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            position: relative;
            overflow: hidden;
        }

        .form-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 0%, transparent 70%);
            animation: rotate 30s linear infinite;
        }

        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .form-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.5rem;
            position: relative;
        }

        .form-subtitle {
            color: #64748b;
            font-size: 0.95rem;
            position: relative;
        }

        .form-body {
            padding: 2rem;
        }

        /* Progress Steps */
        .progress-steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            position: relative;
        }

        .progress-steps::before {
            content: '';
            position: absolute;
            top: 20px;
            left: 0;
            right: 0;
            height: 2px;
            background: #e2e8f0;
            z-index: 0;
        }

        .progress-step {
            position: relative;
            z-index: 1;
            text-align: center;
            flex: 1;
        }

        .step-circle {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.5rem;
            transition: all 0.3s;
            font-weight: 600;
        }

        .progress-step.active .step-circle {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #667eea;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .progress-step.completed .step-circle {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            border-color: #84fab0;
            color: white;
        }

        .step-label {
            font-size: 0.875rem;
            color: #64748b;
            font-weight: 500;
        }

        .progress-step.active .step-label {
            color: #667eea;
            font-weight: 600;
        }

        /* Form Sections */
        .form-section {
            background: #f8fafc;
            border-radius: 0.75rem;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid #e2e8f0;
            transition: all 0.3s;
        }

        .form-section:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .form-section-title {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 2px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Form Controls */
        .form-label-phoenix {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-control-phoenix {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: white;
        }

        .form-control-phoenix:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            transform: translateY(-2px);
        }

        .form-control-phoenix.is-invalid {
            border-color: var(--danger-color);
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        /* Buttons */
        .btn-phoenix {
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn-phoenix::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .btn-phoenix:active::before {
            width: 300px;
            height: 300px;
        }

        .btn-phoenix-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-phoenix-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-phoenix-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-phoenix-secondary:hover {
            background: #4b5563;
            transform: translateY(-2px);
        }

        /* Alert */
        .alert-phoenix {
            border-radius: 0.75rem;
            border: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInRight 0.5s ease-out;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .alert-phoenix-info {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #0369a1;
        }

        .alert-phoenix-warning {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #92400e;
        }

        /* File Upload */
        .file-upload-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
            width: 100%;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            padding: 2rem;
            border: 2px dashed #d1d5db;
            border-radius: 0.75rem;
            background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%);
            cursor: pointer;
            transition: all 0.3s;
        }

        .file-upload-label:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, #ede9fe 0%, #ddd6fe 100%);
            transform: scale(1.02);
        }

        .file-upload-input {
            position: absolute;
            left: -9999px;
        }

        /* Image Preview */
        #imagePreview {
            display: none;
            animation: fadeIn 0.5s;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        #previewImg {
            border-radius: 0.75rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        #previewImg:hover {
            transform: scale(1.05);
        }

        /* Loading Spinner */
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top: 3px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Generated Fields Display */
        .generated-field {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            border-radius: 0.5rem;
            border: 1px solid #bae6fd;
            margin-top: 0.5rem;
        }

        .generated-field-value {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #0369a1;
            flex: 1;
        }

        .copy-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 0.5rem;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.3s;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .copy-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .copy-btn.copied {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
            animation: pulse 0.5s;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Tooltip */
        .tooltip-custom {
            position: absolute;
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            white-space: nowrap;
            z-index: 1000;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.3s;
            top: -40px;
            right: 0;
        }

        .tooltip-custom.show {
            opacity: 1;
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
            padding: 0.75rem;
            border-radius: 0.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        /* Required field indicator */
        .required {
            color: var(--danger-color);
            animation: blink 2s infinite;
        }

        @keyframes blink {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        /* Help text */
        .form-text {
            font-size: 0.875rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }

        /* Character counter */
        .char-counter {
            font-size: 0.75rem;
            color: #9ca3af;
            text-align: right;
            margin-top: 0.25rem;
        }

        /* Live Preview Card */
        .live-preview {
            background: white;
            border-radius: 0.75rem;
            padding: 1.5rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 2rem;
        }

        .preview-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .preview-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 2rem;
        }

        .preview-name {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .preview-job {
            color: #64748b;
            font-size: 0.875rem;
        }

        .preview-social {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-top: 1rem;
        }

        .preview-social a {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            transition: all 0.3s;
        }

        .preview-social a:hover {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            transform: translateY(-3px);
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

            .form-body {
                padding: 1rem;
            }

            .progress-steps {
                flex-direction: column;
                gap: 1rem;
            }

            .progress-steps::before {
                display: none;
            }

            .live-preview {
                position: static;
                margin-top: 2rem;
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
            <a href="{{ route('admin.create') }}" class="sidebar-item active">
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
                    <li class="breadcrumb-item active">Nouveau client</li>
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
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-phoenix-success alert-phoenix mb-4" style="background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%); color: #065f46;">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                <div class="col-lg-8">
                    <div class="form-card">
                        <div class="form-header">
                            <h2 class="form-title">
                                <i class="fas fa-user-plus me-2" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;"></i>
                                Créer un nouveau client
                            </h2>
                            <p class="form-subtitle">Remplissez les informations pour créer une nouvelle vCard</p>
                        </div>
                        <div class="form-body">
                            <!-- Progress Steps -->
                            <div class="progress-steps">
                                <div class="progress-step active" id="step1">
                                    <div class="step-circle">1</div>
                                    <div class="step-label">Informations</div>
                                </div>
                                <div class="progress-step" id="step2">
                                    <div class="step-circle">2</div>
                                    <div class="step-label">Réseaux</div>
                                </div>
                                <div class="progress-step" id="step3">
                                    <div class="step-circle">3</div>
                                    <div class="step-label">Finalisation</div>
                                </div>
                            </div>

                            <form action="{{ route('admin.store') }}" method="POST" enctype="multipart/form-data" id="createCustomerForm">
                                @csrf
                                
                                <!-- Step 1: Informations de base -->
                                <div id="formStep1">
                                    <div class="form-section">
                                        <h3 class="form-section-title">
                                            <i class="fas fa-user"></i>
                                            Informations de base
                                        </h3>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="firstname" class="form-label-phoenix">
                                                    <i class="fas fa-user"></i>
                                                    Prénom <span class="required">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-phoenix @error('firstname') is-invalid @enderror" 
                                                       id="firstname" 
                                                       name="firstname" 
                                                       value="{{ old('firstname') }}" 
                                                       required
                                                       placeholder="Entrez le prénom"
                                                       oninput="updatePreview()">
                                                @if($errors->has('firstname'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('firstname') }}
                                                    </div>
                                                @endif
                                            </div>
                                            
                                            <div class="col-md-6 mb-3">
                                                <label for="lastname" class="form-label-phoenix">
                                                    <i class="fas fa-user"></i>
                                                    Nom <span class="required">*</span>
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-phoenix @error('lastname') is-invalid @enderror" 
                                                       id="lastname" 
                                                       name="lastname" 
                                                       value="{{ old('lastname') }}" 
                                                       required
                                                       placeholder="Entrez le nom"
                                                       oninput="updatePreview()">
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
                                                    <i class="fas fa-envelope"></i>
                                                    Email <span class="required">*</span>
                                                </label>
                                                <input type="email" 
                                                       class="form-control form-control-phoenix @error('email') is-invalid @enderror" 
                                                       id="email" 
                                                       name="email" 
                                                       value="{{ old('email') }}" 
                                                       required
                                                       placeholder="exemple@email.com">
                                                <div class="form-text">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    L'email sera utilisé pour envoyer les identifiants de connexion
                                                </div>
                                                @if($errors->has('email'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="phone" class="form-label-phoenix">
                                                    <i class="fas fa-phone"></i>
                                                    Téléphone
                                                </label>
                                                <input type="tel" 
                                                       class="form-control form-control-phoenix @error('phone') is-invalid @enderror" 
                                                       id="phone" 
                                                       name="phone" 
                                                       value="{{ old('phone') }}" 
                                                       placeholder="+33 6 12 34 56 78"
                                                       oninput="formatPhone(this)">
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
                                                <label for="job" class="form-label-phoenix">
                                                    <i class="fas fa-briefcase"></i>
                                                    Poste
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-phoenix @error('job') is-invalid @enderror" 
                                                       id="job" 
                                                       name="job" 
                                                       value="{{ old('job') }}" 
                                                       placeholder="Ex: Directeur Marketing"
                                                       oninput="updatePreview()">
                                                @if($errors->has('job'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('job') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="company" class="form-label-phoenix">
                                                    <i class="fas fa-building"></i>
                                                    Entreprise
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-phoenix @error('company') is-invalid @enderror" 
                                                       id="company" 
                                                       name="company" 
                                                       value="{{ old('company') }}" 
                                                       placeholder="Nom de l'entreprise">
                                                @if($errors->has('company'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('company') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-phoenix btn-phoenix-primary" onclick="nextStep(2)">
                                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 2: Réseaux sociaux -->
                                <div id="formStep2" style="display: none;">
                                    <div class="form-section">
                                        <h3 class="form-section-title">
                                            <i class="fas fa-share-alt"></i>
                                            Réseaux sociaux
                                        </h3>
                                        
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="website" class="form-label-phoenix">
                                                    <i class="fas fa-globe"></i>
                                                    Site web
                                                </label>
                                                <input type="url" 
                                                       class="form-control form-control-phoenix @error('website') is-invalid @enderror" 
                                                       id="website" 
                                                       name="website" 
                                                       value="{{ old('website') }}" 
                                                       placeholder="https://www.exemple.com"
                                                       onblur="formatUrl(this)">
                                                @if($errors->has('website'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('website') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="linkedin" class="form-label-phoenix">
                                                    <i class="fab fa-linkedin"></i>
                                                    LinkedIn
                                                </label>
                                                <input type="url" 
                                                       class="form-control form-control-phoenix @error('linkedin') is-invalid @enderror" 
                                                       id="linkedin" 
                                                       name="linkedin" 
                                                       value="{{ old('linkedin') }}" 
                                                       placeholder="https://linkedin.com/in/pseudo"
                                                       onblur="formatUrl(this)">
                                                @if($errors->has('linkedin'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('linkedin') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="instagram" class="form-label-phoenix">
                                                    <i class="fab fa-instagram"></i>
                                                    Instagram
                                                </label>
                                                <input type="text" 
                                                       class="form-control form-control-phoenix @error('instagram') is-invalid @enderror" 
                                                       id="instagram" 
                                                       name="instagram" 
                                                       value="{{ old('instagram') }}" 
                                                       placeholder="@pseudo"
                                                       oninput="formatUsername(this, 'instagram')">
                                                @if($errors->has('instagram'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('instagram') }}
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label for="facebook" class="form-label-phoenix">
                                                    <i class="fab fa-facebook"></i>
                                                    Facebook
                                                </label>
                                                <input type="url" 
                                                       class="form-control form-control-phoenix @error('facebook') is-invalid @enderror" 
                                                       id="facebook" 
                                                       name="facebook" 
                                                       value="{{ old('facebook') }}" 
                                                       placeholder="https://facebook.com/pseudo"
                                                       onblur="formatUrl(this)">
                                                @if($errors->has('facebook'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('facebook') }}
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
                                                       placeholder="@pseudo"
                                                       oninput="formatUsername(this, 'twitter')">
                                                @if($errors->has('twitter'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('twitter') }}
                                                    </div>
                                                @endif
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
                                                       placeholder="https://youtube.com/@chaine"
                                                       onblur="formatUrl(this)">
                                                @if($errors->has('youtube'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('youtube') }}
                                                    </div>
                                                @endif
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
                                                       placeholder="@pseudo"
                                                       oninput="formatUsername(this, 'tiktok')">
                                                @if($errors->has('tiktok'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('tiktok') }}
                                                    </div>
                                                @endif
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
                                                       placeholder="+33 1 23 45 67 89"
                                                       oninput="formatPhone(this)">
                                                @if($errors->has('phone_secondary'))
                                                    <div class="text-danger mt-1">
                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                        {{ $errors->first('phone_secondary') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-phoenix btn-phoenix-secondary" onclick="previousStep(1)">
                                            <i class="fas fa-arrow-left me-2"></i> Précédent
                                        </button>
                                        <button type="button" class="btn btn-phoenix btn-phoenix-primary" onclick="nextStep(3)">
                                            Suivant <i class="fas fa-arrow-right ms-2"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Step 3: Finalisation -->
                                <div id="formStep3" style="display: none;">
                                    <div class="form-section">
                                        <h3 class="form-section-title">
                                            <i class="fas fa-camera"></i>
                                            Photo de profil
                                        </h3>
                                        
                                        <div class="mb-3">
                                            <div class="file-upload-wrapper">
                                                <label for="photo" class="file-upload-label">
                                                    <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-2"></i>
                                                    <div class="text-center">
                                                        <p class="mb-0 fw-semibold">Cliquez pour télécharger une photo</p>
                                                        <small class="text-muted">PNG, JPG jusqu'à 5MB</small>
                                                    </div>
                                                </label>
                                                <input type="file" 
                                                       class="file-upload-input @error('photo') is-invalid @enderror" 
                                                       id="photo" 
                                                       name="photo" 
                                                       accept="image/*"
                                                       onchange="previewImage(event)">
                                            </div>
                                            <div id="imagePreview" class="mt-3 text-center">
                                                <img id="previewImg" src="" alt="Aperçu" class="img-thumbnail" style="max-width: 200px;">
                                            </div>
                                            @if($errors->has('photo'))
                                                <div class="text-danger mt-1">
                                                    <i class="fas fa-exclamation-circle me-1"></i>
                                                    {{ $errors->first('photo') }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-section">
                                        <h3 class="form-section-title">
                                            <i class="fas fa-cog"></i>
                                            Informations générées
                                        </h3>
                                        
                                        <div class="alert alert-phoenix-info">
                                            <i class="fas fa-info-circle me-2"></i>
                                            <div>
                                                <strong>Slug :</strong> sera généré automatiquement à partir du nom<br>
                                                <strong>Code admin :</strong> sera généré automatiquement pour l'accès client
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label-phoenix">
                                                <i class="fas fa-link"></i>
                                                Slug prévisualisé
                                            </label>
                                            <div class="generated-field">
                                                <span class="generated-field-value" id="slugPreview">-</span>
                                                <button type="button" class="copy-btn" onclick="copyToClipboard('slugPreview', this)" title="Copier">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <div class="tooltip-custom">Copié!</div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label-phoenix">
                                                <i class="fas fa-key"></i>
                                                Code admin prévisualisé
                                            </label>
                                            <div class="generated-field">
                                                <span class="generated-field-value" id="codePreview">-</span>
                                                <button type="button" class="copy-btn" onclick="copyToClipboard('codePreview', this)" title="Copier">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                                <div class="tooltip-custom">Copié!</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between">
                                        <button type="button" class="btn btn-phoenix btn-phoenix-secondary" onclick="previousStep(2)">
                                            <i class="fas fa-arrow-left me-2"></i> Précédent
                                        </button>
                                        <button type="submit" class="btn btn-phoenix btn-phoenix-primary" id="submitBtn">
                                            <i class="fas fa-save me-2"></i>
                                            <span id="submitText">Créer le client</span>
                                            <div class="loading-spinner ms-2" id="submitSpinner"></div>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Live Preview Column -->
                <div class="col-lg-4">
                    <div class="live-preview">
                        <div class="preview-header">
                            <div class="preview-avatar" id="previewAvatar">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="preview-name" id="previewName">Nom Prénom</div>
                            <div class="preview-job" id="previewJob">Poste</div>
                        </div>
                        
                        <div class="preview-social" id="previewSocial">
                            <a href="#" title="Email"><i class="fas fa-envelope"></i></a>
                            <a href="#" title="Téléphone"><i class="fas fa-phone"></i></a>
                            <a href="#" title="LinkedIn"><i class="fab fa-linkedin"></i></a>
                            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                        </div>

                        <hr class="my-3">

                        <div class="text-center">
                            <small class="text-muted">
                                <i class="fas fa-eye me-1"></i>
                                Aperçu en temps réel
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentStep = 1;

        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
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

        // Step Navigation
        function nextStep(step) {
            // Validate current step
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

            // Scroll to top
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        function validateStep(step) {
            if (step === 1) {
                const firstname = document.getElementById('firstname').value;
                const lastname = document.getElementById('lastname').value;
                const email = document.getElementById('email').value;

                if (!firstname || !lastname || !email) {
                    showNotification('Veuillez remplir tous les champs obligatoires', 'warning');
                    return false;
                }

                // Email validation
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    showNotification('Veuillez entrer une adresse email valide', 'warning');
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
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.style.display = 'block';
                    
                    // Update avatar in live preview
                    document.getElementById('previewAvatar').innerHTML = `<img src="${e.target.result}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">`;
                }
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
                document.getElementById('previewAvatar').innerHTML = '<i class="fas fa-user"></i>';
            }
        }

        // Form submission with loading state
        document.getElementById('createCustomerForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            const submitText = document.getElementById('submitText');
            const submitSpinner = document.getElementById('submitSpinner');
            
            submitBtn.disabled = true;
            submitText.textContent = 'Création en cours...';
            submitSpinner.style.display = 'inline-block';
        });

        // Format phone number
        function formatPhone(input) {
            let value = input.value.replace(/\s/g, '');
            let formattedValue = value;
            
            if (value.startsWith('+33')) {
                formattedValue = '+33 ' + value.substring(3).replace(/(.{2})/g, '$1 ').trim();
            } else if (value.startsWith('0')) {
                formattedValue = value.replace(/(.{2})/g, '$1 ').trim();
            }
            
            input.value = formattedValue;
        }

        // Format URL
        function formatUrl(input) {
            if (input.value && !input.value.startsWith('http')) {
                input.value = 'https://' + input.value;
            }
        }

        // Format username for social media
        function formatUsername(input, platform) {
            let value = input.value;
            if (value && !value.startsWith('@')) {
                input.value = '@' + value.replace('@', '');
            }
        }

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
            document.getElementById('slugPreview').textContent = slug;
            
            // Generate admin code
            const code = Math.random().toString(36).substring(2, 8).toUpperCase();
            document.getElementById('codePreview').textContent = code;
        }

        // Copy to clipboard function
        function copyToClipboard(elementId, button) {
            const text = document.getElementById(elementId).textContent;
            
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(text).then(() => {
                    showCopyFeedback(button, true);
                }).catch(() => {
                    fallbackCopy(text, button);
                });
            } else {
                fallbackCopy(text, button);
            }
        }

        function fallbackCopy(text, button) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            
            try {
                document.execCommand('copy');
                showCopyFeedback(button, true);
            } catch (err) {
                showCopyFeedback(button, false);
            }
            
            document.body.removeChild(textArea);
        }

        function showCopyFeedback(button, success) {
            const icon = button.querySelector('i');
            const tooltip = button.nextElementSibling;
            
            if (success) {
                button.classList.add('copied');
                icon.className = 'fas fa-check';
                tooltip.textContent = 'Copié!';
                tooltip.style.background = '#10b981';
            } else {
                tooltip.textContent = 'Erreur!';
                tooltip.style.background = '#ef4444';
            }
            
            tooltip.classList.add('show');
            
            setTimeout(() => {
                button.classList.remove('copied');
                icon.className = 'fas fa-copy';
                tooltip.classList.remove('show');
            }, 2000);
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 
                'linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%)' : 
                'linear-gradient(135deg, #fccb90 0%, #ff9a9e 100%)';
            const textColor = type === 'success' ? '#0f5132' : '#842029';
            const icon = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
            
            notification.style.cssText = `
                position: fixed; top: 20px; right: 20px; background: ${bgColor}; 
                color: ${textColor}; padding: 15px 20px; border-radius: 10px; 
                box-shadow: 0 10px 25px rgba(0,0,0,0.2); z-index: 10000; 
                animation: slideInRight 0.3s ease-out; font-weight: 500; 
                max-width: 300px; display: flex; align-items: center;
            `;
            notification.innerHTML = `
                <i class="fas ${icon} me-3" style="font-size: 1.25rem;"></i>
                <span>${message}</span>
            `;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOutRight 0.3s ease-out';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Add slide animations
        const style = document.createElement('style');
        style.textContent = `
            @keyframes slideInRight { 
                from { opacity: 0; transform: translateX(100px); } 
                to { opacity: 1; transform: translateX(0); } 
            }
            @keyframes slideOutRight { 
                from { opacity: 1; transform: translateX(0); } 
                to { opacity: 0; transform: translateX(100px); } 
            }
        `;
        document.head.appendChild(style);

        // Initialize
        document.addEventListener('DOMContentLoaded', function() {
            updatePreview();
        });
    </script>
</body>
</html>