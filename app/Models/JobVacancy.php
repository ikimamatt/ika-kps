<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class JobVacancy extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'alumnus_id',
        'title',
        'slug',
        'company',
        'company_alumni_info',
        'alumni_info',
        'job_type',
        'type_badge_class',
        'location',
        'salary_range',
        'posted_time_info',
        'description',
        'requirements',
        'deadline',
        'cta_label',
        'cta_link',
        'apply_url',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    protected static function booted(): void
    {
        static::creating(function (JobVacancy $job) {
            if (empty($job->slug)) {
                $job->slug = Str::slug($job->title.'-'.$job->company).'-'.rand(100, 999);
            }
        });
    }

    public function alumnus(): BelongsTo
    {
        return $this->belongsTo(Alumnus::class);
    }

    /**
     * Scope a query to only include active and non-expired job vacancies.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
            });
    }

    /**
     * Get the badge style class based on job type.
     */
    public function getBadgeClass(): string
    {
        return match ($this->job_type) {
            'Full Time' => 'bg-emerald-100 text-emerald-800',
            'Part Time' => 'bg-sky-100 text-sky-800',
            'Magang', 'Magang / Internship' => 'bg-purple-100 text-purple-800',
            'Kontrak' => 'bg-amber-100 text-amber-800',
            default => 'bg-surface-container text-on-surface-variant',
        };
    }

    /**
     * Determine if the application channel is via email.
     */
    public function getIsEmailApplicationAttribute(): bool
    {
        $raw = trim($this->apply_url ?? '');

        return Str::startsWith($raw, 'mailto:') || (! Str::startsWith($raw, ['http://', 'https://']) && str_contains($raw, '@'));
    }

    /**
     * Extract the raw email address if the application channel is email.
     */
    public function getApplicationEmailAttribute(): ?string
    {
        if (! $this->is_email_application) {
            return null;
        }

        $raw = trim($this->apply_url ?? '');
        $emailOnly = Str::after($raw, 'mailto:');

        return Str::before($emailOnly, '?');
    }

    /**
     * Format the apply URL safely with mailto: or https:// protocols.
     */
    public function getFormattedApplyUrlAttribute(): string
    {
        $raw = trim($this->apply_url ?? '');

        if ($raw === '') {
            return '#';
        }

        if ($this->is_email_application) {
            $email = $this->application_email;
            $subject = rawurlencode("Lamaran: {$this->title} - {$this->company} (via IKA KPS)");

            return "mailto:{$email}?subject={$subject}";
        }

        if (! Str::startsWith($raw, ['http://', 'https://'])) {
            return 'https://'.$raw;
        }

        return $raw;
    }

    /**
     * Get dynamic action button label based on application channel.
     */
    public function getApplicationActionLabelAttribute(): string
    {
        if ($this->is_email_application) {
            return 'Kirim Email Lamaran';
        }

        return $this->cta_label ?: 'Lamar via Website';
    }
}
