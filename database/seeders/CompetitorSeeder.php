<?php

namespace Database\Seeders;

use App\Models\Competitor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompetitorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Competitor::create([
            'nombre'    => 'climahorro',
        ]);

        Competitor::create([
            'nombre'    => 'ahorraclima',
        ]);

        Competitor::create([
            'nombre'    => 'expertclima',
        ]);

        Competitor::create([
            'nombre'    => 'tucalentadoreconomico',
        ]);
    }
}
