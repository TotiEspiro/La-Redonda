class EditorModal {
    constructor() {
        this.currentEntry = null;
        this.currentType = 'texto';
        this.nodeCounter = 0;
        this.connections = []; 
        this.isConnecting = false; 
        this.sourceNodeId = null; 
        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Botones principales
        document.getElementById('createDocumentBtn')?.addEventListener('click', () => this.open());
        document.getElementById('createFirstDocumentBtn')?.addEventListener('click', () => this.open());
        document.getElementById('cancelEditorBtn')?.addEventListener('click', () => this.close());
        document.getElementById('saveDocumentBtn')?.addEventListener('click', () => this.save());
        document.getElementById('docType')?.addEventListener('change', (e) => this.changeType(e.target.value));

        // Formato de texto
        this.initializeFormatButtons();

        // Botones específicos
        document.getElementById('addTaskBtn')?.addEventListener('click', () => this.addTask());
        document.getElementById('addMindMapNodeBtn')?.addEventListener('click', () => this.addMindMapNode());
        document.getElementById('clearMindMapBtn')?.addEventListener('click', () => this.clearMindMap());
        document.getElementById('centerMapBtn')?.addEventListener('click', () => this.centerMindMap());

        // Listener global para cancelar conexión con escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isConnecting) {
                this.cancelConnectionMode();
            }
        });
    }

    open(entry = null) {
        this.currentEntry = entry;
        const modal = document.getElementById('documentEditor');
        
        if (!modal) {
            console.error('Modal no encontrado');
            return;
        }
        
        if (entry) {
            this.loadEntry(entry);
        } else {
            this.resetForm();
        }
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        
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
        this.cancelConnectionMode();
    }

    resetForm() {
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (titleInput) titleInput.value = '';
        if (colorInput) colorInput.value = '#3b82f6';
        if (favoriteInput) favoriteInput.checked = false;
        if (typeSelect) typeSelect.value = 'texto';
        
        this.changeType('texto');
        
        const textEditor = document.getElementById('textEditor');
        if (textEditor) {
            textEditor.innerHTML = '<p>Comienza a escribir aquí...</p>';
        }
        
        this.clearMindMap();
        this.clearTasks();
        this.connections = [];
    }

    loadEntry(entry) {
        console.log('Cargando entrada:', entry);
        
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (titleInput) titleInput.value = entry.title || '';
        if (colorInput) colorInput.value = entry.color || '#3b82f6';
        if (favoriteInput) favoriteInput.checked = entry.is_favorite || false;
        if (typeSelect) typeSelect.value = entry.type || 'texto';
        
        this.changeType(entry.type || 'texto');
        
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
                const defaultEditor = document.getElementById('textEditor');
                if (defaultEditor) {
                    defaultEditor.innerHTML = entry.content || '<p>Comienza a escribir aquí...</p>';
                }
        }
    }

    changeType(newType) {
        this.currentType = newType;
        
        document.querySelectorAll('.editor-panel').forEach(panel => {
            if (panel) panel.classList.add('hidden');
        });
        
        const textToolbar = document.getElementById('textToolbar');
        if (textToolbar) {
            if (newType === 'texto') {
                textToolbar.classList.remove('hidden');
            } else {
                textToolbar.classList.add('hidden');
            }
        }
        
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
        }
    }

    async save() {
        const titleInput = document.getElementById('docTitle');
        const colorInput = document.getElementById('docColor');
        const favoriteInput = document.getElementById('docFavorite');
        const typeSelect = document.getElementById('docType');
        
        if (!titleInput || !colorInput || !favoriteInput || !typeSelect) return;

        const title = titleInput.value.trim();
        const color = colorInput.value;
        const isFavorite = favoriteInput.checked;
        const type = typeSelect.value;

        if (!title) {
            this.showNotification('Por favor, ingresa un título', 'error');
            return;
        }

        let content = '';
        
        switch(type) {
            case 'texto':
                const textEditor = document.getElementById('textEditor');
                content = textEditor ? textEditor.innerHTML : '';
                break;
            case 'lista':
                content = this.getTasksContent();
                break;
            case 'mapa_conceptual':
                content = this.getMindMapContent();
                break;
        }

        // Validaciones básicas
        if (!content || content === '<p><br></p>' || content === '<p>Comienza a escribir aquí...</p>') {
            if (type === 'texto') {
                this.showNotification('Por favor, escribe algún contenido', 'error');
                return;
            } else if (type === 'lista' && content === '[]') {
                this.showNotification('Por favor, agrega al menos una tarea', 'error');
                return;
            } else if (type === 'mapa_conceptual') {
                const mapData = JSON.parse(content);
                if (mapData.nodes.length === 0) {
                    this.showNotification('Por favor, agrega al menos un nodo', 'error');
                    return;
                }
            }
        }

        const data = { title, content, type, color, is_favorite: isFavorite };

        try {
            let url = '/diario';
            let method = 'POST';
            
            if (this.currentEntry) {
                url = `/diario/${this.currentEntry.id}`;
                method = 'PUT';
            }

            const csrfToken = this.getCsrfToken();
            if (!csrfToken) return;

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

            if (result.success) {
                this.showNotification(result.message, 'success');
                this.close();
                setTimeout(() => window.location.reload(), 1500);
            } else {
                this.showNotification(result.message || 'Error al guardar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al guardar', 'error');
        }
    }

    
    addTask() {
        const container = document.getElementById('tasksContainer');
        if (!container) return;
        
        if (container.children.length === 1 && container.querySelector('.text-center')) {
            container.innerHTML = '';
        }

        const template = document.getElementById('taskTemplate');
        const clone = template.content.cloneNode(true);
        
        const taskItem = clone.querySelector('.task-item');
        container.appendChild(taskItem);
        
        const deleteBtn = taskItem.querySelector('.delete-task');
        deleteBtn.addEventListener('click', () => {
            taskItem.remove();
            if (container.children.length === 0) {
                container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún. ¡Agrega tu primera tarea!</p></div>';
            }
        });
        
        const textInput = taskItem.querySelector('.task-text');
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
                const template = document.getElementById('taskTemplate');
                const clone = template.content.cloneNode(true);
                const taskItem = clone.querySelector('.task-item');
                
                const checkbox = taskItem.querySelector('.task-checkbox');
                const input = taskItem.querySelector('.task-text');
                
                if (checkbox) checkbox.checked = task.completed;
                if (input) input.value = task.text || '';
                
                const deleteBtn = taskItem.querySelector('.delete-task');
                deleteBtn.addEventListener('click', () => {
                    taskItem.remove();
                    if (container.children.length === 0) {
                        container.innerHTML = '<div class="text-center text-gray-500 py-8"><p>No hay tareas aún.</p></div>';
                    }
                });
                
                container.appendChild(taskItem);
            });
        } catch (e) {
            console.error('Error tareas:', e);
            this.clearTasks();
        }
    }

    getTasksContent() {
        const tasks = [];
        document.querySelectorAll('.task-item').forEach(item => {
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

    

    addMindMapNode() {
        const container = document.querySelector('.mind-map-container');
        if (!container) return null;
        
        const emptyMsg = document.getElementById('emptyMapMsg');
        if (emptyMsg) emptyMsg.style.display = 'none';

        this.nodeCounter++;
        const nodeId = `node-${this.nodeCounter}`;
        
        const template = document.getElementById('mindMapNodeTemplate');
        const clone = template.content.cloneNode(true);
        const nodeEl = clone.querySelector('.mind-map-node');
        
        nodeEl.id = nodeId;
        const offset = (this.nodeCounter * 20) % 200;
        nodeEl.style.top = `${50 + offset}px`;
        nodeEl.style.left = `${50 + offset}px`;
        
        const input = nodeEl.querySelector('.node-text');
        input.addEventListener('input', (e) => {
            e.target.setAttribute('value', e.target.value);
        });
        input.addEventListener('mousedown', (e) => e.stopPropagation());

        const deleteBtn = nodeEl.querySelector('.delete-node');
        deleteBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.deleteNode(nodeId);
        });
        deleteBtn.addEventListener('mousedown', (e) => e.stopPropagation());

        const connectBtn = nodeEl.querySelector('.connect-node');
        connectBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.handleConnectionClick(nodeId);
        });
        connectBtn.addEventListener('mousedown', (e) => e.stopPropagation());

        this.makeDraggable(nodeEl);
        container.appendChild(nodeEl);
        setTimeout(() => input.focus(), 50);

        return nodeEl;
    }

    handleConnectionClick(nodeId) {
        const node = document.getElementById(nodeId);
        const btn = node.querySelector('.connect-node');

        if (this.isConnecting) {
            if (this.sourceNodeId === nodeId) {
                this.cancelConnectionMode();
            } else {
                this.createConnection(this.sourceNodeId, nodeId);
                this.cancelConnectionMode();
            }
        } else {
            this.isConnecting = true;
            this.sourceNodeId = nodeId;
            
            node.classList.add('ring-2', 'ring-yellow-400');
            btn.innerHTML = '❌';
            btn.classList.add('text-red-500');
            
            document.querySelector('.mind-map-container').style.cursor = 'crosshair';
        }
    }

    cancelConnectionMode() {
        if (this.sourceNodeId) {
            const node = document.getElementById(this.sourceNodeId);
            if (node) {
                node.classList.remove('ring-2', 'ring-yellow-400');
                const btn = node.querySelector('.connect-node');
                if (btn) {
                    btn.innerHTML = '🔗';
                    btn.classList.remove('text-red-500');
                }
            }
        }
        this.isConnecting = false;
        this.sourceNodeId = null;
        const container = document.querySelector('.mind-map-container');
        if (container) container.style.cursor = 'default';
    }

    createConnection(fromId, toId) {
        const exists = this.connections.some(c => 
            (c.from === fromId && c.to === toId) || 
            (c.from === toId && c.to === fromId)
        );

        if (!exists && fromId !== toId) {
            this.connections.push({ from: fromId, to: toId });
            this.drawConnections();
        }
    }

    deleteNode(nodeId) {
        const node = document.getElementById(nodeId);
        if (node) node.remove();

        this.connections = this.connections.filter(c => c.from !== nodeId && c.to !== nodeId);
        this.drawConnections();

        const container = document.querySelector('.mind-map-container');
        if (container && container.querySelectorAll('.mind-map-node').length === 0) {
            const emptyMsg = document.getElementById('emptyMapMsg');
            if (emptyMsg) emptyMsg.style.display = 'flex';
        }
    }

    drawConnections() {
        const svg = document.getElementById('connectionsLayer');
        if (!svg) return;
        
        svg.innerHTML = '';

        this.connections.forEach(conn => {
            const nodeA = document.getElementById(conn.from);
            const nodeB = document.getElementById(conn.to);

            if (nodeA && nodeB) {
                const rectA = this.getNodeCenter(nodeA);
                const rectB = this.getNodeCenter(nodeB);

                const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
                line.setAttribute('x1', rectA.x);
                line.setAttribute('y1', rectA.y);
                line.setAttribute('x2', rectB.x);
                line.setAttribute('y2', rectB.y);
                line.setAttribute('stroke', '#9CA3AF');
                line.setAttribute('stroke-width', '2');
                
                svg.appendChild(line);
            }
        });
    }

    getNodeCenter(element) {
        return {
            x: element.offsetLeft + (element.offsetWidth / 2),
            y: element.offsetTop + (element.offsetHeight / 2)
        };
    }

    makeDraggable(element) {
        const self = this; 
        let isDragging = false;
        let startX, startY, initialX, initialY;

        function drag(e) {
            if (!isDragging) return;
            e.preventDefault();

            const clientX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
            const clientY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;

            const dx = clientX - startX;
            const dy = clientY - startY;

            element.style.left = `${initialX + dx}px`;
            element.style.top = `${initialY + dy}px`;

            self.drawConnections();
        }

        function stopDrag() {
            isDragging = false;
            element.style.zIndex = ''; 
            document.removeEventListener('mousemove', drag);
            document.removeEventListener('touchmove', drag);
            document.removeEventListener('mouseup', stopDrag);
            document.removeEventListener('touchend', stopDrag);
        }

        function startDrag(e) {
            if (e.target.closest('input') || e.target.closest('button') || 
                e.target.closest('.delete-node') || e.target.closest('.connect-node')) {
                return;
            }

            e.preventDefault();
            isDragging = true;
            
            const clientX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
            const clientY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
            
            startX = clientX;
            startY = clientY;
            initialX = element.offsetLeft;
            initialY = element.offsetTop;

            document.addEventListener('mousemove', drag);
            document.addEventListener('touchmove', drag, { passive: false });
            document.addEventListener('mouseup', stopDrag);
            document.addEventListener('touchend', stopDrag);
            
            element.style.zIndex = '50';
        }

        element.addEventListener('mousedown', startDrag);
        element.addEventListener('touchstart', startDrag, { passive: false });
    }

    clearMindMap() {
        const container = document.querySelector('.mind-map-container');
        if (container) {
            const nodes = container.querySelectorAll('.mind-map-node');
            nodes.forEach(n => n.remove());
            
            const emptyMsg = document.getElementById('emptyMapMsg');
            if (emptyMsg) emptyMsg.style.display = 'flex';
        }
        
        const svg = document.getElementById('connectionsLayer');
        if (svg) svg.innerHTML = '';

        this.nodeCounter = 0;
        this.connections = [];
        this.cancelConnectionMode();
    }

    centerMindMap() {
        const nodes = document.querySelectorAll('.mind-map-node');
        if (nodes.length === 0) return;
        
        const container = document.querySelector('.mind-map-container');
        const rect = container.getBoundingClientRect();
        
        nodes.forEach((node, index) => {
            const left = (rect.width - 150) / 2 + ((index % 3) - 1) * 180;
            const top = (rect.height - 100) / 2 + (Math.floor(index / 3) * 120);
            
            node.style.left = `${left}px`;
            node.style.top = `${top}px`;
        });
        
        this.drawConnections();
    }

    getMindMapContent() {
        const nodes = [];
        document.querySelectorAll('.mind-map-node').forEach(node => {
            const textInput = node.querySelector('.node-text');
            nodes.push({
                id: node.id,
                text: textInput ? textInput.value : '',
                position: {
                    x: parseInt(node.style.left) || 0,
                    y: parseInt(node.style.top) || 0
                }
            });
        });
        
        return JSON.stringify({ nodes, connections: this.connections });
    }

    loadMindMap(content) {
        this.clearMindMap();
        if (!content || content === '{"nodes":[]}') return;
        
        try {
            const mapData = JSON.parse(content);
            let maxId = 0;
            
            if (mapData.nodes) {
                mapData.nodes.forEach(nodeData => {
                    const newNode = this.addMindMapNode();
                    if (newNode) {
                        newNode.id = nodeData.id;
                        const input = newNode.querySelector('.node-text');
                        if (input) {
                            input.value = nodeData.text || '';
                            input.setAttribute('value', nodeData.text || '');
                        }
                        if (nodeData.position) {
                            newNode.style.left = nodeData.position.x + 'px';
                            newNode.style.top = nodeData.position.y + 'px';
                        }
                        
                        const numId = parseInt(nodeData.id.replace('node-', ''));
                        if (!isNaN(numId) && numId > maxId) maxId = numId;
                    }
                });
            }
            this.nodeCounter = maxId;

            if (mapData.connections) {
                this.connections = mapData.connections;
                setTimeout(() => this.drawConnections(), 0);
            }
        } catch (e) {
            console.error('Error carga mapa:', e);
        }
    }

    

    initializeFormatButtons() {
        document.querySelectorAll('.format-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault(); 
                e.stopPropagation();
                
                const command = btn.dataset.command;
                const value = btn.dataset.value || null;
                
                document.execCommand(command, false, value);
                
                const editor = document.getElementById('textEditor');
                if (editor) editor.focus();
            });
        });

        const fontFamily = document.getElementById('fontFamily');
        if (fontFamily) {
            fontFamily.addEventListener('change', (e) => {
                document.execCommand('fontName', false, e.target.value);
                document.getElementById('textEditor')?.focus();
            });
        }
        
        const fontSize = document.getElementById('fontSize');
        if (fontSize) {
            fontSize.addEventListener('change', (e) => {
                document.execCommand('fontSize', false, e.target.value);
                document.getElementById('textEditor')?.focus();
            });
        }
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || 
               document.querySelector('input[name="_token"]')?.value;
    }

    showNotification(message, type = 'info') {
        document.querySelectorAll('.custom-notification').forEach(n => n.remove());
        
        const notification = document.createElement('div');
        notification.className = `custom-notification fixed top-4 right-4 p-4 rounded-lg shadow-lg text-white z-50 transition-transform duration-300 ${
            type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500'
        }`;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => notification.remove(), 300);
        }, 4000);
    }
}

document.addEventListener('DOMContentLoaded', function() {
    window.editorModal = new EditorModal();
    window.openEditorModal = (entry) => window.editorModal.open(entry);
});