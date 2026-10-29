<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_suspensions')) return;
        if (!Schema::hasColumn('employee_suspensions', 'status')) return;

        Schema::table('employee_suspensions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('employee_suspensions')) return;
        if (Schema::hasColumn('employee_suspensions', 'status')) return;

        Schema::table('employee_suspensions', function (Blueprint $table) {
            $table->enum('status', ['active', 'lifted', 'served'])->default('active')->after('reason');
        });
    }
};
