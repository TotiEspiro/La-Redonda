<footer class="bg-nav-footer pt-12 pb-24 md:pb-12">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="footer-logo text-center mb-8 pb-8">
            <div class="footer-logo-img flex justify-center">
                <div class="h-auto w-auto flex items-center justify-center">
                    <img src="{{ asset('img/logo_redonda_texto.png') }}" alt="Logo Redonda" class="h-14 md:h-18 w-auto">
                </div>
            </div>
        </div>

        <div class="footer-grid grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <div class="footer-section px-4 border-b-2 border-black pb-8 md:pb-0 md:border-b-0 md:border-r-2 last:border-0">
                <h3 class="text-xl font-semibold text-text-dark mb-4 md:mb-6 text-center md:text-left">Contacto</h3>
                <div class="footer-info space-y-4 text-center md:text-left">
                    <p>
                        <strong class="block text-black mb-1">Dirección:</strong>
                        Vuelta de Obligado 2042,<br>
                        CABA, Argentina
                    </p>
                    <p>
                        <strong class="block text-black mb-1">Teléfono:</strong>
                        (+54) 011 4783-8008
                    </p>
                    <p>
                        <strong class="block text-black mb-1">Párroco:</strong>
                        Pedro Bayá Casal
                    </p>
                </div>
            </div>

            {{-- Sección Grupos --}}
            <div class="footer-section px-4 border-b-2 border-black pb-8 md:pb-0 md:border-b-0 md:border-r-2 last:border-0">
                <h3 class="text-xl font-semibold text-text-dark mb-4 md:mb-6 text-center md:text-left">Grupos Parroquiales</h3>
                <ul class="footer-links space-y-3 text-center md:text-left">
                    <li><a href="{{ url('/grupos/jovenes') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Juveniles</a></li>
                    <li><a href="{{ url('/grupos/jovenes') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Carlo Acutis</a></li>
                    <li><a href="{{ url('/grupos/jovenes') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Juan Pablo II</a></li>
                    <li><a href="{{ url('/grupos/especiales') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Noche de Caridad</a></li>
                    <li><a href="{{ url('/grupos/especiales') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Grupo Misionero</a></li>
                    <li><a href="{{ url('/grupos/jovenes') }}" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Coro Parroquial</a></li>
                </ul>
            </div>

            {{-- Sección Redes --}}
            <div class="footer-section px-4 border-b-2 border-black pb-8 md:pb-0 md:border-b-0 md:border-r-2 last:border-0">
                <h3 class="text-xl font-semibold text-text-dark mb-4 md:mb-6 text-center md:text-left">Seguinos en</h3>
                <div class="social-links-column space-y-3">
                    <a href="#" class="social-link-item flex items-center justify-center md:justify-start gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button md:hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram" class="w-6 h-6">
                        <span class="social-text font-medium">Instagram</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center justify-center md:justify-start gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button md:hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram" class="w-6 h-6">
                        <span class="social-text font-medium">Librería y Santería</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center justify-center md:justify-start gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button md:hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram" class="w-6 h-6">
                        <span class="social-text font-medium">Cáritas Parroquial</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center justify-center md:justify-start gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button md:hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_facebook.png" alt="Facebook" class="w-6 h-6">
                        <span class="social-text font-medium">Facebook</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center justify-center md:justify-start gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button md:hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_youtube.png" alt="Youtube" class="w-6 h-6">
                        <span class="social-text font-medium">Youtube</span>
                    </a>
                </div>
            </div>

            <div class="footer-section px-4">
                <h3 class="text-xl font-semibold text-text-dark mb-4 md:mb-6 text-center md:text-left">
                    Envíanos un Mensaje
                </h3>

                <form id="contactForm" class="contact-form space-y-4"> 
                    <div class="contact-form-grid grid grid-cols-1 gap-4">
                        <div class="flex flex-col">
                            <label for="name" class="block text-sm font-bold text-gray-700 mb-1 ml-1">
                                Nombre Completo
                            </label>
                            <input 
                                id="name" 
                                type="text" 
                                placeholder="Ingresá tu nombre completo" 
                                required 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-sm focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 transition-colors">
                        </div>
                        <div class="flex flex-col">
                            <label for="email" class="block text-sm font-bold text-gray-700 mb-1 ml-1">
                                Email
                            </label>
                            <input 
                                id="email" 
                                type="email" 
                                placeholder="Ingresá tu email" 
                                required 
                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-sm focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 transition-colors">
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="consulta" class="block text-sm font-bold text-gray-700 mb-1 ml-1">
                            Consulta
                        </label>
                        <textarea 
                            id="consulta" 
                            placeholder="Escribí tu consulta acá..." 
                            rows="5" 
                            required 
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-sm focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 resize-y min-h-[128px] transition-colors"
                        ></textarea>
                    </div>
                    <button 
                        type="submit" 
                        class="w-full bg-button text-white py-3 px-6 border-none rounded-lg cursor-pointer font-poppins font-semibold text-sm hover:bg-blue-900 hover:-translate-y-0.5 transition-all duration-200 shadow-md"
                    >
                        Enviar Mensaje
                    </button>
                </form>
            </div>
        </div>

        <div class="border-t-2 my-6 border-black"></div>

        <div class="footer-bottom flex flex-col md:flex-row justify-between items-center pt-4 gap-4 text-center md:text-left">
            <div class="copyright text-sm text-text-light">
                &copy; 2025 La Redonda - Inmaculada Concepción de Belgrano (Sitio Web realizado por Tomas Espiro)
            </div>
            <div class="legal-links flex flex-col md:flex-row gap-4 md:gap-6">
                <a href="{{ route('legal.terminos') }}" class="text-sm text-text-light no-underline hover:text-button hover:underline transition-colors">Términos y Condiciones</a>
                <a href="{{ route('legal.privacidad') }}" class="text-sm text-text-light no-underline hover:text-button hover:underline transition-colors">Política de Privacidad</a>
            </div>
        </div>
    </div>
