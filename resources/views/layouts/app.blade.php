<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storage of Things @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Storage of Things</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Регистрация</a>
                        </li>
                    @else
                        <!-- Приветствие -->
                        <li class="nav-item">
                            <span class="nav-link text-white">Привет, {{ auth()->user()->name }}!</span>
                        </li>

                        <!-- Ссылки только для авторизованных -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('things.index') }}">
                                <i class="bi bi-box-seam me-1"></i> Мои вещи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('places.index') }}">
                                <i class="bi bi-house-door me-1"></i> Мои места
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('things.create') }}">
                                <i class="bi bi-plus-circle me-1"></i> Добавить вещь
                            </a>
                        </li>

                        <!-- Выход -->
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-white p-0 border-0 bg-transparent">
                                    Выход
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>