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
            <tr class="attendance-table__row">
                <th class="attendance-table__header">名前</th>
                <th class="attendance-table__header">勤務開始</th>
                <th class="attendance-table__header">勤務終了</th>
                <th class="attendance-table__header">休憩時間</th>
                <th class="attendance-table__header">勤務時間</th>
            </tr>
            @foreach ($users as $user)
            @php
            $workTime = $user->work_times->first();
            $restTime = $user->rest_times->first();
            @endphp
            <tr class="attendance-table__row">
                <td class="attendance-table__item">{{ $user->name }}</td>
                <td class="attendance-table__item">{{ $user->work_time->start }}</td>
                <td class="attendance-table__item">{{ $user->work_time->finish }}</td>
                <td class="attendance-table__item">{{ $user->total_rest }}</td>
                <td class="attendance-table__item">{{$user->total_work }}</td>
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