<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionsModel extends Model
{
    use HasFactory;

    protected $table = 'positions'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'position_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'position_name', 'is_archived', 'is_deleted'
    ];

}
