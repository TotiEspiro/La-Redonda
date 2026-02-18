@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="bg-button p-8 text-white text-center">
            <h1 class="text-3xl font-bold">Política de Privacidad</h1>
            <p class="mt-2 opacity-90 text-sm italic text-white">Nos importa tu privacidad</p>
        </div>
        
        <div class="p-8 prose prose-blue max-w-none text-gray-600 leading-relaxed">
            <h2 class="text-xl font-bold text-gray-800">1. Información que Recopilamos</h2>
            <p>Recopilamos información personal básica (nombre, correo electrónico) para la creación de cuentas. Además, almacenamos el contenido generado en su Diario Espiritual e Intenciones de Misa.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">2. Seguridad de los Datos</h2>
            <p>En <strong>La Redonda</strong>, la seguridad es prioridad. El contenido de tu diario se almacena de forma encriptada, lo que significa que solo es legible por el propietario de la cuenta a través de su sesión activa.</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">3. Uso de la Información</h2>
            <p>Los datos se utilizan únicamente para personalizar su experiencia, gestionar sus solicitudes parroquiales y enviar notificaciones importantes (si usted las habilita).</p>

            <h2 class="text-xl font-bold text-gray-800 mt-6">4. Derechos del Usuario</h2>
            <p>Usted tiene derecho a:</p>
            <ul class="list-disc pl-5 space-y-2">
                <li>Acceder a sus datos personales.</li>
                <li>Rectificar información inexacta.</li>
                <li>Eliminar su cuenta y todos los datos asociados de forma permanente.</li>
            </ul>

            <h2 class="text-xl font-bold text-gray-800 mt-6">5. Cookies y Notificaciones Push</h2>
            <p>Utilizamos cookies esenciales para mantener su sesión iniciada. Las notificaciones push se utilizan para avisos de la comunidad y recordatorios, pudiendo ser desactivadas desde el perfil.</p>

            <div class="mt-10 pt-6 border-t border-gray-100 flex justify-center">
                <img src="{{ asset('img/logo_redonda_texto.png') }}" class="h-12 opacity-50" alt="La Redonda">
            </div>
        </div>
    </div>
</div>
@endsection