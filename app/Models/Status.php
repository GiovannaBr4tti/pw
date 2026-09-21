<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'tbStatus';

    protected $primaryKey = 'idStatus';

    public $timestamps = false;


    protected $fillable = [
        'nomeStatus'
    ];



    public function tarefas()
    {
        return $this->hasMany(Tarefa::class, 'idStatus');
    }
}