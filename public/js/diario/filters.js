// Filtros y búsqueda
function initializeFilters() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const searchInput = document.getElementById('searchDocuments');

    if (filterButtons.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', applyFilter);
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', applySearch);
    }
}

function applyFilter(event) {
    const filter = event.currentTarget.dataset.filter;
    
    // Actualizar botones activos
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.classList.remove('bg-button', 'text-white');
        btn.classList.add('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    });
    
    event.currentTarget.classList.remove('bg-gray-200', 'text-gray-700', 'hover:bg-gray-300');
    event.currentTarget.classList.add('bg-button', 'text-white');

    // Aplicar filtro
    filterDocuments(filter);
}

function applySearch(event) {
    const searchTerm = event.target.value.toLowerCase();
    filterDocuments('all', searchTerm);
}

function filterDocuments(filter = 'all', searchTerm = '') {
    const documents = document.querySelectorAll('.document-card');
    let visibleCount = 0;
    
    documents.forEach(doc => {
        const type = doc.dataset.type;
        const favorite = doc.dataset.favorite;
        const title = doc.dataset.title.toLowerCase();
        
        let show = true;
        
        // Aplicar filtro de tipo/favorito
        if (filter !== 'all') {
            if (filter === 'favorite') {
                show = favorite === 'true';
            } else {
                show = type === filter;
            }
        }
        
        // Aplicar búsqueda
        if (show && searchTerm) {
            show = title.includes(searchTerm.toLowerCase());
        }
        
        // Mostrar/ocultar con animación
        if (show) {
            doc.style.display = 'block';
            setTimeout(() => {
                doc.style.opacity = '1';
                doc.style.transform = 'scale(1)';
            }, 10);
            visibleCount++;
        } else {
            doc.style.opacity = '0';
            doc.style.transform = 'scale(0.8)';
            setTimeout(() => {
                doc.style.display = 'none';
            }, 300);
        }
    });

    // Mostrar mensaje si no hay resultados
    const emptyState = document.querySelector('.empty-state');
    if (emptyState) {
        if (visibleCount === 0) {
            emptyState.classList.remove('hidden');
        } else {
            emptyState.classList.add('hidden');
        }
    }
}