<!-- Modal Editor de Documentos -->
<div id="documentEditor" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl mx-4 max-h-[95vh] overflow-hidden flex flex-col">
        <!-- Toolbar -->
        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <!-- Tipo de Documento -->
                <div class="flex items-center space-x-2">
                    <select id="docType" class="border border-gray-300 rounded px-3 py-1 text-sm focus:ring-blue-600 focus:border-blue-600">
                        <option value="texto">Reflexión</option>
                        <option value="mapa_conceptual">Mapa Conceptual</option>
                        <option value="lista">Lista de Tareas</option> 
                    </select>
                </div>
                
                <!-- Color -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm text-gray-600">Color:</label>
                    <input type="color" id="docColor" value="#3b82f6" class="w-8 h-8 border border-gray-300 rounded cursor-pointer">
                </div>

                <!-- Favorito -->
                <div class="flex items-center space-x-2">
                    <input type="checkbox" id="docFavorite" class="w-4 h-4 text-blue-600 rounded">
                    <label for="docFavorite" class="text-sm text-gray-600 flex items-center space-x-1">
                        <img src="../img/icono_favoritos.png" class="w-5 h-5" alt="Favorito"> 
                        <span>Favorito</span>
                    </label>
                </div>
            </div>
            
            <div class="flex items-center space-x-2">
                <button id="cancelEditorBtn" 
                        class="bg-grey px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors flex items-center space-x-2">
                    <span>✕</span> 
                    <span>Cancelar</span>
                </button>
                <button id="saveDocumentBtn" 
                        class="bg-button text-white px-6 py-2 rounded font-semibold hover:bg-blue-500 transition-colors flex items-center space-x-2">
                    <span>Guardar</span>
                </button>
            </div>
        </div>

        <!-- Editor Principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Título del Documento -->
            <div class="border-b border-gray-200 px-6 py-4">
                <div class="flex items-center space-x-2">
                    <input type="text" id="docTitle" placeholder="Título del documento..." 
                           class="w-full text-2xl font-bold border-none outline-none placeholder-gray-400">
                </div>
            </div>

            <!-- Toolbar de Formato (solo para texto) -->
            <div id="textToolbar" class="bg-white border-b border-gray-200 px-6 py-3 flex items-center space-x-4 flex-wrap hidden">
                <!-- Fuente -->
                <div class="flex items-center space-x-2">
                    <select id="fontFamily" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="Arial">Arial</option>
                        <option value="Times New Roman">Times New Roman</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Verdana">Verdana</option>
                    </select>
                </div>
                
                <!-- Tamaño -->
                <div class="flex items-center space-x-2">
                    <select id="fontSize" class="border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="1">8pt</option>
                        <option value="2">10pt</option>
                        <option value="3">12pt</option>
                        <option value="4">14pt</option>
                        <option value="5">18pt</option>
                        <option value="6">24pt</option>
                        <option value="7">36pt</option>
                    </select>
                </div>

                <!-- Botones de Formato -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button data-command="bold" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Negrita">
                        <span>𝐁</span>
                    </button>
                    <button data-command="italic" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Itálica">
                        <span>𝐼</span> 
                    </button>
                    <button data-command="underline" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Subrayado">
                        <span>𝐔</span>
                    </button>
                    <button data-command="strikethrough" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Tachado">
                        <span>𝐒</span> 
                    </button>
                </div>

                <!-- Alineación -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button data-command="justifyLeft" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Alinear izquierda">
                        <span>⫷</span>
                    </button>
                    <button data-command="justifyCenter" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Centrar">
                        <span>⫸</span> 
                    </button>
                    <button data-command="justifyRight" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Alinear derecha">
                        <span>⫹</span>
                    </button>
                    <button data-command="justifyFull" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Justificar">
                        <span>⫺</span>
                    </button>
                </div>

                <!-- Listas -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button data-command="insertUnorderedList" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Lista con viñetas">
                        <span>•</span>
                    </button>
                    <button data-command="insertOrderedList" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Lista numerada">
                        <span>1.</span>
                    </button>
                </div>

                <!-- Sangría -->
                <div class="flex items-center space-x-1 border-l border-gray-300 pl-4">
                    <button data-command="outdent" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Disminuir sangría">
    
                        <span>←</span> 
                    </button>
                    <button data-command="indent" class="format-btn p-2 rounded hover:bg-gray-100 flex items-center" title="Aumentar sangría">
                        <span>→</span>
                    </button>
                </div>
            </div>

            <!-- Área de Edición Dinámica -->
            <div class="flex-1 overflow-auto bg-gray-50">
                <!-- Editor de Texto -->
                <div id="textEditor" class="editor-panel bg-white min-h-full p-8 max-w-4xl mx-auto shadow-inner" 
                     style="min-height: 400px;" contenteditable="true">
                    <p>Comienza a escribir aquí...</p>
                </div>

                <!-- Editor de Mapa Conceptual -->
                <div id="mindMapEditor" class="editor-panel hidden bg-white min-h-full p-4">
                    <div class="flex flex-col h-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold flex items-center space-x-2">
                                <img src="../img/icono_mapa.png" class="w-6 h-6" alt="Mapa"> 
                                <span>Editor de Mapa Conceptual</span>
                            </h3>
                            <div class="flex space-x-2">
                                <button id="addMindMapNodeBtn" class="bg-button text-white px-3 py-1 rounded text-sm flex items-center space-x-2">
                                    <span>+</span> 
                                    <span>Agregar Nodo</span>
                                </button>
                                <button id="clearMindMapBtn" class="bg-gray-600 text-white px-3 py-1 rounded text-sm flex items-center space-x-2">
                                    <span>Limpiar Todo</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                            <p class="text-sm text-yellow-800 flex items-center space-x-2">
                                <span><strong>Instrucciones:</strong> Haz clic en "Agregar Nodo" para crear conceptos. Arrastra los nodos para moverlos.</span>
                            </p>
                        </div>
                        
                        <div id="mindMapCanvas" class="flex-1 border-2 border-dashed border-gray-300 rounded-lg bg-gray-50 relative min-h-96 overflow-auto">
                            <div class="mind-map-container relative w-full h-full min-h-96">
                                <div class="absolute inset-0 flex items-center justify-center text-gray-500">
                                    <div class="text-center">
                                       <img src="../img/icono_mapa.png" class="w-12 h-12 mx-auto mb-2" alt="Mapa vacío"> -->
                                        <p class="text-lg mb-2">Mapa Conceptual</p>
                                        <p class="text-sm">Haz clic en "Agregar Nodo" para comenzar</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Controles del mapa -->
                        <div class="mt-4 flex space-x-2 justify-end">
                            <button id="centerMapBtn" class="bg-gray-500 text-white px-3 py-1 rounded text-sm flex items-center space-x-2">
                                <span>Centrar Mapa</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Sistema de Listas -->
                <div id="listEditor" class="editor-panel hidden bg-white min-h-full p-4">
                    <div class="max-w-4xl mx-auto">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold flex items-center space-x-2">
                                <img src="../img/icono_activo.png" class="w-6 h-6" alt="Lista">
                                <span>Lista de Tareas</span>
                            </h3>
                            <button id="addTaskBtn" class="bg-button hover:bg-blue-500 text-white px-4 py-2 rounded-lg flex items-center space-x-2">
                                <span>+</span> 
                                <span>Nueva Tarea</span>
                            </button>
                        </div>
                        
                        <div class="space-y-3" id="tasksContainer">
                            <div class="text-center text-gray-500 py-8">
                                <p>No hay tareas aún. ¡Agrega tu primera tarea!</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Template para tareas -->
