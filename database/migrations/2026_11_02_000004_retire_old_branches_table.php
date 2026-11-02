<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * The old `branches` table has been fully replaced by `branches_workplaces`
     * (every dependent table now points at branches_workplaces.id via brwp_id).
     * Renamed rather than dropped, so the data is still recoverable if needed.
     */
    public function up(): void
    {
        if (Schema::hasTable('branches') && !Schema::hasTable('branches_legacy_backup_20261102')) {
            DB::statement('RENAME TABLE `branches` TO `branches_legacy_backup_20261102`');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('branches_legacy_backup_20261102') && !Schema::hasTable('branches')) {
            DB::statement('RENAME TABLE `branches_legacy_backup_20261102` TO `branches`');
        }
    }
};
