<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSuspension extends Model
{
    protected $table      = 'employee_suspensions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'employee_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'created_by',
        'updated_by',
        'is_deleted',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }
}
