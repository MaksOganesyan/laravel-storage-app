@extends('layouts.app')

@section('title', 'Передать вещь')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Передать вещь: {{ $thing->name }}</h1>

        <form action="{{ route('things.transfer.store', $thing) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="user_id" class="form-label">Кому передать</label>
                <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                    <option value="">-- Выберите пользователя --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Количество</label>
                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror"
                       value="{{ old('amount', 1) }}" min="1" max="{{ $thing->available_amount }}" required>
                <small class="form-text text-muted">
                    Доступно для передачи: <strong>{{ $thing->available_amount }}</strong> шт.
                </small>
                @error('amount')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Передать</button>
            <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
