@extends('layouts.app')

@section('content')
<div class="intentions-container py-8 md:py-12">
    <div class="container max-w-7xl mx-auto px-4">
        <h1 class="donation-title text-3xl md:text-4xl font-semibold text-text-dark text-center mb-8 md:mb-12 border-b-2 border-black pb-2">Intenciones</h1>
        <div class="intentions-content grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 max-w-6xl mx-auto items-start">
            
            <div class="intentions-left order-1 lg:order-1">
                <div class="space-y-6 text-base md:text-lg text-left md:text-left bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:bg-transparent lg:shadow-none lg:border-none lg:p-0">
                    <div class="flex items-start gap-3">
                        <span class="text-button text-xl mt-1">•</span>
                        <p class="text-text-dark leading-relaxed">
                            Acá podés anotar tus intenciones para la Misa de 19:30hs (de Lunes a Sábado) o 19hs (los domingos). No se mencionan en particular, sino que se pide por todas las que se anotaron durante el día en la Web.
                        </p>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <span class="text-button text-xl mt-1">•</span>
                        <p class="text-text-dark leading-relaxed">
                           En la Secretaria parroquial se anotan las intenciones para las Misas, eligiendo el día y el horario, las cuales son mencionadas en las celebraciones respectivas.
                        </p>
                    </div>
                    
                    <div class="flex items-start gap-3">
                        <span class="text-button text-xl mt-1">•</span>
                        <p class="text-text-dark leading-relaxed">
                            Podés ingresar desde tu email y se permite anotar hasta un máximo de 4 intenciones.
                        </p>
                    </div>
                </div>
            </div>

            <div class="intentions-right order-2 lg:order-2">
                <div class="form-container bg-background-light p-6 md:p-8 rounded-xl shadow-lg border border-gray-200">                  
                    <h3 class="text-xl font-semibold text-text-dark mb-6 md:hidden text-center">Nueva Intención</h3>
                    
                    <form id="intentionForm" class="intention-form space-y-5 md:space-y-6">
                        <div class="form-group">
                            <label for="intentionType" class="block font-semibold text-text-dark mb-2 md:mb-3 text-sm md:text-base">Tipo de Intención</label>
                            
                          
                            <div class="relative">
                                <select id="intentionType" name="intentionType" required 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg font-poppins text-base text-text-dark bg-white focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 appearance-none cursor-pointer transition-colors hover:border-button">
                                    <option value="" disabled selected>Seleccioná una opción...</option>
                                    <option value="salud">Salud</option>
                                    <option value="intenciones">Intenciones Particulares</option>
                                    <option value="accion-gracias">Acción de Gracias</option>
                                    <option value="difuntos">Difuntos</option>
                                </select>
                                
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-button">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name" class="block font-semibold text-text-dark mb-2 md:mb-3 text-sm md:text-base">Nombre</label>
                            <input type="text" id="name" name="name" required placeholder="Ingresá tu nombre completo" class="w-full px-4 py-3 border border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                        </div>

                        <div class="form-group">
                            <label for="email" class="block font-semibold text-text-dark mb-2 md:mb-3 text-sm md:text-base">Email</label>
                            <input type="email" id="email" name="email" required placeholder="Ingresá tu email" class="w-full px-4 py-3 border border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                        </div>

                        <button type="submit" class="w-full bg-button text-white py-3 md:py-4 px-8 border-none rounded-lg cursor-pointer font-poppins font-semibold text-lg hover:bg-blue-500 hover:translate-y-[-2px] transition-all shadow-md mt-4">
                            Enviar Intención
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="confirmationModalIntention" class="relative z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full max-w-md p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl md:text-2xl font-semibold text-text-dark">Confirmar Intención</h2>
                    <span class="modal-close cursor-pointer text-2xl text-gray-400 hover:text-button transition-colors">&times;</span>
                </div>
                
                <div class="confirmation-content space-y-4 mb-6 bg-gray-50 p-4 rounded-lg border border-gray-100">
                    <div class="flex flex-col md:flex-row md:justify-between border-b border-gray-200 pb-2">
                        <strong class="text-text-dark text-sm md:text-base">Tipo:</strong> 
                        <span id="confirmIntentionType" class="text-button font-medium capitalize"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:justify-between border-b border-gray-200 pb-2">
                        <strong class="text-text-dark text-sm md:text-base">Nombre:</strong> 
                        <span id="confirmName" class="font-medium text-text-dark"></span>
                    </div>
                    <div class="flex flex-col md:flex-row md:justify-between">
                        <strong class="text-text-dark text-sm md:text-base">Email:</strong> 
                        <span id="confirmEmailIntention" class="text-sm break-all text-gray-600"></span>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row gap-3 md:gap-4 justify-end">
                    <button type="button" id="cancelIntention" class="bg-white text-text-dark py-3 px-6 border border-gray-300 rounded-lg font-semibold hover:bg-gray-50 transition-all w-full md:w-auto text-center">
                        Cancelar
                    </button>
                    <button type="button" id="confirmIntention" class="bg-button text-white py-3 px-6 rounded-lg font-semibold hover:bg-blue-500 transition-all w-full md:w-auto text-center shadow-md">
                        Confirmar
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="js/intenciones.js"></script>
@endsection