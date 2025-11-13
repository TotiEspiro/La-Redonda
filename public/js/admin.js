document.addEventListener('DOMContentLoaded', function() {
    const hamburgerMenu = document.getElementById('hamburgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    
    // Elementos de las líneas del hamburguesa
    const line1 = document.getElementById('line1');
    const line2 = document.getElementById('line2');
    const line3 = document.getElementById('line3');

    // Siempre comenzar con el menú cerrado
    function resetMenu() {
        mobileMenu.classList.add('hidden');
        line1.style.transform = 'none';
        line2.style.opacity = '1';
        line3.style.transform = 'none';
    }

    // Resetear el menú al cargar la página
    resetMenu();

    if (hamburgerMenu && mobileMenu) {
        // Toggle menú principal
        hamburgerMenu.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = mobileMenu.classList.toggle('hidden');
            
            // Animación del hamburguesa a X
            if (isHidden) {
                // Estado: Hamburguesa (volver a estado normal)
                line1.style.transform = 'none';
                line2.style.opacity = '1';
                line3.style.transform = 'none';
            } else {
                // Estado: X
                line1.style.transform = 'rotate(45deg) translateY(8px)';
                line2.style.opacity = '0';
                line3.style.transform = 'rotate(-45deg) translateY(-8px)';
            }
        });

        // Cerrar menú al hacer clic fuera
        document.addEventListener('click', function(event) {
            const isClickInsideHamburger = hamburgerMenu.contains(event.target);
            const isClickInsideMenu = mobileMenu.contains(event.target);
            
            if (!isClickInsideHamburger && !isClickInsideMenu && !mobileMenu.classList.contains('hidden')) {
                resetMenu();
            }
        });

        // Cerrar con tecla Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !mobileMenu.classList.contains('hidden')) {
                resetMenu();
            }
        });

        // Prevenir que el menú se cierre al hacer clic dentro de él
        mobileMenu.addEventListener('click', function(e) {
            e.stopPropagation();
        });

        // Cerrar menú automáticamente al hacer clic en cualquier enlace
        const allLinks = mobileMenu.querySelectorAll('a');
        allLinks.forEach(link => {
            link.addEventListener('click', function() {
                // Pequeño delay para permitir la navegación
                setTimeout(resetMenu, 100);
            });
        });

    } else {
        console.error('Elementos del menú no encontrados');
    }
});