<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'state',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * 🔗 Una Zona tiene muchas Citas de Recolección
     */
    public function pickupAppointments()
    {
        return $this->hasMany(PickupAppointment::class, 'zone_id');
    }
}