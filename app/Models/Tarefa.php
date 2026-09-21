<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tarefa extends Model
{
    protected $table = 'tbTarefa';

    protected $primaryKey = 'idTarefa';

    public $timestamps = false;


    protected $fillable = [
        'nomeTarefa',
        'descTarefa',
        'prazoTarefa',
        'idStatus',
        'idUser'
    ];


    public function status()
    {
        return $this->belongsTo(Status::class, 'idStatus');
    }


    public function usuario()
    {
        return $this->belongsTo(User::class, 'idUser');
    }
}