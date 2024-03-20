<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendaRequest;
use App\Models\FormaPagamento;
use App\Models\Venda;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $vendas = Venda::with(['produtos', 'cliente', 'parcelas'])
            ->paginate(10)
            ->withQueryString();
        return Inertia::render('Venda/index', [
            'vendas' => $vendas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $forma_pagamentos = FormaPagamento::all();
        return Inertia::render('Venda/Create', [
            'forma_pagamento' => $forma_pagamentos,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVendaRequest $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(Venda $venda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venda $venda)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Venda $venda)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venda $venda)
    {
        //
    }
}
