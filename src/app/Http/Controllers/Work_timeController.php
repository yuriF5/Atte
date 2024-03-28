<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Work_time;
use App\Models\Rest_time;
use App\Http\Requests\WorkRequest;
use Carbon\Carbon;
use Auth;

class Work_timeController extends Controller
{
    public function attendance()
    {$currentDate = date('Y-m-d');
        return view('attendance', compact('currentDate'));
    }
    
    public function index()
    {
    $user = Auth::user()->name;
    $current_date = $request->input('date', date('Y-m-d'));
    $prev_date = date('Y-m-d', strtotime('-1 day', strtotime($current_date)));
    $next_date = date('Y-m-d', strtotime('+1 day', strtotime($current_date)));

    $work_times = WorkTime::paginate(5);
    $rest_times = RestTime::all();
    
    return view('attendance', compact('user', 'work_times', 'rest_times', 'current_date', 'prev_date', 'next_date'));
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

    $work_finish_time = Work_time::where('user_id', $user->id)->latest()->first();

    $start_time = new Carbon($work_finish_time->start);
    $now = Carbon::now();

    $restTime = 0;
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
                    return redirect()->back()->with('message', '休憩打刻が押されていません');
                }
            }
        }
    }

        public function startRest(Request $request)
    {
        $user = Auth::user();
        $work_time_id = $request->input('work_time_id');
        $oldStartTime = Rest_time::where('work_time_id',$work_time_id)->latest()->first();
        $oldDay= '';

        if($oldStartTime) {
            $oldTimeStart = new Carbon($oldStartTime->start);
            $oldDay = $oldTimeStart->startOfDay();
        }
        $today = Carbon::today();

        if(($oldDay == $today) && (empty($oldStartTime->finish))) {
            return redirect()->back()->with('message','休憩開始打刻済みです');
        }

        if($oldStartTime) {
            $oldFinish = new Carbon($oldStartTime->finish);
            $oldDay = $oldFinish->startOfDay();
        }

        if(($oldDay == $today)) {
            return redirect()->back()->with('message','休憩終了打刻済みです');
        }

        Rest_time::create([
            'work_time_id' => $work_time_id, 
            'start' => Carbon::now(),
        ]);
        return redirect('/');
    }

    public function finishRest(Request $request)
    {
    $user = Auth::user();
    $work_time_id = $request->input('work_time_id');
    $finish_time = $request->input('finish');

    Rest_time::where('work_time_id', $work_time_id)->update(['finish' => Carbon::now()]);

    $rest_finish_time = Rest_time::where('work_time_id', $work_time_id)->latest()->first();

    $start_time = new Carbon($rest_finish_time->start);
    $now = Carbon::now();

    $restTime = 0;
        if ($rest_finish_time && $rest_finish_time->startRestTime && !$rest_finish_time->endRestTime) {
            $startRestTime = new Carbon($rest_finish_time->startRestTime);
            $finishRestTime = new Carbon($rest_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
        }
        $stayTime = $start_time->diffInMinutes($now);
        $workingMinute = $stayTime - $restTime;
        $workingHour = ceil($workingMinute / 15) * 0.25;

        if ($rest_finish_time) {
            if (empty($rest_finish_time->finish)) {
                if ($rest_finish_time->startRestTime && !$rest_finish_time->endRestTime) {
                    return redirect()->back()->with('message', '休憩打刻が押されていません');
                } else {
                    $rest_finish_time->update([
                        'finish' => Carbon::now(),
                        'total_time' => $workingMinute
                    ]);
                    return redirect()->back();
                }
            } else {
                $today = Carbon::now();
                $day = $today->day;
                $oldRestFinish = new Carbon($rest_finish_time->finish);
                $oldRestFinishDay = $oldRestFinish->day;
                if ($day == $oldRestFinishDay) {
                    return redirect()->back();
                } else {
                    return redirect()->back()->with('message', '休憩開始打刻が押されていません');
                }
            }
        }
    }
    public function changeDate(Request $request)
    {
        // ボタンがクリックされたときの処理
        $currentDate = $request->input('currentDate');

        // ボタンによって日付を変更
        if ($request->input('direction') === 'prev') {
            $currentDate = date('Y-m-d', strtotime($currentDate . ' -1 day'));
        } elseif ($request->input('direction') === 'next') {
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        // 日付を表示
        return view('attendance', compact('currentDate'));
    }
}