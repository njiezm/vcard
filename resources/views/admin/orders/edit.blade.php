@extends('layouts.admin')

@section('title', 'Éditer la Commande')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Éditer la Commande #{{ $order->order_id }}</h1>
        <a href="{{ route('admin.orders') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Retour à la liste
        </a>
    </div>

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
                            @csrf
                            @method('PUT') <!-- Important pour la mise à jour -->

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="firstname" class="form-label">Prénom</label>
                                    <input type="text" class="form-control" id="firstname" name="firstname" value="{{ $order->firstname }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="lastname" class="form-label">Nom</label>
                                    <input type="text" class="form-control" id="lastname" name="lastname" value="{{ $order->lastname }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $order->email }}" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control" id="phone" name="phone" value="{{ $order->phone ?? '' }}">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="amount" class="form-label">Montant</label>
                                    <input type="text" class="form-control" id="amount" name="amount" value="{{ $order->amount }}" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="currency" class="form-label">Devise</label>
                                    <input type="text" class="form-control" id="currency" name="currency" value="{{ $order->currency }}" maxlength="3" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="payment_method" class="form-label">Méthode de Paiement</label>
                                    <select class="form-select" id="payment_method" name="payment_method" required>
                                        <option value="paypal" {{ $order->payment_method === 'paypal' ? 'selected' : '' }}>PayPal</option>
                                        <option value="sumup" {{ $order->payment_method === 'sumup' ? 'selected' : '' }}>SumUp</option>
                                        <option value="bank_transfer" {{ $order->payment_method === 'bank_transfer' ? 'selected' : '' }}>Virement Bancaire</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="status" class="form-label">Statut de la Commande</label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En Attente</option>
                                        <option value="paid" {{ $order->status === 'paid' ? 'selected' : '' }}>Payée</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Complétée</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                    </select>
                                </div>
                            </div>

                            <hr>
                            <button type="submit" class="btn btn-success btn-icon-split">
                                <span class="icon text-white-50">
                                    <i class="fas fa-save"></i>
                                </span>
                                <span class="text">Enregistrer les modifications</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection