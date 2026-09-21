<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function index()
    {
        return view('cadastro');
    }



    public function store(Request $request)
    {

        $request->validate([

            'nomeUser' => 'required',
            'email' => 'required|email|unique:tbUser',
            'senha' => 'required|min:6'

        ]);



        User::create([

            'nomeUser' => $request->nomeUser,

            'email' => $request->email,

            'senha' => Hash::make($request->senha)

        ]);



        return redirect('/login');

    }

}