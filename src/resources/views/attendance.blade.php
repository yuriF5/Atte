
@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class=main>

    <div class="text-xl">
        <div class=date>{{date('Y-m-d')}}</div>
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
            <td class="attendance-table__item">
                <td class="attendance-table__item">{{$work_time->user_id->name;}}</td>
            <td class="attendance-table__item">
                <td class="attendance-table__item">{{ Carbon\Carbon::parse($work_time->start)->format('Y-m-d H:i:s'); }}</td>
                @if (is_null($work_time->finish))
                <td>(就業中)</td>
                @endif
            <td class="attendance-table__item">
                <td class="attendance-table__item">{{ Carbon\Carbon::parse($work_time->finish)->format(Y-m-d H:i:s'); }}</td>
            <td class="attendance-table__item">
                <td class="attendance-table__item">{{ ($rest_time->total_time)->format('Y-m-d H:i:s'); }}</td>
            <td class="attendance-table__item">
                <td class="attendance-table__item">{{Carbon\Carbon::parse($work_time->total_time)->format(Y-m-d H:i:s');}}</td>      
        </tr>

        </table>
</div>
</div>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection
