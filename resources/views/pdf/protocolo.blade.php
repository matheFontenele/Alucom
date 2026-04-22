<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protocolo de Entrega</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            /* Marca d'água ao fundo */
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logos/' . $config['slug'] . '.png'))) }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%;
            width: 21cm;
            height: 29.7cm;
        }
        .content {
            padding: 3.5cm 1.2cm 1.5cm 1.2cm; /* Espaço para o cabeçalho do timbrado */
        }
        
        /* Estilo das tabelas tipo "Grid" da imagem */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px 8px;
            text-align: left;
            font-size: 11px;
        }
        .label {
            font-weight: bold;
            display: block;
            font-size: 10px;
            text-transform: uppercase;
        }
        .bg-gray {
            background-color: #f2f2f2;
        }

        .texto-atesto {
            font-size: 10px;
            margin: 20px 0;
            text-align: justify;
            font-weight: bold;
        }

        .assinatura-container {
            margin-top: 40px;
            font-size: 11px;
        }
        .linha-assinatura {
            border-bottom: 1px solid #000;
            width: 300px;
            display: inline-block;
            margin-bottom: 10px;
        }
        .rodape-info {
            position: absolute;
            bottom: 1.5cm;
            left: 1.2cm;
            right: 1.2cm;
            font-size: 10px;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>
    <div class="content">
        <table>
            <tr>
                <td width="30%" class="bg-gray">
                    <span class="label">Nº do Documento</span>
                    {{ str_pad($movimentacao->id, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td width="30%" class="bg-gray">
                    <span class="label">Data</span>
                    {{ \Carbon\Carbon::parse($movimentacao->data_movimentacao)->format('d/m/Y') }}
                </td>
                <td width="40%" class="bg-gray">
                    <span class="label">Tipo</span>
                    {{ $movimentacao->tipo }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td class="bg-gray">
                    <span class="label">Cliente</span>
                    {{ $movimentacao->requisicao->cliente->nome ?? 'N/D' }}
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td width="60%">
                    <span class="label">Endereço</span>
                    {{ $movimentacao->requisicao->cliente->endereco ?? 'N/D' }}
                </td>
                <td width="40%">
                    <span class="label">Bairro</span>
                    {{ $movimentacao->requisicao->cliente->bairro ?? 'N/D' }}
                </td>
            </tr>
            <tr>
                <td width="60%">
                    <span class="label">Cidade</span>
                    {{ $movimentacao->requisicao->cliente->cidade ?? 'N/D' }}
                </td>
                <td width="40%">
                    <span class="label">Estado</span>
                    {{ $movimentacao->requisicao->cliente->estado ?? 'N/D' }}
                </td>
            </tr>
        </table>

        <div style="margin-top: 20px;">
            <table class="bg-gray">
                <tr>
                    <td><span class="label">Equipamento</span></td>
                </tr>
            </table>
            <table>
                @foreach($itens as $item)
                <tr>
                    <td>
                        {{ $item->equipamento->patrimonio ?? 'S/P' }} - 
                        {{ $item->equipamento->catalogo->nome ?? 'N/D' }} 
                        NS {{ $item->equipamento->num_serie ?? 'N/D' }}
                    </td>
                </tr>
                @endforeach
            </table>
        </div>

        <table>
            <tr class="bg-gray">
                <td><span class="label">Tipos por equipamentos:</span></td>
            </tr>
            <tr>
                <td>
                    @php
                        $agrupado = $itens->groupBy('equipamento.catalogo.nome');
                    @endphp
                    @foreach($agrupado as $nome => $grupo)
                        {{ strtoupper($nome) }}: {{ $grupo->count() }}<br>
                    @endforeach
                    <strong>Total Geral: {{ $itens->count() }}</strong>
                </td>
            </tr>
        </table>

        <div class="texto-atesto">
            Atesto que recebi e conferi os equipamentos acima citados. A responsabilidade por qualquer falta e/ou avaria será conforme as condições previstas no processo licitatório e no contrato firmado, exceto pelos desgastes naturais decorrentes do uso regular.
        </div>

        <div class="assinatura-container">
            <p>Assinatura: <span class="linha-assinatura"></span></p>
            <p>Nome Completo: <span class="linha-assinatura"></span></p>
            <p>CPF: <span class="linha-assinatura"></span></p>
        </div>

        <p style="font-size: 11px; font-weight: bold; margin-top: 30px;">
            Obs. Rubricar as outras páginas se houver.
        </p>

        <div class="rodape-info">
            <div style="float: left;">Emitido por: {{ auth()->user()->name ?? 'Sistema' }}</div>
            <div style="float: right;">Data: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</body>
</html>