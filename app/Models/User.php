<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Los atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'document_number',
        'role_id',
    ];

    /**
     * Los atributos que deben ocultarse para arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Los atributos que deben ser convertidos a tipos nativos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relación con el modelo Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Relación con las reservas del usuario.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
