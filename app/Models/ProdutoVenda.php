<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

/**
 * @mixin Builder
 */
class ProdutoVenda extends Pivot
{
    protected $table = 'produto_venda';
    use HasFactory;

    protected $fillable = [
        'quantidade',
        'valor_unitario'
    ];
}
