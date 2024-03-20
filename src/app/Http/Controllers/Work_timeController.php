<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_time;
use App\Http\Requests\WorkRequest;
use Carbon\Carbon;
use Auth;

class Work_timeController extends Controller
{
    public function attendance(){
        return view('attendance');
    }
    
    public function index() {
        $users = Auth::user()->name;
        return view('index',compact('user'));
    }
    public function store(Request $request)
    {
        $user_id = $request->input(users_id);
        $work_start_time=$request->input('start');
        Attendance::create([
            'users_id'=>$user_id,
            'start'=>Carbon::now(),
        ]);
        return redirect('/');
    }

    public function update(Request $request)
    {
        $user_id = $request->input(users_id);
        $work_finish_time=$request->input('finish');
        Attendance::where('user_id',$user_id)->update(['finish'=>Carbon::now()]);
        return redirect('/');
    }
}