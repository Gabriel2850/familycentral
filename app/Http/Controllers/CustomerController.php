<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Muestra el listado de todos los clientes.
     */
    public function index()
    {
        $customers = Customer::latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Busca o registra un cliente y gestiona su recurrencia automáticamente.
     */
    public function registerOrFind(Request $request)
    {
        // 1. Validar datos mínimos requeridos
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20',
            'email'   => 'nullable|email',
            'address' => 'required|string',
        ]);

        // 2. Buscar si el cliente ya existe por su teléfono
        $customer = Customer::where('phone', $validated['phone'])->first();

        if ($customer) {
            // 🔄 Si YA existe y agenda un nuevo envío, se marca como Recurrente
            if (!$customer->is_recurrent) {
                $customer->update(['is_recurrent' => true]);
            }
        } else {
            // 🆕 Si NO existe, se crea como Cliente Nuevo y se guarda la fecha de primer envío
            $customer = Customer::create([
                'name'             => $validated['name'],
                'phone'            => $validated['phone'],
                'email'            => $validated['email'],
                'address'          => $validated['address'],
                'is_recurrent'     => false,
                'first_shipped_at' => now(), // Registra la fecha y hora actual
            ]);
        }

        return $customer;
    }

    /**
     * Muestra los detalles de un cliente específico.
     */
    public function show(Customer $customer)
    {
        return view('customers.show', compact('customer'));
    }

    /**
     * Eliminación de cliente (Restringida exclusivamente a Administradores).
     */
    public function destroy(Customer $customer)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 🔒 Verificamos el rol directamente desde el objeto de usuario activo
        if (!$user || $user->role !== 'admin') {
            abort(403, 'Solo un Administrador de Familyenvios puede eliminar registros de clientes.');
        }

        $customer->delete(); // Aplica el Soft Delete

        return redirect()->back()->with('success', 'Cliente eliminado con éxito.');
    }
}