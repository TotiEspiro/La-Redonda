<style>
    /* Scrollbar minimalista */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    
    /* Animación de entrada suave */
    .slide-up-fade { 
        animation: slideUpFade 0.25s cubic-bezier(0.4, 0, 0.2, 1) forwards; 
    }
    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Acordeones */
    .accordion-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s ease;
        opacity: 0;
    }
    .accordion-content.active {
        max-height: 1000px; 
        opacity: 1;
    }

    .nav-main-container { transition: all 0.3s ease; }

    @media (max-width: 767px) {
        .nav-main-container {
            background-color: rgba(224, 242, 254, 0.92) !important;
            backdrop-filter: blur(8px);
        }
    }

    @media (min-width: 768px) {
        .nav-main-container {
            background-color: #a4e0f3 !important;
            border-bottom: 2px solid #38bdf8 !important;
        }
    }

    .grid-menu-item {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        min-height: 48px;
        padding: 0.5rem;
    }

    #pcLine1, #pcLine2, #pcLine3 {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.2s ease;
        transform-origin: center;
    }
</style>

<?php
    /**
     * LISTA MAESTRA DE GRUPOS (Sincronizada con DB)
     */
    $allGroupSlugs = [
        'catequesis_niños', 'catequesis_adolescentes', 'catequesis_adultos', 
        'acutis', 'juveniles', 'juan_pablo', 'coro', 'misioneros', 
        'santa_ana', 'san_joaquin', 'ardillas', 'costureras', 
        'comedor', 'caritas', 'caridad'
    ];

    $unreadNotifications = collect();
    if (Auth::check()) {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $unreadNotifications = $user->unreadNotifications;
    }

    $inicioUrl = Auth::check() ? route('dashboard') : url('/');
?>

