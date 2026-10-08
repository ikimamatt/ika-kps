<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Business extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'alumnus_id',
        'name',
        'slug',
        'category',
        'owner_info',
        'description',
        'action_type',
        'action_label',
        'action_link',
        'image_url',
        'status',
        'address',
        'city',
        'phone',
        'whatsapp_number',
        'website_url',
    ];

    protected static function booted(): void
    {
        static::creating(function (Business $business) {
            if (empty($business->slug)) {
                $business->slug = Str::slug($business->name).'-'.rand(100, 999);
            }
        });
    }

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function getActionUrlAttribute(): ?string
    {
        return $this->action_link;
    }

    public function setActionUrlAttribute(?string $value): void
    {
        $this->attributes['action_link'] = $value;
    }
}
