@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="gold-text">Gestão de Mensalidades (CU-02)</h2>
                <p class="text-grey">Controlo de planos e renovações</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalNovaMensalidade">
                    + Novo Plano Mensal
                </button>
                <a href="{{ Auth::user()->perfil === 'Admin' ? route('admin.dashboard') : route('gestor.dashboard') }}"
                    class="btn btn-outline">
                    Voltar ao Painel
                </a>
            </div>
        </div>

        <div class="card p-0 overflow-hidden bg-dark border-gold">
            <table class="table table-dark table-hover mb-0">
                <thead>
                    <tr class="gold-text">
                        <th>Cliente</th>
                        <th>Estado</th>
                        <th>Vencimento</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($mensalidades as $m)
                        @php 
                            $vencido = $m->dias_restantes <= 0;
                            $pertoVencer = !$vencido && $m->dias_restantes <= 5;
                        @endphp
                        <tr>
                            <td class="align-middle">{{ $m->cliente_nome }}</td>
                            <td class="align-middle">
                                <span class="badge {{ $vencido ? 'bg-danger' : ($pertoVencer ? 'bg-warning text-dark' : 'bg-success') }}">
                                    {{ $vencido ? 'VENCIDO' : ($pertoVencer ? 'A VENCER' : 'ATIVO') }}
                                </span>
                            </td>
                            <td class="align-middle {{ $vencido ? 'text-danger fw-bold' : ($pertoVencer ? 'text-warning fw-bold' : '') }}">
                                {{ \Carbon\Carbon::parse($m->data_expiracao)->format('d/m/Y') }}
                                <small class="d-block">
                                    @if($vencido)
                                        Expirado
                                    @else
                                        Faltam {{ $m->dias_restantes }} dias
                                    @endif
                                </small>
                            </td>
                            
<td class="text-center">
    <div class="d-flex gap-2 justify-content-center">
        <button class="btn btn-outline-info btn-sm" 
            onclick="abrirEdicao('{{ $m->id }}', '{{ $m->cliente_nome }}')">
            Editar
        </button>

        <form action="{{ route('mensalidades.renovar', $m->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-gold btn-sm">Renovar</button>
        </form>

        <form action="{{ route('mensalidades.eliminar', $m->id) }}" method="POST" 
              onsubmit="return confirm('Tem a certeza que deseja eliminar este mensalista?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger btn-sm">
                Eliminar
            </button>
        </form>
    </div>
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-grey">Nenhum mensalista encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="modalNovaMensalidade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark border-gold text-white">
                <div class="modal-header border-gold">
                    <h5 class="modal-title gold-text">Registar Nova Mensalidade</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('mensalidades.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome do Cliente</label>
                            <input type="text" name="cliente_nome" class="form-control bg-dark text-white border-gold" required>
                        </div>
                    </div>
                    <div class="modal-footer border-gold">
                        <button type="submit" class="btn btn-gold">Ativar Plano</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarMensalista" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark border-gold text-white">
                <div class="modal-header border-gold">
                    <h5 class="modal-title gold-text">Editar Mensalista</h5>
                </div>
                <form id="formEditar" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome do Cliente</label>
                            <input type="text" name="cliente_nome" id="edit_nome" class="form-control bg-dark text-white border-gold" required>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="pagou_mes" value="1" class="form-check-input" id="checkPagou">
                            <label class="form-check-label">Confirmar pagamento de 1 mês (+30 dias)</label>
                        </div>
                    </div>
                    <div class="modal-footer border-gold">
                        <button type="submit" class="btn btn-gold">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function abrirEdicao(id, nome) {
            // Ajustado para usar URL absoluta e evitar erro 404
            const baseUrl = "{{ url('/gestor/mensalidades/editar') }}";
            document.getElementById('formEditar').action = baseUrl + "/" + id;
            document.getElementById('edit_nome').value = nome;
            new bootstrap.Modal(document.getElementById('modalEditarMensalista')).show();
        }
    </script>
@endsection