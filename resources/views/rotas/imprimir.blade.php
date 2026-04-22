<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Manifesto RT-{{ str_pad($rota->id, 5, '0', STR_PAD_LEFT) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            @page {
                margin: 1cm;
            }

            body {
                background: white !important;
            }
        }

        .print-bg-yellow {
            background-color: #FFFF00 !important;
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>

<body onload="window.print()"> {{-- Dispara a impressão ao carregar --}}
    <div class="w-full text-black bg-white p-4" style="font-family: Arial, sans-serif;">
        <table class="w-full border-collapse border border-black text-[13px] leading-tight">
            <tbody>
                <div class="hidden print:block w-full text-black bg-white" style="font-family: Arial, sans-serif;">
                    <table class="w-full border-collapse border border-black text-[13px] leading-tight">
                        <tbody>
                            {{-- Cabeçalho ROMANEIO --}}
                            <tr>
                                <td colspan="3" class="text-center font-bold text-base py-1 border border-black print-bg-yellow" style="background-color: #FFFF00 !important;">ROMANEIO</td>
                            </tr>

                            @php
                            $primeiraReq = $rota->requisicoes->first();
                            $primeiroCliente = $primeiraReq->cliente ?? null;
                            @endphp
                            <tr>
                                <td class="font-bold uppercase border border-black px-1 w-[120px]">CLIENTE:</td>
                                <td colspan="2" class="border border-black px-1">{{ $primeiroCliente ? ($primeiroCliente->nome_fantasia ?? $primeiroCliente->nome) : 'DIVERSOS' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">ENDEREÇO:</td>
                                <td colspan="2" class="border border-black px-1">{{ $rota->cidade_destino }} - {{ $rota->estado_destino }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">CNPJ:</td>
                                <td colspan="2" class="border border-black px-1">{{ $primeiroCliente->cnpj ?? '' }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">TIPO:</td>
                                <td colspan="2" class="border border-black px-1 font-bold" style="color: #CC0000;">
                                    <span style="background-color: #FFCCCC !important; padding: 0 4px; border-radius: 2px;">ROTA</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">EMPRESA ENTRI:</td>
                                <td colspan="2" class="border border-black px-1"></td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">EMPRESA:</td>
                                <td colspan="2" class="border border-black px-1 font-bold text-white" style="background-color: #990000 !important;">
                                    Alucom
                                </td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">SAIDA :</td>
                                <td colspan="2" class="border border-black px-1 text-center font-bold">ALUCOM FORTALEZA</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">MOTORISTA:</td>
                                <td colspan="2" class="border border-black px-1">{{ $rota->motorista->name }}</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1">VEICULO:</td>
                                <td colspan="2" class="border border-black px-1">{{ $rota->veiculo->modelo }} - {{ strtoupper($rota->veiculo->placa) }}</td>
                            </tr>

                            <tr>
                                <td colspan="3" class="text-center font-bold py-1 border border-black print-bg-yellow" style="background-color: #FFFF00 !important;">EQUIPAMENTOS</td>
                            </tr>
                            <tr>
                                <td class="font-bold uppercase border border-black px-1 text-center w-[80px]">QUANT.:</td>
                                <td class="font-bold uppercase border border-black px-1 w-[100px]">PESO:</td>
                                <td class="font-bold uppercase border border-black px-1">TIPO:</td>
                            </tr>

                            {{-- Loop de Requisições na Impressão --}}
                            @foreach($rota->requisicoes as $req)
                            <tr>
                                <td class="text-center border border-black px-1">{{ $req->quantidade ?? 1 }}</td>
                                <td class="border border-black px-1"></td>
                                <td class="border border-black px-1 uppercase">
                                    {{-- Ajustado de modelo para nome --}}
                                    {{ $req->catalogo->nome ?? 'EQUIPAMENTO NÃO ESPECIFICADO' }}
                                    {{ $req->patrimonio_novo ? '- PAT: ' . $req->patrimonio_novo : '' }}
                                </td>
                            </tr>
                            @endforeach

                            {{-- Linhas em branco extra --}}
                            @php $linhasExtras = 12 - $rota->requisicoes->count(); @endphp
                            @for($i = 0; $i < ($linhasExtras> 0 ? $linhasExtras : 5); $i++)
                                <tr>
                                    <td class="border border-black px-1 py-[9px]"></td>
                                    <td class="border border-black px-1"></td>
                                    <td class="border border-black px-1"></td>
                                </tr>
                                @endfor

                                <tr>
                                    <td colspan="3" class="border border-black px-1 text-xs">
                                        No caso de transportadora informar a quantidade de volume
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border border-black px-1">Volume =</td>
                                    <td class="border border-black px-1 font-bold">{{ $rota->requisicoes->count() }}</td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="border border-black px-1">Peso =</td>
                                    <td class="border border-black px-1"></td>
                                </tr>
                        </tbody>
                    </table>
                </div>
            </tbody>
        </table>
    </div>
</body>

</html>