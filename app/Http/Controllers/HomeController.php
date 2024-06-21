<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Cliente;
use App\Models\Estacionamento;
use App\Models\Produto;
use App\Models\Venda;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];


        $data['TotalClientes'] = Cliente::count();

        $data['TotalVendas'] = Venda::count();

        $data['TotalProdutos'] = Produto::count();

        return view('home', compact('data'));
    }
}
