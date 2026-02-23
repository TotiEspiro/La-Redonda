

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
            overflow: visible !important;
        }
    }
    .rotate-icon { transition: transform 0.3s ease; }
    .group-card.is-open .rotate-icon { transform: rotate(180deg); }
</style>

<div class="w-full">
   <div class="text-center mt-12 mb-18 md:mb-12">
        <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter">Jóvenes</h1>
        <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2 pb-6">
            Comunidad, formación y espiritualidad para chicos de 11 a 35 años.
        </p>
    </div>
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="<?php echo e(route('grupos.catequesis')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Catequesis</span>
        </a>
        <a href="<?php echo e(route('grupos.mayores')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Mayores</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto px-4 pb-20">
        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="<?php echo e(asset('img/grupos_juveniles.jpg')); ?>" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Juveniles</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">12 a 17 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap"> Viernes 17:00hs a 19:00hs</span>
                    <p class="text-text-light text-sm leading-relaxed mb-4">Buscamos que los chicos sigan creciendo en la fe de una manera más en comunidad y que la puedan compartir con chicos de su edad!</p>
                    <p class="text-text-light text-sm leading-relaxed mb-4">Hacemos dinámicas, momentos de oración y también de recreación para que vayan formando una amistad entre ellos!</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'juveniles', 'nombre' => 'Juveniles'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="<?php echo e(asset('img/grupo_acutis.jpg')); ?>" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">San Carlo Acutis</h3>
                </div>
                
                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">18 a 24 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Sabado 17:30hs</span>
                    <div class="text-text-light text-sm leading-relaxed mb-4"><strong>• Comunidad:</strong> La Fe no se vive solo, se trata de vivir la alegría de un Dios vivo junto con el otro.</p>
                        <p><strong>• Formación:</strong> Crecer en las cosas de Dios mediante charlas y testimonios.</p>
                        <p><strong>• Oración:</strong> Fundamental para seguir creciendo en el vínculo con Dios.</p>
                    </div>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'acutis', 'nombre' => 'Grupo Acutis'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="<?php echo e(asset('img/grupo_juanpablo.png')); ?>" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Juan Pablo II</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">25 a 35 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Miércoles 19:00 hs</span>
                    <p class="text-text-light text-sm leading-relaxed">Un espacio para madurar la fe en comunidad, compartir experiencias de vida y servicio.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'juan_pablo', 'nombre' => 'Grupo Juan Pablo II'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-300 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="<?php echo e(asset('img/grupo_coro.jpg')); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-20')"></div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Coro</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">18 a 30 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Ensayos previos a las misas</span>
                    <p class="text-text-light text-sm leading-relaxed mb-4">Grupo de jóvenes y adultos que animan las celebraciones litúrgicas a través de la música y el canto, creando un ambiente de alegría.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'coro', 'nombre' => 'Coro Parroquial'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="lg:hidden text-center"><span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg></span></div>
                </div>
            </div>
        </div>

        
        <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
            <div class="w-full bg-gray-100 md:h-64 overflow-hidden"><img src="<?php echo e(asset('img/grupo_misionero.jpg')); ?>" class="w-full h-full object-cover"></div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Grupo Misionero</h3>
                </div>

                <div class="mobile-content">
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mb-4 inline-block">18 a 35 años</span>
                    <span class="text-[9px] font-black text-button bg-blue-50 px-2 py-1 rounded-lg uppercase tracking-wider ml-2 whitespace-nowrap">Viernes 19hs</span>
                    <p class="text-text-light text-sm leading-relaxed mb-4">Llevamos el Evangelio a otros barrios y comunidades a través del servicio y la alegría.</p>
                </div>

                <div class="mt-auto flex flex-col gap-3">
                    <?php echo $__env->make('partials.group-join-button', ['slug' => 'misioneros', 'nombre' => 'Grupo Misionero'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/jovenes.blade.php ENDPATH**/ ?>