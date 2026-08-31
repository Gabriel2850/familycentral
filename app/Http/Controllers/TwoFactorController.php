<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PragmaRX\Google2FA\Google2FA;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class TwoFactorController extends Controller
{
    // Mostrar pantalla para activar 2FA y escanear QR
    public function showSetup(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $google2fa = new Google2FA();

        if (!$user->google2fa_secret) {
            $user->google2fa_secret = $google2fa->generateSecretKey();
            $user->save();
        }

        $qrCodeUrl = $google2fa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->google2fa_secret
        );

        // Generar código QR en formato SVG de forma 100% nativa
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrImage = $writer->writeString($qrCodeUrl);

        return view('livewire.profile.two-factor-setup',[
            'qrImage' => $qrImage,
            'secret'  => $user->google2fa_secret,
            'enabled' => $user->two_factor_enabled
        ]);
    }

    // Confirmar y activar 2FA ingresando el primer código de la App
    public function enable(Request $request)
{
    $request->validate(['code' => 'required|digits:6']);

    /** @var User $user */
    $user = $request->user();
    $google2fa = new Google2FA();

    // Pasar 8 como tercer argumento extiende la ventana de tolerancia de tiempo
    $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code, 8);

    if ($valid) {
        $user->two_factor_enabled = true;
        $user->save();
        return redirect()->back()->with('status', '¡Autenticación de 2 Factores activada con éxito!');
    }

    return redirect()->back()->withErrors(['code' => 'El código de verificación es incorrecto.']);
}

    // Desactivar 2FA
    public function disable(Request $request)
    {
        /** @var User $user */
        $user = $request->user();
        $user->two_factor_enabled = false;
        $user->google2fa_secret = null;
        $user->save();

        return redirect()->back()->with('status', 'La autenticación de 2 factores ha sido desactivada.');
    }

    // Vista donde el usuario ingresa el código de 6 dígitos al iniciar sesión
    public function showChallenge()
    {
        if (!session('2fa_user_id')) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    // Validar el código enviado en el Login
    public function verifyChallenge(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);

        $userId = session('2fa_user_id');

        if (!$userId) {
            return redirect()->route('login');
        }

        $user = User::findOrFail($userId);

        $google2fa = new Google2FA();
        $valid = $google2fa->verifyKey($user->google2fa_secret, $request->code, 8);

        if ($valid) {
            session()->forget('2fa_user_id');
            Auth::login($user);
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->withErrors(['code' => 'El código es inválido o ha expirado.']);
    }
}