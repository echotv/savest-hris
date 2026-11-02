<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Dated history of which Area a branch belongs to — same "current record"
     * convention as salary_grade_movement/employee_position_movement: the
     * latest non-deleted row per branch, with end_date IS NULL meaning the
     * assignment is still active ("Present"). Only branches (is_branch = 1
     * on branches_workplaces) are ever assigned an area here — plain
     * workplaces are excluded by the application layer, not this table.
     */
    public function up(): void
    {
        if (Schema::hasTable('branch_area_movements')) {
            return;
        }

        Schema::create('branch_area_movements', function (Blueprint $table) {
            $table->id('bam_id');
            $table->unsignedBigInteger('brwp_id');
            $table->unsignedInteger('area');
            $table->date('effective_date');
            $table->date('end_date')->nullable();
            $table->text('remarks')->nullable();
            $table->boolean('is_deleted')->default(false);

            $table->foreign('brwp_id')
                  ->references('id')->on('branches_workplaces')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('branch_area_movements');
    }
};
