@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="main">

    <div class="date">
        <button onclick="changeDate('prev')">&lt;</button> 
        {{ date('Y-m-d') }}
        <button onclick="changeDate('next')">&gt;</button>
    </div>  
    
<script>
    function changeDate(direction) {
        var currentDate = new Date('{{ date('Y-m-d') }}');
        if (direction === 'prev') {
            currentDate.setDate(currentDate.getDate() - 1); // 1日前に移動
        } else if (direction === 'next') {
            currentDate.setDate(currentDate.getDate() + 1); // 1日後に移動
        }
        var year = currentDate.getFullYear();
        var month = ('0' + (currentDate.getMonth() + 1)).slice(-2);
        var day = ('0' + currentDate.getDate()).slice(-2);
        var formattedDate = year + '-' + month + '-' + day;
        document.querySelector('.date').innerHTML = formattedDate; // 日付を更新
        // ここに日付が変更された場合の追加の処理を記述することもできます。
    }
</script>

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
                <td class="attendance-table__item">{{ $work_time->user->name }}</td>
                <td class="attendance-table__item">{{ Carbon\Carbon::parse($work_time->start)->format('Y-m-d H:i:s') }}</td>
                @if (is_null($work_time->finish))
                <td class="attendance-table__item">(就業中)</td>
                @else
                <td class="attendance-table__item">{{ Carbon\Carbon::parse($work_time->finish)->format('Y-m-d H:i:s') }}</td>
                @endif

                <?php $found_rest_time = false; ?>
                @foreach($rest_times?? [] as $rest_time)
                    @if ($rest_time->work_time_id == $work_time->id)
                        <?php $found_rest_time = true; ?>
                        <td class="attendance-table__item">{{ Carbon\Carbon::parse($rest_time->finish)->format('Y-m-d H:i:s') }}</td>
                        <td class="attendance-table__item">{{ $rest_time->total_time }}</td>
                    @endif
                @endforeach

                @if (!$found_rest_time)
                    <td class="attendance-table__item">-</td>
                    <td class="attendance-table__item">-</td>
                @endif
            </tr>
            @endforeach
        </table>
    </div>
    {{ $work_times->links() }} 
</div>

<footer class="footer">
    <div class="footer__ttl">
        <small class="footer__small">Atte,inc.</small>
    </div>
</footer>
@endsection