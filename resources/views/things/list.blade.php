@extends('layouts.app')

@section('title', $title ?? 'Список вещей')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">{{ $title }}</h1>

        <div class="alert alert-info text-center mb-4">
    <strong>Источник данных:</strong> {{ $source ?? 'из базы данных (первый запрос)' }}
</div>
        @if($things->isEmpty())
            <div class="alert alert-info text-center">
                В этой категории пока нет вещей.
            </div>
        @else
            <div class="row g-4">
                @foreach($things as $thing)
                    <div class="col-md-6 col-lg-4">
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

                                <p class="text-muted mb-1">
                                    Всего: <strong>{{ $thing->amount }} {{ $thing->unit->short ?? 'шт.' }}</strong>
                                </p>

                                <p class="text-success mb-1">
                                    Доступно: <strong>{{ $thing->available_amount }} {{ $thing->unit->short ?? 'шт.' }}</strong>
                                </p>

                                <div class="d-flex gap-2 mt-3">
                                    <a href="{{ route('things.edit', $thing) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Редактировать
                                    </a>

                                    <form action="{{ route('things.destroy', $thing) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить вещь?')">
                                            <i class="bi bi-trash"></i> Удалить
                                        </button>
                                    </form>

                                    @if($thing->available_amount > 0)
                                        <a href="{{ route('things.transfer', $thing) }}" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-arrow-right-circle"></i> Передать
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-outline-secondary disabled">Передать (нет доступных)</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $things->links() }}
            </div>
        @endif
    </div>
@endsection
