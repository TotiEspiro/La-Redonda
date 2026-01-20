@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-light py-6 md:py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="bg-button px-6 py-8 text-white">
                <div class="flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-6">
                    <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-md flex-shrink-0">
                        <span class="text-button text-4xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl md:text-3xl font-bold">{{ Auth::user()->name }}</h1>
                        <p class="text-blue-100 text-sm md:text-base">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap gap-2 mt-3 justify-center md:justify-start">
                            @foreach(Auth::user()->roles as $role)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide shadow-sm
                                    {{ $role->name === 'superadmin' ? 'bg-red-500 text-white' : 
                                       ($role->name === 'admin' ? 'bg-yellow-400 text-yellow-900' : 
                                       ($role->name === 'user' ? 'bg-blue-400 text-white' : 'bg-nav-footer text-white')) }}">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-text-dark mb-4 flex items-center border-b border-gray-100 pb-2">
                        <span class="mr-2"></span> Información de la Cuenta
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <label class="block text-xs font-bold text-text-light uppercase tracking-wider mb-1">Nombre completo</label>
                            <p class="text-text-dark font-medium text-lg">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <label class="block text-xs font-bold text-text-light uppercase tracking-wider mb-1">Email</label>
                            <p class="text-text-dark font-medium text-lg break-all">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <label class="block text-xs font-bold text-text-light uppercase tracking-wider mb-1">Total de Roles</label>
                            <p class="text-text-dark font-medium">
                                <span class="text-button font-bold text-lg">
                                    {{ Auth::user()->roles->count() }}
                                </span> rol(es) asignado(s)
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <label class="block text-xs font-bold text-text-light uppercase tracking-wider mb-1">Miembro desde</label>
                            <p class="text-text-dark font-medium text-lg">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.edit') }}" 
                       class="w-full md:w-auto bg-button text-white px-6 py-3 rounded-lg font-semibold text-center hover:bg-blue-500 transition-all shadow-sm">
                        Editar Perfil
                    </a>
                    <a href="{{ route('profile.change-password') }}" 
                       class="w-full md:w-auto bg-white border-2 border-gray-200 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center hover:border-gray-300 hover:bg-gray-50 transition-all">
                        Cambiar Contraseña
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection