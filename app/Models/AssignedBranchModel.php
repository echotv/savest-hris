<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignedBranchModel extends Model
{
    use HasFactory;

    protected $table = 'assigned_branches';

    protected $primaryKey = 'ab_id';

    public $incrementing = true;

    protected $keyType = 'int';

    public $timestamps = false;

    protected $fillable = [
        'ut_id',
        'branch_code'
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function userType()
    {
        return $this->belongsTo(UserTypeModel::class, 'ut_id', 'ut_id');
    }

    public function branch()
    {
        return $this->belongsTo(BranchModel::class, 'branch_code', 'branch_code');
    }
}
