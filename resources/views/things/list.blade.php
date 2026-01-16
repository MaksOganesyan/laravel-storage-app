@extends('layouts.app')

@section('title', $title)

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">{{ $title }}</h1>

        @if($things->isEmpty())
            <div class="alert alert-info text-center">
                В этой категории пока нет вещей.
            </div>
        @else
            <div class="row">
                @foreach($things as $thing)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $thing->name }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($thing->description ?? 'Без описания', 100) }}
                                </p>

                                @if($thing->wrnt)
                                    <p class="text-muted small">
                                        Гарантия до: {{ $thing->wrnt->format('d.m.Y') }}
                                    </p>
                                @endif

                                @if(isset($thing->pivot) && $thing->pivot->amount)
                                    <p class="text-muted small">
                                        Количество: {{ $thing->pivot->amount }}
                                    </p>
                                @endif

                                <a href="{{ route('things.edit', $thing) }}" class="btn btn-sm btn-outline-primary mt-2">
                                    <i class="bi bi-pencil"></i> Редактировать
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            
        @endif
    </div>
@endsection
