<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchWorkplaceModel extends Model
{
    protected $table = 'branches_workplaces';

    protected $fillable = [
        'brwp_code',
        'name',
        'location',
        'is_branch',
        'is_visible',
        'is_deleted',
    ];

    protected $casts = [
        'is_branch'  => 'boolean',
        'is_visible' => 'boolean',
        'is_deleted' => 'boolean',
    ];
}
