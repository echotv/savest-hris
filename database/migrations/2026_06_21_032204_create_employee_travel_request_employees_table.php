<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employee_travel_request_employees')) return;

        Schema::create('employee_travel_request_employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_request_id');
            $table->unsignedInteger('employee_id')->index();
            $table->timestamps();

            $table->foreign('travel_request_id')
                  ->references('id')->on('employee_travel_requests')
                  ->onDelete('cascade');

            $table->index(['travel_request_id', 'employee_id'], 'idx_etre_req_emp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_travel_request_employees');
    }
};
