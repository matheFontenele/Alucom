<?php

namespace App\Http\Controllers;

use App\Models\Movimentacao;
use App\Models\Equipamento;
use App\Models\Clientes;
use App\Models\Estoque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class MovimentacaoController extends Controller
{
    /**
     * Lista o histórico de movimentações com paginação.
     */
    public function index()
    {
        $movimentacoes = Movimentacao::with(['equipamento', 'requisicao.cliente'])
            ->orderBy('data_movimentacao', 'desc')
            ->paginate(20);

        return view('movimentacoes.index', compact('movimentacoes'));
    }

    /**
     * Formulário para criação de movimentação manual.
     */
    public function create()
    {
        $equipamentos = Equipamento::orderBy('nome')->get();
        $clientes = Clientes::orderBy('nome')->get();
        $estoques = Estoque::orderBy('nome')->get();

        return view('movimentacoes.create', compact('equipamentos', 'clientes', 'estoques'));
    }

    /**
     * Registra uma nova movimentação e sincroniza o estado do equipamento.
     */
    public function store(Request $request)
    {
        $request->validate([
            'equipamento_id'    => 'required|exists:equipamentos,id',
            'tipo'              => 'required|string',
            'situacao'          => 'required|string',
            'origem'            => 'required|string',
            'destino'           => 'required|string',
            'data_movimentacao' => 'required|date',
            'requisicao_id'     => 'nullable|exists:requisicoes,id',
            'observacao'        => 'nullable|string',
        ]);

        return DB::transaction(function () use ($request) {
            $equipamento = Equipamento::findOrFail($request->equipamento_id);

            $equipamento->status = $request->tipo;
            $equipamento->situacao = $request->situacao;

            switch ($request->tipo) {
                case 'Liberado':
                case 'Manutenção':
                case 'Devolução':
                    if (str_contains($request->situacao, 'Estoque') || $request->situacao === 'Liberado') {
                        $equipamento->cliente_id = null;
                        $estoque = Estoque::where('nome', $request->destino)->first();
                        $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                    }
                    break;

                case 'Aluguel':
                case 'Reservado':
                case 'Substituição':
                    if ($request->situacao === 'No Cliente' || $request->situacao === 'Separado') {
                        $equipamento->estoque_id = null;
                        $cliente = Clientes::where('nome', $request->destino)->first();
                        $equipamento->cliente_id = $cliente->id ?? $equipamento->cliente_id;
                    }
                    break;
            }

            $equipamento->save();
            Movimentacao::create($request->all());

            return redirect()->route('movimentacoes.index')->with('success', 'Movimentação registrada com sucesso!');
        });
    }

    public function edit($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        return view('movimentacoes.edit', compact('movimentacao'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'situacao'   => 'required|string',
            'observacao' => 'nullable|string',
        ]);

        $movimentacao = Movimentacao::findOrFail($id);
        $equipamento = $movimentacao->equipamento;

        return DB::transaction(function () use ($request, $movimentacao, $equipamento) {
            $movimentacao->update($request->only(['situacao', 'observacao']));

            if ($equipamento) {
                $equipamento->situacao = $request->situacao;

                if (in_array($request->situacao, ['Em Estoque', 'Liberado'])) {
                    $equipamento->status = 'Liberado';
                    $equipamento->cliente_id = null;
                    $estoque = Estoque::where('nome', $movimentacao->destino)->first();
                    $equipamento->estoque_id = $estoque->id ?? $equipamento->estoque_id;
                }

                $equipamento->save();
            }

            return redirect()->route('movimentacoes.index')->with('success', 'Registro atualizado com sucesso!');
        });
    }

    public function destroy($id)
    {
        $movimentacao = Movimentacao::findOrFail($id);
        $movimentacao->delete();

        return redirect()->back()->with('success', 'Registro removido do histórico!');
    }

    /**
     * Gera o PDF do Protocolo com cores dinâmicas.
     */
    public function emitirProtocolo($id)
    {

        // Aumenta o limite de memoria temporariamente para 256MB ou 512MB apenas para gerar este PDF
        ini_set('memory_limit', '512M');
        // 1. Busca os dados
        $movimentacao = Movimentacao::with(['requisicao.cliente', 'equipamento.catalogo'])->findOrFail($id);

        // 2. Lógica de configuração de cores e dados da empresa (Define a variável $config)
        $empresaKey = strtolower($movimentacao->origem);

        $empresas = [
            'alucom' => [
                'nome' => 'Alucom',
                'slug' => 'alucom',
                'cor'  => '#D32F2F', // Vermelho
                'razao_social' => 'ALUCOM LTDA - CNPJ 01.628.251/0001-88',
                'endereco' => 'Rua Riachuelo, 40 - Papicu - CEP: 60.175-205',
                'endereco_curto' => 'Rua Riachuelo, 40 - Papicu - Fortaleza/CE',
                'contato' => 'Fortaleza - CE | (85) 3262-3191',
                'contatos_footer' => '(85) 98814-6081 | 0800 166 1000 | comercial@alucom.com.br'
            ],
            'moreia' => [
                'nome' => 'Moreia',
                'slug' => 'moreia',
                'cor'  => '#FF8C00', // Laranja
                'razao_social' => 'MOREIA TECNOLOGIA LTDA',
                'endereco' => 'Endereço da Moreia...',
                'endereco_curto' => 'Cidade/UF',
                'contato' => 'Telefone Moreia',
                'contatos_footer' => 'contato@moreia.com.br'
            ],
            'ip' => [
                'nome' => 'IP',
                'slug' => 'ip',
                'cor'  => '#0000FF', // Azul
                'razao_social' => 'IP SOLUÇÕES TECNOLÓGICAS',
                'endereco' => 'Endereço IP...',
                'endereco_curto' => 'Cidade/UF',
                'contato' => 'Telefone IP',
                'contatos_footer' => 'contato@ip.com.br'
            ],
            'zaploc' => [
                'nome' => 'ZapLoc',
                'slug' => 'zaploc',
                'cor'  => '#2E8B57', // Verde
                'razao_social' => 'ZAPLOC LOCAÇÕES E SERVIÇOS',
                'endereco' => 'Endereço ZapLoc...',
                'endereco_curto' => 'Cidade/UF',
                'contato' => 'Telefone ZapLoc',
                'contatos_footer' => 'contato@zaploc.com.br'
            ],
        ];

        // Se a origem não for uma das empresas acima, usa Alucom como padrão
        $config = $empresas['alucom'];
        foreach ($empresas as $key => $value) {
            if (str_contains($empresaKey, $key)) {
                $config = $value;
                break;
            }
        }

        // 3. Busca os itens
        if ($movimentacao->requisicao_id) {
            $itens = Movimentacao::with('equipamento.catalogo')
                ->where('requisicao_id', $movimentacao->requisicao_id)
                ->get();
        } else {
            $itens = collect([$movimentacao]);
        }

        // 4. Passa a variável $config para a view
        $pdf = Pdf::loadView('pdf.protocolo', compact('movimentacao', 'itens', 'config'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Protocolo_{$config['slug']}_MOV_{$movimentacao->id}.pdf");
    }
}
