<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function index()
    {
        $produtos = Produto::latest()->paginate(5);
        return view('produto', compact('produtos'));
    }

    public function create()
    {
        return view('produto_add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
        ]);

        Produto::create($request->all());

        return redirect()->route('produtos.index')->with('create', 'Produto criado com sucesso.');
    }

    public function show(Produto $produto)
    {
        return view('produto.show', compact('produto'));
    }

    public function edit(Produto $produto)
    {
        return view('produto_edit', compact('produto'));
    }

    public function update(Request $request, Produto $produto)
    {
        $request->validate([
            'nome' => 'required|string',
            'preco' => 'required|numeric',
        ]);

        $produto->update($request->all());

        return redirect()->route('produtos.index')->with('create', 'Produto atualizado com sucesso.');
    }

    public function destroy(Produto $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')->with('create', 'Produto deletado com sucesso.');
    }
}
