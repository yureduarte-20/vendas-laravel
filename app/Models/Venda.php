<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin Builder
 */
class Venda extends Model
{
    use HasFactory;
    protected $fillable = [
        'total'
    ];

    public function produtos()
    {
        return $this->belongsToMany(Produto::class)
            ->using(ProdutoVenda::class)->withTimestamps();
    }
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class);
    }
    public function parcelas():HasMany
    {
        return $this->hasMany(Parcela::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
