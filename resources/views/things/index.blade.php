@extends('layouts.app')

@section('title', 'Мои вещи')

@section('content')
    <div class="container py-5">
        <!-- Заголовок и кнопка добавления -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Мои вещи</h1>
            <a href="{{ route('things.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Добавить вещь
            </a>
        </div>

        <!-- Уведомление об успехе -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Пустой список -->
        @if($things->isEmpty())
            <div class="alert alert-info text-center py-4">
                У вас пока нет вещей. Добавьте первую!
            </div>
        @else
            <!-- Список вещей в карточках -->
            <div class="row">
                @foreach($things as $thing)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $thing->name }}</h5>

                                <p class="card-text text-muted flex-grow-1">
                                    {{ Str::limit($thing->description ?? 'Без описания', 100) }}
                                </p>

                                @if($thing->wrnt)
                                    <p class="text-muted small">
                                        Гарантия до: {{ $thing->wrnt->format('d.m.Y') }}
                                    </p>
                                @endif

                                <!-- Кнопки действий -->
                                <div class="mt-auto d-flex gap-2 flex-wrap">
                                    <a href="{{ route('things.edit', $thing) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Редактировать
                                    </a>
                                    <p>Количество: {{ $thing->amount }} шт. (доступно: {{ $thing->available_amount }})</p>

                                    <form action="{{ route('things.destroy', $thing) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить вещь?')">
                                            <i class="bi bi-trash"></i> Удалить
                                        </button>
                                    </form>

                                    <form action="{{ route('things.transfer', $thing) }}" method="GET" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-arrow-right-circle"></i> Передать
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
