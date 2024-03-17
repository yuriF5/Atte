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
        $user = Auth::user()->name;
        return view('index',compact('user'));
    }
    public function start()
    {
        $user = Auth::user();
        $oldWork_time = Work_time::where('user_id', $user->id)->latest()->first();
        if ($oldWork_time) {
            $oldWork_timestart = new Carbon($oldWork_time->start);
            $oldWork_timeDay = $oldTWork_time_start->startOfDay();
        } else {
            $work_time = Work_time::create([
                'user_id' => $user->id,
                'start' => Carbon::now(),
            ]);

            return redirect()->back()->with('my_status', '出勤打刻が完了しました');

        }
        
        $newWork_timeDay = Carbon::today();

        /**
         * 日付を比較する。同日付の出勤打刻で、かつ直前のTimestampの退勤打刻がされていない場合エラーを吐き出す。
         */
        if (($oldWork_timeDay == $newWork_timeDay) && (empty($oldWork_time->finish))){
            return redirect()->back()->with('error', 'すでに出勤打刻がされています');
        }

        $work_time = Work_time::create([
            'user_id' => $user->id,
            'start' => Carbon::now(),
        ]);

        return redirect()->back()->with('my_status', '出勤打刻が完了しました');
    }

    public function finish()
    {
        $user = Auth::user();
        $work_time = Work_time::where('user_id', $user->id)->latest()->first();

        if( !empty($work_time->finish)) {
            return redirect()->back()->with('error', '既に退勤の打刻がされているか、出勤打刻されていません');
        }
        $work_time->update([
            'finish' => Carbon::now()
        ]);

        return redirect()->back()->with('my_status', '退勤打刻が完了しました');
    }
}