<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use App\Models\Mensalidade;
use App\Models\User;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function dashboard()
    {
        $hoje = Carbon::today();

        // Estatísticas Reais
        $receitaHoje = Servico::whereDate('data_registo', $hoje)->sum('preco');
        $totalAtendimentos = Servico::whereDate('data_registo', $hoje)->count();
        $comissoesPagar = Servico::whereDate('data_registo', $hoje)->sum('comissao_valor');
        
        $mensalidadesVencidas = Mensalidade::where('data_expiracao', '<', $hoje)
                                           ->where('status', 'Ativo')
                                           ->count();

        // Lista para Gestão de Utilizadores (Admin vê todos para poder editar/remover)
        $barbeiros = User::whereIn('perfil', ['Barbeiro', 'Gestor', 'Admin'])->get();
        
        // Lista para o Modal de Atendimento (Apenas quem realmente corta cabelo)
        $todosBarbeiros = User::where('perfil', 'Barbeiro')->get();

        // Mensalistas Ativos para o    select do Modal de Atendimento
        $mensalistasAtivos = Mensalidade::where('status', 'Ativo')
                                       ->where('data_expiracao', '>=', $hoje)
                                       ->get();

        return view('admin.dashboard', compact(
            'receitaHoje', 
            'totalAtendimentos', 
            'comissoesPagar', 
            'mensalidadesVencidas', 
            'barbeiros', 
            'todosBarbeiros',
            'mensalistasAtivos'
        ));
    }

    public function salvarSalario(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'salario_base' => 'required|numeric|min:0'
        ]);

        $user = User::find($request->user_id);
        $user->salario_base = $request->salario_base;
        $user->save();

        return redirect()->back()->with('success', 'Salário de ' . $user->name . ' atualizado!');
    }
}