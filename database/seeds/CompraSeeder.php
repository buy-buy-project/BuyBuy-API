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

        $compras[] = ['quantidade' => 6, 'produtoID' => 1, 'listaID' => 9];
        $compras[] = ['quantidade' => 2, 'produtoID' => 2, 'listaID' => 9];
        $compras[] = ['quantidade' => 3, 'produtoID' => 3, 'listaID' => 9];
        $compras[] = ['quantidade' => 4, 'produtoID' => 4, 'listaID' => 9];
        $compras[] = ['quantidade' => 2, 'produtoID' => 5, 'listaID' => 9];
        $compras[] = ['quantidade' => 1, 'produtoID' => 6, 'listaID' => 9];
        $compras[] = ['quantidade' => 8, 'produtoID' => 7, 'listaID' => 9];
        $compras[] = ['quantidade' => 9, 'produtoID' => 8, 'listaID' => 9];
        $compras[] = ['quantidade' => 5, 'produtoID' => 9, 'listaID' => 9];
        $compras[] = ['quantidade' => 7, 'produtoID' => 10, 'listaID' => 9];


        foreach($compras as $compra) {
            App\Models\Compra::create(
            	[
            		'quantidade' => $compra['quantidade'],
            		'produto_id' => $compra['produtoID'],
            		'lista_compra_id' => $compra['listaID']
            	]
            )->save();
        }
    }
}
