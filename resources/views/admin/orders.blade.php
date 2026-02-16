@extends('layouts.admin')

@section('title', 'Gestion des Commandes')

@section('content')
<div class="container-fluid py-4">
    <!-- Enhanced Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">
                <i class="fas fa-shopping-cart me-2 text-primary"></i>
                Gestion des Commandes
            </h2>
            <p class="text-muted mb-0">
                <span id="orderCount">{{ $orders->total() }}</span> commandes au total
                @if(request('search'))
                    - Filtrage: <span class="badge bg-info">{{ request('search') }}</span>
                @endif
            </p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary" onclick="exportOrders()">
                <i class="fas fa-download me-2"></i>Exporter
            </button>
            <button type="button" class="btn btn-primary" onclick="showBulkActions()">
                <i class="fas fa-tasks me-2"></i>Actions groupées
            </button>
        </div>
    </div>

    <!-- Enhanced Stats Cards with Charts -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $stats['total_orders'] }}</h3>
                            <p class="mb-0 opacity-75">Total Commandes</p>
                            <small class="opacity-50">
                                <i class="fas fa-arrow-up me-1"></i>
                                +12% ce mois
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-2 p-3">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: 75%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $stats['pending_orders'] }}</h3>
                            <p class="mb-0 opacity-75">En Attente</p>
                            <small class="opacity-50">
                                <i class="fas fa-clock me-1"></i>
                                {{ round($stats['pending_orders'] / max($stats['total_orders'], 1) * 100) }}% du total
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-2 p-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: {{ round($stats['pending_orders'] / max($stats['total_orders'], 1) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $stats['paid_orders'] }}</h3>
                            <p class="mb-0 opacity-75">Payées</p>
                            <small class="opacity-50">
                                <i class="fas fa-check-circle me-1"></i>
                                {{ round($stats['paid_orders'] / max($stats['total_orders'], 1) * 100) }}% du total
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-2 p-3">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: {{ round($stats['paid_orders'] / max($stats['total_orders'], 1) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);">
                <div class="card-body text-white">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $stats['cancelled_orders'] }}</h3>
                            <p class="mb-0 opacity-75">Annulées</p>
                            <small class="opacity-50">
                                <i class="fas fa-times-circle me-1"></i>
                                {{ round($stats['cancelled_orders'] / max($stats['total_orders'], 1) * 100) }}% du total
                            </small>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-2 p-3">
                            <i class="fas fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar bg-white" style="width: {{ round($stats['cancelled_orders'] / max($stats['total_orders'], 1) * 100) }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Advanced Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-filter me-2 text-primary"></i>
                    Filtres avancés
                </h5>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="toggleFilters()">
                    <i class="fas fa-chevron-down" id="filterToggle"></i>
                </button>
            </div>
        </div>
        <div class="card-body" id="filtersContainer">
            <form method="GET" action="{{ route('admin.orders') }}" id="filterForm">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-search me-1 text-muted"></i>Recherche rapide
                        </label>
                        <div class="input-group">
                            <input type="text" 
                                   name="search" 
                                   class="form-control" 
                                   placeholder="Nom, email, N° commande..." 
                                   value="{{ request('search') }}"
                                   onkeyup="liveSearch(this.value)">
                            <button class="btn btn-outline-secondary" type="button" onclick="clearSearch()">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-flag me-1 text-muted"></i>Statut
                        </label>
                        <select name="status" class="form-select" onchange="submitFilters()">
                            <option value="">Tous les statuts</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                <i class="fas fa-clock"></i> En Attente
                            </option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                                <i class="fas fa-check"></i> Payée
                            </option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                <i class="fas fa-flag"></i> Complétée
                            </option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                                <i class="fas fa-times"></i> Annulée
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-credit-card me-1 text-muted"></i>Paiement
                        </label>
                        <select name="payment_method" class="form-select" onchange="submitFilters()">
                            <option value="">Toutes les méthodes</option>
                            <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                <i class="fas fa-university"></i> Virement
                            </option>
                            <option value="sumup" {{ request('payment_method') == 'sumup' ? 'selected' : '' }}>
                                <i class="fas fa-credit-card"></i> SumUp
                            </option>
                            <option value="paypal" {{ request('payment_method') == 'paypal' ? 'selected' : '' }}>
                                <i class="fab fa-paypal"></i> PayPal
                            </option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar me-1 text-muted"></i>Période
                        </label>
                        <select name="period" class="form-select" onchange="submitFilters()">
                            <option value="">Toutes les périodes</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Aujourd'hui</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Cette semaine</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Ce mois</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Cette année</option>
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-sort-amount-down me-1 text-muted"></i>Tri
                        </label>
                        <select name="sort" class="form-select" onchange="submitFilters()">
                            <option value="created_at|desc" {{ request('sort') == 'created_at|desc' ? 'selected' : '' }}>
                                Plus récentes
                            </option>
                            <option value="created_at|asc" {{ request('sort') == 'created_at|asc' ? 'selected' : '' }}>
                                Plus anciennes
                            </option>
                            <option value="amount|desc" {{ request('sort') == 'amount|desc' ? 'selected' : '' }}>
                                Montant décroissant
                            </option>
                            <option value="amount|asc" {{ request('sort') == 'amount|asc' ? 'selected' : '' }}>
                                Montant croissant
                            </option>
                        </select>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search me-2"></i>Appliquer les filtres
                            </button>
                            <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-redo me-2"></i>Réinitialiser
                            </a>
                            <button type="button" class="btn btn-outline-info" onclick="saveFilters()">
                                <i class="fas fa-bookmark me-2"></i>Enregistrer cette vue
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table with Enhanced Features -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2 text-primary"></i>
                        Liste des commandes
                    </h5>
                    <small class="text-muted">
                        {{ $orders->firstItem() }}-{{ $orders->lastItem() }} sur {{ $orders->total() }} résultats
                    </small>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group" style="width: 250px;">
                        <input type="text" class="form-control form-control-sm" placeholder="Recherche rapide..." id="quickSearch">
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-secondary active" onclick="setView('table')">
                            <i class="fas fa-list"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="setView('grid')">
                            <i class="fas fa-th"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <!-- Bulk Actions Bar -->
            <div class="bg-light border-bottom p-3 d-none" id="bulkActionsBar">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted me-3">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                            <label for="selectAll" class="ms-2">Tout sélectionner</label>
                        </span>
                        <span id="selectedCount" class="badge bg-primary">0 sélectionné(s)</span>
                    </div>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary" onclick="bulkUpdateStatus('paid')">
                            <i class="fas fa-check me-1"></i>Marquer payées
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="bulkCreateCustomers()">
                            <i class="fas fa-user-plus me-1"></i>Créer clients
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="bulkDelete()">
                            <i class="fas fa-trash me-1"></i>Supprimer
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="ordersTable">
                    <thead class="bg-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="masterCheckbox" onchange="toggleMasterSelect()">
                            </th>
                            <th width="120">N° Commande</th>
                            <th>Client</th>
                            <th>Contact</th>
                            <th width="120">Montant</th>
                            <th width="120">Méthode</th>
                            <th width="120">Statut</th>
                            <th width="150">Date</th>
                            <th width="120" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr data-order-id="{{ $order->id }}" class="order-row" ondblclick="showOrderDetails({{ $order->id }})">
                                <td>
                                    <input type="checkbox" class="order-checkbox" value="{{ $order->id }}" onchange="updateSelectedCount()">
                                </td>
                                <td>
                                    <span class="badge bg-secondary fw-bold" style="font-family: monospace;">
                                        {{ $order->order_id }}
                                    </span>
                                    @if($order->created_at->diffInDays(now()) < 1)
                                        <span class="badge bg-success ms-1">Nouveau</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <div class="avatar-initial rounded-circle bg-primary text-white">
                                                {{ strtoupper(substr($order->firstname, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <div class="fw-semibold">{{ $order->firstname }} {{ $order->lastname }}</div>
                                            <small class="text-muted">ID: #{{ $order->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="small">
                                        <div><i class="fas fa-envelope text-muted me-1"></i>{{ $order->email }}</div>
                                        <div><i class="fas fa-phone text-muted me-1"></i>{{ $order->phone }}</div>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $order->formatted_amount }}</div>
                                    <small class="text-muted">HT</small>
                                </td>
                                <td>
                                    @switch($order->payment_method)
                                        @case('bank_transfer')
                                            <span class="badge bg-info">
                                                <i class="fas fa-university me-1"></i>Virement
                                            </span>
                                            @break
                                        @case('sumup')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-credit-card me-1"></i>SumUp
                                            </span>
                                            @break
                                        @case('paypal')
                                            <span class="badge bg-warning text-dark">
                                                <i class="fab fa-paypal me-1"></i>PayPal
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    @switch($order->status)
                                        @case('pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>En Attente
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check-circle me-1"></i>Payée
                                            </span>
                                            @break
                                        @case('completed')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-flag-checkered me-1"></i>Complétée
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times-circle me-1"></i>Annulée
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <div class="small">
                                        <div>{{ $order->created_at->format('d/m/Y') }}</div>
                                        <div class="text-muted">{{ $order->created_at->format('H:i') }}</div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm">
                                        @if($order->status === 'paid')
                                            <button type="button" 
                                                    class="btn btn-success"
                                                    onclick="createCustomerFromOrder({{ $order->id }})"
                                                    title="Créer le client">
                                                <i class="fas fa-user-plus"></i>
                                            </button>
                                        @endif
                                        
                                        <button type="button" 
                                                class="btn btn-info"
                                                onclick="showOrderDetails({{ $order->id }})"
                                                title="Voir les détails">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        
                                        <div class="btn-group btn-group-sm">
                                            <button type="button" 
                                                    class="btn btn-outline-secondary dropdown-toggle" 
                                                    data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'pending')">
                                                        <i class="fas fa-clock text-warning me-2"></i>En Attente
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'paid')">
                                                        <i class="fas fa-check text-success me-2"></i>Payée
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="updateOrderStatus({{ $order->id }}, 'completed')">
                                                        <i class="fas fa-flag text-primary me-2"></i>Complétée
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="sendInvoice({{ $order->id }})">
                                                        <i class="fas fa-envelope text-info me-2"></i>Envoyer facture
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="downloadInvoice({{ $order->id }})">
                                                        <i class="fas fa-download text-primary me-2"></i>Télécharger facture
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="deleteOrder({{ $order->id }})">
                                                        <i class="fas fa-trash me-2"></i>Supprimer
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="empty-state">
                                        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                                        <h5 class="text-muted">Aucune commande trouvée</h5>
                                        <p class="text-muted">Essayez de modifier vos filtres de recherche</p>
                                        <button type="button" class="btn btn-primary" onclick="clearAllFilters()">
                                            <i class="fas fa-redo me-2"></i>Réinitialiser les filtres
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Enhanced Pagination -->
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        <small>
                            Affichage de {{ $orders->firstItem() }} à {{ $orders->lastItem() }} 
                            sur {{ $orders->total() }} commandes
                        </small>
                    </div>
                    <div>
                        {{ $orders->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Order Details Modal -->
<div class="modal fade" id="orderDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-file-invoice me-2"></i>
                    Détails de la commande
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="orderDetailsContent">
                <!-- Content loaded via AJAX -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary" onclick="editOrder()">
                    <i class="fas fa-edit me-2"></i>Modifier
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div class="modal fade" id="statusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <p class="mb-0">Traitement en cours...</p>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions Modal -->
<div class="modal fade" id="bulkActionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-tasks me-2"></i>
                    Actions groupées
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted mb-3">Sélectionnez l'action à appliquer aux commandes sélectionnées :</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-outline-primary" onclick="bulkUpdateStatus('paid')">
                        <i class="fas fa-check me-2"></i>Marquer comme payées
                    </button>
                    <button type="button" class="btn btn-outline-success" onclick="bulkCreateCustomers()">
                        <i class="fas fa-user-plus me-2"></i>Créer les clients
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="bulkSendInvoices()">
                        <i class="fas fa-envelope me-2"></i>Envoyer les factures
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="bulkExport()">
                        <i class="fas fa-download me-2"></i>Exporter les données
                    </button>
                    <hr>
                    <button type="button" class="btn btn-outline-danger" onclick="bulkDelete()">
                        <i class="fas fa-trash me-2"></i>Supprimer les commandes
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stats-card {
    transition: all 0.3s ease;
    border: none;
    overflow: hidden;
}

.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
}

.stats-card .card-body {
    position: relative;
    z-index: 1;
}

.order-row {
    transition: all 0.2s ease;
    cursor: pointer;
}

.order-row:hover {
    background-color: rgba(56, 116, 255, 0.05);
}

.order-row.selected {
    background-color: rgba(56, 116, 255, 0.1);
}

.avatar-initial {
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.empty-state {
    padding: 3rem 1rem;
}

.btn-group .btn {
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
}

.dropdown-menu {
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    border-radius: 0.5rem;
}

.dropdown-item {
    transition: all 0.2s ease;
    padding: 0.5rem 1rem;
}

.dropdown-item:hover {
    background-color: rgba(56, 116, 255, 0.1);
    transform: translateX(5px);
}

.badge {
    font-weight: 500;
    padding: 0.375rem 0.75rem;
}

.table th {
    border-top: none;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.75rem;
    letter-spacing: 0.5px;
    color: var(--phoenix-text-muted);
}

.progress {
    background-color: rgba(255, 255, 255, 0.3);
}

.modal-content {
    border: none;
    border-radius: 1rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

.form-control:focus {
    border-color: var(--phoenix-primary);
    box-shadow: 0 0 0 0.2rem rgba(56, 116, 255, 0.25);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(100px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideOutRight {
    from {
        opacity: 1;
        transform: translateX(0);
    }
    to {
        opacity: 0;
        transform: translateX(100px);
    }
}

.removing {
    animation: slideOutRight 0.3s ease-out forwards;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    animation: slideInRight 0.3s ease-out;
    border-radius: 0.5rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    max-width: 350px;
}

.notification.success {
    background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
    color: #0f5132;
}

.notification.error {
    background: linear-gradient(135deg, #fccb90 0%, #ff9a9e 100%);
    color: #842029;
}

.notification.warning {
    background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
    color: #664d03;
}

.notification.info {
    background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
    color: #055160;
}
</style>
@endpush

@push('scripts')
<script>
// Global variables
let selectedOrders = new Set();
let currentOrderId = null;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    initializeKeyboardShortcuts();
    initializeQuickSearch();
    updateOrderCount();
});

// Keyboard shortcuts
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + K for quick search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('quickSearch').focus();
        }
        
        // Escape to clear search
        if (e.key === 'Escape') {
            clearSearch();
        }
        
        // Ctrl/Cmd + A to select all
        if ((e.ctrlKey || e.metaKey) && e.key === 'a' && !e.target.matches('input, textarea')) {
            e.preventDefault();
            toggleSelectAll();
        }
    });
}

// Quick search functionality
function initializeQuickSearch() {
    const searchInput = document.getElementById('quickSearch');
    let searchTimeout;
    
    searchInput.addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            liveSearch(e.target.value);
        }, 300);
    });
}

