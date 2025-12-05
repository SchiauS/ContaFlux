<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'amount',
        'currency',
        'paid_at',
        'financial_transaction_id',
        'note',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function transaction()
    {
        return $this->belongsTo(FinancialTransaction::class, 'financial_transaction_id');
    }
}
