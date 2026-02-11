@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12 bg-background-light min-h-screen">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-black text-text-dark mb-4 border-b-4 border-button pb-2 inline-block px-4 uppercase tracking-tighter">{{ $categoria }}</h1>
            <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2">
                {{ $descripcion }}
            </p>
        </div>

        {{-- LÓGICA DE PRIORIDAD: Diseños manuales primero --}}
        @php
            $esCategoriaManual = in_array($categoria, ['Catequesis', 'Jóvenes', 'Mayores', 'Más Grupos']);
        @endphp

        @if($esCategoriaManual)
            {{-- Mostramos tus archivos personalizados (catequesis.blade.php, etc) --}}
            <div class="w-full">
                @if($categoria == 'Catequesis')
                    @include('grupos.catequesis')
                @elseif($categoria == 'Jóvenes')
                    @include('grupos.jovenes')
                @elseif($categoria == 'Mayores')
                    @include('grupos.mayores')
                @elseif($categoria == 'Más Grupos')
                    @include('grupos.especiales')
                @endif
            </div>
        @else
            {{-- Si es cualquier otra categoría, mostramos los grupos de la base de datos --}}
            @if($groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groups as $group)
                        <div class="bg-white rounded-xl shadow-lg p-5 border border-gray-100 flex flex-col h-full">
                            @if($group->image)
                                <img src="{{ Storage::url($group->image) }}" class="w-full h-48 object-cover rounded-lg mb-4">
                            @endif
                            <h3 class="text-xl font-bold text-text-dark mb-2">{{ $group->name }}</h3>
                            <p class="text-gray-600 text-sm mb-4 flex-grow">{{ $group->description ?? 'Sin descripción.' }}</p>
                            @include('partials.group-join-button', ['slug' => $group->category, 'nombre' => $group->name])
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-200">
                    <p class="text-gray-400 font-bold uppercase text-xs tracking-widest">No hay grupos disponibles en esta sección</p>
                </div>
            @endif
        @endif

        <div class="text-center mt-8 md:mt-12">
            <a href="{{ route('grupos.index') }}" class="inline-flex items-center bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 hover:text-black transition-all shadow-sm">
                <span class="mr-2">←</span> Volver a Grupos
            </a>
        </div>
    </div>
</div>
@endsection