@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12 bg-background-light min-h-screen">
    <div class="container max-w-7xl mx-auto px-4">
        <div class="text-center mb-10 md:mb-12">
            <h1 class="text-3xl md:text-4xl font-bold text-text-dark mb-4 border-b-2 border-black pb-2 inline-block px-4">{{ $categoria }}</h1>
            <p class="text-text-dark text-base md:text-lg max-w-3xl mx-auto mt-4 leading-relaxed px-2">
                {{ $descripcion }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-4 md:p-8 border border-gray-100">
            @if($groups->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($groups as $group)
                    <a href="{{ route('grupos.show', $group->id) }}" class="block border border-gray-200 rounded-xl p-5 hover:border-button hover:shadow-lg transition-all duration-300 group h-full flex flex-col bg-gray-50 hover:bg-white">
                        @if($group->image)
                        <div class="mb-4 overflow-hidden rounded-lg shadow-sm">
                            <img src="{{ Storage::url($group->image) }}" alt="{{ $group->name }}" class="w-full h-48 object-cover transform group-hover:scale-105 transition-transform duration-500">
                        </div>
                        @endif
                        
                        <h3 class="text-xl font-bold text-text-dark mb-3 group-hover:text-button transition-colors">{{ $group->name }}</h3>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed flex-grow">{{ $group->description }}</p>
                        
                        <div class="space-y-2 text-sm text-gray-500 pt-4 border-t border-gray-200 mt-auto">
                            @if($group->age_range)
                            <div class="flex items-center">
                                <span class="mr-2 text-lg">👥</span>
                                <span>{{ $group->age_range }}</span>
                            </div>
                            @endif
                            
                            @if($group->meeting_days || $group->meeting_time)
                            <div class="flex items-center">
                                <span class="mr-2 text-lg">📅</span>
                                <span>{{ $group->meeting_days }} {{ $group->meeting_time }}</span>
                            </div>
                            @endif
                            
                            @if($group->location)
                            <div class="flex items-center">
                                <span class="mr-2 text-lg">📍</span>
                                <span>{{ $group->location }}</span>
                            </div>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 justify-items-center">
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

        <div class="text-center mt-8 md:mt-12">
            <a href="{{ route('grupos.index') }}" class="inline-flex items-center bg-white text-gray-700 border border-gray-300 px-6 py-3 rounded-full font-semibold hover:bg-gray-50 hover:text-black transition-all shadow-sm">
                <span class="mr-2">←</span> Volver a Grupos
            </a>
        </div>
    </div>
</div>
@endsection