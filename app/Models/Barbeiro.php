<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barbeiro extends Model
{
protected $fillable = ['nome', 'salario_base', 'user_id'];

    // Relacionamento: Um barbeiro tem muitos serviços
    public function servicos() {
        return $this->hasMany(Servico::class);
    }

    public function index() 
{
    // O Admin definirá isto no futuro, por agora guardamos numa variável
    $valorBaseDefinidoPeloAdmin = 500; 
    
    // Lógica do CU-01: 40% de bónus se for fim de semana (Sábado=6, Domingo=7)
    $diaDaSemana = date('N'); 
    $percentagemBarbeiro = ($diaDaSemana >= 6) ? 0.40 : 0.30; // 40% fim de semana.
    
    $minhaComissao = $valorBaseDefinidoPeloAdmin * $percentagemBarbeiro;

    return view('barbeiro.dashboard', [
        'valorBase' => $valorBaseDefinidoPeloAdmin,
        'comissao' => $minhaComissao,
        'isFimDeSemana' => ($diaDaSemana >= 6)
    ]);
    
}
// Dentro de app/Models/Servico.php

public function barbeiro()
{
    // O Laravel vai ligar o 'user_id' do serviço ao 'id' do utilizador
    return $this->belongsTo(User::class, 'user_id'); 
}
}