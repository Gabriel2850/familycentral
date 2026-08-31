<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class SecurityQuestionResetController extends Controller
{
    // Paso 1: Formulario de ingreso de correo o usuario
    public function showFindAccountForm()
    {
        return view('auth.security-reset.find-account');
    }

    // Paso 2: Verificar usuario y mostrar las preguntas de seguridad
    public function verifyAccount(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'No encontramos ningún usuario con ese correo electrónico.',
        ]);

        $email = trim(strtolower($request->email));
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        // Verificar si las preguntas o respuestas están vacías en la BD
        if (
            empty($user->security_question_1) || 
            empty($user->security_answer_1) || 
            empty($user->security_question_2) || 
            empty($user->security_answer_2)
        ) {
            return redirect()->back()->withErrors([
                'email' => 'Este usuario no tiene preguntas de seguridad configuradas correctamente. Contacta al administrador.'
            ]);
        }

        // Guardamos temporalmente el ID en la sesión
        session(['reset_user_id' => $user->id]);

        return view('auth.security-reset.answer-questions', compact('user'));
    }

    // Paso 3: Validar las respuestas y mostrar formulario de nueva contraseña
    public function processAnswers(Request $request)
    {
        $userId = session('reset_user_id');
        if (!$userId) {
            return redirect()->route('password.security.request')->withErrors(['email' => 'Sesión expirada.']);
        }

        $user = User::findOrFail($userId);

        $request->validate([
            'answer_1' => 'required|string',
            'answer_2' => 'required|string',
        ]);

        $validAnswer1 = Hash::check(trim(mb_strtolower($request->answer_1)), $user->security_answer_1);
        $validAnswer2 = Hash::check(trim(mb_strtolower($request->answer_2)), $user->security_answer_2);

        if (!$validAnswer1 || !$validAnswer2) {
            return redirect()->back()->withErrors(['answers' => 'Una o ambas respuestas son incorrectas.']);
        }

        session(['security_passed_user_id' => $user->id]);

        return redirect()->route('password.security.reset_form');
    }

    // Paso 4: Formulario de cambio de contraseña
    public function showResetForm()
    {
        if (!session('security_passed_user_id')) {
            return redirect()->route('password.security.request');
        }

        return view('auth.security-reset.new-password');
    }

    // Paso 5: Guardar la nueva contraseña
    public function updatePassword(Request $request)
    {
        $userId = session('security_passed_user_id');
        if (!$userId) {
            return redirect()->route('password.security.request');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($userId);
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Limpiar la sesión
        session()->forget(['reset_user_id', 'security_passed_user_id']);

        return redirect()->route('login')->with('status', '¡Tu contraseña ha sido restablecida correctamente!');
    }

   /**
 * Permite al usuario autenticado guardar sus preguntas de seguridad desde su perfil.
 */
public function storeUserQuestions(Request $request)
{
    $request->validate([
        'security_question_1' => 'required|string',
        'security_answer_1'   => 'required|string|min:2',
        'security_question_2' => 'required|string',
        'security_answer_2'   => 'required|string|min:2',
    ]);

    // Usar $request->user() le otorga a Intelephense la definición exacta del modelo User
    /** @var \App\Models\User $user */
    $user = $request->user();

    $user->update([
        'security_question_1' => $request->security_question_1,
        'security_answer_1'   => Hash::make(trim(mb_strtolower($request->security_answer_1))),
        'security_question_2' => $request->security_question_2,
        'security_answer_2'   => Hash::make(trim(mb_strtolower($request->security_answer_2))),
    ]);

    return redirect()->back()->with('status', 'Preguntas de seguridad actualizadas con éxito.');
}
}