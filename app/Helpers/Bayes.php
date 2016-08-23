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
				$dt = self::QTD_DIAS - $t;
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

				$formula *= (1 - ($qtdVezesTransicao/$qtdTotalVezesTransicao) );
			}

			$probabilidades[$estado] = 1 - $formula;
		}
		
	}
}