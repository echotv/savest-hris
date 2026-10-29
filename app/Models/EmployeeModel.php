<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeModel extends Model
{
    use HasFactory;

    protected $table = 'employee'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'employee_id';
    public $incrementing = true; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'employee_number', 'lastname', 'firstname', 'middlename', 'suffix', 'gender', 'date_of_birth', 'place_of_birth', 'civil_status', 'personal_email',
        'street', 'barangay', 'city', 'province', 'religion', 'date_employed', 'college_code', 'department', 'branch_code', 'position_id', 'account_status', 'pagibig', 'philhealth', 'tin', 'sss', 'biometric_id',
        'national_id', 'spouse_name', 'spouse_occupation', 'spouse_empl_name', 'father_name', 'mother_name', 'childrens', 'eci_name', 'eci_cnumber', 'date_inputted', 'inputted_byname', 'inputted_byid',
        'updated_at', 'es_id', 'updated_byname', 'updated_by_id', 'profile_picture', 'is_deleted', 'is_archived', 'citizenship', 'height', 'weight', 'drivers_license_no', 'telephone_no', 'cellphone_no', 'eci_address'
    ];

    /**
     * True when the given employment status counts as a current employee.
     * Unknown/missing status is treated as current so we never lock someone out by accident.
     */
    public static function isCurrentEmployeeStatus($es_id): bool
    {
        $es = EmploymentStatusModel::find($es_id);
        return !$es || (int) $es->is_current_employee === 1;
    }

    /**
     * Keep the account in line with the employment status:
     *  - non-current status (e.g. Resigned)          -> Inactive
     *  - moved from a non-current to a current status -> Active again
     * A current -> current change leaves the account as-is, so a manual
     * deactivation isn't undone. Deleted employees are never reactivated.
     */
    public static function syncAccountWithEmploymentStatus($employee_id, $previous_es_id = null): void
    {
        $employee = self::find($employee_id);
        if (!$employee) {
            return;
        }

        if (!self::isCurrentEmployeeStatus($employee->es_id)) {
            $active = 0;
        } elseif ($previous_es_id !== null && !self::isCurrentEmployeeStatus($previous_es_id) && !$employee->is_deleted) {
            $active = 1;
        } else {
            return;
        }

        self::where('employee_id', $employee_id)->update(['account_status' => $active]);
        User::where('employee_id', $employee_id)->update(['is_active' => $active]);
    }

}
