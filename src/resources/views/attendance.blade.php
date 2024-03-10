<!--退勤一覧表-->
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

<div class="text-xl">
                <div>{{date('Y-m-d')}}</div>
</div>

<div class="attendance-table">
    <table class="attendance-table__inner">
        <tr class="attendance-table__row">
        <th class="attendance-table__header">名前</th>
        <th class="attendance-table__header">勤務開始</th>
        <th class="attendance-table__header">勤務終了</th>
        <th class="attendance-table__header">休憩時間</th>
        <th class="attendance-table__header">勤務時間</th>
    </tr>
    <tr class="attendance-table__row">
        @foreach($stamps as $stamp)
        <td class="attendance-table__item">$stamp->name</td>
        <td class="attendance-table__item">$stamp->punchIn</td>
        <td class="attendance-table__item">$stamp->punchOut</td>
        <td class="attendance-table__item">stamp->total_rests</td>
        <td class="attendance-table__item">gmdate("H:i:s",(strtotime($date.$stamp->end_work)-strtotime($date.$stamp->start_work)))</td>
        @endforeach
    </tr>
    </table>
</div>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection