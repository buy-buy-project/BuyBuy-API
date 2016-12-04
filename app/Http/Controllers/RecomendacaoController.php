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

        // Cria nova lista
        $this->insereListaApresentacao();

        // Insere produto na lista recomendada
        $listas = ListaCompra::where('consumidor_id', 3)->get();
        if($listas->count()) {
            foreach ($listas as $lista)
                $listaCompraID = $lista->id;

            $qtdsRecomendadas = array_keys($probabilidades);
            $qtdRecomendada = $qtdsRecomendadas[0];

            if($qtdRecomendada > 0)
                Compra::create(['quantidade' => $qtdRecomendada, 'produto_id' => 1, 'lista_compra_id' => $listaCompraID]);
        }

        echo json_encode($retorno);
    }

    /**
     * Apaga lista existente e insere um nova com 9 produtos a ser recomendada para o consumidor
     *
     */
    private function insereListaApresentacao() {
        // Apaga caso já exista
        $listas = ListaCompra::where('consumidor_id', 3)->get();

        if($listas->count() > 0) {
            foreach($listas as $lista) {
                $compras = Compra::where('lista_compra_id', $lista->id)->get();

                foreach ($compras as $compra)
                    $compra->delete();

                $lista->delete();
            }
        }

        // Insere lista
        $listaID = ListaCompra::create(
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
            Compra::create(
                [
                    'quantidade' => $compra['quantidade'],
                    'produto_id' => $compra['produtoID'],
                    'lista_compra_id' => $compra['listaID']
                ]
            )->save();
        }
    }
}
