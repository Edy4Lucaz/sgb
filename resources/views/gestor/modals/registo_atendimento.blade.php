<div class="modal fade" id="modalNovoAtendimentoGestor" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-gold">
            <div class="modal-header border-bottom-gold">
                <h5 class="modal-title gold-text">Novo Atendimento (Gestor)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('servicos.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="gold-text small mb-2 d-block">BARBEIRO RESPONSÁVEL</label>
                        <select name="barbeiro_id" class="form-select bg-black text-white border-gold-subtle" required>
                            <option value="">Selecione o barbeiro...</option>
                            @foreach($barbeiros as $b)
                                <option value="{{ $b->id }}">{{ $b->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="gold-text small mb-2 d-block">TIPO DE CLIENTE</label>
                        <select name="tipo_cliente" id="tipo_cliente_gestor" class="form-select bg-black text-white border-gold-subtle" required onchange="toggleInputsGestor()">
                            <option value="1">Avulso (Pagamento Imediato)</option>
                            <option value="2">Mensalista (Validar Plano)</option>
                        </select>
                    </div>

                    <div id="campo_avulso">
                        <label class="gold-text small mb-2 d-block">NOME DO CLIENTE</label>
                        <input type="text" name="cliente_nome_avulso" class="form-control bg-black text-white border-gold-subtle" placeholder="Nome do cliente">
                    </div>

                    <div id="campo_mensalista" style="display: none;">
                        <label class="gold-text small mb-2 d-block">SELECIONAR MENSALISTA</label>
                        <select name="mensalista_id" class="form-select bg-black text-white border-gold-subtle">
                            <option value="">Escolha um mensalista ativo...</option>
                            @foreach($mensalistasAtivos as $m)
                                <option value="{{ $m->id }}">{{ $m->cliente_nome }} (Expira: {{ \Carbon\Carbon::parse($m->data_expiracao)->format('d/m') }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-gold w-100">CONFIRMAR REGISTO</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleInputsGestor() {
    const tipo = document.getElementById('tipo_cliente_gestor').value;
    document.getElementById('campo_avulso').style.display = tipo == '1' ? 'block' : 'none';
    document.getElementById('campo_mensalista').style.display = tipo == '2' ? 'block' : 'none';
}
</script>