<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Helpers\Markov;
use App\Helpers\Bayes;

use Khill\Lavacharts\Lavacharts;

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
//        $comprasIntervalo3 = '[{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-09-01"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-31"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-30"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-29"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-28"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-27"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-26"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-25"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-24"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-23"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-22"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-21"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-20"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-19"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-18"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-17"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-16"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-15"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-14"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-13"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-12"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-11"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-10"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-09"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-08"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-07"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-06"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-05"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-04"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-08-03"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-02"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-08-01"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-31"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-30"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-29"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-28"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-27"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-26"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-25"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-24"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-23"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-22"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-21"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-20"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-19"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-18"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-17"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-16"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-15"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-14"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-13"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-12"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-11"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-10"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-09"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-08"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-07"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-06"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-05"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-04"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-03"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-07-02"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-07-01"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-30"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-29"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-28"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-27"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-26"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-25"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-24"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-23"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-22"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-21"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-20"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-19"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-18"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-17"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-16"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-15"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-14"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-13"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-12"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-11"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-10"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-09"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-08"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-07"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-06"},{"consumidor":1,"produto_id":7,"quantidade":0,"data_lista":"2016-06-05"},{"consumidor":1,"produto_id":7,"quantidade":5,"data_lista":"2016-06-06"}]';
//        $compras = $comprasIntervalo3;

        $markov = Markov::aprendizagem(null, null, $compras);
        //Bayes::inferencia($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
        //Bayes::inferenciaNova($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
        Bayes::inferenciaGuilherme($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
    }

    public function experimento2() {
        set_time_limit(0);
        $ruidos = [0.1, 0.2, 0.3, 0.4, 0.5, 0.6, 0.7, 0.8, 0.9, 1, 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7, 1.8, 1.9, 2];
        //$ruidos = [1];

        $totalAcertoRuido = [
            0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0,
            5 => 0, 6 => 0, 7 => 0, 8 => 0, 9 => 0,
            10 => 0, 11 => 0, 12 => 0, 13 => 0, 14 => 0,
            15 => 0, 16 => 0, 17 => 0, 18 => 0, 19 => 0
        ];
        $i = 0;
    
        foreach ($ruidos as $ruido) {
            for($k = 1; $k <= 100; $k++) {
                //$servidor = 'http://104.236.111.86:4000/experimento2/'.$k.'/'.$ruido;
                $servidor = 'http://localhost:4000/experimento2/1/'.$ruido;
                //echo 'k ' . $k . '-> ruido ' . $ruido . "<br>";
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
                $probabilidade = Bayes::inferenciaGuilherme($markov['rede'], $markov['historico'], $markov['totalPorTransicao']);
                $quantidadeCalculada = key($probabilidade);
                if($quantidadeCalculada == 15) {
                    $totalAcertoRuido[$i]++;
                }
            }
            $i++;
        }

        //dd($totalAcertoRuido);

        $lava = new Lavacharts;

        $votes  = $lava->DataTable();

        $votes->addStringColumn('Ruido')
              ->addNumberColumn('Acertos')
              ->addRow(['0.1', $totalAcertoRuido[0]])
              ->addRow(['0.2', $totalAcertoRuido[1]])
              ->addRow(['0.3', $totalAcertoRuido[2]])
              ->addRow(['0.4', $totalAcertoRuido[3]])
              ->addRow(['0.5', $totalAcertoRuido[4]])
              ->addRow(['0.6', $totalAcertoRuido[5]])
              ->addRow(['0.7', $totalAcertoRuido[6]])
              ->addRow(['0.8', $totalAcertoRuido[7]])
              ->addRow(['0.9', $totalAcertoRuido[8]])
              ->addRow(['1', $totalAcertoRuido[9]])
              ->addRow(['1.1', $totalAcertoRuido[10]])
              ->addRow(['1.2', $totalAcertoRuido[11]])
              ->addRow(['1.3', $totalAcertoRuido[12]])
              ->addRow(['1.4', $totalAcertoRuido[13]])
              ->addRow(['1.5', $totalAcertoRuido[14]])
              ->addRow(['1.6', $totalAcertoRuido[15]])
              ->addRow(['1.7', $totalAcertoRuido[16]])
              ->addRow(['1.8', $totalAcertoRuido[17]])
              ->addRow(['1.9', $totalAcertoRuido[18]])
              ->addRow(['2', $totalAcertoRuido[19]]);

        $lava->BarChart('Acertos', $votes);

        echo '<div id="grafico"></div>';
        echo $lava->render('BarChart', 'Acertos', 'grafico');
    }
}

