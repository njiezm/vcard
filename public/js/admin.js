/**
 * Phoenix Admin Dashboard Script
 * Handles all interactive features of the admin panel.
 */

'use strict';

// =================================================================
// 1. State and Configuration
// =================================================================
const PhoenixAdmin = {
    // State variables
    state: {
        allCustomers: [],
        filteredCustomers: [],
        currentSort: { column: 'name', direction: 'asc' },
        activeFilter: 'all',
        searchQuery: '',
        sidebarCollapsed: false,
        theme: 'light'
    },
    
    // DOM Elements cache
    elements: {},
    
    // Configuration
    config: {
        debounceDelay: 300,
        animationDuration: 300,
        toastDuration: 3000,
        apiEndpoints: {
            customers: '/api/customers',
            toggle: '/api/customers/{id}/toggle',
            delete: '/api/customers/{id}',
            sendEmail: '/api/customers/{id}/send-email'
        }
    },
    
    // Initialize the application
    init() {
        this.cacheElements();
        this.bindEvents();
        this.initializeTheme();
        this.initializeSidebar();
        this.initializeSearch();
        this.initializeFilters();
        this.initializeSorting();
        this.initializeTooltips();
        this.loadCustomerData();
        console.log('Phoenix Admin initialized');
    },
    
    // Cache DOM elements
    cacheElements() {
        this.elements = {
            body: document.body,
            sidebar: document.querySelector('.sidebar'),
            sidebarToggle: document.querySelectorAll('.sidebar-toggle'),
            themeToggle: document.querySelector('.theme-toggle'),
            themeIcon: document.getElementById('themeIcon'),
            searchInput: document.querySelector('.search-input'),
            filterChips: document.querySelectorAll('.filter-chip'),
            table: document.querySelector('.customers-table'),
            tableBody: document.querySelector('.customers-table tbody'),
            mobileCardsContainer: document.getElementById('mobileCardsContainer'),
            toastContainer: document.getElementById('toastContainer'),
            dropdowns: document.querySelectorAll('.dropdown')
        };
    },
    
    // Bind event listeners
    bindEvents() {
        // Sidebar toggle
        this.elements.sidebarToggle.forEach(btn => {
            btn.addEventListener('click', () => this.toggleSidebar());
        });
        
        // Theme toggle
        this.elements.themeToggle?.addEventListener('click', () => this.toggleTheme());
        
        // Search input
        this.elements.searchInput?.addEventListener('input', (e) => {
            this.debounceSearch(e.target.value);
        });
        
        // Filter chips
        this.elements.filterChips.forEach(chip => {
            chip.addEventListener('click', () => this.setActiveFilter(chip));
        });
        
        // Sortable headers
        document.querySelectorAll('.sortable').forEach(header => {
            header.addEventListener('click', () => this.handleSort(header));
        });
        
        // Copy buttons
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleCopy(e, btn));
        });
        
        // Window resize
        window.addEventListener('resize', this.debounce(() => {
            this.handleResize();
        }, 250));
        
        // Keyboard navigation
        document.addEventListener('keydown', (e) => this.handleKeyboard(e));
        
        // Close dropdowns when clicking outside
        document.addEventListener('click', (e) => this.closeDropdowns(e));
    },
    
    // =================================================================
    // 2. Theme Management
    // =================================================================
    initializeTheme() {
        const savedTheme = localStorage.getItem('theme') || this.getCookie('theme');
        if (savedTheme === 'dark') {
            this.state.theme = 'dark';
            this.elements.body.classList.add('dark-theme');
            this.updateThemeIcon(true);
        }
    },
    
    toggleTheme() {
        const isDark = this.elements.body.classList.toggle('dark-theme');
        this.state.theme = isDark ? 'dark' : 'light';
        localStorage.setItem('theme', this.state.theme);
        this.setCookie('theme', this.state.theme, 365);
        this.updateThemeIcon(isDark);
        this.showToast(`Thème ${isDark ? 'sombre' : 'clair'} activé`, 'info');
    },
    
    updateThemeIcon(isDark) {
        if (this.elements.themeIcon) {
            this.elements.themeIcon.className = isDark ? 'fas fa-sun' : 'fas fa-moon';
        }
    },
    
    // =================================================================
    // 3. Sidebar Management
    // =================================================================
    initializeSidebar() {
        // Check for saved state
        const savedState = localStorage.getItem('sidebarCollapsed');
        if (savedState === 'true') {
            this.collapseSidebar(true);
        }
        
        // Auto-collapse on mobile
        if (window.innerWidth <= 992) {
            this.collapseSidebar(true);
        }
    },
    
    toggleSidebar() {
        const isCollapsed = this.elements.sidebar.classList.toggle('collapsed');
        this.state.sidebarCollapsed = isCollapsed;
        localStorage.setItem('sidebarCollapsed', isCollapsed);
        
        // Update ARIA attributes
        this.elements.sidebar.setAttribute('aria-expanded', !isCollapsed);
        
        // Emit custom event
        this.emitEvent('sidebarToggled', { collapsed: isCollapsed });
    },
    
    collapseSidebar(force = false) {
        if (force || window.innerWidth <= 992) {
            this.elements.sidebar.classList.add('collapsed');
            this.state.sidebarCollapsed = true;
            this.elements.sidebar.setAttribute('aria-expanded', 'false');
        }
    },
    
    // =================================================================
    // 4. Search and Filter
    // =================================================================
    initializeSearch() {
        // Add search suggestions if needed
        this.setupSearchSuggestions();
    },
    
    debounceSearch: this.debounce(function(query) {
        this.state.searchQuery = query.toLowerCase().trim();
        this.applyFiltersAndRender();
    }, 300),
    
    setupSearchSuggestions() {
        // Implementation for search suggestions
        console.log('Search suggestions setup');
    },
    
    initializeFilters() {
        // Set initial active filter
        const activeChip = document.querySelector('.filter-chip.active');
        if (activeChip) {
            this.state.activeFilter = activeChip.dataset.filter || 'all';
        }
    },
    
    setActiveFilter(chip) {
        // Remove active class from all chips
        this.elements.filterChips.forEach(c => c.classList.remove('active'));
        
        // Add active class to clicked chip
        chip.classList.add('active');
        
        // Update state
        this.state.activeFilter = chip.dataset.filter || 'all';
        
        // Apply filters
        this.applyFiltersAndRender();
        
        // Emit event
        this.emitEvent('filterChanged', { filter: this.state.activeFilter });
    },
    
    applyFiltersAndRender() {
        // Filter customers
        this.state.filteredCustomers = this.state.allCustomers.filter(customer => {
            const matchesSearch = !this.state.searchQuery || 
                customer.name.toLowerCase().includes(this.state.searchQuery) ||
                customer.email.toLowerCase().includes(this.state.searchQuery) ||
                customer.slug.toLowerCase().includes(this.state.searchQuery);

            const matchesFilter = 
                this.state.activeFilter === 'all' ||
                (this.state.activeFilter === 'active' && customer.is_active) ||
                (this.state.activeFilter === 'inactive' && !customer.is_active);

            return matchesSearch && matchesFilter;
        });

        // Sort and render
        this.sortAndRender();
    },
    
    // =================================================================
    // 5. Sorting
    // =================================================================
    initializeSorting() {
        // Set initial sort
        const activeHeader = document.querySelector('.sortable.active');
        if (activeHeader) {
            this.state.currentSort.column = activeHeader.dataset.sort || 'name';
            this.state.currentSort.direction = activeHeader.dataset.direction || 'asc';
        }
    },
    
    handleSort(header) {
        const column = header.dataset.sort;
        
        // Update sort direction
        if (this.state.currentSort.column === column) {
            this.state.currentSort.direction = this.state.currentSort.direction === 'asc' ? 'desc' : 'asc';
        } else {
            this.state.currentSort.column = column;
            this.state.currentSort.direction = 'asc';
        }
        
        // Update UI
        this.updateSortIcons();
        
        // Sort and render
        this.sortAndRender();
        
        // Emit event
        this.emitEvent('sortChanged', this.state.currentSort);
    },
    
    updateSortIcons() {
        document.querySelectorAll('.sortable i').forEach(icon => {
            icon.className = 'fas fa-sort';
        });
        
        const activeHeader = document.querySelector(`.sortable[data-sort="${this.state.currentSort.column}"] i`);
        if (activeHeader) {
            activeHeader.className = `fas fa-sort-${this.state.currentSort.direction === 'asc' ? 'up' : 'down'}`;
        }
    },
    
    sortAndRender() {
        // Sort filtered customers
        this.state.filteredCustomers.sort((a, b) => {
            let valA = a[this.state.currentSort.column];
            let valB = b[this.state.currentSort.column];

            if (typeof valA === 'string') {
                valA = valA.toLowerCase();
                valB = valB.toLowerCase();
            }

            if (valA < valB) return this.state.currentSort.direction === 'asc' ? -1 : 1;
            if (valA > valB) return this.state.currentSort.direction === 'asc' ? 1 : -1;
            return 0;
        });
        
        // Render
        this.renderTable();
        this.renderMobileCards();
    },
    
    // =================================================================
    // 6. Data Management
    // =================================================================
    loadCustomerData() {
        // Extract data from table
        const rows = this.elements.tableBody?.querySelectorAll('tr') || [];
        this.state.allCustomers = Array.from(rows).map(row => ({
            id: row.dataset.customerId,
            name: row.querySelector('td[data-name]')?.dataset.name || '',
            email: row.querySelector('td[data-email]')?.dataset.email || '',
            slug: row.querySelector('code')?.textContent || '',
            admin_code: row.querySelectorAll('code')[1]?.textContent || '',
            is_active: row.dataset.isActive === 'true'
        }));
        
        this.state.filteredCustomers = [...this.state.allCustomers];
    },
    
    renderTable() {
        if (!this.elements.tableBody) return;
        
        // Create a map for quick lookup
        const rowMap = new Map();
        this.elements.tableBody.querySelectorAll('tr').forEach(row => {
            rowMap.set(row.dataset.customerId, row);
        });
        
        // Sort the rows based on the filteredCustomers order
        const sortedRows = this.state.filteredCustomers
            .map(customer => rowMap.get(customer.id.toString()))
            .filter(row => row); // Filter out any nulls
        
        // Clear and re-append
        this.elements.tableBody.innerHTML = '';
        sortedRows.forEach(row => {
            row.classList.add('animate-fadeIn');
            this.elements.tableBody.appendChild(row);
        });
    },
    
    renderMobileCards() {
        if (!this.elements.mobileCardsContainer || window.innerWidth > 768) return;
        
        this.elements.mobileCardsContainer.innerHTML = this.state.filteredCustomers.map(customer => `
            <div class="customer-card animate-slideInUp">
                <div class="customer-card-header">
                    <strong>${this.escapeHtml(customer.name)}</strong>
                    <span class="badge ${customer.is_active ? 'bg-success' : 'bg-secondary'}">
                        ${customer.is_active ? 'Actif' : 'Inactif'}
                    </span>
                </div>
                <div class="customer-card-body">
                    <p><strong>Email:</strong> ${this.escapeHtml(customer.email || '-')}</p>
                    <p><strong>Slug:</strong> <code>${this.escapeHtml(customer.slug)}</code></p>
                    <p><strong>Code Admin:</strong> <code>${this.escapeHtml(customer.admin_code)}</code></p>
                </div>
                <div class="customer-card-footer">
                    <a href="/vcard/${this.escapeHtml(customer.slug)}" target="_blank" class="btn btn-sm btn-phoenix btn-phoenix-info">Voir</a>
                    <a href="/admin/${this.escapeHtml(customer.slug)}" target="_blank" class="btn btn-sm btn-phoenix btn-phoenix-warning">Admin</a>
                    <button onclick="PhoenixAdmin.toggleActive(${customer.id})" class="btn btn-sm btn-phoenix btn-phoenix-warning">
                        <i class="fas ${customer.is_active ? 'fa-toggle-on' : 'fa-toggle-off'}"></i>
                    </button>
                    <button onclick="PhoenixAdmin.deleteCustomer(${customer.id})" class="btn btn-sm btn-phoenix btn-phoenix-danger">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `).join('');
    },
    
    // =================================================================
    // 7. Customer Actions
    // =================================================================
    async toggleActive(customerId) {
        const customer = this.state.allCustomers.find(c => c.id == customerId);
        if (!customer) return;
        
        const action = customer.is_active ? 'désactiver' : 'activer';
        if (!await this.confirm(`Êtes-vous sûr de vouloir ${action} la carte de ${customer.name} ?`)) {
            return;
        }
        
        // Optimistic UI update
        customer.is_active = !customer.is_active;
        this.applyFiltersAndRender();
        
        try {
            // API Call
            const response = await fetch(this.config.apiEndpoints.toggle.replace('{id}', customerId), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            this.showToast(`Carte ${customer.is_active ? 'activée' : 'désactivée'} avec succès.`, 'success');
            this.emitEvent('customerToggled', { id: customerId, active: customer.is_active });
        } catch (error) {
            console.error('Toggle Active Error:', error);
            // Revert on error
            customer.is_active = !customer.is_active;
            this.applyFiltersAndRender();
            this.showToast('Erreur lors de la mise à jour du statut.', 'error');
        }
    },
    
    async deleteCustomer(customerId) {
        const customer = this.state.allCustomers.find(c => c.id == customerId);
        if (!customer) return;
        
        if (!await this.confirm(`Êtes-vous sûr de vouloir supprimer définitivement ${customer.name} ?`)) {
            return;
        }
        
        const row = document.querySelector(`tr[data-customer-id="${customerId}"]`);
        if (row) {
            row.classList.add('removing');
            setTimeout(() => row.remove(), 400);
        }
        
        try {
            // API Call
            const response = await fetch(this.config.apiEndpoints.delete.replace('{id}', customerId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            if (!response.ok) throw new Error('Network response was not ok');
            
            // Update local data
            this.state.allCustomers = this.state.allCustomers.filter(c => c.id != customerId);
            this.state.filteredCustomers = this.state.filteredCustomers.filter(c => c.id != customerId);
            
            this.showToast('Client supprimé avec succès.', 'success');
            this.renderMobileCards();
            this.emitEvent('customerDeleted', { id: customerId });
        } catch (error) {
            console.error('Delete Error:', error);
            this.showToast('Erreur lors de la suppression.', 'error');
            // Optionally, reload the page
            setTimeout(() => location.reload(), 2000);
        }
    },
    
    async sendEmail(customerId) {
        const customer = this.state.allCustomers.find(c => c.id == customerId);
        if (!customer || !customer.email) {
            this.showToast('Ce client n\'a pas d\'adresse email.', 'warning');
            return;
        }
        
        // Show loading modal
        this.showEmailModal('loading');
        
        try {
            // API Call
            const response = await fetch(this.config.apiEndpoints.sendEmail.replace('{id}', customerId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (response.ok) {
                this.showEmailModal('success');
                this.showToast('Email envoyé avec succès !', 'success');
                this.emitEvent('emailSent', { id: customerId });
            } else {
                throw new Error(result.message || 'Erreur inconnue');
            }
        } catch (error) {
            console.error('Send Email Error:', error);
            this.showEmailModal('error', error.message);
            this.showToast('Erreur lors de l\'envoi de l\'email.', 'error');
        }
    },
    
    // =================================================================
    // 8. Utility Functions
    // =================================================================
    showToast(message, type = 'info', duration = null) {
        if (!this.elements.toastContainer) return;
        
        const toast = document.createElement('div');
        toast.className = `toast toast-${type} animate-slideInRight`;
        toast.innerHTML = `
            <i class="fas fa-${this.getToastIcon(type)}" aria-hidden="true"></i>
            <span>${this.escapeHtml(message)}</span>
            <button class="toast-close" aria-label="Fermer">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>
        `;
        
        // Add close functionality
        toast.querySelector('.toast-close').addEventListener('click', () => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        });
        
        // Add to container
        this.elements.toastContainer.appendChild(toast);
        
        // Auto remove
        setTimeout(() => {
            toast.classList.add('removing');
            setTimeout(() => toast.remove(), 300);
        }, duration || this.config.toastDuration);
    },
    
    getToastIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            warning: 'exclamation-triangle',
            info: 'info-circle'
        };
        return icons[type] || 'info-circle';
    },
    
    showEmailModal(status, message = '') {
        const modal = document.getElementById('emailStatusModal');
        if (!modal) return;
        
        // Hide all status elements
        document.getElementById('emailLoading').style.display = 'none';
        document.getElementById('emailSuccess').style.display = 'none';
        document.getElementById('emailError').style.display = 'none';
        
        // Show appropriate status
        switch (status) {
            case 'loading':
                document.getElementById('emailLoading').style.display = 'block';
                break;
            case 'success':
                document.getElementById('emailSuccess').style.display = 'block';
                break;
            case 'error':
                document.getElementById('emailError').style.display = 'block';
                document.getElementById('errorMessage').textContent = message;
                break;
        }
        
        // Show modal
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
    },
    
    async confirm(message) {
        return new Promise((resolve) => {
            const modal = document.createElement('div');
            modal.className = 'modal fade';
            modal.innerHTML = `
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Confirmation</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>${this.escapeHtml(message)}</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="button" class="btn btn-primary confirm-btn">Confirmer</button>
                        </div>
                    </div>
                </div>
            `;
            
            document.body.appendChild(modal);
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();
            
            // Handle buttons
            modal.querySelector('.confirm-btn').addEventListener('click', () => {
                resolve(true);
                bsModal.hide();
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                resolve(false);
                modal.remove();
            });
        });
    },
    
    copyToClipboard(text, buttonElement) {
        navigator.clipboard.writeText(text).then(() => {
            this.showToast('Copié dans le presse-papiers !', 'success');
            
            if (buttonElement) {
                const originalIcon = buttonElement.innerHTML;
                buttonElement.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    buttonElement.innerHTML = originalIcon;
                }, 2000);
            }
        }).catch(err => {
            console.error('Failed to copy: ', err);
            this.showToast('Échec de la copie', 'error');
        });
    },
    
    handleCopy(event, button) {
        event.preventDefault();
        event.stopPropagation();
        const textToCopy = button.previousElementSibling?.textContent || '';
        this.copyToClipboard(textToCopy, button);
    },
    
    toggleDropdown(dropdownId) {
        const dropdown = document.getElementById(dropdownId);
        if (!dropdown) return;
        
        const isOpen = dropdown.classList.contains('show');
        
        // Close all other dropdowns
        document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
            if (menu !== dropdown) menu.classList.remove('show');
        });
        
        // Toggle current dropdown
        dropdown.classList.toggle('show', !isOpen);
        
        // Update ARIA attributes
        const button = dropdown.previousElementSibling;
        if (button) {
            button.setAttribute('aria-expanded', !isOpen);
        }
    },
    
    closeDropdowns(event) {
        if (!event.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
                const button = menu.previousElementSibling;
                if (button) {
                    button.setAttribute('aria-expanded', 'false');
                }
            });
        }
    },
    
    exportData(format) {
        // Implementation for data export
        const url = `/admin/export?format=${format}&filter=${this.state.activeFilter}&search=${this.state.searchQuery}`;
        window.open(url, '_blank');
        this.showToast(`Exportation en ${format.toUpperCase()} initiée`, 'info');
    },
    
    printData() {
        window.print();
    },
    
    // =================================================================
    // 9. Event Handlers
    // =================================================================
    handleResize() {
        if (window.innerWidth <= 768) {
            this.renderMobileCards();
        }
        
        if (window.innerWidth <= 992 && !this.state.sidebarCollapsed) {
            this.collapseSidebar(true);
        }
    },
    
    handleKeyboard(event) {
        // ESC key to close dropdowns
        if (event.key === 'Escape') {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
        
        // Ctrl/Cmd + K for search
        if ((event.ctrlKey || event.metaKey) && event.key === 'k') {
            event.preventDefault();
            this.elements.searchInput?.focus();
        }
        
        // Ctrl/Cmd + / for sidebar toggle
        if ((event.ctrlKey || event.metaKey) && event.key === '/') {
            event.preventDefault();
            this.toggleSidebar();
        }
    },
    
    // =================================================================
    // 10. Helper Functions
    // =================================================================
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    },
    
    emitEvent(eventName, data) {
        const event = new CustomEvent(eventName, { detail: data });
        document.dispatchEvent(event);
    },
    
    getCookie(name) {
        const value = `; ${document.cookie}`;
        const parts = value.split(`; ${name}=`);
        if (parts.length === 2) return parts.pop().split(';').shift();
    },
    
    setCookie(name, value, days) {
        const expires = new Date();
        expires.setTime(expires.getTime() + (days * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    },
    
    initializeTooltips() {
        // Initialize Bootstrap tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }
};

// =================================================================
// 11. Global Functions (for onclick attributes)
// =================================================================
window.PhoenixAdmin = PhoenixAdmin;

// Legacy global functions for backward compatibility
window.toggleActive = (id) => PhoenixAdmin.toggleActive(id);
window.deleteCustomer = (id) => PhoenixAdmin.deleteCustomer(id);
window.sendEmail = (id) => PhoenixAdmin.sendEmail(id);
window.copyToClipboard = (text, button) => PhoenixAdmin.copyToClipboard(text, button);
window.toggleDropdown = (id) => PhoenixAdmin.toggleDropdown(id);
window.exportData = (format) => PhoenixAdmin.exportData(format);
window.printData = () => PhoenixAdmin.printData();
window.toggleSidebar = () => PhoenixAdmin.toggleSidebar();
window.toggleTheme = () => PhoenixAdmin.toggleTheme();

// =================================================================
// 12. Initialize on DOM Ready
// =================================================================
document.addEventListener('DOMContentLoaded', () => {
    PhoenixAdmin.init();
});

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = PhoenixAdmin;
}