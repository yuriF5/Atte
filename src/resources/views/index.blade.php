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
                <form class="attendance__button" action="/work/start" method="post">
                @csrf
                    <div class="attendance__button-ttl">
                        <input type="hidden" name="user_id" value="user_id">
                        <button class="attendance__button-start" name="start_time" value="{{Auth::user()->id}}">勤務開始</button>
                    </div>
                </form>
                <form class="attendance__button" action="/work/finish"  method="post">
                @csrf
                    <div class="attendance__button-ttl">
                        <input type="hidden" name="user_id" value="user_id">
                        <button class="attendance__button-stop" class="end__button" name="end_time" value="{{Auth::user()->id}}">勤務終了</button>
                    </div>
                </form>
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

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection