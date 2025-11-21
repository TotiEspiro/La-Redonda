@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-background-light py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <!-- Header del perfil -->
            <div class="bg-button px-6 py-8 text-white">
                <div class="flex items-center space-x-4">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center">
                        <span class="text-button text-2xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ Auth::user()->name }}</h1>
                        <p class="text-blue-100">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            @foreach(Auth::user()->roles as $role)
                                <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold 
                                    {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-800' : 
                                       ($role->name === 'admin' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($role->name === 'admin_grupo_parroquial' ? 'bg-purple-100 text-purple-800' :
                                       ($role->name === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'))) }}">
                                    {{ $role->display_name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido del perfil -->
            <div class="p-6">
                <!-- Información de la cuenta -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-text-dark mb-4">Información de la Cuenta</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-text-light mb-1">Nombre completo</label>
                            <p class="text-text-dark font-medium">{{ Auth::user()->name }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-text-light mb-1">Email</label>
                            <p class="text-text-dark font-medium">{{ Auth::user()->email }}</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-text-light mb-1">Total de Roles</label>
                            <p class="text-text-dark font-medium">
                                <span class="bg-button text-white px-3 py-1 rounded-full text-sm">
                                    {{ Auth::user()->roles->count() }} rol(es)
                                </span>
                            </p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <label class="block text-sm font-medium text-text-light mb-1">Miembro desde</label>
                            <p class="text-text-dark font-medium">{{ Auth::user()->created_at->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Sección de Roles y Permisos -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-text-dark mb-4">Mis Roles y Permisos</h2>
                    
                    @if(Auth::user()->roles->count() > 0)
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-text-dark mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-button" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Roles Asignados
                                </h3>
                                <div class="space-y-3">
                                    @foreach(Auth::user()->roles as $role)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                            <div>
                                                <span class="font-medium text-text-dark">{{ $role->display_name }}</span>
                                                @if($role->description)
                                                    <p class="text-sm text-text-light mt-1">{{ $role->description }}</p>
                                                @endif
                                            </div>
                                            <span class="text-xs font-medium px-2 py-1 rounded 
                                                {{ $role->name === 'superadmin' ? 'bg-red-100 text-red-800' : 
                                                   ($role->name === 'admin' ? 'bg-yellow-100 text-yellow-800' : 
                                                   ($role->name === 'admin_grupo_parroquial' ? 'bg-purple-100 text-purple-800' :
                                                   ($role->name === 'user' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'))) }}">
                                                {{ $role->name }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Tarjeta de Permisos -->
                            <div class="bg-white border border-gray-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-text-dark mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Permisos Disponibles
                                </h3>
                                <div class="space-y-2">
                                    @if(Auth::user()->isSuperAdmin())
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acceso completo al sistema
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Gestión de todos los usuarios
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Administración de grupos
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Panel de administración completo
                                        </div>
                                    @elseif(Auth::user()->isAdmin())
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Gestión de usuarios
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Administración de grupos
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acceso al panel de administración
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acceso al Diario de La Redonda
                                        </div>
                                    @elseif(Auth::user()->hasAnyRole(['admin_grupo_parroquial', 'catequesis', 'juveniles', 'acutis', 'juan_pablo', 'coro', 'san_joaquin', 'santa_ana', 'ardillas', 'costureras', 'misioneros', 'caridad_comedor']))
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acceso al Diario de La Redonda
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Creación de documentos personales
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Gestión de contenido de grupo
                                        </div>
                                    @else
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Acceso básico al sistema
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Ver información pública
                                        </div>
                                        <div class="flex items-center text-sm text-text-dark">
                                            <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                            Gestionar perfil personal
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Nota Informativa -->
                        <div class="mt-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <div class="flex items-start">
                                <svg class="h-5 w-5 text-blue-400 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <p class="text-sm text-blue-700">
                                        <strong>Información sobre roles:</strong> Tus roles determinan las secciones del sistema a las que puedes acceder y las acciones que puedes realizar. 
                                        Si necesitas cambios en tus roles, contacta con un administrador del sistema.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 rounded-lg border border-gray-200">
                            <div class="text-gray-400 text-5xl mb-4">👤</div>
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">No tienes roles asignados</h3>
                            <p class="text-gray-500">Actualmente no tienes roles específicos asignados en el sistema.</p>
                            <p class="text-sm text-gray-400 mt-2">Contacta con un administrador para asignarte roles.</p>
                        </div>
                    @endif
                </div>

                <!-- Acciones -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('profile.edit') }}" 
                       class="bg-button text-white px-6 py-3 rounded-lg font-semibold text-center hover:bg-blue-500 transition-colors no-underline">
                        Editar Perfil
                    </a>
                    <a href="{{ route('profile.change-password') }}" 
                       class="bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold text-center hover:bg-gray-700 transition-colors no-underline">
                        Cambiar Contraseña
                    </a>
                    <a href="{{ url('/') }}" 
                       class="bg-gray-300 text-gray-700 px-6 py-3 rounded-lg font-semibold text-center hover:bg-gray-400 transition-colors no-underline">
                        Volver al Inicio
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection