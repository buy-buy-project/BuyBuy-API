<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Consumidor;
use App\Models\Produto;
use App\Models\Compra;
use App\Models\ListaCompra;

use App\Helpers\Markov;
use App\Helpers\Bayes;

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
     * Gera rede markoviana
     *
     * @return void
     */
    public function index() {
        set_time_limit(0);
        
        foreach ($this->consumidores as $consumidor) {
            $probabilidadesProdutos = [];
            $quantidadesProdutos = [];
            $produtosFinais = [];

        	foreach ($this->produtos as $produto) {
                $markov = Markov::aprendizagem($consumidor->id, $produto->id);
                $probabilidade = Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);

                $quantidadesProdutos[$produto->id] = key($probabilidade);
                $probabilidadesProdutos[$produto->id] = array_shift($probabilidade);
        	}

            arsort($probabilidadesProdutos);

            $idLista = null;
            $inseridos = 0;
            foreach($probabilidadesProdutos as $idProduto => $prob) {
                if($inseridos == 10)
                    break;

                if($quantidadesProdutos[$idProduto] > 0) {
                    if($inseridos == 0) {
                        $dadosLista = [
                            'data_lista' => date('Y-m-d', strtotime('now')),
                            'consumidor_id' => $consumidor->id,
                            'recomendada' => 1,
                            'confirmada' => 0
                        ];
                        $idLista = ListaCompra::create($dadosLista)->id;
                    }

                    $dadosCompras = [
                        'quantidade' => $quantidadesProdutos[$idProduto],
                        'produto_id' => $idProduto,
                        'lista_compra_id' => $idLista
                    ];

                    Compra::create($dadosCompras);
                    $inseridos++;
                }
            }
        }
    }

    /**
     * Consulta o gerador para gerar histórico dos produtos para um usuário
     *
     * @return void
     */
    public function buscaHistoricoParaInserir($idConsumidor) {
        set_time_limit(0);

        for($i = 1; $i <= 31; $i++) {
            $intervalosDeDia = [7, 15, 30];
            $ruidosQuantidade = [0.4, 0.7, 1.0, 1.3, 1.5, 1.7, 2.0];
            $ruidosTempo = [0.4, 0.7, 1.0, 1.3];

            $aleatorioDia = rand(0, 2);
            $aleatorioQtd = rand(0, 6);
            $aleatorioTem = rand(0, 3);

            $servidor = 'http://localhost:8081/experimento4/1/'.$ruidosQuantidade[$aleatorioQtd].'/'.$ruidosTempo[$aleatorioTem].'/'.$intervalosDeDia[$aleatorioDia];

            $context = stream_context_create(array(
                'http' => array(
                    'method' => 'GET',
                    'header' => "Connection: close\r\n".
                                "Content-type: application/json\r\n",
                )
            ));
            // Realize comunicação com o servidor
            $compras = file_get_contents($servidor, null, $context);
            $compras = json_decode($compras);

            foreach($compras as $compra) {
                $listaCompra = ListaCompra::where(['data_lista' => $compra->data_lista, 'confirmada' => 1, 'consumidor_id' => $idConsumidor])->first();

                $idLista = null;
                if(!empty($listaCompra))
                    $idLista = $listaCompra->id;
                else {
                    $dadosLista = [
                        'data_lista' => $compra->data_lista,
                        'consumidor_id' => $idConsumidor,
                        'recomendada' => 0,
                        'confirmada' => 1
                    ];

                    $idLista = ListaCompra::create($dadosLista)->id;
                }

                $dadosCompras = [
                    'quantidade' => $compra->quantidade,
                    'produto_id' => $i,
                    'lista_compra_id' => $idLista
                ];

                Compra::create($dadosCompras);
            }    
        }
    }

}
