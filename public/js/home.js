document.addEventListener('DOMContentLoaded', function() {
    // Carrusel de Avisos - Mostrar 2 tarjetas a la vez
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (track && prevBtn && nextBtn) {
        let currentPosition = 0;
        const cards = document.querySelectorAll('.announcement-card');
        const totalCards = cards.length;
        const cardsPerView = 2;
        const totalSlides = Math.ceil(totalCards / cardsPerView);
        
        console.log('Total cards:', totalCards, 'Total slides:', totalSlides);
        
        function updateCarousel() {
            // Calcular el desplazamiento basado en el ancho del contenedor
            const containerWidth = track.parentElement.clientWidth;
            const translateX = currentPosition * containerWidth;
            
            track.style.transform = `translateX(-${translateX}px)`;
            
            // Actualizar estado de botones
            prevBtn.disabled = currentPosition === 0;
            nextBtn.disabled = currentPosition >= totalSlides - 1;
            
            console.log('Current position:', currentPosition, 'Translate X:', translateX + 'px');
        }
        
        prevBtn.addEventListener('click', function() {
            if (currentPosition > 0) {
                currentPosition--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', function() {
            if (currentPosition < totalSlides - 1) {
                currentPosition++;
                updateCarousel();
            }
        });
        
        // Inicializar carrusel
        function initCarousel() {
            // Configurar el ancho del track para que quepan todos los slides
            const containerWidth = track.parentElement.clientWidth;
            track.style.width = `${totalSlides * containerWidth}px`;
            
            // Configurar el ancho de cada card
            cards.forEach(card => {
                card.style.width = `calc(${containerWidth / cardsPerView}px - 2rem)`;
            });
            
            updateCarousel();
        }
        
        initCarousel();
        
        // Recalcular en resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(initCarousel, 250);
        });
    }

    // =============================================
    // MODALES (Tu código original - mantenido)
    // =============================================
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.modal-close');

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    closeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modal = this.closest('.modal');
            if (modal) {
                closeModal(modal);
            }
        });
    });

    modals.forEach(modal => {
        modal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeModal(this);
            }
        });
    });

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            modals.forEach(modal => {
                if (modal.classList.contains('flex')) {
                    closeModal(modal);
                }
            });
        }
    });

    const readMoreBtns = document.querySelectorAll('.read-more-btn');
    readMoreBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal');
            const modal = document.getElementById(modalId);
            
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            }
        });
    });
});