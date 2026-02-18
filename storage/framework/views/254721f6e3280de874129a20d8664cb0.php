

<?php $__env->startSection('content'); ?>
<div class="bg-gray-50 min-h-screen py-8 md:py-12">
    <div class="container max-w-7xl mx-auto px-4 text-center md:text-left">
        
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-black text-gray-900 uppercase tracking-tighter">Bienvenido, <?php echo e(Auth::user()->name); ?></h1>
            <p class="text-gray-500 font-medium">Panel central de actividad parroquial</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            
            <div class="lg:col-span-2 space-y-8 text-left">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8">
                    <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Mis Comunidades Activas</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?php
                            // Lista de slugs actualizada
                            $allGroupSlugs = [
                                'catequesis_niños', 'catequesis_adolescentes', 'catequesis_adultos', 
                                'acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros', 
                                'santa_ana', 'san_joaquin', 'ardillas', 'costureras', 
                                'comedor', 'caritas', 'caridad'
                            ];

                            $userGroups = Auth::user()->roles->filter(function($r) use ($allGroupSlugs) {
                                $slug = str_replace('admin_', '', $r->name);
                                return in_array($slug, $allGroupSlugs);
                            })->unique(fn($r) => str_replace('admin_', '', $r->name));
                            
                            $isMember = $userGroups->isNotEmpty() || Auth::user()->isAdmin();
                        ?>
                        <?php $__empty_1 = true; $__currentLoopData = $userGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php $slug = str_replace('admin_', '', $role->name); $isAdmin = Auth::user()->isAdminOfGroup($slug); ?>
                            <a href="<?php echo e(route('grupos.dashboard', $slug)); ?>" class="flex items-center p-4 bg-gray-50 rounded-2xl border-2 border-transparent hover:border-button/30 hover:bg-white transition-all group">
                                <div class="w-12 h-12 rounded-xl bg-blue-900 text-white flex items-center justify-center font-black uppercase text-lg shadow-sm group-hover:scale-105 transition-transform"><?php echo e(substr($slug, 0, 1)); ?></div>
                                <div class="ml-4 min-w-0 flex-1">
                                    <span class="block font-bold text-gray-800 uppercase text-xs truncate"><?php echo e(str_replace('_', ' ', $slug)); ?></span>
                                    <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest"><?php echo e($isAdmin ? 'Gestionar' : 'Materiales'); ?> →</span>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full py-12 text-center border-2 border-dashed border-gray-200 rounded-3xl">
                                <p class="text-gray-400 italic text-sm">Aún no eres miembro de ningún grupo activo.</p>
                                <a href="<?php echo e(route('grupos.index')); ?>" class="text-button font-black uppercase text-[10px] tracking-widest mt-4 inline-block hover:underline">Explorar grupos →</a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <?php if($isMember): ?>
                    <a href="<?php echo e(route('diario.index')); ?>" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-2xl"><img src="<?php echo e(asset('img/icono_biblia.png')); ?>" class="w-8 h-8"></div>
                        <div><span class="block font-black text-gray-800 text-xs uppercase tracking-tight">Diario Espiritual</span><span class="text-[10px] text-gray-400 font-medium">Tus reflexiones</span></div>
                    </a>
                    <?php endif; ?>

                    <a href="<?php echo e(url('/intenciones')); ?>" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-2xl"><img src="<?php echo e(asset('img/icono_intenciones.png')); ?>" class="w-8 h-8"></div>
                        <div><span class="block font-black text-gray-800 text-xs uppercase tracking-tight">Intenciones</span><span class="text-[10px] text-gray-400 font-medium">Sube tus peticiones</span></div>
                    </a>

                    
                    <a href="<?php echo e(route('donations.create')); ?>" class="bg-white p-6 rounded-3xl border border-gray-100 shadow-sm hover:shadow-md transition-all flex items-center gap-4">
                        <div class="p-3 bg-blue-50 rounded-2xl"><img src="<?php echo e(asset('img/icono_donaciones.png')); ?>" class="w-8 h-8"></div>
                        <div><span class="block font-black text-gray-800 text-xs uppercase tracking-tight">Donaciones</span><span class="text-[10px] text-gray-400 font-medium">Apoya a la iglesia</span></div>
                    </a>
                </div>
            </div>

            
            <div class="lg:col-span-1 space-y-8 text-left">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 text-center relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1 bg-button"></div>
                    <div class="w-20 h-20 bg-blue-900 text-white rounded-2xl flex items-center justify-center text-3xl font-black mx-auto mb-4 uppercase shadow-lg"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                    <h4 class="font-black text-gray-800 uppercase tracking-tighter"><?php echo e(Auth::user()->name); ?></h4>
                    <p class="text-[10px] text-gray-400 font-black uppercase mt-1">Edad: <?php echo e(Auth::user()->age ?? 'N/A'); ?> años</p>
                    <a href="<?php echo e(route('profile.show')); ?>" class="block w-full py-3 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-600 hover:bg-gray-100 transition-colors mt-4">Ver Perfil</a>
                </div>
            </div>
        </div>

        
        <section class="py-12 mb-12 border-t border-gray-100">
            <h2 class="text-2xl md:text-3xl font-black text-center text-text-dark mb-10 border-b-2 border-black pb-2 uppercase tracking-tighter">Avisos Parroquiales</h2>
            <?php if(isset($announcements) && $announcements->count() > 0): ?>
            <div class="carousel-outer-container relative max-w-6xl mx-auto px-4">
                <div class="overflow-hidden py-4" id="carouselContainer">
                    <div class="flex transition-transform duration-500 ease-out" id="carouselTrack">
                        <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $announcement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="announcement-wrapper flex-shrink-0 px-3 box-border flex justify-center w-full md:w-1/2 lg:w-1/3">
                            <div class="announcement-card bg-white rounded-3xl overflow-hidden shadow-sm text-center flex flex-col h-full border border-gray-100 w-full group hover:shadow-xl transition-all duration-300">
                                <div class="w-full aspect-video bg-gray-50 flex items-center justify-center overflow-hidden relative">
                                    <?php if($announcement->image_url): ?> <img src="<?php echo e($announcement->image_url); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"> <?php endif; ?>
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
                    <button class="bg-white text-button border-2 border-button/10 w-12 h-12 rounded-2xl cursor-pointer flex items-center justify-center hover:text-white hover:bg-blue-900 transition-all shadow-sm" id="prevBtn"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg></button>
                    <button class="bg-white text-button border-2 border-button/10 w-12 h-12 rounded-2xl cursor-pointer flex items-center justify-center hover:text-white hover:bg-blue-900 transition-all shadow-sm" id="nextBtn"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg></button>
                </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>

        
        <section class="py-12 pb-20 border-t border-gray-100">
            <h2 class="text-2xl md:text-3xl font-black text-center text-text-dark mb-10 border-b-2 border-black pb-2 uppercase tracking-tighter">Horarios de la Parroquia</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 text-left">
                
                <div class="schedule-card bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-auto overflow-hidden">
                    <div class="schedule-header p-8 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default transition-colors group bg-gray-50 border-b border-gray-100 hover:bg-white">
                        <div class="flex items-center gap-4 md:flex-col md:gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform"><img src="<?php echo e(asset('img/icono_misas.png')); ?>" class="h-9 w-auto"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-black">Misas</h4>
                        </div>
                        <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    <div class="schedule-content hidden md:block p-8 text-center flex-1">
                        <div class="space-y-6 text-text-dark">
                            <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Lunes a Sábado</span><p class="text-gray-800 font-bold text-sm">10:00 | 17:30 | 19:30</p></div>
                            <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Domingo</span><p class="text-gray-800 font-bold text-sm leading-relaxed">08:00 | 09:30 | 11:00<br>12:30 | 18:00 | 19:00<br>20:00</p></div>
                        </div>
                    </div>
                </div>
                
                <div class="schedule-card bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-auto overflow-hidden">
                    <div class="schedule-header p-8 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default transition-colors group bg-gray-50 border-b border-gray-100 hover:bg-white">
                        <div class="flex items-center gap-4 md:flex-col md:gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform"><img src="<?php echo e(asset('img/icono_confesiones.png')); ?>" class="h-9 w-auto"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-black">Confesiones</h4>
                        </div>
                        <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    <div class="schedule-content hidden md:block p-8 text-center flex-1">
                        <span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Lunes a Sábado</span>
                        <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100"><p class="text-gray-800 font-bold text-sm">10:30 a 12:00<br>18:00 a 19:00</p></div>
                        <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Domingo</span><p class="text-gray-800 font-bold text-sm leading-relaxed">Durante las misas</p></div>
                    </div>
                </div>
                
                <div class="schedule-card bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-auto overflow-hidden">
                    <div class="schedule-header p-8 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default transition-colors group bg-gray-50 border-b border-gray-100 hover:bg-white">
                        <div class="flex items-center gap-4 md:flex-col md:gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform"><img src="<?php echo e(asset('img/icono_secretaria.png')); ?>" class="h-9 w-auto"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-black">Secretaría</h4>
                        </div>
                        <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    <div class="schedule-content hidden md:block p-8 text-center flex-1">
                        <span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Lunes a Viernes</span>
                        <p class="text-gray-800 font-bold text-sm mb-4">16:00 a 19:00</p>
                        <p class="text-button font-bold text-[12px] hover:underline">secretaria@inmaculada.org.ar</p>
                    </div>
                </div>
                
                <div class="schedule-card bg-white rounded-3xl shadow-sm border border-gray-100 flex flex-col h-auto overflow-hidden">
                    <div class="schedule-header p-8 flex flex-row md:flex-col items-center justify-between md:justify-center cursor-pointer md:cursor-default transition-colors group bg-gray-50 border-b border-gray-100 hover:bg-white">
                        <div class="flex items-center gap-4 md:flex-col md:gap-6">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform"><img src="<?php echo e(asset('img/icono_donaciones.png')); ?>" class="h-9 w-auto"></div>
                            <h4 class="text-xs font-black uppercase tracking-widest text-black">Donaciones</h4>
                        </div>
                        <div class="schedule-chevron md:hidden text-gray-400 transition-transform duration-300"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                    </div>
                    <div class="schedule-content hidden md:block p-8 text-center flex-1">
                        <div class="space-y-6 text-text-dark">
                            <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">La Ropa y los alimentos traerlas por el templo</span></div>
                            <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Lunes a Sábado</span><p class="text-gray-800 font-bold text-sm">09:00 a 21:00</p></div>
                            <div><span class="block font-black text-gray-400 text-[10px] uppercase mb-2 tracking-widest">Domingo</span><p class="text-gray-800 font-bold text-sm leading-relaxed">07:30 a 21:30</p></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

<?php echo $__env->make('partials.onboarding-modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/dashboard.blade.php ENDPATH**/ ?>