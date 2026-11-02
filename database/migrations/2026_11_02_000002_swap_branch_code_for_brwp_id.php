<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    // Every table that referenced branches by the string branch_code column,
    // and whether that column was nullable on the old table (same nullability
    // is kept on the new brwp_id column).
    protected array $tables = [
        'assigned_branches'              => false,
        'employee'                       => true,
        'biometric_ids'                  => false,
        'employee_branch_visit_branches' => false,
        'holiday_branches'                => false,
    ];

    public function up(): void
    {
        if (!Schema::hasTable('branches_workplaces')) {
            return;
        }

        foreach ($this->tables as $table => $nullable) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'branch_code')) {
                continue;
            }

            if (!Schema::hasColumn($table, 'brwp_id')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('brwp_id')->nullable()->after('branch_code');
                });
            }

            // Backfill brwp_id from the matching branches_workplaces.brwp_code row.
            // COLLATE pins the comparison: brwp_code is a fresh Laravel string column
            // (utf8mb4_unicode_ci) while the legacy *.branch_code columns this app has
            // always used are utf8mb4_general_ci — the same mismatch this codebase has
            // hit (and fixed the same way) for every other branch_code join.
            DB::statement("
                UPDATE `{$table}` t
                JOIN `branches_workplaces` bw
                    ON bw.`brwp_code` COLLATE utf8mb4_general_ci = t.`branch_code`
                SET t.`brwp_id` = bw.`id`
                WHERE t.`branch_code` IS NOT NULL
            ");

            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('branch_code');
            });

            if (!$nullable) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unsignedBigInteger('brwp_id')->nullable(false)->change();
                });
            }
        }
    }

    public function down(): void
    {
        foreach (array_reverse($this->tables) as $table => $nullable) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'brwp_id')) {
                continue;
            }

            if (!Schema::hasColumn($table, 'branch_code')) {
                Schema::table($table, function (Blueprint $t) use ($nullable) {
                    $t->string('branch_code', 20)->nullable($nullable)->after('brwp_id');
                });
            }

            DB::statement("
                UPDATE `{$table}` t
                JOIN `branches_workplaces` bw ON bw.`id` = t.`brwp_id`
                SET t.`branch_code` = bw.`brwp_code`
                WHERE t.`brwp_id` IS NOT NULL
            ");

            Schema::table($table, function (Blueprint $t) {
                $t->dropColumn('brwp_id');
            });
        }
    }
};
