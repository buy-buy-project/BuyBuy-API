<?php

namespace App\Helpers;

class Bayes
{
	const QTD_DIAS = 90;

	public static function inferencia($redeMarkov, $historico, $totalFuncaoTransicao, $retornaTodasProbs = false) {
        $estados = array_keys($redeMarkov);
        $probs = [];

        $maxProb = -1;
        $maxEstado = 0;


        foreach($estados as $destino){
            $termos = [];
            $probs[$destino]=0;
            foreach(range(1,90) as $dt){
                $termos[$dt] = self::probUmaTransicao($redeMarkov, $historico[365-$dt], $destino, $dt);
            }
            $avg = array_sum(array_values($termos))/count($termos);
            foreach(range(1,90) as $dt){
                $termos[$dt] = $termos[$dt]  - $avg;
                if($termos[$dt] < 0)
                    $termos[$dt]=0;
            }
            $probs[$destino] = max(array_values($termos));
        }

        // Calcula fator de normalização
        $somaDasProbabilidades = array_sum(array_values($probs));
        $fatorNormalizacao = 1 / $somaDasProbabilidades;

        // Aplica o fator nas probabilidades
        array_walk($probs, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

        arsort($probs);

        if($retornaTodasProbs)
            return $probs;

        $quantidadeMaiorProbabilidade = key($probs);

        $maiorProbabilidade = array_shift($probs);

        return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
    }

	private static function aplicaFatorNormalizacao(&$valor, $indice, $fator) {
		$valor = ($valor * $fator) * 100;
	}
}
