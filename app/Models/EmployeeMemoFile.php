<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMemoFile extends Model
{
    protected $table    = 'employee_memo_files';
    protected $fillable = ['memo_id', 'url'];

    public function memo()
    {
        return $this->belongsTo(EmployeeMemo::class, 'memo_id');
    }
}
