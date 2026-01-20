class DiarioApp {
    constructor() {
        this.currentEntry = null;
        this.initializeApp();
    }

    initializeApp() {
        document.addEventListener('DOMContentLoaded', () => {
            this.initializeEventListeners();
            this.initializeDocumentManager();
        });
    }

    initializeEventListeners() {
        this.setupCreateButtons();
        this.setupFilters();
    }

    setupCreateButtons() {
        const createBtn = document.getElementById('createDocumentBtn');
        const createFirstBtn = document.getElementById('createFirstDocumentBtn');
        
        if (createBtn) {
            createBtn.addEventListener('click', () => this.openEditor());
        }
        
        if (createFirstBtn) {
            createFirstBtn.addEventListener('click', () => this.openEditor());
        }
    }

    setupFilters() {
        const filterButtons = document.querySelectorAll('.filter-btn');
        const searchInput = document.getElementById('searchDocuments');

        filterButtons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                const filter = e.currentTarget.dataset.filter;
                this.applyFilter(filter);
                
                filterButtons.forEach(b => {
                    b.classList.remove('bg-button', 'text-white');
                    b.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
                });
                e.currentTarget.classList.add('bg-button', 'text-white');
                e.currentTarget.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
            });
        });

        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.applySearch(e.target.value.toLowerCase());
            });
        }
    }

    initializeDocumentManager() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.edit-btn')) {
                this.handleEdit(e.target.closest('.edit-btn'));
            }
            
            if (e.target.closest('.delete-btn')) {
                this.handleDelete(e.target.closest('.delete-btn'));
            }
            
            if (e.target.closest('.favorite-btn')) {
                this.handleFavorite(e.target.closest('.favorite-btn'));
            }
        });
    }

    applyFilter(filter = 'all') {
        const documents = document.querySelectorAll('.document-card');
        
        documents.forEach(doc => {
            const type = doc.dataset.type;
            const favorite = doc.dataset.favorite;
            
            let show = true;
            
            if (filter !== 'all') {
                if (filter === 'favorite') {
                    show = favorite === 'true';
                } else {
                    show = type === filter;
                }
            }
            
            this.toggleDocumentVisibility(doc, show);
        });
    }

    applySearch(searchTerm = '') {
        const documents = document.querySelectorAll('.document-card');
        
        documents.forEach(doc => {
            const title = doc.dataset.title;
            const type = doc.dataset.type;
            const favorite = doc.dataset.favorite;
            
            let show = true;
            
            if (searchTerm) {
                show = title.includes(searchTerm);
            }
            
            this.toggleDocumentVisibility(doc, show);
        });
    }

    toggleDocumentVisibility(doc, show) {
        if (show) {
            doc.style.display = 'block';
            setTimeout(() => {
                doc.style.opacity = '1';
                doc.style.transform = 'scale(1)';
            }, 10);
        } else {
            doc.style.opacity = '0';
            doc.style.transform = 'scale(0.8)';
            setTimeout(() => {
                doc.style.display = 'none';
            }, 300);
        }
    }

    async handleEdit(button) {
        const entryId = button.dataset.id;
        
        try {
            this.showNotification('Cargando entrada...', 'info');
            
            const response = await fetch(`/diario/${entryId}`);
            const result = await response.json();

            if (result.success) {
                this.openEditor(result.entry);
            } else {
                this.showNotification(result.message || 'Error al cargar la entrada', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al cargar la entrada', 'error');
        }
    }

    async handleDelete(button) {
        const entryId = button.dataset.id;
        
        if (window.documentManager) {
            window.documentManager.confirmDelete(button);
        } else {
            if (confirm('¿Estás seguro de que quieres eliminar esta entrada?')) {
                await this.performDelete(entryId, button);
            }
        }
    }

    async performDelete(entryId, button) {
        try {
            this.showNotification('Eliminando entrada...', 'info');
            
            const response = await fetch(`/diario/${entryId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification(result.message, 'success');
                
                const card = button.closest('.document-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => {
                        card.remove();
                        this.checkEmptyState();
                    }, 300);
                }
            } else {
                this.showNotification(result.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al eliminar', 'error');
        }
    }

    async handleFavorite(button) {
        const entryId = button.dataset.id;
        
        try {
            const response = await fetch(`/diario/${entryId}/toggle-favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                }
            });

            const result = await response.json();

            if (result.success) {
                if (result.is_favorite) {
                    button.classList.add('text-yellow-500');
                    button.classList.remove('text-gray-400');
                } else {
                    button.classList.remove('text-yellow-500');
                    button.classList.add('text-gray-400');
                }
                
                const card = button.closest('.document-card');
                if (card) {
                    card.dataset.favorite = result.is_favorite.toString();
                }
                
                this.showNotification(result.message, 'success');
            } else {
                this.showNotification(result.message || 'Error al actualizar favorito', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al actualizar favorito', 'error');
        }
    }

    openEditor(entry = null) {
        if (window.editorModal) {
            window.editorModal.open(entry);
        } else {
            console.error('Editor modal no está disponible');
            this.showNotification('Error: Editor no disponible', 'error');
        }
    }

    checkEmptyState() {
        const documentsGrid = document.getElementById('documentsGrid');
        const documents = documentsGrid.querySelectorAll('.document-card');
        
        if (documents.length === 0) {
            console.log('No hay documentos, mostrando estado vacío');
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value;
    }

    showNotification(message, type = 'info') {
        if (window.documentManager && window.documentManager.showNotification) {
            window.documentManager.showNotification(message, type);
            return;
        }

        const container = document.getElementById('notificationContainer') || this.createNotificationContainer();
        const notification = document.createElement('div');
        
        const bgColor = type === 'success' ? 'bg-green-500' : 
                       type === 'error' ? 'bg-red-500' : 
                       type === 'warning' ? 'bg-yellow-500' : 
                       'bg-button';
        
        notification.className = `${bgColor} text-white p-4 rounded-lg shadow-lg transform transition-transform duration-300 translate-x-0 mb-2`;
        notification.innerHTML = `
            <div class="flex items-center justify-between">
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200 transition-colors" onclick="this.parentElement.parentElement.remove()">
                    ×
                </button>
            </div>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 4000);
    }

    createNotificationContainer() {
        const container = document.createElement('div');
        container.id = 'notificationContainer';
        container.className = 'fixed top-4 right-4 z-50 space-y-2';
        document.body.appendChild(container);
        return container;
    }
}

const diarioApp = new DiarioApp();
window.diarioApp = diarioApp;

window.openEditorModal = (entry = null) => {
    diarioApp.openEditor(entry);
};

window.toggleFavorite = (event) => {
    diarioApp.handleFavorite(event.currentTarget || event.target);
};

window.editEntry = (event) => {
    diarioApp.handleEdit(event.currentTarget || event.target);
};

window.deleteEntry = (event) => {
    diarioApp.handleDelete(event.currentTarget || event.target);
};

