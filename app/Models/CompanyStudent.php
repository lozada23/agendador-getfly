<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyStudent extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'document_number',
        'email',
        'phone',
    ];

    protected $table = 'company_students'; // Especifica la tabla si no sigue la convención (opcional)

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'student_document', 'document_number');
    }
}