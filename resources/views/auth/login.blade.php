@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Вход</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
                @error('email')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Пароль</label>
                <input type="password" name="password" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary">Войти</button>
        </form>

        <p class="mt-3">Нет аккаунта? <a href="{{ route('register') }}">Зарегистрироваться</a></p>
    </div>
@endsection
