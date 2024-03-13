<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\Product;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GfcController extends Controller
{
    public function dashboard() {
        /* Productos Activos */
        $productsAct = DB::connection('presta')->table('product')->where('active', 1)->count();
        /* Productos Desactivados */
        $productsDes = DB::connection('presta')->table('product')->where('active', 0)->count();
        /* Productos Actualizados Hoy */
        $productsUpd = DB::connection('presta')->table('product')->where('date_upd', now())->count();
        /* Productos existentes en la Distribase */
        $productsDis = DB::connection('presta')->table('product')->count();
        
        return view("gfc.dashboard", [
            "productsAct"   => $productsAct,
            "productsDes"   => $productsDes,
            "productsUpd"   => $productsUpd,
            "productsDis"   => $productsDis,
        ]);
    }

    public function bestProducts(Request $request) {
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
            'product_lang.link_rewrite as url_name',
            DB::raw("count(gfc_order_detail.product_id) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
        )->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $subcategoriesAires = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 770)
            ->orWhere('id_category', 770)
            ->get();

        $arrayCategoriesAires = $subcategoriesAires->map(function($item){
            return $item->id_category;
        });

        $aires = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order')
                ->where('orders.valid', 1)
                ->whereBetween('orders.date_add', [$start, $end]);
        })
        ->join('category_product', function (JoinClause $joinClause) use ($arrayCategoriesAires) {
            $joinClause->on('product.id_product', '=', 'category_product.id_product')
                ->whereIn('category_product.id_category', $arrayCategoriesAires);
        })
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(gfc_order_detail.product_id) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
            'category_product.id_category as categoria',
        )->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        $subcategoriesCalderas = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('id_parent', 768)
            ->orWhere('id_category', 768)
            ->get();

        $arrayCategoriesCalderas = $subcategoriesCalderas->map(function($item){
            return $item->id_category;
        });

        $calderas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
                $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order')
                ->where('orders.valid', 1)
                ->whereBetween('orders.date_add', [$start, $end]);
        })
        ->join('category_product', function (JoinClause $joinClause) use ($arrayCategoriesCalderas) {
            $joinClause->on('product.id_product', '=', 'category_product.id_product')
                ->whereIn('category_product.id_category', $arrayCategoriesCalderas);
        })
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(gfc_order_detail.product_id) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
            'category_product.id_category as categoria',
        )->groupBy('product.id_product')
        ->orderBy('total_products', 'DESC')
        ->get();

        return view("gfc.best_products", [
            "productsMasVendidos" => $select,
            "airesMasVendidos"  =>  $aires,
            "calderasMasVendidos"  =>  $calderas,
            "startDate" => $start->format('d/m/Y'),
            "endDate" => $end->format('d/m/Y'),
        ]);
    }

    public function monPrice() {
        $products = Product::with('competidor')->get();
        $competitors = Competitor::with('products')->get();
        $gfcData = Competitor::with('products')->where('nombre', 'LIKE', '%gasfriocalor%')->first();

        $arrayCompetitors = $competitors->map(function($item){
            return $item->nombre;
        });

        $arrayCompetitors->prepend('Producto');

        /* return $gfcData; */

        return view("gfc.mon_price", [
            'products'  => $products,
            'competitors'=> $arrayCompetitors,
            'gfcData'=> $gfcData,
        ]);
    }

    public function datatable() {
        $products = Product::query();

        return DataTables::eloquent($products)
            ->editColumn('nombre', function (Product $product) {
                return view('gfc.products.datatables.nombre', [
                    'url' => $product->gfc,
                    'nombre'    => $product->nombre,
                ]);
            })
            ->editColumn('gfc_price', '{{ number_format($gfc_price, 2, ",", ".") }} €')
            ->editColumn('climahorro_price', function (Product $product) {
                return view('gfc.products.datatables.competidor_price', [
                    'competidor_price' => $product->climahorro_price,
                    'competidor_percent'    => $product->climahorro_percent,
                ]);
            })
            ->editColumn('ahorraclima_price', function (Product $product) {
                return view('gfc.products.datatables.competidor_price', [
                    'competidor_price' => $product->ahorraclima_price,
                    'competidor_percent'    => $product->ahorraclima_percent,
                ]);
            })
            ->editColumn('expertclima_price', function (Product $product) {
                return view('gfc.products.datatables.competidor_price', [
                    'competidor_price' => $product->expertclima_price,
                    'competidor_percent'    => $product->expertclima_percent,
                ]);
            })
            ->editColumn('tucalentadoreconomico_price', function (Product $product) {
                return view('gfc.products.datatables.competidor_price', [
                    'competidor_price' => $product->tucalentadoreconomico_price,
                    'competidor_percent'    => $product->tucalentadoreconomico_percent,
                ]);
            })
            ->toJson();
    }
}
