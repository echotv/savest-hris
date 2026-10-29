<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchVisitEmployee extends Model
{
    public $timestamps = false;
    protected $table   = 'employee_branch_visit_employees';
    protected $fillable = ['branch_visit_id', 'employee_id'];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }
}
