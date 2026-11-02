<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('branches_workplaces')) {
            Schema::create('branches_workplaces', function (Blueprint $table) {
                $table->id();
                $table->string('brwp_code', 20)->unique();
                $table->string('name', 255)->unique();
                $table->text('location')->nullable();
                // 1 = an actual branch, 0 = a non-branch workplace (farm, trading post, etc.)
                $table->tinyInteger('is_branch')->default(1);
                // Carried over from the old branches table so no existing feature regresses.
                $table->integer('area')->nullable();
                $table->boolean('is_visible')->default(true);
                $table->boolean('is_deleted')->default(false);
                $table->timestamps();
            });
        }

        // One-time data migration from the old `branches` table, only if it still
        // exists and hasn't been migrated yet (safe to re-run).
        if (Schema::hasTable('branches') && DB::table('branches_workplaces')->count() === 0) {
            // Workplaces that are not formal branches (farms/trading posts/etc.) — every
            // other row from `branches` is treated as a real branch.
            $nonBranchCodes = ['Branch 11', 'Branch 12', 'Branch 15'];

            $rows = DB::table('branches')->get();
            foreach ($rows as $row) {
                DB::table('branches_workplaces')->insert([
                    'brwp_code'  => $row->branch_code,
                    'name'       => $row->branch_name,
                    'location'   => $row->address,
                    'is_branch'  => in_array($row->branch_code, $nonBranchCodes) ? 0 : 1,
                    'area'       => $row->area ?? null,
                    'is_visible' => $row->is_visible,
                    'is_deleted' => $row->is_deleted,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('branches_workplaces');
    }
};
