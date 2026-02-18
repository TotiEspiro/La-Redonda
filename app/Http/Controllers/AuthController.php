<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str; // Importante para generar la contraseña aleatoria
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

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();
            if ($user->isAdmin()) return redirect()->route('admin.dashboard');
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'required'
        ], ['g-recaptcha-response.required' => 'Por favor, confirma que no eres un robot.']);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $captchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => env('RECAPTCHA_SECRET_KEY'),
            'response' => $request->input('g-recaptcha-response'),
            'remoteip' => $request->ip(),
        ]);

        if (!$captchaResponse->json('success')) return back()->withErrors(['captcha' => 'Fallo la verificación de seguridad.'])->withInput();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'onboarding_completed' => false
        ]);

        Auth::login($user);
        return redirect()->route('profile.edit')->with('info', '¡Bienvenido! Completa tu edad.');
    }

    public function redirectToProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) return redirect()->route('login');
        return Socialite::driver($provider)->redirect();
    }

    /**
     * MANEJO DEL CALLBACK (Corregido para evitar errores de integridad en la BD)
     */
    public function handleProviderCallback($provider)
    {
        try {
            // Usamos stateless() para evitar problemas de sesión en entornos cloud
            $socialUser = Socialite::driver($provider)->stateless()->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->with('error', 'Error de conexión con ' . ucfirst($provider) . '. Inténtalo de nuevo.');
        }

        // Buscamos usuario por email
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            // CASO 1: El usuario ya existe. Vinculamos o actualizamos datos.
            if ($user->provider_name !== null && $user->provider_name !== $provider) {
                return redirect()->route('login')->with('error', "Este email ya está registrado con {$user->provider_name}. Ingresa con ese botón.");
            }

            if ($user->provider_name === null) {
                // Vinculamos cuenta manual con la red social para mayor comodidad
                $user->update([
                    'provider_id'   => $socialUser->getId(),
                    'provider_name' => $provider,
                    'avatar'        => $socialUser->getAvatar()
                ]);
            }
        } else {
            // CASO 3: Usuario totalmente nuevo (Aquí estaba el error)
            // Generamos una contraseña aleatoria de 24 caracteres para cumplir con la BD
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'provider_id' => $socialUser->getId(),
                'provider_name' => $provider,
                'avatar' => $socialUser->getAvatar(),
                'password' => Hash::make(Str::random(24)), // <--- SOLUCIÓN: Nunca enviamos NULL
                'onboarding_completed' => false,
            ]);
        }

        Auth::login($user);

        // Si es la primera vez o no tiene edad, lo mandamos a completar su perfil
        if (!$user->age) {
            return redirect()->route('profile.edit')->with('info', 'Por favor, registra tu edad para poder inscribirte en grupos.');
        }

        return redirect()->route('dashboard');
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
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required'
        ]);

        $user = Auth::user();
        if (!$user) return response()->json(['success' => false], 401);

        $user->updatePushSubscription($request->endpoint, $request->keys['p256dh'], $request->keys['auth']);
        return response()->json(['success' => true]);
    }
}