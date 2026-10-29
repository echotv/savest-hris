<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceEvaluationFile extends Model
{
    protected $table    = 'performance_evaluation_files';
    protected $fillable = ['evaluation_id', 'url'];

    public function evaluation()
    {
        return $this->belongsTo(PerformanceEvaluation::class, 'evaluation_id');
    }
}
