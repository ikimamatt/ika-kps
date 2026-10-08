<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Alumnus extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'alumni';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'title',
        'level',
        'class_year',
        'full_year',
        'profession',
        'institution',
        'domicile',
        'summary',
        'email',
        'phone',
        'location',
        'linkedin_url',
        'instagram_handle',
        'avatar_url',
        'is_verified',
        'verified_at',
        'verified_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_verified' => 'boolean',
            'verified_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Alumnus $alumnus) {
            if (empty($alumnus->slug)) {
                $baseSlug = Str::slug($alumnus->name).'-'.($alumnus->full_year ?? rand(1000, 9999));
                $alumnus->slug = $baseSlug;
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function businesses(): HasMany
    {
        return $this->hasMany(Business::class);
    }

    public function jobVacancies(): HasMany
    {
        return $this->hasMany(JobVacancy::class);
    }

    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
