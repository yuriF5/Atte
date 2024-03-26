<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Work_timeController;
use Carbon\Carbon;

class Work_time extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'start',
        'finish',
        'total_time'
    ];

    

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function rest_time()
    {
        return $this->hasMany(Rest_time::class);
    }
}
