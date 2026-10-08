<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'full_name',
        'level',
        'graduation_year',
        'phone_whatsapp',
        'email',
        'profession',
        'institution',
        'domicile',
        'notes',
        'status',
    ];
}
