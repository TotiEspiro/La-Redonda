@extends('layouts.app')

@section('content')
<div class="intentions-container py-12">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Título Principal -->
        <h1 class="text-4xl font-semibold text-text-dark text-center mb-12 border-b-2 border-black pb-2">Intenciones</h1>
        
        <div class="intentions-content grid grid-cols-1 lg:grid-cols-2 gap-12 max-w-6xl mx-auto">
            <!-- Columna Izquierda - Texto -->
            <div class="intentions-left">
                <div class="space-y-6">
                    <p class="text-text-dark leading-relaxed">
                        Acá podes anotar tus intenciones para la Misa de 19:30hs (de Lunes a Sábado) o 19hs (los domingos). No se mencionan en particular, sino que se pide por todas las que se anotaron durante el dia en la Web
                    </p>
                    
                    <p class="text-text-dark leading-relaxed">
                       En la Secretaria parroquial se anotan las intenciones para las Misas, eligiendo el día y el horario, las cuales son mencionadas en las celebraciones respectivas.
                    </p>
                    
                    <p class="text-text-dark leading-relaxed">
                       En la Secretaria parroquial se anotan las intenciones para las Misas, eligiendo el día y el horario, las cuales son mencionadas en las celebraciones respectivas.
                    </p>
                    
                    <p class="text-text-dark leading-relaxed">
                        Podés ingresae desde tu email y se permite anotar hasta un maximo de 4 intenciones.
                    </p>
                </div>
            </div>

            <!-- Columna Derecha - Formulario -->
            <div class="intentions-right">
                <div class="form-container bg-background-light p-8 rounded-xl shadow-lg">                  
                    <form id="intentionForm" class="intention-form space-y-6">
                        <!-- Selector de Tipo de Intención -->
                        <div class="form-group">
                            <label for="intentionType" class="block font-semibold text-text-dark mb-3">Tipo de Intención</label>
                            <select id="intentionType" name="intentionType" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 bg-white">
                                <option value="" disabled selected>Seleccione el tipo de intención</option>
                                <option value="salud">Salud</option>
                                <option value="intenciones">Intenciones</option>
                                <option value="accion-gracias">Acción de gracias</option>
                                <option value="difuntos">Difuntos</option>
                            </select>
                        </div>

                        <!-- Campo para el Nombre -->
                        <div class="form-group">
                            <label for="name" class="block font-semibold text-text-dark mb-3">Nombre</label>
                            <input type="text" id="name" name="name" required placeholder="Ingrese su nombre completo" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                        </div>

                        <!-- Campo para el Email -->
                        <div class="form-group">
                            <label for="email" class="block font-semibold text-text-dark mb-3">Email</label>
                            <input type="email" id="email" name="email" required placeholder="Ingrese su email" class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                        </div>

                        <button type="submit" class="w-full bg-button text-white py-4 px-8 border-none rounded-lg cursor-pointer font-poppins font-semibold text-lg hover:bg-blue-500 hover:translate-y-[-2px] transition-all">
                            Enviar Intención
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmación para Intenciones -->
<div id="confirmationModalIntention" class="modal hidden fixed inset-0 bg-black bg-opacity-70 z-50 items-center justify-center p-4">
    <div class="modal-content confirmation-modal bg-white p-8 rounded-xl max-w-md w-full max-h-[85vh] overflow-y-auto shadow-2xl">
        <span class="modal-close absolute top-4 right-6 cursor-pointer text-2xl text-text-light hover:text-button transition-colors">&times;</span>
        <h2 class="text-2xl font-semibold text-text-dark mb-6 text-center">Confirmar Intención</h2>
        
        <!-- Contenido del modal que el JavaScript actualiza -->
        <div class="confirmation-content space-y-4 mb-6">
            <div class="confirmation-item">
                <strong>Tipo de Intención:</strong> 
                <span id="confirmIntentionType" class="ml-2"></span>
            </div>
            <div class="confirmation-item">
                <strong>Nombre:</strong> 
                <span id="confirmName" class="ml-2"></span>
            </div>
            <div class="confirmation-item">
                <strong>Email:</strong> 
                <span id="confirmEmailIntention" class="ml-2"></span>
            </div>
        </div>
        
        <div class="modal-actions flex gap-4 justify-end">
            <button type="button" class="cancel-btn bg-gray-100 text-text-dark py-3 px-6 border-2 border-gray-300 rounded-lg cursor-pointer font-semibold hover:bg-gray-200 transition-all" id="cancelIntention">Cancelar</button>
            <button type="button" class="confirm-btn bg-button text-white py-3 px-6 border-none rounded-lg cursor-pointer font-semibold hover:bg-blue-500 transition-all" id="confirmIntention">Confirmar Intención</button>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">


<script src="js/intenciones.js"></script>


@endsection