<?php

use Illuminate\Database\Seeder;

class ListaApresentacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	// Apaga caso já exista
    	$listas = App\Models\ListaCompra::where('consumidor_id', 3)->get();

    	if($listas->count() > 0) {
    		foreach($listas as $lista) {
		    	$compras = App\Models\Compra::where('lista_compra_id', $lista->id)->get();

		       	foreach ($compras as $compra)
		            $compra->delete();

		        $lista->delete();
		    }
	    }

    	// Insere lista
        $listaID = App\Models\ListaCompra::create(
        	[
        		'data_lista' => date('Y-m-d', strtotime('now')),
        		'consumidor_id' => 3,
        		'recomendada' => 1,
        		'confirmada' => 0
        	]
        )->id;

        // Insere 9 compras na lista
        $compras = [];
        $compras[] = ['quantidade' => 2, 'produtoID' => 2, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 3, 'produtoID' => 3, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 4, 'produtoID' => 4, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 2, 'produtoID' => 5, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 1, 'produtoID' => 6, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 8, 'produtoID' => 7, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 9, 'produtoID' => 8, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 5, 'produtoID' => 9, 'listaID' => $listaID];
        $compras[] = ['quantidade' => 7, 'produtoID' => 10, 'listaID' => $listaID];

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
