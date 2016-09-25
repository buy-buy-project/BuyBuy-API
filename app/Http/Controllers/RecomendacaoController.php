<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ListaCompra;
use App\Models\Consumidor;
use App\Models\Produto;

class RecomendacaoController extends Controller
{
    /**
     * Retorna lista recomendada do dia
     *
     * @param  int  $idConsumidor
     * @return string JSON com as listas/compras/dados do consumidor
     */
    public function index($idConsumidor) {
    	$arrWhere = [];
    	$arrWhere['data_lista'] = date('Y-m-d', strtotime("now"));
    	$arrWhere['consumidor_id'] = $idConsumidor;
    	$arrWhere['recomendada'] = 1;
    	$arrWhere['confirmada'] = 0;
    	$listaCompra = ListaCompra::where($arrWhere)->get();

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
