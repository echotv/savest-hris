<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BranchModel extends Model
{
    protected $table = 'branches';

    protected $primaryKey = 'branch_code';

    public $incrementing = false; // because primary key is string

    protected $keyType = 'string';

    protected $fillable = [
        'branch_code',
        'branch_name',
        'area',
        'address',
        'is_visible',
        'is_deleted'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
        'is_deleted' => 'boolean',
    ];

    public $timestamps = false; // remove if you add created_at & updated_at
}
