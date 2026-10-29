<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryGradeMovement extends Model
{
    protected $table = 'salary_grade_movement';
    protected $primaryKey = 'sgm_id';

    public $timestamps = false; // since we removed created_at & updated_at

    protected $fillable = [
        'employee_id',
        'salary_grade_id',
        'grade_level',
        'effective_date',
        'end_date',
        'step',
        'basic_salary',
        'hourly_rate',
        'daily_rate',
        'remarks',
        'is_deleted'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date' => 'date',
        'basic_salary' => 'decimal:2',
        'daily_rate' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    public function salaryGrade()
    {
        return $this->belongsTo(SalaryGrade::class, 'salary_grade_id', 'salary_grade_id');
    }
}