<nav class="nav-main-container sticky top-0 z-50 py-3 md:py-6 shadow-sm">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center">      
            <div class="flex items-center">
                <a href="<?php echo e(route('home')); ?>" class="flex-shrink-0 transition-transform active:scale-95">
                    <img class="h-9 md:h-12 w-auto" src="<?php echo e(asset('img/logo_redonda_texto.png')); ?>" alt="La Redonda">
                </a>
            </div>

            <div class="flex items-center space-x-2 md:space-x-6">
                <?php if(auth()->guard()->check()): ?>
                
                <div class="relative">
                    <button onclick="toggleNotifications(event)" class="relative p-2 text-gray-600 hover:bg-sky-50 rounded-full transition-all focus:outline-none">
                        <img src="<?php echo e(asset('img/icono_campana.png')); ?>" class="w-6 h-6" alt="Notificaciones">
                        <?php if($unreadNotifications->count() > 0): ?>
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-[9px] font-black text-white bg-red-500 rounded-full border-2 border-white">
                                <?php echo e($unreadNotifications->count()); ?>

                            </span>
                        <?php endif; ?>
                    </button>

                    <div id="notiPanel" class="hidden absolute right-0 mt-4 w-72 md:w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 z-[60] overflow-hidden slide-up-fade">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100 flex justify-between items-center">
                            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Notificaciones</span>
                            <?php if($unreadNotifications->count() > 0): ?>
                                <button onclick="markAllAsRead()" class="text-[10px] font-bold text-button hover:underline">Limpiar todo</button>
                            <?php endif; ?>
                        </div>
                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            <?php $__empty_1 = true; $__currentLoopData = $unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <?php 
                                    $nData = is_array($n->data) ? $n->data : json_decode($n->data, true); 
                                    // CORRECCIÓN: Priorizar 'url', luego 'link', y si no hay nada o es '#', ir al dashboard
                                    $rawUrl = $nData['url'] ?? ($nData['link'] ?? null);
                                    $notiUrl = ($rawUrl && $rawUrl !== '#') ? $rawUrl : route('dashboard');
                                ?>
                                <a href="<?php echo e($notiUrl); ?>" class="block px-4 py-4 hover:bg-blue-50 border-b border-gray-50 transition-colors">
                                    <p class="text-xs font-bold text-gray-900 leading-tight mb-1"><?php echo e($nData['title'] ?? ($nData['titulo'] ?? 'Aviso')); ?></p>
                                    <p class="text-[11px] text-gray-500 line-clamp-2"><?php echo e($nData['message'] ?? ($nData['mensaje'] ?? '')); ?></p>
                                    <span class="text-[9px] text-gray-400 mt-2 block font-medium"><?php echo e($n->created_at->diffForHumans()); ?></span>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="py-12 text-center text-gray-300 font-bold uppercase text-[10px] tracking-tighter">Sin novedades</div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <a href="<?php echo e(url('/donaciones')); ?>" class="hidden md:inline-block bg-button text-white px-6 py-2 rounded-lg font-bold text-base hover:bg-blue-900 transition-all shadow-sm">Donaciones</a>

                <div class="hidden md:block relative">
                    <button class="flex relative w-10 h-10 flex-col items-center justify-center cursor-pointer z-50 focus:outline-none space-y-1.5 bg-white/50 rounded-xl" id="desktopHamburgerBtn" onclick="toggleDesktopMenu(event)">
                        <span class="block w-6 h-0.5 bg-text-dark" id="pcLine1"></span>
                        <span class="block w-6 h-0.5 bg-text-dark" id="pcLine2"></span>
                        <span class="block w-6 h-0.5 bg-text-dark" id="pcLine3"></span>
                    </button>

                    <div id="desktopMenu" class="hidden absolute right-0 mt-3 w-72 bg-white rounded-2xl shadow-2xl border border-gray-100 z-50 overflow-hidden slide-up-fade">
                        <div class="max-h-[85vh] overflow-y-auto custom-scrollbar">
                            
                            <?php if(Auth::check() && Auth::user()->isAdmin()): ?>
                            <div class="p-3 bg-yellow-400">
                                <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center justify-center w-full py-3 bg-white/20 hover:bg-white/30 border border-white/30 rounded-xl text-black text-[11px] font-black uppercase tracking-widest transition-all">
                                    <img src="<?php echo e(asset('img/icono_admin.png')); ?>" class="w-4 h-4 mr-2"> Panel de Administrador
                                </a>
                            </div>
                            <?php endif; ?>

                            <div class="py-2 border-b border-gray-50">
                                <a href="<?php echo e($inicioUrl); ?>" class="block px-5 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 transition uppercase">Inicio</a>
                                
                                
                                <div class="border-t border-gray-50">
                                    <button class="w-full flex items-center justify-between px-5 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 transition uppercase" onclick="toggleAccordionSmooth('pcGruposNav', 'pcGruposNavArr')">
                                        <span>Explorar Grupos</span>
                                        <span id="pcGruposNavArr" class="text-[10px]">▸</span>
                                    </button>
                                    <div id="pcGruposNav" class="accordion-content bg-gray-50/50 px-3">
                                        <a href="<?php echo e(route('grupos.index')); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 uppercase mb-1">Ver Todos</a>
                                        <a href="<?php echo e(route('grupos.catequesis')); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 uppercase mb-1">Catequesis</a>
                                        <a href="<?php echo e(route('grupos.jovenes')); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 uppercase mb-1">Jóvenes</a>
                                        <a href="<?php echo e(route('grupos.mayores')); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 uppercase mb-1">Mayores</a>
                                        <a href="<?php echo e(route('grupos.especiales')); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 uppercase mb-1">Más Grupos</a>
                                    </div>
                                </div>

                                
                                <a href="<?php echo e(url('/intenciones')); ?>" class="block px-5 py-3 text-xs font-bold text-gray-700 hover:bg-blue-50 transition uppercase border-t border-gray-50">
                                    Intenciones
                                </a>
                            </div>

                            <?php if(auth()->guard()->check()): ?>
                                <?php
                                    $user = Auth::user();
                                    $userRoles = $user->roles;

                                    // Grupos donde el usuario es MIEMBRO
                                    $memberGroups = $userRoles->filter(fn($r) => in_array($r->name, $allGroupSlugs))
                                                             ->map(fn($r) => $r->name)->unique();
                                    
                                    // Grupos para GESTIÓN
                                    if($user->isSuperAdmin()) {
                                        $managedGroups = collect($allGroupSlugs)->map(fn($slug) => (object)[
                                            'name' => "admin_$slug",
                                            'display' => str_replace('_', ' ', $slug)
                                        ]);
                                    } else {
                                        $managedGroups = $userRoles->filter(fn($r) => str_starts_with($r->name, 'admin_') && $r->name !== 'admin_grupo_parroquial')
                                                                  ->map(fn($r) => (object)['name' => $r->name, 'display' => str_replace('admin_', '', $r->name)]);
                                    }

                                    $hasAccessToDiario = $user->isAdmin() || $memberGroups->isNotEmpty() || $managedGroups->isNotEmpty();
                                ?>
                                <div class="bg-gray-50/30">
                                    <?php if($hasAccessToDiario): ?>
                                        <a href="<?php echo e(route('diario.index')); ?>" class="flex items-center px-5 py-4 text-xs font-bold border-b uppercase border-gray-100 text-gray-700 hover:bg-blue-50 transition">
                                            <img src="<?php echo e(asset('img/icono_biblia.png')); ?>" class="w-4 h-4 mr-3"> Diario de La Redonda
                                        </a>
                                    <?php endif; ?>

                                    
                                    <?php if($memberGroups->isNotEmpty()): ?>
                                        <div class="border-b border-gray-50">
                                            <button class="w-full flex items-center justify-between px-5 py-4 text-xs font-black text-gray-700 hover:bg-blue-50 transition uppercase" onclick="toggleAccordionSmooth('pcComuSub', 'pcComuArr')">
                                                <span class="flex items-center"><img src="<?php echo e(asset('img/icono_grupos.png')); ?>" class="w-5 h-5 mr-3"> Mis Grupos</span>
                                                <span id="pcComuArr" class="text-[10px]">▸</span>
                                            </button>
                                            <div id="pcComuSub" class="accordion-content bg-gray-50/50 px-3">
                                                <?php $__currentLoopData = $memberGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $groupData = \App\Models\Group::where('category', $slug)->first();
                                                        // SEGURIDAD: Redirigir a verificación si hay clave y no se ha validado
                                                        $targetRoute = route('grupos.dashboard', $slug);
                                                        if ($groupData && $groupData->group_password && !session('group_unlocked_' . $slug)) {
                                                            $targetRoute = route('grupos.verify-form', $slug);
                                                        }
                                                    ?>
                                                    <a href="<?php echo e($targetRoute); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 truncate uppercase mb-1">
                                                        <?php if($groupData && $groupData->group_password && !session('group_unlocked_' . $slug)): ?>
                                                            🔒 <?php echo e(str_replace('_', ' ', $slug)); ?>

                                                        <?php else: ?>
                                                            <?php echo e(str_replace('_', ' ', $slug)); ?>

                                                        <?php endif; ?>
                                                    </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    
                                    <?php if($managedGroups->isNotEmpty()): ?>
                                        <div class="border-b border-gray-50">
                                            <button class="w-full flex items-center justify-between px-5 py-4 text-xs font-bold text-gray-700 hover:bg-blue-50 transition" onclick="toggleAccordionSmooth('pcGestionSub', 'pcGestionArr')">
                                                <span class="flex items-center uppercase">
                                                    <img src="<?php echo e(asset('img/icono_gestion.png')); ?>" class="w-5 h-5 mr-3"> 
                                                    <?php echo e($user->isSuperAdmin() ? 'Coordinación Total' : 'Gestión Parroquial'); ?>

                                                </span>
                                                <span id="pcGestionArr" class="text-[10px]">▸</span>
                                            </button>
                                            <div id="pcGestionSub" class="accordion-content bg-gray-50/50 px-3">
                                                <?php $__currentLoopData = $managedGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <a href="<?php echo e(route('grupos.dashboard', str_replace('admin_', '',  $g->name))); ?>" class="block p-2 text-[9px] font-bold border border-gray-100 rounded bg-white hover:bg-button hover:text-white text-gray-600 truncate uppercase mb-1"><?php echo e($g->display); ?></a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <a href="<?php echo e(route('profile.show')); ?>" class="flex items-center px-5 py-4 text-xs font-bold text-gray-700 hover:bg-blue-50 transition border-b border-gray-50 uppercase">
                                        <img src="<?php echo e(asset('img/icono_perfil.png')); ?>" class="w-4 h-4 mr-3"> Mi Perfil
                                    </a>
                                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0"><?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full text-center px-5 py-4 text-xs font-bold text-white bg-red-500 hover:bg-red-600 transition uppercase">Cerrar Sesión</button>
                                    </form>
                                </div>
                            <?php else: ?>
                                <div class="p-3 space-y-2">
                                    <a href="<?php echo e(route('login')); ?>" class="block w-full py-3 text-center text-xs font-bold text-black bg-nav-footer hover:text-button hover:bg-white
                                     rounded-xl border transition">Ingresar</a>
                                    <a href="<?php echo e(route('register')); ?>" class="block w-full py-3 text-center text-xs font-bold text-white bg-button hover:bg-blue-900 rounded-xl transition">Registrarse</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>


