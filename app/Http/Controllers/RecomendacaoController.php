<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\ListaCompra;
use App\Models\Consumidor;
use App\Models\Produto;
use App\Models\Compra;

use App\Helpers\Markov;
use App\Helpers\Bayes;

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
                $compra->produto_sku = $produto->sku;
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

    /**
     * Retorna as probabilidades de recomendação de um produto
     *
     * @param array histórico de compras do produto
     * @return string json com as probabilidades
     */
    public function recomendaUmProduto(Request $request) {
        $historico = $request->all();
        $historico = json_encode($historico);

        $markov = Markov::aprendizagem(null, null, $historico);
        $probabilidades = Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao'], true);

        $retorno = [];
        $i = 0;
        foreach ($probabilidades as $quantidade => $prob) {
            $retorno[$i]['quantidade'] = $quantidade;
            $retorno[$i]['probabilidade'] = $prob;
            $i++;
        }

        // Insere produto na lista recomendada
        $listas = ListaCompra::where('consumidor_id', 3)->get();
        if($listas->count()) {
            foreach ($listas as $lista)
                $listaCompraID = $lista->id;

            if(key($probabilidades) > 0)
                Compra::create(['quantidade' => key($probabilidades), 'produto_id' => 1, 'lista_compra_id' => $listaCompraID]);
        }

        echo json_encode($retorno);
    }  
}
