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
        Schema::create('produto_venda', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('quantidade')->default(1);
            $table->unsignedFloat('valor_unitario')->default(0.1);

            $table->foreignIdFor(\App\Models\Produto::class)->nullable()->nullOnDelete();
            $table->foreignIdFor(\App\Models\Venda::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produto_venda');
    }
};
