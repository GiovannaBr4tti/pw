@php
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\DB;

    $idUser = Auth::id();

    // Data do banco (mesma referência usada nas consultas com CURDATE)
    $hojeBanco = DB::selectOne('SELECT CURDATE() as hoje')->hoje;
    $inicio = \Carbon\Carbon::parse($hojeBanco);
    $amanhaBanco = $inicio->copy()->addDay()->format('Y-m-d');

    // Resumo (cards do topo)
    $base = DB::table('tbTarefa')
        ->join('tbStatus', 'tbTarefa.idStatus', '=', 'tbStatus.idStatus')
        ->where('tbTarefa.idUser', $idUser);

    $total = $base->clone()->count();

    // "abertas" = status que não contém "conclu" (Concluido, Concluída...)
    $abertas = $base->clone()
        ->where('tbStatus.nomeStatus', 'not like', '%conclu%')
        ->whereNotNull('tbTarefa.prazoTarefa');

    $atrasadas = $abertas->clone()
        ->whereRaw('tbTarefa.prazoTarefa < CURDATE()')
        ->count();

    $proximas = $abertas->clone()
        ->whereRaw('tbTarefa.prazoTarefa BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)')
        ->count();

    // Gráfico 1 - minhas tarefas por status
    $tarefasPorStatus = DB::table('tbTarefa')
        ->join('tbStatus', 'tbTarefa.idStatus', '=', 'tbStatus.idStatus')
        ->where('tbTarefa.idUser', $idUser)
        ->select('tbStatus.nomeStatus as status', DB::raw('COUNT(*) as quantidade'))
        ->groupBy('tbStatus.idStatus', 'tbStatus.nomeStatus')
        ->get();

    // Gráfico 2 - minhas tarefas por mês de prazo
    $tarefasPorMes = DB::table('tbTarefa')
        ->where('idUser', $idUser)
        ->whereNotNull('prazoTarefa')
        ->select(DB::raw("DATE_FORMAT(prazoTarefa, '%Y-%m') as mes"), DB::raw('COUNT(*) as quantidade'))
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    // Gráfico 3 - carga dos próximos 14 dias (planejamento)
    $cargaBanco = DB::table('tbTarefa')
        ->join('tbStatus', 'tbTarefa.idStatus', '=', 'tbStatus.idStatus')
        ->where('tbTarefa.idUser', $idUser)
        ->where('tbStatus.nomeStatus', 'not like', '%conclu%')
        ->whereRaw('tbTarefa.prazoTarefa BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 13 DAY)')
        ->select('tbTarefa.prazoTarefa as dia', DB::raw('COUNT(*) as quantidade'))
        ->groupBy('tbTarefa.prazoTarefa')
        ->pluck('quantidade', 'dia');

    $diasSemana = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sáb'];

    // primeira barra: tudo o que já está atrasado
    $carga = [['rotulo' => 'Atrasadas', 'quantidade' => (int) $atrasadas, 'atrasada' => true, 'hoje' => false]];

    for ($i = 0; $i < 14; $i++) {
        $dia = $inicio->copy()->addDays($i);
        $carga[] = [
            'rotulo' => $diasSemana[$dia->dayOfWeek] . ' ' . $dia->format('d/m'),
            'quantidade' => (int) $cargaBanco->get($dia->format('Y-m-d'), 0),
            'atrasada' => false,
            'hoje' => $i === 0,
        ];
    }

    // Gráfico 4 - saúde dos prazos por mês (concluídas, em dia e atrasadas)
    $saude = DB::table('tbTarefa')
        ->join('tbStatus', 'tbTarefa.idStatus', '=', 'tbStatus.idStatus')
        ->where('tbTarefa.idUser', $idUser)
        ->whereNotNull('tbTarefa.prazoTarefa')
        ->select(
            DB::raw("DATE_FORMAT(tbTarefa.prazoTarefa, '%Y-%m') as mes"),
            DB::raw("SUM(tbStatus.nomeStatus LIKE '%conclu%') as concluidas"),
            DB::raw("SUM(tbStatus.nomeStatus NOT LIKE '%conclu%' AND tbTarefa.prazoTarefa >= CURDATE()) as em_dia"),
            DB::raw("SUM(tbStatus.nomeStatus NOT LIKE '%conclu%' AND tbTarefa.prazoTarefa < CURDATE()) as atrasadas")
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();
@endphp
<!DOCTYPE html>
<html lang="pt-br">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>NextList - Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/tarefas.css') }}">

    <script src="https://cdn.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>

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

            <a href="{{ route('tarefas',['data'=>$hojeBanco]) }}">
                <span class="nav-icone">🌞</span>
                Hoje
            </a>

            <a href="{{ route('tarefas',['inicio'=>$amanhaBanco]) }}">
                <span class="nav-icone">🚀</span>
                Em breve
            </a>

            <a href="{{ route('dashboard') }}" class="active">
                <span class="nav-icone">📊</span>
                Dashboard
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
                Dashboard
            </h1>

        </header>



        <!-- RESUMO -->

        <section class="kpi-grid">

            <div class="kpi">
                <span>Total de tarefas</span>
                <strong>{{ $total }}</strong>
            </div>

            <div class="kpi fucsia">
                <span>Atrasadas</span>
                <strong>{{ $atrasadas }}</strong>
            </div>

            <div class="kpi lilas">
                <span>Vencem nos próximos 7 dias</span>
                <strong>{{ $proximas }}</strong>
            </div>

        </section>



        <!-- GRÁFICOS -->

        <section class="dashboard-grid">

            @if($tarefasPorStatus->isEmpty())

            <div class="sem-tarefa">
                Nenhuma tarefa encontrada.
            </div>

            @else

            <div class="dashboard-chart">
                <div id="graficoStatus"></div>
            </div>

            <div class="dashboard-chart">
                <div id="graficoMes"></div>
            </div>

            <div class="dashboard-chart">
                <div id="graficoCarga"></div>
            </div>

            <div class="dashboard-chart">
                <div id="graficoSaude"></div>
            </div>

            @endif

        </section>

    </div>


    @if($tarefasPorStatus->isNotEmpty())

    <script>

        // Paleta lilás suave
        const paleta = ['#9d84e6', '#f0a6dc', '#c3b1f5', '#7d67d0', '#f7c8ee', '#b19af0', '#dcd0fa', '#5a4aa8'];

        const titulo = (texto, sub = '') => ({
            text: texto,
            subtext: sub,
            textStyle: { color: '#4b3f8f', fontSize: 16, fontWeight: 'bold' },
            subtextStyle: { color: '#7566b8', fontSize: 12 }
        });

        const eixo = {
            axisLabel: { color: '#7566b8' },
            axisLine: { lineStyle: { color: '#dcd0fa' } },
            splitLine: { lineStyle: { color: '#f0e9ff' } }
        };

        const degradeVertical = new echarts.graphic.LinearGradient(0, 0, 0, 1, [
            { offset: 0, color: '#c3b1f5' },
            { offset: 1, color: '#9d84e6' }
        ]);


        // 1 - tarefas por status (sem zoom ao passar o mouse)
        const dadosStatus = @json($tarefasPorStatus);

        const chart1 = echarts.init(document.getElementById('graficoStatus'));

        chart1.setOption({

            color: paleta,

            title: titulo('Tarefas por Status'),

            tooltip: { trigger: 'item' },

            legend: { top: '10%', left: 'center', textStyle: { color: '#5a4aa8' } },

            series: [
                {
                    name: 'Tarefas',
                    type: 'pie',
                    radius: ['40%', '70%'],
                    selectedMode: false,
                    itemStyle: { borderRadius: 8, borderColor: '#ffffff', borderWidth: 3 },
                    label: { show: false },
                    labelLine: { show: false },
                    emphasis: { scale: false, label: { show: false } },
                    data: dadosStatus.map(item => ({
                        value: item.quantidade,
                        name: item.status
                    }))
                }
            ]
        });


        // 2 - tarefas por mês de prazo
        const tarefasPorMes = @json($tarefasPorMes);

        const chart2 = echarts.init(document.getElementById('graficoMes'));

        chart2.setOption({

            title: titulo('Tarefas por mês de prazo', 'Quantas tarefas vencem em cada mês'),

            tooltip: { trigger: 'axis' },

            grid: { left: '3%', right: '4%', bottom: '3%', top: '24%', containLabel: true },

            xAxis: { type: 'category', data: tarefasPorMes.map(item => item.mes), ...eixo },

            yAxis: { type: 'value', minInterval: 1, ...eixo },

            series: [
                {
                    type: 'bar',
                    barMaxWidth: 46,
                    data: tarefasPorMes.map(item => item.quantidade),
                    itemStyle: { color: degradeVertical, borderRadius: [10, 10, 0, 0] },
                    showBackground: true,
                    backgroundStyle: { color: 'rgba(196, 181, 253, 0.15)', borderRadius: [10, 10, 0, 0] }
                }
            ]
        });


        // 3 - carga dos próximos 14 dias (para planejar a semana)
        const carga = @json($carga);

        const chart3 = echarts.init(document.getElementById('graficoCarga'));

        chart3.setOption({

            title: titulo('Carga dos próximos 14 dias', 'Tarefas abertas por dia de prazo (hoje em roxo, atrasadas em rosa)'),

            tooltip: {
                trigger: 'axis',
                formatter: p => `${p[0].name}<br/><b>${p[0].value}</b> ${p[0].value === 1 ? 'tarefa' : 'tarefas'}`
            },

            grid: { left: '3%', right: '4%', bottom: '3%', top: '26%', containLabel: true },

            xAxis: {
                type: 'category',
                data: carga.map(item => item.rotulo),
                ...eixo,
                axisLabel: { ...eixo.axisLabel, interval: 0, rotate: 50, fontSize: 11 }
            },

            yAxis: { type: 'value', minInterval: 1, ...eixo },

            series: [
                {
                    type: 'bar',
                    barMaxWidth: 26,
                    data: carga.map(item => ({
                        value: item.quantidade,
                        itemStyle: {
                            color: item.atrasada ? '#e26a8d' : (item.hoje ? '#7d67d0' : degradeVertical),
                            borderRadius: [8, 8, 0, 0]
                        }
                    })),
                    label: {
                        show: true,
                        position: 'top',
                        color: '#5a4aa8',
                        formatter: p => p.value > 0 ? p.value : ''
                    }
                }
            ]
        });


        // 4 - saúde dos prazos por mês (concluídas, em dia, atrasadas + taxa de conclusão)
        const saude = @json($saude);

        const mesesSaude = saude.map(item => item.mes);

        const taxaConclusao = saude.map(item => {
            const soma = Number(item.concluidas) + Number(item.em_dia) + Number(item.atrasadas);
            return soma ? Math.round(Number(item.concluidas) / soma * 100) : 0;
        });

        const chart4 = echarts.init(document.getElementById('graficoSaude'));

        chart4.setOption({

            title: titulo('Como estão os seus prazos', 'Concluídas, em dia e atrasadas por mês'),

            tooltip: { trigger: 'axis' },

            legend: { bottom: 0, left: 'center', textStyle: { color: '#5a4aa8' } },

            grid: { left: '3%', right: '4%', bottom: '14%', top: '26%', containLabel: true },

            xAxis: { type: 'category', data: mesesSaude, ...eixo },

            yAxis: [
                { type: 'value', minInterval: 1, ...eixo },
                {
                    type: 'value',
                    min: 0,
                    max: 100,
                    axisLabel: { color: '#7566b8', formatter: '{value}%' },
                    axisLine: { show: false },
                    splitLine: { show: false }
                }
            ],

            series: [
                {
                    name: 'Concluídas',
                    type: 'bar',
                    stack: 'prazos',
                    barMaxWidth: 46,
                    itemStyle: { color: '#9d84e6' },
                    data: saude.map(item => Number(item.concluidas))
                },
                {
                    name: 'Em dia',
                    type: 'bar',
                    stack: 'prazos',
                    itemStyle: { color: '#b8cbff' },
                    data: saude.map(item => Number(item.em_dia))
                },
                {
                    name: 'Atrasadas',
                    type: 'bar',
                    stack: 'prazos',
                    itemStyle: { color: '#e26a8d' },
                    data: saude.map(item => Number(item.atrasadas))
                },
                {
                    name: 'Taxa de conclusão',
                    type: 'line',
                    yAxisIndex: 1,
                    smooth: true,
                    symbol: 'circle',
                    symbolSize: 8,
                    lineStyle: { width: 3, color: '#5a4aa8' },
                    itemStyle: { color: '#5a4aa8' },
                    tooltip: { valueFormatter: valor => valor + '%' },
                    data: taxaConclusao
                }
            ]
        });


        // Ajusta os gráficos ao redimensionar a janela
        window.addEventListener('resize', () => {
            chart1.resize();
            chart2.resize();
            chart3.resize();
            chart4.resize();
        });

    </script>

    @endif

    <!-- JAVASCRIPT (menu lateral) -->

    <script src="{{ asset('js/tarefas.js') }}"></script>

</body>

</html>