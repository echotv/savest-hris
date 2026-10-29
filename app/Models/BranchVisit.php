<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchVisit extends Model
{
    protected $table      = 'employee_branch_visits';
    protected $primaryKey = 'id';

    protected $fillable = [
        'purpose', 'start_date', 'end_date',
        'notes', 'created_by', 'updated_by', 'is_deleted',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employeeLinks()
    {
        return $this->hasMany(BranchVisitEmployee::class, 'branch_visit_id');
    }

    public function branchLinks()
    {
        return $this->hasMany(BranchVisitBranch::class, 'branch_visit_id');
    }

    public function days()
    {
        return $this->hasMany(BranchVisitDay::class, 'branch_visit_id')->orderBy('visit_date');
    }
}
