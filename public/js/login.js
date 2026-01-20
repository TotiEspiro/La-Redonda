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
            setTimeout(updateProgress, 200);
        } else {
            setTimeout(() => {
                loadingScreen.classList.add('fade-out');
                setTimeout(() => {
                    loadingScreen.style.display = 'none';
                }, 500);
            }, 500);
        }
    }
    
    // Iniciar progreso
    setTimeout(updateProgress, 300);
    
    // Fallback por si algo sale mal
    setTimeout(() => {
        if (loadingScreen.style.display !== 'none') {
            loadingScreen.classList.add('fade-out');
            setTimeout(() => {
                loadingScreen.style.display = 'none';
            }, 500);
        }
    }, 5000);
}

// Activar en el formulario de login
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            showProgressLoading();
        });
    }
});