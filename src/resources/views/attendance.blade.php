@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="center">
        <div class="date">
            <button onclick="changeDate('prev')">&lt;</button> 
            {{ date('Y-m-d') }}
            <button onclick="changeDate('next')">&gt;</button>
        </div>  
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
            @foreach($work_times?? [] as $work_time)
            <tr class="attendance-table__row">
                <td class="attendance-table__item">{{ $workTime->user->name }}</td>
                <td class="attendance-table__item">{{ $workTime->start }}</td>
                <td class="attendance-table__item">{{ $workTime->finish }}</td>
                <td class="attendance-table__item">{{ $workTime->rest_times_sum_total_time }}</td>
                <td class="attendance-table__item">{{ $workTime->total }}</td>
            </tr>
            @endforeach
        </table>
        {{ $workTimes->links() }}
    </div>

</div>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection