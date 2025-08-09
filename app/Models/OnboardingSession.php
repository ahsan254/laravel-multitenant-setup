<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OnboardingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'full_name',
        'email',
        'password_hash',
        'company_name',
        'subdomain',
        'company_metadata',
        'billing_info',
        'current_step',
        'is_completed',
        'expires_at',
    ];

    protected $casts = [
        'company_metadata' => 'array',
        'billing_info' => 'array',
        'expires_at' => 'datetime',
        'is_completed' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($session) {
            if (empty($session->token)) {
                $session->token = Str::random(64);
            }
            if (empty($session->expires_at)) {
                $session->expires_at = now()->addHours(24);
            }
        });
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function canProceedToStep(string $step): bool
    {
        $steps = ['account', 'password', 'company', 'billing', 'confirmation'];
        $currentIndex = array_search($this->current_step, $steps);
        $targetIndex = array_search($step, $steps);

        return $targetIndex <= $currentIndex + 1;
    }

    public function updateStep(string $step): void
    {
        $this->update(['current_step' => $step]);
    }

    public function complete(): void
    {
        $this->update([
            'is_completed' => true,
            'current_step' => 'confirmation'
        ]);
    }
}



