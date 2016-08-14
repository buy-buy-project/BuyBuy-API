<?php

use Illuminate\Database\Seeder;

class ConsumidorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $consumidores = ['Lucas Lira', 'Lucas Alves', 'Robson'];

        foreach($consumidores as $nomeConsumidor) {
            App\Models\Consumidor::create(['nome' => $nomeConsumidor])->save();
        }
    }
}
