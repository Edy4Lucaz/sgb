<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensalidade extends Model
{
  protected $fillable = ['cliente_id', 'cliente_nome', 'data_expiracao', 'status'];

    public function cliente() {
        return $this->belongsTo(Cliente::class);
    }
}