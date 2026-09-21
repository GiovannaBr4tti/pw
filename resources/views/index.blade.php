<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <title>NextList</title>
</head>

<body>

    <header>

        <div class="logo">
            <img src="/image/logo.png" alt="Logo">
            <h2>NextList</h2>
        </div>

        <nav>
            <a href="/sobre">Sobre</a>
            <a href="/devs">Devs</a>
            <a href="/login">Login</a>
        </nav>

    </header>

        <main>


        <div class="card">

            <h1>Bem-vindo ao NextList</h1>

            <p>
                Organize suas tarefas de forma simples e eficiente.
            </p>

            <br>

            <p>
                Gerencie tarefas, usuários e mensagens de contato em um só lugar.
            </p>

        </div>

        <div class="contato-container">
            <div class="contato-texto">
                <h2>Contate-nos</h2>
                <p>
                    O NextList é um sistema simples e eficiente para gerenciamento de tarefas,
                    usuários e mensagens. Aqui você pode organizar suas atividades, acompanhar
                    prazos e manter tudo sob controle em um só lugar.
                </p>
            </div>

            <div class="contato">

                <h2>Entre em contato</h2>

                <form action="/contatos" method="POST">

                    @csrf

                    <input
                        type="text"
                        name="txNome"
                        placeholder="Nome"
                        required>

                    <input
                        type="email"
                        name="txEmail"
                        placeholder="Email"
                        required>

                    <input
                        type="text"
                        name="txAssunto"
                        placeholder="Assunto"
                        required>

                    <textarea
                        name="txMensagem"
                        placeholder="Digite sua mensagem"
                        required></textarea>

                    <button type="submit">
                        Enviar mensagem
                    </button>

                </form>

            </div>
        </div>

    </main>

    <footer>

        <h2>NextList</h2>

        <div class="footer-column">

        <div class="footer-section">
            <h3>Navegação</h3>
            <p>Tarefas</p>
            <p>Usuários</p>
            <p>Mensagens</p>
        </div>

        <div class="footer-section">
            <h3>Contato</h3>
            <p>nextlist@email.com</p>
        </div>
        </div>

        <div class="footer-section2">
            <p>© 2026 NextList</p>
        </div>


    </footer>

</body>

</html>