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
                <form id="worktime-form" method="POST" action="{{ route('worktimeStore') }}">
          @csrf
          <div class="form-group row justify-content-center">
                <div class="col-md-6 d-flex">
                    <div class="mr-3">
                        @if(isset($worktime->working_flg) && ($worktime->working_flg === 1 || $worktime->working_flg === 0))
                            <button id="start-button" type="button" class="btn btn-primary btn-lg" disabled>出勤する</button>
                        @else
                            <button id="start-button" type="button" class="btn btn-primary btn-lg">出勤する</button>
                        @endif
                        <input id="start_time" type="hidden" name="start_time">
                    </div>
                    <div class="mr-3">
                        @if(isset($worktime->working_flg) && $worktime->working_flg === 1)
                            <button id="end-button" type="button" class="btn btn-secondary btn-lg">退勤する</button>
                        @else
                            <button id="end-button" type="button" class="btn btn-secondary btn-lg" disabled>退勤する</button>
                        @endif
                        <input id="end_time" type="hidden" name="end_time">
                    </div>
            
            <div class="main__content__bleak">
                <form class="attendance__button">
                @csrf
                <div class="bleak__button">
                    <input type="hidden" name="user_id" value="user_id">
                    <button class="attendance__button-start" name="start" value="">休憩開始</button>
                </div>
            </form>
            <form class="attendance__button" >
                @csrf
                <div class="bleak__button">
                    <input type="hidden" name="user_id" value="user_id">
                    <button class="attendance__button-stop">休憩終了</button>
                </div>
            </div>
        </div>  
    </div>
</div>
<script>
    const startButton = document.getElementById('start-button');
    const endButton = document.getElementById('end-button');
    const startTimeInput = document.getElementById('start_time');
    const endTimeInput = document.getElementById('end_time');
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
    endButton.addEventListener('click', () => {
        const now = new Date();
        const endTime = new Date(0, 0, 0, now.getHours(), now.getMinutes());
        endTimeInput.value = endTime.toLocaleTimeString();
        endButton.disabled = true;
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