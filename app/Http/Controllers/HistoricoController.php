<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ListaCompra;
use App\Models\Consumidor;

class HistoricoController extends Controller
{
	/**
     * Retorna histórico de compras de um determinado consumidor
     *
     * @param  int  $idConsumidor
     * @return string JSON com as listas/compras/dados do consumidor
     */
    public function index($idConsumidor) {
    	$listaCompra = ListaCompra::where('consumidor_id', $idConsumidor)->get();

        foreach ($listaCompra as $lista) {
            $consumidor = Consumidor::findOrFail($idConsumidor);
            $lista->consumidor()->associate($consumidor);
            $lista->compras = $lista->compras()->get();
        }

        return $listaCompra->toJson();
    }
}
