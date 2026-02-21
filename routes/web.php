<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IntentionController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\DiarioController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\EvangelioDiarioController;

/*
|--------------------------------------------------------------------------
| Redirecciones Globales
|--------------------------------------------------------------------------
*/
Route::get('/home', function () {
    return Auth::check() ? redirect()->route('dashboard') : redirect()->route('home'); 
});

/*
|--------------------------------------------------------------------------
| Rutas Públicas (ACCESO SIN LOGUEARSE)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/donaciones', [DonationController::class, 'create'])->name('donations.create');
Route::get('/intenciones', [IntentionController::class, 'create'])->name('intentions.create');
Route::post('/intenciones/guardar', [IntentionController::class, 'store'])->name('intentions.store');
Route::post('/donaciones/procesar', [DonationController::class, 'store'])->name('donations.process');

// Información de Grupos (Público)
Route::prefix('grupos')->group(function () {
    Route::get('/', [GroupController::class, 'index'])->name('grupos.index');
    Route::get('/catequesis', [GroupController::class, 'category'])->defaults('slug', 'catequesis')->name('grupos.catequesis');
    Route::get('/jovenes', [GroupController::class, 'category'])->defaults('slug', 'jovenes')->name('grupos.jovenes');
    Route::get('/mayores', [GroupController::class, 'category'])->defaults('slug', 'mayores')->name('grupos.mayores');
    Route::get('/mas_grupos', [GroupController::class, 'category'])->defaults('slug', 'mas_grupos')->name('grupos.especiales');
});

/*
|--------------------------------------------------------------------------
| Autenticación y Recuperación
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/logout', [AuthController::class, 'logout']); 

// --- Verificación de Email (Nativas de Laravel) ---
// Estas rutas son necesarias para que MustVerifyEmail funcione correctamente
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])->middleware(['signed'])->name('verification.verify');
Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Ruta para mostrar la vista de enlace expirado
Route::get('/email/verificacion-expirada', function () {
    return view('auth.verification-expire');
})->name('auth.verification.expire'); // <--- Asegúrate de que se llame así

// --- Login Social (Google y Facebook) ---
Route::get('/auth/{provider}/redirect', [AuthController::class, 'redirectToProvider'])->name('social.redirect');
Route::get('/auth/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('social.callback');

// --- Recuperación de Contraseña ---
Route::get('/forgot-password', fn() => view('auth.forgot-password'))->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');

// --- Validación por Inactividad (Código de Seguridad) ---
Route::get('/verificar-codigo', [AuthController::class, 'showVerifyCode'])->name('auth.verify.code');
Route::post('/verificar-codigo', [AuthController::class, 'verifyCode'])->name('auth.verify.code.post');

// --- Secciones Legales ---
Route::get('/terminos-y-condiciones', [HomeController::class, 'terminos'])->name('legal.terminos');
Route::get('/politica-de-privacidad', [HomeController::class, 'privacidad'])->name('legal.privacidad');


/*
|--------------------------------------------------------------------------
| Rutas Protegidas (REQUIEREN LOGIN)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // HUB CENTRAL DEL USUARIO
    Route::get('/dashboard', [GroupController::class, 'userDashboard'])->name('dashboard');

    // --- Sistema de Notificaciones (Polling y Push) ---
    Route::post('/notifications/subscribe', [AuthController::class, 'updatePushSubscription'])->name('notifications.subscribe');
    
    Route::get('/notificaciones/unread-count', function() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        return response()->json([
            'count' => $user->unreadNotifications->count(),
            'latest' => $user->notifications()->latest()->first()
        ]);
    })->name('notifications.unread-count');

    Route::post('/notificaciones/marcar-leida', function() {
        /** @var \App\Models\User $user */
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.read-all');

    // --- Perfil de Usuario ---
    Route::prefix('perfil')->group(function () {
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/editar', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/actualizar', [ProfileController::class, 'update'])->name('profile.update');
        Route::get('/password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
        Route::post('/password', [ProfileController::class, 'changePassword'])->name('profile.password');
        
        // NUEVAS RUTAS DE GESTIÓN DE ALERTAS Y LIMPIEZA
        Route::post('/preferencias', [ProfileController::class, 'updatePreference'])->name('profile.update-preference');
        Route::delete('/notificaciones/limpiar', [ProfileController::class, 'destroyAllNotifications'])->name('profile.notifications.clear');
    });
    
    // --- Diario Espiritual ---
    Route::prefix('diario')->group(function () {
        Route::get('/', [DiarioController::class, 'index'])->name('diario.index');
        Route::post('/', [DiarioController::class, 'store'])->name('diario.store');
        Route::get('/{id}', [DiarioController::class, 'show'])->name('diario.show');
        Route::put('/{id}', [DiarioController::class, 'update'])->name('diario.update');
        Route::delete('/{id}', [DiarioController::class, 'destroy'])->name('diario.destroy');
    });

    // --- Gestión de Comunidades (Miembros y Coordinadores) ---
    Route::prefix('grupos')->group(function () {
        // Dashboard de Grupo (Solo Coordinadores o acceso a materiales)
        Route::get('/{groupRole}/panel-comunidad', [GroupController::class, 'groupDashboard'])->name('grupos.dashboard');
        
        // --- Seguridad de Acceso (NUEVO: Contraseña de Grupo) ---
        Route::get('/{groupRole}/validar-acceso', [GroupController::class, 'showVerifyPassword'])->name('grupos.verify-form');
        Route::post('/{groupRole}/verificar-acceso', [GroupController::class, 'verifyPassword'])->name('grupos.verify-password');
        Route::patch('/{groupRole}/configurar-clave', [GroupController::class, 'updateGroupPassword'])->name('grupos.update-password');

        // Materiales
        Route::get('/{groupRole}/materiales', [GroupController::class, 'groupMaterials'])->name('grupos.materials');
        Route::post('/{groupRole}/upload', [GroupController::class, 'uploadMaterial'])->name('grupos.upload-material');
        Route::delete('/material/{id}/delete', [GroupController::class, 'deleteMaterial'])->name('groups.delete');
        Route::get('/material/{id}/download', [GroupController::class, 'downloadMaterial'])->name('groups.download');
        Route::get('/material/{id}/view', [GroupController::class, 'viewMaterial'])->name('groups.view');

        // Solicitudes e Integrantes
        Route::post('/{groupRole}/solicitar', [GroupController::class, 'sendRequest'])->name('grupos.send-request');
        Route::post('/procesar-solicitud/{requestId}', [GroupController::class, 'handleRequest'])->name('grupos.handle-request');
        Route::delete('/panel/{groupRole}/members/{userId}', [GroupController::class, 'removeMember'])->name('grupos.remove-member');
    });

    // Onboarding y Recomendaciones
    Route::post('/onboarding/complete', [GroupController::class, 'completeOnboarding'])->name('onboarding.complete');
    Route::get('/onboarding/recommended', [GroupController::class, 'getRecommendedGroups'])->name('onboarding.recommended');

    // Rutas de Prueba (Debug)
    Route::get('/test-push', function() {
        return "Para probar Push, usa la ruta /test-vibracion si tienes el celular vinculado.";
    });
    
    Route::get('/test-vibracion', function() {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user) return "Debes estar logueado";
        $user->notify(new \App\Notifications\AvisoComunidad(
            "¡Prueba Exitosa!", 
            "Si estás leyendo esto, tu celular ya recibe avisos de La Redonda.",
            route('dashboard')
        ));
    });
});

