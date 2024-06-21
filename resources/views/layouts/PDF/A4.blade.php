<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Documento PDF</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            margin: 20px;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        h1 {
            text-align: center;
        }
        ul {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Detalhes das Vendas</h1>
        <table>
            <thead>
                <tr>
                    <th>Vendedor</th>
                    <th>Cliente</th>
                    <th>Data da Venda</th>
                    <th>Produtos</th>
                    <th>Forma de Pagamento</th>
                    <th>Valor Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($vendas as $venda)
                    <tr>
                        <td>{{ $venda->usuario->name ?? 'Não Informado' }}</td>
                        <td>{{ $venda->cliente->nome ?? 'Não informado' }}</td>
                        <td>{{ $venda->data_venda }}</td>
                        <td>
                            <ul>
                                @foreach ($venda->itens as $item)
                                    <li>Produto: {{ $item->produto->nome }} Quantidade: {{ $item->quantidade }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            @if ($venda->forma_pagamento == 2)
                                Pagamento a prazo
                            @endif
                        </td>
                        <td>R$ {{ number_format($venda->itens->sum('preco_total'), 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
