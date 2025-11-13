@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="container max-w-7xl mx-auto px-4">
        <!-- Header de Categoría -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-semibold text-text-dark mb-4 border-b-2 border-black pb-2">{{ $categoria }}</h1>
            <p class="text-text-dark text-lg max-w-3xl mx-auto">
                {{ $descripcion }}
            </p>
        </div>

        <!-- Grupos de la Categoría -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            @if($groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groups as $group)
                    <a href="{{ route('grupos.show', $group->id) }}" class="block border border-gray-200 rounded-lg p-6 hover:border-button hover:shadow-md transition-all duration-300">
                        @if($group->image)
                        <div class="mb-4">
                            <img src="{{ Storage::url($group->image) }}" alt="{{ $group->name }}" class="w-full h-48 object-cover rounded-lg">
                        </div>
                        @endif
                        
                        <h3 class="text-xl font-semibold text-text-dark mb-3">{{ $group->name }}</h3>
                        <p class="text-text-dark mb-4">{{ $group->description }}</p>
                        
                        <div class="space-y-2 text-sm text-text-light">
                            @if($group->age_range)
                            <div class="flex items-center">
                                <span class="mr-2">👥</span>
                                <span>{{ $group->age_range }}</span>
                            </div>
                            @endif
                            
                            @if($group->meeting_days)
                            <div class="flex items-center">
                                <span class="mr-2">📅</span>
                                <span>{{ $group->meeting_days }}</span>
                            </div>
                            @endif
                            
                            @if($group->meeting_time)
                            <div class="flex items-center">
                                <span class="mr-2">🕒</span>
                                <span>{{ $group->meeting_time }}</span>
                            </div>
                            @endif
                            
                            @if($group->location)
                            <div class="flex items-center">
                                <span class="mr-2">📍</span>
                                <span>{{ $group->location }}</span>
                            </div>
                            @endif
                        </div>
                        
                        @if($group->contact_email || $group->contact_phone)
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <h4 class="font-semibold text-text-dark mb-2">Contacto:</h4>
                            @if($group->contact_email)
                            <a href="mailto:{{ $group->contact_email }}" class="text-button hover:text-blue-500 text-sm">
                                📧 {{ $group->contact_email }}
                            </a>
                            @endif
                            @if($group->contact_phone)
                            <div class="text-text-light text-sm">
                                📞 {{ $group->contact_phone }}
                            </div>
                            @endif
                        </div>
                        @endif
                    </a>
                    @endforeach
                </div>
            @else
                <!-- Grupos por defecto cuando no hay grupos en la BD -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
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
            @endif
        </div>

        <!-- Botón Volver -->
        <div class="text-center mt-8">
            <a href="{{ route('grupos.index') }}" class="inline-block bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                ← Volver a Grupos Parroquiales
            </a>
        </div>
    </div>
</div>
@endsection