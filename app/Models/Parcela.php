<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Parcela extends Model
{
    use HasFactory;
    protected $fillable = [
        'valor',
        'observacoes',
        'vencimento',
        'data_pagamento',

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
