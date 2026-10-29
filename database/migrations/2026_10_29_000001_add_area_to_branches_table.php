<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // branches isn't a migration-created table in this codebase (bootstrapped outside
        // Laravel's migrations, same as employee/positions/employment_status) — guard with
        // hasColumn so this stays safe to run on any environment.
        if (Schema::hasTable('branches') && !Schema::hasColumn('branches', 'area')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->integer('area')->nullable()->after('branch_name');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('branches') && Schema::hasColumn('branches', 'area')) {
            Schema::table('branches', function (Blueprint $table) {
                $table->dropColumn('area');
            });
        }
    }
};
