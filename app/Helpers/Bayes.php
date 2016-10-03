<?php

namespace App\Helpers;

class Bayes
{
	const QTD_DIAS = 90;

	public static function inferencia($redeMarkov, $historico, $totalFuncaoTransicao) {
		// Gera as situações
		$situacoes = [];
		foreach ($historico as $t => $quantidade) {
			if($quantidade != -1) {
				$dt = 1 + self::QTD_DIAS - $t;
				$situacoes[$dt] = $quantidade;
			}
		}

		// Calcula a probabilidade das quantidades
		$probabilidades = [];
		foreach($redeMarkov as $estado => $transicoes) {
			$formula = 1;
			foreach ($situacoes as $dt => $quantidade) {
				$qtdVezesTransicao = isset($redeMarkov[$quantidade][$estado][$dt]) ? $redeMarkov[$quantidade][$estado][$dt] : 0;

				$qtdTotalVezesTransicao = isset($totalFuncaoTransicao[$quantidade][$estado]) ? $totalFuncaoTransicao[$quantidade][$estado] : 1;

				$formula *= (1 - ($qtdVezesTransicao / $qtdTotalVezesTransicao) );
			}

			$probabilidades[$estado] = 1 - $formula;
		}

		// Calcula fator de normalização
		$somaDasProbabilidades = array_sum($probabilidades);
		$fatorNormalizacao = 1 / $somaDasProbabilidades;

		// Aplica o fator nas probabilidades
		array_walk($probabilidades, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

		arsort($probabilidades);

		$quantidadeMaiorProbabilidade = key($probabilidades);

		$maiorProbabilidade = array_shift($probabilidades);

		return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
	}


    public static function probUmaTransicao($redeMarkov, $from, $to, $dt){
        //Somando Prob chegar em $to
        $soma = 0;
        foreach($redeMarkov as $f => $t){
            $soma += isset($t[$to][$dt]) ? $t[$to][$dt] : 0;
        }
        return ($soma==0 || !isset($redeMarkov[$from][$to][$dt])) ? 0 : $redeMarkov[$from][$to][$dt]/$soma;
    }

	public static function inferenciaGuilherme($redeMarkov, $historico, $totalFuncaoTransicao) {
        #dd(self::probUmaTransicao($redeMarkov, 5, 5, 2));
        $estados = array_keys($redeMarkov);
        $probs = [];

        $maxProb = -1;
        $maxEstado = 0;

        foreach($estados as $destino){
            $termos = [];
            foreach(range(1,90) as $dt){
                $termos[$dt] = self::probUmaTransicao($redeMarkov, $historico[365-$dt], $destino, $dt);
            }
            $probs[$destino] = array_sum($termos) / count($termos);
        }
        
        // Calcula fator de normalização
        $somaDasProbabilidades = array_sum($probs);
        $fatorNormalizacao = 1 / $somaDasProbabilidades;

        // Aplica o fator nas probabilidades
        array_walk($probs, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

        arsort($probs);

        //dd($probs);

        $quantidadeMaiorProbabilidade = key($probs);

        $maiorProbabilidade = array_shift($probs);

        return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
    }

	public static function inferenciaNova($redeMarkov, $historico, $totalFuncaoTransicao) {
		// Gera as situações
		$situacoes = [];
		foreach ($historico as $t => $quantidade) {
			if($quantidade != -1) {
				$dt = 1 + self::QTD_DIAS - $t;
				$situacoes[$dt] = $quantidade;
			}
		}

		// Calcula a probabilidade das quantidades
		$probabilidades = [];
		foreach($redeMarkov as $estado => $transicoes) {
			$formula = 1;
			foreach ($situacoes as $dt => $quantidade) {
				$qtdVezesTransicao = isset($redeMarkov[$quantidade][$estado][$dt]) ? $redeMarkov[$quantidade][$estado][$dt] : 0;

				$qtdTotalVezesTransicao = 0;
				foreach ($redeMarkov as $estadoTotal => $transicoesTotal) {
					$qtdTotalVezesTransicao += isset($redeMarkov[$estadoTotal][$estado][$dt]) ? $redeMarkov[$estadoTotal][$estado][$dt] : 0;
				}

				$qtdTotalVezesTransicao = ($qtdTotalVezesTransicao > 0) ? $qtdTotalVezesTransicao : 1;

				#$parcelaProbabilidade = (($qtdVezesTransicao / $qtdTotalVezesTransicao) * log($dt));
				$parcelaProbabilidade = (($qtdVezesTransicao / $qtdTotalVezesTransicao) );

				$formula *= (1 - $parcelaProbabilidade);
			}

			$probabilidades[$estado] = 1 - $formula;
		}

		// Calcula fator de normalização
		$somaDasProbabilidades = array_sum($probabilidades);
		$fatorNormalizacao = 1 / $somaDasProbabilidades;

		// Aplica o fator nas probabilidades
		array_walk($probabilidades, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

		arsort($probabilidades);

		//dd($probabilidades);

		$quantidadeMaiorProbabilidade = key($probabilidades);

		$maiorProbabilidade = array_shift($probabilidades);

		return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
	}

	private static function aplicaFatorNormalizacao(&$valor, $indice, $fator) {
		$valor = ($valor * $fator) * 100;
	}
}
