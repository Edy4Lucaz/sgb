<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SGB-Elite | Painel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/elite.css') }}">
    
    <style>
        .modal-backdrop { z-index: 1040 !important; }
        .modal { z-index: 1050 !important; }
        .form-control:focus { background-color: #000; color: #fff; border-color: #FFD700; box-shadow: none; }
        /* Adicionei isto para garantir que o fundo do modal seja escuro como o resto do sistema */
        .modal-content { background-color: #1a1a1a !important; color: white; border: 1px solid #FFD700; }
    </style>
</head>
<body>

<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('welcome') }}">
            <img src="{{ asset('assets/logo.png') }}" alt="SGB-Elite Logo" 
                 style="height: 40px; margin-right: 15px; filter: drop-shadow(0 0 5px rgba(255, 215, 0, 0.5));">
            <span class="gold-text" style="font-weight: 800; letter-spacing: 1px;">SGB-ELITE</span>
        </a>

        <div class="d-flex align-items-center">
            @auth
            <div class="text-end me-3 d-none d-md-block">
                <div class="gold-text small" style="line-height: 1;">{{ Auth::user()->name }}</div>
                <div class="text-grey" style="font-size: 0.7rem; text-transform: uppercase;">{{ Auth::user()->perfil }}</div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-logout btn-sm">SAIR</button>
            </form>
            @endauth
        </div>
    </div>
</nav>

<div class="content-area container mt-4">
    @yield('content')
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>