<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkExperiencesModel extends Model
{
    use HasFactory;

    protected $table = 'work_experiences'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'we_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'employee_id', 'we_company_name', 'we_position', 'we_salary', 'we_date_started', 'we_date_ended', 'is_deleted'
    ];

}
