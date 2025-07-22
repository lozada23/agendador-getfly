<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ route('redirect') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @can('isPilot')
                                <li class="nav-item"><a class="nav-link" href="{{ route('pilot.dashboard') }}">Inicio</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('pilot.courses') }}">Cursos</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('pilot.bookings') }}">Mis Reservas</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('pilot.profile') }}">Mi Perfil</a></li>
                            @endcan

                            @can('isCompany')
                                <li class="nav-item"><a class="nav-link" href="{{ route('company.dashboard') }}">Inicio</a></li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" id="estudiantesDropdown" role="button" data-bs-toggle="dropdown">
                                        Estudiantes
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('company.students.index') }}">Listado</a></li>
                                        <li><a class="dropdown-item" href="{{ route('company.students.create') }}">Registrar</a></li>
                                    </ul>
                                </li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('company.bookings') }}">Reservas</a></li>
                            @endcan

                            @can('isAdmin')
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">Inicio</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.courses.index') }}">Cursos</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.locations.index') }}">Ubicaciones</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.bookings.index') }}">Reservas</a></li>
                                <li class="nav-item"><a class="nav-link" href="{{ route('admin.export.index') }}">Exportar</a></li>
                            @endcan
                        @endauth
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Cerrar sesión
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            {{ $slot }}
        </main>
    </div>
</body>
</html>