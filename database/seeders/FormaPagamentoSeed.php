<?php

namespace Database\Seeders;

use App\Models\FormaPagamento;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FormaPagamentoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FormaPagamento::create([
            'nome' => 'Cartão Crédito'
        ]);
        FormaPagamento::create([
            'nome' => 'Cartão Débito'
        ]);
        FormaPagamento::create([
            'nome' => 'Pix'
        ]);
        FormaPagamento::create([
            'nome' => 'Boleto'
        ]);
        FormaPagamento::create([
            'nome' => 'Dinheiro'
        ]);
    }
}
