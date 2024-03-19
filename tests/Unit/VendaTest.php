<?php

namespace Tests\Unit;

use App\Models\Cliente;
use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use App\Models\ProdutoVenda;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class VendaTest extends TestCase
{
    public function test_deletar_venda()
    {


        $user = User::factory()->create();
        $produto = Produto::create([
            'nome' => 'Carro',
            'valor_base' => 20_000.00
        ]);
        $cliente = Cliente::create([
            'nome' => 'teste',
            'cpf' => '00000000000',
        ]);
        $venda = Venda::newModelInstance([
            'total' => 40_000.00
        ]);
        $venda->cliente()->associate($cliente);
        $venda->user()->associate($user);
        $venda->save();
        $venda->produtos()->attach($produto, [
            'quantidade' => 2,
            'valor_unitario' => 20_000.00
        ]);

        $produto_venda = ProdutoVenda::all()[0];
        $this->assertEquals(2, $produto_venda->quantidade);
        $this->assertEquals(20_000.00, $produto_venda->valor_unitario);
        $this->assertEquals($produto_venda->produto_id, $produto->id);
        $this->assertEquals($produto_venda->venda_id, $venda->id);
        $venda->delete();
        $produto_venda = ProdutoVenda::where('venda_id', $venda->id)->count();
        $this->assertEquals(0, $produto_venda);
    }
}
