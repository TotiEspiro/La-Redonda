<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intenciones para Misa - La Redonda Joven</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="../img/logo_redonda.png">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { margin: 0; }
            .print-header { 
                border-bottom: 2px solid #333 !important;
                margin-bottom: 20px !important;
            }
            .intention-section { 
                page-break-inside: avoid;
                margin-bottom: 25px !important;
            }
        }
        .name-list {
            column-count: 2;
            column-gap: 2rem;
        }
        @media (max-width: 768px) {
            .name-list {
                column-count: 1;
            }
        }
    </style>
</head>
<body class="bg-background font-poppins">
    <div class="container max-w-4xl mx-auto py-8 px-4">
        <!-- Header -->
        <div class="print-header text-center mb-8 p-6 border-b-2 border-text-dark bg-nav-footer rounded">
            <h1 class="text-3xl font-semibold text-text-dark mb-2">Intenciones para la Misa</h1>
            <p class="text-text-light text-lg">La Redonda Joven</p>
            <p class="text-text-light mt-2">
                Generado el: {{ now()->setTimezone('America/Argentina/Buenos_Aires')->format('d/m/Y') }} - 
                Misa de {{ now()->hour >= 12 ? '19:30' : '09:00' }}hs
            </p>
            
            <!-- Botones de acción -->
            <div class="no-print mt-6 flex justify-center gap-4 ">
                <button onclick="window.print()" 
                        class=" text-white bg-button hover:bg-blue-500 px-4 py-2 rounded transition-colors">
                    Imprimir
                </button>
                <button onclick="window.close()" 
                        class= "text-white bg-gray-500 hover:bg-gray-700 px-4 py-2 rounded transition-colors">
                    Cerrar
                </button>
            </div>
        </div>

        <!-- Agrupar intenciones por tipo -->
        @php
            $groupedIntentions = $intentions->groupBy('type');
            $typeLabels = [
                'salud' => 'Por la Salud de',
                'difuntos' => 'Por el Reposo de',
                'accion-gracias' => 'En Acción de Gracias por',
                'intenciones' => 'Por las Intenciones de'
            ];
            
            // Determinar horario de misa basado en la hora actual
            $horaMisa = now()->setTimezone('America/Argentina/Buenos_Aires')->hour >= 24 ? '19:30' : '09:00';
        @endphp

        <div class="space-y-8">
            @forelse($groupedIntentions as $type => $typeIntentions)
            <div class="intention-section bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <!-- Header del tipo de intención -->
                <div class="mb-4 pb-3 border-b border-gray-200">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            @if($type == 'salud') bg-green-100 text-green-800
                            @elseif($type == 'difuntos') bg-gray-100 text-gray-800
                            @elseif($type == 'accion-gracias') bg-yellow-100 text-yellow-800
                            @else bg-blue-100 text-blue-800 @endif">
                            {{ $typeLabels[$type] ?? ucfirst($type) }}
                        </span>
                        <span class="text-text-light text-sm">
                            ({{ $typeIntentions->count() }} {{ $typeIntentions->count() === 1 ? 'intención' : 'intenciones' }})
                        </span>
                    </div>
                </div>

                <!-- Lista de nombres -->
                <div class="name-list">
                    @foreach($typeIntentions as $intention)
                    <div class="name-item mb-2 break-inside-avoid">
                        <span class="text-text-dark font-medium">{{ $intention->name }}</span>
                        @if($type === 'difuntos')
                        <span class="text-text-light text-sm ml-1">(Q.E.P.D.)</span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="text-text-light text-xl">No hay intenciones para mostrar</div>
                <p class="text-text-light mt-2">No se han registrado intenciones para la misa de hoy.</p>
            </div>
            @endforelse
        </div>
    </div>
    <script src="https://cdn.tailwindcss.com"></script>
</body>
</html>