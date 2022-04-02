@extends('layout.app')
@section('content')
<main>
    <form action="{{ URL::to('/statistics') }}" method="GET">
        <h1>
            <a href="{{ URL::to('/') }}" title="Към връзката">
                Регистрация на помощи
            </a>
            <span>
                /
            </span>
            <a href="{{ URL::to('/statistics') }}" class="active">
                Справки
            </a>
        </h1>
        <div class="row">
            <div class="col">
                <label for="passport_number">Паспорт №</label>
                <input type="text" name="passport_number" id="passport_number" value="{{ $search['passport_number'] ?? '' }}">
            </div>
            <div class="col">
                <label for="date_from">От дата</label>
                <input type="date" name="date_from" id="date_from" value="{{ $search['date_from'] ?? '' }}">
            </div>
            <div class="col">
                <label for="date_to">Дo дата</label>
                <input type="date" name="date_to" id="date_to" value="{{ $search['date_to'] ?? '' }}">
            </div>
            <div class="col" style="width: fit-content;">
                <button class="btn-create" title="Търсене на приложения филтър" style="height: 40px; margin-top: auto; min-width: 120px;">
                    Търсене
                </button>
            </div>
        </div>
        @if($search['passport_number'])
        @if($imports_count)


        <h2>
            Получени помощи за лицето с паспорт №: {{ $search['passport_number'] }}
        </h2>
        <table cellspacing="0" cellpadding="0">
            <tr>
                <th>
                    Вид помощ
                </th>
                <th>
                    Дата
                </th>
                <th>
                    Коментар
                </th>
            </tr>
            @foreach($imports_list as $list)
            <tr>
                <td>
                    {{ $list->activity_name }}
                </td>
                <td>
                    {{ $list->date_dmy }}
                </td>
                <td>
                    {{ $list->description ?? '-' }}
                </td>
            </tr>
            @endforeach
        </table>
        <hr />
        @else
        <p class="no-data">
            Няма намерени данни за лице с паспорт №: {{ $search['passport_number'] }}
            @if($search['date_from'] || $search['date_to'])
            за избрания период от време
            @endif
        </p>
        @endif
        @endif
        <ul class="main-statistics">
            <li>
                Брой помощи: <span>{{ $imports_count ?? 'Няма данни' }}</span>
            </li>
            @if(!$search['passport_number'])
            <li>
                Брой души: <span>{{ $people_count ?? 'Няма данни' }}</span>
            </li>
            @endif
            <li>
                Най-често ползвана: <span>{{ $most_common ?? 'Няма данни' }}</span>
            </li>
            <li>
                Най-рядко ползвана: <span>{{ $least_common  ?? 'Няма данни' }}</span>
            </li>
        </ul>
        <hr />
        <a href="{{ URL::to('/') }}" class="btn-cancel" title="Връщане към регистрация на помощи" style="width: fit-content; margin: 0 auto;">
            Към начало
        </a>
    </form>
</main>
@endsection