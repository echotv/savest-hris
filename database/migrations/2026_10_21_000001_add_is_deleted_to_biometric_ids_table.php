<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('biometric_ids', 'is_deleted')) {
            Schema::table('biometric_ids', function (Blueprint $table) {
                $table->tinyInteger('is_deleted')->default(0)->after('biometric_id');
            });
        }

        // A hard UNIQUE index can't see is_deleted, so a soft-deleted row would still
        // collide with a fresh insert for the same employee/branch (or branch/biometric_id).
        // Drop the uniques and keep plain indexes — the controller already enforces
        // uniqueness among active (is_deleted = 0) rows, same as positions/employment_status/
        // salary_grades do at the validation layer rather than the DB layer.
        Schema::table('biometric_ids', function (Blueprint $table) {
            $table->dropUnique('biometric_ids_employee_id_branch_code_unique');
            $table->dropUnique('biometric_ids_branch_code_biometric_id_unique');
            $table->index(['employee_id', 'branch_code'], 'biometric_ids_employee_branch_idx');
            $table->index(['branch_code', 'biometric_id'], 'biometric_ids_branch_biometric_idx');
        });
    }

    public function down(): void
    {
        Schema::table('biometric_ids', function (Blueprint $table) {
            $table->dropIndex('biometric_ids_employee_branch_idx');
            $table->dropIndex('biometric_ids_branch_biometric_idx');
            $table->unique(['employee_id', 'branch_code'], 'biometric_ids_employee_id_branch_code_unique');
            $table->unique(['branch_code', 'biometric_id'], 'biometric_ids_branch_code_biometric_id_unique');
            $table->dropColumn('is_deleted');
        });
    }
};
