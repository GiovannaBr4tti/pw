<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <title>NextList - Organize suas tarefas</title>
</head>

<body>

    <!-- TOPO -->

    <header class="topo">

        <div class="container">

            <a href="/" class="logo">
                <img src="/image/logo.png" alt="Logo" onerror="this.style.display='none'">
                <h2>NextList</h2>
            </a>

            <nav>
                <a href="#recursos">Recursos</a>
                <a href="#como-funciona">Como funciona</a>
                <a href="/sobre">Sobre</a>
                <a href="/devs">Devs</a>
                <a href="#contato">Contato</a>
                <a href="/login" class="btn-nav">Login</a>
            </nav>

        </div>

    </header>


    <main>

        <div class="container">

            <!-- HERO -->

            <section class="hero">

                <div class="hero-texto">

                    <span class="selo">✨ Organização simples e bonita</span>

                    <h1>
                        Organize suas tarefas e <span>conquiste o seu dia</span>
                    </h1>

                    <p>
                        O NextList reúne tudo o que você precisa fazer em um só lugar:
                        crie tarefas, defina prazos, acompanhe o status e veja o seu
                        progresso em gráficos.
                    </p>

                    <div class="hero-botoes">
                        <a href="/cadastro" class="btn">Começar agora</a>
                        <a href="/login" class="btn-sec">Já tenho conta</a>
                    </div>

                </div>

                <!-- prévia ilustrativa -->
                <div class="mock" aria-hidden="true">

                    <div class="mock-topo">
                        <span>Minhas tarefas</span>
                        <small>66% concluído</small>
                    </div>

                    <div class="mock-barra"><span></span></div>

                    <div class="mock-tarefa">
                        <strong>Entregar o trabalho</strong>
                        <div class="mock-linha">
                            <span class="pill azul">Em andamento</span>
                            <span class="pill lilas">📅 30/09</span>
                            <span class="pill ambar">Amanhã</span>
                        </div>
                    </div>

                    <div class="mock-tarefa">
                        <strong>Estudar para a prova</strong>
                        <div class="mock-linha">
                            <span class="pill rosa">Pendente</span>
                            <span class="pill lilas">📅 03/10</span>
                            <span class="pill azul">Em 5 dias</span>
                        </div>
                    </div>

                    <div class="mock-tarefa">
                        <strong>Revisar o código</strong>
                        <div class="mock-linha">
                            <span class="pill verde">Concluído</span>
                            <span class="pill lilas">📅 27/09</span>
                            <span class="pill verde">Concluída ✓</span>
                        </div>
                    </div>

                </div>

            </section>


            <!-- RECURSOS -->

            <section class="secao" id="recursos">

                <div class="secao-cabecalho">
                    <h2>Tudo para manter as tarefas em dia</h2>
                    <p>Ferramentas simples para você não perder nenhum prazo.</p>
                </div>

                <div class="recursos">

                    <div class="recurso">
                        <div class="icone">📝</div>
                        <h3>Crie e edite tarefas</h3>
                        <p>Adicione nome, descrição, prazo e status em segundos, e ajuste quando quiser.</p>
                    </div>

                    <div class="recurso">
                        <div class="icone">📅</div>
                        <h3>Prazos e filtros</h3>
                        <p>Veja o que vence hoje, o que vem em breve ou filtre por período e por status.</p>
                    </div>

                    <div class="recurso">
                        <div class="icone">🏷️</div>
                        <h3>Status coloridos</h3>
                        <p>Identifique de relance o que está pendente, em andamento ou concluído.</p>
                    </div>

                    <div class="recurso">
                        <div class="icone">📊</div>
                        <h3>Dashboard com gráficos</h3>
                        <p>Acompanhe o seu progresso, a carga dos próximos dias e a saúde dos seus prazos.</p>
                    </div>

                </div>

            </section>


            <!-- COMO FUNCIONA -->

            <section class="secao" id="como-funciona">

                <div class="secao-cabecalho">
                    <h2>Como funciona</h2>
                    <p>Em três passos você já está organizado.</p>
                </div>

                <div class="passos">

                    <div class="passo">
                        <div class="numero">1</div>
                        <h3>Crie a sua conta</h3>
                        <p>Faça o cadastro gratuito e entre no NextList.</p>
                    </div>

                    <div class="passo">
                        <div class="numero">2</div>
                        <h3>Adicione as suas tarefas</h3>
                        <p>Registre o que precisa fazer e defina o prazo de cada uma.</p>
                    </div>

                    <div class="passo">
                        <div class="numero">3</div>
                        <h3>Acompanhe o progresso</h3>
                        <p>Use os filtros e o dashboard para ver como você está indo.</p>
                    </div>

                </div>

            </section>


            <!-- CHAMADA -->

            <section class="secao">

                <div class="chamada">
                    <h2>Pronto para organizar o seu universo?</h2>
                    <p>Comece agora e deixe as suas tarefas sempre à vista.</p>
                    <a href="/cadastro" class="btn">Criar minha conta</a>
                </div>

            </section>


            <!-- CONTATO -->

            <section class="secao" id="contato">

                <div class="contato-container">

                    <div class="contato-texto">

                        <h2>Fale com a gente</h2>

                        <p>
                            Tem uma dúvida, sugestão ou encontrou algum problema no NextList?
                            Envie uma mensagem e a nossa equipe responde o mais rápido possível.
                        </p>

                        <div class="contato-info">
                            ✉️ nextlist@email.com
                        </div>

                    </div>

                    <div class="contato">

                        <h3>Entre em contato</h3>

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

                        @if(session('sucesso'))

                        <p class="sucesso">
                            {{ session('sucesso') }}
                        </p>

                        @endif

                        @if(session('erro'))

                        <p class="erro">
                            {{ session('erro') }}
                        </p>

                        @endif

                        @if($errors->any())

                        <div class="erro">

                            @foreach($errors->all() as $erro)

                            <p>{{ $erro }}</p>

                            @endforeach

                        </div>

                        @endif

                    </div>

                </div>

            </section>

        </div>

    </main>


    <!-- RODAPÉ -->

    <footer class="rodape">

        <div class="container">

            <div class="rodape-grade">

                <div class="rodape-marca">
                    <h2>NextList</h2>
                    <p>Organize suas tarefas de forma simples, bonita e eficiente.</p>
                </div>

                <div class="rodape-colunas">

                    <div class="rodape-secao">
                        <h4>Navegação</h4>
                        <a href="/login">Entrar</a>
                        <a href="/cadastro">Cadastro</a>
                        <a href="/sobre">Sobre</a>
                        <a href="/devs">Devs</a>
                    </div>

                    <div class="rodape-secao">
                        <h4>Contato</h4>
                        <p>nextlist@email.com</p>
                    </div>

                </div>

            </div>

            <div class="rodape-copy">
                © 2026 NextList ✨ organize o seu universo, uma tarefa de cada vez.
            </div>

        </div>

    </footer>

</body>

</html>