// Live search
async function liveSearch(query) {
    if (query.length < 2) {
        restoreAllRows();
        return;
    }
    
    const rows = document.querySelectorAll('.order-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(query.toLowerCase())) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    updateOrderCount(visibleCount);
}

// Restore all rows
function restoreAllRows() {
    document.querySelectorAll('.order-row').forEach(row => {
        row.style.display = '';
    });
    updateOrderCount();
}

// Update order count
function updateOrderCount(count = null) {
    const countElement = document.getElementById('orderCount');
    if (count !== null) {
        countElement.textContent = count + ' commandes';
    } else {
        countElement.textContent = document.querySelectorAll('.order-row:not([style*="display: none"])').length + ' commandes';
    }
}

// Toggle filters
function toggleFilters() {
    const container = document.getElementById('filtersContainer');
    const toggle = document.getElementById('filterToggle');
    
    if (container.style.display === 'none') {
        container.style.display = 'block';
        toggle.classList.remove('fa-chevron-right');
        toggle.classList.add('fa-chevron-down');
    } else {
        container.style.display = 'none';
        toggle.classList.remove('fa-chevron-down');
        toggle.classList.add('fa-chevron-right');
    }
}

// Submit filters
function submitFilters() {
    document.getElementById('filterForm').submit();
}

// Clear search
function clearSearch() {
    document.querySelector('input[name="search"]').value = '';
    document.getElementById('quickSearch').value = '';
    submitFilters();
}

// Clear all filters
function clearAllFilters() {
    window.location.href = '{{ route('admin.orders') }}';
}

// Save filters
function saveFilters() {
    const filters = {
        search: document.querySelector('input[name="search"]').value,
        status: document.querySelector('select[name="status"]').value,
        payment_method: document.querySelector('select[name="payment_method"]').value,
        period: document.querySelector('select[name="period"]').value,
        sort: document.querySelector('select[name="sort"]').value
    };
    
    localStorage.setItem('orderFilters', JSON.stringify(filters));
    showNotification('Filtres enregistrés avec succès', 'success');
}

// Export orders
function exportOrders() {
    const format = confirm('Exporter en format Excel ?\n(Cliquez sur Annuler pour CSV)');
    const url = format ? 
        '{{ route('admin.orders.export', ['format' => 'excel']) }}' : 
        '{{ route('admin.orders.export', ['format' => 'csv']) }}';
    
    window.open(url, '_blank');
    showNotification('Export en cours...', 'info');
}

// Show bulk actions
function showBulkActions() {
    if (selectedOrders.size === 0) {
        showNotification('Veuillez sélectionner au moins une commande', 'warning');
        return;
    }
    
    const modal = new bootstrap.Modal(document.getElementById('bulkActionsModal'));
    modal.show();
}

// Toggle select all
function toggleSelectAll() {
    const checkboxes = document.querySelectorAll('.order-checkbox');
    const selectAll = document.getElementById('selectAll');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
        if (selectAll.checked) {
            selectedOrders.add(parseInt(checkbox.value));
        } else {
            selectedOrders.delete(parseInt(checkbox.value));
        }
    });
    
    updateSelectedCount();
    updateBulkActionsBar();
}

