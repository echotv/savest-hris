<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GraduateStudiesModel extends Model
{
    use HasFactory;

    protected $table = 'graduate_studies'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'gs_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'employee_id', 'gs_name_of_school', 'gs_sch_address', 'gs_course', 'gs_year_graduated', 'is_deleted'
    ];

}
