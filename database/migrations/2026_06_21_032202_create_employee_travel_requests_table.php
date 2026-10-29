<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('employee_travel_requests')) return;

        Schema::create('employee_travel_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('request_type', ['On-Travel', 'Seminar', 'Training']);
            $table->date('start_date');
            $table->date('end_date');
            $table->string('purpose', 255);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_travel_requests');
    }
};
