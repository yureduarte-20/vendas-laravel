<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormaPagamento extends Model
{
    use HasFactory;
    protected $fillable = [
        'nome'
    ];

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }
}
