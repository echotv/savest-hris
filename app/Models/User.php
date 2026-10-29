<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'type',
        'profile_picture',
        'ut_id',
        'is_active',
        'password',
        'employee_id',
        'created_at',
        'updated_at',
        'otp',
        'otp_sent_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // User Model
public function userType()
{
    return $this->belongsTo(UserTypeModel::class, 'ut_id');
}

/**
 * Exclude accounts linked to a deleted employee profile.
 * Accounts with no employee link (e.g. admins) are kept.
 */
public function scopeWithoutDeletedEmployees($query)
{
    return $query->whereNotExists(function ($q) {
        $q->selectRaw(1)
          ->from('employee')
          ->whereColumn('employee.employee_id', 'users.employee_id')
          ->where('employee.is_deleted', 1);
    });
}

/**
 * True when this account belongs to a deleted employee profile.
 */
public function hasDeletedEmployee(): bool
{
    return $this->employee_id
        && EmployeeModel::where('employee_id', $this->employee_id)->where('is_deleted', 1)->exists();
}
}
