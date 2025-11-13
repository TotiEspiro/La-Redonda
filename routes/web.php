<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IntentionController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserHomeController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\admin\AnnouncementController;
use App\Http\Controllers\admin\EvangelioDiarioController;

// Rutas Públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/donaciones', [DonationController::class, 'create']);
Route::get('/intenciones', [IntentionController::class, 'create']);

// Rutas para procesar formularios
Route::post('/intenciones/guardar', [IntentionController::class, 'store']);
Route::post('/donaciones/procesar', [DonationController::class, 'store']);

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas temporales para otras páginas
Route::get('/horarios', function () {
    return view('home');
});

Route::get('/contacto', function () {
    return view('home');
});

// Rutas del Panel de Administración
Route::prefix('admin')->middleware(['admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/', [AdminController::class, 'dashboard']);
    
    // Gestión de Usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/update-roles', [AdminController::class, 'updateUserRoles'])->name('admin.users.update-roles');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Gestión de Intenciones
    Route::get('/intentions', [AdminController::class, 'intentions'])->name('admin.intentions');
    Route::get('/intentions/print', [AdminController::class, 'printIntentions'])->name('admin.intentions.print');
    Route::delete('/intentions/{id}', [AdminController::class, 'deleteIntention'])->name('admin.intentions.delete');
    Route::get('/intentions/{id}', [AdminController::class, 'getIntention'])->name('admin.intentions.show');
    
    // Gestión de Donaciones
    Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');

    // Gestión de Anuncios
    Route::get('/announcements', [AnnouncementController::class, 'index'])->name('admin.announcements.index');
    Route::get('/announcements/create', [AnnouncementController::class, 'create'])->name('admin.announcements.create');
    Route::post('/announcements', [AnnouncementController::class, 'store'])->name('admin.announcements.store');
    Route::get('/announcements/{announcement}/edit', [AnnouncementController::class, 'edit'])->name('admin.announcements.edit');
    Route::put('/announcements/{announcement}', [AnnouncementController::class, 'update'])->name('admin.announcements.update');
    Route::delete('/announcements/{announcement}', [AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');
    Route::patch('/announcements/{announcement}/toggle-status', [AnnouncementController::class, 'toggleStatus'])->name('admin.announcements.toggle-status');
    Route::delete('/announcements/delete-all', [AnnouncementController::class, 'deleteAll'])->name('admin.announcements.delete-all');

    // Evangelio Diario
    Route::get('/evangelio-diario/editar', [EvangelioDiarioController::class, 'editar'])->name('admin.evangelio-diario.editar');
    Route::put('/evangelio-diario/actualizar', [EvangelioDiarioController::class, 'actualizar'])->name('admin.evangelio-diario.actualizar');
});

// Gestión de Grupos (Rutas existentes)
Route::prefix('grupos')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('grupos.index');
    Route::get('/catequesis', [GroupController::class, 'catequesis'])->name('grupos.catequesis');
    Route::get('/jovenes', [GroupController::class, 'jovenes'])->name('grupos.jovenes');
    Route::get('/mayores', [GroupController::class, 'mayores'])->name('grupos.mayores');
    Route::get('/especiales', [GroupController::class, 'especiales'])->name('grupos.especiales');
    Route::get('/{group}', [GroupController::class, 'show'])->name('grupos.show');
});

// Clear Loading Session
Route::post('/clear-loading-session', [AuthController::class, 'clearLoadingSession'])
    ->name('clear.loading.session');

// Rutas de perfil
Route::middleware(['auth'])->group(function () {
    Route::get('/perfil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/perfil/editar', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/perfil/actualizar', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/perfil/cambiar-contraseña', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::post('/perfil/cambiar-contraseña', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

// Ruta para olvidar contraseña (placeholder)
Route::get('/olvide-contraseña', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Rutas del Diario de La Redonda - Solo para usuarios con roles de grupo parroquial
Route::middleware(['auth', 'grupo_parroquial'])->prefix('diario')->group(function () {
    Route::get('/', [UserHomeController::class, 'index'])->name('diario.index');
    Route::post('/entrada/crear', [UserHomeController::class, 'createEntry'])->name('diario.create');
    Route::get('/entrada/{id}', [UserHomeController::class, 'getEntry'])->name('diario.get');
    Route::put('/entrada/{id}/actualizar', [UserHomeController::class, 'updateEntry'])->name('diario.update');
    Route::delete('/entrada/{id}/eliminar', [UserHomeController::class, 'deleteEntry'])->name('diario.delete');
    Route::post('/entrada/{id}/favorito', [UserHomeController::class, 'toggleFavorite'])->name('diario.favorite');
});

// Rutas de Gestión de Grupos Parroquiales - Nuevas rutas
Route::middleware(['auth'])->prefix('grupos')->group(function () {
    // Dashboard de administración para AdminGrupoParroquial
    Route::get('/{groupRole}/dashboard', [GroupController::class, 'groupDashboard'])->name('grupos.dashboard');
    
    // Vista de materiales para miembros del grupo
    Route::get('/{groupRole}/materiales', [GroupController::class, 'groupMaterials'])->name('groups.materials');
    
    // Acciones de administración
    Route::post('/{groupRole}/upload', [GroupController::class, 'uploadMaterial'])->name('groups.upload');
    Route::delete('/material/{id}/delete', [GroupController::class, 'deleteMaterial'])->name('groups.delete');
    Route::get('/material/{id}/download', [GroupController::class, 'downloadMaterial'])->name('groups.download');
});