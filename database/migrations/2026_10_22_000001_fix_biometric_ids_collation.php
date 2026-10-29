<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // branches.branch_code is utf8mb4_general_ci; this column was created at the
        // Laravel-default utf8mb4_unicode_ci, which breaks any JOIN/FK comparing the two
        // ("Illegal mix of collations"). Same fix already applied to holiday_branches and
        // branch_visits — see 2026_06_26_000001_fix_holiday_branches_collation.php.
        DB::statement("ALTER TABLE biometric_ids MODIFY branch_code VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE biometric_ids MODIFY branch_code VARCHAR(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL");
    }
};
