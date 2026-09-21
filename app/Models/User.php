<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use App\Models\Tarefa;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable;


    protected $table = 'tbUser';


    protected $primaryKey = 'idUser';


    public $timestamps = false;



    protected $fillable = [
        'nomeUser',
        'email',
        'senha'
    ];



    protected $hidden = [
        'senha'
    ];



    public function getAuthPassword()
    {
        return $this->senha;
    }


    public function tarefas()
    {
        return $this->hasMany(Tarefa::class, 'idUser');
    }
}