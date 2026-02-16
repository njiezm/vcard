@extends('layouts.admin')

@section('title', 'Gestion des Clients - Phoenix Admin')

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

@php
 $customersArray = $customers->map(function($customer) {
    return [
        'id' => $customer->id,
        'name' => $customer->firstname . ' ' . $customer->lastname,
        'email' => $customer->email,
        'slug' => $customer->slug ?? '',
        'admin_code' => $customer->admin_code,
        'is_active' => $customer->is_active,
        'photo' => $customer->photo ? asset('storage/' . $customer->photo) : null,
        'phone' => $customer->phone,
        'created_at' => $customer->created_at?->format('Y-m-d H:i:s')
    ];
})->toArray();
@endphp



@section('content')
<div class="container-fluid py-4">
    <!-- En-tête de la page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Gestion des clients</h1>
            <p class="text-muted mb-0">Gérez tous vos clients et leurs cartes virtuelles</p>
        </div>
        <a href="{{ route('admin.create') }}" class="btn btn-phoenix btn-primary">
            <i class="fas fa-plus me-2"></i>
            Ajouter un client
        </a>
    </div>

    <!-- Filtres et recherche -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" 
                               class="form-control border-start-0" 
                               id="searchInput" 
                               placeholder="Rechercher par nom, email ou téléphone..."
                               autocomplete="off">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="d-flex gap-2">
                        <button class="btn btn-phoenix btn-outline-secondary filter-chip active" 
                                data-filter="all">
                            <i class="fas fa-users me-1"></i>
                            Tous
                            <span class="badge bg-secondary ms-1">{{ $customers->count() }}</span>
                        </button>
                        <button class="btn btn-phoenix btn-outline-success filter-chip" 
                                data-filter="active">
                            <i class="fas fa-check-circle me-1"></i>
                            Actifs
                            <span class="badge bg-success ms-1">{{ $customers->where('is_active', true)->count() }}</span>
                        </button>
                        <button class="btn btn-phoenix btn-outline-danger filter-chip" 
                                data-filter="inactive">
                            <i class="fas fa-times-circle me-1"></i>
                            Inactifs
                            <span class="badge bg-danger ms-1">{{ $customers->where('is_active', false)->count() }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des clients -->
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="customersTable">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Téléphone</th>
                            <th>Carte virtuelle</th>
                            <th>Statut</th>
                            <th>Actions</th>
                            <th>Lien</th>
                        </tr>
                    </thead>
                    <tbody id="customersTableBody">
                        @forelse($customers as $customer)
                            <tr data-customer-id="{{ $customer->id }}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-3">
                                            @if($customer->photo)
                                                <img src="{{ asset('storage/' . $customer->photo) }}" 
                                                     alt="{{ $customer->name }}" 
                                                     class="rounded-circle"
                                                     onerror="this.src='{{ asset('images/default-avatar.png') }}'">
                                            @else
                                                <div class="avatar-name rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center">
                                                    <span>{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $customer->name }}</h6>
                                            <small class="text-muted">{{ $customer->email }}</small>
                                            @if($customer->phone)
                                                <br><small class="text-muted">{{ $customer->phone }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $customer->phone ?? '-' }}</td>
                                <td>
                                    @if($customer->slug)
                                        <a href="{{ route('vcard.show', $customer->slug) }}" 
                                           target="_blank" 
                                           class="text-primary text-decoration-none">
                                            {{ route('vcard.show', $customer->slug) }}
                                        </a>
                                    @else
                                        <span class="text-muted">Non défini</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $customer->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $customer->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-phoenix-secondary" 
                                                type="button" 
                                                data-bs-toggle="dropdown" 
                                                aria-expanded="false">
                                            <i class="fas fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.edit', $customer->id) }}">
                                                    <i class="fas fa-edit me-2"></i> Modifier
                                                </a>
                                            </li>
                                            <li>
                                                <button class="dropdown-item send-email-btn" 
                                                        data-customer-id="{{ $customer->id }}"
                                                        data-customer-name="{{ $customer->name }}"
                                                        data-customer-email="{{ $customer->email }}">
                                                    <i class="fas fa-envelope me-2"></i> Envoyer l'e-mail
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item toggle-active-btn" 
                                                        data-customer-id="{{ $customer->id }}"
                                                        data-customer-name="{{ $customer->name }}"
                                                        data-is-active="{{ $customer->is_active ? 'true' : 'false' }}">
                                                    <i class="fas fa-toggle-{{ $customer->is_active ? 'off' : 'on' }} me-2"></i> 
                                                    {{ $customer->is_active ? 'Désactiver' : 'Activer' }}
                                                </button>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <button class="dropdown-item text-danger delete-customer-btn" 
                                                        data-customer-id="{{ $customer->id }}"
                                                        data-customer-name="{{ $customer->name }}">
                                                    <i class="fas fa-trash me-2"></i> Supprimer
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                                <td>
                                    @if($customer->slug)
                                        <button class="btn btn-sm btn-phoenix-secondary copy-link-btn" 
                                                data-link="{{ route('vcard.show', $customer->slug) }}"
                                                title="Copier le lien">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-phoenix-secondary" disabled>
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-users-slash fa-3x mb-3 d-block"></i>
                                        <h5>Aucun client trouvé</h5>
                                        <p>Commencez par ajouter votre premier client</p>
                                        <a href="{{ route('admin.create') }}" class="btn btn-phoenix btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i>
                                            Ajouter un client
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Pagination -->
        @if($customers->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Affichage de {{ $customers->firstItem() }} à {{ $customers->lastItem() }} 
                        sur {{ $customers->total() }} résultats
                    </div>
                    {{ $customers->links() }}
                </div>
            </div>
        @endif
    </div>

    <!-- Statistiques -->
    <div class="row mt-4">
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Total Clients</h6>
                            <h3 class="mb-0">{{ $customers->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <div class="icon-box bg-primary-subtle text-primary">
                                <i class="fas fa-users"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Clients Actifs</h6>
                            <h3 class="mb-0">{{ $customers->where('is_active', true)->count() }}</h3>
                        </div>
                        <div class="ms-3">
                            <div class="icon-box bg-success-subtle text-success">
                                <i class="fas fa-check-circle"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Taux d'activation</h6>
                            <h3 class="mb-0">
                                @php
                                    $rate = $customers->count() > 0 
                                        ? round(($customers->where('is_active', true)->count() / $customers->count()) * 100, 1) 
                                        : 0;
                                @endphp
                                {{ $rate }}%
                            </h3>
                        </div>
                        <div class="ms-3">
                            <div class="icon-box bg-info-subtle text-info">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="text-muted mb-1">Nouveaux ce mois</h6>
                            <h3 class="mb-0">
                                @php
                                    $newThisMonth = $customers->filter(function($customer) {
                                        return $customer->created_at->isCurrentMonth();
                                    })->count();
                                @endphp
                                {{ $newThisMonth }}
                            </h3>
                        </div>
                        <div class="ms-3">
                            <div class="icon-box bg-warning-subtle text-warning">
                                <i class="fas fa-user-plus"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Envoyer Email -->
<div class="modal fade" id="sendEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-envelope text-primary me-2"></i>
                    Envoyer l'e-mail de bienvenue
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Confirmez-vous l'envoi de l'e-mail à <strong id="emailCustomerName"></strong> ?</p>
                <p class="text-muted">Adresse e-mail : <strong id="emailCustomerEmail"></strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmSendEmail">
                    <i class="fas fa-paper-plane me-2"></i>Envoyer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Toggle Active -->
<div class="modal fade" id="toggleActiveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Changer le statut
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="toggleMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-warning" id="confirmToggleActive">
                    <i class="fas fa-check me-2"></i>Confirmer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Supprimer -->
<div class="modal fade" id="deleteCustomerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-trash text-danger me-2"></i>
                    Supprimer le client
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer <strong id="deleteCustomerName"></strong> ?</p>
                <p class="text-danger">Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteCustomer">
                    <i class="fas fa-trash me-2"></i>Supprimer
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="fas fa-info-circle text-primary me-2"></i>
            <strong class="me-auto">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Message
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.icon-box {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.avatar {
    width: 40px;
    height: 40px;
    overflow: hidden;
}

.avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.avatar-name {
    width: 40px;
    height: 40px;
    font-weight: 600;
    font-size: 1rem;
}

.filter-chip.active {
    background-color: var(--phoenix-primary) !important;
    border-color: var(--phoenix-primary) !important;
    color: white !important;
}

.table th {
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: #6c757d;
}

.dropdown-menu {
    min-width: 180px;
}

.btn-phoenix {
    border-radius: 8px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-phoenix:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.card {
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    transition: box-shadow 0.2s ease;
}

.card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let currentCustomerId = null;
    const toastEl = document.getElementById('liveToast');
    const toast = new bootstrap.Toast(toastEl);
    
    // Fonction pour afficher une notification
    function showToast(message, type = 'info') {
        const toastMessage = document.getElementById('toastMessage');
        const toastHeader = toastEl.querySelector('.toast-header i');
        
        toastMessage.textContent = message;
        
        // Mettre à jour l'icône et la couleur selon le type
        toastHeader.className = 'fas me-2';
        switch(type) {
            case 'success':
                toastHeader.classList.add('fa-check-circle', 'text-success');
                break;
            case 'error':
                toastHeader.classList.add('fa-exclamation-circle', 'text-danger');
                break;
            case 'warning':
                toastHeader.classList.add('fa-exclamation-triangle', 'text-warning');
                break;
            default:
                toastHeader.classList.add('fa-info-circle', 'text-primary');
        }
        
        toast.show();
    }
    
    // Gestionnaire pour le bouton d'envoi d'e-mail
    document.addEventListener('click', function(e) {
        if (e.target.closest('.send-email-btn')) {
            const btn = e.target.closest('.send-email-btn');
            currentCustomerId = btn.dataset.customerId;
            
            document.getElementById('emailCustomerName').textContent = btn.dataset.customerName;
            document.getElementById('emailCustomerEmail').textContent = btn.dataset.customerEmail;
            
            const modal = new bootstrap.Modal(document.getElementById('sendEmailModal'));
            modal.show();
        }
    });
    
    // Confirmation envoi e-mail
    document.getElementById('confirmSendEmail').addEventListener('click', function() {
        if (!currentCustomerId) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Envoi en cours...';
        
        fetch(
    `{{ route('admin.send.email', ['customer' => ':id']) }}`.replace(':id', currentCustomerId),
    {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    }
)

        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('sendEmailModal')).hide();
            
            if (data.success) {
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur réseau', 'error');
            bootstrap.Modal.getInstance(document.getElementById('sendEmailModal')).hide();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Envoyer';
            currentCustomerId = null;
        });
    });
    
    // Gestionnaire pour le bouton toggle active
    document.addEventListener('click', function(e) {
        if (e.target.closest('.toggle-active-btn')) {
            const btn = e.target.closest('.toggle-active-btn');
            currentCustomerId = btn.dataset.customerId;
            const isActive = btn.dataset.isActive === 'true';
            const action = isActive ? 'désactiver' : 'activer';
            
            document.getElementById('toggleMessage').innerHTML = 
                `Êtes-vous sûr de vouloir <strong>${action}</strong> la carte de <strong>${btn.dataset.customerName}</strong> ?<br>
                <small class="text-muted">${isActive ? 'La carte ne sera plus accessible.' : 'La carte sera accessible publiquement.'}</small>`;
            
            const modal = new bootstrap.Modal(document.getElementById('toggleActiveModal'));
            modal.show();
        }
    });
    
    // Confirmation toggle active
    document.getElementById('confirmToggleActive').addEventListener('click', function() {
        if (!currentCustomerId) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';
        
       fetch(`{{ route('admin.customer.toggleActive', ['customer' => ':id']) }}`.replace(':id', currentCustomerId), {

            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('toggleActiveModal')).hide();
            
            if (data.success) {
                // Mettre à jour le statut dans le tableau
                const row = document.querySelector(`tr[data-customer-id="${currentCustomerId}"]`);
                if (row) {
                    const statusBadge = row.querySelector('td:nth-child(4) .badge');
                    if (statusBadge) {
                        statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
                        statusBadge.className = `badge ${data.is_active ? 'bg-success' : 'bg-danger'}`;
                    }
                    
                    // Mettre à jour le bouton dans le dropdown
                    const toggleBtn = row.querySelector('.toggle-active-btn');
                    if (toggleBtn) {
                        toggleBtn.dataset.isActive = data.is_active;
                        toggleBtn.innerHTML = `<i class="fas fa-toggle-${data.is_active ? 'off' : 'on'} me-2"></i>${data.is_active ? 'Désactiver' : 'Activer'}`;
                    }
                }
                
                showToast(data.message, 'success');
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur réseau', 'error');
            bootstrap.Modal.getInstance(document.getElementById('toggleActiveModal')).hide();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Confirmer';
            currentCustomerId = null;
        });
    });
    
    // Gestionnaire pour le bouton supprimer
    document.addEventListener('click', function(e) {
        if (e.target.closest('.delete-customer-btn')) {
            const btn = e.target.closest('.delete-customer-btn');
            currentCustomerId = btn.dataset.customerId;
            
            document.getElementById('deleteCustomerName').textContent = btn.dataset.customerName;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteCustomerModal'));
            modal.show();
        }
    });
    
    // Confirmation suppression
    document.getElementById('confirmDeleteCustomer').addEventListener('click', function() {
        if (!currentCustomerId) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Suppression...';
        
        fetch(`{{ route('admin.customer.destroy', ['customer' => ':id']) }}`.replace(':id', currentCustomerId), {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            bootstrap.Modal.getInstance(document.getElementById('deleteCustomerModal')).hide();
            
            if (data.success) {
                // Supprimer la ligne du tableau
                const row = document.querySelector(`tr[data-customer-id="${currentCustomerId}"]`);
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                }
                
                showToast(data.message, 'success');
                
                // Mettre à jour les compteurs
                updateCounters();
            } else {
                showToast(data.message || 'Une erreur est survenue', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showToast('Erreur réseau', 'error');
            bootstrap.Modal.getInstance(document.getElementById('deleteCustomerModal')).hide();
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash me-2"></i>Supprimer';
            currentCustomerId = null;
        });
    });
    
    // Gestionnaire pour copier le lien
    document.addEventListener('click', function(e) {
        if (e.target.closest('.copy-link-btn')) {
            const btn = e.target.closest('.copy-link-btn');
            const link = btn.dataset.link;
            
            navigator.clipboard.writeText(link).then(() => {
                const originalIcon = btn.innerHTML;
                btn.innerHTML = '<i class="fas fa-check text-success"></i>';
                
                setTimeout(() => {
                    btn.innerHTML = originalIcon;
                }, 2000);
                
                showToast('Lien copié dans le presse-papiers', 'success');
            }).catch(err => {
                console.error('Erreur:', err);
                showToast('Erreur lors de la copie', 'error');
            });
        }
    });
    
    // Fonction pour mettre à jour les compteurs
    function updateCounters() {
        const rows = document.querySelectorAll('#customersTableBody tr');
        const activeCount = document.querySelectorAll('#customersTableBody tr .badge.bg-success').length;
        
        // Mettre à jour les badges dans les filtres
        document.querySelector('.filter-chip[data-filter="all"] .badge').textContent = rows.length;
        document.querySelector('.filter-chip[data-filter="active"] .badge').textContent = activeCount;
        document.querySelector('.filter-chip[data-filter="inactive"] .badge').textContent = rows.length - activeCount;
    }
    
    // Gestionnaire de recherche
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function(e) {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = e.target.value.toLowerCase();
                const rows = document.querySelectorAll('#customersTableBody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            }, 300);
        });
    }
    
    // Gestionnaire des filtres
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            // Mettre à jour le filtre actif
            document.querySelectorAll('.filter-chip').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            const rows = document.querySelectorAll('#customersTableBody tr');
            
            rows.forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else if (filter === 'active') {
                    const isActive = row.querySelector('.badge.bg-success');
                    row.style.display = isActive ? '' : 'none';
                } else if (filter === 'inactive') {
                    const isInactive = row.querySelector('.badge.bg-danger');
                    row.style.display = isInactive ? '' : 'none';
                }
            });
        });
    });
});
</script>
@endpush