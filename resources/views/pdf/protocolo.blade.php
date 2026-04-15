<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protocolo de Entrega - {{ $config['nome'] }}</title>
    <style>
        @page { margin: 0.5cm; }
        body { 
            font-family: 'Helvetica', sans-serif; 
            color: #333; 
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }

        /* Cabeçalho com Cor Dinâmica */
        .header {
            width: 100%;
            border-bottom: 3px solid {{ $config['cor'] }};
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .logo { height: 60px; }
        .company-data { text-align: right; font-size: 9px; color: #555; }

        .doc-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
            color: #000;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }
        .bg-gray { background-color: #f2f2f2; font-weight: bold; width: 120px; }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .items-table th {
            background-color: #f2f2f2;
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
            text-align: left;
        }
        .items-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }

        .terms {
            font-size: 10px;
            margin-top: 30px;
            text-align: justify;
        }
        .signatures {
            margin-top: 60px;
            width: 100%;
        }
        .sig-box {
            width: 45%;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
            padding-top: 5px;
            display: inline-block;
        }

        /* Rodapé com Cor Dinâmica */
        .footer {
            position: fixed;
            bottom: 30px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: {{ $config['cor'] }};
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table width="100%">
            <tr>
                <td>
                    {{-- O arquivo da logo deve seguir o nome da empresa em minusculo (ex: alucom.png) --}}
                    <img src="{{ public_path('images/logos/' . $config['slug'] . '.png') }}" class="logo">
                </td>
                <td class="company-data">
                    <strong>{{ $config['razao_social'] }}</strong><br>
                    {{ $config['endereco'] }}<br>
                    {{ $config['contato'] }}
                </td>
            </tr>
        </table>
    </div>

    <div class="doc-title">Protocolo de Entrega de Equipamentos</div>

    <table class="info-table">
        <tr>
            <td class="bg-gray">Nº Documento:</td>
            <td>{{ str_pad($movimentacao->requisicao_id ?? $movimentacao->id, 6, '0', STR_PAD_LEFT) }}</td>
            <td class="bg-gray">Data:</td>
            <td>{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="bg-gray">Cliente:</td>
            <td colspan="3">{{ $movimentacao->destino }}</td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th width="15%">Tombo</th>
                <th>Descrição do Equipamento</th>
                <th width="25%">Nº de Série / Modelo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($itens as $item)
            <tr>
                <td>{{ $item->equipamento->tombo }}</td>
                <td>{{ $item->equipamento->nome }}</td>
                <td>{{ $item->equipamento->serial ?? $item->equipamento->catalogo->nome ?? 'N/A' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="terms">
        Atesto que recebi e conferi os equipamentos acima citados. A responsabilidade por qualquer falta e/ou avaria será conforme as condições previstas no processo licitatório e no contrato firmado, exceto pelos desgastes naturais decorrentes do uso regular. [cite: 1]
    </div>

    <div class="signatures">
        <div class="sig-box" style="float: left;">
            {{ strtoupper($config['nome']) }}
        </div>
        <div class="sig-box" style="float: right;">
            CLIENTE (RECEBEDOR)
        </div>
    </div>

    <div class="footer">
        {{ $config['razao_social'] }} - {{ $config['endereco_curto'] }}<br>
        {{ $config['contatos_footer'] }}
    </div>

</body>
</html>