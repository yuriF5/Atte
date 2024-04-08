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
    
    public function index(Request $request)
    {
        // ログインしているユーザーを取得する
        $user = Auth::user();
        
        // 勤務中のIDを取得する
        $work_time_id = Work_time::whereNull('finish')
        ->where('user_id', $user->id)
        ->pluck('id')
        ->first();
        
        // ビューにデータを渡して表示する
        return view('index', ['work_time_id' => $work_time_id]);
    }
    // 日付一覧表
    public function attendance()
    {
       // 1. 今日の日付を取得
    $displayDate = now();

    // 2. ログインユーザーの情報を取得
    $user = Auth::user();

    // 3. ログインユーザーの勤務データを取得し、ページネーションを適用
    $workTimes = Work_time::with('user')
        ->where('user_id', $user->id)
        ->select('user_id', 'start', 'finish')
        ->withSum('rest_time', 'total_time')
        ->paginate(5);

    return view('attendance', compact('workTimes', 'displayDate', 'user'));
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

    // 既に勤務開始している場合の処理
    if ($oldStartTime && empty($oldStartTime->finish)) {
        return redirect()->back()->with('message', '既に勤務を開始しています。勤務終了ボタンを押してから新規の勤務を開始して下さい。');
    }

        if($oldStartTime) {
            $oldFinish = new Carbon($oldStartTime->finish);
            $oldDay = $oldFinish->startOfDay();
        }

        if(($oldDay == $today)) {
            return redirect()->back()->with('message','本日分の勤務は既に終了してます');
        }

        Work_time::create([
            'user_id'=>$user_id,
            'start'=>Carbon::now(),
        ]);
        return redirect('/')->with('message', '勤務を開始しました');
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

        // 休憩時間が存在しない場合、0をデフォルトとして設定
        $totalRestTime = $totalRestTime ?? 0;

        if ($work_finish_time && $work_finish_time->startRestTime && $work_finish_time->finishRestTime) {
            $startRestTime = new Carbon($work_finish_time->startRestTime);
            $finishRestTime = new Carbon($work_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
        }

        // 勤務時間の計算
        $totalRestTime = Rest_time::where('work_time_id', $work_finish_time->id)->sum('total_time');
        $stayTime = $start_time->diffInMinutes($now);
        $workingMinute = $stayTime - $totalRestTime;

        // 勤務時間が0未満の場合は0とする
        $workingMinute = max(0, $workingMinute);

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
                    return redirect()->back()->with('message', '本日の勤務、お疲れ様でした');
                }
            } elseif ($work_finish_time->finish) {
                $today = Carbon::now();
                $day = $today->day;
                $oldWorkFinish = new Carbon($work_finish_time->finish);
                $oldWorkFinishDay = $oldWorkFinish->day;
                if ($day == $oldWorkFinishDay) {
                    return redirect()->back()->with('message', '本日の勤務、お疲れ様でした');
                } else {
                    return redirect()->back()->with('message', '既に本日の勤務は終了してます');
                }
            }
        }   
    }

    //休憩開始 
    public function startRest(Request $request)
    {
        $user = Auth::user();
        $work_time_id = (int)$request->input('work_time_id');
        // 未終了の勤務情報を取得
        $workTimes = Work_time::whereNull('finish')->where('id', $work_time_id)->get();
        // 既存の休憩情報を取得
        $oldStartTime = Rest_time::where('work_time_id', $work_time_id)->whereNull('finish')->first();
        $oldDay = '';

        if ($oldStartTime) {
            return redirect()->back()->with('message', '既に休憩開始が押されています');
        }

        if (!$workTimes->isEmpty()) {
            Rest_time::create([
            'work_time_id' => $work_time_id,
            'start' => Carbon::now(),
        ]);
        return redirect('/')->with('message', '休憩が開始されました');
        }

        return redirect()->back()->with('error', '現在勤務中ではありません');
    }

    // 休憩終了
    public function finishRest(Request $request)
    {
        $user = Auth::user();
        $work_time_id = $request->input('work_time_id');
        $finish_time = $request->input('finish');
        
        // 未終了の休憩情報を取得
        $rest_finish_time = Rest_time::where('work_time_id', $work_time_id)->whereNull('finish')->first();
        
        // 休憩時間の合計を計算する変数を初期化
        $totalRestTime = 0;

        // 休憩開始時と終了時の時間の差を計算し、合計に追加
        if ($rest_finish_time && $rest_finish_time->startRestTime && $rest_finish_time->finishRestTime) {
            $startRestTime = new Carbon($rest_finish_time->startRestTime);
            $finishRestTime = new Carbon($rest_finish_time->finishRestTime);
            $restTime = $startRestTime->diffInMinutes($finishRestTime);
            // 休憩時間を合計に追加
            $totalRestTime += $restTime;
        }

        // 合計された休憩時間が0であれば、0を追加
        if ($totalRestTime == 0) {
            $totalRestTime = 0;
        }

        // 休憩終了時の時刻を取得
        $finishTime = Carbon::now();

        if ($rest_finish_time) {
            if (empty($rest_finish_time->finish)) {
                // 休憩が終了していない場合
                if ($rest_finish_time->startRestTime && $rest_finish_time->finishRestTime) {
                    // 休憩開始時刻と終了時刻がある場合、休憩時間を計算して更新
                    $total_time = $totalRestTime;
                    $rest_finish_time->update([
                        'finish' => $finishTime,
                        'total_time' => $total_time
                    ]);
                    return redirect()->back()->with('message', '休憩が終了しました');
                } else {
                    // 休憩開始時刻がない場合、休憩終了時刻を更新
                    $rest_finish_time->update([
                        'finish' => $finishTime,
                        'total_time' => 0 // 休憩時間がない場合、total_timeを0に設定
                    ]);
                    return redirect()->back()->with('message', '休憩が終了しました');
                }
            } else {
                // すでに休憩が終了している場合はメッセージを表示
                return redirect()->back()->with('message', '休憩終了打刻済みです');
            }
        } else {
            // 休憩情報が見つからない場合はエラーメッセージを表示
            return redirect()->back()->with('error', '休憩情報が見つかりません');
        }
    }
}