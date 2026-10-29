<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class SystemLogsModel extends Model
{
    use HasFactory;

    protected $table = 'system_logs'; // Specify table name if different
    public $timestamps = false;
    protected $primaryKey = 'sl_id';
    public $incrementing = false; // true if it's auto-incrementing
    protected $keyType = 'int'; // or 'string' if your key is not numeric
    protected $fillable = [
        'action', 'message', 'user_id', 'log_date'
    ];
    public static function addLog(string $action,  $message, ?int $userId = null): self
    {
        return self::create([
            'action'   => $action,
            'message'  => $message,
            'user_id'  => $userId,
            'log_date' => Carbon::now('Asia/Manila'), // ✅ Philippine timezone
        ]);
    }
    public static function get_logs($log_date, $action='All', $user_id='All'): Collection
    {
        $where = [];
        if( $action != 'All') $where['action'] = $action;
        if( $user_id != 'All') $where['user_id'] = $user_id;

        $query = self::query();

        // A blank date, or the sentinel 'All', means "do not filter by date".
        if (!empty($log_date) && $log_date !== 'All') {
            $query->whereDate('system_logs.log_date', $log_date);
        }

        return $query->where($where)
            ->join('users', 'system_logs.user_id', '=', 'users.id')
            ->select('system_logs.*', 'users.name as user_name')
            ->orderBy('system_logs.log_date', 'desc')
            ->get();
    }

}
