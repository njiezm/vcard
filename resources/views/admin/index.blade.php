<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administration vCard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #4f46e5;
            --secondary-color: #6366f1;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --card-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
            --transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: var(--light-bg);
            color: #334155;
            line-height: 1.6;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: 250px;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
            color: white;
            padding: 2rem 1rem;
            z-index: 1000;
            transition: var(--transition);
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand {
            color: white;
            text-decoration: none;
            font-size: 1.5rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: var(--transition);
        }

        .sidebar-brand:hover {
            transform: translateX(5px);
            color: white;
        }

        .sidebar-menu {
            list-style: none;
        }

        .sidebar-item {
            display: block;
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 0.5rem;
            transition: var(--transition);
            font-weight: 500;
        }

        .sidebar-item:hover {
            background: rgba(255,255,255,0.1);
            color: white;
            transform: translateX(5px);
        }

        .sidebar-item.active {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Main Content */
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            min-height: 100vh;
            transition: var(--transition);
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .topbar-title {
            font-size: 1.75rem;
            font-weight: 700;
            color: var(--primary-color);
            margin-bottom: 0.25rem;
        }

        .topbar-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        /* Stats Cards */
        .stats-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            transition: var(--transition);
            border: 1px solid #e2e8f0;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }

        .stats-icon {
            width: 50px;
            height: 50px;
            border-radius: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
        }

        .stats-icon.primary { background: linear-gradient(135deg, var(--primary-color), var(--secondary-color)); }
        .stats-icon.success { background: linear-gradient(135deg, #10b981, #34d399); }
        .stats-icon.warning { background: linear-gradient(135deg, #f59e0b, #fbbf24); }
        .stats-icon.info { background: linear-gradient(135deg, #3b82f6, #60a5fa); }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .table-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e293b;
            margin: 0;
        }

        /* Buttons */
        .btn-phoenix {
            padding: 0.625rem 1.25rem;
            border-radius: 0.5rem;
            font-weight: 500;
            border: none;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.875rem;
        }

        .btn-phoenix-primary {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
        }

        .btn-phoenix-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.3);
            color: white;
        }

        .btn-phoenix-success {
            background: linear-gradient(135deg, var(--success-color), #34d399);
            color: white;
        }

        .btn-phoenix-warning {
            background: linear-gradient(135deg, var(--warning-color), #fbbf24);
            color: white;
        }

        .btn-phoenix-danger {
            background: linear-gradient(135deg, var(--danger-color), #f87171);
            color: white;
        }

        .btn-phoenix-info {
            background: linear-gradient(135deg, var(--info-color), #60a5fa);
            color: white;
        }

        .btn-phoenix-secondary {
            background: #64748b;
            color: white;
        }

        .btn-action {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
            margin: 0 0.125rem;
        }

        /* Search and Filters */
        .search-filters-bar {
            background: white;
            padding: 1rem 1.5rem;
            border-radius: 0.75rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            flex-wrap: wrap;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
        }

        .search-input-group {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
        }

        .search-input-group input {
            padding-left: 2.5rem;
            border: 1px solid #e2e8f0;
            border-radius: 0.5rem;
            transition: var(--transition);
        }

        .search-input-group input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            outline: none;
        }

        .filter-chip {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            background: #f1f5f9;
            color: #64748b;
            cursor: pointer;
            transition: var(--transition);
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .filter-chip:hover {
            background: #e2e8f0;
            transform: translateY(-1px);
        }

        .filter-chip.active {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            color: white;
            border-color: var(--primary-color);
        }

        /* Table Styles */
        .table {
            margin: 0;
        }

        .table thead th {
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            padding: 1rem;
            border: none;
            position: relative;
        }

        .table tbody tr {
            transition: var(--transition);
        }

        .table tbody tr:hover {
            background: #f8fafc;
        }

        .table tbody td {
            padding: 1rem;
            vertical-align: middle;
            border-color: #f1f5f9;
        }

        /* Copy Button Styles */
        .copy-btn {
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            transition: var(--transition);
            font-size: 0.875rem;
            margin-left: 0.5rem;
        }

        .copy-btn:hover {
            background: #f1f5f9;
            color: var(--primary-color);
        }

        .copy-btn.copied {
            color: var(--success-color);
            animation: copyPulse 0.6s ease;
        }

        @keyframes copyPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Mobile Cards */
        .mobile-cards-container {
            display: none;
            padding: 1rem;
        }

        .customer-card {
            background: white;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            box-shadow: var(--card-shadow);
            border: 1px solid #e2e8f0;
            transition: var(--transition);
        }

        .customer-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .customer-card-header {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #f1f5f9;
        }

        .customer-card-body {
            margin-bottom: 1rem;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #f8fafc;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            font-weight: 600;
            color: #64748b;
            font-size: 0.875rem;
        }

        .customer-card-actions {
            display: flex;
            gap: 0.5rem;
            padding-top: 0.75rem;
            border-top: 1px solid #f1f5f9;
        }

        /* Loading State */
        .btn-loading {
            position: relative;
            color: transparent !important;
            pointer-events: none;
        }

        .btn-loading::after {
            content: "";
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top: 2px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        /* Alert */
        .alert-phoenix {
            border-radius: 0.5rem;
            border: none;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            animation: slideInDown 0.3s ease-out;
        }

        .alert-phoenix-success { background: #d1fae5; color: #065f46; }

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
            transition: var(--transition);
        }

        .sidebar-toggle:hover {
            transform: scale(1.1);
        }

        /* Sortable Headers */
        .sortable {
            cursor: pointer;
            user-select: none;
            position: relative;
            padding-right: 1.5rem !important;
        }

        .sortable::after {
            content: '⇅';
            position: absolute;
            right: 0.5rem;
            opacity: 0.3;
            font-size: 0.875rem;
        }

        .sortable.sorted-asc::after {
            content: '↑';
            opacity: 1;
            color: var(--primary-color);
        }

        .sortable.sorted-desc::after {
            content: '↓';
            opacity: 1;
            color: var(--primary-color);
        }

        /* Animations */
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeOut {
            from { opacity: 1; transform: translateX(0); }
            to { opacity: 0; transform: translateX(-20px); }
        }

        .removing {
            animation: fadeOut 0.4s ease-out forwards;
        }

        /* Copy Tooltip */
        .copy-tooltip {
            position: fixed;
            background: #1e293b;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            pointer-events: none;
            z-index: 10000;
            opacity: 0;
            transition: opacity 0.3s ease;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }

        .copy-tooltip.show {
            opacity: 1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 1rem; }
            .sidebar-toggle { display: block; }
            .table-responsive { display: none; }
            .mobile-cards-container { display: block; }
            .search-filters-bar { flex-direction: column; align-items: stretch; }
            .search-input-group { max-width: 100%; }
            .topbar { flex-direction: column; text-align: center; gap: 1rem; }
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
            <a href="{{ route('admin.index') }}" class="sidebar-item active">
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
            <div>
                <h1 class="topbar-title">Tableau de bord</h1>
                <p class="text-muted mb-0">Gestion des vCards</p>
            </div>
            <div class="topbar-user">
                <span class="text-muted">Administrateur</span>
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        </header>

        <!-- Content -->
        <div class="content">
            <!-- Success Alert -->
            @if(session('success'))
                <div class="alert alert-phoenix-success alert-phoenix mb-4">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon primary me-3">
                                <i class="fas fa-users"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $customers->count() }}</h3>
                                <p class="text-muted mb-0">Total clients</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon success me-3">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $customers->where('photo', '!=', null)->count() }}</h3>
                                <p class="text-muted mb-0">Avec photo</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon warning me-3">
                                <i class="fas fa-globe"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $customers->where('website', '!=', null)->count() }}</h3>
                                <p class="text-muted mb-0">Avec site web</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 mb-3">
                    <div class="stats-card">
                        <div class="d-flex align-items-center">
                            <div class="stats-icon info me-3">
                                <i class="fas fa-instagram"></i>
                            </div>
                            <div>
                                <h3 class="mb-0">{{ $customers->where('instagram', '!=', null)->count() }}</h3>
                                <p class="text-muted mb-0">Avec Instagram</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters Bar -->
            <div class="search-filters-bar">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" class="form-control" placeholder="Rechercher par nom, email, slug...">
                </div>
                <div class="filter-chip active" data-filter="all">
                    <i class="fas fa-list me-1"></i> Tous
                </div>
                <div class="filter-chip" data-filter="active">
                    <i class="fas fa-toggle-on me-1"></i> Actifs
                </div>
                <div class="filter-chip" data-filter="inactive">
                    <i class="fas fa-toggle-off me-1"></i> Inactifs
                </div>
            </div>

            <!-- Table Card -->
            <div class="table-card">
                <div class="table-header">
                    <h2 class="table-title">Liste des clients</h2>
                    <a href="{{ route('admin.create') }}" class="btn btn-phoenix btn-phoenix-primary">
                        <i class="fas fa-plus"></i>
                        Nouveau client
                    </a>
                </div>
                
                <!-- Desktop Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="customersTable">
                        <thead>
                            <tr>
                                <th class="sortable" data-sort="id">ID</th>
                                <th class="sortable" data-sort="name">Nom</th>
                                <th class="sortable" data-sort="email">Email</th>
                                <th>Slug</th>
                                <th>Code Admin</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                                <tr data-customer-id="{{ $customer->id }}" data-is-active="{{ $customer->is_active ? 'true' : 'false' }}">
                                    <td>
                                        <span class="badge bg-light text-dark">#{{ $customer->id }}</span>
                                    </td>
                                    <td data-name="{{ $customer->firstname }} {{ $customer->lastname }}">
                                        <div class="d-flex align-items-center">
                                            @if($customer->photo)
                                                <img src="{{ asset('storage/' . $customer->photo) }}" 
                                                     alt="{{ $customer->firstname }}" 
                                                     class="rounded-circle me-2" 
                                                     width="32" 
                                                     height="32">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 32px; height: 32px; font-size: 0.875rem;">
                                                    {{ substr($customer->firstname, 0, 1) }}{{ substr($customer->lastname, 0, 1) }}
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-semibold">{{ $customer->firstname }} {{ $customer->lastname }}</div>
                                                @if($customer->phone)
                                                    <small class="text-muted">{{ $customer->phone }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td data-email="{{ $customer->email }}">
                                        @if($customer->email)
                                            <span class="text-muted">{{ $customer->email }}</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <code class="bg-light px-2 py-1 rounded">{{ $customer->slug }}</code>
                                            <button class="copy-btn" onclick="copyToClipboard('{{ $customer->slug }}', this)" title="Copier le slug">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <code class="bg-light px-2 py-1 rounded">{{ $customer->admin_code }}</code>
                                            <button class="copy-btn" onclick="copyToClipboard('{{ $customer->admin_code }}', this)" title="Copier le code admin">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('vcard.show', $customer->slug) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-phoenix btn-phoenix-info btn-action"
                                               title="Voir la vCard">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="/admin/{{ $customer->slug }}" 
                                                target="_blank" 
                                                class="btn btn-sm btn-phoenix btn-phoenix-warning btn-action"
                                                title="Admin client">
                                                    <i class="fas fa-user-cog"></i>
                                                </a>
                                            @if($customer->email)
                                                <button onclick="sendEmail({{ $customer->id }})" 
                                                        class="btn btn-sm btn-phoenix btn-phoenix-success btn-action"
                                                        title="Envoyer l'email de bienvenue">
                                                    <i class="fas fa-envelope"></i>
                                                </button>
                                            @endif
                                            <a href="{{ route('admin.edit', $customer->id) }}" 
                                               class="btn btn-sm btn-phoenix btn-phoenix-secondary btn-action"
                                               title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                        <button onclick="toggleActive({{ $customer->id }})" 
                                                class="btn btn-sm btn-phoenix btn-phoenix-warning btn-action"
                                                title="{{ $customer->is_active ? 'Désactiver la carte' : 'Activer la carte' }}">
                                            <i class="fas {{ $customer->is_active ? 'fa-toggle-on' : 'fa-toggle-off' }}"></i>
                                        </button>
                                        <button onclick="deleteCustomer({{ $customer->id }})" 
                                                class="btn btn-sm btn-phoenix btn-phoenix-danger btn-action"
                                                title="Supprimer le client">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Cards Container -->
                <div class="mobile-cards-container" id="mobileCardsContainer">
                    <!-- Cards will be generated by JavaScript -->
                </div>
            </div>
        </div>
    </main>

    <!-- Validation Modal -->
    <div class="modal fade" id="validationModal" tabindex="-1" aria-labelledby="validationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="validationModalLabel">
                        <i class="fas fa-check-circle me-2"></i>
                        Validation du nouveau client
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <p class="text-center mb-4">
                        Le client <strong id="customerName"></strong> a été créé avec succès. 
                        Que souhaitez-vous faire ?
                    </p>
                    <div class="d-flex justify-content-center flex-wrap">
                        <button type="button" class="btn btn-option btn-success m-2" onclick="validateAndSend('validate_send')">
                            <i class="fas fa-paper-plane me-2"></i>
                            Valider et envoyer l'email
                        </button>
                        <button type="button" class="btn btn-option btn-primary m-2" onclick="validateAndSend('validate_only')">
                            <i class="fas fa-check me-2"></i>
                            Valider sans envoyer
                        </button>
                        <button type="button" class="btn btn-option btn-secondary m-2" onclick="validateAndSend('cancel')">
                            <i class="fas fa-times me-2"></i>
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Email Status Modal -->
    <div class="modal fade" id="emailStatusModal" tabindex="-1" aria-labelledby="emailStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title" id="emailStatusModalLabel">
                        <i class="fas fa-envelope me-2"></i>
                        Statut de l'envoi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div id="emailLoading" class="mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                        <p class="mt-3">Envoi de l'email en cours...</p>
                    </div>
                    <div id="emailSuccess" style="display: none;">
                        <i class="fas fa-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="mt-3 fw-semibold">Email envoyé avec succès !</p>
                    </div>
                    <div id="emailError" style="display: none;">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 3rem;"></i>
                        <p class="mt-3 fw-semibold">Erreur lors de l'envoi</p>
                        <p class="text-muted" id="errorMessage"></p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Copy Tooltip -->
    <div class="copy-tooltip" id="copyTooltip">
        <i class="fas fa-check-circle me-2"></i>
        <span id="copyTooltipText">Copié !</span>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Application State
        const AppState = {
            customers: [],
            currentFilter: 'all',
            searchQuery: '',
            sortKey: null,
            sortDirection: 'asc'
        };

        // Initialize Application
        document.addEventListener('DOMContentLoaded', function() {
            initializeCustomers();
            initializeSearchAndFilter();
            initializeTableSort();
            generateMobileCards();
            setupValidationModal();
        });

        // Initialize Customers Data
        function initializeCustomers() {
            const rows = document.querySelectorAll('#customersTable tbody tr');
            AppState.customers = Array.from(rows).map(row => ({
                id: row.dataset.customerId,
                isActive: row.dataset.isActive === 'true',
                name: row.querySelector('td[data-name]').dataset.name,
                email: row.querySelector('td[data-email]').dataset.email,
                slug: row.querySelector('code').textContent,
                adminCode: row.querySelectorAll('code')[1].textContent,
                element: row
            }));
        }

        // Toggle Sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            if (window.innerWidth <= 768 && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                sidebar.classList.remove('active');
            }
        });

        // Copy to Clipboard Function
        async function copyToClipboard(text, button) {
            try {
                // Use modern Clipboard API
                await navigator.clipboard.writeText(text);
                
                // Visual feedback
                const icon = button.querySelector('i');
                const originalClass = icon.className;
                
                // Change icon to checkmark
                icon.className = 'fas fa-check';
                button.classList.add('copied');
                
                // Show tooltip
                showCopyTooltip(button, 'Copié !');
                
                // Reset after 2 seconds
                setTimeout(() => {
                    icon.className = originalClass;
                    button.classList.remove('copied');
                }, 2000);
                
            } catch (err) {
                // Fallback for older browsers
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
                    showCopyTooltip(button, 'Copié !');
                } catch (err) {
                    showCopyTooltip(button, 'Erreur de copie');
                }
                
                document.body.removeChild(textArea);
            }
        }

        // Show Copy Tooltip
        function showCopyTooltip(element, message) {
            const tooltip = document.getElementById('copyTooltip');
            const tooltipText = document.getElementById('copyTooltipText');
            const rect = element.getBoundingClientRect();
            
            tooltipText.textContent = message;
            tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
            tooltip.style.top = rect.top - 40 + 'px';
            tooltip.classList.add('show');
            
            setTimeout(() => {
                tooltip.classList.remove('show');
            }, 2000);
        }

        // Search and Filter Functionality
        function initializeSearchAndFilter() {
            const searchInput = document.getElementById('searchInput');
            const filterChips = document.querySelectorAll('.filter-chip');

            searchInput.addEventListener('input', debounce(function(e) {
                AppState.searchQuery = e.target.value.toLowerCase();
                applyFilters();
            }, 300));

            filterChips.forEach(chip => {
                chip.addEventListener('click', function() {
                    filterChips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    AppState.currentFilter = this.dataset.filter;
                    applyFilters();
                });
            });
        }

        // Debounce Function
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Apply Filters
        function applyFilters() {
            const filteredCustomers = AppState.customers.filter(customer => {
                const matchesSearch = !AppState.searchQuery || 
                    customer.name.toLowerCase().includes(AppState.searchQuery) ||
                    customer.email.toLowerCase().includes(AppState.searchQuery) ||
                    customer.slug.toLowerCase().includes(AppState.searchQuery);

                const matchesFilter = AppState.currentFilter === 'all' ||
                    (AppState.currentFilter === 'active' && customer.isActive) ||
                    (AppState.currentFilter === 'inactive' && !customer.isActive);

                return matchesSearch && matchesFilter;
            });

            // Update table rows
            AppState.customers.forEach(customer => {
                const isVisible = filteredCustomers.includes(customer);
                customer.element.style.display = isVisible ? '' : 'none';
            });

            // Update mobile cards
            updateMobileCards(filteredCustomers);
        }

        // Table Sort Functionality
        function initializeTableSort() {
            const headers = document.querySelectorAll('th.sortable');
            
            headers.forEach(header => {
                header.addEventListener('click', () => {
                    const sortKey = header.dataset.sort;
                    
                    // Reset other headers
                    headers.forEach(h => h.classList.remove('sorted-asc', 'sorted-desc'));
                    
                    // Determine sort direction
                    if (AppState.sortKey === sortKey) {
                        AppState.sortDirection = AppState.sortDirection === 'asc' ? 'desc' : 'asc';
                    } else {
                        AppState.sortKey = sortKey;
                        AppState.sortDirection = 'asc';
                    }
                    
                    header.classList.add(`sorted-${AppState.sortDirection}`);
                    sortTable();
                });
            });
        }

        // Sort Table
        function sortTable() {
            AppState.customers.sort((a, b) => {
                let aVal, bVal;
                
                if (AppState.sortKey === 'id') {
                    aVal = parseInt(a.id);
                    bVal = parseInt(b.id);
                } else if (AppState.sortKey === 'name') {
                    aVal = a.name;
                    bVal = b.name;
                } else if (AppState.sortKey === 'email') {
                    aVal = a.email;
                    bVal = b.email;
                }
                
                if (aVal < bVal) return AppState.sortDirection === 'asc' ? -1 : 1;
                if (aVal > bVal) return AppState.sortDirection === 'asc' ? 1 : -1;
                return 0;
            });
            
            // Reorder DOM elements
            const tbody = document.querySelector('#customersTable tbody');
            AppState.customers.forEach(customer => {
                tbody.appendChild(customer.element);
            });
        }

        // Generate Mobile Cards
        function generateMobileCards() {
            const container = document.getElementById('mobileCardsContainer');
            container.innerHTML = '';
            
            AppState.customers.forEach(customer => {
                const row = customer.element;
                const phone = row.querySelector('small')?.textContent || '';
                const avatar = row.querySelector('img')?.src || null;
                const initials = avatar ? null : customer.name.split(' ').map(n => n[0]).join('');

                const card = document.createElement('div');
                card.className = 'customer-card';
                card.dataset.customerId = customer.id;
                card.dataset.isActive = customer.isActive;
                card.dataset.name = customer.name;
                card.dataset.email = customer.email;
                card.dataset.slug = customer.slug;
                
                card.innerHTML = `
                    <div class="customer-card-header">
                        ${avatar ? `<img src="${avatar}" class="rounded-circle me-3" width="48" height="48">` : 
                          `<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px; font-size: 1.2rem;">${initials}</div>`}
                        <div>
                            <h5 class="mb-0">${customer.name}</h5>
                            <small class="text-muted">${phone}</small>
                        </div>
                    </div>
                    <div class="customer-card-body">
                        <div class="info-row">
                            <span class="info-label">Email:</span> 
                            <span>${customer.email || '-'}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Slug:</span> 
                            <div class="d-flex align-items-center">
                                <code>${customer.slug}</code>
                                <button class="copy-btn" onclick="copyToClipboard('${customer.slug}', this)" title="Copier le slug">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                        <div class="info-row">
                            <span class="info-label">Code Admin:</span> 
                            <div class="d-flex align-items-center">
                                <code>${customer.adminCode}</code>
                                <button class="copy-btn" onclick="copyToClipboard('${customer.adminCode}', this)" title="Copier le code admin">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="customer-card-actions">
                        <button onclick="toggleActive(${customer.id})" class="btn btn-sm btn-phoenix-${customer.isActive ? 'warning' : 'success'}" title="${customer.isActive ? 'Désactiver' : 'Activer'}">
                            <i class="fas fa-toggle-${customer.isActive ? 'on' : 'off'}"></i>
                        </button>
                        <button onclick="deleteCustomer(${customer.id})" class="btn btn-sm btn-phoenix-danger" title="Supprimer">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                container.appendChild(card);
            });
        }

        // Update Mobile Cards
        function updateMobileCards(visibleCustomers) {
            const cards = document.querySelectorAll('.customer-card');
            cards.forEach(card => {
                const customerId = card.dataset.customerId;
                const isVisible = visibleCustomers.some(c => c.id === customerId);
                card.style.display = isVisible ? '' : 'none';
            });
        }

        // API Call Function
        async function apiCall(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                }
            };
            const finalOptions = { ...defaultOptions, ...options };

            try {
                const response = await fetch(url, finalOptions);
                const data = await response.json();
                
                if (!response.ok) {
                    throw new Error(data.message || 'Une erreur est survenue.');
                }
                
                return data;
            } catch (error) {
                console.error('API Call Error:', error);
                showNotification(error.message || 'Erreur réseau.', 'error');
                throw error;
            }
        }

        // Set Button Loading State
        function setButtonLoading(button, isLoading) {
            if (isLoading) {
                button.classList.add('btn-loading');
                button.disabled = true;
            } else {
                button.classList.remove('btn-loading');
                button.disabled = false;
            }
        }

        // Toggle Active Status
        async function toggleActive(customerId) {
            const button = event.target.closest('button');
            const originalContent = button.innerHTML;
            setButtonLoading(button, true);

            try {
                const url = `{{ route('admin.customer.toggleActive', ['customer' => ':id']) }}`.replace(':id', customerId);
                const data = await apiCall(url, { method: 'POST' });
                
                // Update state
                const customer = AppState.customers.find(c => c.id === customerId);
                if (customer) {
                    customer.isActive = data.is_active;
                    customer.element.dataset.isActive = data.is_active;
                }
                
                // Update UI
                const icon = button.querySelector('i');
                icon.className = `fas fa-toggle-${data.is_active ? 'on' : 'off'}`;
                button.className = `btn btn-sm btn-phoenix-${data.is_active ? 'warning' : 'success'} btn-action`;
                button.title = data.is_active ? 'Désactiver la carte' : 'Activer la carte';
                
                // Update mobile card if exists
                const mobileCard = document.querySelector(`.customer-card[data-customer-id="${customerId}"]`);
                if (mobileCard) {
                    mobileCard.dataset.isActive = data.is_active;
                    const mobileButton = mobileCard.querySelector('button[onclick*="toggleActive"]');
                    if (mobileButton) {
                        mobileButton.className = `btn btn-sm btn-phoenix-${data.is_active ? 'warning' : 'success'}`;
                        mobileButton.querySelector('i').className = `fas fa-toggle-${data.is_active ? 'on' : 'off'}`;
                    }
                }
                
                showNotification(data.message, 'success');
            } catch (error) {
                // Error already handled by apiCall
            } finally {
                setButtonLoading(button, false);
            }
        }

        // Delete Customer
        async function deleteCustomer(customerId) {
            if (!confirm('Voulez-vous vraiment supprimer ce client ? Cette action est irréversible.')) {
                return;
            }

            const button = event.target.closest('button');
            setButtonLoading(button, true);

            try {
                const url = `{{ route('admin.customer.destroy', ['customer' => ':id']) }}`.replace(':id', customerId);
                const data = await apiCall(url, { method: 'DELETE' });

                // Animate and remove
                const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
                const card = document.querySelector(`.customer-card[data-customer-id="${customerId}"]`);
                
                if (row) {
                    row.classList.add('removing');
                    setTimeout(() => {
                        row.remove();
                        // Update state
                        AppState.customers = AppState.customers.filter(c => c.id !== customerId);
                    }, 400);
                }
                
                if (card) {
                    card.style.display = 'none';
                    setTimeout(() => card.remove(), 400);
                }
                
                showNotification(data.message, 'success');
            } catch (error) {
                // Error already handled by apiCall
            } finally {
                setButtonLoading(button, false);
            }
        }

        // Send Email
        let currentCustomerId = null;

        function sendEmail(customerId) {
            currentCustomerId = customerId;
            const statusModal = new bootstrap.Modal(document.getElementById('emailStatusModal'));
            
            // Reset modal state
            document.getElementById('emailLoading').style.display = 'block';
            document.getElementById('emailSuccess').style.display = 'none';
            document.getElementById('emailError').style.display = 'none';
            
            statusModal.show();

            const url = "{{ route('admin.send.email', ['customer' => ':id']) }}".replace(':id', customerId);
            
            apiCall(url, { method: 'POST' })
                .then(data => {
                    document.getElementById('emailLoading').style.display = 'none';
                    document.getElementById('emailSuccess').style.display = 'block';
                    setTimeout(() => statusModal.hide(), 2000);
                })
                .catch(error => {
                    document.getElementById('emailLoading').style.display = 'none';
                    document.getElementById('emailError').style.display = 'block';
                    document.getElementById('errorMessage').textContent = error.message;
                });
        }

        // Setup Validation Modal
        function setupValidationModal() {
            @if(session('show_validation_modal') && session('new_customer'))
                const customer = @json(session('new_customer'));
                currentCustomerId = customer.id;
                document.getElementById('customerName').textContent = customer.firstname + ' ' + customer.lastname;
                const modal = new bootstrap.Modal(document.getElementById('validationModal'));
                modal.show();
            @endif
        }

        // Validate and Send
        function validateAndSend(action) {
            if (!currentCustomerId) return;
            
            const modal = bootstrap.Modal.getInstance(document.getElementById('validationModal'));
            modal.hide();
            
            if (action === 'cancel') return;
            
            apiCall('{{ route("admin.validate.send") }}', {
                method: 'POST',
                body: JSON.stringify({ 
                    customer_id: currentCustomerId, 
                    action: action 
                })
            }).then(data => {
                showNotification(data.message, 'success');
            }).catch(() => {
                // Error already handled by apiCall
            });
        }

        // Show Notification
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
    </script>
</body>
</html>