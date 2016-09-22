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
			$formulaTotal = 0;
			$formula = 0;
			foreach ($situacoes as $dt => $quantidade) {
				$qtdVezesTransicao = isset($redeMarkov[$quantidade][$estado][$dt]) ? $redeMarkov[$quantidade][$estado][$dt] : 0;
				$formula += $qtdVezesTransicao;
			}

			foreach ($totalFuncaoTransicao as $estadoAtual => $proximoEstado) {
				foreach ($proximoEstado as $prox => $valorEstado) {
					if($prox == $estado) {
						$formulaTotal += $valorEstado;
					}
				}
			}

			if($formulaTotal == 0) {
				//echo '<pre>'; print_r($historico); echo '</pre>';
				//echo 'Estado: '. $estado . '<pre>'; print_r($totalFuncaoTransicao); echo '</pre>';

				/*foreach ($totalFuncaoTransicao as $estadoAtual => $proximoEstado) {
					echo 'estadoAtual: ' . $estadoAtual;
					echo '<pre>'; print_r($proximoEstado); echo '</pre>';
					foreach ($proximoEstado as $prox => $valorEstado) {
						echo 'prox: ' . $prox;
						echo ' -> valorEstado: ' . $valorEstado . '<br>';
						if($prox == $estado) {
							echo '<br>entrou aqui<br>';
							$formulaTotal += $valorEstado;
						}
					}
				}*/
			}
			$formulaTotal = ($formulaTotal == 0) ? 1 : $formulaTotal;
			$probabilidades[$estado] = $formula / $formulaTotal;
		}

		// Calcula fator de normalização
		$somaDasProbabilidades = array_sum($probabilidades);
		$fatorNormalizacao = 1 / $somaDasProbabilidades;

		// Aplica o fator nas probabilidades
		array_walk($probabilidades, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

		arsort($probabilidades);

		//echo '<pre>'; print_r($probabilidades); echo '</pre>';
		//echo '<pre>'; print_r($redeMarkov); echo '</pre>';
		//echo '<pre>'; print_r($totalFuncaoTransicao); echo '</pre>';

		//dd($probabilidades);

		$quantidadeMaiorProbabilidade = key($probabilidades);

		$maiorProbabilidade = array_shift($probabilidades);

		return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
	}

	private static function aplicaFatorNormalizacao(&$valor, $indice, $fator) {
		$valor = ($valor * $fator) * 100;
	}
}