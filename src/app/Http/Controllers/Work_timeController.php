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
    // ページネーション
    public function attendance()
    {
        $workTimes = Work_time::with('user')->select('user_id', 'start', 'finish')->withSum('rest_times', 'total_time')->paginate(5);
    return view('attendance', compact('workTimes'));
    }
    
    // 勤務開始
    public function create(Request $request)
    {
        $user = Auth::user();
        $user_id = $request->input('user_id');
        $oldStartTime = Work_time::where('user_id',$user->id)->latest()->first();
        $oldDay= '';
        
        // 勤務開始日時定義とメッセージ設定
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
            return redirect()->back()->with('message','退勤打刻は完了しました');
        }

        Work_time::create([
            'user_id'=>$user_id,
            'start'=>Carbon::now(),
        ]);
        return redirect('/');
    }

    // 勤務終了
    public function store(Request $request)
    {
        $user = Auth::user();
        $user_id = $request->input('user_id');
        Work_time::where('user_id', $user->id)->whereNull('finish')->update(['finish' => Carbon::now()]);   
        $work_finish_time = Work_time::where('user_id', $user->id)->latest()->first();
        $start_time = new Carbon($work_finish_time->start);
        $now = Carbon::now();

         // 勤務時間を設定する為の休憩時間デフォルト値
        $restTime = 0;
        if ($work_finish_time && $work_finish_time->startRestTime && $work_finish_time->finishRestTime) {
            $startRestTime = new Carbon($work_finish_time->startRestTime);
            $finishRestTime = new Carbon($work_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
        }

        // 勤務時間の計算
        $stayTime = $start_time->diffInMinutes($now);
        $workingMinute = $stayTime - $restTime;
        $workingHour = ceil($workingMinute / 15) * 0.25;

        // 勤務終了した際のメッセージ設定
        if ($work_finish_time) {
            if (empty($work_finish_time->finish)) {
                if ($work_finish_time->startRestTime && $work_finish_time->finishRestTime) {
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

    //休憩開始 
    public function startRest(Request $request)
    {
        $user = Auth::user();
        $work_time_id = Work_time::find($request->id);
        $workTimes = Work_time::whereNull('finish')->where('user_id', Auth::user()->id)->get();
        $oldStartTime = Rest_time::where('work_time_id', $work_time_id)->latest()->first();
        $oldDay = '';

        // 日時定義
        if ($oldStartTime) {
            $oldTimeStart = new Carbon($oldStartTime->start);
            $oldDay = $oldTimeStart->startOfDay();
        }
        $today = Carbon::today();

        if ($oldDay == $today && empty($oldStartTime->finish)) {
            return redirect()->back()->with('message', '休憩開始打刻済みです');
        }
        session()->flash('message', '休憩が開始されました。');

        Rest_time::create([
            'work_time_id' => $work_time_id,
            'start' => Carbon::now(),
        ]);
    }

    // 休憩終了
    public function finishRest(Request $request)
    {
    $user = Auth::user();
    $work_time_id = $request->input('work_time_id');
    $finish_time = $request->input('finish');
    Rest_time::where('work_time_id', $work_time_id)->whereNull('finish')->update(['finish' => Carbon::now()]);
    $rest_finish_time = Rest_time::where('work_time_id', $work_time_id)->latest()->first();
    $start_time = new Carbon($rest_finish_time->start);
    $now = Carbon::now();

    $restTime = 0;
        if ($rest_finish_time && $rest_finish_time->startRestTime && $rest_finish_time->finishRestTime) {
            $startRestTime = new Carbon($rest_finish_time->startRestTime);
            $finishRestTime = new Carbon($rest_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
        }
        $stayTime = $start_time->diffInMinutes($now);
        $workingMinute = $stayTime - $restTime;
        $workingHour = ceil($workingMinute / 15) * 0.25;

        if ($rest_finish_time) {
            if (empty($rest_finish_time->finish)) {
                if ($rest_finish_time->startRestTime && $rest_finish_time->finishRestTime) {
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
    
    
}