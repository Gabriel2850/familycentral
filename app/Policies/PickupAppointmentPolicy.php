<?php

namespace App\Policies;

use App\Models\PickupAppointment;
use App\Models\User;

class PickupAppointmentPolicy
{
    /**
     * Determina si el usuario puede ver la cita o su factura adjunta.
     * Tanto admin como empleados pueden ver citas.
     */
    public function view(User $user, PickupAppointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'empleado']);
    }

    /**
     * Determina si el usuario puede crear citas.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'empleado']);
    }

    /**
     * Determina si el usuario puede actualizar la cita.
     */
    public function update(User $user, PickupAppointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'empleado']);
    }

    /**
     * Determina si el usuario puede aplicar Soft Delete a la cita.
     * Tanto admin como empleados pueden eliminar de forma lógica (Soft Delete).
     */
    public function delete(User $user, PickupAppointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'empleado']);
    }

    /**
     * Determina si el usuario puede exportar el PDF de UNA cita individual.
     */
    public function exportPdf(User $user, PickupAppointment $appointment): bool
    {
        return in_array($user->role, ['admin', 'empleado']);
    }

    /**
     * Determina si el usuario puede exportar reportes o PDFs de HISTORIALES GENERALES.
     * Restringido EXCLUSIVAMENTE a Administradores.
     */
    public function exportGeneralHistoryPdf(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Restaurar o eliminar permanentemente de la BD.
     * Restringido EXCLUSIVAMENTE a Administradores.
     */
    public function forceDelete(User $user, PickupAppointment $appointment): bool
    {
        return $user->role === 'admin';
    }
}