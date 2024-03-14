<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
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
            ->where('active', 1)
            ->where('id_parent', 770)
            ->orWhere('id_category', 770)
            ->get();
        $arrayCategoriesAires = $subcategoriesAires->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAires)
            ->get();
        $arrayCategoriesAiresTwo = $subcategoriesAiresTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesAiresThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 770)
            ->orWhereIn('id_parent', $arrayCategoriesAiresTwo)
            ->get();
        $arrayCategoriesAiresThree = $subcategoriesAiresThree->map(function($item){
            return $item->id_category;
        });

        $aires = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(gfc_order_detail.id_order) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT gfc_orders.id_order ORDER BY gfc_orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesAiresThree)
        ->groupBy('product.id_product')
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
        $subcategoriesCalderasTwo = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderas)
            ->get();
        $arrayCategoriesCalderasTwo = $subcategoriesCalderasTwo->map(function($item){
            return $item->id_category;
        });
        $subcategoriesCalderasThree = DB::connection('presta')->table('category')
            ->select('id_category')
            ->where('active', 1)
            ->where('id_category', 768)
            ->orWhereIn('id_parent', $arrayCategoriesCalderasTwo)
            ->get();
        $arrayCategoriesCalderasThree = $subcategoriesCalderasThree->map(function($item){
            return $item->id_category;
        });

        $calderas = DB::connection('presta')->table('product')
        ->join('product_lang', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'product_lang.id_product');
        })
        ->join('order_detail', function (JoinClause $joinClause) {
            $joinClause->on('product.id_product', '=', 'order_detail.product_id');
        })
        ->join('orders', function (JoinClause $joinClause) use ($start, $end) {
            $joinClause->on('orders.id_order', '=', 'order_detail.id_order');                
        })        
        ->select(
            'product.id_product',
            'product.reference as SKU',
            'order_detail.product_reference',
            'order_detail.product_name as Product_Name_Combination',
            'product_lang.name as Product_Name',
            'product_lang.link_rewrite as url_name',
            DB::raw("count(gfc_order_detail.id_order) as ordered_qty"),
            DB::raw('SUM(gfc_order_detail.product_quantity) as total_products'),
            DB::raw('GROUP_CONCAT(DISTINCT gfc_orders.id_order ORDER BY gfc_orders.id_order  SEPARATOR", ") as orders_ids'),
        )
        ->where('orders.valid', 1)
        ->whereBetween('orders.date_add', [$start, $end])
        ->whereIn('product.id_category_default', $arrayCategoriesCalderasThree)
        ->groupBy('product.id_product')
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
        $competitors = Competitor::with('products')->get();

        $arrayHeads = $competitors->map(function($item){
            return $item->nombre;
        });

        $arrayHeads->prepend('Producto');

        $arrayColumns = $competitors->map(function($item){
            return ['data'  => $item->nombre, 'orderable' => false];
        });

        $arrayColumns->prepend(['data'  => 'nombre']);

        return view("gfc.mon_price", [
            'arrayHeads'=> $arrayHeads,
            'arrayColumns'=> $arrayColumns,
        ]);
    }

    public function datatable() {
        $products = Product::query();

        $competitors = Competitor::with('products')->get();
        $gfcData = Competitor::with('products')->where('nombre', 'LIKE', '%gasfriocalor%')->first();

        $dt = DataTables::eloquent($products)
            ->editColumn('nombre', function (Product $product) use ($gfcData) {
                return view('gfc.products.datatables.nombre', [
                    'url' => $product->competidor()->find($gfcData->id)->pivot->url,
                    'nombre'    => $product->nombre,
                ]);
            });

        foreach ($competitors as $competitor) {
            $dt->addColumn($competitor->nombre, function (Product $product) use ($gfcData, $competitor) {
                return view('gfc.products.datatables.competidor_price', [
                    'gfcData' => $gfcData,
                    'product'    => $product,
                    'competitor'    => $competitor,
                ]);
            });
        }        

        return $dt->toJson();
    }
}
