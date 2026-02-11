<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intenciones para Misa - La Redonda Joven</title>
    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('img/logo_nav_redonda.png')); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'nav-footer': '#a4e0f3',
                        'button': '#5cb1e3',
                        'text-dark': '#333333',
                        'text-light': '#666666',
                        'background-light': '#f9f9f9',
                    },
                    fontFamily: {
                        'poppins': ['Poppins', 'sans-serif'],
                    },
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #f9f9f9;
        }
        @media print {
            @page { 
                margin: 1.5cm; 
                size: A4;
            }
            body { 
                background-color: white; 
                -webkit-print-color-adjust: exact; 
                print-color-adjust: exact; 
                margin: 0;
            }
            .no-print { 
                display: none !important; 
            }
            
            .print-container {
                max-width: 100% !important;
                width: 100% !important;
                box-shadow: none !important;
                margin: 0 !important;
                padding: 0 !important;
                border: none !important;
            }
            
            .intention-card {
                break-inside: avoid; 
                border: 1px solid #eee;
                margin-bottom: 1rem !important;
                box-shadow: none !important;
            }
            
            .print-columns {
                column-count: 2 !important;
                column-gap: 2rem;
            }
            
            .intention-header {
                border-bottom: 2px solid #ccc !important;
                padding-bottom: 5px !important;
                margin-bottom: 10px !important;
            }
        }
    </style>
</head>
<body class="font-poppins text-text-dark antialiased">
    
    <div class="min-h-screen py-6 px-4 md:py-10">
        <div class="print-container max-w-4xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
            
            <div class="no-print bg-nav-footer px-6 py-8 text-center border-b-4 border-white relative">
                <div class="flex justify-center mb-4">
                    <img src="<?php echo e(asset('img/logo_redonda.png')); ?>" alt="Logo" class="h-20 w-auto drop-shadow-sm">
                </div>
                
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">Intenciones de Misa</h1>
                <p class="text-gray-700 font-medium text-lg">Parroquia Inmaculada Concepción</p>
                
                <div class="mt-6 inline-flex items-center bg-white/80 backdrop-blur-sm px-6 py-2 rounded-full shadow-sm border border-white">
                    <span class="text-gray-800 font-semibold text-sm md:text-base flex items-center gap-2">
                        <span> <?php echo e(now()->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y')); ?></span>
                        <span class="text-gray-400">|</span> 
                        <span> <?php echo e(now()->setTimezone('America/Argentina/Buenos_Aires')->hour >= 12 ? '19:30' : '09:00'); ?> hs</span>
                    </span>
                </div>

                <div class="absolute top-4 right-4 flex flex-col gap-2">
                    <button onclick="window.print()" 
                            class="bg-button text-white px-4 py-2 rounded-lg font-semibold hover:bg-blue-500 transition-colors shadow-md flex items-center justify-center gap-2 text-sm group">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        <span class="hidden md:inline">Imprimir</span>
                    </button>
                    <button onclick="window.close()" 
                            class="bg-white text-gray-600 border border-gray-200 px-4 py-2 rounded-lg font-semibold hover:bg-gray-50 transition-colors shadow-sm flex items-center justify-center gap-2 text-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        <span class="hidden md:inline">Cerrar</span>
                    </button>
                </div>
            </div>

            <div class="p-6 md:p-10 bg-white min-h-[500px]">
                <?php
                    $groupedIntentions = $intentions->groupBy('type');
                    $typeLabels = [
                        'salud' => 'Por la Salud de',
                        'difuntos' => 'Por el Eterno Descanso de',
                        'accion-gracias' => 'En Acción de Gracias',
                        'intenciones' => 'Intenciones Particulares'
                    ]
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $groupedIntentions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type => $typeIntentions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="intention-card mb-8 last:mb-0 rounded-xl p-1 break-inside-avoid">
                        
                        <div class="intention-header flex items-center gap-3 mb-4 pb-2 border-b border-gray-100">
                            <h2 class="text-lg md:text-xl font-bold text-gray-800 uppercase tracking-wide">
                                <?php echo e($typeLabels[$type] ?? ucfirst($type)); ?>

                            </h2>
                            <span class="ml-auto bg-gray-100 text-gray-600 text-xs font-bold px-2.5 py-1 rounded-full">
                                <?php echo e($typeIntentions->count()); ?>

                            </span>
                        </div>

                        <div class="columns-1 md:columns-2 lg:columns-3 gap-4 space-y-4 print-columns">
                            <?php $__currentLoopData = $typeIntentions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $intention): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="break-inside-avoid">
                                    <div class="p-2 rounded-lg  <?php echo e($typeStyles[$type] ?? ''); ?> shadow-sm flex items-start gap-2">
                                        <span class="font-semibold text-sm md:text-base leading-tight text-gray-800">
                                            <?php echo e($intention->name); ?>

                                        </span>
                                        <?php if($type === 'difuntos'): ?>
                                            <span class="text-[10px] opacity-60 font-normal self-start mt-0.5 uppercase tracking-tighter">(Q.E.P.D)</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="flex flex-col items-center justify-center py-20 border-2 border-dashed border-gray-200 rounded-2xl ">
                        <h3 class="text-xl font-bold text-gray-600">No hay intenciones registradas</h3>
                        <p class="text-gray-500 mt-2 text-sm">Aún no se han recibido peticiones para esta misa.</p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="no-print bg-gray-50 p-4 text-center border-t border-gray-200">
                <p class="text-xs text-gray-400">
                    Sistema de Gestión La Redonda Joven | Visualización previa
                </p>
            </div>
        </div>
    </div>

</body>
</html><?php /**PATH C:\laragon\www\la_redonda_joven\resources\views/admin/intentions/print.blade.php ENDPATH**/ ?>