<template id="taskTemplate">
    <div class="task-item bg-white border border-gray-200 rounded-lg p-4 flex items-center space-x-3 hover:shadow-md transition-shadow">
        <!-- ICONO CHECKBOX TAREA -->
        <input type="checkbox" class="task-checkbox w-4 h-4 text-blue-600 rounded">
        <input type="text" class="task-text flex-1 border-none outline-none" placeholder="Describe tu tarea...">
        <button class="delete-task text-red-500 hover:text-red-700 p-1 flex items-center">
            <span>✕</span>
        </button>
    </div>
</template>

<!-- Template para nodos de mapa conceptual -->
<template id="mindMapNodeTemplate">
    <div class="mind-map-node absolute bg-white border-2 border-blue-500 rounded-lg p-3 shadow-lg cursor-move min-w-32" style="top: 100px; left: 100px;">
        <input type="text" class="node-text w-full border-none outline-none text-center font-semibold" placeholder="Escribe aquí...">
        <div class="flex justify-center space-x-2 mt-2">
            <button class="add-child-node text-blue-500 text-xs p-1 flex items-center">       
                <span>+</span> 
            </button>
            <button class="delete-node text-red-500 text-xs p-1 flex items-center">
                <span>X</span> 
            </button>
        </div>
    </div>
</template>