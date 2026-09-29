@php
    // Data e hora no fuso do Brasil
    $agora = now('America/Sao_Paulo');
    $hoje = $agora->format('Y-m-d');
    $amanha = $agora->copy()->addDay()->format('Y-m-d');

    $diasSemana = ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'];
    $hojeTexto = $diasSemana[$agora->dayOfWeek] . ', ' . $agora->format('d/m/Y');

    $saudacao = $agora->hour < 12 ? 'Bom dia' : ($agora->hour < 18 ? 'Boa tarde' : 'Boa noite');

    // Ajudantes
    $concluida = fn($t) => stripos($t->status->nomeStatus ?? '', 'conclu') !== false;
    $diasAte = fn($t) => (int) round((strtotime($t->prazoTarefa) - strtotime($hoje)) / 86400);

    // Resumo (considera as tarefas que estão sendo exibidas)
    $total = $tarefas->count();
    $feitas = $tarefas->filter($concluida)->count();
    $percentual = $total ? (int) round($feitas / $total * 100) : 0;

    $abertas = $tarefas->reject($concluida);
    $qtdHoje = $abertas->filter(fn($t) => $diasAte($t) === 0)->count();
    $qtdSemana = $abertas->filter(fn($t) => $diasAte($t) >= 1 && $diasAte($t) <= 7)->count();
    $qtdAtrasadas = $abertas->filter(fn($t) => $diasAte($t) < 0)->count();

    if ($total === 0) {
        $frase = 'Sua lista está vazia. Que tal criar a primeira tarefa?';
    } elseif ($qtdAtrasadas > 0) {
        $frase = "Você tem {$qtdAtrasadas} " . ($qtdAtrasadas == 1 ? 'tarefa atrasada' : 'tarefas atrasadas') . '. Respire fundo e vá por partes.';
    } elseif ($qtdHoje > 0) {
        $frase = "Hoje vencem {$qtdHoje} " . ($qtdHoje == 1 ? 'tarefa' : 'tarefas') . '. Você consegue!';
    } else {
        $frase = 'Tudo em dia por aqui. Continue brilhando!';
    }

    // Rótulo do prazo em cada card
    $rotuloPrazo = function ($t) use ($concluida, $diasAte) {
        if ($concluida($t)) {
            return ['prazo-ok', 'Concluída ✓'];
        }
        $d = $diasAte($t);
        if ($d < 0) {
            return ['prazo-atrasado', 'Atrasada há ' . abs($d) . (abs($d) == 1 ? ' dia' : ' dias')];
        }
        if ($d === 0) {
            return ['prazo-hoje', 'Vence hoje'];
        }
        if ($d === 1) {
            return ['prazo-breve', 'Amanhã'];
        }
        return ['prazo-breve', 'Em ' . $d . ' dias'];
    };

    // Filtros ativos
    $filtrosAtivos = request()->filled('data') || request()->filled('inicio') || request()->filled('fim') || request()->filled('status');
    $nomeStatusFiltro = optional($status->firstWhere('idStatus', request('status')))->nomeStatus;
@endphp
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>NextList - Tarefas</title>

    <link rel="stylesheet" href="{{ asset('css/tarefas.css') }}">

</head>

