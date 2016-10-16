<?php

namespace App\Helpers;
//include 'Math.php';

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

				$qtdTotalVezesTransicao = ($qtdTotalVezesTransicao <= 0) ? 1 : $qtdTotalVezesTransicao;

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


    public static function probUmaTransicaoAntigo($redeMarkov, $from, $to, $dt){
        //Somando Prob chegar em $to
        $soma = 0;
        foreach($redeMarkov as $f => $t){
            $soma += isset($t[$to][$dt]) ? $t[$to][$dt] : 0;
        }

        return ($soma==0 || !isset($redeMarkov[$from][$to][$dt])) ? 0 : $redeMarkov[$from][$to][$dt]/$soma;
    }

    public static function probUmaTransicao($redeMarkov, $from, $to, $dt){
        //Somando Prob chegar em $to
        $soma = 0;
        foreach($redeMarkov[$from] as $t => $v){
            $soma += isset($v[$dt]) ? $v[$dt] : 0;
        }

        return ($soma==0 || !isset($redeMarkov[$from][$to][$dt])) ? 0 : $redeMarkov[$from][$to][$dt]/$soma;
    }

	public static function inferenciaCorreta_old($redeMarkov, $historico, $totalFuncaoTransicao) {
        #dd(self::probUmaTransicao($redeMarkov, 5, 5, 2));

        $estados = array_keys($redeMarkov);
        $probs = [];

        $maxProb = -1;
        $maxEstado = 0;

        foreach($estados as $destino){
            $termos = [];
            $probs[$destino]=0;
            $prod=1;
            foreach(range(1,90) as $dt){
                $termos[$dt] = self::probUmaTransicao($redeMarkov, $historico[365-$dt], $destino, $dt);
                $prod += $termos[$dt];
                #$prod *= sqrt($termos[$dt]);
            }
            $probs[$destino] = array_sum($termos) / count($termos);
            #$probs[$destino] = $prod+0.000000001;
            $json_string = json_encode(array_values($termos), JSON_PRETTY_PRINT);
            echo '"v_'.$destino.'":'.$json_string.',';
        }
        // Calcula fator de normalização
        $somaDasProbabilidades = array_sum($probs);
        $fatorNormalizacao = 1 / $somaDasProbabilidades;

        // Aplica o fator nas probabilidades
        array_walk($probs, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

        /*if(isset($probs[49])) {
        	$probs[50] += $probs[49];
        	unset($probs[49]);
        }

        if(isset($probs[51])) {
        	$probs[50] += $probs[51];
        	unset($probs[51]);
        }*/

        arsort($probs);

        $quantidadeMaiorProbabilidade = key($probs);

        $maiorProbabilidade = array_shift($probs);

        return [$quantidadeMaiorProbabilidade => $maiorProbabilidade];
    }



	public static function inferenciaCorreta($redeMarkov, $historico, $totalFuncaoTransicao) {
        #dd(self::probUmaTransicao($redeMarkov, 5, 5, 2));

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
        #$somaDasProbabilidades = array_sum(array_values($probs));
        #$fatorNormalizacao = 1 / $somaDasProbabilidades;
        $fatorNormalizacao = 1;
        //dd($probs);

        // Aplica o fator nas probabilidades
        array_walk($probs, array('self', 'aplicaFatorNormalizacao'), $fatorNormalizacao);

        /*if(isset($probs[49])) {
        	if(isset($probs[50])) {
        		$probs[50] += $probs[49];
        		unset($probs[49]);
        	} else {
        		$probs[50] = $probs[49];
        		unset($probs[49]);
        	}
        }

        if(isset($probs[51])) {
        	if(isset($probs[50])) {
        		$probs[50] += $probs[51];
        		unset($probs[51]);
        	} else {
        		$probs[50] = $probs[51];
        		unset($probs[51]);
        	}
        }*/

        arsort($probs);

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
