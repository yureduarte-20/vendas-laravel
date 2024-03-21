<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVendaRequest;
use App\Http\Requests\UpdateVendaRequest;
use App\Models\Cliente;
use App\Models\FormaPagamento;
use App\Models\Parcela;
use App\Models\Produto;
use App\Models\ProdutoVenda;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $searchString = \Illuminate\Support\Facades\Request::query('search');
        $vendas = Venda::with(['produtos',
            'cliente',
            'parcelas',
            'user'])
            ->when($searchString, function (Builder $query) use ($searchString) {
                $query->whereHas('cliente', fn($q)=> $q->where('nome', 'LIKE', $searchString.'%'))
                    ->orWhereHas('user', fn($q)=> $q->where('name', 'LIKE', $searchString.'%'));
            })
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
        $validated = $request->validated();

        DB::beginTransaction();

        try {

            $cliente = Cliente::findOrFail($validated['cliente']);
            $itens = array_map(fn($produto) => $produto['item'], $validated['produtos']);
            $quantidade = array_map(fn($produto) => $produto['qtde'], $validated['produtos']);
            $valor_unitario = array_map(fn($produto) => $produto['valor'], $validated['produtos']);
            $produtos = Produto::whereIn('id', $itens)->get();

            $parcelas = $validated['parcelas'];
            $valores = array_column($validated['parcelas'], 'valor');

            $sum = array_sum($valores);
            if ($sum != $validated['total']) {
                return back()
                    ->withInput()
                    ->withErrors(['total' => 'As  parcelas não coincidem com o valor da venda']);
            }

            $venda = Venda::create([
                'total' => $validated['total'],
                'cliente_id' => $cliente->id,
                'user_id' => $request->user()->id
            ]);
            foreach ($produtos as $key => $produto) {
                $venda->produtos()->attach($produto, [
                    'valor_unitario' => $valor_unitario[$key],
                    'quantidade' => $quantidade[$key]
                ]);
            }
            foreach ($parcelas as $parcela) {
                $venda->parcelas()->create([
                    'vencimento' => $parcela['vencimento'],
                    'forma_pagamento_id' => $parcela['meio_pagamento'],
                    'valor' => $parcela['valor']
                ]);
            }
            DB::commit();
            session()->flash('notification', ['type' => 'success', 'message' => 'Venda realizada com sucesso!']);
            return Redirect::to(route('vendas.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notification', ['type' => 'error', 'message' => 'Algo deu errado ao tentar salvar']);
            return back();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venda $venda)
    {
        $venda->load(['cliente', 'parcelas' => fn($query) => $query->with(['forma_pagamento']),
            'user',
            'produtos']);

        return Inertia::render('Venda/Edit', [
            'cliente' => $venda->cliente,
            'parcelas' => $venda->parcelas->map(fn($parcela) => ['valor' => $parcela->valor, 'id' => $parcela->id, 'vencimento' => $parcela->vencimento, 'meio_pagamento' => $parcela->forma_pagamento]),
            'produtos' => $venda->produtos->map(fn($produto) => ['item' => $produto, 'nome' => $produto->nome , 'qtde' => $produto->pivot->quantidade, 'preco_base' => $produto->pivot->valor_unitario,
                'id' => $produto->id],
            ),
            'total' => $venda->total,
            'id' => $venda->id,
            'forma_pagamento' => FormaPagamento::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVendaRequest $request, Venda $venda)
    {
        $validated = $request->validated();

        DB::beginTransaction();
        try {

            $cliente = Cliente::findOrFail($validated['cliente']);
            $itens = array_map(fn($produto) => $produto['item'], $validated['produtos']);
            $quantidade = array_map(fn($produto) => $produto['qtde'], $validated['produtos']);
            $valor_unitario = array_map(fn($produto) => $produto['valor'], $validated['produtos']);
            $produtos = Produto::whereIn('id', $itens)->get();

            $parcelas = $validated['parcelas'];
            $valores = array_column($parcelas, 'valor');
            $sum = array_sum($valores);
            if ($sum != $validated['total']) {
                session()->flash('notification', [ 'type' => 'error', 'message' => 'As  parcelas não coincidem com o valor da venda' ]);
                return back()
                    ->withInput()
                    ->withErrors(['total' => 'As  parcelas não coincidem com o valor da venda']);
            }

            $venda->cliente()->associate($cliente);
            $venda->total = $validated['total'];

            $venda->produtos()->detach();
            foreach ($produtos as $key => $produto) {
                ProdutoVenda::create ([
                    'valor_unitario' => $valor_unitario[$key],
                    'quantidade' => $quantidade[$key],
                    'produto_id' => $produto->id,
                    'venda_id' => $venda->id
                ]);
            }
            $venda->parcelas()->delete();
            foreach ($parcelas as $parcela) {
                $venda->parcelas()->create([
                    'vencimento' => $parcela['vencimento'],
                    'forma_pagamento_id' => $parcela['meio_pagamento'],
                    'valor' => $parcela['valor']
                ]);
            }
            $venda->save();
            DB::commit();
            session()->flash('notification', ['type' => 'success', 'message' => 'Editado com sucesso!']);
            return Redirect::to(route('vendas.index'));
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('notification', ['type' => 'error', 'message' => 'Algo deu errado ao tentar editar']);
            return back();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venda $venda)
    {
        $venda->delete();
        session()->flash('notification', ['type' => 'success', 'message' => 'Deletado com sucesso!']);
        return Redirect::to(route('vendas.index'));
    }
}