// Toggle master select
function toggleMasterSelect() {
    const masterCheckbox = document.getElementById('masterCheckbox');
    const checkboxes = document.querySelectorAll('.order-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = masterCheckbox.checked;
        if (masterCheckbox.checked) {
            selectedOrders.add(parseInt(checkbox.value));
        } else {
            selectedOrders.delete(parseInt(checkbox.value));
        }
    });
    
    updateSelectedCount();
    updateBulkActionsBar();
}

// Update selected count
function updateSelectedCount() {
    const count = document.querySelectorAll('.order-checkbox:checked').length;
    document.getElementById('selectedCount').textContent = count + ' sélectionné(s)';
    
    if (count > 0) {
        document.getElementById('bulkActionsBar').classList.remove('d-none');
    } else {
        document.getElementById('bulkActionsBar').classList.add('d-none');
    }
}

// Update bulk actions bar
function updateBulkActionsBar() {
    const bar = document.getElementById('bulkActionsBar');
    if (selectedOrders.size > 0) {
        bar.classList.remove('d-none');
    } else {
        bar.classList.add('d-none');
    }
}

// API Call helper
async function apiCall(url, options = {}) {
    try {
        const response = await fetch(url, {
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                ...options.headers
            },
            ...options
        });
        
        const data = await response.json();
        
        if (!response.ok) {
            throw new Error(data.message || 'Erreur serveur');
        }
        
        return data;
    } catch (error) {
        showNotification(error.message, 'error');
        throw error;
    }
}

