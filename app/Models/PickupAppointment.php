<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Zone;

class PickupAppointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'zone_id',
        'user_id',
        'scheduled_date',
        'box_quantity',
        'box_dimensions',
        'tracking_number',
        'status',
        'notes',
        'invoice_path',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    // 🔗 Cita pertenece a un Cliente
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // 🔗 Cita pertenece a una Zona de EUA
    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }

    // 🔗 Cita fue creada por un Usuario (Empleado/Admin)
    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}