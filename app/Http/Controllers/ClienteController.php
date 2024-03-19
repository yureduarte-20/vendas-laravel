<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;


class ClienteController extends Controller
{
    protected $rules = [
        'cpf' => 'string|required|min:11|max:11|unique:clientes,cpf',
        'nome' => 'string|required|min:3'
    ];
    public function index()
    {
        $query_cliente = \Illuminate\Support\Facades\Request::query('cliente');
        $query_user = \Illuminate\Support\Facades\Request::query('user');
        $clientes = Cliente::when($query_cliente, function (Builder $query) use ($query_cliente) {
            $query->where('id', $query_cliente);
        })
            ->when($query_user, fn(Builder $query) => $query->whereHas('vendas', fn(Builder $query) => $query->where('user_id', $query_user)))
            ->withCount(['vendas'])
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Cliente/index', [
            'clientes' => $clientes
        ]);
    }

    public function create()
    {
        return Inertia::render('Cliente/Create');
    }
    public function edit(string $cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        return Inertia::render('Cliente/Edit', [...$cliente->toArray()]);
    }

    public function update(Request $request, string $cliente_id)
    {
        $validator = Validator::make($request->all(), [
            'nome' => $this->rules['nome'],
            'cpf' => 'string|required|min:11|max:11'
        ]);
        $cliente = Cliente::find($cliente_id);
        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }
        $validated = $validator->validated();
        if($cliente->cpf !== $validated['cpf']){
            $validator = Validator::make($request->all(), [
                'cpf' => 'string|required|min:11|max:11|unique:clientes,cpf'
            ]);
            if ($validator->fails()) {
                return back()
                    ->withErrors($validator)
                    ->withInput();
            }

        }
        $cliente->fill($validated)->save();
        return redirect(route('clientes.index'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->rules);
        $cliente = Cliente::create($validated);
        session()->flash('notification', [ 'status' => 'success', 'message' => 'Criado com sucesso!' ]);
        return Redirect::to(route('clientes.index'));
    }

    public function destroy(string $cliente_id)
    {
        $cliente = Cliente::findOrFail($cliente_id);
        $cliente->delete();
        return Redirect::to(route('clientes.index'));
    }
}
