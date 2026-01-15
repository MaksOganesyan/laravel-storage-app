@extends('layouts.app')

@section('title', 'Вещи, которые мне передали')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Вещи, которые мне передали</h1>

        @if($received->isEmpty())
            <div class="alert alert-info">
                Вам пока ничего не передали.
            </div>
        @else
            <div class="row">
                @foreach($received as $thing)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $thing->name }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($thing->description ?? 'Без описания', 100) }}
                                </p>

                                <p class="text-muted small">
                                    От: {{ $thing->master->name }}<br>
                                    Количество: {{ $thing->pivot->amount }}
                                </p>

                                @if($thing->wrnt)
                                    <p class="text-muted small">
                                        Гарантия до: {{ $thing->wrnt->format('d.m.Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <a href="{{ route('things.index') }}" class="btn btn-secondary mt-3">Назад к моим вещам</a>
    </div>
@endsection
