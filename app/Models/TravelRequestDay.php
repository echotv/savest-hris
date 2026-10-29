<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TravelRequestDay extends Model
{
    protected $table    = 'employee_travel_request_days';
    protected $fillable = ['travel_request_id', 'request_date', 'time_start', 'time_end'];

    protected $casts = [
        'request_date' => 'date',
    ];
}
