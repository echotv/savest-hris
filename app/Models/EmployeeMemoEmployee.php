<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMemoEmployee extends Model
{
    protected $table    = 'employee_memo_employees';
    protected $fillable = ['memo_id', 'employee_id'];

    public function memo()
    {
        return $this->belongsTo(EmployeeMemo::class, 'memo_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }
}
