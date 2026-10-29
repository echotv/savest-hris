<?php
// Runs /tmp/positions_upload.sql through the HRIS app's own DB connection.
// Backs up the current positions to JSON first; inserts only positions that don't exist yet.
$root = '/home/savest-hr/htdocs/hr.savest.cloud';
require $root . '/vendor/autoload.php';
$app = require $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$sql   = preg_replace('/^--.*$/m', '', file_get_contents('/tmp/positions_upload.sql'));
$stmts = array_values(array_filter(array_map('trim', preg_split('/;\s*[\r\n]+/', $sql))));

$before = DB::table('positions')->count();
file_put_contents('/tmp/positions_backup_before.json', DB::table('positions')->get()->toJson());
echo "before_count=$before (backup saved to /tmp/positions_backup_before.json)\n";
echo "already existing, will be skipped: " . count(DB::select($stmts[0])) . "\n";

$inserted = DB::affectingStatement($stmts[1]);
echo "inserted=$inserted\n";
echo "after_count=" . DB::table('positions')->count() . "\n";
