<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BiometricId extends Model
{
    protected $table = 'biometric_ids';

    protected $fillable = [
        'employee_id',
        'brwp_id',
        'biometric_id',
        'is_deleted',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'employee_id');
    }

    public function branch()
    {
        return $this->belongsTo(BranchWorkplaceModel::class, 'brwp_id', 'id');
    }
}
