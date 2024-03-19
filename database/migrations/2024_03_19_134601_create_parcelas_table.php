<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->id();
            $table->unsignedDecimal('valor');
            $table->date('vencimento');
            $table->date('data_pagamento')->nullable();
            $table->text('observacoes')->nullable();

            $table->foreignIdFor(\App\Models\FormaPagamento::class)->constrained();
            $table->foreignIdFor(\App\Models\Venda::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parcelas');
    }
};
