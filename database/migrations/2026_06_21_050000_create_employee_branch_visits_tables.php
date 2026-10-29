<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_branch_visits')) {
            Schema::create('employee_branch_visits', function (Blueprint $table) {
                $table->id();
                $table->string('purpose', 255);
                $table->date('start_date');
                $table->date('end_date');
                $table->text('notes')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_branch_visit_employees')) {
            Schema::create('employee_branch_visit_employees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_visit_id');
                $table->unsignedInteger('employee_id')->index();

                $table->foreign('branch_visit_id')
                      ->references('id')->on('employee_branch_visits')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('employee_branch_visit_branches')) {
            Schema::create('employee_branch_visit_branches', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_visit_id');
                $table->string('branch_code', 20)->index();

                $table->foreign('branch_visit_id')
                      ->references('id')->on('employee_branch_visits')
                      ->onDelete('cascade');
            });
        }

        if (!Schema::hasTable('employee_branch_visit_days')) {
            Schema::create('employee_branch_visit_days', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('branch_visit_id');
                $table->date('visit_date');
                $table->time('time_start');
                $table->time('time_end');

                $table->foreign('branch_visit_id')
                      ->references('id')->on('employee_branch_visits')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_branch_visit_days');
        Schema::dropIfExists('employee_branch_visit_branches');
        Schema::dropIfExists('employee_branch_visit_employees');
        Schema::dropIfExists('employee_branch_visits');
    }
};
