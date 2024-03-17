
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
            <td class="attendance-table__item">{{Auth::user()->name }}</td>

                </td>
            <td class="attendance-table__item">サンプルテキスト</td>
            <td class="attendance-table__item">サンプルテキスト</td>
            <td class="attendance-table__item">サンプルテキスト</td>
            <td class="attendance-table__item">サンプルテキスト</td>
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