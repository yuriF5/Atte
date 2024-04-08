@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="main">
    <div class="center">
        <<form class="header__wrap" action="{{ route('per/date') }}" method="post">
        @csrf
        <button class="date__change-button" name="prevDate"><</button>
        <input type="hidden" name="displayDate" value="{{ $displayDate }}">
        <p class="header__text">{{ $displayDate->format('Y-m-d') }}</p>
        <button class="date__change-button" name="nextDate">></button>
        </form>
    </div>

    <div class="attendance-table">
        <table class="attendance-table__inner">
            @foreach ($attendanceData as $key => $data)
            <tr class="attendance-table__row">
                <th class="attendance-table__header">名前</th>
                <th class="attendance-table__header">勤務開始</th>
                <th class="attendance-table__header">勤務終了</th>
                <th class="attendance-table__header">休憩時間</th>
                <th class="attendance-table__header">勤務時間</th>
            </tr>
            @foreach ($work_times as $work_time)
            <tr class="attendance-table__row">
                <td class="attendance-table__item">{{ $users[$key]->name }}</td>
                <td class="attendance-table__item">{{ $workTimes[$key]->start }}</td>
                <td class="attendance-table__item">{{ $workTimes[$key]->finish }}</td>
                <td class="attendance-table__item">{{ $restTimes[$key]->total_time }}</td>
                <td class="attendance-table__item">{{ $workTimes[$key]->total_time }}</td>
            </tr>
            @endforeach
        </table>
        {{ $users->links('vendor/pagination/paginate') }}
    </div>
</div>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection