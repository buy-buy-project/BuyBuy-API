<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Models\Consumidor;
use App\Models\Produto;
use App\Models\ListaCompra;
use App\Models\Compra;
use App\Models\Markov;

use DateTime;

class MarkovController extends Controller
{

    const QTD_DIAS = 90;

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
    public function index()
    {
        $compras = null;
        $historico = $this->inicializaArray();
        $estados = [];
        $transicoes = [];
        $dataHoje = new DateTime(date('Y-m-d', strtotime("now")));

        foreach ($this->consumidores as $consumidor) {
        	foreach ($this->produtos as $produto) {
                $compras = Markov::comprasDoConsumidorPorProduto($consumidor->id, $produto->id);

                // Gera o historico
                foreach ($compras as $compra) {
                    $dataCompra = new DateTime($compra->data_lista);
                    $t = self::QTD_DIAS - ($dataHoje->diff($dataCompra)->days);
                    $historico[$t] = $compra->quantidade;
                }

                // Gera os estados
                foreach ($historico as $quantidade) {
                    if($quantidade != -1) {
                        if(!isset($estados[$quantidade]))
                            $estados[$quantidade] = [];
                    }
                }

                // Gera as transições
                $transicoes = $estados;
                foreach ($historico as $t => $quantidade) {
                    if($quantidade != -1) {
                        $estadoAtual = $quantidade;

                        for($deltaT = 1; $deltaT <= self::QTD_DIAS; $deltaT++) {
                            for($i = $t+1; $i <= self::QTD_DIAS; $i = $i + $deltaT) {
                                $proximaQuantidade = $historico[$i];

                                if($proximaQuantidade != -1 && !isset($transicoes[$estadoAtual][$proximaQuantidade])) {
                                    $transicoes[$estadoAtual][$proximaQuantidade] = 0;
                                }

                            }
                        }
                    }
                }

        	}
        }
    }

    private function inicializaArray() {
        $array = [];
        for($i = 1; $i <= self::QTD_DIAS; $i++) {
            $array[$i] = -1;
        }
        return $array;
    }

}
