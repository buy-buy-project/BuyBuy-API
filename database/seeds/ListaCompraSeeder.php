<?php

use Illuminate\Database\Seeder;

class ListaCompraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $listas = [];
        $listas[] = ['data_lista' => '2016-07-21','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-22','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-23','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-24','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-25','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-26','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-27','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => '2016-07-28','consumidorID' => 1, 'recomendada' => 0, 'confirmada' => 1];
        $listas[] = ['data_lista' => date('Y-m-d', strtotime('now')),'consumidorID' => 1, 'recomendada' => 1, 'confirmada' => 0];

        foreach($listas as $lista) {
            App\Models\ListaCompra::create(
            	[
            		'data_lista' => $lista['data_lista'],
            		'consumidor_id' => $lista['consumidorID'],
            		'recomendada' => $lista['recomendada'],
            		'confirmada' => $lista['confirmada']
            	]
            )->save();
        }
    }
}
