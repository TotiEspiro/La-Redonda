<?php $__env->startSection('content'); ?>
<section class="py-12 md:py-16">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 md:gap-12 items-center">
            <div class="order-1 lg:order-1">
                <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-6 md:mb-8 border-b-4 border-button pb-2 text-center md:text-center uppercase tracking-tighter">Bienvenidos a La Redonda</h1>
                <div class="space-y-4 text-base md:text-lg font-medium leading-relaxed">
                    <p class="text-text-dark">La Iglesia de la Inmaculada Concepción, conocida cariñosamente como <strong>"La Redonda"</strong>, es un faro de fe y comunidad en el corazón de Belgrano.</p>
                    <p class="text-text-dark">Con casi <strong>150 años</strong> de historia, nuestra iglesia combina la rica tradición católica con una vibrante vida comunitaria que acoge a personas de todas las edades.</p>
                    <p class="text-text-dark">Creemos en el poder transformador del evangelio y en la importancia de construir una comunidad donde cada persona se sienta valorada y acompañada.</p>
                    <p class="text-text-dark">Ofrecemos diversos <strong>grupos y actividades para jóvenes, adultos y familias</strong>, buscando crecer juntos en la fe y el servicio a los demás.</p>
                </div>
            </div>
            <div class="order-2 lg:order-2 mt-8 lg:mt-0">
                <img src="img/iglesia_la_redonda.jpg" alt="La Redonda" class="m-auto rounded-3xl shadow-2xl transform transition duration-500 hover:scale-105 w-full max-w-md h-auto object-cover">
            </div>
        </div>
    </div>
</section>


<section class="py-12 bg-gray-50 border-y border-gray-100">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-black text-center text-text-dark mb-10 border-b-2 border-black pb-2 uppercase tracking-tighter">Avisos Parroquiales</h2>
        <?php if(isset($announcements) && $announcements->count() > 0): ?>
        <div class="carousel-outer-container relative max-w-6xl mx-auto px-4">
            <div class="overflow-hidden py-4" id="carouselContainer">
                <div class="flex transition-transform duration-500 ease-out" id="carouselTrack">
                    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="announcement-wrapper flex-shrink-0 px-3 box-border flex justify-center w-full md:w-1/2 lg:w-1/3">
                        <div class="announcement-card bg-white rounded-3xl overflow-hidden shadow-sm text-center flex flex-col h-full border border-gray-100 w-full group hover:shadow-xl transition-all duration-300">
                            <div class="w-full aspect-video bg-gray-50 flex items-center justify-center overflow-hidden relative">
                                <?php if($announcement->image_url): ?>
                                    <img src="<?php echo e($announcement->image_url); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                                <?php else: ?>
                                    <div class="text-gray-200"><svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg></div>
                                <?php endif; ?>
                                <div class="absolute top-4 left-4"><span class="bg-button text-white text-[8px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Noticia</span></div>
                            </div>
                            <div class="p-6 flex flex-col flex-1">
                                <h3 class="text-sm font-black text-text-dark mb-2 uppercase line-clamp-2"><?php echo e($announcement->title); ?></h3>
                                <p class="text-xs text-text-light mb-6 flex-1 line-clamp-3 font-medium leading-relaxed"><?php echo e($announcement->short_description); ?></p>
                                <button class="read-more-btn w-full bg-button text-white py-3 rounded-2xl font-black text-sm hover:bg-blue-900 transition-colors" data-modal="modal-<?php echo e($announcement->id); ?>">Leer Más</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <?php if($announcements->count() > 1): ?>
            <div class="carousel-nav flex justify-center gap-4 mt-8">
                <button class="bg-white text-button border-2 border-button/10 w-12 h-12 rounded-2xl flex items-center justify-center hover:text-white hover:bg-blue-900 transition-all shadow-sm" id="prevBtn"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg></button>
                <button class="bg-white text-button border-2 border-button/10 w-12 h-12 rounded-2xl flex items-center justify-center hover:text-white hover:bg-blue-900 transition-all shadow-sm" id="nextBtn"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg></button>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>


