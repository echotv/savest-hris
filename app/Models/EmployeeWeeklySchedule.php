<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeWeeklySchedule extends Model
{
    protected $table = 'employee_weekly_schedules';

    protected $primaryKey = 'ews_id';

    public $timestamps = false; // since your table has no created_at/updated_at

    protected $fillable = [
        'employee_id',
        'effective_date',
        'end_date',
        'is_deleted',
    ];

    protected $casts = [
        'effective_date'   => 'date',
        'end_date'     => 'date',
        'is_deleted'   => 'boolean',
    ];

    // ============================
    // ✅ RELATIONSHIP
    // ============================
    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id');
    }
    public function schedules()
{
    return $this->hasMany(WeeklySchedule::class, 'ews_id', 'ews_id');
}
}
