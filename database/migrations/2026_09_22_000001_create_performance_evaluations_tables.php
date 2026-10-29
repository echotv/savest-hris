<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('performance_evaluations')) {
            Schema::create('performance_evaluations', function (Blueprint $table) {
                $table->id();
                $table->unsignedInteger('employee_id')->index();
                $table->string('evaluation_period', 100);
                $table->string('evaluation_type', 100);
                $table->date('evaluation_date')->index();
                $table->decimal('overall_score', 5, 2)->nullable();
                $table->string('overall_rating', 100)->nullable();
                $table->text('remarks')->nullable();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('performance_evaluation_files')) {
            Schema::create('performance_evaluation_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('evaluation_id');
                $table->string('url', 500);
                $table->timestamps();

                $table->foreign('evaluation_id')
                      ->references('id')->on('performance_evaluations')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('performance_evaluation_files');
        Schema::dropIfExists('performance_evaluations');
    }
};
