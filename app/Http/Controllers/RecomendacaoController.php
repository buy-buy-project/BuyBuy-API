<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ListaCompra;
use App\Models\Consumidor;
use App\Models\Produto;
use App\Models\Compra;

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

    /**
     * Insere lista de compras confirmada
     *
     * @param  int  $idConsumidor
     */
    public function listaDeComprasConfirmada(Request $request) {
        $listaCompra = $request->all();
        $listaCompra = $listaCompra['lista_compra'][0];

        $compras = Compra::where('lista_compra_id', $listaCompra['id'])->get();

        // apaga as compras
        foreach ($compras as $compra) {
            $compra->delete();
        }

        // insere compras confirmadas
        foreach ($listaCompra['compras'] as $compra) {
            unset($compra['id']);
            Compra::create($compra);
        }

        // atualiza a lista
        $lista = ListaCompra::findOrFail($listaCompra['id']);
        $lista->update(['confirmada' => 1]);
    }

    public function teste() {
        $servidor = 'http://localhost/BuyBuy-API/public/listaConfirmada';

        $content = '{
         "lista_compra": [{
          "id": 1,
          "data_lista": "2016-07-21",
          "recomendada": 2,
          "confirmada": 1,
          "consumidor_id": 1,
          "compras": [{
           "id": 1,
           "quantidade": 6,
           "produto_id": 1,
           "lista_compra_id": 1
          }, {
           "id": 2,
           "quantidade": 2,
           "produto_id": 2,
           "lista_compra_id": 1
          }]
         }]
        }';

        $context = stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'header' => "Connection: close\r\n".
                            "Content-type: application/json\r\n".
                            "Content-Length: ".strlen($content)."\r\n",
                'content' => $content
            )
        ));

        $teste = file_get_contents($servidor, null, $context);

        echo $teste;
    }
}
