<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ProdutoController extends Controller
{
    protected $rules = [
        'nome' => 'string|required',
        'descricao' => 'nullable|string',
        'preco_base' => 'nullable|numeric|min:0.01'
    ];
    public function index()
    {
        $queryString = \Illuminate\Support\Facades\Request::query('search');
        $produtos = Produto::
          when($queryString, fn(Builder $query) => $query->where('nome', 'LIKE', '%'.$queryString.'%'))
        ->paginate(10)
            ->withQueryString();
        return Inertia::render('Produtos/index', [ 'produtos' => $produtos ]);
    }

    public function edit(string $produto)
    {
        return Inertia::render('Produtos/Edit', [ ...Produto::findOrFail($produto)->toArray() ]);
    }
    public function create()
    {
        return Inertia::render('Produtos/Create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        Produto::create($validated);
        session()->flash('notification', [ 'type'=>'success', 'message'=>'Produto criado com sucesso!' ]);
        return Redirect::to(route('produtos.index'));
    }
    public function update(Request $request, string $produto)
    {
        $validated = $request->validate($this->rules);
        $_produto = Produto::findOrFail($produto);
        $_produto->fill($validated)->saveOrFail();
        session()->flash('notification', [ 'type' => 'success', 'message'=> 'Atualizado com sucesso!' ]);
        return Redirect::to(route('produtos.index'));
    }
    public function destroy(string $produto)
    {
        $_produto = Produto::findOrFail($produto);
        $_produto->delete();
        session()->flash('notification', [ 'type' => 'success', 'message'=> 'Deletado com sucesso!' ]);
        return Redirect::to(route('produtos.index'));
    }

}
