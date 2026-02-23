

<?php $__env->startSection('content'); ?>
<style>
    .mobile-content {
        max-height: 0;
        opacity: 0;
        transition: max-height 0.7s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.5s ease;
        overflow: hidden;
    }
    .group-card.is-open .mobile-content {
        max-height: 500px;
        opacity: 1;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 1024px) {
        .mobile-content {
            max-height: none !important;
            opacity: 1 !important;
            display: block !important;
        }
    }
    .rotate-icon { transition: transform 0.3s ease; }
    .group-card.is-open .rotate-icon { transform: rotate(180deg); }
</style>

<div class="w-full">
    <div class="text-center mt-12 mb-18 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter">Más Grupos</h1>
            <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2 pb-6">
                Servicio, caridad y misiones especiales de nuestra parroquia.
            </p>
        </div>
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="<?php echo e(route('grupos.mayores')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Mayores</span>
        </a>
        <a href="<?php echo e(route('grupos.catequesis')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Catequesis</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto px-4 pb-20">
        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/grupo_caritas.png')); ?>" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-50');" alt="Cáritas" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Cáritas</h3>
                </div>
                
                <div class="mobile-content">
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mb-4">Servicio</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Sábados 17:30 hs</span>
                    <p class="text-text-light text-sm leading-relaxed mb-4">Asistencia y promoción humana para familias necesitadas.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'caritas', 'nombre' => 'Cáritas'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/grupo_comedor.jpg')); ?>" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-50');" alt="Comedor Parroquial" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Comedor</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mb-4">Servicio</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Viernes y Sabados 19:30hs</span>
                    <p class="text-text-light text-sm leading-relaxed">Servicio de alimentación para personas en situación de calle.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'comedor', 'nombre' => 'Comedor Parroquial'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/grupo_caridad.jpg')); ?>" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-50');" alt="Noche de Caridad" class="w-full h-full object-cover">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Noche de Caridad</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mb-4">Servicio Nocturno</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Jueves 20:00 hs</span>
                    <p class="text-text-light text-sm leading-relaxed">Recorrida nocturna para acompañar y asistir con alimentos a hermanos en situación de calle.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'caridad', 'nombre' => 'Noche de Caridad'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-8 md:mt-12">
            <a href="<?php echo e(route('grupos.index')); ?>" class="inline-flex items-center bg-white text-button px-8 py-3 rounded-full font-black uppercase text-[10px] tracking-widest border-2 border-button hover:bg-button hover:text-white transition-all shadow-lg active:scale-95 mb-20">
                Ver Todos los Grupos
            </a>
        </div>
</div>

<script>
    function toggleCard(card) {
        if (window.innerWidth < 1024) {
            document.querySelectorAll('.group-card').forEach(c => { if(c !== card) c.classList.remove('is-open'); });
            card.classList.toggle('is-open');
        }
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/especiales.blade.php ENDPATH**/ ?>