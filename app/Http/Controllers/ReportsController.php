<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Venda;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ReportsController extends Controller
{
    public function __invoke()
    {

    }

    public function report(Request $request)
    {
        $vendas = Venda::with(['cliente', 'user', 'produtos', 'parcelas'])->get();
        $pdf = app('dompdf.wrapper');
        $pdf->getDomPDF()->set_option("enable_php", true);
        $pdf->loadView('reports.vendas', compact('vendas'));
        return $pdf->download('vendas.pdf');
    }

}
