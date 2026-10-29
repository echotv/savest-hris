<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegesModel extends Model
{
    use HasFactory;

    protected $table = 'colleges'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'c_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'name_of_school', 'course', 'year_graduated', 'employee_id', 'is_deleted'
    ];

}
