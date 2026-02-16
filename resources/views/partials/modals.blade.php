<!-- Email Status Modal -->
<div class="modal fade" id="emailStatusModal" tabindex="-1" aria-labelledby="emailStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="emailStatusModalLabel">Statut de l'envoi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body text-center py-4">
                <!-- Loading State -->
                <div id="emailLoading" style="display: none;">
                    <div class="spinner mb-3" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                    <p>Envoi de l'email en cours...</p>
                </div>
                
                <!-- Success State -->
                <div id="emailSuccess" style="display: none;">
                    <i class="fas fa-check-circle text-success fa-3x mb-3"></i>
                    <p class="mb-0">Email envoyé avec succès !</p>
                </div>
                
                <!-- Error State -->
                <div id="emailError" style="display: none;">
                    <i class="fas fa-exclamation-circle text-danger fa-3x mb-3"></i>
                    <p class="mb-0">Erreur lors de l'envoi</p>
                    <p class="text-muted small" id="errorMessage"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Customer Detail Modal -->
<div class="modal fade" id="customerDetailModal" tabindex="-1" aria-labelledby="customerDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerDetailModalLabel">Détails du client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <div class="customer-detail-content">
                    <!-- Content will be dynamically loaded -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-primary">Sauvegarder</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmationModalLabel">Confirmation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <p id="confirmationMessage"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirmer</button>
            </div>
        </div>
    </div>
</div>