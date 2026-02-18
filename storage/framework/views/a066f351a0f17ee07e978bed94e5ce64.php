<div id="documentEditor" class="fixed inset-0 bg-black bg-opacity-60 flex items-end sm:items-center justify-center z-50 hidden p-0 sm:p-4 backdrop-blur-sm">
    <!-- Se aumentó el tamaño a max-w-[96vw] y h-[95vh] para maximizar el espacio de trabajo -->
    <div id="editorModalContainer" class="bg-white w-full h-[95vh] sm:h-[92vh] sm:rounded-xl sm:max-w-[96vw] overflow-hidden flex flex-col shadow-2xl rounded-t-xl transition-all duration-300">
        
        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex flex-col md:flex-row items-center justify-between shrink-0 gap-3 md:gap-0">
            <div class="flex flex-wrap items-center justify-between md:justify-start w-full md:w-auto gap-3">
                <div class="flex-1 md:flex-none">
                    <select id="docType" class="w-full md:w-auto border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-button focus:border-button bg-white shadow-sm">
                        <option value="texto">Reflexión</option>
                        <option value="mapa_conceptual">Mapa Conceptual</option>
                        <option value="lista">Lista de Tareas</option> 
                    </select>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="flex items-center gap-2 bg-white border border-gray-300 rounded-lg px-2 py-1 shadow-sm">
                        <label class="text-xs text-gray-500 font-medium uppercase">Color</label>
                        <input type="color" id="docColor" value="#3b82f6" class="w-6 h-6 border-none p-0 rounded cursor-pointer bg-transparent">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="docFavorite" class="hidden peer">
                        <label for="docFavorite" class="flex items-center gap-1 text-gray-400 peer-checked:text-yellow-500 cursor-pointer hover:bg-gray-100 px-2 py-1.5 rounded-lg transition-colors">
                            <img src="../img/icono_favoritos.png" class="w-6 h-6" alt="Favorito">
                            <span class="text-sm ml-1 mt-1 font-medium text-gray-600 peer-checked:text-yellow-600">Favorito</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-2 w-full md:w-auto">
                <!-- BOTÓN PANTALLA COMPLETA - ID: fullscreenBtn -->
                <button id="fullscreenBtn" type="button" class="p-2.5 bg-gray-100 text-gray-600 hover:text-button hover:bg-blue-50 rounded-lg transition-all mr-2 flex items-center justify-center border border-gray-200" title="Pantalla Completa">
                    <svg id="fullscreenIcon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                </button>

                <button id="cancelEditorBtn" type="button"
                        class="flex-1 md:flex-none justify-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors flex items-center gap-2 text-sm font-medium shadow-sm">
                    <span>✕</span> 
                    <span>Cancelar</span>
                </button>
                <button id="saveDocumentBtn" type="button"
                        class="flex-1 md:flex-none justify-center bg-button text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-600 transition-colors flex items-center gap-2 text-sm shadow-md">
                    <span>Guardar</span>
                </button>
            </div>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden bg-white relative">
            
            <div class="border-b border-gray-100 px-4 md:px-8 py-3 md:py-4 shrink-0">
                <div class="flex items-center gap-2">
                    <input type="text" id="docTitle" placeholder="Título del documento..." 
                           class="w-full text-xl md:text-3xl font-bold border-none outline-none placeholder-gray-300 text-gray-800 bg-transparent">
                </div>
            </div>

            <div id="textToolbar" class="bg-white border-b border-gray-200 px-3 py-2 md:px-4 flex flex-wrap md:flex-nowrap items-center gap-y-3 gap-x-2 hidden shrink-0 transition-all">
                <div class="flex items-center gap-2 w-full md:w-auto justify-between md:justify-start border-b md:border-b-0 border-gray-100 pb-2 md:pb-0">
                    <select id="fontFamily" class="flex-1 md:flex-none border border-gray-300 rounded-md px-2 py-1.5 text-sm bg-gray-50 focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="Arial">Arial</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Inter">Inter</option>
                        <option value="Montserrat">Montserrat</option>
                    </select>
                    <select id="fontSize" class="w-24 md:w-auto border border-gray-300 rounded-md px-2 py-1.5 text-sm bg-gray-50 focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="1">Pequeño</option>
                        <option value="3" selected>Normal</option>
                        <option value="5">Grande</option>
                        <option value="7">Enorme</option>
                    </select>
                </div>
                
                <div class="flex items-center gap-1">
                    <button data-command="bold" type="button" class="format-btn p-2 rounded hover:bg-gray-200 font-bold" title="Negrita">B</button>
                    <button data-command="italic" type="button" class="format-btn p-2 rounded hover:bg-gray-200 italic" title="Cursiva">I</button>
                    <button data-command="underline" type="button" class="format-btn p-2 rounded hover:bg-gray-200 underline" title="Subrayado">U</button>
                    <button data-command="insertUnorderedList" type="button" class="format-btn p-2 rounded hover:bg-gray-200" title="Lista">•</button>
                </div>
            </div>

            <div class="flex-1 overflow-auto bg-gray-50 relative">
                
                <div id="textSection" class="editor-panel bg-white min-h-full p-6 md:p-10 max-w-5xl mx-auto shadow-sm outline-none text-lg leading-relaxed" 
                     contenteditable="true">
                    <p>Comienza a escribir aquí...</p>
                </div>

                <div id="mapSection" class="editor-panel hidden bg-white min-h-full p-4 flex flex-col">
                    <div class="flex flex-col h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 z-20 relative shrink-0">
                            <h3 class="text-lg font-semibold text-gray-800">Mapa Conceptual</h3>
                            <div class="flex space-x-2 w-full sm:w-auto">
                                <button id="addMindMapNodeBtn" type="button" class="bg-button text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm hover:bg-blue-600 transition-colors">+ Nodo</button>
                                <button id="clearMindMapBtn" type="button" class="bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm shadow-sm">Limpiar</button>
                            </div>
                        </div>
                        
                        <div id="mindMapCanvas" class="flex-1 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 relative min-h-[500px] overflow-hidden touch-none">
                            <svg id="connectionsLayer" class="absolute inset-0 w-full h-full pointer-events-none z-0"></svg>
                            <div class="mind-map-container absolute inset-0 z-10 w-full h-full"></div>
                        </div>
                    </div>
                </div>

                <div id="listSection" class="editor-panel hidden bg-white min-h-full p-4 md:p-8">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <h3 class="text-lg font-semibold text-gray-800">Lista de Tareas</h3>
                            <button id="addTaskBtn" type="button" class="w-full sm:w-auto bg-button hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg flex items-center justify-center space-x-2 font-medium shadow-sm transition-colors">
                                <span>+ Nueva Tarea</span>
                            </button>
                        </div>
                        <div class="space-y-3" id="tasksContainer"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<template id="taskTemplate">
    <div class="task-item bg-white border border-gray-200 rounded-lg p-4 flex items-center space-x-3 hover:shadow-md transition-shadow group">
        <input type="checkbox" class="task-checkbox w-5 h-5 text-blue-600 rounded border-gray-300 focus:ring-blue-500 cursor-pointer">
        <input type="text" class="task-text flex-1 border-none outline-none text-gray-700 placeholder-gray-400 bg-transparent text-base" placeholder="Describe tu tarea...">
        <button type="button" class="delete-task text-gray-400 hover:text-red-500 p-2 rounded-full hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</template>

