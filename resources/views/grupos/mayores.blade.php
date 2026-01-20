@extends('layouts.app')

@section('content')
<div class="py-8 md:py-12 bg-gray-50 min-h-screen">
    <div class="container max-w-7xl mx-auto px-4 py-6 md:py-8">
        <div class="relative mb-10">
            <div class="text-center px-4 md:px-12">
                <h1 class="text-3xl md:text-4xl font-bold text-text-dark border-b-2 border-button inline-block pb-2">
                    Mayores
                </h1>
                <p class="mt-4 text-text-light max-w-2xl mx-auto text-sm md:text-base">
                   Grupos para la tercera edad.
                </p>
            </div>
            <div class="absolute top-0 right-0 flex gap-2">
                <a href="{{ route('grupos.jovenes') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 md:w-12 md:h-12 bg-button text-white border border-gray-300 rounded-full font-semibold hover:bg-blue-500 transition-all shadow-sm group"
                   title="Ir a Jóvenes">
                    <svg class="w-5 h-5 md:w-6 md:h-6 transform rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
                <a href="{{ route('grupos.especiales') }}" 
                   class="inline-flex items-center justify-center w-10 h-10 md:w-12 md:h-12 bg-button text-white border border-gray-300 rounded-full font-semibold hover:bg-blue-500 transition-all shadow-sm group"
                   title="Ir a Más Grupos">
                    <svg class="w-5 h-5 md:w-6 md:h-6 transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 justify-center max-w-5xl mx-auto items-stretch">
            <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full"> 
                <div class="accordion-header md:cursor-default cursor-pointer bg-white select-none flex-shrink-0">
                    <div class="w-full bg-gray-100 relative md:h-72 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('img/grupo_santana.png') }}" alt="Grupo Coro" class="w-full h-auto md:h-full object-cover block">
                    </div>

                    <div class="p-4 flex items-center justify-between border-b border-transparent md:border-none">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-text-dark text-lg">Santa Ana</h3>
                            <span class="text-xs text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Mujeres Mayores</span>
                        </div>
                        <div class="accordion-chevron text-gray-400 transition-transform duration-300 md:hidden bg-gray-50 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="accordion-content hidden md:flex flex-col flex-grow p-6 pt-0 border-t md:border-t-0 border-gray-100 bg-white">
                    <p class="text-text-light text-sm mb-6 text-left flex-grow leading-relaxed">
                        Grupo de oración a la palabra de Dios dedicado especialmente para mujeres mayores.
                    </p>
                    <div class="flex items-center justify-center text-sm text-button font-medium bg-blue-50 py-3 rounded-lg mt-auto border border-blue-100 px-3 min-h-[3.5rem] text-center">
                        <img src="{{ asset('img/icono_horario.png') }}" class="mr-2 h-4 w-4 flex-shrink-0">
                        <span>Viernes 15:30 - 17:30 hs</span>
                    </div>
                </div>
            </div>

            <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
                <div class="accordion-header md:cursor-default cursor-pointer bg-white select-none flex-shrink-0">
                    <div class="w-full bg-gray-100 relative md:h-72 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('img/grupos_sanjoaquin.png') }}" alt="Grupo Coro" class="w-full h-auto md:h-full object-cover block">
                    </div>

                    <div class="p-4 flex items-center justify-between border-b border-transparent md:border-none">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-text-dark text-lg">San Joaquín</h3>
                            <span class="text-xs text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Hombres Mayores</span>
                        </div>
                        <div class="accordion-chevron text-gray-400 transition-transform duration-300 md:hidden bg-gray-50 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="accordion-content hidden md:flex flex-col flex-grow p-6 pt-0 border-t md:border-t-0 border-gray-100 bg-white">
                    <p class="text-text-light text-sm mb-6 text-left flex-grow leading-relaxed">
                        Grupo de oración a la palabra de Dios dedicado especialmente para hombres mayores.
                    </p>
                    <div class="flex items-center justify-center text-sm text-button font-medium bg-blue-50 py-3 rounded-lg mt-auto border border-blue-100 px-3 min-h-[3.5rem] text-center">
                        <img src="{{ asset('img/icono_horario.png') }}" class="mr-2 h-4 w-4 flex-shrink-0">
                        <span>Viernes 15:30 - 17:30 hs</span>
                    </div>
                </div>
            </div>

            <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
                
                <div class="accordion-header md:cursor-default cursor-pointer bg-white select-none flex-shrink-0">
                    <div class="w-full bg-gray-100 relative md:h-72 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('img/grupo_ardillas.jpg') }}" alt="Grupo Coro" class="w-full h-auto md:h-full object-cover block">
                    </div>

                    <div class="p-4 flex items-center justify-between border-b border-transparent md:border-none">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-text-dark text-lg">Grupo Ardillas</h3>
                            <span class="text-xs text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Recreación</span>
                        </div>
                        <div class="accordion-chevron text-gray-400 transition-transform duration-300 md:hidden bg-gray-50 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="accordion-content hidden md:flex flex-col flex-grow p-6 pt-0 border-t md:border-t-0 border-gray-100 bg-white">
                    <p class="text-text-light text-sm mb-6 text-left flex-grow leading-relaxed">
                        Formación continua, recreación y esparcimiento para adultos.
                    </p>
                    <div class="flex items-center justify-center text-sm text-button font-medium bg-blue-50 py-3 rounded-lg mt-auto border border-blue-100 px-3 min-h-[3.5rem] text-center">
                        <img src="{{ asset('img/icono_horario.png') }}" class="mr-2 h-4 w-4 flex-shrink-0">
                        <span>Martes 16:00 - 18:00 hs</span>
                    </div>
                </div>
            </div>

            <div class="group bg-white border border-gray-200 rounded-xl hover:border-button hover:shadow-lg transition-all duration-300 flex flex-col overflow-hidden h-full">
                
                <div class="accordion-header md:cursor-default cursor-pointer bg-white select-none flex-shrink-0">
                    <div class="w-full bg-gray-100 relative md:h-72 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset('img/grupo_costureras.jpg') }}" onerror="this.src='{{ asset('img/logo_redonda.png') }}'; this.classList.add('p-12', 'opacity-50');" alt="Costureras" class="w-full h-auto md:h-full object-cover block">
                    </div>

                    <div class="p-4 flex items-center justify-between border-b border-transparent md:border-none">
                        <div class="flex flex-col">
                            <h3 class="font-bold text-text-dark text-lg">Costureras</h3>
                            <span class="text-xs text-button font-medium bg-blue-50 px-2 py-0.5 rounded-full inline-block w-fit mt-1">Servicio</span>
                        </div>
                        <div class="accordion-chevron text-gray-400 transition-transform duration-300 md:hidden bg-gray-50 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="accordion-content hidden md:flex flex-col flex-grow p-6 pt-0 border-t md:border-t-0 border-gray-100 bg-white">
                    <p class="text-text-light text-sm mb-6 text-left flex-grow leading-relaxed">
                        Refacción de materiales para asistir a lugares careciados.
                    </p>
                    <div class="flex items-center justify-center text-sm text-button font-medium bg-blue-50 py-3 rounded-lg mt-auto border border-blue-100 px-3 min-h-[3.5rem] text-center">
                        <img src="{{ asset('img/icono_horario.png') }}" class="mr-2 h-4 w-4 flex-shrink-0">
                        <span>Martes 15:00 - 17:00 hs</span>
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-12 md:mt-16">
            <div class="bg-button text-white rounded-xl p-8 md:p-10 w-full max-w-3xl mx-auto text-center shadow-xl">
                <h2 class="text-2xl md:text-3xl font-semibold mb-4">¿Te gustaría unirte?</h2>
                <p class="text-blue-50 mb-8 text-base md:text-lg">
                    No dudes en acercarte y participar. Todos son bienvenidos a formar parte de nuestra comunidad.
                </p>
                <a href="https://www.instagram.com/direct/t/105222797545921/" target="_blank" class="inline-block bg-white text-button px-8 py-3 rounded-full font-bold hover:bg-gray-100 hover:scale-105 transition-all shadow-md">
                    Contáctanos
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const headers = document.querySelectorAll('.accordion-header');
        if (headers.length > 0) {
            headers.forEach(header => {
                header.addEventListener('click', function() {
                    if (window.innerWidth >= 768) return;
                    const content = this.nextElementSibling;
                    const chevron = this.querySelector('.accordion-chevron');
                    if (content) {
                        const isHidden = content.classList.contains('hidden');
                        if (isHidden) {
                            content.classList.remove('hidden');
                            content.classList.add('flex', 'flex-col');
                            if(chevron) chevron.style.transform = 'rotate(180deg)';
                        } else {
                            content.classList.add('hidden');
                            content.classList.remove('flex', 'flex-col');
                            if(chevron) chevron.style.transform = 'rotate(0deg)';
                        }
                    }
                });
            });
        }
    });
</script>
@endsection