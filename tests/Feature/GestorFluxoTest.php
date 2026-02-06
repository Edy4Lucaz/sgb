<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Servico;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class GestorFluxoTest extends TestCase
{
    use RefreshDatabase; // Garante um banco limpo para cada teste

    /**
     * Teste de Integração: Verifica se o Gestor consegue gerar o PDF
     */
    public function test_gestor_pode_gerar_relatorio_pdf()
    {
        // 1. Criar Gestor (Perfil com G maiúsculo como na sua Migration)
        $gestor = User::factory()->create([
            'perfil' => 'Gestor',
            'role'   => 2
        ]);

        // 2. Criar Serviço com todos os campos obrigatórios (evitando NOT NULL constraint)
        Servico::create([
            'barbeiro_id'    => $gestor->id,
            'cliente_nome'   => 'Cliente Teste PDF',
            'preco'          => 5000,
            'comissao_valor' => 1000,
            'data_registo'   => Carbon::today(),
            'status'         => 'Pendente',
            'is_weekend'     => 0,
            'tipo_cliente'   => 'Normal'
        ]);

        // 3. Simular login e testar rota
        $response = $this->actingAs($gestor)->get(route('relatorio.pdf'));

        // 4. Validações
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Teste de Sistema: Verifica se o fecho de caixa funciona de ponta a ponta
     */
    public function test_fluxo_de_fechar_caixa_com_sucesso()
    {
        $gestor = User::factory()->create([
            'perfil' => 'Gestor',
            'role'   => 2
        ]);

        // Criar serviço que será fechado
        $servico = Servico::create([
            'barbeiro_id'    => $gestor->id,
            'cliente_nome'   => 'Cliente Fecho',
            'preco'          => 2000,
            'comissao_valor' => 500,
            'data_registo'   => Carbon::today(),
            'status'         => 'Pendente',
            'is_weekend'     => 0,
            'tipo_cliente'   => 'Normal'
        ]);

        // Simular o clique no botão de fechar caixa
        $response = $this->actingAs($gestor)->post(route('caixa.fechar'));

        // Verificar redirecionamento (302) e mensagem na sessão
        $response->assertStatus(302);
        $response->assertSessionHas('success');

        // Verificar se a alteração persistiu no banco de dados (Semântica)
        $this->assertDatabaseHas('servicos', [
            'id'     => $servico->id,
            'status' => 'Fechado'
        ]);
    }
}