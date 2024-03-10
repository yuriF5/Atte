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
                <form class="attendance__button">
                <button class="attendance__button-start" type="submit">勤務開始</button>
                </form>
                <form class="attendance__button">
                <button class="attendance__button-stop" type="submit">勤務終了</button>
                </form>
            </div>
            <div class="main__content__bleak">
                <form class="attendance__button">
                <button class="attendance__button-start" type="submit">休憩開始</button>
                </form>
                <form class="attendance__button">
                <button class="attendance__button-stop" type="submit">休憩終了</button>
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