<body>

    <!-- SIDEBAR -->

    <aside id="sidebar">

        <div class="logo">

            <h1>NextList</h1>

        </div>

        <div class="perfil">

            <div class="foto"></div>

            <h2>

                {{ Auth::user()->nomeUser }}

            </h2>

        </div>

        <nav>

            <a href="{{ route('tarefas') }}">
                <span class="nav-icone">📥</span>
                Entrada
            </a>

            <a href="{{ route('tarefas',['data'=>$hoje]) }}">
                <span class="nav-icone">🌞</span>
                Hoje
            </a>

            <a href="{{ route('tarefas',['inicio'=>$amanha]) }}">
                <span class="nav-icone">🚀</span>
                Em breve
            </a>

            <a href="{{ route('dashboard') }}">
                <span class="nav-icone">📊</span>
                Dashboard
            </a>

        </nav>

        <div class="mini-progresso">

            <small>
                <span>Progresso</span>
                <span>{{ $percentual }}%</span>
            </small>

            <div class="mini-barra">
                <span style="width: {{ $percentual }}%"></span>
            </div>

        </div>

        <div class="logout">

            <form action="{{ route('logout') }}"
                method="POST">

                @csrf

                <button type="submit">

                    Logout

                </button>

            </form>

        </div>

    </aside>



    <!-- CONTEÚDO -->

    <div id="content">

        <header>

            <button id="menu-btn">
                ☰
            </button>

            <h1>
                Minhas tarefas
            </h1>

            <span class="header-data">
                📅 {{ $hojeTexto }}
            </span>

        </header>



        <!-- HERO -->

        <section class="hero">

            <div class="hero-texto">

                <span class="hero-data">✨ {{ $hojeTexto }}</span>

                <h2>
                    {{ $saudacao }}, {{ Auth::user()->nomeUser }}
                </h2>

                <p>
                    {{ $frase }}
                </p>

            </div>

            <div class="hero-progresso">

                <div class="anel" style="--p: {{ $percentual }}">
                    <span>{{ $percentual }}%</span>
                </div>

                <small>{{ $feitas }} de {{ $total }} concluídas</small>

            </div>

        </section>



        <!-- RESUMO -->

        <section class="resumo">

            <div class="resumo-card rc-lilas">
                <div class="resumo-icone">📋</div>
                <div>
                    <strong>{{ $total }}</strong>
                    <span class="rotulo">Total de tarefas</span>
                </div>
            </div>

            <div class="resumo-card rc-azul">
                <div class="resumo-icone">🌞</div>
                <div>
                    <strong>{{ $qtdHoje }}</strong>
                    <span class="rotulo">Vencem hoje</span>
                </div>
            </div>

            <div class="resumo-card rc-rosa">
                <div class="resumo-icone">🚀</div>
                <div>
                    <strong>{{ $qtdSemana }}</strong>
                    <span class="rotulo">Próximos 7 dias</span>
                </div>
            </div>

            <div class="resumo-card rc-atraso">
                <div class="resumo-icone">⏰</div>
                <div>
                    <strong>{{ $qtdAtrasadas }}</strong>
                    <span class="rotulo">Atrasadas</span>
                </div>
            </div>

        </section>



        <!-- FILTROS -->

        <section class="filtros">

            <form action="{{ route('tarefas') }}"
                method="GET">

                <div class="filtros-topo">

                    <strong>🔎 Filtrar tarefas</strong>

                    @if($filtrosAtivos)
                    <a class="limpar" href="{{ route('tarefas') }}">Limpar filtros</a>
                    @endif

                </div>

                <div>

                    <label>

                        Data

                    </label>

                    <input
                        type="date"
                        name="data"
                        value="{{ request('data') }}">

                </div>

                <div>

                    <label>

                        Data Inicial

                    </label>

                    <input
                        type="date"
                        name="inicio"
                        value="{{ request('inicio') }}">

                </div>

                <div>

                    <label>

                        Data Final

                    </label>

                    <input
                        type="date"
                        name="fim"
                        value="{{ request('fim') }}">

                </div>

                <div>

                    <label>

                        Status

                    </label>

                    <select name="status">

                        <option value="">

                            Todos

                        </option>

                        @foreach($status as $item)

                        <option

                            value="{{ $item->idStatus }}"

                            {{ request('status')==$item->idStatus ? 'selected' : '' }}>

                            {{ $item->nomeStatus }}

                        </option>

                        @endforeach

                    </select>

                </div>

                <button type="submit">

                    Filtrar

                </button>

            </form>

            @if($filtrosAtivos)

            <div class="chips-filtro">

                <span class="chips-rotulo">Filtros ativos:</span>

                @if(request('data'))
                <span class="chip">📅 {{ date('d/m/Y', strtotime(request('data'))) }}</span>
                @endif

                @if(request('inicio'))
                <span class="chip">De {{ date('d/m/Y', strtotime(request('inicio'))) }}</span>
                @endif

                @if(request('fim'))
                <span class="chip">Até {{ date('d/m/Y', strtotime(request('fim'))) }}</span>
                @endif

                @if($nomeStatusFiltro)
                <span class="chip">🏷️ {{ $nomeStatusFiltro }}</span>
                @endif

            </div>

            @endif

        </section>



        <!-- TÍTULO DA LISTA -->

        <div class="secao-titulo">

            <h3>Suas tarefas</h3>

            <span class="contagem">{{ $total }}</span>

            <span class="linha"></span>

        </div>



        <section class="tarefas">

            @forelse($tarefas as $tarefa)

            @php
                [$classePrazo, $textoPrazo] = $rotuloPrazo($tarefa);
            @endphp

            <div class="card">


                <!-- CABEÇALHO DO CARD -->
                <div class="card-top">

                    <div class="card-icone i{{ $loop->index % 4 }}">
                        {{ mb_strtoupper(mb_substr($tarefa->nomeTarefa, 0, 1)) }}
                    </div>

                    <h2>
                        {{ $tarefa->nomeTarefa }}
                    </h2>

                </div>



                <!-- DESCRIÇÃO -->

                <div class="descricao">

                    <p>

                        {{ $tarefa->descTarefa }}

                    </p>

                </div>


                <!-- INFORMAÇÕES -->

                <div class="card-meta">

                    <div class="status s{{ $tarefa->idStatus % 6 }}">
                        <span>

                            {{ $tarefa->status->nomeStatus }}

                        </span>
                    </div>

                    <span class="chip-data">

                        📅 {{ date('d/m/Y',strtotime($tarefa->prazoTarefa)) }}

                    </span>

                    <span class="prazo {{ $classePrazo }}">

                        {{ $textoPrazo }}

                    </span>

                </div>



                <!-- AÇÕES -->

                <div class="acoes">


                    <button

                        class="btn-editar"

                        data-id="{{ $tarefa->idTarefa }}"

                        data-nome="{{ $tarefa->nomeTarefa }}"

                        data-desc="{{ $tarefa->descTarefa }}"

                        data-prazo="{{ $tarefa->prazoTarefa }}"

                        data-status="{{ $tarefa->idStatus }}">

                        Editar

                    </button>

                    <form

                        action="{{ route('tarefas.destroy',$tarefa->idTarefa) }}"

                        method="POST">

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"

                            class="btn-excluir"

                            onclick="return confirm('Excluir esta tarefa?')">

                            Excluir
                        </button>

                    </form>

                </div>

            </div>

            @empty

            <div class="sem-tarefa">

                <span class="vazio-emoji">🌌</span>

                <strong>Nenhuma tarefa encontrada.</strong>

                <p>Que tal criar uma nova tarefa para começar?</p>

                <button type="button"
                    class="btn-vazio"
                    onclick="document.getElementById('btn-criar').click()">

                    + Criar tarefa

                </button>

            </div>

            @endforelse

        </section>



        <!-- RODAPÉ -->

        <footer class="rodape">
            NextList ✨ organize o seu universo, uma tarefa de cada vez.
        </footer>


        <button id="btn-criar" class="btn-flutuante">

            + Nova tarefa

        </button>

        <!-- MODAL CRIAR -->

        <div id="modalCriar"
            class="modal">

            <div class="modal-content">

                <h2>

                    Nova tarefa

                </h2>

                <form

                    action="{{ route('tarefas.store') }}"

                    method="POST">

                    @csrf

                    <input

                        type="text"

                        name="nomeTarefa"

                        placeholder="Nome"

                        required>

                    <textarea

                        name="descTarefa"

                        placeholder="Descrição"

                        required></textarea>

                    <input

                        type="date"

                        name="prazoTarefa"

                        required>

                    <select name="idStatus">

                        @foreach($status as $item)

                        <option value="{{ $item->idStatus }}">

                            {{ $item->nomeStatus }}

                        </option>

                        @endforeach

                    </select>

                    <div class="modal-buttons">

                        <button type="submit">

                            Criar

                        </button>

                        <button
                            type="button"
                            id="fecharCriar">

                            Cancelar

                        </button>

                    </div>

                </form>

            </div>

        </div>



        <!-- MODAL EDITAR -->

        <div id="modalEditar"
            class="modal">

            <div class="modal-content">

                <h2>

                    Editar tarefa

                </h2>

                <form

                    id="formEditar"

                    method="POST">

                    @csrf

                    @method('PUT')



                    <input

                        type="text"

                        id="editarNome"

                        name="nomeTarefa"

                        required>



                    <textarea

                        id="editarDescricao"

                        name="descTarefa"

                        required></textarea>



                    <input

                        type="date"

                        id="editarPrazo"

                        name="prazoTarefa"

                        required>



                    <select

                        id="editarStatus"

                        name="idStatus">

                        @foreach($status as $item)

                        <option value="{{ $item->idStatus }}">

                            {{ $item->nomeStatus }}

                        </option>

                        @endforeach

                    </select>



                    <div class="modal-buttons">

                        <button type="submit">

                            Salvar

                        </button>

                        <button

                            type="button"

                            id="fecharEditar">

                            Cancelar

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

    <!-- JAVASCRIPT -->

    <script src="{{ asset('js/tarefas.js') }}"></script>

</body>

</html>