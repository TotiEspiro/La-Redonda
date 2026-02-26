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

    /* Estilos para el bloqueo visual del botón original */
    .join-btn-visual-lock {
        opacity: 1; 
        filter: grayscale(1);
        transition: all 0.5s ease;
    }

    /* Animación de la modal */
    .animate-modal-pop {
        animation: modalPop 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    @keyframes modalPop {
        0% { transform: scale(0.9); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }

    /* Colores para botones Si/No en la modal con el celeste solicitado */
    .btn-status-active-si {
        background-color: #5cb1e3 !important;
        color: white !important;
        border-color: #5cb1e3 !important;
        box-shadow: 0 4px 12px rgba(92, 177, 227, 0.3);
    }
    .btn-status-active-no {
        background-color: #ef4444 !important;
        color: white !important;
        border-color: #ef4444 !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    /* Estilo para el botón validado (Check) */
    .btn-info-validated {
        background-color: #f0f9ff !important;
        color: #5cb1e3 !important;
        border-color: #5cb1e3 !important;
        cursor: default;
    }
</style>

<div class="text-center mt-12 mb-18 md:mb-12">
        <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter">Catequesis</h1>
        <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2 pb-6">
            Formación sacramental para niños, adolescentes y adultos.
        </p>
    </div>

<div class="w-full">
    <div class="flex items-center justify-between mb-10 bg-white p-4 rounded-2xl shadow-sm border border-gray-100 max-w-6xl mx-auto mt-6">
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

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-6xl mx-auto px-4 pb-20">
        <?php 
            $grupos = [
                [
                    'slug' => 'catequesis_ninos', 
                    'nombre' => 'Catequesis Niños', 
                    'edad' => '7 a 12 años', 
                    'img' => 'catequesis_niños.jpg', 
                    'link' => '',
                    'desc' => 'En el grupo de catequesis preparamos a las familias, tanto a los chicos como a los padres al mismo tiempo.<br><br>La catequesis de primera comunión es un proceso que dura dos años, donde los chicos asisten semanalmente junto a sus padres o algún familiar para que este los acompañe en el camino de la fe.'
                ],
                [
                    'slug' => 'confirmacion', 
                    'nombre' => 'Catequesis Adolescentes', 
                    'edad' => '13 a 17 años', 
                    'img' => 'catequesis_adolescentes.png', 
                    'link' => '',
                    'desc' => 'Crecimiento en el Espíritu Santo y vida comunitaria para jóvenes de secundaria.'
                ],
                [
                    'slug' => 'catequesis_adultos', 
                    'nombre' => 'Catequesis Adultos', 
                    'edad' => 'Mayores de 18', 
                    'img' => 'catequesis_mayores.jpg', 
                    'link' => '',
                    'desc' => 'Iniciación cristiana, sacramentos y profundización en la fe para adultos.'
                ],
            ];

            $userRequests = Auth::check() 
                ? \DB::table('group_requests')->where('user_id', Auth::id())->pluck('status', 'group_role')->toArray()
                : [];
        ?>

        <?php $__currentLoopData = $grupos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php 
                $status = $userRequests[$g['slug']] ?? null;
                $isValidated = ($status === 'pending' || $status === 'approved');
            ?>
            
            <div class="group-card group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full cursor-pointer lg:cursor-default" onclick="toggleCard(this)">
                <div class="w-full bg-gray-100 md:h-64 flex items-center justify-center overflow-hidden">
                    <img src="<?php echo e(asset('img/'.$g['img'])); ?>" class="w-full h-full object-cover" onerror="this.src='<?php echo e(asset('img/logo_redonda.png')); ?>'; this.classList.add('p-12', 'opacity-20')">
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-text-dark text-lg uppercase tracking-tight"><?php echo e($g['nombre']); ?></h3>
                    </div>

                    <div class="mobile-content">
                        <div class="flex flex-wrap gap-2 mb-4">
                            <span class="text-[10px] text-button font-black uppercase bg-blue-50 px-2 py-1 rounded-full w-fit inline-block"><?php echo e($g['edad']); ?></span>
                        </div>
                        <p class="text-text-light text-sm leading-relaxed mb-4"><?php echo $g['desc']; ?></p>
                    </div>

                    <div class="mt-auto flex flex-col gap-3">
                        <div class="flex gap-2">
                            
                            <button id="info-btn-<?php echo e($g['slug']); ?>" 
                                    onclick="openCoordinatorModal(event, '<?php echo e($g['slug']); ?>', '<?php echo e($g['nombre']); ?>', '<?php echo e($g['link']); ?>')" 
                                    class="<?php echo \Illuminate\Support\Arr::toCssClasses([
                                        'inline-flex items-center justify-center px-6 py-3 rounded-2xl font-black uppercase text-base tracking-widest border-2 transition-all shadow-lg active:scale-95',
                                        'bg-white text-button border-button hover:bg-button hover:text-white' => !$isValidated,
                                        'btn-info-validated' => $isValidated
                                    ]); ?>">
                                <?php if($isValidated): ?>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path d="M5 13l4 4L19 7"></path></svg>
                                <?php else: ?>
                                    <img src="<?php echo e(asset('img/icono_info.png')); ?>" alt="Info" class="w-5 h-5">
                                <?php endif; ?>
                            </button>

                            <div class="flex-1 relative" id="container-<?php echo e($g['slug']); ?>">
                                <?php if(!$isValidated): ?>
                                    <div id="shield-<?php echo e($g['slug']); ?>" class="absolute inset-0 z-20 cursor-not-allowed" onclick="event.stopPropagation()"></div>
                                <?php endif; ?>
                                
                                <div id="visual-<?php echo e($g['slug']); ?>" class="<?php echo \Illuminate\Support\Arr::toCssClasses(['join-btn-visual-lock' => !$isValidated]); ?>" onclick="event.stopPropagation()">
                                    <?php echo $__env->make('partials.group-join-button', ['slug' => $g['slug'], 'nombre' => $g['nombre']], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="lg:hidden text-center">
                            <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest flex items-center justify-center gap-1">
                                Toca para más info <svg class="w-2 h-2 rotate-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>

    <div class="text-center mt-8 md:mt-12">
        <a href="<?php echo e(route('grupos.index')); ?>" class="inline-flex items-center bg-white text-button px-8 py-3 rounded-full font-black uppercase text-[10px] tracking-widest border-2 border-button hover:bg-button hover:text-white transition-all shadow-lg active:scale-95 mb-20">
            Ver Todos los Grupos
        </a>
    </div>
</div>


<div id="coordinatorModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-6 bg-black/80 backdrop-blur-md">
    <div class="bg-white rounded-[3.5rem] w-full max-w-sm overflow-hidden shadow-2xl animate-modal-pop p-10 flex flex-col items-center text-center">
        
        <div class="w-20 h-20 rounded-[2rem] flex items-center justify-center mb-8">
            <div class=" rounded-2xl flex items-center justify-center">
                <div class="text-[#5cb1e3]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <img src="<?php echo e(asset('img/logo_nav_redonda.png')); ?>" alt="La Redonda" class="w-16 h-16">
                </div>
            </div>
        </div>

        <h3 class="text-2xl font-black text-text-dark uppercase tracking-tighter mb-3 leading-tight">¿Hablaste con nosotros?</h3>
        
        <p class="text-sm text-gray-500 font-medium leading-relaxed mb-8 px-4">
            Para sumarte a <strong id="modalGroupName" class="text-[#5cb1e3] italic">Grupo</strong>, primero debés contactar a la coordinación.
        </p>

        
        <a id="coordinatorLink" href="https://www.instagram.com/direct/t/105222797545921/" target="_blank" 
           class="w-full flex items-center justify-center gap-4 py-4 px-6 bg-gradient-to-br from-white to-sky-50 border-2 border-sky-100 rounded-2xl shadow-sm hover:shadow-md hover:bg-sky-100 transition-all group mb-10">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center border border-sky-100 group-hover:scale-110 transition-transform">
                <img src="<?php echo e(asset('img/icono_instagram.png')); ?>" class="w-6 h-6 object-contain" alt="Instagram" onerror="this.src='https://www.svgrepo.com/show/521711/instagram.svg'">
            </div>
            <div class="text-left">
                <span class="block text-[11px] font-black text-[#5cb1e3] uppercase tracking-widest leading-none mb-1">Coordinación</span>
                <span class="block text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Enviar mensaje directo</span>
            </div>
        </a>

        <div class="w-full space-y-4 mb-8">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em]">¿Confirmas el contacto previo?</p>
            <div class="flex gap-3">
                <button type="button" id="btnModalSi" onclick="setModalContactStatus(true)" 
                        class="flex-1 py-4 border-2 bg-sky-50 border-sky-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all hover:bg-sky-100">
                    SÍ
                </button>
                <button type="button" id="btnModalNo" onclick="setModalContactStatus(false)" 
                        class="flex-1 py-4 border-2 bg-red-50 border-red-100 rounded-2xl font-black text-[10px] uppercase tracking-widest transition-all hover:bg-red-100">
                    NO
                </button>
            </div>
        </div>

        <button type="button" id="btnModalConfirm" onclick="unlockJoinButton()" 
                class="w-full py-5 bg-gray-100 text-gray-400 rounded-[1.8rem] font-black text-xs uppercase tracking-[0.2em] shadow-xl pointer-events-none opacity-50 transition-all transform active:scale-95">
            Confirmar Acceso
        </button>

        <button onclick="closeCoordinatorModal()" class="mt-8 text-[9px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-colors">
            Cerrar Ventana
        </button>
    </div>
</div>

<script>
    let currentGroupSlug = null;

    function toggleCard(card) {
        if (window.innerWidth < 1024) {
            document.querySelectorAll('.group-card').forEach(c => {
                if(c !== card) c.classList.remove('is-open');
            });
            card.classList.toggle('is-open');
        }
    }

    function openCoordinatorModal(event, slug, name, link) {
        event.stopPropagation();
        
        const btn = document.getElementById('info-btn-' + slug);
        if (btn.classList.contains('btn-info-validated')) return;

        currentGroupSlug = slug;
        const modal = document.getElementById('coordinatorModal');
        document.getElementById('modalGroupName').innerText = name;
        const modalLink = document.getElementById('coordinatorLink');
        modalLink.href = link || 'https://www.instagram.com/direct/t/105222797545921/';
        
        setModalContactStatus(null);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeCoordinatorModal() {
        document.getElementById('coordinatorModal').classList.add('hidden');
        document.getElementById('coordinatorModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    function setModalContactStatus(status) {
        const btnSi = document.getElementById('btnModalSi');
        const btnNo = document.getElementById('btnModalNo');
        const btnConfirm = document.getElementById('btnModalConfirm');

        btnSi.classList.remove('btn-status-active-si');
        btnNo.classList.remove('btn-status-active-no');

        if (status === true) {
            btnSi.classList.add('btn-status-active-si');
            btnConfirm.classList.remove('bg-gray-100', 'text-gray-400', 'pointer-events-none', 'opacity-50');
            btnConfirm.classList.add('bg-blue-900', 'text-white');
        } else if (status === false) {
            btnNo.classList.add('btn-status-active-no');
            btnConfirm.classList.add('bg-gray-100', 'text-gray-400', 'pointer-events-none', 'opacity-50');
            btnConfirm.classList.remove('bg-blue-900', 'text-white');
        }
    }

    function unlockJoinButton() {
        if (!currentGroupSlug) return;

        const infoBtn = document.getElementById('info-btn-' + currentGroupSlug);
        if (infoBtn) {
            infoBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path d="M5 13l4 4L19 7"></path></svg>';
            infoBtn.classList.remove('text-button', 'border-button', 'hover:bg-button', 'hover:text-white');
            infoBtn.classList.add('btn-info-validated');
        }

        const shield = document.getElementById('shield-' + currentGroupSlug);
        if (shield) shield.remove();

        const visual = document.getElementById('visual-' + currentGroupSlug);
        if (visual) {
            visual.classList.remove('join-btn-visual-lock');
            visual.style.opacity = '1';
            visual.style.filter = 'none';
        }

        closeCoordinatorModal();
    }

    window.onclick = function(event) {
        const modal = document.getElementById('coordinatorModal');
        if (event.target == modal) closeCoordinatorModal();
    }
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\copia_laredo\La-Redonda\resources\views/grupos/catequesis.blade.php ENDPATH**/ ?>