<?php

namespace App\Helpers;

class Bayes
{
	const QTD_DIAS = 90;

    public static function probUmaTransicao($redeMarkov, $from, $to, $dt){
        //Somando Prob chegar em $to
        $soma = 0;

        foreach($redeMarkov[$from] as $t => $v){
            $soma += isset($v[$dt]) ? $v[$dt] : 0;
        }

        return ($soma==0 || !isset($redeMarkov[$from][$to][$dt])) ? 0 : $redeMarkov[$from][$to][$dt]/$soma;
    }

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
        $somaDasProbabilidades = ($somaDasProbabilidades > 0) ? $somaDasProbabilidades : 1;
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
