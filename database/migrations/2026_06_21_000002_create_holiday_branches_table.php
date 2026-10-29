<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('holiday_branches')) return;

        Schema::create('holiday_branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('holiday_id')->index();
            $table->string('branch_code', 20);
            $table->unique(['holiday_id', 'branch_code']);

            $table->foreign('holiday_id')
                  ->references('holiday_id')->on('holidays')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holiday_branches');
    }
};
