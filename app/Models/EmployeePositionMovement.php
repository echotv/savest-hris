<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeePositionMovement extends Model
{
    protected $table = 'employee_position_movement';
    protected $primaryKey = 'ep_id';

    public $timestamps = false; // since we removed created_at & updated_at

    protected $fillable = [
        'employee_id',
        'position_id',
        'effective_date',
        'end_date',
        'remarks',
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

    public function Positions()
    {
        return $this->belongsTo(PositionsModel::class, 'position_id', 'position_id');
    }
}
