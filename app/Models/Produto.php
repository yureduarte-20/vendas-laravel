<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Produto extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'descricao',
        'preco_base'
    ];

    public function vendas()
    {
        return $this->belongsToMany(Venda::class)
            ->using(ProdutoVenda::class)
            ->withTimestamps();
    }
}
