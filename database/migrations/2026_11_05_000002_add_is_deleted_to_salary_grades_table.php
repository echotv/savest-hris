<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * salary_grades is referenced by salary_grade_movement with ON DELETE
     * RESTRICT, so a grade already used in any movement history can never be
     * hard-deleted. Switch to a soft delete like every other table here.
     */
    public function up(): void
    {
        if (Schema::hasTable('salary_grades') && !Schema::hasColumn('salary_grades', 'is_deleted')) {
            Schema::table('salary_grades', function (Blueprint $table) {
                $table->boolean('is_deleted')->default(false)->after('is_active');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('salary_grades') && Schema::hasColumn('salary_grades', 'is_deleted')) {
            Schema::table('salary_grades', function (Blueprint $table) {
                $table->dropColumn('is_deleted');
            });
        }
    }
};
