<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class UserApiController extends Controller
{

    public function index()
    {

        $usuarios = User::all();


        return response()->json($usuarios);

    }


    public function show($id)
    {

        $usuario = User::find($id);



        if(!$usuario)
        {

            return response()->json([

                'erro'=>'Usuário não encontrado'

            ],404);

        }



        return response()->json($usuario);

    }








    /*
    |--------------------------------------------------------------------------
    | CRIAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {


        $request->validate([


            'nomeUser'=>'required',


            'email'=>'required|email|unique:tbUser,email',


            'senha'=>'required|min:6'


        ]);




        $usuario = User::create([


            'nomeUser'=>$request->nomeUser,


            'email'=>$request->email,


            // Criptografa a senha

            'senha'=>Hash::make($request->senha)


        ]);




        return response()->json([


            'mensagem'=>'Usuário criado com sucesso',


            'usuario'=>$usuario



        ],201);


    }









    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function update(Request $request,$id)
    {


        $usuario = User::find($id);



        if(!$usuario)
        {

            return response()->json([

                'erro'=>'Usuário não encontrado'

            ],404);

        }




        $usuario->update([


            'nomeUser'=>
                $request->nomeUser ?? $usuario->nomeUser,



            'email'=>
                $request->email ?? $usuario->email,



            'senha'=>

                $request->senha

                ? Hash::make($request->senha)

                : $usuario->senha


        ]);





        return response()->json([


            'mensagem'=>'Usuário atualizado com sucesso',


            'usuario'=>$usuario



        ]);

    }









    /*
    |--------------------------------------------------------------------------
    | EXCLUIR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function destroy($id)
    {


        $usuario = User::find($id);



        if(!$usuario)
        {

            return response()->json([


                'erro'=>'Usuário não encontrado'


            ],404);

        }




        $usuario->delete();




        return response()->json([


            'mensagem'=>'Usuário excluído com sucesso'


        ]);

    }


}