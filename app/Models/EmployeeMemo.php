<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeMemo extends Model
{
    protected $table      = 'employee_memos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'subject', 'date_issued', 'created_by', 'updated_by', 'is_deleted',
    ];

    protected $casts = [
        'date_issued' => 'date',
    ];

    public function employeeLinks()
    {
        return $this->hasMany(EmployeeMemoEmployee::class, 'memo_id');
    }

    public function files()
    {
        return $this->hasMany(EmployeeMemoFile::class, 'memo_id')->orderBy('id');
    }
}
