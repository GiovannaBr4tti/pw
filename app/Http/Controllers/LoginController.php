<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function index()
    {
        return view('login');
    }


    public function login(Request $request)
    {

        $credenciais = [
            'email' => $request->email,
            'password' => $request->senha
        ];


        if (Auth::attempt($credenciais)) {
            return redirect('/tarefas');
        }


        return back()->with('erro', 'Email ou senha incorretos');
    }


    public function logout()
    {

        Auth::logout();

        return redirect('/');
    }
}
