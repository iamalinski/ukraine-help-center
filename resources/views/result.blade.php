@extends('layout.app')
@section('content')
<main id="result">
    <div class="{{ $data['status'] }}">
        <h2>
            {{ $data['message'] }}
        </h2>
        <a href="{{ URL::to('/') }}" class="btn-cancel">
            Назад
        </a>
    </div>
</main>
@endsection