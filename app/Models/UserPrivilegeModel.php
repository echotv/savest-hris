<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPrivilegeModel extends Model
{
    use HasFactory;

    protected $table = 'user_privileges';

    public $timestamps = false;

    protected $primaryKey = 'up_id';

    protected $fillable = [
        'ut_id',
        'privilege'
    ];
}
