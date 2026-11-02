<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchAreaMovement extends Model
{
    protected $table = 'branch_area_movements';
    protected $primaryKey = 'bam_id';
    public $timestamps = false;

    protected $fillable = [
        'brwp_id',
        'area',
        'effective_date',
        'end_date',
        'remarks',
        'is_deleted',
    ];

    protected $casts = [
        'effective_date' => 'date',
        'end_date'       => 'date',
    ];

    public function branch()
    {
        return $this->belongsTo(BranchWorkplaceModel::class, 'brwp_id', 'id');
    }
}
