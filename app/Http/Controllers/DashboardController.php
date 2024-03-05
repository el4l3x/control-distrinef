<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function dashboard(Request $request) {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date',
        ]);
 
        if ($validator->fails()) {
            $start = Carbon::yesterday();
            $end = Carbon::now();
        } else {
            $start = $request->date('start'); 
            $end = $request->date('end');
        }

        /* Productos Activos */
        $productsAct = DB::connection('presta')->table('product')->where('active', 1)->count();
        /* Productos Desactivados */
        $productsDes = DB::connection('presta')->table('product')->where('active', 0)->count();
        /* Productos Actualizados Hoy */
        $productsUpd = DB::connection('presta')->table('product')->where('date_upd', now())->count();
        /* Productos existentes en la Distribase */
        $productsDis = DB::connection('presta')->table('product')->count();

        $select = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order')
                ->where('orders.valid', 1)
                ->whereBetween('orders.date_add', [$start, $end]);
        })->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            DB::raw("count(gfc_order_detail.product_id) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
        )->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        /* Monitor de Precios */
        $scrap = collect();
        $web = new \Spekulatius\PHPScraper\PHPScraper;
        $products = [
            'Caldera de gas de condensación Baxi NEODENS LITE 24/24 F'  => [
                'gfc'   => 'https://www.gasfriocalor.com/caldera-a-gas-de-condensacion-baxi-neodens-lite-2424-f',
                'climahorro'   => 'https://www.climahorro.es/calderas-gas-condensacion/caldera-de-condensacion-baxi-neodens-lite-2424f-2418.html',
                'ahorraclima'   => 'https://ahorraclima.es/calderas-de-gas-condensacion/caldera-gas-baxi-neodens-lite-24-f-3175.html',
                'expertclima'   => 'https://expertclima.es/caldera-gas-baxi-neodens-lite-24-24-f-condensacion.html',
                'tucalentadoreconomico'   => 'https://tucalentadoreconomico.es/caldera-de-gas/3496-caldera-baxi-neodens-lite-2424-f-8433106222076.html',
            ],
            'Caldera de gas Ferroli BLUEHELIX HITECH RRT 28C'  => [
                'gfc'   => 'https://www.gasfriocalor.com/caldera-a-gas-ferroli-bluehelix-hitech-rrt-28c',
                'climahorro'   => 'https://www.climahorro.es/calderas-ferroli/caldera-de-gas-ferroli-bluehelix-hitech-rrt-28c-2298.html',
                'ahorraclima'   => 'https://ahorraclima.es/calderas-de-gas-condensacion/caldera-de-gas-ferroli-bluehelix-hitech-rrt-28-c-3085.html',
                'expertclima'   => 'https://expertclima.es/ferroli-bluehelix-hitech-rrt-28-c-caldera-gas-condensacion.html',
                'tucalentadoreconomico'   => 'https://tucalentadoreconomico.es/caldera-condensacion-gas-natural/2292-caldera-ferroli-bluehelix-alpha-24-c.html',
            ],
            'Caldera a gas de condensación Ariston Cares S 24'  => [
                'gfc'   => 'https://www.gasfriocalor.com/caldera-a-gas-de-condensacion-ariston-cares-s-24',
                'climahorro'   => 'https://www.climahorro.es/calderas-ariston/caldera-de-gas-ariston-cares-s-24-ff-eu2-2396.html',
                'ahorraclima'   => 'https://ahorraclima.es/calderas/caldera-de-gas-ariston-cares-s-24-ff-eu2-3163.html',
                'expertclima'   => 'https://expertclima.es/caldera-ariston-cares-premium-evo-24-ff-eu.html',
                'tucalentadoreconomico'   => 'https://tucalentadoreconomico.es/productos/526-caldera-ariston-cares-s-24.html',
            ],
            'Aire Acondicionado Split 1x1 Hisense Brissa 12 CA35YR03'  => [
                'gfc'   => 'https://www.gasfriocalor.com/aire-acondicionado-split-hisense-brissa-12k-ca35yr03',
                'climahorro'   => 'https://www.climahorro.es/aire-acondicionado/aire-acondicionado-hisense-brissa-ca35yr03-2517.html',
                'ahorraclima'   => 'https://ahorraclima.es/aire-acondicionado-1x1-split-/aire-acondicionado-hisense-brissa-ca35yr03-3253.html',
                'expertclima'   => 'https://expertclima.es/hisense-brissa-ca35yr01-aire-acondicionado-1x1.html',
                'tucalentadoreconomico'   => 'https://tucalentadoreconomico.es/a-split-1x1/2348-aire-acondicionado-hisense-brissa-ca35yr03-r32-6926597703169.html',
            ],
            'Aire Acondicionado Split 1x1 Mitsubishi Electric MSZ-HR35VF'  => [
                'gfc'   => 'https://www.gasfriocalor.com/aire-acondicionado-split-mitsubishi-electric-msz-hr35vf',
                'climahorro'   => 'https://www.climahorro.es/mitsubishi/-msz-hr35vf-1602.html',
                'ahorraclima'   => 'https://ahorraclima.es/aire-acondicionado-1x1-split-/aire-acondicionado-mitsubishi-electric-msz-hr35vf-2396.html',
                'expertclima'   => 'https://expertclima.es/mitsubishi-electric-msz-hr35vf-aire-acondicionado-1x1.html',
                'tucalentadoreconomico'   => 'https://tucalentadoreconomico.es/aire-acondicionado/3935-aire-acondicionado-mitsubishi-msz-hr-35-vf-wifi-8851492265994.html',
            ],
        ];

        foreach ($products as $key => $product) {
            $row['nombre'] = $key;
            foreach ($product as $shop => $url) {
                $web->go($url);
                switch ($shop) {
                    case 'ahorraclima':
                        $string = $web->filter("//div[@class='current-price']//span[@class='price']")->text();
                        $string = Str::remove('€', $string);
                        $row[$shop] = floatval(Str::replace(',', '.', $string));
                        break;

                    case 'expertclima':
                        $string = $web->filter("//div[@class='current-price']//span[@class='current-price-value']")->text();
                        $string = Str::remove('€', $string);
                        $row[$shop] = floatval(Str::replace(',', '.', $string));
                        break;
                        
                    case 'tucalentadoreconomico':
                        $string = $web->filter("//div[@class='current-price']//span[@itemprop='price']")->text();
                        $string = Str::remove('€', $string);
                        $row[$shop] = floatval(Str::replace(',', '.', $string));
                        break;
                        
                    case 'gfc':
                        $string = $web->filter("//div[@class='current-price']//span[@class='price_with_tax price_pvp']")->text();
                        $string = Str::remove('€', $string);
                        $row[$shop] = floatval(Str::replace(',', '.', $string));
                        break;
                    
                    default:
                        try {
                            $string = $web->filter("//*[@class='product-price current-price-value']")->text();
                            $string = Str::remove('€', $string);
                            $row[$shop] = floatval(Str::replace(',', '.', $string));
                        } catch (\Throwable $th) {
                            return  "Error en el producto {$url} para la tienda {$shop}: ". $th->getMessage();
                        }
                        break;
                }
            }
            $scrap->push($row);
            unset($row);
        }

        return view("dashboard", [
            "productsAct"   => $productsAct,
            "productsDes"   => $productsDes,
            "productsUpd"   => $productsUpd,
            "productsDis"   => $productsDis,
            "productsMasVendidos" => $select,
            "startDate" => $start->format('d-m-Y'),
            "endDate" => $end->format('d-m-Y'),
            "monitorPrice" => $scrap,
        ]);
    }
}
