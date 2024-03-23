<!--打刻用ページ-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="main">
    <div class="main__inner">
        <div class="main__title">
            <p class="main__ttl">{{Auth::user()->name }}さんお疲れ様です</p>
        </div>
        <div class="main__content">
            <div class="main__content__works">
                <div class="attendance__content">
                    <form action="{{ route('work_time/start') }}" method="POST">
                        @csrf
                        <input hidden type="text" name="user_id" value="{{Auth::user()->id}}">
                        <button class="attendance__button-start" name="start" type="submit">出勤</button>
                    </form>
                    <form action="{{ route('work_time/finish') }}" method="POST">
                        @csrf
                        <input hidden type="text" name="user_id" value="{{Auth::user()->id}}">
                        <button class="attendance__button-stop" name="finish "type="submit">退勤</button>
                    </form>
                </div>
            </div>
            <div class="main__content__bleak">
                <form class="bleak__button"action="{{ route('rest_time/start') }}
                " method="POST">
                        @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}
                    ">
                    <button class="attendance__button-start" name="r_start" >休憩開始</button>
                </form>
                <form class="bleak__button"action="{{ route('rest_time/finish') }}
                " method="POST">
                        @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}
                    ">
                    <button class="attendance__button-finish" name="r_finish" >休憩終了</button>
                </form>
            </div>
        </div>  
    </div>
</div>
<script>
    const startButton = document.getElementById('start-button');
    const finishButton = document.getElementById('finish-button');
    const startTimeInput = document.getElementById('start_time');
    const finishTimeInput = document.getElementById('finish_time');
    const submitButton = document.getElementById('submit-button');

    // 出勤ボタンがクリックされたときの処理
    startButton.addEventListener('click', () => {
        const now = new Date();
        const startTime = new Date(0, 0, 0, now.getHours(), now.getMinutes());
        startTimeInput.value = startTime.toLocaleTimeString();
        startButton.disabled = true;
        submitButton.disabled = false;
    });

    // 退勤ボタンがクリックされたときの処理
    finishButton.addEventListener('click', () => {
        const now = new Date();
        const endTime = new Date(0, 0, 0, now.getHours(), now.getMinutes());
        finishTimeInput.value = endTime.toLocaleTimeString();
        finishButton.disabled = true;
        submitButton.disabled = false;
    });

    // 現在時刻を表示するための要素を取得する
    const currentTimeElement = document.getElementById('current-time');

    // 現在時刻を更新する関数を定義する
    function updateCurrentTime() {
        // 現在時刻を取得する
        const now = new Date();
        // 現在時刻を文字列に変換する
        const options = {weekday: 'short', year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric'};
        const currentTime = now.toLocaleString('ja-JP', options);
        // 現在時刻を要素に表示する
        currentTimeElement.textContent = currentTime;
    }

    // 現在時刻を初期表示する
    updateCurrentTime();

    // 1秒ごとに現在時刻を更新する
    setInterval(updateCurrentTime, 1000);

</script>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection