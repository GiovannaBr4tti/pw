<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Tarefa;
use App\Models\Status;

class TarefasController extends Controller
{

    public function index(Request $request)
    {

        $query = Tarefa::with('status')
            ->where('idUser', Auth::id());


        if ($request->filled('data')) {

            $query->whereDate(
                'prazoTarefa',
                $request->data
            );

        }

        if (
            $request->filled('inicio') &&
            $request->filled('fim')
        ) {

            $query->whereBetween(
                'prazoTarefa',
                [
                    $request->inicio,
                    $request->fim
                ]
            );

        }

        if (
            $request->filled('inicio') &&
            !$request->filled('fim')
        ) {

            $query->whereDate(
                'prazoTarefa',
                '>=',
                $request->inicio
            );

        }


        if ($request->filled('status')) {

            $query->where(
                'idStatus',
                $request->status
            );

        }


        $tarefas = $query
            ->orderBy('prazoTarefa', 'asc')
            ->get();


        $status = Status::all();

        return view(
            'tarefas',
            compact(
                'tarefas',
                'status'
            )
        );

    }


    public function store(Request $request)
    {

        $request->validate([

            'nomeTarefa' => 'required',

            'descTarefa' => 'required',

            'prazoTarefa' => 'required|date',

            'idStatus' => 'required'

        ]);


        Tarefa::create([

            'nomeTarefa' => $request->nomeTarefa,

            'descTarefa' => $request->descTarefa,

            'prazoTarefa' => $request->prazoTarefa,

            'idStatus' => $request->idStatus,

            'idUser' => Auth::id()

        ]);


        return redirect()->route('tarefas');

    }


    public function show($id)
    {

        $tarefa = Tarefa::with('status')

            ->where('idTarefa', $id)

            ->where('idUser', Auth::id())

            ->firstOrFail();


        $status = Status::all();

        return view(
            'editar-tarefa',
            compact(
                'tarefa',
                'status'
            )
        );

    }


    public function update(Request $request, $id)
    {

        $request->validate([

            'nomeTarefa' => 'required',

            'descTarefa' => 'required',

            'prazoTarefa' => 'required|date',

            'idStatus' => 'required'

        ]);


        $tarefa = Tarefa::where(
            'idTarefa',
            $id
        )

            ->where(
                'idUser',
                Auth::id()
            )

            ->firstOrFail();


        $tarefa->update([

            'nomeTarefa' => $request->nomeTarefa,

            'descTarefa' => $request->descTarefa,

            'prazoTarefa' => $request->prazoTarefa,

            'idStatus' => $request->idStatus

        ]);


        return redirect()->route('tarefas');

    }

    public function destroy($id)
    {

        $tarefa = Tarefa::where(
            'idTarefa',
            $id
        )

            ->where(
                'idUser',
                Auth::id()
            )

            ->firstOrFail();


        $tarefa->delete();

        return redirect()->route('tarefas');

    }

}