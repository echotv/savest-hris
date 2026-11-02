<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Branch/Workplace is now tracked per Position Movement record, the same
     * way Position itself already is — saving a movement updates the
     * employee's current branch/workplace, and Employee Edit no longer
     * carries an editable Branch/Workplace field.
     *
     * Existing movement rows have no real historical branch on file, so they
     * are backfilled from each employee's current brwp_id — the closest
     * approximation available.
     */
    public function up(): void
    {
        if (!Schema::hasTable('employee_position_movement') || Schema::hasColumn('employee_position_movement', 'brwp_id')) {
            return;
        }

        Schema::table('employee_position_movement', function (Blueprint $table) {
            $table->unsignedBigInteger('brwp_id')->nullable()->after('position_id');
        });

        DB::statement('
            UPDATE employee_position_movement epm
            JOIN employee e ON e.employee_id = epm.employee_id
            SET epm.brwp_id = e.brwp_id
            WHERE e.brwp_id IS NOT NULL
        ');

        Schema::table('employee_position_movement', function (Blueprint $table) {
            $table->unsignedBigInteger('brwp_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        if (Schema::hasTable('employee_position_movement') && Schema::hasColumn('employee_position_movement', 'brwp_id')) {
            Schema::table('employee_position_movement', function (Blueprint $table) {
                $table->dropColumn('brwp_id');
            });
        }
    }
};
