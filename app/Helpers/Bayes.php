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
	}

	private static function aplicaFatorNormalizacao(&$valor, $indice, $fator) {
		$valor = ($valor * $fator) * 100;
	}
}