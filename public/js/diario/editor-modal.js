/**
 * EditorModal - Lógica de edición avanzada y persistencia RESTful
 */
class EditorModal {
    constructor() {
        this.currentEntry = null;
        this.currentType = 'texto';
        this.isFullscreen = false;
        
        // Estado del Mapa Conceptual
        this.nodes = [];
        this.connections = []; 
        this.isConnecting = false;
        this.sourceNodeId = null;
        this.draggedNode = null;
        this.dragOffset = { x: 0, y: 0 };

        this.initializeEventListeners();
    }

    initializeEventListeners() {
        // Botones de control del modal
        document.getElementById('cancelEditorBtn')?.addEventListener('click', () => this.close());
        document.getElementById('saveDocumentBtn')?.addEventListener('click', () => this.save());
        
        // Tipo de documento y Pantalla Completa
        document.getElementById('docType')?.addEventListener('change', (e) => this.changeType(e.target.value));
        document.getElementById('fullscreenBtn')?.addEventListener('click', () => this.toggleFullscreen());

        // Formato de texto
        this.initializeFormatButtons();

        // Tareas
        document.getElementById('addTaskBtn')?.addEventListener('click', () => this.addTask());

        // Mapa Conceptual: Controles
        document.getElementById('addMindMapNodeBtn')?.addEventListener('click', () => this.addMindMapNode());
        document.getElementById('clearMindMapBtn')?.addEventListener('click', () => {
            this.showCustomConfirm('¿Estás seguro de que quieres limpiar todo el mapa conceptual?', () => this.clearMindMap());
        });

        // Eventos de Mouse Globales (Drag & Drop)
        document.addEventListener('mousemove', (e) => this.handleMouseMove(e));
        document.addEventListener('mouseup', () => this.handleMouseUp());

        // Manejo de Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !document.getElementById('documentEditor').classList.contains('hidden')) {
                if (this.isConnecting) this.cancelConnection();
                else this.close();
            }
        });
    }

    /**
     * Maximiza o restaura el tamaño de la modal
     */
    toggleFullscreen() {
        const container = document.getElementById('editorModalContainer');
        const icon = document.getElementById('fullscreenIcon');
        this.isFullscreen = !this.isFullscreen;
        
        if (this.isFullscreen) {
            container.classList.add('modal-fullscreen');
            if(icon) {
                icon.classList.remove('fa-expand');
                icon.classList.add('fa-compress');
            }
        } else {
            container.classList.remove('modal-fullscreen');
            if(icon) {
                icon.classList.remove('fa-compress');
                icon.classList.add('fa-expand');
            }
        }
        // Actualizar líneas del mapa
        setTimeout(() => this.updateConnections(), 300);
    }

    /** --- GESTIÓN DE TAREAS (SIN BUCLES VACÍOS) --- **/

    addTask(taskData = null) {
        const container = document.getElementById('tasksContainer');
        const template = document.getElementById('taskTemplate');
        if (!container || !template) return;

        // Validación: No crear tarea si la última ya está vacía
        if (!taskData) {
            const lastTask = container.querySelector('.task-item:last-child');
            if (lastTask) {
                const lastInput = lastTask.querySelector('.task-text');
                if (lastInput && lastInput.value.trim() === "") {
                    lastInput.focus();
                    return;
                }
            }
        }

        const clone = template.content.cloneNode(true);
        const item = clone.querySelector('.task-item');
        const input = item.querySelector('.task-text');
        const checkbox = item.querySelector('.task-checkbox');
        
        if (taskData) {
            input.value = taskData.text;
            checkbox.checked = taskData.completed;
        }

        // LIMPIEZA AUTOMÁTICA AL PERDER FOCO (BLUR)
        input.addEventListener('blur', () => {
            setTimeout(() => {
                if (input.value.trim() === "" && item.parentNode) {
                    item.remove();
                }
            }, 150);
        });

        input.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (input.value.trim() !== "") this.addTask();
            }
        });

        item.querySelector('.delete-task').addEventListener('click', () => item.remove());
        container.appendChild(item);
        if (!taskData) input.focus();
    }

    /** --- MAPA CONCEPTUAL (FUNCIONALIDAD DE UNIÓN) --- **/

    addMindMapNode(data = null) {
        const container = document.querySelector('.mind-map-container');
        const template = document.getElementById('mindMapNodeTemplate');
        if (!container || !template) return;

        const clone = template.content.cloneNode(true);
        const node = clone.querySelector('.mind-map-node');
        const id = data ? data.id : `node_${Date.now()}_${Math.floor(Math.random() * 1000)}`;
        
        node.dataset.id = id;
        node.style.left = data ? `${data.x}px` : '100px';
        node.style.top = data ? `${data.y}px` : '100px';

        const input = node.querySelector('.node-text');
        input.value = data ? data.text : '';

        node.addEventListener('mousedown', (e) => this.startDragging(e, node));
        
        // Botón Unir (🔗)
        node.querySelector('.connect-node')?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.handleConnectionClick(id);
        });

        // Botón Eliminar
        node.querySelector('.delete-node')?.addEventListener('click', (e) => {
            e.stopPropagation();
            this.deleteNode(id);
        });

        container.appendChild(node);
        if (!data) input.focus();
        this.updateConnections();
    }

    handleConnectionClick(id) {
        if (!this.isConnecting) {
            this.isConnecting = true;
            this.sourceNodeId = id;
            this.showNotification('Toca otro nodo para crear la unión', 'info');
            document.querySelector(`[data-id="${id}"]`)?.classList.add('ring-4', 'ring-blue-500');
        } else {
            if (this.sourceNodeId !== id) {
                // Evitar conexiones duplicadas
                const alreadyExists = this.connections.some(c => 
                    (c.from === this.sourceNodeId && c.to === id) || (c.from === id && c.to === this.sourceNodeId)
                );
                if (!alreadyExists) {
                    this.connections.push({ from: this.sourceNodeId, to: id });
                    this.updateConnections();
                }
            }
            this.cancelConnection();
        }
    }

    cancelConnection() {
        this.isConnecting = false;
        document.querySelectorAll('.mind-map-node').forEach(n => n.classList.remove('ring-4', 'ring-blue-500'));
        this.sourceNodeId = null;
    }

    deleteNode(id) {
        document.querySelector(`[data-id="${id}"]`)?.remove();
        this.connections = this.connections.filter(c => c.from !== id && c.to !== id);
        this.updateConnections();
    }

    updateConnections() {
        const svg = document.getElementById('connectionsLayer');
        if (!svg) return;
        svg.innerHTML = '';

        this.connections.forEach(conn => {
            const fromEl = document.querySelector(`[data-id="${conn.from}"]`);
            const toEl = document.querySelector(`[data-id="${conn.to}"]`);
            if (!fromEl || !toEl) return;

            const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
            line.setAttribute('x1', fromEl.offsetLeft + (fromEl.offsetWidth / 2));
            line.setAttribute('y1', fromEl.offsetTop + (fromEl.offsetHeight / 2));
            line.setAttribute('x2', toEl.offsetLeft + (toEl.offsetWidth / 2));
            line.setAttribute('y2', toEl.offsetTop + (toEl.offsetHeight / 2));
            line.setAttribute('stroke', '#3b82f6');
            line.setAttribute('stroke-width', '2');
            svg.appendChild(line);
        });
    }

    startDragging(e, node) {
        if (this.isConnecting) {
            e.stopPropagation();
            this.handleConnectionClick(node.dataset.id);
            return;
        }
        if (e.target.tagName === 'INPUT' || e.target.closest('button')) return;
        this.draggedNode = node;
        const rect = node.getBoundingClientRect();
        this.dragOffset.x = e.clientX - rect.left;
        this.dragOffset.y = e.clientY - rect.top;
        e.preventDefault();
    }

    handleMouseMove(e) {
        if (this.draggedNode) {
            const canvas = document.getElementById('mindMapCanvas');
            const rect = canvas.getBoundingClientRect();
            let x = e.clientX - rect.left - this.dragOffset.x;
            let y = e.clientY - rect.top - this.dragOffset.y;
            
            x = Math.max(0, Math.min(x, rect.width - this.draggedNode.offsetWidth));
            y = Math.max(0, Math.min(y, rect.height - this.draggedNode.offsetHeight));

            this.draggedNode.style.left = `${x}px`;
            this.draggedNode.style.top = `${y}px`;
            this.updateConnections();
        }
    }

    handleMouseUp() {
        this.draggedNode = null;
    }

    clearMindMap() {
        const container = document.querySelector('.mind-map-container');
        if (container) container.innerHTML = '';
        this.connections = [];
        this.updateConnections();
    }

    /** --- GUARDADO REAL (RUTAS LARAVEL) --- **/

    async save() {
        const titleInput = document.getElementById('docTitle');
        const title = titleInput ? titleInput.value : '';
        const type = document.getElementById('docType').value;
        const color = document.getElementById('docColor').value;
        const isFavorite = document.getElementById('docFavorite').checked;
        
        if (!title.trim()) {
            this.showNotification('Por favor, ingresa un título para tu entrada', 'error');
            titleInput?.focus();
            return;
        }

        let content = '';
        if (type === 'texto') {
            content = document.getElementById('textSection').innerHTML;
        } else if (type === 'lista') {
            const tasks = Array.from(document.querySelectorAll('#tasksContainer .task-item')).map(item => ({
                text: item.querySelector('.task-text').value,
                completed: item.querySelector('.task-checkbox').checked
            })).filter(t => t.text.trim() !== "");
            content = JSON.stringify(tasks);
        } else if (type === 'mapa_conceptual') {
            const nodes = Array.from(document.querySelectorAll('.mind-map-node')).map(n => ({
                id: n.dataset.id,
                text: n.querySelector('.node-text').value,
                x: parseInt(n.style.left),
                y: parseInt(n.style.top)
            }));
            content = JSON.stringify({ nodes, connections: this.connections });
        }

        // RUTAS BASADAS EN TU WEB.PHP
        const url = this.currentEntry ? `/diario/${this.currentEntry.id}` : '/diario';
        const method = this.currentEntry ? 'PUT' : 'POST';

        try {
            this.showNotification('Guardando cambios...', 'info');
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCsrfToken(),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, type, content, color, is_favorite: isFavorite })
            });

            const result = await response.json();

            if (result.success) {
                this.showNotification('¡Entrada guardada con éxito!', 'success');
                this.close();
                // RECARGAR PARA ACTUALIZAR INDEX.BLADE
                setTimeout(() => location.reload(), 600);
            } else {
                this.showNotification(result.message || 'Error al guardar', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            this.showNotification('Error de conexión al servidor', 'error');
        }
    }

    /** --- CICLO DE VIDA DEL MODAL --- **/

    open(entry = null) {
        this.currentEntry = entry;
        const modal = document.getElementById('documentEditor');
        if (!modal) return;

        this.isFullscreen = false;
        document.getElementById('editorModalContainer')?.classList.remove('modal-fullscreen');
        
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';

        if (entry) {
            document.getElementById('docTitle').value = entry.title || '';
            document.getElementById('docType').value = entry.type || 'texto';
            document.getElementById('docColor').value = entry.color || '#3b82f6';
            document.getElementById('docFavorite').checked = !!entry.is_favorite;
            this.changeType(entry.type || 'texto');
            this.loadContent(entry);
        } else {
            this.resetForm();
        }
    }

    resetForm() {
        document.getElementById('docTitle').value = '';
        document.getElementById('docType').value = 'texto';
        document.getElementById('docColor').value = '#3b82f6';
        document.getElementById('docFavorite').checked = false;
        this.changeType('texto');
        const textSec = document.getElementById('textSection');
        if(textSec) textSec.innerHTML = '<p>Escribe tu reflexión aquí...</p>';
        document.getElementById('tasksContainer').innerHTML = '';
        this.clearMindMap();
    }

    close() {
        document.getElementById('documentEditor').classList.add('hidden');
        document.body.style.overflow = 'auto';
        this.currentEntry = null;
    }

    changeType(type) {
        this.currentType = type;
        const panels = ['textSection', 'listSection', 'mapSection', 'textToolbar'];
        panels.forEach(p => document.getElementById(p)?.classList.add('hidden'));

        if (type === 'texto') {
            document.getElementById('textSection')?.classList.remove('hidden');
            document.getElementById('textToolbar')?.classList.remove('hidden');
        } else if (type === 'lista') {
            document.getElementById('listSection')?.classList.remove('hidden');
        } else if (type === 'mapa_conceptual') {
            document.getElementById('mapSection')?.classList.remove('hidden');
            setTimeout(() => this.updateConnections(), 100);
        }
    }

    loadContent(entry) {
        if (entry.type === 'texto') {
            document.getElementById('textSection').innerHTML = entry.content || '';
        } else if (entry.type === 'lista') {
            document.getElementById('tasksContainer').innerHTML = '';
            try {
                const tasks = JSON.parse(entry.content);
                tasks.forEach(t => this.addTask(t));
            } catch(e) {}
        } else if (entry.type === 'mapa_conceptual') {
            this.clearMindMap();
            try {
                const data = JSON.parse(entry.content);
                data.nodes.forEach(n => this.addMindMapNode(n));
                this.connections = data.connections || [];
                setTimeout(() => this.updateConnections(), 200);
            } catch(e) {}
        }
    }

    initializeFormatButtons() {
        document.querySelectorAll('.format-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const command = btn.dataset.command;
                if (command) {
                    document.execCommand(command, false, null);
                    document.getElementById('textSection')?.focus();
                }
            });
        });
        document.getElementById('fontFamily')?.addEventListener('change', (e) => document.execCommand('fontName', false, e.target.value));
        document.getElementById('fontSize')?.addEventListener('change', (e) => document.execCommand('fontSize', false, e.target.value));
    }

    getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    }

    showNotification(message, type = 'info') {
        const n = document.createElement('div');
        const bg = type === 'success' ? 'bg-green-600' : (type === 'error' ? 'bg-red-600' : 'bg-blue-600');
        n.className = `fixed top-4 right-4 p-4 rounded-lg shadow-2xl text-white z-[100] transition-all duration-300 transform translate-x-full ${bg}`;
        n.textContent = message;
        document.body.appendChild(n);
        setTimeout(() => n.classList.remove('translate-x-full'), 100);
        setTimeout(() => {
            n.classList.add('translate-x-full');
            setTimeout(() => n.remove(), 300);
        }, 3500);
    }

    showCustomConfirm(message, onConfirm) {
        const overlay = document.createElement('div');
        overlay.className = 'fixed inset-0 bg-black bg-opacity-50 z-[200] flex items-center justify-center p-4 backdrop-blur-sm';
        overlay.innerHTML = `
            <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full">
                <div class="text-red-500 mb-4 flex justify-center"><i class="fas fa-exclamation-triangle text-4xl"></i></div>
                <h3 class="text-xl font-bold text-gray-800 text-center mb-2">Confirmar Acción</h3>
                <p class="text-gray-600 text-center mb-8">${message}</p>
                <div class="flex gap-4">
                    <button id="confirmCancel" class="flex-1 px-4 py-2 border rounded-xl hover:bg-gray-50">Cancelar</button>
                    <button id="confirmOk" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 font-bold shadow-lg transition-all">Sí, Eliminar</button>
                </div>
            </div>
        `;
        document.body.appendChild(overlay);
        overlay.querySelector('#confirmCancel').onclick = () => overlay.remove();
        overlay.querySelector('#confirmOk').onclick = () => { onConfirm(); overlay.remove(); };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.editorModal = new EditorModal();
});