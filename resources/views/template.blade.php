<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} | @yield('title')</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @stack('styles')

    <style>
        .exhibition-card {
            transition: transform 0.3s ease;
            height: 100%;
        }

        .exhibition-card:hover {
            transform: translateY(-5px);
        }

        .exhibition-image {
            height: 200px;
            object-fit: cover;
        }

        :root {
            --bs-primary: #6c757d;
            --bs-secondary: #2c3e50;
        }
    </style>
</head>
<body class="antialiased">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-secondary fixed-top">
    <div class="container">
        <a class="navbar-brand" href="{{route('main')}}">Музей оптики ИТМО</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('map.index')) active @endif" href="{{ route('map.index') }}">
                        План музея
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('exhibit_group.index')) active @endif" href="{{ route('exhibit_group.index') }}">
                        Инсталляции
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link @if(request()->routeIs('exhibit.index')) active @endif" href="{{ route('exhibit.index') }}">
                        Экспонаты
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="py-4">
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    @yield('content')
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; 2025 Музей Оптики ИТМО (никакого отношения к проекту не имеет). никакие права не защищены</p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white text-decoration-none me-3">Privacy Policy</a>
                <a href="#" class="text-white text-decoration-none">Contact Us</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
