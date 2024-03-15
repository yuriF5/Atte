<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Work_timeController;

class Work_time extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','start','finish','total_time'];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
