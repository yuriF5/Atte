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
    @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
        </div>
        <div class="main__content">
            <div class="main__content__works">
                <form class="attendance__content" action="{{ route('work_time/start') }}" method="POST">
                    @csrf
                    <input hidden type="text" name="user_id" value="{{Auth::user()->id}}">
                    <button class="attendance__button-start" name="start" type="submit">出勤</button>
                </form>
                <form class="attendance__content" action="{{ route('work_time/finish') }}" method="POST">
                    @csrf
                    <input hidden type="text" name="user_id" value="{{Auth::user()->id}}">
                    <button class="attendance__button-stop" name="finish "type="submit" >退勤</button>
                </form>
            </div>
            <div class="main__content__bleak">
                <form class="attendance__content"action="{{ route('rest_time/start') }}
                " method="POST">
                        @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}
                    ">
                    <button class="attendance__button-start" name="startRest" >休憩開始</button>
                </form>
                <form class="attendance__content"action="{{ route('rest_time/finish') }}
                " method="POST">
                        @csrf
                    <input type="hidden" name="user_id" value="{{Auth::user()->id}}
                    ">
                    <button class="attendance__button-stop" name="finishRest">休憩終了</button>
                </form>
            </div>
        </div>  
    </div>
</div>



<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection