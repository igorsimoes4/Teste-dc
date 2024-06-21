<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::latest()->paginate(5);
        return view('cliente', compact('clientes'));
    }

    public function create()
    {
        return view('cliente_add');
    }

    public function store(Request $request)
    {
        $request->validate([
            'cpf' => 'required|string|unique:clientes,cpf',
            'nome' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'nullable|string',
        ]);

        Cliente::create($request->all());

        return redirect()->route('clientes.index')->with('create', 'Cliente criado com sucesso.');
    }

    public function show(Cliente $cliente)
    {
        return view('cliente_edit', compact('cliente'));
    }

    public function edit(Cliente $cliente)
    {
        return view('cliente_edit', compact('cliente'));
    }

    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            'cpf' => 'required|string|unique:clientes,cpf,' . $cliente->id,
            'nome' => 'required|string',
            'email' => 'required|email',
            'telefone' => 'nullable|string',
        ]);

        $cliente->update($request->all());

        return redirect()->route('clientes.index')->with('create', 'Cliente atualizado com sucesso.');
    }

    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        return redirect()->route('clientes.index')->with('create', 'Cliente deletado com sucesso.');
    }
}
