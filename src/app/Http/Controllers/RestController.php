<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rest_time;
use App\Http\Requests\RestRequest;
use Carbon\Carbon;
use Auth;

class RestController extends Controller
{
    public function r_start(Request $request)
    {
        $user = Auth::user();
        $rest = Rest_time::where('user_id',$user->id)->latest()->first();
        if($rest) {
            $rest->update(['start' => now()]);
        }else{
            Rest_time::create([
                'user_id' => $user->id,
                'start' => Carbon::now(),
            ]);
        }
        return redirect()->back();
    }

    
    public function r_finish(Request $request)
{
    $user = Auth::user();
    $rest = Rest_time::where('user_id', $user->id)->latest()->first();

    $finish = Carbon::now();

    if ($rest) {
        $startTime = $rest->start;
        $startTime = Carbon::parse($startTime);
        $finish = Carbon::now();
        $rest_time = $startTime->diffInMinutes($finish);

        $rest->update([
            'finish' => $finish,
            'total_time' => $total_time,
        ]);
    } else {
        $start = Carbon::now();
        $total_time = 0;
        $finish = Carbon::now();
        $total_time = $start->diffInMinutes($finish);
        $t_time = Rest_time::create([
            'user_id' => $user->id,
            'start' => null,
            'finish' => $finish,
            'total_time' => $t_time,
        ]);
    }

    return view('/', ['rest_times' => $rest_times]);
}

}
