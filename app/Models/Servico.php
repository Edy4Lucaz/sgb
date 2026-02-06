<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Servico extends Model
{
    protected $fillable = [
        'barbeiro_id',
        'cliente_nome',
        'tipo_cliente',
        'preco',
        'comissao_valor',
        'is_weekend',
        'data_registo',
        'status' // Adicione o status aqui para o fecho de caixa funcionar bem
    ];

    public function barbeiro()
    {
        // Ligamos ao Model User usando a coluna barbeiro_id
        return $this->belongsTo(User::class, 'barbeiro_id');
    }
}