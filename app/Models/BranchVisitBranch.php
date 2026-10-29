<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchVisitBranch extends Model
{
    public $timestamps = false;
    protected $table   = 'employee_branch_visit_branches';
    protected $fillable = ['branch_visit_id', 'branch_code'];

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_code', 'branch_code');
    }
}
