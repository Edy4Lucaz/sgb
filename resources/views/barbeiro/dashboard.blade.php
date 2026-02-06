@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success bg-dark text-success border-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger bg-dark text-danger border-danger">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-4">
            <h2 class="gold-text">Painel do Barbeiro: {{ Auth::user()->name }}</h2>
            <p class="text-grey">SGB-Elite | Controlo de Produção</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>MINHA COMISSÃO (ESTIMADA)</h3>
                <p>{{ number_format($comissaoTotal ?? 0, 0, ',', '.') }} Kz</p>
                <span class="small text-grey">
                    @if($isFimDeSemana) 
                        Extra: 40% (Fim de Semana) 
                    @else 
                        Salário Fixo (Dia Útil) 
                    @endif
                </span>
            </div>

            <div class="stat-card">
                <h3>SERVIÇOS REALIZADOS HOJE</h3>
                <p>{{ count($atendimentos ?? []) }}</p>
            </div>

            <div class="stat-card">
                <h3>STATUS DO TURNO</h3>
                <p style="font-size: 1.5rem;" class="text-success">ATIVO</p>
            </div>
        </div>

        <div class="card mt-4">
            <h3 class="gold-text mb-3">Gestão de Atendimento</h3>
            <p class="text-grey">Utilize os botões abaixo para gerir os seus serviços.</p>
            <div class="d-flex gap-3">
                <button type="button" class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalRegistoServico">
                    REGISTAR NOVO ATENDIMENTO
                </button>

                <button type="button" class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#modalHistoricoRapido">
                    VER MEU HISTÓRICO
                </button>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalRegistoServico" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark border-gold">
                <div class="modal-header border-bottom-gold">
                    <h5 class="modal-title gold-text">Novo Atendimento (CU-01)</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ route('servicos.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-4">
                        
                        <div class="mb-4">
                            <label class="gold-text small mb-2 d-block">TIPO DE CLIENTE</label>
                            <select name="tipo_cliente" id="tipo_cliente" class="form-select bg-black text-white border-gold-subtle" required onchange="toggleCamposCliente()">
                                <option value="1">Avulso (Pagamento Direto)</option>
                                <option value="2">Mensalista (Verificar Plano)</option>
                            </select>
                        </div>

                        <div class="mb-3" id="div_cliente_avulso">
                            <label class="gold-text small mb-2 d-block">NOME DO CLIENTE</label>
                            <input type="text" name="cliente_nome_avulso" id="input_avulso"
                                class="form-control bg-black text-white border-gold-subtle" 
                                placeholder="Escreva o nome do cliente" style="background: #000 !important; color: #fff !important;">
                        </div>

                        <div class="mb-3 d-none" id="div_cliente_mensalista">
                            <label class="gold-text small mb-2 d-block">SELECIONAR MENSALISTA</label>
                            <select name="mensalidade_id" id="select_mensalista" class="form-select bg-black text-white border-gold-subtle" style="background: #000 !important;">
                                <option value="">Escolha um mensalista...</option>
                                @foreach($mensalistasAtivos as $m)
                                    <option value="{{ $m->id }}">{{ $m->cliente_nome }}</option>
                                @endforeach
                            </select>
                        </div>

                        <input type="hidden" name="barbeiro_id" value="{{ Auth::user()->id }}">

                        <div class="p-3 border-gold-dashed rounded text-center" style="background: rgba(255, 215, 0, 0.05);">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-grey small">VALOR:</span>
                                <span class="text-white fw-bold">500 Kz</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-grey small">COMISSÃO:</span>
                                <span class="gold-text fw-bold">
                                    {{ $isFimDeSemana ? '200 Kz (40%)' : '0 Kz (Salário)' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer border-0 p-3">
                        <button type="button" class="btn btn-outline-light btn-sm" data-bs-dismiss="modal">CANCELAR</button>
                        <button type="submit" class="btn btn-gold px-4">CONFIRMAR REGISTO</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalHistoricoRapido" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content bg-dark border-gold">
                <div class="modal-header border-bottom-gold">
                    <h5 class="modal-title gold-text">Meus Atendimentos de Hoje</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-dark table-hover mb-0">
                        <thead class="gold-text">
                            <tr>
                                <th class="ps-3">Cliente</th>
                                <th>Tipo</th>
                                <th>Valor</th>
                                <th>Comissão</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($atendimentos ?? [] as $a)
                                <tr>
                                    <td class="ps-3">{{ $a->cliente_nome }}</td>
                                    <td>{{ $a->tipo_cliente }}</td>
                                    <td>{{ number_format($a->preco ?? 0, 0, ',', '.') }} Kz</td>
                                    <td class="gold-text">{{ number_format($a->comissao_valor ?? 0, 0, ',', '.') }} Kz</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-grey p-4">Nenhum registo hoje.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-outline btn-sm text-white" data-bs-dismiss="modal">FECHAR</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleCamposCliente() {
            const tipo = document.getElementById('tipo_cliente').value;
            const divAvulso = document.getElementById('div_cliente_avulso');
            const divMensalista = document.getElementById('div_cliente_mensalista');
            const inputAvulso = document.getElementById('input_avulso');
            const selectMensalista = document.getElementById('select_mensalista');

            if (tipo == "1") { // Avulso
                divAvulso.classList.remove('d-none');
                divMensalista.classList.add('d-none');
                inputAvulso.required = true;
                selectMensalista.required = false;
            } else { // Mensalista
                divAvulso.classList.add('d-none');
                divMensalista.classList.remove('d-none');
                inputAvulso.required = false;
                selectMensalista.required = true;
            }
        }

        // Garante que o estado inicial está correto ao abrir
        document.addEventListener('DOMContentLoaded', toggleCamposCliente);
    </script>

    <style>
        .border-gold { border: 1px solid #FFD700 !important; }
        .border-gold-subtle { border: 1px solid rgba(255, 215, 0, 0.3) !important; }
        .border-bottom-gold { border-bottom: 1px solid #B8860B !important; }
        .border-gold-dashed { border: 1px dashed #B8860B; }
        .bg-black { background-color: #000 !important; }
        .gold-text { color: #FFD700 !important; }
        .text-grey { color: #888; }
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; }
        .stat-card { background: #1a1a1a; padding: 20px; border: 1px solid #333; border-radius: 8px; }
        .stat-card h3 { font-size: 0.8rem; color: #FFD700; margin-bottom: 10px; }
        .stat-card p { font-size: 1.8rem; font-weight: bold; margin: 0; }
    </style>
@endsection