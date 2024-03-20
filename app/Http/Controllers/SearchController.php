<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function find_cliente(Request $request)
    {
        $search = $request->query('search');
        $not_search = $request->query('not');

        if($not_search)
            $not_search = explode(',', $not_search);
        return Cliente::when($search, fn(Builder $query) => $query->where('nome', 'LIKE', '%' . $search . '%')
            ->orWhere('cpf', 'LIKE', '%' . $search . '%'))
            ->when($not_search, fn($query) => $query->whereNotIn('id', $not_search))
            ->get();
    }

    public function find_produtos(Request $request)
    {
        $search = $request->query('search');
        $not_search = $request->query('not');
        if($not_search)
            $not_search = explode(',', $not_search);
        return Produto::when($search , fn(Builder $query) => $query->where('nome', 'LIKE', '%'.$search.'%'))
            ->when($not_search, fn($query) => $query->whereNotIn('id', $not_search))
            ->get();
    }
}
