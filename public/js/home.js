document.addEventListener('DOMContentLoaded', function() {
    const scheduleHeaders = document.querySelectorAll('.schedule-header');

    if (scheduleHeaders.length > 0) {
        scheduleHeaders.forEach(header => {
            const newHeader = header.cloneNode(true);
            header.parentNode.replaceChild(newHeader, header);
            
            newHeader.addEventListener('click', function(e) {
                if (window.innerWidth >= 768) return;

                e.preventDefault(); 
                
                const content = this.nextElementSibling;
                const chevron = this.querySelector('.schedule-chevron');

                if (content) {
                    if (content.classList.contains('hidden')) {
                        content.classList.remove('hidden');
                        if (chevron) chevron.style.transform = 'rotate(180deg)';
                    } else {
                        content.classList.add('hidden');
                        if (chevron) chevron.style.transform = 'rotate(0deg)';
                    }
                }
            });
        });
    }

    
    const track = document.getElementById('carouselTrack');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    if (track) {
        let currentIndex = 0;
        let cardsPerView = 1;
        let touchStartX = 0;
        let touchEndX = 0;

        const cards = document.querySelectorAll('.announcement-wrapper');
        const totalCards = cards.length;
        
        function updateDimensions() {
            cardsPerView = window.innerWidth < 768 ? 1 : 2;
            
            const container = track.parentElement; 
            if (!container) return;

            const containerWidth = container.getBoundingClientRect().width;
            const slideWidth = containerWidth / cardsPerView;

            cards.forEach(card => {
                card.style.width = `${slideWidth}px`;
                card.style.flexShrink = '0';
            });

            updatePosition();
        }

        function updatePosition() {
            const maxIndex = Math.max(0, totalCards - cardsPerView);
            if (currentIndex > maxIndex) currentIndex = maxIndex;
            if (currentIndex < 0) currentIndex = 0;

            const container = track.parentElement;
            if (!container) return;

            const slideWidth = container.getBoundingClientRect().width / cardsPerView;
            const currentTranslate = currentIndex * -slideWidth;
            
            track.style.transform = `translateX(${currentTranslate}px)`;
            
            if(prevBtn && nextBtn) {
                prevBtn.disabled = currentIndex === 0;
                nextBtn.disabled = currentIndex === maxIndex;
                prevBtn.style.opacity = currentIndex === 0 ? '0.5' : '1';
                prevBtn.style.cursor = currentIndex === 0 ? 'not-allowed' : 'pointer';
                
                nextBtn.style.opacity = currentIndex === maxIndex ? '0.5' : '1';
                nextBtn.style.cursor = currentIndex === maxIndex ? 'not-allowed' : 'pointer';
            }
        }

        if(prevBtn) prevBtn.addEventListener('click', () => { if(currentIndex > 0) { currentIndex--; updatePosition(); }});
        if(nextBtn) nextBtn.addEventListener('click', () => { const maxIndex = totalCards - cardsPerView; if(currentIndex < maxIndex) { currentIndex++; updatePosition(); }});

        track.addEventListener('touchstart', e => { touchStartX = e.changedTouches[0].screenX; }, {passive: true});
        track.addEventListener('touchend', e => { 
            touchEndX = e.changedTouches[0].screenX; 
            if (touchEndX < touchStartX - 50) { 
                const maxIndex = totalCards - cardsPerView;
                if (currentIndex < maxIndex) { currentIndex++; updatePosition(); }
            }
            if (touchEndX > touchStartX + 50) { 
                if (currentIndex > 0) { currentIndex--; updatePosition(); }
            }
        }, {passive: true});

        updateDimensions();
        let resizeTimeout;
        window.addEventListener('resize', () => { 
            clearTimeout(resizeTimeout); 
            resizeTimeout = setTimeout(updateDimensions, 100); 
        });
    }

    
    const modals = document.querySelectorAll('.modal');
    const closeBtns = document.querySelectorAll('.modal-close');
    const readMoreBtns = document.querySelectorAll('.read-more-btn');

    function closeModal(modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function openModal(modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    closeBtns.forEach(btn => btn.addEventListener('click', function() { 
        const modal = this.closest('.modal');
        if (modal) closeModal(modal);
    }));

    modals.forEach(modal => modal.addEventListener('click', function(e) { 
        if (e.target === this) closeModal(this); 
    }));

    document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') { 
            modals.forEach(m => { if(m.classList.contains('flex')) closeModal(m); }); 
        }
    });
    
    readMoreBtns.forEach(btn => btn.addEventListener('click', function() {
        const modalId = this.getAttribute('data-modal');
        const modal = document.getElementById(modalId);
        if (modal) openModal(modal);
    }));
});