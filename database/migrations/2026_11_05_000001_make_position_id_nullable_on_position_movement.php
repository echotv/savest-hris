<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Position is no longer collected on Employee Registration, so a
     * brand-new employee's auto-created first Position Movement record may
     * not have one yet — HR assigns it afterward via Position Movement.
     */
    public function up(): void
    {
        if (Schema::hasTable('employee_position_movement') && Schema::hasColumn('employee_position_movement', 'position_id')) {
            Schema::table('employee_position_movement', function (Blueprint $table) {
                $table->integer('position_id')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('employee_position_movement') && Schema::hasColumn('employee_position_movement', 'position_id')) {
            Schema::table('employee_position_movement', function (Blueprint $table) {
                $table->integer('position_id')->nullable(false)->change();
            });
        }
    }
};
