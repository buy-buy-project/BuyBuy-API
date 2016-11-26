<?php

use Illuminate\Database\Seeder;

class ProdutoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $produtos = [];
        $produtos[] = ['nome' => 'Arroz', 'sku' => 'kg'];
        $produtos[] = ['nome' => 'Feijão', 'sku' => 'kg'];
        $produtos[] = ['nome' => 'Água', 'sku' => 'l'];
        $produtos[] = ['nome' => 'Leite', 'sku' => 'cx'];
        $produtos[] = ['nome' => 'Café', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Laranja', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Banana', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Biscoito', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Refrigerante', 'sku' => 'l'];
        $produtos[] = ['nome' => 'Carne', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Frango', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Ovo', 'sku' => 'cx'];
        $produtos[] = ['nome' => 'Shampoo', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Pasta de Dente', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Papel Higiênico', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Sabonete', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Azeite', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Sal', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Açúcar', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Cerveja', 'sku' => 'cx'];
        $produtos[] = ['nome' => 'Sorvete', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Abacaxi', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Melancia', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Melão', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Cenoura', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Desodorante', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Queijo', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Pão', 'sku' => 'unid'];
        $produtos[] = ['nome' => 'Bolacha', 'sku' => 'pct'];
        $produtos[] = ['nome' => 'Cebola', 'sku' => 'g'];
        $produtos[] = ['nome' => 'Alho', 'sku' => 'g'];

        foreach($produtos as $produto) {
            App\Models\Produto::create($produto)->save();
        }
    }
}
