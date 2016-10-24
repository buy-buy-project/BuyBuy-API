<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\Markov;
use App\Helpers\Bayes;

use Khill\Lavacharts\Lavacharts;

use Log;

class ExperimentosController extends Controller
{
    public function experimento1() {
        //$servidor = 'http://104.236.111.86:8081/experimento1/14';
        $servidor = 'http://localhost:4000/experimento1/14';
        $context = stream_context_create(array(
            'http' => array(
                'method' => 'GET',
                'header' => "Connection: close\r\n".
                            "Content-type: application/json\r\n",
            )
        ));
        // Realize comunicação com o servidor
        $compras = file_get_contents($servidor, null, $context);

        $markov = Markov::aprendizagem(null, null, $compras);
        //Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
        //Bayes::inferenciaNova($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
        Bayes::inferenciaCorreta($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
    }

    /*public function experimento2() {
        set_time_limit(0);
        //$ruidos = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2];
        //$ruidos = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4, 3.7, 4.0, 4.3, 4.6, 4.9, 5.2, 5.5, 5.8];
        $ruidos = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4];

        $totalAcertoRuido = [];
        foreach ($ruidos as $r) {
            $totalAcertoRuido[strval($r)] = 0;
        }

        #echo '{"resultados": {';
        foreach ($ruidos as $ruido) {
            Log::info('ruido ' . $ruido);
            for($k = 1; $k <= 100; $k++) {
                $servidor = 'http://localhost:8081/experimento2/1/'.$ruido.'/10';
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'GET',
                        'header' => "Connection: close\r\n".
                                    "Content-type: application/json\r\n",
                    )
                ));
                // Realize comunicação com o servidor
                $compras = file_get_contents($servidor, null, $context);

                $markov = Markov::aprendizagem(null, null, $compras);
                //$probabilidade = Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                //$probabilidade = Bayes::inferenciaNova($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                $probabilidade = Bayes::inferenciaCorreta($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                $quantidadeCalculada = key($probabilidade);
                Log::info('quantidadeCalculada ' . $quantidadeCalculada);
                if($quantidadeCalculada == 50) {
                    $totalAcertoRuido[strval($ruido)]++;
                }
            }
            Log::info('Total de acerto do ruido ' . $totalAcertoRuido[strval($ruido)]);
            #echo 'ruido '.$ruido.' Total de acerto do ruido ' . $totalAcertoRuido[strval($ruido)] .'<br>';
        }
        $json_string = json_encode(array_values($markov['historico']), JSON_PRETTY_PRINT);
        #echo '"historico": '.$json_string.'}}';
        //die;
        //echo '<pre>'; print_r($totalAcertoRuido); echo '</pre>';

        $lava = new Lavacharts;

        $votes  = $lava->DataTable();

        $votes->addStringColumn('Ruido')
              ->addNumberColumn('Acertos');

        foreach ($ruidos as $r) {
            $votes->addRow([strval($r), $totalAcertoRuido[strval($r)]]);
        }

        $lava->ColumnChart('Acertos', $votes, ['vAxis' => ['minValue' => 0]]);

        echo '<div id="grafico"></div>';
        echo $lava->render('ColumnChart', 'Acertos', 'grafico');
    }*/

    public function experimento2() {
        set_time_limit(0);
        $ruidos = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4];
        //$ruidos = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2];
        //$ruidos = [0.1, 0.3, 0.5, 0.7, 0.9, 1.1, 1.3, 1.5, 1.7, 1.9, 2.1];

        $totalErroRuido = [];
        foreach ($ruidos as $r) {
            $totalErroRuido[strval($r)] = 0;
        }

        $lava = new Lavacharts;

        $grafico = $lava->DataTable();
        $grafico->addStringColumn('Ruído')
                ->addNumberColumn('% Acerto');

        $maiorDif = 0;
        $maiorDifTeste = 50;
        foreach ($ruidos as $ruido) {
            Log::info('ruido ' . $ruido);
            
            $erro = 0;
            for($k = 1; $k <= 100; $k++) {
                $servidor = 'http://localhost:8081/experimento2/1/'.$ruido.'/5';
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'GET',
                        'header' => "Connection: close\r\n".
                                    "Content-type: application/json\r\n",
                    )
                ));
                // Realize comunicação com o servidor
                $compras = file_get_contents($servidor, null, $context);

