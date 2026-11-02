<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('branches_workplaces') && Schema::hasColumn('branches_workplaces', 'area')) {
            Schema::table('branches_workplaces', function (Blueprint $table) {
                $table->dropColumn('area');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('branches_workplaces') && !Schema::hasColumn('branches_workplaces', 'area')) {
            Schema::table('branches_workplaces', function (Blueprint $table) {
                $table->integer('area')->nullable();
            });
        }
    }
};
