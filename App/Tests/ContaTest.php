<?php
use Livro\Database\Transaction;
use Livro\Database\Repository;
use Livro\Database\Criteria;

use PHPUnit\Framework\TestCase;

class ContaTest extends TestCase
{
    public function testNewAccounts()
    {
        Transaction::open('livro');
        $cliente = new Pessoa;
        $cliente->nome = 'Teste';
        $cliente->store();

        $venda = new Venda;
        $venda->cliente = $cliente;
        $venda->data_venda  = date('Y-m-d');
        $venda->desconto    = 10;
        $venda->acrescimos  = 5;

        $produtos = [1,2,3];
        $total_produtos = 0;
        foreach ($produtos as $id_produto)
        {
            $venda->addItem($produto = new Produto($id_produto), 1);
            $total_produtos += $produto->preco_venda;
        }
        $venda->valor_venda = $total_produtos;
        $venda->valor_final = $total_produtos + $venda->acrescimos - $venda->desconto;
        $venda->store();
        $parcelas = 5;
        Conta::geraParcelas($cliente->id, 2, $venda->valor_final, $parcelas);

        $this->assertEquals( $venda->valor_final, $cliente->totalDebitos());
        //Transaction::close();
    }
}
