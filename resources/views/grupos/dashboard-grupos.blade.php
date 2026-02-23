@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen pb-24 md:pb-20">
    
    {{-- Header de Gestión --}}
    <div class="bg-button text-white pt-10 pb-24 md:pb-32 shadow-lg relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-20 -mt-20 blur-3xl"></div>
        <div class="container max-w-7xl mx-auto px-4 relative z-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-3xl md:text-5xl font-black uppercase tracking-tighter leading-tight mb-2">{{ $groupName }}</h1>
                    <div class="flex items-center gap-3">
                        <span class="bg-blue-900/50 px-3 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest border border-white/10">Coordinación</span>
                        <p class="text-blue-50 font-bold uppercase text-[10px] tracking-widest opacity-80">{{ $totalMembers }} Miembros</p>
                    </div>
                </div>
                
                {{-- Botones Principales --}}
                <div class="grid grid-cols-2 sm:flex gap-3 w-full md:w-auto">
                    <button onclick="openUploadModal()" class="col-span-2 md:flex-none bg-white text-button px-6 py-4 rounded-2xl font-black text-xs shadow-2xl hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest">
                        + Subir Material
                    </button>
                    <a href="{{ route('grupos.materials', $group->category) }}" class="flex-1 md:flex-none bg-blue-900 text-white border border-white/20 px-6 py-4 rounded-2xl font-black text-xs text-center backdrop-blur-sm hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                     Biblioteca
                    </a>
                    <a href="{{ route('grupos.members', $groupRole) }}" class="flex-1 md:flex-none bg-blue-900 text-white px-6 py-4 rounded-2xl font-black text-xs text-center shadow-lg hover:scale-[1.02] active:scale-95 transition-all uppercase tracking-widest flex items-center justify-center gap-2">
                        Miembros
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVEGACIÓN TÁCTIL (Sticky Bar) --}}
    <div class="md:hidden sticky top-[70px] z-40 bg-gray-50/95 backdrop-blur-md py-4 px-4 overflow-x-auto no-scrollbar border-b border-gray-200">
        <div class="flex gap-2 min-w-max">
            <button onclick="switchTab('comunidad')" id="tab-btn-comunidad" class="tab-btn active px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">Resumen</button>
            <button onclick="switchTab('solicitudes')" id="tab-btn-solicitudes" class="tab-btn px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all relative">
                Peticiones
                @if(count($requests) > 0)
                    <span class="ml-1 bg-red-500 text-white px-1.5 rounded-full text-[8px]">{{ count($requests) }}</span>
                @endif
            </button>
            <button onclick="switchTab('seguridad')" id="tab-btn-seguridad" class="tab-btn px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">Seguridad</button>
            <button onclick="switchTab('archivos')" id="tab-btn-archivos" class="tab-btn px-5 py-2.5 rounded-full text-[10px] font-black uppercase tracking-widest transition-all">Archivos</button>
        </div>
    </div>

    {{-- Contenedor de Contenido --}}
    <div class="container max-w-7xl mx-auto px-4 -mt-10 md:-mt-16 relative z-30 pt-4 md:pt-0">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- LADO IZQUIERDO: SECCIONES PRINCIPALES --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- SECCIÓN COMUNIDAD --}}
                <div id="section-comunidad" class="tab-content bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate-fade-in mt-6 md:mt-0">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Últimos Agregados</h3>
                        <div class="hidden sm:flex items-center gap-2">
                            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                            <span class="text-[9px] font-black text-gray-400 uppercase">Activo</span>
                        </div>
                    </div>
                    
                    {{-- Desktop Table --}}
                    <div class="hidden md:block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-[10px] font-black text-gray-400 uppercase tracking-widest bg-white border-b border-gray-50">
                                    <th class="px-8 py-4">Usuario</th>
                                    <th class="px-4 py-4 text-center">Edad</th>
                                    <th class="px-4 py-4">Ingreso</th>
                                    <th class="px-8 py-4 text-right">Gestión</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($latestMembers as $member)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-gray-900 text-white flex items-center justify-center font-black text-xs uppercase shadow-sm group-hover:bg-button transition-colors">{{ substr($member->name, 0, 1) }}</div>
                                            <div class="min-w-0">
                                                <p class="text-xs font-black text-gray-800 uppercase tracking-tight truncate mb-0.5">{{ $member->name }}</p>
                                                <p class="text-[10px] text-gray-400 font-medium truncate">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-5 text-center">
                                        <span class="px-2 py-1 bg-gray-100 rounded-lg text-[10px] font-black text-gray-600">{{ $member->age ?? '?' }}</span>
                                    </td>
                                    <td class="px-4 py-5">
                                        <div class="flex flex-col leading-tight">
                                            <span class="text-xs font-bold text-gray-700">{{ $member->joined_at->format('d/m/Y') }}</span>
                                            <span class="text-[9px] text-gray-400 uppercase font-black tracking-tighter">{{ $member->joined_at->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        @if(Auth::id() !== $member->id)
                                        <button onclick="confirmRemoveMember({{ $member->id }}, '{{ $member->name }}')" class="p-2 text-gray-300 hover:text-red-500 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile List --}}
                    <div class="md:hidden p-4 space-y-4">
                        @forelse($latestMembers as $member)
                        <div class="bg-gray-50 rounded-[1.5rem] p-5 border border-gray-100 shadow-sm transition-all active:bg-white active:shadow-md">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-2xl bg-gray-900 text-white flex items-center justify-center font-black text-sm uppercase shadow-md">{{ substr($member->name, 0, 1) }}</div>
                                <div class="min-w-0 flex-1">
                                    <h4 class="text-sm font-black text-gray-800 uppercase tracking-tight truncate">{{ $member->name }}</h4>
                                    <p class="text-[10px] text-gray-400 font-medium truncate">{{ $member->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-[8px] font-black text-gray-300 uppercase mb-1">Edad</span>
                                    <span class="bg-white px-2 py-1 rounded-lg text-[10px] font-black border border-gray-200">{{ $member->age ?? '?' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200/60">
                                <div class="flex flex-col">
                                    <span class="text-[8px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Ingresó el</span>
                                    <span class="text-[10px] font-bold text-gray-600">{{ $member->joined_at->format('d/m/Y') }}</span>
                                </div>
                                @if(Auth::id() !== $member->id)
                                <button onclick="confirmRemoveMember({{ $member->id }}, '{{ $member->name }}')" class="bg-white text-red-500 px-5 py-2.5 rounded-xl text-[9px] font-black uppercase tracking-widest border border-red-100 shadow-sm active:scale-95 transition-all">
                                    Retirar
                                </button>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="py-12 text-center text-gray-300 font-black uppercase text-[10px] italic">No hay miembros aún</div>
                        @endforelse
                    </div>
                </div>

                {{-- SECCIÓN SOLICITUDES --}}
                <div id="section-solicitudes" class="tab-content bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate-fade-in hidden md:block">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Peticiones Pendientes</h3>
                        <div class="flex items-center gap-4">
                            @if(count($requests) > 0)
                            <form action="{{ route('grupos.requests.delete-all', $groupRole) }}" method="POST" onsubmit="return confirm('¿Eliminar TODAS las solicitudes pendientes?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-[9px] font-black text-red-400 hover:text-red-600 uppercase tracking-widest transition-all">Eliminar Todas</button>
                            </form>
                            @endif
                            <span class="bg-yellow-100 text-yellow-700 px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest">{{ count($requests) }}</span>
                        </div>
                    </div>
                    <div class="p-4 space-y-4">
                        @forelse($requests as $req)
                        <div class="flex flex-col sm:flex-row items-center justify-between p-5 bg-gray-50 rounded-[1.8rem] border border-gray-100 transition-all gap-5 shadow-sm">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center font-black text-button border border-gray-200 shadow-sm flex-shrink-0 text-lg">{{ $req->age }}</div>
                                <div class="min-w-0">
                                    <p class="text-sm font-black text-gray-800 uppercase tracking-tight truncate mb-0.5">{{ $req->name }}</p>
                                    <p class="text-[11px] text-gray-400 font-medium truncate">{{ $req->email }}</p>
                                </div>
                            </div>
                            <div class="flex gap-2 w-full sm:w-auto">
                                <form action="{{ route('grupos.handle-request', $req->id) }}" method="POST" class="flex-1">
                                    @csrf <input type="hidden" name="action" value="approve">
                                    <button class="w-full sm:w-12 h-12 bg-green-500 text-white rounded-2xl shadow-lg flex items-center justify-center hover:bg-green-600 transition-all active:scale-90 font-black text-[10px] uppercase">
                                        <span class="sm:hidden px-4 py-4 block text-center">Aceptar Miembro</span>
                                        <svg class="hidden sm:block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('grupos.handle-request', $req->id) }}" method="POST" class="flex-1">
                                    @csrf <input type="hidden" name="action" value="reject">
                                    <button class="w-full sm:w-12 h-12 bg-red-500 text-white rounded-2xl shadow-lg flex items-center justify-center hover:bg-red-600 transition-all active:scale-90 font-black text-[10px] uppercase">
                                        <span class="sm:hidden px-4 py-4 block text-center">Rechazar</span>
                                        <svg class="hidden sm:block w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @empty
                        <div class="py-16 text-center text-gray-300 font-black uppercase text-[10px] tracking-widest italic">Sin solicitudes pendientes</div>
                        @endforelse
                    </div>
                </div>

                {{-- SECCIÓN SEGURIDAD --}}
                <div id="section-seguridad" class="tab-content bg-white rounded-[2rem] shadow-xl border border-gray-100 overflow-hidden animate-fade-in hidden md:block">
                    <div class="px-8 py-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em]">Seguridad del Grupo</h3>
                        <div class="bg-blue-50 p-2 rounded-lg">
                            <svg class="w-4 h-4 text-button" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    </div>
                    <div class="p-8">
                        @if(session('success') && request()->routeIs('grupos.dashboard'))
                            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded-r-2xl flex items-center gap-3">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <p class="text-[10px] font-black text-green-700 uppercase tracking-widest">Ajustes de seguridad aplicados</p>
                            </div>
                        @endif

                        <p class="text-[11px] text-gray-500 font-bold uppercase mb-6 tracking-tight leading-relaxed">Configura una clave de acceso obligatoria para que los miembros puedan visualizar los materiales privados del grupo.</p>
                        
                        <form action="{{ route('grupos.update-password', $groupRole) }}" method="POST" class="space-y-6">
                            @csrf
                            @method('PATCH')
                            <div>
                                <label class="block text-[10px] font-black text-gray-400 uppercase mb-3 tracking-widest">Contraseña Actual / Nueva</label>
                                <div class="relative">
                                    <input id="group_password" type="password" name="group_password" value="{{ $group->group_password }}" 
                                        placeholder="Sin contraseña (acceso libre)"
                                        class="w-full p-5 pr-14 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button transition-all text-sm">
                                    
                                    <button type="button" onclick="toggleGroupPassword()" 
                                        class="absolute right-4 top-1/2 -translate-y-1/2 p-2 text-gray-400 hover:text-button transition-colors">
                                        <svg id="eyeClosed" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.057 10.057 0 012.183-4.403M15 12a3 3 0 11-6 0 3 3 0 016 0zm6.362-3.638A9.956 9.956 0 0121.542 12c-1.274 4.057-5.064 7-9.542 7-1.447 0-2.812-.324-4.032-.904m3.582-11.096A10.05 10.05 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21M3 3l3.582 3.582" /></svg>
                                        <svg id="eyeOpen" class="w-6 h-6 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-blue-900 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg hover:bg-blue-950 transition-all active:scale-95">Actualizar Seguridad</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LADO DERECHO: MÉTRICAS Y ARCHIVOS --}}
            <div class="lg:col-span-4 space-y-8">
                
                {{-- SECCIÓN ARCHIVOS --}}
                <div id="section-archivos" class="tab-content bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 animate-fade-in hidden md:block">
                    <div class="flex justify-between items-center mb-8">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Últimos Archivos</h3>
                        <a href="{{ route('grupos.materials', $group->category) }}" class="text-[9px] font-black text-button hover:underline uppercase tracking-widest">Ver todo</a>
                    </div>
                    <div class="space-y-4">
                        @forelse($materials as $m)
                        <div class="p-4 border border-gray-100 rounded-2xl hover:bg-blue-50/50 transition-all group flex items-center justify-between">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center border border-gray-100 group-hover:bg-white transition-colors">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-black text-gray-700 truncate uppercase tracking-tight leading-none mb-1">{{ $m->title }}</p>
                                    <p class="text-[8px] text-gray-400 font-bold uppercase">{{ $m->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <button onclick="confirmDeleteResource({{ $m->id }}, '{{ $m->title }}')" class="p-2 text-gray-200 hover:text-red-500 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </div>
                        @empty
                        <p class="text-center py-8 text-gray-300 text-[10px] font-black uppercase tracking-widest italic border-2 border-dashed border-gray-50 rounded-2xl">Sin recursos subidos</p>
                        @endforelse
                    </div>
                </div>

                {{-- MÉTRICAS OPERATIVAS (Resumen) --}}
                <div id="section-metrics" class="bg-white rounded-[2rem] shadow-xl border border-gray-100 p-8 animate-fade-in block md:block">
                    <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8 text-center md:text-left">Estado Operativo</h3>
                    <div class="space-y-6">
                         <div class="p-5 bg-blue-50 rounded-3xl border border-blue-100 group hover:bg-blue-100 transition-colors flex items-center justify-between">
                            <div>
                                <span class="block text-[8px] font-black text-button uppercase tracking-widest mb-1">Total Miembros</span>
                                <span class="text-3xl font-black text-button leading-none">{{ $totalMembers }}</span>
                            </div>
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                <img src="{{ asset('img/icono_usuarios.png') }}" class="w-8 h-8">
                            </div>
                        </div>
                        <div class="p-5 bg-blue-100 rounded-3xl border border-purple-100 group hover:bg-blue-200 transition-colors flex items-center justify-between">
                            <div>
                                <span class="block text-[8px] font-black text-blue-900 uppercase tracking-widest mb-1">Materiales</span>
                                <span class="text-3xl font-black text-blue-900 leading-none">{{ count($materials) }}</span>
                            </div>
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-sm">
                                <img src="{{ asset('img/icono_archivo.png') }}" class="w-8 h-8">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DE SUBIDA --}}
<div id="uploadModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-end sm:items-center justify-center z-[60] hidden p-0 sm:p-4">
    <div class="bg-white w-full max-w-md rounded-t-[2.5rem] sm:rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up">
        <div class="px-8 py-6 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h2 class="text-xl font-black text-gray-800 uppercase tracking-tighter">Subir Material</h2>
            <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 text-3xl p-2">&times;</button>
        </div>
        <form id="uploadForm" class="p-8 space-y-6">
            @csrf
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Título del Material</label>
                <input type="text" name="title" required placeholder="Ej: Lecturas Domingo 15" class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button transition-all text-sm">
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Tipo de Archivo</label>
                <select name="type" required class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button appearance-none text-sm cursor-pointer">
                    <option value="pdf">Documento PDF</option>
                    <option value="image">Imagen / Foto</option>
                    <option value="doc">Word / Excel</option>
                    <option value="mp3">Audio (MP3/WAV)</option>
                    <option value="mp4">Video (MP4)</option>
                </select>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Descripción</label>
                <textarea name="description" rows="2" placeholder="Notas adicionales sobre el archivo..." class="w-full p-4 bg-gray-50 border border-gray-200 rounded-2xl outline-none font-medium focus:ring-2 focus:ring-button resize-none text-sm"></textarea>
            </div>
            <div>
                <label class="block text-[10px] font-black text-gray-400 uppercase mb-2 tracking-widest">Seleccionar Archivo</label>
                <div class="relative">
                    <input type="file" name="file" required class="w-full text-[10px] text-gray-500 file:mr-4 file:py-3 file:px-6 file:rounded-full file:border-0 file:bg-blue-50 file:text-button file:font-black cursor-pointer uppercase tracking-widest">
                </div>
            </div>
            <button type="submit" class="w-full py-5 bg-button text-white rounded-[1.5rem] font-black text-sm shadow-xl shadow-blue-100 hover:bg-blue-900 transition-all active:scale-95">Comenzar Subida</button>
        </form>
    </div>
</div>

{{-- MODAL DE CONFIRMACIÓN --}}
<div id="confirmActionModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-[110] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 text-center animate-slide-up">
        <div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <h3 id="confirmTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2">¿Confirmar Acción?</h3>
        <p id="confirmMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <div class="flex gap-3">
            <button onclick="closeConfirmModal()" class="flex-1 py-4 bg-gray-50 text-gray-400 rounded-2xl font-black uppercase text-[10px] tracking-widest border border-gray-100">Cancelar</button>
            <button id="btnFinalConfirm" class="flex-1 py-4 bg-red-500 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg shadow-red-100 hover:bg-red-600 transition-all active:scale-95">Confirmar</button>
        </div>
    </div>
</div>

{{-- MODAL DE ESTADO --}}
<div id="statusModal" class="fixed inset-0 bg-black/70 backdrop-blur-md flex items-center justify-center z-[120] hidden p-4">
    <div class="bg-white w-full max-w-sm rounded-[2.5rem] shadow-2xl p-8 text-center animate-fade-in">
        <div id="statusIcon" class="mx-auto mb-6"></div>
        <h3 id="statusTitle" class="text-xl font-black text-gray-800 uppercase tracking-tight mb-2"></h3>
        <p id="statusMsg" class="text-gray-500 mb-8 text-sm font-medium leading-relaxed"></p>
        <button onclick="closeStatus()" class="w-full py-4 bg-button text-white rounded-2xl font-black uppercase text-[10px] tracking-widest shadow-lg active:scale-95">Entendido</button>
    </div>
</div>

<script>
    let currentActionId = null;
    let currentActionType = null;

    function switchTab(tabId) {
        if (window.innerWidth >= 768) return;

        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove('active', 'bg-button', 'text-white');
            btn.classList.add('text-gray-400', 'bg-white');
        });

        const target = document.getElementById('section-' + tabId);
        if(target) target.classList.remove('hidden');

        const btn = document.getElementById('tab-btn-' + tabId);
        if(btn) {
            btn.classList.add('active', 'bg-button', 'text-white');
            btn.classList.remove('text-gray-400', 'bg-white');
            btn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        }

        if(tabId === 'comunidad') {
            document.getElementById('section-metrics').classList.remove('hidden');
        } else {
            document.getElementById('section-metrics').classList.add('hidden');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (window.innerWidth < 768) {
            switchTab('comunidad');
        }
    });

    function openUploadModal() { document.getElementById('uploadModal').classList.remove('hidden'); }
    function closeUploadModal() { document.getElementById('uploadModal').classList.add('hidden'); }
    function closeStatus() { document.getElementById('statusModal').classList.add('hidden'); }
    function closeConfirmModal() { document.getElementById('confirmActionModal').classList.add('hidden'); }

    function showUIStatus(title, message, success = true) {
        const modal = document.getElementById('statusModal');
        const icon = document.getElementById('statusIcon');
        document.getElementById('statusTitle').textContent = title;
        document.getElementById('statusMsg').textContent = message;
        icon.innerHTML = success ? 
            `<div class="w-16 h-16 bg-green-50 text-green-500 rounded-2xl flex items-center justify-center mx-auto"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg></div>` :
            `<div class="w-16 h-16 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mx-auto"><svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></div>`;
        modal.classList.remove('hidden'); modal.classList.add('flex');
    }

    document.getElementById('uploadForm')?.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true; btn.textContent = 'PROCESANDO...';
        try {
            const res = await fetch("{{ route('grupos.upload-material', $slug) }}", {
                method: 'POST', body: new FormData(this), headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
            });
            const data = await res.json();
            closeUploadModal();
            if (data.success) {
                showUIStatus('¡Éxito!', 'Archivo subido correctamente.');
                setTimeout(() => location.reload(), 1200);
            } else { showUIStatus('Error', 'No se pudo subir el archivo.', false); }
        } catch (e) { showUIStatus('Error de Red', 'Sin conexión.', false); }
        finally { btn.disabled = false; btn.textContent = 'SUBIR AHORA'; }
    });

    function confirmDeleteResource(id, name) {
        currentActionId = id; currentActionType = 'resource';
        document.getElementById('confirmTitle').textContent = '¿Eliminar Material?';
        document.getElementById('confirmMsg').innerHTML = `Vas a borrar: <br><span class="font-bold text-red-500">${name}</span>`;
        document.getElementById('confirmActionModal').classList.remove('hidden');
        document.getElementById('confirmActionModal').classList.add('flex');
    }

    function confirmRemoveMember(id, name) {
        currentActionId = id; currentActionType = 'member';
        document.getElementById('confirmTitle').textContent = '¿Remover Miembro?';
        document.getElementById('confirmMsg').innerHTML = `Vas a retirar de la comunidad a: <br><span class="font-bold text-red-500">${name}</span>`;
        document.getElementById('confirmActionModal').classList.remove('hidden');
        document.getElementById('confirmActionModal').classList.add('flex');
    }

    document.getElementById('btnFinalConfirm')?.addEventListener('click', async function() {
        this.disabled = true;
        try {
            let url = currentActionType === 'resource' ? `/grupos/material/${currentActionId}/delete` : `/grupos/panel/{{ $group->category }}/members/${currentActionId}`;
            const res = await fetch(url, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' } });
            if (res.ok) location.reload();
            else showUIStatus('Error', 'Acción fallida.', false);
        } catch (err) { showUIStatus('Error Fatal', 'Conexión interrumpida.', false); }
        finally { this.disabled = false; }
    });

    function toggleGroupPassword() {
        const input = document.getElementById('group_password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
        if (input.type === 'password') {
            input.type = 'text'; eyeOpen.classList.remove('hidden'); eyeClosed.classList.add('hidden');
        } else {
            input.type = 'password'; eyeOpen.classList.add('hidden'); eyeClosed.classList.remove('hidden');
        }
    }
</script>

<style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    
    .tab-btn { background: white; color: #94a3b8; border: 1px solid #f1f5f9; }
    .tab-btn.active { background: #5cb1e3; color: white; border-color: #5cb1e3; box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.2); }

    @keyframes slide-up { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-slide-up { animation: slide-up 0.3s ease-out forwards; }
    @keyframes fade-in { from { opacity: 0; transform: scale(0.98); } to { opacity: 1; transform: scale(1); } }
    .animate-fade-in { animation: fade-in 0.3s ease-out forwards; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection