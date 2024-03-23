<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_time;
use App\Http\Requests\WorkRequest;
use Carbon\Carbon;
use Auth;

class Work_timeController extends Controller
{
    public function attendance()
    {
        return view('attendance');
    }
    
    public function index()
    {
        $user = Auth::user()->name;
        return view('index',compact('user'));
    }

    public function create(Request $request)
    {
        
        $user = Auth::user();
        $user_id = $request->input('user_id');
        $oldStartTime = Work_time::where('user_id',$user->id)->latest()->first();
        $oldDay= '';
        
        if($oldStartTime) {
            $oldTimeStart = new Carbon($oldStartTime->start);
            $oldDay = $oldTimeStart->startOfDay();
        }
        $today = Carbon::today();

        if(($oldDay == $today) && (empty($oldStartTime->finish))) {
            return redirect()->back()->with('message','出勤打刻済みです');
        }

        if($oldStartTime) {
            $oldFinish = new Carbon($oldStartTime->finish);
            $oldDay = $oldFinish->startOfDay();
        }

        if(($oldDay == $today)) {
            return redirect()->back()->with('message','退勤打刻済みです');
        }

        Work_time::create([
            'user_id'=>$user_id,
            'start'=>Carbon::now(),
        ]);
        return redirect('/');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $request->input('user_id');
        $finish_time=$request->input('finish');
        Work_time::where('user_id', $user_id)->update(['finish' => Carbon::now()]);

        $work_finish_time = Work::where('user_id',$user->id)->latest()->first();
        $now = new Carbon();// Carbonインスタンスを生成するときはCarbon::now()を使う
        $start_time = new Carbon($work_finish_time->start_time);
        $startRestTime = new Carbon($work_finish_time->startRestTime);
        $finishRestTime = new Carbon($work_finish_time->finishRestTime);
        $stayTime = $start_time->diffInMinutes($now);
        $restTime =  $startRestTime->diffInMinutes($finishRestTime);
        $workingMinute = $stayTime - $restTime;
        $workingHour = ceil($workingMinute / 15) * 0.25;

        if($work_finish_time) {
            if(empty($work_finish_time->finish)) {
                if($work_finish_time->startRestTime && !$work_finish_time->endRestTime) {
                    return redirect()->back()->with('message','休憩打刻が押されていません');
                }else{
                    $work_finish_time->update([
                        'finish' => Carbon::now(),
                        'total_time' => $workingMinute
                    ]);
                    return redirect()->back();
                }
            }else{
                $today = new Carbon();
                $day = $today->day;
                $oldWorkFInish = new Carbon();
                $oldWorkFinishDay = $oldWorkFinish->day;
                if($day == $oldWorkFinishDay) {
                    return redirect()->back()->with('message','退勤済みです');
                }else{
                    return redirect()->back()->with('message','出勤打刻が押されていません');
                }
            }
        }else{
            return redirect()->back()->with('message','出勤打刻がされていません');
        }
    }
}