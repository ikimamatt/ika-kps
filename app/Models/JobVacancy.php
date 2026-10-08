<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'company',
        'company_alumni_info',
        'job_type',
        'type_badge_class',
        'posted_time_info',
        'description',
        'cta_label',
        'cta_link',
    ];
}
