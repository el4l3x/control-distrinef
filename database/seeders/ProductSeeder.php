<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            "nombre" => "Caldera de gas de condensación Baxi NEODENS LITE 24/24 F",
            "gfc" => "https://www.gasfriocalor.com/caldera-a-gas-de-condensacion-baxi-neodens-lite-2424-f",
            "climahorro" => "https://www.climahorro.es/calderas-gas-condensacion/caldera-de-condensacion-baxi-neodens-lite-2424f-2418.html",
            "ahorraclima" => "https://ahorraclima.es/calderas-de-gas-condensacion/caldera-gas-baxi-neodens-lite-24-f-3175.html",
            "expertclima" => "https://expertclima.es/caldera-gas-baxi-neodens-lite-24-24-f-condensacion.html",
            "tucalentadoreconomico" => "https://tucalentadoreconomico.es/caldera-de-gas/3496-caldera-baxi-neodens-lite-2424-f-8433106222076.html",
        ]);

        Product::create([
            "nombre" => "Caldera de gas Ferroli BLUEHELIX HITECH RRT 28C",
            "gfc" => "https://www.gasfriocalor.com/caldera-a-gas-ferroli-bluehelix-hitech-rrt-28c",
            "climahorro" => "https://www.climahorro.es/calderas-ferroli/caldera-de-gas-ferroli-bluehelix-hitech-rrt-28c-2298.html",
            "ahorraclima" => "https://ahorraclima.es/calderas-de-gas-condensacion/caldera-de-gas-ferroli-bluehelix-hitech-rrt-28-c-3085.html",
            "expertclima" => "https://expertclima.es/ferroli-bluehelix-hitech-rrt-28-c-caldera-gas-condensacion.html",
            "tucalentadoreconomico" => "https://tucalentadoreconomico.es/caldera-condensacion-gas-natural/2292-caldera-ferroli-bluehelix-alpha-24-c.html",
        ]);

        Product::create([
            "nombre" => "Caldera a gas de condensación Ariston Cares S 24",
            "gfc" => "https://www.gasfriocalor.com/caldera-a-gas-de-condensacion-ariston-cares-s-24",
            "climahorro" => "https://www.climahorro.es/calderas-ariston/caldera-de-gas-ariston-cares-s-24-ff-eu2-2396.html",
            "ahorraclima" => "https://ahorraclima.es/calderas/caldera-de-gas-ariston-cares-s-24-ff-eu2-3163.html",
            "expertclima" => "https://expertclima.es/caldera-ariston-cares-premium-evo-24-ff-eu.html",
            "tucalentadoreconomico" => "https://tucalentadoreconomico.es/productos/526-caldera-ariston-cares-s-24.html",
        ]);

        Product::create([
            "nombre" => "Aire Acondicionado Split 1x1 Hisense Brissa 12 CA35YR03",
            "gfc" => "https://www.gasfriocalor.com/aire-acondicionado-split-hisense-brissa-12k-ca35yr03",
            "climahorro" => "https://www.climahorro.es/aire-acondicionado/aire-acondicionado-hisense-brissa-ca35yr03-2517.html",
            "ahorraclima" => "https://ahorraclima.es/aire-acondicionado-1x1-split-/aire-acondicionado-hisense-brissa-ca35yr03-3253.html",
            "expertclima" => "https://expertclima.es/hisense-brissa-ca35yr01-aire-acondicionado-1x1.html",
            "tucalentadoreconomico" => "https://tucalentadoreconomico.es/a-split-1x1/2348-aire-acondicionado-hisense-brissa-ca35yr03-r32-6926597703169.html",
        ]);

        Product::create([
            "nombre" => "Aire Acondicionado Split 1x1 Mitsubishi Electric MSZ-HR35VF",
            "gfc" => "https://www.gasfriocalor.com/aire-acondicionado-split-mitsubishi-electric-msz-hr35vf",
            "climahorro" => "https://www.climahorro.es/mitsubishi/-msz-hr35vf-1602.html",
            "ahorraclima" => "https://ahorraclima.es/aire-acondicionado-1x1-split-/aire-acondicionado-mitsubishi-electric-msz-hr35vf-2396.html",
            "expertclima" => "https://expertclima.es/mitsubishi-electric-msz-hr35vf-aire-acondicionado-1x1.html",
            "tucalentadoreconomico" => "https://tucalentadoreconomico.es/aire-acondicionado/3935-aire-acondicionado-mitsubishi-msz-hr-35-vf-wifi-8851492265994.html",
        ]);
    }
}
