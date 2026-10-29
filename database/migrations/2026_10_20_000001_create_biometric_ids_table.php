<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('biometric_ids')) return;

        // employee isn't a migration-backed table in this codebase (it's bootstrapped
        // outside of Laravel's migrations, same as branches/positions/employment_status/
        // salary_grades) — so on an environment where it hasn't been created/restored yet,
        // adding a hard FK to it here would fail the whole migration. Attach the FK only
        // when employee already exists; otherwise fall back to the same "enforced via
        // validation, not a DB constraint" pattern already used for branch_code below.
        $employeeTableExists = Schema::hasTable('employee');

        Schema::create('biometric_ids', function (Blueprint $table) use ($employeeTableExists) {
            $table->id();
            // Plain (signed) int, not unsignedInteger: employee.employee_id is a signed
            // `int` column, and MySQL requires an exact type match (incl. signedness) for
            // a real FK constraint to be accepted.
            $table->integer('employee_id')->index();
            // No FK on branch_code: branches.branch_code is utf8mb4_general_ci while a
            // fresh Laravel string column defaults to utf8mb4_unicode_ci, which breaks a
            // real FK constraint (see the fix_holiday_branches_collation / fix_branch_visits_collation
            // migrations already in this codebase). Every other master-data reference in this
            // app (positions, employment_status, salary_grades) is enforced the same way —
            // via the controller's `exists:branches,branch_code` validation rule, not a DB FK.
            $table->string('branch_code', 20)->index();
            $table->string('biometric_id', 100);
            $table->timestamps();

            // One biometric ID per employee per branch...
            $table->unique(['employee_id', 'branch_code']);
            // ...and no two employees can share the same device ID within the same branch.
            $table->unique(['branch_code', 'biometric_id']);

            if ($employeeTableExists) {
                $table->foreign('employee_id')
                      ->references('employee_id')->on('employee')
                      ->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('biometric_ids');
    }
};
