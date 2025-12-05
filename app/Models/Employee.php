<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'role',
        'salary',
        'currency',
        'status',
        'hired_at',
        'terminated_at',
    ];

    protected $casts = [
        'hired_at' => 'date',
        'terminated_at' => 'date',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function timeEntries()
    {
        return $this->hasMany(EmployeeTimeEntry::class);
    }

    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class);
    }

    public function payments()
    {
        return $this->hasMany(EmployeePayment::class);
    }
}
