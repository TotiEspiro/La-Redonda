

<?php $__env->startSection('content'); ?>
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-button p-8 text-white text-center">
            <h1 class="text-3xl font-bold">Términos y Condiciones</h1>
            <p class="mt-2 opacity-90 text-sm italic text-white">Última actualización: <?php echo e(date('d/m/Y')); ?></p>
        </div>
        
        <div class="p-8 prose prose-blue max-w-none text-gray-600 leading-relaxed">
            <h2 class="text-xl font-bold text-gray-800">1. Aceptación de los Términos</h2>
            <p>Al acceder y utilizar la aplicación de <strong>La Redonda</strong>, usted acepta cumplir con estos términos y condiciones de uso. Si no está de acuerdo con alguna de las cláusulas, le recomendamos no utilizar el servicio.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">2. Uso del Diario Espiritual</h2>
            <p>El "Diario Espiritual" es una herramienta personal. Aunque el sistema implementa medidas de seguridad (como encriptación de datos), el usuario es responsable de mantener la confidencialidad de su cuenta y contraseña.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">3. Donaciones e Intenciones</h2>
            <p>Las donaciones realizadas a través de la plataforma son voluntarias y se destinan al sostenimiento de la parroquia y sus obras de caridad. Las intenciones de misa solicitadas están sujetas a la disponibilidad y programación parroquial.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">4. Conducta del Usuario</h2>
            <p>El usuario se compromete a utilizar la aplicación con respeto hacia la comunidad. Queda prohibida la carga de contenido ofensivo, ilegal o que vulnere la sensibilidad de la comunidad parroquial.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">5. Modificaciones</h2>
            <p>La Redonda se reserva el derecho de modificar estos términos en cualquier momento para adaptarlos a nuevas funcionalidades o requisitos legales.</p>

            <div class="mt-10 p-4 bg-blue-50 rounded-lg text-blue-800 text-sm">
                Si tiene dudas sobre estos términos, puede contactarse con la secretaría parroquial.
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/legal/terminos.blade.php ENDPATH**/ ?>