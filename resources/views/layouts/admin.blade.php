<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Tableau de bord') | Administration VCard</title>
    <meta name="description" content="@yield('description', 'Tableau de bord d\'administration pour la gestion des cartes VCard')">
    <meta name="keywords" content="vcard, administration, dashboard, gestion">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="@yield('title', 'Tableau de bord') | Administration VCard">
    <meta property="og:description" content="@yield('description', 'Tableau de bord d\'administration')">
    <meta property="og:type" content="website">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    
    <!-- Fonts: Inter est la base du style Phoenix -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @stack('styles')
    @yield('head')

    <style>
        :root {
            --phoenix-bg: #f5f7fa;
            --phoenix-sidebar-bg: #ffffff;
            --phoenix-navbar-bg: rgba(255, 255, 255, 0.8);
            --phoenix-border-color: #e3e6ed;
            --phoenix-text-main: #3874ff;
            --phoenix-text-muted: #5e6e82;
            --phoenix-heading: #232e3e;
            --phoenix-sidebar-width: 250px;
            --phoenix-transition: all 0.2s ease-in-out;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--phoenix-bg);
            color: var(--phoenix-heading);
            overflow-x: hidden;
        }

        /* Dark Theme Support (Base sur votre cookie) */
        body.dark-theme {
            --phoenix-bg: #0f172a;
            --phoenix-sidebar-bg: #1e293b;
            --phoenix-navbar-bg: rgba(30, 41, 59, 0.8);
            --phoenix-border-color: #334155;
            --phoenix-heading: #f1f5f9;
            --phoenix-text-muted: #94a3b8;
        }

        /* --- Sidebar Style Phoenix --- */
        .sidebar {
            width: var(--phoenix-sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--phoenix-sidebar-bg);
            border-right: 1px solid var(--phoenix-border-color);
            z-index: 1030;
            transition: var(--phoenix-transition);
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar-brand {
            font-weight: 700;
            font-size: 1.15rem;
            color: var(--phoenix-heading);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand i {
            color: var(--phoenix-text-main);
        }

        .nav-label {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05rem;
            color: var(--phoenix-text-muted);
        }

        .nav-list {
            list-style: none;
            padding: 0 0.75rem;
            margin: 0;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 0.6rem 1rem;
            color: var(--phoenix-text-muted);
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            font-size: 0.9rem;
            margin-bottom: 2px;
            transition: var(--phoenix-transition);
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
            text-align: center;
        }

        .nav-link:hover {
            background-color: rgba(0,0,0,0.03);
            color: var(--phoenix-heading);
        }

        .nav-link.active {
            background-color: rgba(56, 116, 255, 0.1);
            color: var(--phoenix-text-main);
        }

        /* --- Main Content & Topbar --- */
        .main-wrapper {
            margin-left: var(--phoenix-sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--phoenix-transition);
        }

        .topbar {
            height: 65px;
            background: var(--phoenix-navbar-bg);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--phoenix-border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .search-box {
            position: relative;
            width: 300px;
        }

        .search-box input {
            background: rgba(0,0,0,0.05);
            border: none;
            border-radius: 20px;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.85rem;
            width: 100%;
            color: var(--phoenix-heading);
        }

        .search-box i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--phoenix-text-muted);
        }

        .page-content {
            padding: 2rem;
            flex-grow: 1;
        }

        .btn-phoenix {
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
            padding: 0.5rem 1rem;
            border: 1px solid var(--phoenix-border-color);
            background: var(--phoenix-sidebar-bg);
            color: var(--phoenix-heading);
        }

        .user-avatar {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            background: var(--phoenix-text-main);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            cursor: pointer;
        }

        @media (max-width: 992px) {
            .sidebar { left: -var(--phoenix-sidebar-width); }
            .sidebar.show { left: 0; }
            .main-wrapper { margin-left: 0; }
        }

        
    </style>
</head>
<body class="h-100 @if(request()->cookie('theme') === 'dark') dark-theme @endif">

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('admin.index') }}" class="sidebar-brand">
                <i class="fas fa-bolt"></i>
                <span>VCard Admin</span>
            </a>
            <button class="btn d-lg-none" onclick="toggleSidebar()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <nav class="mt-2">
            <div class="nav-label">Menu Principal</div>
            <ul class="nav-list">
                <li>
                    <a href="{{ route('admin.index') }}" class="nav-link {{ request()->routeIs('admin.index') ? 'active' : '' }}">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.orders') }}" class="nav-link">
                        <i class="fas fa-user-friends"></i> <span>Commandes</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.create') }}" class="nav-link {{ request()->routeIs('admin.create') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i> <span>Nouveau Client</span>
                    </a>
                </li>
            </ul>

            <div class="nav-label">Analyse</div>
            <ul class="nav-list">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-chart-bar"></i> <span>Statistiques</span>
                    </a>
                </li>
            </ul>

            <div class="nav-label">Système</div>
            <ul class="nav-list">
                <li>
                    <a href="#" class="nav-link">
                        <i class="fas fa-cog"></i> <span>Paramètres</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.logout') }}" class="nav-link text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Topbar -->
        <header class="topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn p-0 d-lg-none" onclick="toggleSidebar()">
                    <i class="fas fa-bars fs-4"></i>
                </button>
                <div class="search-box d-none d-md-block">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Rechercher un client...">
                </div>
            </div>

            <div class="d-flex align-items-center gap-3">
                <!-- Export Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-phoenix d-none d-sm-block dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-download me-2"></i>Exporter
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-csv me-2 text-muted"></i>CSV</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2 text-muted"></i>Excel</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-file-pdf me-2 text-muted"></i>PDF</a></li>
                    </ul>
                </div>

                <div class="dropdown">
                    <div class="user-avatar" data-bs-toggle="dropdown" aria-expanded="false">
                        {{ substr(Auth::user()->name ?? 'AD', 0, 2) }}
                    </div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 p-2 mt-2">
                        <li><a class="dropdown-item rounded-2" href="#"><i class="fas fa-user me-2 small text-muted"></i>Profil</a></li>
                        <li><a class="dropdown-item rounded-2" href="#"><i class="fas fa-key me-2 small text-muted"></i>Sécurité</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item rounded-2 text-danger" href="{{ route('admin.logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt me-2 small"></i>Déconnexion
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="page-content" id="main-content">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="py-3 px-4 bg-white border-top text-center text-md-start">
            <span class="text-muted small">© {{ date('Y') }} VCard Admin — Style Phoenix.</span>
        </footer>
    </div>

    <!-- Hidden Logout Form -->
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/admin.js') }}" defer></script>
    
    @stack('scripts')
    @yield('scripts')

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Gestion simplifiée du thème (compatible avec votre logique de cookie)
        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }
    </script>
</body>
</html>