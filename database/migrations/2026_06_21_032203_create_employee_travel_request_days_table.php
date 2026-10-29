<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employee_travel_request_days')) return;

        Schema::create('employee_travel_request_days', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('travel_request_id');
            $table->date('request_date');
            $table->time('time_start');
            $table->time('time_end');
            $table->timestamps();

            $table->foreign('travel_request_id')
                  ->references('id')->on('employee_travel_requests')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_travel_request_days');
    }
};
