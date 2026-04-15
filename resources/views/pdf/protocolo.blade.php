<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Protocolo - {{ $config['nome'] }}</title>
    <style>
        @page {
            margin: 0; /* Necessário para o fundo preencher tudo */
        }
        body {
            font-family: 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            background-image: url("data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/logos/' . $config['slug'] . '.png'))) }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 100% 100%; /* Força preencher o A4 */
            width: 21cm;
            height: 29.7cm;
        }
        .content {
            padding: 3.2cm 1.2cm 1.5cm 1.2cm; /* Espaço para não cobrir o cabeçalho/rodapé do timbrado */
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table, th, td {
            border: 1px solid #000;
        }
        td {
            padding: 5px 8px;
            font-size: 10.5px;
            vertical-align: top;
        }
        .label {
            font-weight: bold;
            font-size: 9px;
            display: block;
            text-transform: uppercase;
        }
        .terms {
            font-size: 10px;
            font-weight: bold;
            text-align: justify;
            margin-top: 15px;
        }
        .signatures {
            margin-top: 25px;
            border: none;
        }
        .signatures td {
            border: none;
            padding: 10px 0;
        }
        .footer-info {
            position: absolute;
            bottom: 1.8cm;
            left: 1.2cm;
            font-size: 9px;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="content">
    <table>
        <tr>
            <td width="25%"><span class="label">Nº do Documento</span>{{ str_pad($movimentacao->id, 4, '0', STR_PAD_LEFT) }}</td>
            <td width="25%"><span class="label">Data</span>{{ $movimentacao->data_movimentacao->format('d/m/Y') }}</td>
            <td><span class="label">Tipo</span>{{ $movimentacao->tipo ?? 'Saída/Entrega' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td><span class="label">Cliente</span>{{ $movimentacao->destino }}</td>
        </tr>
        <tr>
            <td width="50%"><span class="label">Endereço</span>{{ $movimentacao->endereco_cliente ?? 'N/D' }}</td>
            <td><span class="label">Bairro</span>{{ $movimentacao->bairro_cliente ?? 'Centro' }}</td>
        </tr>
        <tr>
            <td width="50%"><span class="label">Cidade</span>{{ $movimentacao->cidade_cliente ?? 'Uruoca' }}</td>
            <td><span class="label">Estado</span>{{ $movimentacao->estado_cliente ?? 'Ceará' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="background-color: #eee;"><span class="label">Equipamento</span></td>
        </tr>
        @foreach($itens as $item)
        <tr>
            <td>{{ $item->equipamento->tombo }} - {{ $item->equipamento->nome }} {{ $item->equipamento->serial ? 'NS '.$item->equipamento->serial : '' }}</td>
        </tr>
        @endforeach
    </table>

    <table>
        <tr>
            <td>
                <span class="label">Tipos por equipamentos:</span>
                @php $contagem = []; @endphp
                @foreach($itens as $item)
                    @php $nome = $item->equipamento->nome; $contagem[$nome] = ($contagem[$nome] ?? 0) + 1; @endphp
                @endforeach
                @foreach($contagem as $nome => $qtd)
                    {{ strtoupper($nome) }}: {{ $qtd }} <br>
                @endforeach
                <strong>Total Geral: {{ count($itens) }}</strong>
            </td>
        </tr>
    </table>

    <div class="terms">
        Atesto que recebi e conferi os equipamentos acima citados. A responsabilidade por qualquer falta e/ou avaria será conforme as condições previstas no processo licitatório e no contrato firmado, exceto pelos desgastes naturais decorrentes do uso regular.
    </div>

    <table class="signatures">
        <tr>
            <td width="60%">
                <strong>Assinatura:</strong> ____________________________________________________
                <br><br>
                <strong>Nome Completo:</strong> ________________________________________________
                <br><br>
                <strong>CPF:</strong> __________________________________________________________
            </td>
            <td width="40%" style="text-align: right; vertical-align: bottom;">
                ____/____/_______
                <br>
                <span style="font-size: 15px;">|____|____|</span>
            </td>
        </tr>
    </table>

    <div style="margin-top: 20px; font-size: 10px; font-weight: bold;">
        Obs. Rubricar as outras páginas se houver.
    </div>

    <div class="footer-info">
        Emitido por: {{ auth()->user()->name ?? 'Sistema' }} <span style="margin-left: 100px;">Data: {{ now()->format('d/m/Y H:i') }}</span>
    </div>
</div>

</body>
</html>