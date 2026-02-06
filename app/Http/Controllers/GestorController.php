<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Servico;
use App\Models\Mensalidade;
use App\Models\User;
use App\Models\LogAcao;
use App\Mail\RelatorioDiarioMail; 
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail; 
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Exception;

class GestorController extends Controller
{
    /**
     * Dashboard Principal - Exibe estatísticas do dia
     */
    public function dashboard()
    {
        $hoje = Carbon::today();

        $atendimentosHoje = Servico::with('barbeiro')
                            ->whereDate('data_registo', $hoje)
                            ->where('status', '!=', 'Fechado')
                            ->get();

        $receitaHoje = $atendimentosHoje->sum('preco');
        
        $totalMensalistasAtivos = Mensalidade::where('status', 'Ativo')
            ->whereDate('data_expiracao', '>=', $hoje)
            ->count();

        $barbeiros = User::where('perfil', 'barbeiro')->get();
        $mensalistasAtivos = Mensalidade::where('status', 'Ativo')
            ->whereDate('data_expiracao', '>=', $hoje)
            ->get();

        return view('gestor.dashboard', compact(
            'atendimentosHoje',
            'receitaHoje',
            'totalMensalistasAtivos',
            'barbeiros',
            'mensalistasAtivos'
        ));
    }

    /**
     * Fechar Caixa: Finaliza serviços e envia relatório por e-mail automaticamente
     */
    public function fecharCaixa() 
    {
        $hoje = Carbon::today();
        $servicos = Servico::whereDate('data_registo', $hoje)
                    ->where('status', '!=', 'Fechado')
                    ->get();

        if ($servicos->isEmpty()) {
            return redirect()->back()->with('error', 'O caixa já está zerado ou já foi fechado!');
        }

        // 1. Atualiza o status dos serviços para fechado
        foreach ($servicos as $s) {
            $s->status = 'Fechado';
            $s->save();
        }

        // 2. Regista a ação nos Logs (Auditabilidade para a Defesa)
        LogAcao::create([
            'user_id' => Auth::id(),
            'acao' => 'Fecho de Caixa',
            'descricao' => 'Caixa encerrado e relatório enviado por e-mail.'
        ]);

        // 3. Tenta enviar o e-mail automaticamente
        try {
            $this->enviarRelatorioEmailSilent();
            $mensagem = 'Caixa fechado e relatório enviado com sucesso!';
            $tipo = 'success';
        } catch (Exception $e) {
            // Caso falhe a internet, o caixa fecha mas avisa do erro no envio
            $mensagem = 'Caixa fechado, mas o e-mail falhou (Verifique a conexão).';
            $tipo = 'warning';
        }

        return redirect()->back()->with($tipo, $mensagem);
    }

    /**
     * Enviar Relatório por Email (Versão interna para o fecho de caixa)
     */
    private function enviarRelatorioEmailSilent()
    {
        $data = $this->getRelatorioData();
        $pdf = Pdf::loadView('gestor.pdf.relatorio_diario', $data);
        $pdfContent = $pdf->output();

        Mail::to('lucasedson744@gmail.com')->send(new RelatorioDiarioMail($data, $pdfContent));
    }

    /**
     * Enviar Relatório por Email (Versão manual via botão)
     */
    public function enviarRelatorioEmail()
    {
        try {
            $this->enviarRelatorioEmailSilent();
            return redirect()->back()->with('success', 'Relatório enviado para o seu e-mail!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'Erro ao enviar e-mail: ' . $e->getMessage());
        }
    }

    /**
     * Auxiliar: Organiza dados do relatório (Conceito DRY)
     */
    private function getRelatorioData()
    {
        $hoje = Carbon::today();
        $atendimentos = Servico::with('barbeiro')->whereDate('data_registo', $hoje)->get();
        
        $total = $atendimentos->sum('preco');
        $comissao = $atendimentos->sum('comissao_valor');

        return [
            'titulo'       => 'Relatório de Atendimento - SGB ELITE',
            'data'         => $hoje->format('d/m/Y'),
            'atendimentos' => $atendimentos,
            'total'        => $total,
            'comissao'     => $comissao,
            'lucro'        => $total - $comissao
        ];
    }

    /**
     * Gerar Relatório PDF para Download
     */
    public function gerarRelatorioPDF()
    {
        $data = $this->getRelatorioData();
        $pdf = Pdf::loadView('gestor.pdf.relatorio_diario', $data);
        return $pdf->download('relatorio_'.Carbon::today()->format('d_m_Y').'.pdf');
    }

    /**
     * Gerar Relatório CSV para Excel
     */
    public function gerarRelatorioCSV()
    {
        $hoje = Carbon::today();
        $servicos = Servico::with('barbeiro')->whereDate('data_registo', $hoje)->get();

        return new StreamedResponse(function() use ($servicos) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Cliente', 'Barbeiro', 'Preco (Kz)', 'Comissao (Kz)', 'Data', 'Status']);

            foreach ($servicos as $s) {
                fputcsv($handle, [
                    $s->cliente_nome,
                    $s->barbeiro->name ?? 'N/A',
                    $s->preco,
                    $s->comissao_valor,
                    $s->data_registo,
                    $s->status
                ]);
            }
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="relatorio_'.Carbon::today()->format('d_m_Y').'.csv"',
        ]);
    }

    /**
     * Gestão de Mensalidades
     */
    public function mensalidades()
    {
        $mensalidades = Mensalidade::orderBy('data_expiracao', 'asc')->get();
        foreach ($mensalidades as $m) {
            $hoje = Carbon::today();
            $vencimento = Carbon::parse($m->data_expiracao);
            $diff = $hoje->diffInDays($vencimento, false);
            $m->dias_restantes = ($diff < 0) ? 0 : (int) min($diff, 30);
        }
        return view('gestor.mensalidades', compact('mensalidades'));
    }

    public function storeMensalidade(Request $request)
    {
        $request->validate(['cliente_nome' => 'required|string|max:255']);
        Mensalidade::create([
            'cliente_nome'   => $request->cliente_nome,
            'data_expiracao' => Carbon::now()->addDays(30),
            'status'         => 'Ativo',
            'data_pagamento' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Mensalista registado!');
    }

    public function updateMensalidade(Request $request, $id)
    {
        $m = Mensalidade::findOrFail($id);
        $request->validate(['cliente_nome' => 'required', 'status' => 'required']);
        $m->update($request->all());
        return redirect()->back()->with('success', 'Dados atualizados!');
    }

    public function renovarMensalidade($id)
    {
        $m = Mensalidade::findOrFail($id);
        $m->update([
            'data_expiracao' => Carbon::now()->addDays(30),
            'status' => 'Ativo',
            'data_pagamento' => Carbon::now()
        ]);
        return redirect()->back()->with('success', 'Mensalidade renovada!');
    }

    public function eliminarMensalidade($id)
    {
        Mensalidade::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Removido com sucesso!');
    }
}