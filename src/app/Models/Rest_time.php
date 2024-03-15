<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rest_time extends Model
{
    use HasFactory;
    protected $fillable = ['work_time_id','start','finish','total_time'];
}
