<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório Diário</title>
    <style>
        body { font-family: sans-serif; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; font-size: 12px; }
        th { background-color: #d4af37; color: white; }
        .header { text-align: center; margin-bottom: 30px; }
        .resumo { background: #f9f9f9; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>SGB ELITE</h1>
        <h3>Relatório de Atendimento</h3>
        <p>Data: {{ $data }}</p>
    </div>

    <div class="resumo">
        <h4>Resumo Financeiro</h4>
        <p><strong>Total Bruto:</strong> {{ number_format($total, 2) }} Kz</p>
        <p><strong>Comissões:</strong> {{ number_format($comissao, 2) }} Kz</p>
        <p><strong>Lucro Líquido:</strong> {{ number_format($lucro, 2) }} Kz</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Cliente</th>
                <th>Barbeiro</th>
                <th>Preço</th>
                <th>Comissão</th>
            </tr>
        </thead>
        <tbody>
            @foreach($atendimentos as $a)
            <tr>
                <td>{{ $a->cliente_nome ?? 'N/A' }}</td>
                <td>{{ $a->barbeiro->name ?? 'N/A' }}</td>
                <td>{{ number_format($a->preco, 2) }} Kz</td>
                <td>{{ number_format($a->comissao_valor, 2) }} Kz</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>