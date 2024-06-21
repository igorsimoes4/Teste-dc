<?php

namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\Cliente;
use App\Models\Produto;
use App\Models\ItemVenda;
use App\Models\Parcela;
use App\Models\Vendas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\Providers\Auth as ProvidersAuth;

class VendaController extends Controller
{
    public function index()
    {
        $vendas = Venda::with('cliente', 'itens.produto', 'parcelas', 'usuario')->paginate(3);

        return view('vendas', compact('vendas'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $produtos = Produto::all();
        return view('vendas_add', compact('clientes', 'produtos'));
    }

    public function store(Request $request)
    {

        $user = Auth::getUser();

        $venda = Venda::create([
            'cliente_id' => $request->input('cliente_id'),
            'forma_pagamento' => $request->input('forma_pagamento'),
            'user_id' => $user['id'],
        ]);

        foreach ($request->produtos as $produto) {
            ItemVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $produto['produto_id'],
                'quantidade' => $produto['quantidade'],
                'preco_unitario' => $produto['preco_unitario'],
                'preco_total' => $produto['preco_unitario'] * $produto['quantidade'],
            ]);
        }

        if ($request->forma_pagamento == 2) {
            $this->gerarParcelas($venda, $request->parcelas);
        }

        return redirect()->route('vendas.index')->with('create', 'Venda Criada com Sucesso');
    }

    public function edit($id)
    {
        $venda = Venda::with('cliente', 'itens.produto', 'parcelas', 'usuario')
            ->findOrFail($id);

        $clientes = Cliente::all();
        $produtos = Produto::all();

        return view('vendas_edit', compact('venda', 'clientes', 'produtos'));
    }

    public function show(Request $request, $id) {

        $validatedData = $request->validate([
            'cliente_id' => 'required|integer',
            'forma_pagamento' => 'required|integer',
            'produtos.*.produto_id' => 'required|integer',
            'produtos.*.quantidade' => 'required|integer',
            'produtos.*.preco_unitario' => 'required|numeric',
            'parcelas.*.data_vencimento' => 'nullable|date',
            'parcelas.*.valor' => 'nullable|numeric',
        ]);

        // Buscar a venda pelo ID
        $venda = Venda::findOrFail($id);

        // Atualizar os dados da venda
        $venda->update([
            'cliente_id' => $request->input('cliente_id'),
            'forma_pagamento' => $request->input('forma_pagamento'),
        ]);

        // Remover os itens de venda antigos
        ItemVenda::where('venda_id', $venda->id)->delete();

        // Adicionar os novos itens de venda
        foreach ($request->produtos as $produto) {
            ItemVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $produto['produto_id'],
                'quantidade' => $produto['quantidade'],
                'preco_unitario' => $produto['preco_unitario'],
                'preco_total' => $produto['preco_unitario'] * $produto['quantidade'],
            ]);
        }

        $parcelas = $request->parcelas;
        // Remover as parcelas antigas e adicionar novas somente se a forma de pagamento for a prazo
        if ($parcelas) {
            Parcela::where('venda_id', $venda->id)->delete();
            $this->gerarParcelas($venda, $parcelas);
        }

        // Redirecionar para a lista de vendas com mensagem de sucesso
        return redirect()->route('vendas.index')->with('success', 'Venda atualizada com sucesso!');
    }

    public function update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'cliente_id' => 'required|integer',
            'forma_pagamento' => 'required|integer',
            'produtos.*.produto_id' => 'required|integer',
            'produtos.*.quantidade' => 'required|integer',
            'produtos.*.preco_unitario' => 'required|numeric',
            'parcelas.*.data_vencimento' => 'nullable|date',
            'parcelas.*.valor' => 'nullable|numeric',
        ]);

        // Buscar a venda pelo ID
        $venda = Venda::findOrFail($id);

        // Atualizar os dados da venda
        $venda->update([
            'cliente_id' => $request->input('cliente_id'),
            'forma_pagamento' => $request->input('forma_pagamento'),
        ]);

        // Remover os itens de venda antigos
        ItemVenda::where('venda_id', $venda->id)->delete();

        // Adicionar os novos itens de venda
        foreach ($request->produtos as $produto) {
            ItemVenda::create([
                'venda_id' => $venda->id,
                'produto_id' => $produto['produto_id'],
                'quantidade' => $produto['quantidade'],
                'preco_unitario' => $produto['preco_unitario'],
                'preco_total' => $produto['preco_unitario'] * $produto['quantidade'],
            ]);
        }

        $parcelas = $request->parcelas;
        // Remover as parcelas antigas e adicionar novas somente se a forma de pagamento for a prazo
        if ($parcelas) {
            Parcela::where('venda_id', $venda->id)->delete();
            $this->gerarParcelas($venda, $parcelas);
        }

        // Redirecionar para a lista de vendas com mensagem de sucesso
        return redirect()->route('vendas.index')->with('create', 'Venda atualizada com sucesso!');
    }

    public function destroy(Venda $venda)
    {
        $venda->delete();
        return redirect()->route('vendas.index');
    }

    private function gerarParcelas(Venda $venda, $parcelas)
    {
        foreach ($parcelas as $parcela) {
            Parcela::create([
                'venda_id' => $venda->id,
                'data_vencimento' => $parcela['data_vencimento'],
                'valor' => $parcela['valor'],
            ]);
        }
    }
}
