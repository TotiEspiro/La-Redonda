<div id="documentEditor" class="fixed inset-0 bg-black bg-opacity-60 flex items-end sm:items-center justify-center z-50 hidden p-0 sm:p-4 backdrop-blur-sm">
    <div class="bg-white w-full h-[95vh] sm:h-auto sm:max-h-[90vh] sm:rounded-xl sm:max-w-6xl overflow-hidden flex flex-col shadow-2xl rounded-t-xl">
        
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
                        <option value="Georgia">Georgia</option>
                        <option value="Verdana">Verdana</option>
                    </select>
                    <select id="fontSize" class="w-24 md:w-auto border border-gray-300 rounded-md px-2 py-1.5 text-sm bg-gray-50 focus:ring-1 focus:ring-blue-500 outline-none">
                        <option value="1">Pequeño</option>
                        <option value="3" selected>Normal</option>
                        <option value="5">Grande</option>
                        <option value="7">Enorme</option>
                    </select>
                </div>
            
                <div class="hidden md:block w-px h-6 bg-gray-300 flex-shrink-0"></div>
            
                <div class="flex flex-1 md:flex-none items-center justify-between md:justify-start gap-2 md:gap-3 w-full md:w-auto overflow-x-auto md:overflow-visible no-scrollbar">
                    
                    <div class="flex items-center gap-1 flex-shrink-0 bg-gray-50 md:bg-transparent p-1 md:p-0 rounded-lg">
                        <button data-command="bold" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-700 font-bold min-w-[32px] text-center" title="Negrita">B</button>
                        <button data-command="italic" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-700 italic min-w-[32px] text-center" title="Itálica">I</button>
                        <button data-command="underline" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-700 underline min-w-[32px] text-center" title="Subrayado">U</button>
                        <button data-command="strikethrough" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-700 line-through min-w-[32px] text-center" title="Tachado">S</button>
                    </div>
            
                    <div class="hidden md:block w-px h-6 bg-gray-300 flex-shrink-0"></div>
            
                    <div class="flex items-center gap-1 flex-shrink-0 bg-gray-50 md:bg-transparent p-1 md:p-0 rounded-lg">
                        <button data-command="justifyLeft" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-600 min-w-[32px]" title="Izquierda">⫷</button>
                        <button data-command="justifyCenter" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-600 min-w-[32px]" title="Centro">⫸</button>
                        <button data-command="justifyRight" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-600 min-w-[32px]" title="Derecha">⫹</button>
                    </div>
            
                    <div class="hidden md:block w-px h-6 bg-gray-300 flex-shrink-0"></div>
            
                    <div class="flex items-center gap-1 flex-shrink-0 bg-gray-50 md:bg-transparent p-1 md:p-0 rounded-lg">
                        <button data-command="insertUnorderedList" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-600 min-w-[32px]" title="Viñetas">•</button>
                        <button data-command="insertOrderedList" type="button" class="format-btn p-2 md:p-1.5 rounded hover:bg-gray-200 text-gray-600 min-w-[32px]" title="Numeración">1.</button>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-auto bg-gray-50 relative">
                
                <div id="textEditor" class="editor-panel bg-white min-h-full p-6 md:p-10 max-w-4xl mx-auto shadow-sm outline-none text-lg leading-relaxed" 
                     contenteditable="true">
                    <p>Comienza a escribir aquí...</p>
                </div>

                <div id="mindMapEditor" class="editor-panel hidden bg-white min-h-full p-4 flex flex-col">
                    <div class="flex flex-col h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3 z-20 relative shrink-0">
                            <h3 class="text-lg font-semibold flex items-center space-x-2 text-gray-800">
                                <span class="text-2xl"></span>
                                <span>Mapa Conceptual</span>
                            </h3>
                            <div class="flex space-x-2 w-full sm:w-auto">
                                <button id="addMindMapNodeBtn" type="button" class="flex-1 sm:flex-none justify-center bg-button text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 shadow-sm hover:bg-blue-600 transition-colors">
                                    <span class="text-lg font-bold">+</span> 
                                    <span>Nodo</span>
                                </button>
                                <button id="clearMindMapBtn" type="button" class="flex-1 sm:flex-none justify-center bg-white border border-gray-300 text-gray-700 px-4 py-2 rounded-lg text-sm font-medium flex items-center space-x-2 shadow-sm hover:bg-gray-50 transition-colors">
                                    <span>Limpiar</span>
                                </button>
                            </div>
                        </div>
                        
                        <div id="mindMapCanvas" class="flex-1 border-2 border-dashed border-gray-300 rounded-xl bg-gray-50 relative min-h-[400px] overflow-hidden touch-none">
                            <svg id="connectionsLayer" class="absolute inset-0 w-full h-full pointer-events-none z-0"></svg>
                                <div class="mind-map-container absolute inset-0 z-10">
                                    <div id="emptyMapMsg" class="absolute inset-0 flex items-center justify-center text-gray-400 pointer-events-none p-4 text-center">
                                        <div>
                                            <p class="text-lg font-medium">Comienza tu mapa</p>
                                            <p class="text-sm">Toca "Nodo" para agregar conceptos</p>
                                </div>
                        </div>
                </div>
            </div>
                <div id="listEditor" class="editor-panel hidden bg-white min-h-full p-4 md:p-8">
                    <div class="max-w-3xl mx-auto">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                            <h3 class="text-lg font-semibold flex items-center space-x-2 text-gray-800">
                                <span class="text-2xl"></span>
                                <span>Lista de Tareas</span>
                            </h3>
                            <button id="addTaskBtn" type="button" class="w-full sm:w-auto bg-button hover:bg-blue-600 text-white px-5 py-2.5 rounded-lg flex items-center justify-center space-x-2 font-medium shadow-sm transition-colors">
                                <span class="text-xl leading-none">+</span> 
                                <span>Nueva Tarea</span>
                            </button>
                        </div>
                        
                        <div class="space-y-3" id="tasksContainer">
                            <div class="text-center text-gray-400 py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                                <p class="text-3xl mb-2 opacity-30"></p>
                                <p>No hay tareas aún.</p>
                            </div>
                        </div>
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
    <div class="mind-map-node absolute bg-white border-2 border-blue-500 rounded-xl p-3 shadow-lg cursor-move min-w-[160px] max-w-[200px] group hover:shadow-xl transition-shadow" style="top: 100px; left: 100px; touch-action: none;">
        <input type="text" class="node-text w-full border-b border-transparent focus:border-blue-300 outline-none text-center font-bold text-gray-800 bg-transparent px-1 py-1" placeholder="Concepto...">
        
        <div class="flex justify-center gap-3 mt-2 pt-2 border-t border-gray-100">
            <button type="button" class="connect-node text-gray-400 hover:text-blue-500 p-1.5 rounded hover:bg-blue-50 transition-colors" title="Conectar">
                <span class="text-lg">🔗</span>
            </button>
            <button type="button" class="delete-node text-gray-400 hover:text-red-500 p-1.5 rounded hover:bg-red-50 transition-colors" title="Eliminar">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </div>
    </div>
</template>

<style>
#textEditor ul {
    list-style-type: disc !important;
    padding-left: 2rem !important;
    margin: 1rem 0;
}

#textEditor ol {
    list-style-type: decimal !important;
    padding-left: 2rem !important;
    margin: 1rem 0;
}

#textEditor li {
    margin-bottom: 0.25rem;
}
</style>