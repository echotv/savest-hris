<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequest extends Model
{
    protected $table      = 'employee_travel_requests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'request_type', 'start_date', 'end_date',
        'purpose', 'notes', 'created_by', 'updated_by', 'is_deleted',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employeeLinks()
    {
        return $this->hasMany(TravelRequestEmployee::class, 'travel_request_id');
    }

    public function days()
    {
        return $this->hasMany(TravelRequestDay::class, 'travel_request_id')->orderBy('request_date');
    }
}
