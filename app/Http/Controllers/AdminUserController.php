<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminUserController extends Controller
{
    /**
     * Muestra la lista de usuarios y estadísticas del panel.
     */
    public function index(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $users = $query->orderBy('id', 'desc')->paginate(10);

        $stats = [
            'total'     => User::count(),
            'admins'    => User::where('role', 'admin')->count(),
            'operators' => User::where('role', '!=', 'admin')->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Registra un nuevo usuario.
     */
    public function store(Request $request)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', Rules\Password::defaults()],
            'role'     => ['required', 'string', 'in:admin,operador,user'],
        ]);

        User::create([
            'name'     => $request->input('name'),
            'email'    => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role'     => $request->input('role'),
        ]);

        return redirect()->back()->with('success', 'Usuario creado exitosamente.');
    }

    /**
     * Actualiza la información básica del usuario.
     */
    public function update(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role'  => ['required', 'string', 'in:admin,operador,user'],
        ]);

        $user->update([
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'role'  => $request->input('role'),
        ]);

        return redirect()->back()->with('success', 'Datos del usuario actualizados.');
    }

    /**
     * Actualiza la contraseña del usuario.
     */
    public function updatePassword(Request $request, User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        $request->validate([
            'password' => ['required', Rules\Password::defaults()],
        ]);

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return redirect()->back()->with('success', 'Contraseña actualizada correctamente.');
    }

    /**
     * Elimina a un usuario.
     */
    public function destroy(User $user)
    {
        /** @var User $currentUser */
        $currentUser = Auth::user();

        if (!$currentUser || $currentUser->role !== 'admin') {
            abort(403, 'Acceso denegado');
        }

        if ($user->id === $currentUser->id) {
            return redirect()->back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado con éxito.');
    }
}