// Show order details
async function showOrderDetails(orderId) {
    currentOrderId = orderId;
    const modal = new bootstrap.Modal(document.getElementById('orderDetailsModal'));
    const content = document.getElementById('orderDetailsContent');
    
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Chargement...</span>
            </div>
        </div>
    `;
    
    modal.show();
    
    try {
        const url = `{{ route('admin.orders.show', ['order' => ':id']) }}`.replace(':id', orderId);
        const data = await apiCall(url);
        
        content.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Informations commande</h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">N° Commande:</td>
                            <td class="fw-bold">${data.order.order_id}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Date:</td>
                            <td>${data.order.created_at}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Montant:</td>
                            <td class="fw-bold text-primary">${data.order.amount} €</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Statut:</td>
                            <td>${data.order.status_badge}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <h6 class="text-muted mb-3">Informations client</h6>
                    <table class="table table-sm">
                        <tr>
                            <td class="text-muted">Nom:</td>
                            <td>${data.order.firstname} ${data.order.lastname}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Email:</td>
                            <td>${data.order.email}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Téléphone:</td>
                            <td>${data.order.phone}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Paiement:</td>
                            <td>${data.order.payment_method_badge}</td>
                        </tr>
                    </table>
                </div>
            </div>
            ${data.order.items ? `
                <h6 class="text-muted mb-3 mt-4">Articles commandés</h6>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Article</th>
                                <th>Quantité</th>
                                <th>Prix unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${data.order.items.map(item => `
                                <tr>
                                    <td>${item.name}</td>
                                    <td>${item.quantity}</td>
                                    <td>${item.price} €</td>
                                    <td class="fw-bold">${item.total} €</td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            ` : ''}
        `;
        
    } catch (error) {
        content.innerHTML = `
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur lors du chargement des détails
            </div>
        `;
    }
}

// Create Customer from Order
async function createCustomerFromOrder(orderId) {
    if (!confirm('Créer un client à partir de cette commande ?')) {
        return;
    }

    const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
    statusModal.show();

    try {
        const url = `{{ route('admin.orders.create-customer', ['order' => ':id']) }}`.replace(':id', orderId);
        const data = await apiCall(url, { method: 'POST' });
        
        statusModal.hide();
        showNotification(data.message, 'success');
        
        // Remove the row with animation
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        row.classList.add('removing');
        setTimeout(() => row.remove(), 400);
        
        // Redirect to edit customer after a delay
        setTimeout(() => {
            window.location.href = data.redirect_url;
        }, 1500);
        
    } catch (error) {
        statusModal.hide();
    }
}

// Update Order Status
async function updateOrderStatus(orderId, status) {
    const statusText = {
        'pending': 'En Attente',
        'paid': 'Payée',
        'completed': 'Complétée',
        'cancelled': 'Annulée'
    };

    if (!confirm(`Changer le statut de la commande en "${statusText[status]}" ?`)) {
        return;
    }

    try {
        const url = `{{ route('admin.orders.update-status', ['order' => ':id']) }}`.replace(':id', orderId);
        const data = await apiCall(url, {
            method: 'PUT',
            body: JSON.stringify({ status: status })
        });
        
        showNotification(data.message, 'success');
        
        // Reload the page to show updated status
        setTimeout(() => location.reload(), 1000);
        
    } catch (error) {
        // Error already handled by apiCall
    }
}

// Delete Order
async function deleteOrder(orderId) {
    if (!confirm('Voulez-vous vraiment supprimer cette commande ? Cette action est irréversible.')) {
        return;
    }

    try {
        const url = `{{ route('admin.orders.delete', ['order' => ':id']) }}`.replace(':id', orderId);
        const data = await apiCall(url, { method: 'DELETE' });
        
        showNotification(data.message, 'success');
        
        // Remove the row with animation
        const row = document.querySelector(`tr[data-order-id="${orderId}"]`);
        row.classList.add('removing');
        setTimeout(() => row.remove(), 400);
        
        // Update count
        updateOrderCount();
        
    } catch (error) {
        // Error already handled by apiCall
    }
}

// Send invoice
async function sendInvoice(orderId) {
    try {
        const url = `{{ route('admin.orders.send-invoice', ['order' => ':id']) }}`.replace(':id', orderId);
        const data = await apiCall(url, { method: 'POST' });
        
        showNotification(data.message, 'success');
    } catch (error) {
        // Error already handled by apiCall
    }
}

// Download invoice
function downloadInvoice(orderId) {
    const url = `{{ route('admin.orders.download-invoice', ['order' => ':id']) }}`.replace(':id', orderId);
    window.open(url, '_blank');
    showNotification('Téléchargement de la facture en cours...', 'info');
}

// Bulk operations
async function bulkUpdateStatus(status) {
    if (selectedOrders.size === 0) {
        showNotification('Aucune commande sélectionnée', 'warning');
        return;
    }
    
    if (!confirm(`Mettre à jour le statut de ${selectedOrders.size} commande(s) ?`)) {
        return;
    }
    
    try {
        const url = '{{ route('admin.orders.bulk-update-status') }}';
        const data = await apiCall(url, {
            method: 'POST',
            body: JSON.stringify({
                order_ids: Array.from(selectedOrders),
                status: status
            })
        });
        
        showNotification(data.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } catch (error) {
        // Error already handled
    }
}

async function bulkCreateCustomers() {
    if (selectedOrders.size === 0) {
        showNotification('Aucune commande sélectionnée', 'warning');
        return;
    }
    
    if (!confirm(`Créer des clients à partir de ${selectedOrders.size} commande(s) ?`)) {
        return;
    }
    
    try {
        const url = '{{ route('admin.orders.bulk-create-customers') }}';
        const data = await apiCall(url, {
            method: 'POST',
            body: JSON.stringify({
                order_ids: Array.from(selectedOrders)
            })
        });
        
        showNotification(data.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } catch (error) {
        // Error already handled
    }
}

async function bulkDelete() {
    if (selectedOrders.size === 0) {
        showNotification('Aucune commande sélectionnée', 'warning');
        return;
    }
    
    if (!confirm(`Supprimer définitivement ${selectedOrders.size} commande(s) ? Cette action est irréversible.`)) {
        return;
    }
    
    try {
        const url = '{{ route('admin.orders.bulk-delete') }}';
        const data = await apiCall(url, {
            method: 'DELETE',
            body: JSON.stringify({
                order_ids: Array.from(selectedOrders)
            })
        });
        
        showNotification(data.message, 'success');
        setTimeout(() => location.reload(), 1500);
    } catch (error) {
        // Error already handled
    }
}

// Show notification
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="d-flex align-items-center p-3">
            <i class="fas ${getNotificationIcon(type)} me-3" style="font-size: 1.25rem;"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close btn-close-white ms-3" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease-out forwards';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Get notification icon
function getNotificationIcon(type) {
    const icons = {
        success: 'fa-check-circle',
        error: 'fa-times-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    return icons[type] || 'fa-info-circle';
}

// Set view (table/grid)
function setView(view) {
    // Implementation for grid view
    showNotification(`Vue ${view} bientôt disponible`, 'info');
}

// Edit order
function editOrder() {
    if (currentOrderId) {
        window.location.href = `{{ route('admin.orders.edit', ['order' => ':id']) }}`.replace(':id', currentOrderId);
    }
}
</script>
@endpush