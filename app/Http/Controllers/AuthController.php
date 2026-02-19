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
use Laravel\Socialite\Facades\Socialite;

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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isAdmin() || $user->isSuperAdmin()) return redirect()->route('admin.dashboard');
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    /**
     * Procesa el envío del correo de recuperación con validación de Captcha.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validación básica y de Captcha
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => 'Por favor, completa el captcha de seguridad.'
        ]);

        // 2. Verificación de seguridad reCAPTCHA
        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$captchaResponse->json('success')) {
            return back()->withErrors(['captcha' => 'La verificación de seguridad falló.'])->withInput();
        }

        // 3. Envío del enlace de recuperación
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        // Si el estado es exitoso, devolvemos con el mensaje de Laravel
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

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

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Verificación de reCAPTCHA
        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$captchaResponse->json('success')) {
            return back()->withErrors(['captcha' => 'Fallo la verificación de seguridad.'])->withInput();
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'onboarding_completed' => false
            ]);

            // Asignar rol por defecto
            $userRole = Role::where('slug', 'usuario')->first();
            if ($userRole) {
                $user->roles()->attach($userRole->id);
            }

            DB::commit();

            Auth::login($user);
            return redirect()->route('profile.edit')->with('success', '¡Bienvenido! Por favor completa tu edad.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error en registro: " . $e->getMessage());
            return back()->withErrors(['error' => 'Error al crear la cuenta.'])->withInput();
        }
    }

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) return redirect()->route('login');
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error con ' . ucfirst($provider));
        }

        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            if ($user->provider_name !== null && $user->provider_name !== $provider) {
                return redirect()->route('login')->with('error', "Email registrado con {$user->provider_name}.");
            }
            if ($user->provider_name === null) {
                $user->update([
                    'provider_id'   => $socialUser->getId(),
                    'provider_name' => $provider,
                    'avatar'        => $socialUser->getAvatar()
                ]);
            }
        } else {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'avatar' => $socialUser->getAvatar(),
                'password' => Hash::make(Str::random(24)),
                'onboarding_completed' => false,
            ]);

            $userRole = Role::where('slug', 'usuario')->first();
            if ($userRole) $user->roles()->attach($userRole->id);
        }

        Auth::login($user);
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
        $this->validate($request, [
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);

        $user->updatePushSubscription(
            $request->endpoint, 
            $request->keys['p256dh'], 
            $request->keys['auth']
        );

        return response()->json(['success' => true]);
    }
}