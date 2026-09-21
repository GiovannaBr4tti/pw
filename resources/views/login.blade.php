<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

<main>
    <div class="login-box">

        <h2>Login</h2>

        <form method="POST" action="/login">
            @csrf

            <input 
                type="email" 
                name="email" 
                placeholder="Email" 
                required
            >

            <input 
                type="password" 
                name="senha" 
                placeholder="Senha" 
                required
            >

            <button type="submit">
                Entrar
            </button>

            <a href='/cadastro'>Cadastre-se</a>

        </form>

        @if(session('erro'))
            <p class="erro">
                {{ session('erro') }}
            </p>
        @endif

    </div>
</main>

</body>
</html>