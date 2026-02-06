<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SGB-Elite | Meu Histórico</title>
    <link rel="stylesheet" href="{{ asset('css/estilo.css') }}">
    <style>
        /* Estilos do Modal */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.8); }
        .modal-content { background-color: #1a1a1a; margin: 10% auto; padding: 25px; width: 90%; max-width: 450px; border-radius: 10px; border: 1px solid var(--gold-primary); position: relative; color: white; }
        .close { position: absolute; right: 20px; top: 10px; font-size: 28px; cursor: pointer; color: var(--gold-primary); }
        .btn-detalhes { background: transparent; border: 1px solid var(--gold-primary); color: var(--gold-primary); padding: 5px 12px; cursor: pointer; border-radius: 4px; transition: 0.3s; }
        .btn-detalhes:hover { background: var(--gold-primary); color: #000; }
        .gold-text { color: var(--gold-primary); font-weight: bold; }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="logo"><img src="{{ asset('assets/logo.png') }}" alt="SGB-Elite"></div>
        <div class="nav-menu">
            <a href="{{ url('/barbeiro/dashboard') }}" class="btn btn-outline">⬅ Voltar ao Painel</a>
        </div>
    </header>

    <main class="content-area">
        <h1>Meu Histórico e Remuneração (CU-03)</h1>

        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
             <div class="card" style="flex: 1; text-align: center;">
                 <h3>Salário Base (Fixo)</h3>
                 <p class="gold-text" style="font-size: 1.5em;">{{ number_format($barbeiro->salario_base ?? 0, 2, ',', '.') }} Kz</p>
             </div>
             
             <div class="card" style="flex: 1; text-align: center;">
                 <h3>Bónus FDS (Mês Atual)</h3>
                 <p class="gold-text" style="font-size: 2em;">{{ number_format($bonusAcumulado, 2, ',', '.') }} Kz</p>
                 <small style="color: #888;">Somatório de comissões em fins de semana</small>
             </div>
        </div>

        <div class="card">
            <h2>Últimos Atendimentos</h2>
            <table style="width: 100%; text-align: left; border-collapse: collapse; margin-top: 20px;">
                <thead style="border-bottom: 2px solid var(--gold-primary);">
                    <tr>
                        <th style="padding: 12px; color: var(--gold-primary);">Data</th>
                        <th style="padding: 12px; color: var(--gold-primary);">Cliente</th>
                        <th style="padding: 12px; color: var(--gold-primary);">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servicos as $s)
                    <tr style="border-bottom: 1px solid #333;">
                        <td style="padding: 12px;">{{ \Carbon\Carbon::parse($s->data_registo)->format('d/m/Y') }}</td>
                        <td style="padding: 12px;">{{ $s->cliente_nome }}</td>
                        <td style="padding: 12px;">
                            <button class="btn-detalhes" 
                                onclick="abrirPopup('{{ $s->cliente_nome }}', '{{ $s->tipo_cliente == 2 ? 'Mensalista' : 'Avulso' }}', '{{ number_format($s->comissao_valor, 2, ',', '.') }} Kz', '{{ $s->is_weekend ? 'Sim (+40%)' : 'Não (30%)' }}', '{{ \Carbon\Carbon::parse($s->data_registo)->format('H:i') }}')">
                                👁 Detalhes
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

    <div id="modalDetalhes" class="modal">
        <div class="modal-content">
            <span class="close" onclick="fecharPopup()">&times;</span>
            <h2 class="gold-text">Detalhes do Serviço</h2>
            <hr style="border: 0; border-top: 1px solid #444; margin: 15px 0;">
            <p><strong>Cliente:</strong> <span id="p-cliente"></span></p>
            <p><strong>Tipo:</strong> <span id="p-tipo"></span></p>
            <p><strong>Hora:</strong> <span id="p-hora"></span></p>
            <p><strong>Gerou Bónus:</strong> <span id="p-bonus"></span></p>
            <p style="font-size: 1.3em; margin-top: 15px;"><strong>Sua Comissão:</strong> <span id="p-comissao" class="gold-text"></span></p>
        </div>
    </div>

    <script>
        function abrirPopup(cliente, tipo, comissao, bonus, hora) {
            document.getElementById('p-cliente').innerText = cliente;
            document.getElementById('p-tipo').innerText = tipo;
            document.getElementById('p-comissao').innerText = comissao;
            document.getElementById('p-bonus').innerText = bonus;
            document.getElementById('p-hora').innerText = hora;
            document.getElementById('modalDetalhes').style.display = "block";
        }

        function fecharPopup() {
            document.getElementById('modalDetalhes').style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == document.getElementById('modalDetalhes')) fecharPopup();
        }
    </script>
</body>
</html>