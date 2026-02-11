/**
 * Lógica de pantalla de carga y utilidades de login
 */

function showProgressLoading() {
    const loadingScreen = document.getElementById('loadingScreenProgress');
    const loadingProgress = document.getElementById('loadingProgress');
    const loadingPercent = document.getElementById('loadingPercent');
    
    if (!loadingScreen || !loadingProgress || !loadingPercent) {
        console.error('Elementos de loading no encontrados');
        return;
    }
    
    // Mostrar pantalla
    loadingScreen.style.display = 'flex';
    
    let progress = 0;
    
    function updateProgress() {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;
        
        loadingProgress.style.width = progress + '%';
        loadingPercent.textContent = Math.round(progress);
        
        if (progress < 100) {
            setTimeout(updateProgress, 150);
        } else {
            // No ocultamos inmediatamente si el form se está enviando
            // Laravel recargará la página o redirigirá.
        }
    }
    
    setTimeout(updateProgress, 100);
}

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const eyeIcon = document.getElementById('eyeIcon');

    // Funcionalidad de Ver/Ocultar Contraseña
    if (togglePasswordBtn && passwordInput && eyeIcon) {
        togglePasswordBtn.addEventListener('click', function() {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            
            // Cambiar tipo de input
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            
            // Cambiar icono SVG (Ojo abierto / Ojo con raya)
            if (isPassword) {
                // SVG Ojo tachado (Ver contraseña activado)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                `;
            } else {
                // SVG Ojo normal (Ocultar contraseña)
                eyeIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                `;
            }
        });
    }

    // Activar loading al enviar
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            showProgressLoading();
        });
    }
});