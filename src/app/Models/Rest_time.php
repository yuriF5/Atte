<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Rest_timeController;
use Carbon\Carbon;

class Rest_time extends Model
{
    use HasFactory;
    protected $fillable = 
    [
    'work_time_id',
    'start',
    'finish',
    'total_time'
    ];

    public function work_times()
    {
        return $this->belongsTo(work_time::class);
    }
}
