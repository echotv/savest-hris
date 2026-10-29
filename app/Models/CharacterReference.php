<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharacterReference extends Model
{
    use HasFactory;

    protected $table = 'character_references';

      public $timestamps = false; // 👈 disable this


    protected $fillable = [
        'cr_name',
        'cr_company',
        'cr_position',
        'cr_cnum',
        'employee_id',
        'is_deleted',
    ];

    /**
     * Relationship: Character Reference belongs to Employee
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
