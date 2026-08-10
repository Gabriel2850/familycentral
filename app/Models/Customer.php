<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    // 🛡️ Campos autorizados para inserción masiva
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'is_recurrent',
        'first_shipped_at',
    ];

    // 🗓️ Casteo automático de tipos de datos
    protected $casts = [
        'is_recurrent' => 'boolean',
        'first_shipped_at' => 'datetime',
    ];

    // 🔗 Relación: Un cliente puede tener múltiples citas de recolección
    public function appointments()
    {
        return $this->hasMany(PickupAppointment::class);
    }
}