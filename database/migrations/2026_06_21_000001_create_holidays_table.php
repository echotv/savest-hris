<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('holidays')) return;

        Schema::create('holidays', function (Blueprint $table) {
            $table->increments('holiday_id');
            $table->string('holiday_name', 150);
            $table->date('holiday_date');
            $table->string('holiday_type', 50)->default('Regular Holiday');
            $table->text('description')->nullable();
            $table->boolean('is_recurring')->default(false);
            $table->tinyInteger('is_deleted')->default(0);
            $table->timestamps();

            $table->index('holiday_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('holidays');
    }
};
