<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Verified;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;
use App\Notifications\SecurityCodeNotification;

class AuthController extends Controller
{
    public function showLogin() { return view('auth.login'); }
    public function showRegister() { return view('auth.register'); }
    public function showLinkRequestForm() { return view('auth.forgot-password'); }

    public function showResetForm(Request $request, $token)
    {
        $user = User::where('email', $request->email)->first();
        return view('auth.reset-password')->with(['token' => $token, 'email' => $request->email, 'user' => $user]);
    }

    /**
     * AVISO DE VERIFICACIÓN
     * Esta vista se muestra cuando el usuario está logueado pero no verificado.
     */
    public function showVerificationNotice()
    {
        return Auth::user()->hasVerifiedEmail() 
            ? redirect()->route('dashboard') 
            : view('auth.verify-email');
    }

    /**
     * PROCESAR VERIFICACIÓN DESDE EL MAIL
     */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'El enlace de verificación no es válido o ha expirado.']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login')->with('success', 'Tu email ya había sido verificado. Podés ingresar.');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return redirect()->route('login')->with('success', '¡Excelente! Tu cuenta ha sido activada correctamente. Ya podés iniciar sesión.');
    }

    /**
     * REENVIAR EMAIL DE ACTIVACIÓN
     */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }

    /**
     * INICIO DE SESIÓN
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Retraso para que la pantalla de carga sea visible
        usleep(1200000); 

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            /**
             * LÓGICA PARA CUENTAS NUEVAS VS EXISTENTES
             * Fecha de corte: 21 de Febrero de 2026.
             */
            $cutoffDate = Carbon::parse('2026-02-21 00:00:00');

            // Flujo Profesional: Si no está verificado y es cuenta nueva, redirigir al aviso (sin desloguear)
            if (!$user->email_verified_at && $user->created_at->gt($cutoffDate)) {
                return redirect()->route('verification.notice');
            }

            // Validar Inactividad (7 días)
            if ($user->last_login_at && Carbon::parse($user->last_login_at)->diffInDays(Carbon::now()) > 7) {
                return $this->sendSecurityCode($user);
            }

            return $this->finalizeLogin($request, $user);
        }

        return back()->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.'])->onlyInput('email');
    }

    /**
     * Genera y envía código de seguridad por inactividad.
     */
    private function sendSecurityCode($user)
    {
        $code = (string)rand(100000, 999999);
        $user->update(['security_code' => Hash::make($code)]);
        
        try {
            $user->notify(new SecurityCodeNotification($code));
        } catch (\Exception $e) {
            Log::error("Error enviando código de seguridad por mail: " . $e->getMessage());
        }
        
        session(['verify_user_id' => $user->id]);
        Auth::logout();
        return redirect()->route('auth.verify.code')->with('status', 'Seguridad: Te enviamos un código de validación porque no has ingresado en más de una semana.');
    }

    public function showVerifyCode()
    {
        if (!session('verify_user_id')) return redirect()->route('login');
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $user = User::find(session('verify_user_id'));

        if ($user && Hash::check($request->code, $user->security_code)) {
            $user->update([
                'security_code' => null, 
                'last_login_at' => Carbon::now()
            ]);
            
            Auth::login($user);
            session()->forget('verify_user_id');
            return redirect()->intended('dashboard');
        }
        return back()->withErrors(['code' => 'El código es incorrecto o ya expiró.']);
    }

    private function finalizeLogin($request, $user)
    {
        $user->update(['last_login_at' => Carbon::now()]);
        $request->session()->regenerate();
        
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->intended('dashboard');
    }

    /**
     * REGISTRO DE USUARIO
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required'
        ], [
            'email.unique' => 'Este correo ya está registrado.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'g-recaptcha-response.required' => 'Confirma que no eres un robot.'
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$captchaResponse->json('success')) return back()->withErrors(['captcha' => 'La validación de seguridad falló.']);

        usleep(1200000);

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'email_verified_at' => null 
            ]);

            $role = Role::where('slug', 'usuario')->first();
            if ($role) $user->roles()->attach($role->id);
            DB::commit();

            // Enviar notificación nativa
            $user->sendEmailVerificationNotification();

            // Flujo Profesional: Loguear y mandar al aviso de verificación
            Auth::login($user);
            return redirect()->route('verification.notice');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en registro: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error técnico al crear la cuenta. Intentalo de nuevo.']);
        }
    }

    // --- SOCIALITE (Google / Facebook) ---

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) return redirect()->route('login');
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        usleep(1200000); 
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                $user->update([
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $provider,
                    'email_verified_at' => $user->email_verified_at ?? Carbon::now(), 
                ]);
            } else {
                $user = User::create([
                    'name' => $socialUser->getName(),
                    'email' => $socialUser->getEmail(),
                    'provider_id' => $socialUser->getId(),
                    'provider_name' => $provider,
                    'avatar' => $socialUser->getAvatar(),
                    'password' => Hash::make(Str::random(24)),
                    'onboarding_completed' => false,
                    'email_verified_at' => Carbon::now(), 
                ]);
                $role = Role::where('slug', 'usuario')->first();
                if ($role) $user->roles()->attach($role->id);
            }

            if ($user->last_login_at && Carbon::parse($user->last_login_at)->diffInDays(Carbon::now()) > 7) {
                return $this->sendSecurityCode($user);
            }

            Auth::login($user);
            $user->update(['last_login_at' => Carbon::now()]);
            return $user->age ? redirect()->route('dashboard') : redirect()->route('profile.edit');
        } catch (\Exception $e) { 
            return redirect('/login')->withErrors(['email' => 'Error con el acceso de Google/Facebook.']); 
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updatePushSubscription(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required', 'keys.auth' => 'required', 'keys.p256dh' => 'required']);
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);
        $user->updatePushSubscription($request->endpoint, $request->keys['p256dh'], $request->keys['auth']);
        return response()->json(['success' => true]);
    }

    /**
     * Recuperación de contraseña.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email', 'g-recaptcha-response' => 'required']);
        $status = Password::broker()->sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? back()->with('status', __($status)) : back()->withErrors(['email' => __($status)]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['token' => 'required', 'email' => 'required|email', 'password' => 'required|min:8|confirmed']);
        $status = Password::broker()->reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();
            event(new PasswordReset($user));
        });
        return $status === Password::PASSWORD_RESET ? redirect()->route('login')->with('success', 'Tu contraseña ha sido actualizada correctamente.') : back()->withErrors(['email' => [__($status)]]);
    }
}