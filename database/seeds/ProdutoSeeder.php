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
        $produtos = ['Arroz', 'Feijão'];

        foreach($produtos as $nomeProduto) {
            App\Models\Produto::create(['nome' => $nomeProduto])->save();
        }
    }
}