</footer>

{{-- MODAL DE CONFIRMACIÓN DE CONTACTO --}}
<div id="contactSuccessModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    {{-- Fondo oscuro --}}
    <div class="fixed inset-0 bg-black bg-opacity-70 transition-opacity"></div>

    {{-- Contenedor Centrado --}}
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            
            {{-- Panel del Modal --}}
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all w-full max-w-md p-6">
                
                <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 mb-4">
                    <img src="{{ asset('img/icono_activo.png') }}" alt="Activo" class="h-12 w-12">
                </div>

                <div class="text-center">
                    <h3 class="text-lg font-bold text-gray-900 mb-2" id="modal-title">¡Mensaje Enviado!</h3>
                    <p class="text-sm text-gray-500">
                        Muchas gracias por escribirnos. Recibimos tu consulta y te responderemos a la brevedad a tu correo electrónico.
                    </p>
                </div>

                <div class="mt-6">
                    <button type="button" id="closeContactModal" class="inline-flex w-full justify-center rounded-lg bg-button px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-button focus:ring-offset-2 transition-colors">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const contactModal = document.getElementById('contactSuccessModal');
        const closeContactBtn = document.getElementById('closeContactModal');

        if (contactForm && contactModal) {
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();
                contactModal.classList.remove('hidden');
                contactForm.reset();
            });
            function closeModal() {
                contactModal.classList.add('hidden');
            }
            closeContactBtn.addEventListener('click', closeModal);
            contactModal.addEventListener('click', function(e) {
                if (e.target === this.querySelector('.fixed.inset-0.bg-black') || e.target === this) {
                }
            });     
            contactModal.addEventListener('click', function(e) {
               if(e.target.classList.contains('flex') && e.target.classList.contains('items-center')) {
                   closeModal();
               }
            });
        }
    });
</script>