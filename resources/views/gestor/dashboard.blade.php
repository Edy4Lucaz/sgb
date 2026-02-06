@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="mb-4">
            <h1 class="gold-text">Painel de Atendimento (Recepção)</h1>
            <p class="text-grey">Bem-vindo, {{ Auth::user()->name }}</p>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <h3>Registados no Caixa Hoje</h3>
                <p>{{ count($atendimentosHoje) }}</p>
            </div>
            <div class="stat-card">
                <h3>Receita em Dinheiro</h3>
                <p>{{ number_format($receitaHoje, 0, ',', '.') }} Kz</p>
            </div>
            <div class="stat-card">
                <h3>Mensalistas Ativos</h3>
                <p>{{ $totalMensalistasAtivos }}</p>
            </div>
        </section>

        <section class="card mt-4">
            <h2 class="gold-text">Operações de Balcão</h2>
            <p class="text-grey">Registo de clientes, serviços e controlo de pagamentos.</p>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalNovoAtendimentoGestor">
                    Registar Novo Atendimento (CU-01)
                </button>

                <a href="{{ route('mensalidades.index') }}" class="btn btn-gold">
                    Gerir/Renovar Mensalidades (CU-02)
                </a>

                <form action="{{ route('caixa.fechar') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger">Fechar Caixa</button>
                </form>
            </div>
        </section>
    </div>

    @include('gestor.modals.registo_atendimento')

@endsection