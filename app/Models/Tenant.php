<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Tenant as SpatieTenant;

class Tenant extends SpatieTenant
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subdomain',
        'database_name',
        'domain',
        'data',
        'status',
        'provisioned_at',
    ];

    protected $casts = [
        'data' => 'array',
        'provisioned_at' => 'datetime',
    ];

    public function getDatabaseName(): string
    {
        return $this->database_name;
    }

    public function getDomain(): string
    {
        return $this->domain;
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isProvisioning(): bool
    {
        return $this->status === 'provisioning';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}



