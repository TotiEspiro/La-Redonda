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

    /**
     * Muestra el formulario para establecer la nueva contraseña.
     */
    public function showResetForm(Request $request, $token)
    {
        // Buscamos al usuario por el email que viene en la URL para que la vista tenga el nombre ($user->name)
        $user = User::where('email', $request->email)->first();

        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email,
            'user'  => $user
        ]);
    }

    /**
     * Procesa el inicio de sesión con retraso para la pantalla de carga.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Añadimos un pequeño retraso para que la pantalla de carga sea visible
        usleep(1200000); // 1.2 segundos

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            
            if ($user->isAdmin() || $user->isSuperAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            
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
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'required'
        ], [
            'g-recaptcha-response.required' => 'Por favor, completa el captcha de seguridad.'
        ]);

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$captchaResponse->json('success')) {
            return back()->withErrors(['captcha' => 'La verificación de seguridad falló.'])->withInput();
        }

        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * PROCESA LA ACTUALIZACIÓN FINAL
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        try {
            $status = Password::broker()->reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->password = Hash::make($password);
                    $user->setRememberToken(Str::random(60));
                    $user->save();

                    event(new PasswordReset($user));
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('login')->with('success', 'Tu contraseña ha sido actualizada correctamente.');
            }

            return back()->withErrors(['email' => [__($status)]])->withInput($request->only('email'));

        } catch (\Exception $e) {
            Log::error('Error crítico al resetear contraseña: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocurrió un error al actualizar la contraseña.'])->withInput($request->only('email'));
        }
    }

    /**
     * Registro de usuario con retraso para la pantalla de carga.
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

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$captchaResponse->json('success')) {
            return back()->withErrors(['captcha' => 'Fallo la verificación de seguridad.'])->withInput();
        }

        // Retraso para que se vea el progreso en el registro
        usleep(1500000); // 1.5 segundos

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'onboarding_completed' => false
            ]);

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