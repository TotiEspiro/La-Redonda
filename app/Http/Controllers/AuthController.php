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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Laravel\Socialite\Facades\Socialite;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Muestra el formulario de olvido de contraseña.
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Muestra el formulario para establecer la nueva contraseña.
     */
    public function showResetForm(Request $request, $token)
    {
        $user = User::where('email', $request->email)->first();

        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
            'user'  => $user
        ]);
    }

    /**
     * Inicio de sesión con validación de email e inactividad.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Retraso para ver el loading
        usleep(1200000); 

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 1. Validar verificación de Email (Seguridad obligatoria)
            if (!$user->email_verified_at) {
                Auth::logout();
                return back()->withErrors(['email' => 'Debes verificar tu cuenta. Revisa el correo enviado a tu casilla.']);
            }

            // 2. Validar inactividad (más de 1 semana)
            if ($user->last_login_at && Carbon::parse($user->last_login_at)->diffInDays(Carbon::now()) > 7) {
                return $this->triggerSecurityVerification($user);
            }

            return $this->handleSuccessfulLogin($request, $user);
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Genera y envía código de seguridad por inactividad.
     */
    private function triggerSecurityVerification($user)
    {
        $code = (string)rand(100000, 999999);
        
        // Guardamos el código hasheado para máxima seguridad
        $user->update(['security_code' => Hash::make($code)]);

        // NOTA: Para enviar el mail, debes tener configurado el Mailer y una clase mailable
        // Mail::to($user->email)->send(new \App\Mail\SecurityCodeMail($code));

        session(['pending_verification_user_id' => $user->id]);
        Auth::logout();

        return redirect()->route('auth.verify.code')->with('status', 'Seguridad: Enviamos un código de validación a tu mail porque no has ingresado en más de una semana.');
    }

    public function showVerifyCode()
    {
        if (!session('pending_verification_user_id')) return redirect()->route('login');
        return view('auth.verify-code');
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $user = User::find(session('pending_verification_user_id'));

        if ($user && Hash::check($request->code, $user->security_code)) {
            $user->update([
                'security_code' => null, 
                'last_login_at' => Carbon::now()
            ]);
            
            Auth::login($user);
            session()->forget('pending_verification_user_id');
            return redirect()->intended('dashboard');
        }

        return back()->withErrors(['code' => 'El código es incorrecto o ha expirado.']);
    }

    private function handleSuccessfulLogin($request, $user)
    {
        $user->update(['last_login_at' => Carbon::now()]);
        $request->session()->regenerate();
        
        if ($user->isAdmin() || $user->isSuperAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->intended('dashboard');
    }

    /**
     * Registro de usuario con validación de mail obligatoria.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required'
        ]);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$captchaResponse->json('success')) return back()->withErrors(['captcha' => 'Fallo la verificación de seguridad.']);

        usleep(1200000); 

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'onboarding_completed' => false,
                'email_verified_at' => null // Obligamos a verificar
            ]);

            $userRole = Role::where('slug', 'usuario')->first();
            if ($userRole) $user->roles()->attach($userRole->id);

            DB::commit();

            // Disparar mail de verificación nativo de Laravel
            $user->sendEmailVerificationNotification();

            return redirect()->route('login')->with('success', '¡Cuenta creada! Enviamos un link de validación a tu mail. Debes activarlo para poder ingresar.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en registro: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error técnico al crear la cuenta.']);
        }
    }

    /**
     * Recuperación de contraseña (Lógica original mantenida).
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email', 'g-recaptcha-response' => 'required']);

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
        ]);

        if (!$captchaResponse->json('success')) return back()->withErrors(['captcha' => 'Seguridad fallida.']);

        $status = Password::broker()->sendResetLink($request->only('email'));

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate(['token' => 'required', 'email' => 'required|email', 'password' => 'required|min:8|confirmed']);

        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Tu contraseña ha sido actualizada correctamente.')
            : back()->withErrors(['email' => [__($status)]]);
    }

    /**
     * Socialite (Google/Facebook) con soporte para inactividad y pantalla de carga.
     */
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
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error con el acceso de Google/Facebook.');
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                // Google ya validó el mail, así que lo marcamos como verificado si no lo estaba
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

        // Chequear inactividad incluso en login social
        if ($user->last_login_at && Carbon::parse($user->last_login_at)->diffInDays(Carbon::now()) > 7) {
            return $this->triggerSecurityVerification($user);
        }

        Auth::login($user);
        $user->update(['last_login_at' => Carbon::now()]);
        return $user->age ? redirect()->route('dashboard') : redirect()->route('profile.edit');
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
}