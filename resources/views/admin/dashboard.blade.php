@extends('layouts.app')

@section('content')
    <main class="content-area">
        <div class="mb-4">
            <h1 class="gold-text">Painel Administrativo (Visão Global)</h1>
            <p class="text-grey">Olá, {{ Auth::user()->name }} ({{ Auth::user()->perfil }})</p>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <h3>Receita Total Hoje</h3>
                <p>{{ number_format($receitaHoje, 0, ',', '.') }} Kz</p>
            </div>
            <div class="stat-card">
                <h3>Total Atendimentos</h3>
                <p>{{ $totalAtendimentos }}</p>
            </div>
            <div class="stat-card">
                <h3>Comissões a Pagar</h3>
                <p>{{ number_format($comissoesPagar, 0, ',', '.') }} Kz</p>
            </div>
            <div class="stat-card">
                <h3>Mensalidades Vencidas</h3>
                <p style="color: #ff4d4d;">{{ $mensalidadesVencidas }}</p>
            </div>
        </section>

        <section class="card mt-4">
            <h2 class="gold-text">Gestão Estratégica & Operacional</h2>
            <p class="text-grey">Acesso total a todas as funcionalidades do sistema.</p>

            <div class="d-flex gap-3 mt-4 flex-wrap">
                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalNovoAtendimentoGestor">
                    Registar Novo Atendimento
                </button>

                <div class="dropdown">
                    <button class="btn btn-gold dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Relatórios Financeiros
                    </button>
                    <ul class="dropdown-menu bg-dark border-gold">
                        <li><a href="{{ route('relatorio.pdf') }}" class="btn btn-primary">Baixar PDF</a></li>
                        <li><a class="dropdown-item text-white" href="{{ route('relatorio.csv') }}">Exportar em CSV</a></li>
                    </ul>
                </div>

                <button class="btn btn-gold" data-bs-toggle="modal" data-bs-target="#modalGerirSalarios">
                    Definir Salário Base (RF-11)
                </button>

                <a href="{{ route('mensalidades.index') }}" class="btn btn-outline">Gerir Mensalidades</a>

                <button class="btn btn-outline" data-bs-toggle="modal" data-bs-target="#modalGerirUtilizadores">
                    Gerir Utilizadores
                </button>
            </div>
        </section>
    </main>

    <div class="modal fade" id="modalGerirUtilizadores" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content bg-dark border-gold">
                <div class="modal-header border-gold">
                    <h5 class="modal-title gold-text">Gestão de Staff</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('utilizadores.store') }}" method="POST" class="mb-4">
                        @csrf
                        <div class="row g-2">
                            <div class="col-md-3">
                                <input type="text" name="name" class="form-control" placeholder="Nome" required>
                            </div>
                            <div class="col-md-3">
                                <input type="email" name="email" class="form-control" placeholder="E-mail" required>
                            </div>
                            <div class="col-md-2">
                                <input type="password" name="password" class="form-control" placeholder="Senha" required>
                            </div>
                            <div class="col-md-2">
                                <select name="perfil" class="form-control bg-dark text-white border-gold" required>
                                    <option value="Barbeiro">Barbeiro</option>
                                    <option value="Gestor">Gestor</option>
                                    <option value="Admin">Admin</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-gold w-100">Criar</button>
                            </div>
                        </div>
                    </form>

                    <h6 class="gold-text mb-3">Utilizadores Registados</h6>
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>Perfil</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($barbeiros as $b)
                                <tr>
                                    <td>{{ $b->name }}</td>
                                    <td><span class="badge border border-gold gold-text">{{ $b->perfil }}</span></td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-sm btn-outline"
                                                onclick="abrirModalEdicao('{{ $b->id }}', '{{ $b->name }}', '{{ $b->email }}', '{{ $b->perfil }}')">
                                                Editar
                                            </button>

                                            <form action="{{ route('utilizadores.destroy', $b->id) }}" method="POST"
                                                onsubmit="return confirm('Apagar?')">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Remover</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalGerirSalarios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark border-gold text-white">
                <div class="modal-header border-gold">
                    <h5 class="modal-title gold-text">Definir Salário Base</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.salvarSalario') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Selecionar Funcionário</label>
                            <select name="user_id" class="form-control bg-dark text-white border-gold" required>
                                @foreach($barbeiros as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->perfil }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Valor Mensal (Kz)</label>
                            <input type="number" name="salario_base" class="form-control bg-dark text-white border-gold"
                                placeholder="Ex: 50000" required>
                        </div>
                    </div>
                    <div class="modal-footer border-gold">
                        <button type="submit" class="btn btn-gold">Guardar Salário</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarUtilizador" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-dark border-gold text-white">
                <div class="modal-header border-gold">
                    <h5 class="modal-title gold-text">Editar Utilizador</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="formEditarUtilizador" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nome</label>
                            <input type="text" name="name" id="edit_name"
                                class="form-control bg-dark text-white border-gold" required>
                        </div>
                        <div class="mb-3">
                            <label>E-mail</label>
                            <input type="email" name="email" id="edit_email"
                                class="form-control bg-dark text-white border-gold" required>
                        </div>
                        <div class="mb-3">
                            <label>Perfil</label>
                            <select name="perfil" id="edit_perfil" class="form-control bg-dark text-white border-gold">
                                <option value="Barbeiro">Barbeiro</option>
                                <option value="Gestor">Gestor</option>
                                <option value="Admin">Admin</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Nova Senha (deixe em branco para manter a atual)</label>
                            <input type="password" name="password" class="form-control bg-dark text-white border-gold">
                        </div>
                    </div>
                    <div class="modal-footer border-gold">
                        <button type="submit" class="btn btn-gold">Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function abrirModalEdicao(id, nome, email, perfil) {
            // CORREÇÃO: Usa o helper url() do Laravel para evitar erro 404 em subpastas
            const baseUrl = "{{ url('/admin/utilizador/editar') }}";
            document.getElementById('formEditarUtilizador').action = baseUrl + "/" + id;

            // Preenche os campos
            document.getElementById('edit_name').value = nome;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_perfil').value = perfil;

            // Abre o modal
            var myModal = new bootstrap.Modal(document.getElementById('modalEditarUtilizador'));
            myModal.show();
        }
    </script>

    @include('gestor.modals.registo_atendimento')

@endsection