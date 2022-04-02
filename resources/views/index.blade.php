@extends('layout.app')
@section('content')
<main>
    <form action="{{ URL::to('/add') }}" method="POST">
        @CSRF
        <h1>
            <a href="{{ URL::to('/') }}" class="active">
                Регистрация на помощи
            </a>
            <span>
                /
            </span>
            <a href="{{ URL::to('/statistics') }}" title="Към връзката">
                Справки
            </a>
        </h1>
        <div class="row">
            <div class="col" title="Кликнете върху полето и въведете трите имена на лицето.">
                <label for="person_name">
                    Три имена*
                </label>
                <input type="text" name="person_name" id="person_name" autofocus value="{{ old('person_name') }}" class="{{ $errors->has('person_name') ? 'validate' : '' }}">
            </div>
            <div class="col" title="Кликнете върху полето и въведете паспортният номер на лицето. ">
                <label for="person_passport_number">
                    Паспорт №*
                </label>
                <input type="text" name="person_passport_number" id="person_passport_number" value="{{ old('person_passport_number') }}" class="{{ $errors->has('person_passport_number') ? 'validate' : '' }}">
            </div>
        </div>
        <div class="options row">
            @foreach($activities as $key=>$activity)
            <div class="col" title="Кликнете върху кутийката, за да изберете вид помощ - {{ $activity->name }}">
                <label class="container">
                    {{ $activity->name }}
                    <input type="checkbox" name="activities[]" value="{{ $activity->id }}" @if(old('activities')){{  in_array( $activity->id, old('activities')) ? 'checked' : '' }}@endif class="{{ $errors->has('activities') ? 'validate' : '' }}">
                    <span class="checkmark"></span>
                </label>
            </div>
            @endforeach
        </div>
        <div class="row">
            <div class="col">
                <label for="description">
                    Коментар
                </label>
                <textarea name="description" id="description" class="{{ $errors->has('description') ? 'validate' : '' }}">{{ old('description') }}</textarea>
            </div>
        </div>
        <hr />
        @if ($errors->any())
        <ul class="errors">
            @foreach ($errors->all() as $error)
            <li>
                {{ $error }}
            </li>
            @endforeach
        </ul>
        @endif
        <button class="btn-create" title="Натиснете, за да запазите въведените от Вас данни.">
            Запазване на данните
        </button>
    </form>
</main>
@endsection