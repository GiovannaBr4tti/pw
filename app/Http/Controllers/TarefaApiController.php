<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tarefa;


class TarefaApiController extends Controller
{


    public function index(Request $request)
    {


        $query = Tarefa::with('status');




        if($request->filled('idUser'))
        {

            $query->where(
                'idUser',
                $request->idUser
            );

        }


        if($request->filled('data'))
        {

            $query->whereDate(
                'prazoTarefa',
                $request->data
            );

        }


        if(
            $request->filled('inicio') &&
            $request->filled('fim')
        )
        {

            $query->whereBetween(
                'prazoTarefa',
                [
                    $request->inicio,
                    $request->fim
                ]
            );

        }


        if($request->filled('status'))
        {

            $query->where(
                'idStatus',
                $request->status
            );

        }


        $tarefas = $query
            ->orderBy(
                'prazoTarefa',
                'asc'
            )
            ->get();



        return response()->json($tarefas);


    }





    /*
    |--------------------------------------------------------------------------
    | BUSCAR UMA TAREFA ESPECÍFICA
    |--------------------------------------------------------------------------
    |
    | GET /api/tarefas/{id}
    |
    | Ex:
    |
    | GET /api/tarefas/5
    |
    | Retorna:
    | idTarefa
    | nome
    | descrição
    | prazo
    | status
    | usuário
    |
    */


    public function show($id)
    {


        $tarefa = Tarefa::with('status')
            ->find($id);



        if(!$tarefa)
        {

            return response()->json([

                "erro"=>"Tarefa não encontrada"

            ],404);

        }



        return response()->json($tarefa);


    }







    /*
    |--------------------------------------------------------------------------
    | CRIAR TAREFA
    |--------------------------------------------------------------------------
    |
    | POST /api/tarefas
    |
    */


    public function store(Request $request)
    {


        $request->validate([


            "nomeTarefa"=>"required",

            "descTarefa"=>"required",

            "prazoTarefa"=>"required|date",

            "idStatus"=>"required",

            "idUser"=>"required"


        ]);




        $tarefa = Tarefa::create([


            "nomeTarefa"=>$request->nomeTarefa,

            "descTarefa"=>$request->descTarefa,

            "prazoTarefa"=>$request->prazoTarefa,

            "idStatus"=>$request->idStatus,

            "idUser"=>$request->idUser


        ]);




        return response()->json([


            "mensagem"=>"Tarefa criada com sucesso",

            "tarefa"=>$tarefa


        ],201);



    }








    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR TAREFA
    |--------------------------------------------------------------------------
    |
    | PUT /api/tarefas/{id}
    |
    */


    public function update(Request $request,$id)
    {


        $tarefa = Tarefa::find($id);



        if(!$tarefa)
        {

            return response()->json([

                "erro"=>"Tarefa não encontrada"

            ],404);

        }




        $tarefa->update([


            "nomeTarefa" =>
            $request->nomeTarefa ?? $tarefa->nomeTarefa,


            "descTarefa" =>
            $request->descTarefa ?? $tarefa->descTarefa,


            "prazoTarefa" =>
            $request->prazoTarefa ?? $tarefa->prazoTarefa,


            "idStatus" =>
            $request->idStatus ?? $tarefa->idStatus,


            "idUser" =>
            $request->idUser ?? $tarefa->idUser



        ]);




        return response()->json([


            "mensagem"=>"Tarefa atualizada com sucesso",

            "tarefa"=>$tarefa


        ]);



    }







    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    |
    | DELETE /api/tarefas/{id}
    |
    */


    public function destroy($id)
    {


        $tarefa = Tarefa::find($id);



        if(!$tarefa)
        {

            return response()->json([

                "erro"=>"Tarefa não encontrada"

            ],404);

        }



        $tarefa->delete();




        return response()->json([


            "mensagem"=>"Tarefa excluída com sucesso"


        ]);



    }


}