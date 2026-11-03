<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('salary_grades') && Schema::hasColumn('salary_grades', 'step')) {
            Schema::table('salary_grades', function (Blueprint $table) {
                $table->string('step', 20)->nullable(false)->change();
            });
        }

        // Denormalized copy of salary_grades.step kept on each movement record.
        if (Schema::hasTable('salary_grade_movement') && Schema::hasColumn('salary_grade_movement', 'step')) {
            Schema::table('salary_grade_movement', function (Blueprint $table) {
                $table->string('step', 20)->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('salary_grades') && Schema::hasColumn('salary_grades', 'step')) {
            Schema::table('salary_grades', function (Blueprint $table) {
                $table->integer('step')->nullable(false)->change();
            });
        }

        if (Schema::hasTable('salary_grade_movement') && Schema::hasColumn('salary_grade_movement', 'step')) {
            Schema::table('salary_grade_movement', function (Blueprint $table) {
                $table->integer('step')->nullable()->change();
            });
        }
    }
};
