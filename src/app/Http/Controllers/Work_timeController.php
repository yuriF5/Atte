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
    $finish_time = $request->input('finish');

    Work_time::where('user_id', $user_id)->update(['finish' => Carbon::now()]);

    // 出勤レコードを取得
    $work_finish_time = Work_time::where('user_id', $user->id)->latest()->first();

    // 出勤時間と退勤時間を取得
    $start_time = new Carbon($work_finish_time->start);
    $now = Carbon::now();

    // 休憩時間を考慮した勤務時間の計算
    $restTime = 0; // 休憩時間のデフォルト値を設定
        if ($work_finish_time && $work_finish_time->startRestTime && !$work_finish_time->endRestTime) {
            $startRestTime = new Carbon($work_finish_time->startRestTime);
            $finishRestTime = new Carbon($work_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
        }
        $stayTime = $start_time->diffInMinutes($now);
        $workingMinute = $stayTime - $restTime;
        $workingHour = ceil($workingMinute / 15) * 0.25;

        if ($work_finish_time) {
            if (empty($work_finish_time->finish)) {
                if ($work_finish_time->startRestTime && !$work_finish_time->endRestTime) {
                    return redirect()->back()->with('message', '休憩打刻が押されていません');
                } else {
                    $work_finish_time->update([
                        'finish' => Carbon::now(),
                        'total_time' => $workingMinute
                    ]);
                    return redirect()->back();
                }
            } else {
                $today = Carbon::now();
                $day = $today->day;
                $oldWorkFinish = new Carbon($work_finish_time->finish);
                $oldWorkFinishDay = $oldWorkFinish->day;
                if ($day == $oldWorkFinishDay) {
                    return redirect()->back()->with('message', '退勤済みです');
                } else {
                    return redirect()->back()->with('message', '出勤打刻が押されていません');
                }
            }
        }
    }
}