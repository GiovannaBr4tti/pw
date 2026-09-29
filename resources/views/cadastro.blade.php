<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NextList - Cadastro</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>

    <main>

        <div class="login-box">

            <div class="marca">
                <h1>NextList</h1>
                <p>crie a sua conta e comece a organizar ✨</p>
            </div>

            <h2>Cadastro</h2>

            <form method="POST" action="/cadastro">

                @csrf

                <input
                    type="text"
                    name="nomeUser"
                    placeholder="Nome de usuário"
                    autocomplete="username"
                    required>

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
                    autocomplete="new-password"
                    required>

                <input
                    type="password"
                    name="senha_confirmation"
                    placeholder="Confirmar senha"
                    autocomplete="new-password"
                    required>

                <button type="submit">
                    Cadastrar
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
                Já tem uma conta?
                <a href="/login">Entrar</a>
            </p>

        </div>

    </main>

</body>

</html>