<section class="py-12 bg-white">
    <div class="container max-w-7xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-black text-center text-text-dark mb-10 border-b-2 border-black pb-2 uppercase tracking-tighter">Horarios de la Parroquia</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            
            <div class="schedule-card bg-white rounded-3xl shadow-lg border border-gray-100 flex flex-col h-auto overflow-hidden">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default group relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 bg-nav-footer rounded-2xl flex items-center justify-center group-hover:bg-button transition-colors"><img src="<?php echo e(asset('img/icono_misas.png')); ?>" class="h-8 w-auto"></div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-black">Misas</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 bg-white border-t md:border-t-0 border-gray-50">
                    <div class="space-y-5 text-text-dark text-center">
                        <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-1">Lunes a Sábado</span><p class="text-gray-800 font-bold text-sm">10:00 | 17:30 | 19:30</p></div>
                        <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-1">Domingo</span><p class="text-gray-800 font-bold text-[13px]">08:00 | 09:30 | 11:00<br>12:30 | 18:00 | 19:00<br>20:00</p></div>
                    </div>
                </div>
            </div>
            
            <div class="schedule-card bg-white rounded-3xl shadow-lg border border-gray-100 flex flex-col h-auto overflow-hidden">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default group relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 bg-nav-footer rounded-2xl flex items-center justify-center group-hover:bg-button transition-colors"><img src="<?php echo e(asset('img/icono_confesiones.png')); ?>" class="h-8 w-auto"></div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-black">Confesiones</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 text-center">
                    <span class="block font-black text-gray-400 text-[10px] uppercase mb-2">Lunes a Sábado</span>
                    <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100"><p class="text-gray-800 font-bold text-sm">10:30 a 12:00<br>18:00 a 19:00</p></div>
                </div>
            </div>
            
            <div class="schedule-card bg-white rounded-3xl shadow-lg border border-gray-100 flex flex-col h-auto overflow-hidden">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default group relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 bg-nav-footer rounded-2xl flex items-center justify-center group-hover:bg-button transition-colors"><img src="<?php echo e(asset('img/icono_secretaria.png')); ?>" class="h-8 w-auto"></div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-black">Secretaría</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 text-center">
                    <span class="block font-black text-gray-400 text-[10px] uppercase mb-1">Lunes a Viernes</span>
                    <p class="text-gray-800 font-bold text-sm mb-3">16:00 a 19:00</p>
                    <a href="mailto:secretaria@inmaculada.org.ar" class="text-button font-bold text-[9px] uppercase hover:underline">secretaria@inmaculada.org.ar</a>
                </div>
            </div>
            
            <div class="schedule-card bg-white rounded-3xl shadow-lg border border-gray-100 flex flex-col h-auto overflow-hidden">
                <div class="schedule-header p-6 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default group relative z-10">
                    <div class="flex items-center gap-4 md:flex-col md:gap-6">
                        <div class="w-14 h-14 bg-nav-footer rounded-2xl flex items-center justify-center group-hover:bg-button transition-colors"><img src="<?php echo e(asset('img/icono_donaciones.png')); ?>" class="h-8 w-auto"></div>
                        <h4 class="text-xs font-black uppercase tracking-widest text-black">Donaciones</h4>
                    </div>
                    <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                </div>
                <div class="schedule-content hidden md:block px-6 pb-8 pt-0 md:pt-2 text-center">
                    <p class="text-[9px] text-gray-400 font-black uppercase mb-3 bg-gray-50 py-1 rounded-full">Recogida de ropa/alimentos</p>
                    <p class="text-gray-800 font-bold text-sm">L-S: 09:00 a 21:00<br>D: 07:30 a 21:30</p>
                </div>
            </div>
        </div>
    </div>
</section>


<?php if(isset($announcements)): ?>
    <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div id="modal-<?php echo e($announcement->id); ?>" class="modal hidden fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md">
        <div class="bg-white rounded-3xl max-w-4xl max-h-[90vh] shadow-2xl flex flex-col overflow-hidden relative animate-fade-in">
            <button type="button" class="modal-close absolute top-6 right-6 z-20 bg-white/90 rounded-full w-12 h-12 flex items-center justify-center shadow-xl hover:bg-red-500 hover:text-white transition-all">
                <span class="text-2xl font-bold">&times;</span>
            </button>
            <div class="p-6 md:p-10 overflow-y-auto custom-scrollbar">
                <?php if($announcement->image_url): ?>
                    <div class="rounded-3xl overflow-hidden mb-8 shadow-inner bg-gray-50"><img src="<?php echo e($announcement->image_url); ?>" class="w-full h-auto max-h-[45vh] object-contain mx-auto"></div>
                <?php endif; ?>
                <h3 class="text-2xl md:text-4xl font-black text-gray-900 mb-6 uppercase tracking-tighter border-b-4 border-button pb-4 pr-16"><?php echo e($announcement->title); ?></h3>
                <div class="text-gray-700 mb-10 whitespace-pre-line leading-relaxed text-base font-medium text-justify"><?php echo nl2br(e($announcement->full_description)); ?></div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Lógica Carrusel
    const track = document.getElementById('carouselTrack');
    const container = document.getElementById('carouselContainer');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    if (track && container) {
        let index = 0;
        const items = document.querySelectorAll('.announcement-wrapper');
        function updateCarousel() {
            const containerWidth = container.offsetWidth;
            let show = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
            const itemWidth = containerWidth / show;
            items.forEach(item => { item.style.width = `${itemWidth}px`; });
            track.style.transform = `translateX(-${index * itemWidth}px)`;
            if(prevBtn) prevBtn.disabled = index === 0;
            if(nextBtn) nextBtn.disabled = index >= items.length - show;
        }
        if(nextBtn) nextBtn.onclick = () => { let show = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1); if (index < items.length - show) { index++; updateCarousel(); } };
        if(prevBtn) prevBtn.onclick = () => { if (index > 0) { index--; updateCarousel(); } };
        window.addEventListener('resize', updateCarousel); updateCarousel();
    }
    // Lógica Colapsables (Horarios)
    document.querySelectorAll('.schedule-header').forEach(header => {
        header.addEventListener('click', () => {
            if (window.innerWidth < 768) {
                const card = header.closest('.schedule-card');
                const content = card.querySelector('.schedule-content');
                const chevron = header.querySelector('.schedule-chevron');
                content.classList.toggle('hidden');
                chevron.style.transform = content.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            }
        });
    });
    // Modales
    document.querySelectorAll('.read-more-btn').forEach(btn => { btn.onclick = () => { document.getElementById(btn.dataset.modal).classList.remove('hidden'); document.body.style.overflow = 'hidden'; }; });
    document.querySelectorAll('.modal-close, .modal').forEach(el => { el.onclick = (e) => { if (e.target === el || el.classList.contains('modal-close')) { el.closest('.modal').classList.add('hidden'); document.body.style.overflow = 'auto'; } }; });
});
</script>
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
    @keyframes fade-in { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in { animation: fade-in 0.4s ease-out forwards; }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/home.blade.php ENDPATH**/ ?>