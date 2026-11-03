<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryGrade extends Model
{
    protected $table = 'salary_grades';

    protected $primaryKey = 'salary_grade_id';

    protected $fillable = [
    'grade_level',
    'step',
    'basic_salary',
    'hourly_rate',
    'daily_rate',
    'is_active',
    'is_deleted'
];

protected $casts = [
    'is_active' => 'boolean',
    'is_deleted' => 'boolean',
    'basic_salary' => 'decimal:2',
    'hourly_rate' => 'decimal:2',
    'daily_rate' => 'decimal:2',
];
}
