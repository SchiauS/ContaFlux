<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'worked_on',
        'hours',
        'note',
    ];

    protected $casts = [
        'worked_on' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
