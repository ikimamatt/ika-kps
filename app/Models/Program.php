<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'icon',
        'icon_bg_class',
        'progress_label',
        'progress_status',
        'progress_percent',
        'bar_color_class',
        'achievement_text',
    ];
}
