<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('employee_memos')) {
            Schema::create('employee_memos', function (Blueprint $table) {
                $table->id();
                $table->string('subject', 255);
                $table->date('date_issued')->index();
                $table->unsignedBigInteger('created_by')->nullable();
                $table->unsignedBigInteger('updated_by')->nullable();
                $table->tinyInteger('is_deleted')->default(0);
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('employee_memo_employees')) {
            Schema::create('employee_memo_employees', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('memo_id');
                $table->unsignedInteger('employee_id')->index();
                $table->timestamps();

                $table->foreign('memo_id')
                      ->references('id')->on('employee_memos')
                      ->onDelete('cascade');

                $table->index(['memo_id', 'employee_id'], 'idx_eme_memo_emp');
            });
        }

        if (!Schema::hasTable('employee_memo_files')) {
            Schema::create('employee_memo_files', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('memo_id');
                $table->string('url', 500);
                $table->timestamps();

                $table->foreign('memo_id')
                      ->references('id')->on('employee_memos')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_memo_files');
        Schema::dropIfExists('employee_memo_employees');
        Schema::dropIfExists('employee_memos');
    }
};
