<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentStatusModel extends Model
{
    protected $table = 'employment_status';
    protected $primaryKey = 'es_id';

    public $timestamps = false;

    protected $fillable = [
        'empl_status',
        'is_current_employee',
        'is_visible',
        'is_deleted',
    ];
}
