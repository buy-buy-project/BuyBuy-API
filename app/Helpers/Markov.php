<?php

namespace App\Helpers;

use DateTime;
use App\Models\Markov as MarkovModel;
use App\Helpers\Bayes;

class Markov
{
	const QTD_DIAS = 90;

	public static function aprendizagem($idConsumidor = null, $idProduto = null, $experimento = null) {
		$compras = null;
        $dataHoje = new DateTime('2016-09-22');
        $historico = self::inicializaArray();
        $estados = [];
        $transicoes = [];

        if(!$experimento) {
            $compras = MarkovModel::comprasDoConsumidorPorProduto($idConsumidor, $idProduto);
        } else {
            $compras = json_decode($experimento);
        }

        // Gera o historico
        foreach ($compras as $compra) {
            $dataCompra = new DateTime($compra->data_lista);
            $t = self::QTD_DIAS + 1 - ($dataHoje->diff($dataCompra)->days);
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
        $totalPorTransicao = $estados;
        foreach ($historico as $t => $quantidade) {
            if($quantidade != -1) {
                $estadoAtual = $quantidade;

                // Busca no histórico todas as quantidades que ocorreram após a quantidade atual, em todos os deltaT possíveis
                for($deltaT = 1; $deltaT <= self::QTD_DIAS; $deltaT++) {
                    for($i = $t+1; $i <= self::QTD_DIAS; $i = $i + $deltaT) {
                        $proximoEstado = $historico[$i];

                        // Verifica se já foi inserido como uma transição, caso não tenha sido, então é inserido.
                        if($proximoEstado != -1 && !isset($transicoes[$estadoAtual][$proximoEstado])) {
                            $transicoes[$estadoAtual][$proximoEstado] = [];
                            $totalPorTransicao[$estadoAtual][$proximoEstado] = 0;
                        }

                    }
                }

            }
        }

        // Funções de transição
        $totalPorTransicao = [];
        foreach($transicoes as $estado => $estadosDeTransicao) {
            foreach ($estadosDeTransicao as $estadoTransicaoAtual => $qtdTransicoes) {
                $proximoEstado = $estadoTransicaoAtual;
                $total = 0;

                // Busca no histórico quantas vezes aconteceu uma determinada transição em todos os deltaT possíveis
                for($deltaT = 1; $deltaT <= self::QTD_DIAS; $deltaT++) {
                    $totalNoDeltaT = 0;
                    for($i = 1; $i <= self::QTD_DIAS; $i++) {
                        $estadoAtualHist = $historico[$i];
                        $proximoEstadoHist = isset($historico[$i+$deltaT]) ? $historico[$i+$deltaT] : -1;

                        // Soma a quantidade de vezes na transição e no array de totais para utilizar na inferência
                        if($estado == $estadoAtualHist && $proximoEstado == $proximoEstadoHist) {
                            $totalNoDeltaT += 1;
                            $total += 1;
                        }

                    }
                    $transicoes[$estado][$proximoEstado][$deltaT] = $totalNoDeltaT;
                }

                $totalPorTransicao[$estado][$proximoEstado] = $total;
            }
        }

        $redeMarkov = [
        	'rede' => $transicoes,
        	'historico' => $historico,
        	'totalPorTransicao' => $totalPorTransicao
        ];

        return $redeMarkov;
	}

	public static function inicializaArray() {
		$array = [];
        for($i = 1; $i <= self::QTD_DIAS; $i++) {
            $array[$i] = -1;
        }
        return $array;
	}
}