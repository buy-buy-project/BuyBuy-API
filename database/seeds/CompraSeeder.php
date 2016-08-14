<?php

use Illuminate\Database\Seeder;

class CompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $compras = [];
        $compras[] = ['quantidade' => 6, 'produtoID' => 1, 'listaID' => 1];
        $compras[] = ['quantidade' => 0, 'produtoID' => 1, 'listaID' => 2];
        $compras[] = ['quantidade' => 6, 'produtoID' => 1, 'listaID' => 3];
        $compras[] = ['quantidade' => 0, 'produtoID' => 1, 'listaID' => 4];
        $compras[] = ['quantidade' => 2, 'produtoID' => 1, 'listaID' => 5];
        $compras[] = ['quantidade' => 4, 'produtoID' => 1, 'listaID' => 6];
        $compras[] = ['quantidade' => 0, 'produtoID' => 1, 'listaID' => 7];
        $compras[] = ['quantidade' => 6, 'produtoID' => 1, 'listaID' => 8];

        foreach($compras as $compra) {
            App\Models\Compra::create(
            	[
            		'quantidade' => $compra['quantidade'],
            		'produto_id' => $compra['produtoID'],
            		'lista_id' => $compra['listaID']
            	]
            )->save();
        }
    }
}
