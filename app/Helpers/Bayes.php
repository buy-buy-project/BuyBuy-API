<?php

namespace App\Helpers;

class Bayes
{
	const QTD_DIAS = 90;

	public static function inferencia($redeMarkov, $historico, $totalFuncaoTransicao) {
		$situacoes = [];

		// Gera as situações
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
				$formula *= (1 - ($redeMarkov[$quantidade][$estado][$dt]/$totalFuncaoTransicao[$quantidade][$estado]) );
			}

			$probabilidades[$estado] = 1 - $formula;
		}
		
	}
}