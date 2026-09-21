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
                Entrada
            </a>

            <a href="{{ route('tarefas',['data'=>date('Y-m-d')]) }}">
                Hoje
            </a>

            <a href="{{ route('tarefas',['inicio'=>date('Y-m-d',strtotime('+1 day'))]) }}">
                Em breve
            </a>

        </nav>

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

        </header>



        <!-- FILTROS -->

        <section class="filtros">

            <form action="{{ route('tarefas') }}"
                method="GET">

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

        </section>



        <section class="tarefas">

            @forelse($tarefas as $tarefa)

            <div class="card">


                <!-- CABEÇALHO DO CARD -->
                <div class="card-top">

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

                <div class="status">
                    <span>

                        {{ $tarefa->status->nomeStatus }}

                    </span>
                </div>


                <!-- INFORMAÇÕES -->

                <div class="infos">

                    <span>

                        📅 {{ date('d/m/Y',strtotime($tarefa->prazoTarefa)) }}

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
                Nenhuma tarefa encontrada.
            </div>

            @endforelse

        </section>


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