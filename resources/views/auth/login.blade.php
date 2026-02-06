<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>SGB-Elite | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/elite.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">

<div class="card login-box" style="max-width: 400px; width: 100%;">
    <div class="text-center mb-4">
        <h2 class="gold-text">Acesso Restrito</h2>
        <p class="text-grey small">Identifique-se para gerir a Barbearia</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label class="gold-text small">E-MAIL:</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-4">
            <label class="gold-text small">SENHA:</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger bg-dark text-danger border-danger small">
                {{ $errors->first() }}
            </div>
        @endif

        <button type="submit" class="btn btn-gold w-100 py-2">ENTRAR NO PAINEL</button>
        
        <div class="text-center mt-3">
         <a href="{{ route('welcome') }}" class="text-grey small" style="text-decoration: none;">← Voltar ao Início</a>
        </div>
    </form>
</div>

</body>
</html>