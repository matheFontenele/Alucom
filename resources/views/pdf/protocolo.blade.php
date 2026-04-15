<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Protocolo de Entrega - {{ $config['nome'] }}</title>
    <style>
        /* Configuração da folha A4 */
        @page {
            margin: 0; /* Remove as margens para a imagem de fundo encostar nas bordas */
        }

        body {
            font-family: 'Helvetica', sans-serif;
            color: #333;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            /* Define a logo como imagem de fundo de toda a página */
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logos/' . $config['slug'] . '.png'))) }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: cover; /* Faz a imagem preencher toda a tela */
            width: 21cm;
            height: 29.7cm;
        }

        /* Container de conteúdo com margem interna para não bater na logo do fundo */
        .content-wrapper {
            padding: 3.5cm 1.5cm 2cm 1.5cm; /* Margem superior maior para descer o texto da logo de cima */
        }

        .doc-title {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 25px;
            color: #000;
        }

        /* Tabelas */
        .info-table, .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Fundo levemente branco para ler melhor sobre a marca d'água */
        }

        .info-table td, .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
            font-size: 11px;
        }

        .bg-gray {
            background-color: #f2f2f2;
            font-weight: bold;
            width: 120px;
        }

        .terms {
            font-size: 10px;
            margin-top: 20px;
            text-align: justify;
        }

        .signatures {
            margin-top: 50px;
            width: 100%;
        }

        .sig-box {
            width: 40%;
            border-top: 1px solid #000;
            text-align: center;
            font-size: 10px;
            padding-top: 5px;
        }
    </style>
</head>

<body>
    <div class="content-wrapper">
        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td width="50%"></td> <td style="text-align: right; font-size: 10px;">
                    <strong style="font-size: 12px;">{{ $config['razao_social'] }}</strong><br>
                    {{ $config['endereco'] }}<br>
                    {{ $config['contato'] }}
                </td>
            </tr>
        </table>

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
            Atesto que recebi e conferi os equipamentos acima citados. A responsabilidade por qualquer falta e/ou avaria será conforme as condições previstas no processo licitatório e no contrato firmado.
        </div>

        <table class="signatures">
            <tr>
                <td class="sig-box">{{ strtoupper($config['nome']) }}</td>
                <td width="20%"></td>
                <td class="sig-box">CLIENTE (RECEBEDOR)</td>
            </tr>
        </table>
    </div>
</body>

</html>