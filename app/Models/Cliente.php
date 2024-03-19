<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Builder
 */
class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome',
        'cpf',
    ];

    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
