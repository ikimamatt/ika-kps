<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'body',
        'location',
        'start_date',
        'end_date',
        'category',
        'image_url',
        'status',
        'max_participants',
        'fee',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'fee' => 'decimal:2',
        'max_participants' => 'integer',
    ];

    protected static function booted(): void
    {
        static::creating(function (Event $event) {
            if (empty($event->slug)) {
                $event->slug = Str::slug($event->title).'-'.rand(100, 999);
            }
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function isFullyBooked(): bool
    {
        if (is_null($this->max_participants)) {
            return false;
        }

        return $this->registrations()->where('status', 'confirmed')->count() >= $this->max_participants;
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>=', now()->startOfDay());
    }

    public function scopePast($query)
    {
        return $query->where('start_date', '<', now()->startOfDay());
    }
}
