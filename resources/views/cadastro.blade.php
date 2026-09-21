<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cadastro</title>

    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>


<body>


    <main>

        <div class="login-box">


            <h2>
                Cadastro
            </h2>



            <form method="POST" action="/cadastro">

                @csrf


                <input
                    type="text"
                    name="nomeUser"
                    placeholder="Nome de usuário"
                    required>



                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required>



                <input
                    type="password"
                    name="senha"
                    placeholder="Senha"
                    required>



                <input
                    type="password"
                    name="senha_confirmation"
                    placeholder="Confirmar senha"
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



        </div>


    </main>


</body>

</html>