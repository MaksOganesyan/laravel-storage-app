@extends('layouts.app')

@section('title', $thing->name)

@section('content')
    <div class="container py-5">
        <h1>{{ $thing->name }}</h1>
        <p>{{ $thing->description ?? 'Без описания' }}</p>
        @if($thing->wrnt)
            <p>Гарантия до: {{ $thing->wrnt->format('d.m.Y') }}</p>
        @endif
        <p>Количество: {{ $thing->amount }}</p>
        <a href="{{ route('things.index') }}" class="btn btn-secondary">Назад</a>
    </div>
@endsection
