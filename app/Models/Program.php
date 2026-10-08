<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Program extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'icon',
        'icon_bg_class',
        'progress_label',
        'progress_status',
        'progress_percent',
        'bar_color_class',
        'achievement_text',
        'target_amount',
        'collected_amount',
        'status',
        'start_date',
        'end_date',
        'image_url',
    ];

    protected $casts = [
        'progress_percent' => 'integer',
        'target_amount' => 'decimal:2',
        'collected_amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (Program $program) {
            if (empty($program->slug)) {
                $program->slug = Str::slug($program->title).'-'.rand(100, 999);
            }
        });
    }
}