<template id="mindMapNodeTemplate">
    <div class="mind-map-node absolute bg-white border-2 border-blue-500 rounded-xl p-3 shadow-lg cursor-move min-w-[160px] max-w-[200px] group hover:shadow-xl transition-shadow" style="touch-action: none;">
        <input type="text" class="node-text w-full border-b border-transparent focus:border-blue-300 outline-none text-center font-bold text-gray-800 bg-transparent px-1 py-1" placeholder="Concepto...">
        <div class="flex justify-center gap-3 mt-2 pt-2 border-t border-gray-100">
            <!-- Restaurado el botón de conectar -->
            <button type="button" class="connect-node text-gray-400 hover:text-blue-500 p-1.5 rounded hover:bg-blue-50 transition-colors" title="Conectar">🔗</button>
            <button type="button" class="delete-node text-gray-400 hover:text-red-500 p-1.5 rounded hover:bg-red-50 transition-colors" title="Eliminar">🗑</button>
        </div>
    </div>
</template>

<style>
    /* Estilos Críticos para Pantalla Completa */
    .modal-fullscreen {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        width: 100vw !important;
        height: 100vh !important;
        max-width: none !important;
        max-height: none !important;
        margin: 0 !important;
        border-radius: 0 !important;
        z-index: 9999 !important;
    }
    
    .modal-fullscreen .flex-1.overflow-auto {
        height: calc(100vh - 160px) !important;
    }

    #textSection ul { list-style-type: disc !important; padding-left: 2rem !important; margin: 1rem 0; }
    #textSection ol { list-style-type: decimal !important; padding-left: 2rem !important; margin: 1rem 0; }
</style><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/diario/partials/editor-modal.blade.php ENDPATH**/ ?>