<div class="md:hidden">
    <nav class="fixed bottom-0 left-0 w-full bg-nav-footer border-t-2 border-sky-400 z-50 h-16 shadow-lg">
        <div class="flex justify-around items-center h-full">
            <a href="<?php echo e($inicioUrl); ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-600"><img src="<?php echo e(asset('img/icono_inicio.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Inicio</span></a>
            <a href="<?php echo e(route('grupos.index')); ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-600"><img src="<?php echo e(asset('img/icono_grupos.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Grupos</span></a>
            
            
            <a href="<?php echo e(url('/intenciones')); ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-600"><img src="<?php echo e(asset('img/icono_intenciones.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Intenciones</span></a>
            
            <a href="<?php echo e(url('/donaciones')); ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-600"><img src="<?php echo e(asset('img/icono_donaciones.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Donar</span></a>
            <?php if(auth()->guard()->check()): ?>
                <button id="bottomMenuTrigger" class="flex flex-col items-center justify-center w-full h-full text-gray-600 focus:outline-none"><img src="<?php echo e(asset('img/icono_perfil.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Cuenta</span></button>
            <?php else: ?>
                <a href="<?php echo e(route('login')); ?>" class="flex flex-col items-center justify-center w-full h-full text-gray-600"><img src="<?php echo e(asset('img/icono_perfil.png')); ?>" class="h-6 w-6 mb-1"><span class="text-[9px] font-black uppercase">Ingreso</span></a>
            <?php endif; ?>
        </div>
    </nav>

    <?php if(auth()->guard()->check()): ?>
    <div id="bottomMenuCard" class="hidden fixed bottom-20 right-4 left-4 bg-white rounded-3xl shadow-2xl border border-gray-100 z-50 slide-up-fade overflow-hidden">
        <div class="bg-button p-4 flex items-center justify-between text-white">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center font-black mr-3 uppercase"><?php echo e(substr(Auth::user()->name, 0, 1)); ?></div>
                <span class="text-sm font-black uppercase"><?php echo e(Auth::user()->name); ?></span>
            </div>
            <button id="closeBottomMenu" class="p-2">✕</button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto p-4 space-y-4 bg-gray-50/30">
            <?php if(Auth::user()->isAdmin()): ?>
            <a href="<?php echo e(route('admin.dashboard')); ?>" class="flex items-center w-full px-5 py-4 bg-yellow-400 text-black rounded-2xl shadow-md transition-transform active:scale-95">
                <img src="<?php echo e(asset('img/icono_admin.png')); ?>" class="w-6 h-6 mr-4"> <span class="text-xs font-black uppercase">Panel de Administrador</span>
            </a>
            <?php endif; ?>

            
            <?php if($memberGroups->isNotEmpty()): ?>
                <div class="bg-white border border-sky-100 rounded-2xl overflow-hidden">
                    <button onclick="toggleAccordionSmooth('mobMemberSub', 'mobMemberArr')" class="w-full flex justify-between items-center px-5 py-4 text-xs font-black text-gray-700 uppercase">
                        <span class="flex items-center"><img src="<?php echo e(asset('img/icono_grupos.png')); ?>" class="w-5 h-5 mr-3"> Mis Grupos</span>
                        <span id="mobMemberArr">▸</span>
                    </button>
                    <div id="mobMemberSub" class="accordion-content bg-white/50">
                        <div class="grid grid-cols-2 gap-2 p-2">
                            <?php $__currentLoopData = $memberGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $groupData = \App\Models\Group::where('category', $slug)->first();
                                    $targetRoute = route('grupos.dashboard', $slug);
                                    if ($groupData && $groupData->group_password && !session('group_unlocked_' . $slug)) {
                                        $targetRoute = route('grupos.verify-form', $slug);
                                    }
                                ?>
                                <a href="<?php echo e($targetRoute); ?>" class="grid-menu-item bg-white border border-gray-100 rounded-xl text-[8px] font-bold text-gray-500 uppercase shadow-sm">
                                    <?php if($groupData && $groupData->group_password && !session('group_unlocked_' . $slug)): ?>
                                        🔒 <?php echo e(str_replace('_', ' ', $slug)); ?>

                                    <?php else: ?>
                                        <?php echo e(str_replace('_', ' ', $slug)); ?>

                                    <?php endif; ?>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if($managedGroups->isNotEmpty()): ?>
                <div class="bg-white border border-blue-100 rounded-2xl overflow-hidden">
                    <button onclick="toggleAccordionSmooth('mobSuperSub', 'mobSuperArr')" class="w-full flex justify-between items-center px-5 py-4 text-xs font-black text-gray-700 uppercase">
                        <span class="flex items-center"><img src="<?php echo e(asset('img/icono_gestion.png')); ?>" class="w-5 h-5 mr-3"> Gestión Parroquial</span> 
                        <span id="mobSuperArr">▸</span>
                    </button>
                    <div id="mobSuperSub" class="accordion-content bg-white/50">
                        <div class="grid grid-cols-2 gap-2 p-2">
                            <?php $__currentLoopData = $managedGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $g): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('grupos.dashboard', str_replace('admin_', '', $g->name))); ?>" class="grid-menu-item bg-white border border-gray-100 rounded-xl text-[8px] font-bold text-gray-500 uppercase shadow-sm"><?php echo e($g->display); ?></a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if($hasAccessToDiario): ?>
                <a href="<?php echo e(route('diario.index')); ?>" class="flex items-center w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-gray-700 shadow-sm"><img src="<?php echo e(asset('img/icono_biblia.png')); ?>" class="w-6 h-6 mr-4"><span class="text-xs font-black uppercase">Diario de La Redonda</span></a>
            <?php endif; ?>
            
            <a href="<?php echo e(route('profile.show')); ?>" class="flex items-center w-full px-5 py-4 bg-white border border-gray-100 rounded-2xl text-gray-700 shadow-sm"><img src="<?php echo e(asset('img/icono_perfil.png')); ?>" class="h-6 h-6 mr-4"><span class="text-xs font-black uppercase">Mi Perfil</span></a>
            
            <form method="POST" action="<?php echo e(route('logout')); ?>" class="m-0"><?php echo csrf_field(); ?>
                <button type="submit" class="w-full py-4 bg-red-500 text-white rounded-2xl text-xs font-black uppercase">Cerrar Sesión</button>
            </form>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
    function toggleAccordionSmooth(id, arr) {
        const c = document.getElementById(id);
        const a = document.getElementById(arr);
        if(!c) return;
        const active = c.classList.toggle('active');
        if(a) a.style.transform = active ? 'rotate(90deg)' : 'rotate(0deg)';
    }

    function toggleNotifications(e) {
        e.stopPropagation();
        document.getElementById('notiPanel').classList.toggle('hidden');
        document.getElementById('desktopMenu')?.classList.add('hidden');
        resetHamburgerIcon();
    }

    function toggleDesktopMenu(e) {
        e.stopPropagation();
        const menu = document.getElementById('desktopMenu');
        const isHidden = menu.classList.toggle('hidden');
        document.getElementById('notiPanel')?.classList.add('hidden');
        const l1 = document.getElementById('pcLine1'), l2 = document.getElementById('pcLine2'), l3 = document.getElementById('pcLine3');
        if(!isHidden) {
            l1.style.transform = 'translateY(7px) rotate(45deg)';
            l2.style.opacity = '0';
            l3.style.transform = 'translateY(-7px) rotate(-45deg)';
        } else { resetHamburgerIcon(); }
    }

    function resetHamburgerIcon() {
        const l1 = document.getElementById('pcLine1'), l2 = document.getElementById('pcLine2'), l3 = document.getElementById('pcLine3');
        if(l1) { l1.style.transform = 'none'; l2.style.opacity = '1'; l3.style.transform = 'none'; }
    }

    async function markAllAsRead() {
        try {
            const r = await fetch('<?php echo e(route("notifications.read-all")); ?>', { 
                method: 'POST', headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' } 
            });
            if(r.ok) location.reload();
        } catch(e) { console.error(e); }
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        const bottomTrigger = document.getElementById('bottomMenuTrigger');
        const bottomCard = document.getElementById('bottomMenuCard');
        if(bottomTrigger && bottomCard) {
            bottomTrigger.onclick = (e) => { e.stopPropagation(); bottomCard.classList.toggle('hidden'); };
            document.getElementById('closeBottomMenu').onclick = () => bottomCard.classList.add('hidden');
        }
        document.addEventListener('click', (e) => {
            if (!e.target.closest('#notiPanel') && !e.target.closest('button')) document.getElementById('notiPanel')?.classList.add('hidden');
            if (!e.target.closest('#desktopMenu') && !e.target.closest('#desktopHamburgerBtn')) { document.getElementById('desktopMenu')?.classList.add('hidden'); resetHamburgerIcon(); }
            if (!e.target.closest('#bottomMenuCard') && !e.target.closest('#bottomMenuTrigger')) document.getElementById('bottomMenuCard')?.classList.add('hidden');
        });
    });
</script><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/partials/nav.blade.php ENDPATH**/ ?>