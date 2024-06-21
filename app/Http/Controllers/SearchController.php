<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Venda;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $req) {
        // Validar os dados do formulário, se necessário
        $req->validate([
            'search' => 'required|string|max:255', // Validação básica para garantir que 'query' seja uma string não vazia
        ]);

        // Obter a consulta digitada pelo usuário
        $query = $req->input('search');

        // Realizar a lógica de pesquisa no modelo Venda pelo nome do cliente
        $vendas = Venda::whereHas('cliente', function ($queryBuilder) use ($query) {
            $queryBuilder->where('nome', 'LIKE', '%' . $query . '%');
        })->with('cliente', 'itens.produto', 'parcelas', 'usuario')->paginate(10); // Paginando os resultados, mostrando 10 por página

        // Retornar os resultados para a view ou fazer qualquer outra manipulação necessária
        return view('vendas', compact('vendas'));
    }
}
