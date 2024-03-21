<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
/** @mixin Builder */
class Parcela extends Model
{
    use HasFactory;
    protected $fillable = [
        'valor',
        'vencimento',
        'data_pagamento',
        'forma_pagamento_id'
    ];
    protected $casts = [
        'vencimento' => 'date',
        'data_pagamento' => 'date'
    ];

    public  function venda(): BelongsTo
    {
        return $this->belongsTo(Venda::class);
    }
    public function forma_pagamento() : BelongsTo
    {
        return $this->belongsTo(FormaPagamento::class);
    }

}
