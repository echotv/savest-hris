<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestEmployee extends Model
{
    protected $table    = 'employee_travel_request_employees';
    protected $fillable = ['travel_request_id', 'employee_id'];

    public function request()
    {
        return $this->belongsTo(TravelRequest::class, 'travel_request_id');
    }

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }
}
