<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGB-Elite | Bem-vindo</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/elite.css') }}">
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    
    <div class="text-center">
        <div class="mb-4">
            <img src="{{ asset('assets/logo.png') }}" alt="SGB-Elite Logo" 
                 style="max-width: 250px; filter: drop-shadow(0 0 15px rgba(255, 215, 0, 0.6));">
        </div>
        
        <h1 class="gold-text mb-3" style="font-size: 3rem; font-weight: 800;">Barbearia Elite</h1>
        <p class="text-grey mb-5" style="font-size: 1.2rem; letter-spacing: 2px;">
            EXCELÊNCIA NO CORTE, MAESTRIA NA GESTÃO.
        </p>
        
        <a href="{{ route('login') }}" class="btn btn-gold px-5 py-3">
            ENTRAR NO SISTEMA
        </a>
    </div>

</body>
</html>