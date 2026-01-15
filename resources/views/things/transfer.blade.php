@extends('layouts.app')

@section('title', 'Передать вещь')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Передать вещь «{{ $thing->name }}»</h1>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('things.transfer.store', $thing) }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Кому передать <span class="text-danger">*</span></label>
                <select name="user_id" class="form-control" required>
                    <option value="">-- Выберите пользователя --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Количество <span class="text-danger">*</span></label>
                <input type="number" name="amount" class="form-control" min="1" value="1" required>
                @error('amount')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-success">Передать вещь</button>
            <a href="{{ route('things.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
@endsection
