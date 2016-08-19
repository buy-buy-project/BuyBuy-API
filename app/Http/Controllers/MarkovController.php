<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Consumidor;
use App\Models\Produto;
use App\Models\ListaCompra;
use App\Models\Compra;

class MarkovController extends Controller
{

	/**
     * Método construtor
     *
     * @return void
     */
    public function __construct()
    {
        $this->consumidores = Consumidor::all();
        $this->produtos = Produto::all();
    }

    /**
     * Inicio do Markov.
     *
     * @return void
     */
    public function index()
    {
        $compras = null;
        $data90dias = date('Y-m-d', strtotime("-90 days"));
        $estados = [];

        //$compra = Compra::with('listaCompra.consumidor')->get();

        /*$compra = Compra::with(['listaCompra' => function ($query) {
		    $query->where('consumidor_id', '=', 2);
		}])->where('consumidor_id', '=', 2)->get();*/

		// tentar fazer igual ao show da controller

		dd($compra);

        /*foreach ($this->consumidores as $consumidor) {
        	$lista->consumidor()->associate($consumidor);

        	$listasCompra = ListaCompra::where('consumidor_id', $consumidor->id)
    			->where('data_lista', '>=', $data90dias)
    			->get();

        	foreach ($this->produtos as $produto) {
        		foreach ($listasCompra as $lista) {
		            $lista->compras = $lista->compras()->where('produto_id', $produto->id)->get();
				}
        	}
        }*/
    }
}
