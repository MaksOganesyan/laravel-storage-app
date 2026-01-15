@extends('layouts.app')

@section('title', 'Главная страница')

@section('content')
    <div class="container py-5">
        <!-- Заголовок -->
        <div class="text-center mb-5">
            <h1 class="display-4 fw-bold text-primary">Добро пожаловать в Storage of Things!</h1>
            <p class="lead text-muted mt-3">
                Организуйте хранение своих вещей и делитесь ими с друзьями
            </p>
        </div>

        <!-- Кнопки действий -->
        <div class="row justify-content-center mb-5">
            @auth
                <div class="col-md-4 mb-3">
                    <a href="{{ route('things.index') }}" class="btn btn-primary btn-lg w-100">
                        <i class="bi bi-box-seam me-2"></i> Мои вещи
                    </a>
                </div>
                <div class="col-md-4 mb-3">
                    <a href="{{ route('places.index') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-house-door me-2"></i> Мои места хранения
                    </a>
                </div>
            @else
                <div class="col-md-6 mb-4">
                    <a href="{{ route('register') }}" class="btn btn-success btn-lg w-100">
                        <i class="bi bi-person-plus me-2"></i> Зарегистрироваться
                    </a>
                </div>
            @endauth
        </div>
        @auth
    <div class="row justify-content-center mt-5">
        <div class="col-md-6 mb-4">
            <a href="{{ route('received.things') }}" class="btn btn-info btn-lg w-100">
                <i class="bi bi-gift me-2"></i> Вещи, которые мне передали
            </a>
        </div>
    </div>
@endauth

        <!-- Преимущества (карточки) -->
        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-shield-lock-fill text-primary display-4 mb-3"></i>
                        <h5 class="card-title">Безопасность</h5>
                        <p class="card-text text-muted">
                            Только вы решаете, кому и на какое время передать вещь
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-list-check text-success display-4 mb-3"></i>
                        <h5 class="card-title">Удобство</h5>
                        <p class="card-text text-muted">
                            Всё в одном месте: вещи, места хранения, история использования
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <i class="bi bi-people-fill text-info display-4 mb-3"></i>
                        <h5 class="card-title">Совместное использование</h5>
                        <p class="card-text text-muted">
                            Делитесь вещами с друзьями и знакомыми
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Призыв к действию (кнопка внизу) -->
        @guest
            <div class="text-center mt-5">
                <a href="{{ route('register') }}" class="btn btn-success btn-lg px-5">
                    <i class="bi bi-person-plus me-2"></i> Зарегистрироваться
                </a>
            </div>
        @else
            <div class="text-center mt-5">
                <a href="{{ route('things.create') }}" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-plus-circle me-2"></i> Добавить вещь
                </a>
            </div>
        @endguest
    </div>
@endsection
