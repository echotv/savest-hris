<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklySchedule extends Model
{
    protected $table = 'weekly_schedules';

    protected $primaryKey = 'schedule_id';

    public $timestamps = false; // since your table has no created_at/updated_at

    protected $fillable = [
        'employee_id',
        'ews_id',
        'effective_date',
        'end_date',
        'day_of_week',
        'start_time',
        'end_time',
        'break_start',
        'break_end',
        'is_day_off'
    ];

    protected $casts = [
        'start_time'   => 'datetime:H:i',
        'end_time'     => 'datetime:H:i',
        'break_start'  => 'datetime:H:i',
        'break_end'    => 'datetime:H:i',
        'effective_date'   => 'date',
        'end_date'     => 'date',
        'is_day_off'   => 'boolean',
    ];

    // ============================
    // ✅ RELATIONSHIP
    // ============================
    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }
}

