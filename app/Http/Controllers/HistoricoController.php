<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ListaCompra;
use App\Models\Consumidor;
use App\Models\Produto;

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
            $lista->consumidor_nome = $consumidor->nome;
            $lista->compras = $lista->compras()->get();

            foreach ($lista->compras as $compra) {
                $produto = Produto::findOrFail($compra->produto_id);
                $compra->produto_nome = $produto->nome;
            }

        }

        return $listaCompra->toJson();
    }
}
