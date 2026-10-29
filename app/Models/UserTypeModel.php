<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTypeModel extends Model
{
    use HasFactory;

    protected $table = 'user_type'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'ut_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'user_type', 'color_code', 'is_deleted'
    ];

    public function privileges()
{
    return $this->hasMany(UserPrivilegeModel::class, 'ut_id', 'ut_id');
}

}
