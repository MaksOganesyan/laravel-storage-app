@extends('layouts.app')

@section('title', 'Передать вещь: ' . $thing->name)

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Передать вещь: {{ $thing->name }}</h1>

        <div class="card shadow-sm">
            <div class="card-body">
                <p><strong>Доступно для передачи:</strong> <strong class="text-success">{{ $thing->available_amount }}</strong> шт.</p>

                <form action="{{ route('things.transfer.store', $thing) }}" method="POST">
                    @csrf

                    <!-- Пользователь -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Кому передать</label>
                        <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                            <option value="">-- Выберите пользователя --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Количество -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Количество</label>
                        <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                               value="{{ old('amount', 1) }}" min="1" max="{{ $thing->available_amount }}" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    @if($thing->available_amount < $thing->amount)
    <p class="text-warning mb-1">
        <strong>Передана:</strong> {{ $thing->amount - $thing->available_amount }} шт.
    </p>
@endif
@if($thing->usages->isNotEmpty())
    <p class="text-info mb-3">
        Вещь уже передана: {{ $thing->usages->first()->user->name ?? 'Неизвестно' }}
    </p>
@endif

                    <!-- Место хранения (опционально) -->
                    <div class="mb-3">
                        <label for="place_id" class="form-label">Место хранения (не обязательно)</label>
                        <select name="place_id" id="place_id" class="form-select">
                            <option value="">Не указывать</option>
                            @foreach(auth()->user()->places as $place)
                                <option value="{{ $place->id }}">{{ $place->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" {{ $thing->available_amount <= 0 ? 'disabled' : '' }}>
                        Передать вещь
                    </button>
                    <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
                </form>
            </div>
        </div>
    </div>
@endsection
