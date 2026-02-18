/**
 * DiarioApp - Manejo de la lista de documentos y filtros
 */
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
            const editBtn = e.target.closest('.edit-btn');
            const deleteBtn = e.target.closest('.delete-btn');
            const favoriteBtn = e.target.closest('.favorite-btn');

            if (editBtn) this.handleEdit(editBtn);
            if (deleteBtn) this.handleDelete(deleteBtn);
            if (favoriteBtn) this.handleFavorite(favoriteBtn);
        });
    }

    applyFilter(filter = 'all') {
        const documents = document.querySelectorAll('.document-card');
        documents.forEach(doc => {
            const type = doc.dataset.type;
            const favorite = doc.dataset.favorite;
            let show = filter === 'all' || (filter === 'favorite' ? favorite === 'true' : type === filter);
            this.toggleDocumentVisibility(doc, show);
        });
    }

    applySearch(searchTerm = '') {
        const documents = document.querySelectorAll('.document-card');
        documents.forEach(doc => {
            const title = (doc.dataset.title || '').toLowerCase();
            let show = searchTerm === '' || title.includes(searchTerm);
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
        const entryId = button.dataset.id || button.closest('.document-card').dataset.id;
        try {
            this.showNotification('Cargando entrada...', 'info');
            // Ruta RESTful GET /diario/{id}
            const response = await fetch(`/diario/${entryId}`);
            const result = await response.json();
            
            if (result) {
                // Ajuste dependiendo de si tu controlador devuelve el objeto directo o envuelto en success
                const entry = result.entry || result;
                this.openEditor(entry);
            }
        } catch (error) {
            this.showNotification('Error al cargar la entrada', 'error');
        }
    }

    async handleDelete(button) {
        const entryId = button.dataset.id || button.closest('.document-card').dataset.id;
        
        if (window.editorModal) {
            window.editorModal.showCustomConfirm(
                '¿Estás seguro de que deseas eliminar este archivo? Esta acción no se puede deshacer.',
                async () => {
                    await this.performDelete(entryId, button);
                }
            );
        }
    }

    async performDelete(entryId, button) {
        try {
            this.showNotification('Eliminando...', 'info');
            // Ruta RESTful DELETE /diario/{id}
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
                this.showNotification('Entrada eliminada correctamente', 'success');
                const card = button.closest('.document-card');
                if (card) {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.8)';
                    setTimeout(() => card.remove(), 300);
                }
            } else {
                this.showNotification(result.message || 'Error al eliminar', 'error');
            }
        } catch (error) {
            this.showNotification('Error de conexión al eliminar', 'error');
        }
    }

    async handleFavorite(button) {
        const entryId = button.dataset.id || button.closest('.document-card').dataset.id;
        try {
            // Nota: Verifica que tengas esta ruta en tu web.php o cámbiala por la correspondiente
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
                const icon = button.querySelector('i') || button.querySelector('img');
                const card = button.closest('.document-card');
                
                if (result.is_favorite) {
                    button.classList.add('text-yellow-500');
                    if(card) card.dataset.favorite = "true";
                } else {
                    button.classList.remove('text-yellow-500');
                    if(card) card.dataset.favorite = "false";
                }
                this.showNotification(result.message, 'success');
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    openEditor(entry = null) {
        if (window.editorModal) {
            window.editorModal.open(entry);
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    showNotification(message, type = 'info') {
        if (window.editorModal) {
            window.editorModal.showNotification(message, type);
        }
    }
}

const diarioApp = new DiarioApp();
window.diarioApp = diarioApp;

// Funciones globales para botones dinámicos
window.editEntry = (event) => diarioApp.handleEdit(event.currentTarget);
window.deleteEntry = (event) => diarioApp.handleDelete(event.currentTarget);
window.toggleFavorite = (event) => diarioApp.handleFavorite(event.currentTarget);