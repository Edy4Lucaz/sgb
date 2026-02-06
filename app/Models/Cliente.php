<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    protected $fillable = ['nome', 'tipo']; // Tipo: Avulso ou Mensalista

    // Relacionamento: Um cliente pode ter uma mensalidade
    public function mensalidade() {
        return $this->hasOne(Mensalidade::class);
    }
}