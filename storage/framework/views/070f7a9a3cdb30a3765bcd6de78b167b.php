<div class="w-full">
    
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto">
        <a href="<?php echo e(route('grupos.especiales')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[-4px] transition-transform group">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
            </div>
            <span class="hidden sm:inline">Más Grupos</span>
        </a>
        <a href="<?php echo e(route('grupos.jovenes')); ?>" class="flex items-center gap-2 text-button font-black uppercase text-[10px] tracking-widest hover:translate-x-[4px] transition-transform group">
            <span class="hidden sm:inline">Jóvenes</span>
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center group-hover:bg-button group-hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
            </div>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 justify-center items-stretch max-w-7xl mx-auto">
        
        
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/catequesis_niños.jpg')); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Niños</h3>
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mt-1">Niños (6 a 12 años)</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Preparación para Primera Comunión. Un camino de fe y encuentro para toda la familia y los niños.</p>
                <?php echo $__env->make('partials.group-join-button', ['slug' => 'catequesis_niños', 'nombre' => 'Catequesis Familiar'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

        
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/catequesis_adolescentes.png')); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Adolescentes</h3>
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mt-1">Adolescentes (13 a 17 años)</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Fortalecimiento en el Espíritu Santo y vida comunitaria para jóvenes de secundaria.</p>
                <?php echo $__env->make('partials.group-join-button', ['slug' => 'confirmacion', 'nombre' => 'Confirmación'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>

        
        <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
            <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                <img src="<?php echo e(asset('img/catequesis_mayores.jpg')); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-20')">
            </div>
            <div class="p-6 flex flex-col flex-grow">
                <div class="flex flex-col mb-4">
                    <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight">Catequesis Adultos</h3>
                    <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit mt-1">Mayores de 18</span>
                </div>
                <p class="text-text-light text-sm mb-6 flex-grow leading-relaxed">Iniciación cristiana, sacramentos y profundización en la fe para adultos y servidores.</p>
                <?php echo $__env->make('partials.group-join-button', ['slug' => 'catequesis_adultos', 'nombre' => 'Catequesis Adultos'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>
</div><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/grupos/catequesis.blade.php ENDPATH**/ ?>