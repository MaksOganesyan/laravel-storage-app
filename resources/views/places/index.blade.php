@extends('layouts.app')

@section('title', 'Мои места хранения')

@section('content')
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1> Места хранения</h1>
            <a href="{{ route('places.create') }}" class="btn btn-success">
                <i class="bi bi-plus-lg"></i> Добавить место
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="alert alert-info text-center mb-4">
            <strong>Источник данных:</strong> {{ $source }}
        </div>

        @if($places->isEmpty())
            <div class="alert alert-info">
                У вас пока нет мест хранения. Добавьте первое!
            </div>
        @else
            <div class="row">
                @foreach($places as $place)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card shadow-sm h-100">
                            <div class="card-body">
                                <h5 class="card-title">{{ $place->name }}</h5>
                                <p class="card-text text-muted">
                                    {{ Str::limit($place->description ?? 'Без описания', 100) }}
                                </p>

                                <p class="text-muted small">
                                    @if($place->repair) <span class="badge bg-warning">Ремонт / Мойка</span> @endif
                                    @if(!$place->work) <span class="badge bg-danger">Не в работе</span> @endif
                                </p>

                                <div class="mt-3">
                                    <a href="{{ route('places.edit', $place) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Редактировать
                                    </a>

                                    <form action="{{ route('places.destroy', $place) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Удалить место?')">
                                            <i class="bi bi-trash"></i> Удалить
                                        </button>
                                        <p>Вещей здесь: {{ $place->things()->count() }}</p>
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
