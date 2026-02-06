<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabela de Barbeiros (CU-09)
        Schema::create('barbeiros', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->decimal('salario_base', 10, 2)->default(0);
            $table->string('status')->default('Ativo');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Tabela de Clientes (CU-07)
        Schema::create('clientes', function ($table) {
            $table->id();
            $table->string('nome');
            $table->enum('tipo', ['Avulso', 'Mensalista'])->default('Avulso');
            $table->timestamps();
        });

        // Tabela de Mensalidades (CU-02, CU-08)
        Schema::create('mensalidades', function ($table) {
            $table->id();
            $table->foreignId('cliente_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('cliente_nome');
            $table->date('data_expiracao');
            $table->enum('status', ['Ativo', 'Vencido'])->default('Ativo');
            $table->timestamps();
        });

        // Tabela de Serviços (RF-01, RF-06)
        Schema::create('servicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barbeiro_id')->constrained('users');
            $table->string('cliente_nome');
            $table->integer('tipo_cliente')->default(1); // 1: Avulso, 2: Mensalista
            $table->decimal('preco', 10, 2);
            $table->decimal('comissao_valor', 10, 2);
            $table->boolean('is_weekend');
            $table->dateTime('data_registo'); 
            // CORREÇÃO AQUI: Mudado de enum para string para aceitar "Fechado" sem erros
            $table->string('status')->default('Pendente'); 
            $table->timestamps();
        });

        // Tabela de Logs (CU-06)
        Schema::create('logs_acoes', function ($table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users'); // Adicionei user_id para o log funcionar
            $table->string('acao');
            $table->text('descricao');
            $table->timestamps();
        });

        Schema::create('configuracoes', function (Blueprint $table) {
            $table->id();
            $table->string('chave')->unique();
            $table->decimal('valor', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configuracoes');
        Schema::dropIfExists('logs_acoes');
        Schema::dropIfExists('servicos');
        Schema::dropIfExists('mensalidades');
        Schema::dropIfExists('clientes');
        Schema::dropIfExists('barbeiros');
    }
};