/*
|--------------------------------------------------------------------------
| Panel de Administración General (Sólo SuperAdmins / Admins)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::post('/users/{id}/update-roles', [AdminController::class, 'updateUserRoles'])->name('admin.users.update-roles');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');
    
    // Intenciones
    Route::get('/intentions', [AdminController::class, 'intentions'])->name('admin.intentions');
    Route::get('/intentions/imprimir', [AdminController::class, 'printIntentions'])->name('admin.intentions.print');
    Route::delete('/intentions/delete-all', [AdminController::class, 'deleteAllIntentions'])->name('admin.intentions.delete-all');
    Route::delete('/intentions/{id}', [AdminController::class, 'deleteIntention'])->name('admin.intentions.delete');
    
    // Donaciones e Informes
    Route::get('/donations', [AdminController::class, 'donations'])->name('admin.donations');
    
    // Contenido (Anuncios y Evangelio)
    Route::resource('announcements', AnnouncementController::class, ['as' => 'admin']);
    Route::get('/evangelio-diario/editar', [EvangelioDiarioController::class, 'editar'])->name('admin.evangelio-diario.editar');
    Route::put('/evangelio-diario/actualizar', [EvangelioDiarioController::class, 'actualizar'])->name('admin.evangelio-diario.actualizar');
});

/*
|--------------------------------------------------------------------------
| Fallback y Errores
|--------------------------------------------------------------------------
*/
Route::fallback(fn() => response()->view('errors.404', [], 404));
Route::get('/mantenimiento', function () {
    return view('errors.503');
})->name('maintenance.preview');
