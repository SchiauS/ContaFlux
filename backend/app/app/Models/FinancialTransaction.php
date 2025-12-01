<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'financial_account_id',
        'counterparty',
        'description',
        'direction',
        'amount',
        'currency',
        'tax_rate',
        'occurred_at',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'occurred_at' => 'datetime',
        'amount' => 'decimal:2',
        'tax_rate' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class, 'financial_account_id');
    }
}
