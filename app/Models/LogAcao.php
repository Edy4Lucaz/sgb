<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogAcao extends Model
{
    // Definimos a tabela manualmente caso a migration tenha um nome diferente (ex: logs_acoes)
    protected $table = 'logs_acoes'; 

    protected $fillable = ['acao', 'descricao'];
}