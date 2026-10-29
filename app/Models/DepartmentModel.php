<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentModel extends Model
{
    use HasFactory;

    protected $table = 'department'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'dept_code';
    public $incrementing = false; // true if it's auto-incrementing
    protected $keyType = 'string'; // or 'string' if your key is not numeric
    protected $fillable = [
        'dept_name', 'color_code'
    ];

}
