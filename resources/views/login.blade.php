<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextList - Login</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <main>

        <div class="login-box">

            <div class="marca">
                <h1>NextList</h1>
                <p>organize o seu universo ✨</p>
            </div>

            <h2>Login</h2>

            <form method="POST" action="/login">

                @csrf

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    autocomplete="email"
                    required>

                <input
                    type="password"
                    name="senha"
                    placeholder="Senha"
                    autocomplete="current-password"
                    required>

                <button type="submit">
                    Entrar
                </button>

            </form>

            @if(session('erro'))

            <p class="erro">
                {{ session('erro') }}
            </p>

            @endif

            @if($errors->any())

            <div class="erro">

                @foreach($errors->all() as $erro)

                <p>
                    {{ $erro }}
                </p>

                @endforeach

            </div>

            @endif

            <p class="trocar">
                Ainda não tem conta?
                <a href="/cadastro">Cadastre-se</a>
            </p>

        </div>

    </main>

</body>

</html>