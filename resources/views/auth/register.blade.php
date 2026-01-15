@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <h1 class="mb-4">Регистрация</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-3">
                <label class="form-label">Имя</label>
                <input type="text" name="name" class="form-control" required>
            </div>

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

            <div class="mb-3">
                <label class="form-label">Подтверждение пароля</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-success">Зарегистрироваться</button>
        </form>

        <p class="mt-3">Уже есть аккаунт? <a href="{{ route('login') }}">Войти</a></p>
    </div>
@endsection
