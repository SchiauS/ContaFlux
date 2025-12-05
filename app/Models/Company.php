<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'fiscal_code',
        'currency',
        'fiscal_year_start',
        'timezone',
        'settings',
    ];

    protected $casts = [
        'fiscal_year_start' => 'date',
        'settings' => 'array',
    ];

    public function accounts(): HasMany
    {
        return $this->hasMany(FinancialAccount::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(FinancialTransaction::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function aiSessions(): HasMany
    {
        return $this->hasMany(AiSession::class);
    }
}
