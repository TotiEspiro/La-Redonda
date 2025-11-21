<footer class="bg-nav-footer pt-12 pb-4 mt-8">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Logo del Footer -->
        <div class="footer-logo text-center mb-8 pb-8">
            <div class="footer-logo-img">
                <div class="h-15 w-38 border-dashed flex items-center justify-center ">
                    <img src="{{ asset('img/logo_redonda_texto.png') }}" alt="Logo Redonda" style="height: 70px">
                </div>
            </div>
        </div>

        <div class="footer-grid grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
            <!-- Sección 1: Información de Contacto -->
            <div class="footer-section px-4 border-r-2 border-black">
                <h3 class="text-xl font-semibold text-text-dark mb-6">Contacto</h3>
                <div class="footer-info space-y-4">
                    <p>
                        <strong class="block text-black mb-1">Dirección:</strong>
                        Vuelta de Obligado 2042,<br>
                        CABA, Argentina
                    </p>
                    <p>
                        <strong class="block text-black mb-1">Teléfono:</strong>
                       (+54) 011 47838008
                    </p>
                    <p>
                        <strong class="block text-black mb-1">Párroco:</strong>
                        Pedro Bayá Casal
                    </p>
                </div>
            </div>

            <!-- Sección 2: Grupos Parroquiales -->
            <div class="footer-section px-4 border-r-2 border-black">
                <h3 class="text-xl font-semibold text-text-dark mb-6">Grupos Parroquiales</h3>
                <ul class="footer-links space-y-3">
                    <li><a href="http://127.0.0.1:8000/grupos/jovenes" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Juveniles</a></li>
                    <li><a href="http://127.0.0.1:8000/grupos/jovenes" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Carlo Acutis</a></li>
                    <li><a href="http://127.0.0.1:8000/grupos/jovenes" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Juan Pablo II</a></li>
                    <li><a href="http://127.0.0.1:8000/grupos/especiales" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Noche de Caridad</a></li>
                    <li><a href="http://127.0.0.1:8000/grupos/especiales" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Grupo Misionero</a></li>
                    <li><a href="http://127.0.0.1:8000/grupos/jovenes" class="text-text-dark no-underline hover:text-button hover:underline transition-colors block py-1">Coro Parroquial</a></li>
                </ul>
            </div>

            <!-- Sección 3: Redes Sociales en Columna -->
            <div class="footer-section px-4 border-r-2 border-black">
                <h3 class="text-xl font-semibold text-text-dark mb-6">Síguenos en</h3>
                <div class="social-links-column space-y-3">
                    <a href="#" class="social-link-item flex items-center gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram">
                        <span class="social-text font-medium">Instagram</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram">
                        <span class="social-text font-medium">Librería y Santería</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_instagram.png" alt="Instagram">
                        <span class="social-text font-medium">Cáritas Parroquial</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_facebook.png" alt="Facebook">
                        <span class="social-text font-medium">Facebook</span>
                    </a>
                    <a href="#" class="social-link-item flex items-center gap-4 py-3 px-2 no-underline text-text-dark hover:bg-button hover:bg-opacity-10 hover:text-button hover:translate-x-2 transition-all rounded-lg">
                        <img src="/img/icono_youtube.png" alt="Youtube">
                        <span class="social-text font-medium">Youtube</span>
                    </a>
                </div>
            </div>

            <!-- Sección 4: Formulario de Contacto -->
            <div class="footer-section px-4">
                <h3 class="text-xl font-semibold text-text-dark mb-6">Envíanos un Mensaje</h3>
                <form class="contact-form space-y-4">
                    <div class="contact-form-grid grid grid-cols-1 md:grid-cols-2 gap-4">
                        <input type="text" placeholder="Tu nombre" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                        <input type="email" placeholder="Tu email" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20">
                    </div>
                    <textarea placeholder="Escribe tu consulta aquí..." rows="5" required class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg font-poppins text-base focus:outline-none focus:border-button focus:ring-2 focus:ring-button focus:ring-opacity-20 resize-vertical min-h-32"></textarea>
                    <button type="submit" class="w-full bg-button text-white py-3 px-6 border-none rounded-lg cursor-pointer font-poppins font-semibold text-base hover:bg-blue-500 hover:translate-y-[-2px] transition-all">Enviar Mensaje</button>
                </form>
            </div>
        </div>

        <!-- Línea horizontal -->
        <div class="border-t-2 my-6 border-black"></div>

        <!-- Footer Bottom -->
        <div class="footer-bottom flex flex-col md:flex-row justify-between items-center pt-4">
            <div class="copyright text-sm text-text-light mb-4 md:mb-0">
                &copy; 2025 La Redonda Joven - Inmaculada Concepción de Belgrano
            </div>
            <div class="legal-links flex space-x-6">
                <a href="/terminos-condiciones" class="text-sm text-text-light no-underline hover:text-button hover:underline transition-colors">Términos y Condiciones</a>
                <a href="/politica-privacidad" class="text-sm text-text-light no-underline hover:text-button hover:underline transition-colors">Política de Privacidad</a>
            </div>
        </div>
    </div>
</footer>
