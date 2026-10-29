<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchVisitDay extends Model
{
    public $timestamps = false;
    protected $table   = 'employee_branch_visit_days';
    protected $fillable = ['branch_visit_id', 'visit_date', 'time_start', 'time_end'];
}
