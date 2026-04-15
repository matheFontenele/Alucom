<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Protocolo de Entrega - {{ $config['nome'] }}</title>
    <style>
        @page {
            margin: 1cm;
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }

        /* Cabeçalho */
        .header {
            width: 100%;
            border-bottom: 3px solid {{ $config['cor'] }};
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .logo {
            /* Tamanho ideal para uma logo em PDF A4 */
            width: 180px; 
            height: auto;
        }

        .company-data {
            text-align: right;
            font-size: 10px;
            color: #333;
            vertical-align: middle;
        }

        .doc-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 16px;
            margin: 25px 0;
            color: #000;
        }

        /* Tabelas de Informação */
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

        .bg-gray {
            background-color: #f2f2f2;
            font-weight: bold;
            width: 120px;
        }

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
            line-height: 1.6;
        }

        /* Assinaturas */
        .signatures {
            margin-top: 80px;
            width: 100%;
        }

        .sig-box {
            width: 40%;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
            padding-top: 5px;
        }

        /* Rodapé Fixo */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #777;
            border-top: 1px solid #eee;
            padding-top: 5px;
            padding-bottom: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <table class="header-table">
            <tr>
                <td width="50%">
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logos/' . $config['slug'] . '.png'))) }}" class="logo">
                </td>
                <td class="company-data">
                    <strong style="font-size: 12px; color: #000;">{{ $config['razao_social'] }}</strong><br>
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
        Atesto que recebi e conferi os equipamentos acima citados. A responsabilidade por qualquer falta e/ou avaria será conforme as condições previstas no processo licitatório e no contrato firmado, exceto pelos desgastes naturais decorrentes do uso regular.
    </div>

    <table class="signatures">
        <tr>
            <td class="sig-box" style="border: none;"></td> <td width="20%" style="border: none;"></td> <td class="sig-box" style="border: none;"></td> </tr>
        <tr>
            <td class="sig-box">
                {{ strtoupper($config['nome']) }}
            </td>
            <td></td>
            <td class="sig-box">
                CLIENTE (RECEBEDOR)
            </td>
        </tr>
    </table>

    <div class="footer">
        {{ $config['razao_social'] }} - {{ $config['endereco_curto'] ?? $config['endereco'] }}<br>
        {{ $config['contatos_footer'] ?? $config['contato'] }}
    </div>

</body>

</html>