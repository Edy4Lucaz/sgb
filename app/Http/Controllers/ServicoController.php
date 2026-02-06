<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use App\Models\Configuracao;
use App\Models\Mensalidade;
use App\Models\LogAcao;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ServicoController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $hoje = Carbon::today();
        $isFimDeSemana = Carbon::now()->isWeekend();

        // AQUI ESTÁ A MUDANÇA: Adicionado where status != Fechado
        $atendimentos = Servico::where('barbeiro_id', $userId)
                        ->whereDate('data_registo', $hoje)
                        ->where('status', '!=', 'Fechado') 
                        ->get();

        $comissaoTotal = $atendimentos->sum('comissao_valor');
        $valorBase = Configuracao::where('chave', 'preco_corte')->value('valor') ?? 500;
        $comissaoSugerida = $isFimDeSemana ? ($valorBase * 0.40) : 0;

        $mensalistasAtivos = Mensalidade::where('status', 'Ativo')
                            ->whereDate('data_expiracao', '>=', $hoje)
                            ->get();

        return view('barbeiro.dashboard', compact(
            'atendimentos', 
            'comissaoTotal', 
            'isFimDeSemana', 
            'valorBase', 
            'comissaoSugerida',
            'mensalistasAtivos'
        ));
    }

    public function store(Request $request)
    {
        $precoPadrao = Configuracao::where('chave', 'preco_corte')->value('valor') ?? 500;
        $hoje = Carbon::now();
        $isFimDeSemana = $hoje->isWeekend();
        $comissaoCalculada = $isFimDeSemana ? ($precoPadrao * 0.40) : 0;

        $barbeiroId = $request->barbeiro_id ?? Auth::id();
        $tipoClienteId = $request->tipo_cliente; 
        
        if ($tipoClienteId == 2) { 
            $m = Mensalidade::find($request->mensalidade_id);
            if (!$m || $m->status != 'Ativo') return redirect()->back()->with('error', 'Plano inválido!');
            $nomeCliente = $m->cliente_nome;
            $valorPagoNaHora = 0;
        } else {
            $nomeCliente = $request->cliente_nome_avulso;
            $valorPagoNaHora = $precoPadrao;
        }

        Servico::create([
            'barbeiro_id'    => $barbeiroId,
            'cliente_nome'   => $nomeCliente,
            'tipo_cliente'   => $tipoClienteId,
            'preco'          => $valorPagoNaHora, 
            'comissao_valor' => $comissaoCalculada,
            'is_weekend'     => $isFimDeSemana,
            'data_registo'   => $hoje,
            'status'         => 'Pendente' // Inicia como Pendente para poder ser zerado depois
        ]);

        LogAcao::create([
            'user_id'   => Auth::id(),
            'acao'      => 'Registo de Atendimento',
            'descricao' => "Cliente: {$nomeCliente} | Valor: {$valorPagoNaHora} Kz"
        ]);

        return redirect()->back()->with('success', 'Atendimento registado!');
    }
}