<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluation extends Model
{
    protected $table      = 'performance_evaluations';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id', 'evaluation_period', 'evaluation_type', 'evaluation_date',
        'overall_score', 'overall_rating', 'remarks',
        'created_by', 'updated_by', 'is_deleted',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'overall_score'   => 'decimal:2',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    public function files()
    {
        return $this->hasMany(PerformanceEvaluationFile::class, 'evaluation_id')->orderBy('id');
    }
}
