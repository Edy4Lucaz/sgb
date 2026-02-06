<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Barbeiro;
use App\Models\Configuracao;
use App\Models\Mensalidade;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsuariosSeeder extends Seeder
{
    public function run(): void {
        
        // 1. Preço Padrão
        Configuracao::updateOrCreate(['chave' => 'preco_corte'], ['valor' => 500]);

        // 2. Admin
        $admin = User::create([
            'name' => 'Admin Elite',
            'email' => 'admin@elite.com',
            'password' => Hash::make('123456'),
            'role' => 1,
            'perfil' => 'Admin'
        ]);

        // 3. Gestor
        $gestor = User::create([
            'name' => 'Gestor SGB',
            'email' => 'gestor@elite.com',
            'password' => Hash::make('123456'),
            'role' => 2,
            'perfil' => 'Gestor'
        ]);

        // 4. Barbeiro
        $userBarbeiro = User::create([
            'name' => 'Barbeiro Kundy',
            'email' => 'kundy@elite.com',
            'password' => Hash::make('123456'),
            'role' => 3,
            'perfil' => 'Barbeiro'
        ]);

        Barbeiro::create([
            'user_id' => $userBarbeiro->id,
            'nome' => 'Kundy Nambele',
            'salario_base' => 50000,
            'status' => 'Ativo'
        ]);

        // --- 5. MENSALISTAS (DADOS DE TESTE) ---
        
        // Carlos: Sem conta de usuário (cliente_id nulo), apenas nome
        Mensalidade::create([
            'cliente_id' => null, 
            'cliente_nome' => 'Carlos Mensalista (Teste 5 dias)',
            'data_expiracao' => Carbon::today()->addDays(5),
            'status' => 'Ativo'
        ]);

        // João Silva: Vinculado à conta do Gestor (apenas para teste de vínculo)
        Mensalidade::create([
            'cliente_id' => $gestor->id,
            'cliente_nome' => 'João Silva (Plano Ativo)',
            'data_expiracao' => Carbon::today()->addDays(20),
            'status' => 'Ativo'
        ]);

        // Ricardo: Sem conta de usuário
        Mensalidade::create([
            'cliente_id' => null,
            'cliente_nome' => 'Ricardo Souza (Vencido)',
            'data_expiracao' => Carbon::today()->subDays(2),
            'status' => 'Vencido'
        ]);
    }
}