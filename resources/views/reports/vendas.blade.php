<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Vendas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
<h2>Relatório de Vendas</h2>
<table>
    <thead>
    <tr>
        <th>Venda ID</th>
        <th>Cliente</th>
        <th>Produtos</th>
        <th>Parcelas</th>
        <th>Total</th>
        <th>Data</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($vendas as $venda)
        <tr>
            <td>{{ $venda->id }}</td>
            <td>{{ $venda->cliente->nome }}</td>
            <td>
                {{ count($venda->produtos)  }}
            </td>
            <td>
                {{ count($venda->parcelas)  }}
            </td>
            <td>R$ {{ $venda->total }}</td>
            <td>{{ $venda->created_at->format('d/m/Y') }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
