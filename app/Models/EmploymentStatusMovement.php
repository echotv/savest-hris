<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentStatusMovement extends Model
{
    protected $table = 'employment_status_movement';
    protected $primaryKey = 'esm_id';

    public $timestamps = false; // since we removed created_at & updated_at

    protected $fillable = [
        'employee_id',
        'es_id',
        'effective_date',
        'end_date',
        'is_deleted'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date' => 'date'
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

    public function EmploymentStatus()
    {
        return $this->belongsTo(EmploymentStatusModel::class, 'es_id', 'es_id');
    }
}