                $markov = Markov::aprendizagem(null, null, $compras);
                $probabilidade = Bayes::inferenciaCorreta($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                $quantidadeCalculada = key($probabilidade);

                $erro += abs($quantidadeCalculada-50);

                // grafico ruido
                //$grafico->addRow([$k, abs($quantidadeCalculada-50)]);

                if(abs($quantidadeCalculada-50) > $maiorDif) {
                    $maiorDifTeste = $quantidadeCalculada;
                    $maiorDif = abs($quantidadeCalculada-50);
                }
            }

            $totalErroRuido[strval($ruido)] = $erro;
            
            // grafico ruido
            /*$lava->LineChart('Acertos', $grafico, ['vAxis' => ['minValue' => 0]]);
            echo '<div id="grafico"></div>';
            echo $lava->render('LineChart', 'Acertos', 'grafico');
            die();*/          
        }

        $maiorDif = ($maiorDif == 0) ? 1 : $maiorDif;

        foreach ($ruidos as $r) {
            $erro = $totalErroRuido[strval($r)];
            $notaFinalAcerto = 1 - ($erro / (100 * $maiorDif));
            $grafico->addRow([$r, ($notaFinalAcerto*100)]);
        }

        $lava->LineChart('Acertos', $grafico, [
                'vAxis' => ['minValue' => 0],
                'title' => 'Permutação de Quantidade de Compra',
                'hAxis' => ['title' => 'Ruído'],
                'vAxis' => ['title' => 'Porcentagem de Acerto'],
                'height' => 500
            ]
        );
        echo '<div id="grafico"></div>';
        echo $lava->render('LineChart', 'Acertos', 'grafico');

    }

    public function experimento3() {
        set_time_limit(0);
        $ruidos = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4];

        $totalErroRuido = [];
        foreach ($ruidos as $r) {
            $totalErroRuido[strval($r)] = 0;
        }

        $lava = new Lavacharts;

        $grafico = $lava->DataTable();
        $grafico->addStringColumn('Ruído')
                ->addNumberColumn('% Acerto');

        $maiorDif = 0;
        foreach ($ruidos as $ruido) {
            //Log::info('ruido ' . $ruido);
            $erro = 0;
            for($k = 1; $k <= 100; $k++) {
                $servidor = 'http://localhost:8081/experimento3/1/'.$ruido.'/10';
                $context = stream_context_create(array(
                    'http' => array(
                        'method' => 'GET',
                        'header' => "Connection: close\r\n".
                                    "Content-type: application/json\r\n",
                    )
                ));
                // Realize comunicação com o servidor
                $compras = file_get_contents($servidor, null, $context);

                $markov = Markov::aprendizagem(null, null, $compras);
                $probabilidade = Bayes::inferenciaCorreta($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                $quantidadeCalculada = key($probabilidade);

                $erro += abs($quantidadeCalculada-50);

                // grafico ruido
                //$grafico->addRow([$k, abs($quantidadeCalculada-50)]);

                if(abs($quantidadeCalculada-50) > $maiorDif) {
                    $maiorDif = abs($quantidadeCalculada-50);
                }
            }

            $totalErroRuido[strval($ruido)] = $erro;
            
            // grafico ruido
            /*$lava->LineChart('Acertos', $grafico, ['vAxis' => ['minValue' => 0]]);
            echo '<div id="grafico"></div>';
            echo $lava->render('LineChart', 'Acertos', 'grafico');
            die();*/
        }

        $maiorDif = ($maiorDif == 0) ? 1 : $maiorDif;

        foreach ($ruidos as $r) {
            $erro = $totalErroRuido[strval($r)];
            $notaFinalAcerto = 1 - ($erro / (100 * $maiorDif));
            $grafico->addRow([$r, ($notaFinalAcerto*100)]);
        }

        $lava->LineChart('Acertos', $grafico, [
                'vAxis' => ['minValue' => 0],
                'title' => 'Permutação de Intervalos de Compra',
                'hAxis' => ['title' => 'Ruído'],
                'vAxis' => ['title' => 'Porcentagem de Acerto'],
                'height' => 500
            ]
        );
        echo '<div id="grafico"></div>';
        echo $lava->render('LineChart', 'Acertos', 'grafico');
    }

    public function experimento4() {
        set_time_limit(0);
        $ruidosTempo = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4];
        $ruidosTempo = [0.1, 0.4, 0.7, 1.0, 1.3];
        $ruidosQuantidade = [0.1, 0.4, 0.7, 1.0, 1.3, 1.6, 1.9, 2.2, 2.5, 2.8, 3.1, 3.4];

        $i = 1;
        foreach ($ruidosTempo as $ruidoT) {

            $totalErroRuido = [];
            foreach ($ruidosQuantidade as $r) {
                $totalErroRuido[strval($r)] = 0;
            }

            $maiorDif = 0;
            foreach ($ruidosQuantidade as $ruidoQ) {
            
                $erro = 0;
                for($k = 1; $k <= 100; $k++) {
                    $servidor = 'http://localhost:8081/experimento4/1/'.$ruidoQ.'/'.$ruidoT.'/10';
                    $context = stream_context_create(array(
                        'http' => array(
                            'method' => 'GET',
                            'header' => "Connection: close\r\n".
                                        "Content-type: application/json\r\n",
                        )
                    ));
                    // Realize comunicação com o servidor
                    $compras = file_get_contents($servidor, null, $context);

                    $markov = Markov::aprendizagem(null, null, $compras);
                    $probabilidade = Bayes::inferenciaCorreta($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                    $quantidadeCalculada = key($probabilidade);

                    $erro += abs($quantidadeCalculada-50);

                    // grafico ruido
                    //$grafico->addRow([$k, abs($quantidadeCalculada-50)]);

                    if(abs($quantidadeCalculada-50) > $maiorDif) {
                        $maiorDif = abs($quantidadeCalculada-50);
                    }
                }

                $totalErroRuido[strval($ruidoQ)] = $erro;
            
                // grafico ruido
                /*$lava->LineChart('Acertos', $grafico, ['vAxis' => ['minValue' => 0]]);
                echo '<div id="grafico"></div>';
                echo $lava->render('LineChart', 'Acertos', 'grafico');
                die();*/

            }

            $lava = new Lavacharts;

            $grafico = $lava->DataTable();
            $grafico->addStringColumn('Ruído')
                    ->addNumberColumn('% Acerto');

            $maiorDif = ($maiorDif == 0) ? 1 : $maiorDif;

            foreach ($ruidosQuantidade as $r) {
                $erro = $totalErroRuido[strval($r)];
                $notaFinalAcerto = 1 - ($erro / (100 * $maiorDif));
                $grafico->addRow([$r, ($notaFinalAcerto*100)]);
            }

            $lava->LineChart('Acertos', $grafico, [
                    'vAxis' => ['minValue' => 0],
                    'title' => 'Permutação de Quantidade e Intervalos de Compra (ruído de tempo: '.$ruidoT.') ',
                    'hAxis' => ['title' => 'Ruído'],
                    'vAxis' => ['title' => 'Porcentagem de Acerto'],
                    'height' => 500
                ]
            );

            echo '<div id="grafico_'.$i.'"></div>';
            echo $lava->render('LineChart', 'Acertos', 'grafico_'.$i);
            echo '<div style="clear: both;"></div>';
            
            $i++;
        }

    }
}

