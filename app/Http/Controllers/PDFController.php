<?php

namespace App\Http\Controllers;

use App\Models\Cars;
use App\Models\Venda;
use Barryvdh\DomPDF\Facade\PDF;
use Carbon\Carbon as Carbon;
use Illuminate\Http\Request;

class PDFController extends Controller
{
    public function generatePDFVendas() {

        // Carregar as vendas com os relacionamentos
        $vendas = Venda::with('cliente', 'itens.produto', 'parcelas', 'usuario')->get();

        // Verificar se houve vendas encontradas
        if ($vendas->isEmpty()) {
            return response()->json(['message' => 'Nenhuma venda encontrada.'], 404);
        }

        // Carregar a view e gerar o PDF
        $pdf = PDF::loadView("layouts.PDF.A4", compact('vendas'))->setPaper('a4', 'landscape');

        // Retornar o PDF para download
        return $pdf->download('vendas.pdf');
    }

}
