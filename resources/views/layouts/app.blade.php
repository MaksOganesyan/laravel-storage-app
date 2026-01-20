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
            <a class="navbar-brand" href="/">Storage of Things</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Вход</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Регистрация</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <span class="nav-link text-white">Привет, {{ auth()->user()->name }}!</span>
                        </li>

                        @if(auth()->user()->is_admin)
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-warning" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Админ
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="/things/all"><i class="bi bi-list-ul me-1"></i> Все вещи</a></li>
                                    <li><a class="dropdown-item" href="/places"><i class="bi bi-house-door me-1"></i> Управление местами</a></li>
                                </ul>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="menuDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-list me-1"></i> Меню
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="menuDropdown">
                                <!-- 5 пунктов из допа -->
                                <li><a class="dropdown-item" href="/things"><i class="bi bi-person me-1"></i> Мои вещи</a></li>
                                <li><a class="dropdown-item" href="/things/repair"><i class="bi bi-tools me-1"></i> В ремонте</a></li>
                                <li><a class="dropdown-item" href="/things/work"><i class="bi bi-gear me-1"></i> В работе</a></li>
                                <li><a class="dropdown-item" href="/things/used"><i class="bi bi-people me-1"></i> Переданные мной</a></li>
                                <li><a class="dropdown-item" href="/things/all"><i class="bi bi-list-ul me-1"></i> Все вещи</a></li>

                                <li><hr class="dropdown-divider"></li>

                                <!-- Остальные важные действия -->
                                <li><a class="dropdown-item" href="/places"><i class="bi bi-house-door me-1"></i> Мои места</a></li>
                                <li><a class="dropdown-item" href="/received-things"><i class="bi bi-gift me-1"></i> Мне передали</a></li>
                                <li><a class="dropdown-item" href="/things/create"><i class="bi bi-plus-circle me-1"></i> Добавить вещь</a></li>

                                <li><hr class="dropdown-divider"></li>

                                <!-- Выход -->
                                <li>
                                    <form action="/logout" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-1"></i> Выход
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container mt-4">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

@auth
    <script src="https://js.pusher.com/8.2/pusher.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <script>
        var pusher = new Pusher("{{ config('broadcasting.connections.pusher.key') }}", {
            cluster: "{{ config('broadcasting.connections.pusher.options.cluster') }}",
            forceTLS: true
        });

        console.log('Pusher подключён! Пользователь: {{ auth()->user()->name }}');

        var thingsChannel = pusher.subscribe('things');
        thingsChannel.bind('thing.created', function(data) {
            Toastify({
                text: `Новая вещь добавлена: ${data.thing.name} от ${data.thing.master?.name || 'кто-то'} в ${data.thing.place?.name || 'не указано'}`,
                duration: 8000,
                gravity: "top",
                position: "right",
                backgroundColor: "#28a745",
                stopOnFocus: false,
                close: true,
            }).showToast();
        });

        var placesChannel = pusher.subscribe('places');
        placesChannel.bind('place.created', function(data) {
            Toastify({
                text: `Новое место добавлено: ${data.place.name}`,
                duration: 10000,
                gravity: "top",
                position: "left",
                backgroundColor: "#007bff",
                stopOnFocus: false,
                close: true,
            }).showToast();
        });
    </script>
@endauth

</body>
</html>
