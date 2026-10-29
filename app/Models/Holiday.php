<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Holiday extends Model
{
    protected $table      = 'holidays';
    protected $primaryKey = 'holiday_id';

    protected $fillable = [
        'holiday_name',
        'holiday_date',
        'holiday_type',
        'is_whole_day',
        'time_start',
        'time_end',
        'description',
        'is_recurring',
        'is_deleted',
    ];

    protected $casts = [
        'holiday_date'  => 'date',
        'is_whole_day'  => 'boolean',
    ];

    public function branches(): BelongsToMany
    {
        return $this->belongsToMany(
            BranchModel::class,
            'holiday_branches',
            'holiday_id',
            'branch_code',
            'holiday_id',
            'branch_code'
        );
    }
}
