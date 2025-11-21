class EditorModal {
    constructor() {
        this.currentEntry = null;
        this.currentType = 'texto';
        this.mindMapNodes = [];
        this.nodeCounter = 0;
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Botones de abrir modal
        document.getElementById('createDocumentBtn')?.addEventListener('click', () => this.open());
        document.getElementById('createFirstDocumentBtn')?.addEventListener('click', () => this.open());

        // Botones del modal
        document.getElementById('cancelEditorBtn')?.addEventListener('click', () => this.close());
        document.getElementById('saveDocumentBtn')?.addEventListener('click', () => this.save());

        // Cambio de tipo de documento
        document.getElementById('docType')?.addEventListener('change', (e) => this.changeType(e.target.value));

        // Botones de formato
        this.initializeFormatButtons();

        // Botones específicos por tipo
        document.getElementById('addTaskBtn')?.addEventListener('click', () => this.addTask());
        document.getElementById('addMindMapNodeBtn')?.addEventListener('click', () => this.addMindMapNode());
        document.getElementById('clearMindMapBtn')?.addEventListener('click', () => this.clearMindMap());
        document.getElementById('centerMapBtn')?.addEventListener('click', () => this.centerMindMap());
    }

    open(entry = null) {
        this.currentEntry = entry;
        const modal = document.getElementById('documentEditor');
        
        if (!modal) {
            console.error('Modal no encontrado');
            return;
        }
        
        if (entry) {
            // Modo edición
            this.loadEntry(entry);
        } else {
            // Modo creación
            this.resetForm();
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
        // Enfocar el título
        setTimeout(() => {
            const titleInput = document.getElementById('docTitle');
            if (titleInput) titleInput.focus();
        }, 100);
    }

    close() {
        const modal = document.getElementById('documentEditor');
        if (modal) {
            modal.classList.add('hidden');
        }
        document.body.style.overflow = 'auto';
        this.currentEntry = null;
    }

    resetForm() {
        // Resetear campos básicos
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (titleInput) titleInput.value = '';
        if (colorInput) colorInput.value = '#3b82f6';
        if (favoriteInput) favoriteInput.checked = false;
        if (typeSelect) typeSelect.value = 'texto';
        
        // Resetear editores de forma segura
        this.changeType('texto');
        
        // Limpiar contenido de editores solo si existen
        const textEditor = document.getElementById('textEditor');
        if (textEditor) {
            textEditor.innerHTML = '<p>Comienza a escribir aquí...</p>';
        }
        
        // NOTA: Ya no existe reflectionEditor, se unificó con texto
        this.clearMindMap();
        this.clearTasks();
    }

    loadEntry(entry) {
        console.log('Loading entry:', entry);
        
        // Cargar datos básicos
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (titleInput) titleInput.value = entry.title || '';
        if (colorInput) colorInput.value = entry.color || '#3b82f6';
        if (favoriteInput) favoriteInput.checked = entry.is_favorite || false;
        if (typeSelect) typeSelect.value = entry.type || 'texto';
        
        this.changeType(entry.type || 'texto');
        
        // Cargar contenido según el tipo
        switch(entry.type) {
            case 'texto':
                const textEditor = document.getElementById('textEditor');
                if (textEditor) {
                    textEditor.innerHTML = entry.content || '<p>Comienza a escribir aquí...</p>';
                }
                break;
            case 'lista':
                this.loadTasks(entry.content);
                break;
            case 'mapa_conceptual':
                this.loadMindMap(entry.content);
                break;
            default:
                // Fallback para tipos no especificados
                const defaultEditor = document.getElementById('textEditor');
                if (defaultEditor) {
                    defaultEditor.innerHTML = entry.content || '<p>Comienza a escribir aquí...</p>';
                }
        }
    }

    changeType(newType) {
        this.currentType = newType;
        
        console.log('Changing type to:', newType);
        
        // Ocultar todos los paneles de forma segura
        document.querySelectorAll('.editor-panel').forEach(panel => {
            if (panel) panel.classList.add('hidden');
        });
        
        // Ocultar/mostrar toolbar de texto
        const textToolbar = document.getElementById('textToolbar');
        if (textToolbar) {
            if (newType === 'texto') {
                textToolbar.classList.remove('hidden');
            } else {
                textToolbar.classList.add('hidden');
            }
        }
        
        // Mostrar panel correspondiente
        let panelToShow = null;
        switch(newType) {
            case 'texto':
                panelToShow = document.getElementById('textEditor');
                break;
            case 'mapa_conceptual':
                panelToShow = document.getElementById('mindMapEditor');
                break;
            case 'lista':
                panelToShow = document.getElementById('listEditor');
                break;
        }
        
        if (panelToShow) {
            panelToShow.classList.remove('hidden');
        } else {
            console.warn('Panel not found for type:', newType);
        }
    }

    async save() {
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (!titleInput || !colorInput || !favoriteInput || !typeSelect) {
            this.showNotification('Error: Campos del formulario no encontrados', 'error');
            return;
        }

        const title = titleInput.value.trim();
        const color = colorInput.value;
        const isFavorite = favoriteInput.checked;
        const type = typeSelect.value;

        if (!title) {
            this.showNotification('Por favor, ingresa un título', 'error');
            return;
        }

        let content = '';
        
        // Obtener contenido según el tipo de forma segura
        switch(type) {
            case 'texto':
                const textEditor = document.getElementById('textEditor');
                if (textEditor) {
                    content = textEditor.innerHTML;
                }
                break;
            case 'lista':
                content = this.getTasksContent();
                break;
            case 'mapa_conceptual':
                content = this.getMindMapContent();
                break;
        }

        // Validar contenido mínimo
        if (!content || content === '<p><br></p>' || content === '<p>Comienza a escribir aquí...</p>') {
            if (type === 'texto') {
                this.showNotification('Por favor, escribe algún contenido', 'error');
                return;
            } else if (type === 'lista' && content === '[]') {
                this.showNotification('Por favor, agrega al menos una tarea', 'error');
                return;
            } else if (type === 'mapa_conceptual' && content === '{"nodes":[]}') {
                this.showNotification('Por favor, agrega al menos un nodo al mapa conceptual', 'error');
                return;
            }
        }

        const data = {
            title: title,
            content: content,
            type: type,
            color: color,
            is_favorite: isFavorite
        };

        console.log('Saving data:', data);

        try {
            let url = '/diario';
            let method = 'POST';
            
            if (this.currentEntry) {
                url = `/diario/${this.currentEntry.id}`;
                method = 'PUT';
            }

            const csrfToken = this.getCsrfToken();
            if (!csrfToken) {
                this.showNotification('Error de seguridad', 'error');
                return;
            }

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();
            console.log('Save response:', result);

            if (result.success) {
                this.showNotification(result.message, 'success');
                this.close();
                
                // Recargar la página para ver los cambios
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                this.showNotification(result.message || 'Error al guardar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al guardar la entrada', 'error');
        }
    }

    // ========== MÉTODOS PARA LISTAS ==========
    addTask() {
        const container = document.getElementById('tasksContainer');
        if (!container) return;
        
        // Si es el mensaje vacío, limpiarlo
        if (container.children.length === 1 && container.querySelector('.text-center')) {
            container.innerHTML = '';
        }

        const taskHTML = `
            <div class="task-item bg-white border border-gray-200 rounded-lg p-4 flex items-center space-x-3 hover:shadow-md transition-shadow mb-3">
                <input type="checkbox" class="task-checkbox w-4 h-4 text-blue-600 rounded">
                <input type="text" class="task-text flex-1 border-none outline-none bg-transparent" placeholder="Describe tu tarea..." value="">
                <button class="delete-task text-red-500 hover:text-red-700 p-1 transition-colors">
                    🗑️
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', taskHTML);
        
        // Agregar evento al botón eliminar
        const lastTask = container.lastElementChild;
        const deleteBtn = lastTask.querySelector('.delete-task');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', () => {
                lastTask.remove();
                // Si no quedan tareas, mostrar mensaje
                if (container.children.length === 0) {
                    container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún. ¡Agrega tu primera tarea!</p></div>';
                }
            });
        }
        
        // Enfocar el nuevo campo de texto
        const textInput = lastTask.querySelector('.task-text');
        if (textInput) textInput.focus();
    }

    clearTasks() {
        const container = document.getElementById('tasksContainer');
        if (container) {
            container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún. ¡Agrega tu primera tarea!</p></div>';
        }
    }

    loadTasks(content) {
        this.clearTasks();
        
        if (!content || content === '[]') return;
        
        try {
            const tasks = JSON.parse(content);
            const container = document.getElementById('tasksContainer');
            if (!container) return;
            
            container.innerHTML = '';
            
            tasks.forEach(task => {
                const taskHTML = `
                    <div class="task-item bg-white border border-gray-200 rounded-lg p-4 flex items-center space-x-3 hover:shadow-md transition-shadow mb-3">
                        <input type="checkbox" class="task-checkbox w-4 h-4 text-blue-600 rounded" ${task.completed ? 'checked' : ''}>
                        <input type="text" class="task-text flex-1 border-none outline-none bg-transparent" placeholder="Describe tu tarea..." value="${task.text || ''}">
                        <button class="delete-task text-red-500 hover:text-red-700 p-1 transition-colors">
                            🗑️
                        </button>
                    </div>
                `;
                container.insertAdjacentHTML('beforeend', taskHTML);
                
                // Agregar evento eliminar
                const taskElement = container.lastElementChild;
                const deleteBtn = taskElement.querySelector('.delete-task');
                if (deleteBtn) {
                    deleteBtn.addEventListener('click', () => {
                        taskElement.remove();
                        if (container.children.length === 0) {
                            container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún. ¡Agrega tu primera tarea!</p></div>';
                        }
                    });
                }
            });
            
            // Si no se cargaron tareas, mostrar mensaje
            if (container.children.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún. ¡Agrega tu primera tarea!</p></div>';
            }
        } catch (e) {
            console.error('Error loading tasks:', e);
            this.clearTasks();
        }
    }

    getTasksContent() {
        const tasks = [];
        const taskItems = document.querySelectorAll('.task-item');
        
        taskItems.forEach(item => {
            const textInput = item.querySelector('.task-text');
            const checkbox = item.querySelector('.task-checkbox');
            
            if (textInput && textInput.value.trim()) {
                tasks.push({
                    text: textInput.value.trim(),
                    completed: checkbox ? checkbox.checked : false
                });
            }
        });
        
        return JSON.stringify(tasks);
    }

    // ========== MÉTODOS PARA MAPA CONCEPTUAL ==========
    addMindMapNode() {
        const container = document.querySelector('.mind-map-container');
        if (!container) return null;
        
        // Remover mensaje de inicio si existe
        const emptyMessage = container.querySelector('.text-center');
        if (emptyMessage) {
            emptyMessage.remove();
        }

        this.nodeCounter++;
        const nodeId = `node-${this.nodeCounter}`;
        
        const nodeHTML = `
            <div id="${nodeId}" class="mind-map-node absolute bg-white border-2 border-blue-500 rounded-lg p-3 shadow-lg cursor-move min-w-32 max-w-48" 
                 style="top: ${100 + this.nodeCounter * 10}px; left: ${100 + this.nodeCounter * 10}px;"
                 data-id="${nodeId}">
                <input type="text" class="node-text w-full border-none outline-none text-center font-semibold bg-transparent" 
                       placeholder="Escribe el concepto..." value="Concepto ${this.nodeCounter}">
                <div class="flex justify-center space-x-2 mt-2">
                    <button class="delete-node text-red-500 hover:text-red-700 text-xs p-1 transition-colors" title="Eliminar nodo">
                        ×
                    </button>
                </div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', nodeHTML);
        
        const newNode = document.getElementById(nodeId);
        if (newNode) {
            this.makeDraggable(newNode);
            
            // Evento para eliminar nodo
            const deleteBtn = newNode.querySelector('.delete-node');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    newNode.remove();
                });
            }
            
            // Enfocar el campo de texto
            setTimeout(() => {
                const textInput = newNode.querySelector('.node-text');
                if (textInput) textInput.focus();
            }, 100);
        }
        
        return newNode;
    }

    clearMindMap() {
        const container = document.querySelector('.mind-map-container');
        if (container) {
            container.innerHTML = '<div class="absolute inset-0 flex items-center justify-center text-gray-500"><div class="text-center"><p class="text-lg mb-2">Mapa Conceptual</p><p class="text-sm">Haz clic en "Agregar Nodo" para comenzar</p></div></div>';
        }
        this.nodeCounter = 0;
        this.mindMapNodes = [];
    }

    centerMindMap() {
        const nodes = document.querySelectorAll('.mind-map-node');
        if (nodes.length === 0) return;
        
        const container = document.querySelector('.mind-map-container');
        if (!container) return;
        
        const containerRect = container.getBoundingClientRect();
        
        nodes.forEach((node, index) => {
            const left = (containerRect.width - 150) / 2 + (index % 3) * 160;
            const top = (containerRect.height - 80) / 2 + Math.floor(index / 3) * 100;
            
            node.style.left = left + 'px';
            node.style.top = top + 'px';
        });
    }

    loadMindMap(content) {
        this.clearMindMap();
        
        if (!content || content === '{"nodes":[]}') return;
        
        try {
            const mapData = JSON.parse(content);
            const container = document.querySelector('.mind-map-container');
            if (!container) return;
            
            // Remover mensaje vacío
            const emptyMessage = container.querySelector('.text-center');
            if (emptyMessage) {
                emptyMessage.remove();
            }
            
            if (mapData.nodes && Array.isArray(mapData.nodes)) {
                mapData.nodes.forEach(nodeData => {
                    const newNode = this.addMindMapNode();
                    if (newNode) {
                        const textInput = newNode.querySelector('.node-text');
                        if (textInput) {
                            textInput.value = nodeData.text || '';
                        }
                        
                        if (nodeData.position) {
                            newNode.style.left = nodeData.position.x + 'px';
                            newNode.style.top = nodeData.position.y + 'px';
                        }
                    }
                });
            }
        } catch (e) {
            console.error('Error loading mind map:', e);
            this.clearMindMap();
        }
    }

    getMindMapContent() {
        const nodes = [];
        const nodeElements = document.querySelectorAll('.mind-map-node');
        
        nodeElements.forEach(node => {
            const textInput = node.querySelector('.node-text');
            if (textInput && textInput.value.trim()) {
                nodes.push({
                    id: node.id,
                    text: textInput.value.trim(),
                    position: {
                        x: parseInt(node.style.left) || 0,
                        y: parseInt(node.style.top) || 0
                    }
                });
            }
        });
        
        return JSON.stringify({ nodes: nodes });
    }

    makeDraggable(element) {
        let isDragging = false;
        let startX, startY, initialX, initialY;

        element.addEventListener('mousedown', startDrag);
        element.addEventListener('touchstart', startDrag);

        function startDrag(e) {
            e.preventDefault();
            isDragging = true;
            
            if (e.type === 'mousedown') {
                startX = e.clientX;
                startY = e.clientY;
            } else {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
            }
            
            initialX = element.offsetLeft;
            initialY = element.offsetTop;

            document.addEventListener('mousemove', drag);
            document.addEventListener('touchmove', drag);
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchend', stopDrag);
        }

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();

            let currentX, currentY;
            
            if (e.type === 'mousemove') {
                currentX = e.clientX;
                currentY = e.clientY;
            } else {
                currentX = e.touches[0].clientX;
                currentY = e.touches[0].clientY;
            }

            const dx = currentX - startX;
            const dy = currentY - startY;

            element.style.left = (initialX + dx) + 'px';
            element.style.top = (initialY + dy) + 'px';
        }

        function stopDrag() {
            isDragging = false;
            document.removeEventListener('mousemove', drag);
            document.removeEventListener('touchmove', drag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('touchend', stopDrag);
        }
    }

    // ========== MÉTODOS DE FORMATEO ==========
    initializeFormatButtons() {
        document.querySelectorAll('.format-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const command = btn.dataset.command;
                const value = btn.dataset.value;
                
                try {
                    if (value) {
                        document.execCommand(command, false, value);
                    } else {
                        document.execCommand(command, false, null);
                    }
                    
                    // Actualizar estado visual
                    document.querySelectorAll('.format-btn').forEach(b => b.classList.remove('bg-gray-200'));
                    btn.classList.add('bg-gray-200');
                } catch (error) {
                    console.error('Format command error:', error);
                }
            });
        });

        // Cambios de fuente y tamaño
        const fontFamily = document.getElementById('fontFamily');
        const fontSize = document.getElementById('fontSize');
        
        if (fontFamily) {
            fontFamily.addEventListener('change', (e) => {
                document.execCommand('fontName', false, e.target.value);
            });
        }
        
        if (fontSize) {
            fontSize.addEventListener('change', (e) => {
                document.execCommand('fontSize', false, e.target.value);
            });
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value;
    }

    showNotification(message, type = 'info') {
        // Remover notificaciones existentes
        document.querySelectorAll('.custom-notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transform transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' : 
            type === 'error' ? 'bg-red-500' : 
            'bg-blue-500'
        }`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Auto-eliminar después de 4 segundos
        setTimeout(() => {
            if (notification.parentNode) {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }
        }, 4000);
    }
}

// Inicializar el modal cuando se carga la página
document.addEventListener('DOMContentLoaded', function() {
    window.editorModal = new EditorModal();
    
    // Hacer disponible globalmente para los botones de edición
    window.openEditorModal = (entry = null) => {
        window.editorModal.open(entry);
    };
    
    console.log('Editor modal initialized');
});