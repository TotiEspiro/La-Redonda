

<?php $__env->startSection('content'); ?>
<div class="intentions-container py-8 md:py-12">
    
    <div class="container max-w-7xl mx-auto px-4">
        <h1 class="donation-title text-3xl md:text-4xl font-semibold text-text-dark text-center mb-8 md:mb-12 border-b-2 border-black pb-2">Intenciones</h1>
        <div class="intentions-content grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 max-w-6xl mx-auto items-start">
            <div class="intentions-left order-1 lg:order-1">
                <div class="space-y-6 text-base md:text-lg bg-white p-6 rounded-xl shadow-sm border border-gray-100 lg:bg-transparent lg:shadow-none lg:border-none lg:p-0">
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
                </div>
            </div>
            <div class="intentions-right order-2 lg:order-2">
                <div class="form-container bg-background-light p-6 md:p-8 rounded-xl shadow-lg border border-gray-200">
                    <form id="intentionForm" class="intention-form space-y-6">
                        
                        <div class="form-group">
                            <label for="intentionType" class="block font-semibold text-text-dark mb-2">Tipo de Intención</label>
                            <select id="intentionType" name="intentionType" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                                <option value="" disabled selected>Seleccioná una opción...</option>
                                <option value="salud">Salud</option>
                                <option value="intenciones">Intenciones Particulares</option>
                                <option value="accion-gracias">Acción de Gracias</option>
                                <option value="difuntos">Difuntos</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name" class="block font-semibold text-text-dark mb-2">Nombre</label>
                            <input type="text" id="name" name="name" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Ingresá tu nombre">
                        </div>
                        <div class="form-group">
                            <label for="email" class="block font-semibold text-text-dark mb-2">Email</label>
                            <input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-300 rounded-lg" placeholder="Ingresá tu email">
                        </div>
                        <button type="submit" class="w-full bg-button text-white py-4 rounded-lg font-semibold hover:bg-blue-900 transition-all shadow-md">
                            Enviar Intención
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="confirmationModalIntention" class="hidden fixed inset-0 z-50 items-center justify-center p-4 bg-black bg-opacity-70">
    <div class="relative bg-white rounded-xl shadow-xl w-full max-w-md p-6 md:p-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-text-dark">Confirmar Intención</h2>
            <span class="modal-close cursor-pointer text-2xl text-gray-400">&times;</span>
        </div>
        <div class="confirmation-content space-y-4 mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between border-b pb-2"><strong class="text-sm">Tipo:</strong> <span id="confirmIntentionType" class="text-button font-medium"></span></div>
            <div class="flex justify-between border-b pb-2"><strong class="text-sm">Nombre:</strong> <span id="confirmName" class="font-medium"></span></div>
            <div class="flex justify-between"><strong class="text-sm">Email:</strong> <span id="confirmEmailIntention" class="text-xs text-gray-600"></span></div>
        </div>
        <div class="flex gap-4 justify-end">
            <button type="button" id="cancelIntention" class="bg-white text-text-dark py-2 px-6 border rounded-lg font-semibold">Cancelar</button>
            <button type="button" id="confirmIntention" class="bg-button text-white py-2 px-6 rounded-lg font-semibold shadow-md">Confirmar</button>
        </div>
    </div>
</div>


<div id="statusModal" class="hidden fixed inset-0 z-[60] items-center justify-center p-4 bg-black bg-opacity-75">
    <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-sm p-8 text-center animate-fade-in">
        <div id="statusModalIcon" class="mb-4">
            
        </div>
        <h3 id="statusModalTitle" class="text-xl font-bold text-text-dark mb-2"></h3>
        <p id="statusModalMessage" class="text-text-light mb-8 text-sm leading-relaxed"></p>
        <button id="closeStatusModal" class="w-full bg-button text-white py-3 rounded-xl font-bold hover:bg-blue-900 transition-all shadow-lg">
            Entendido
        </button>
    </div>
</div>

<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<script src="js/intenciones.js"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/intenciones.blade.php ENDPATH**/ ?>