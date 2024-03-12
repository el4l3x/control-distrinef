<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Competitor;
use Illuminate\Support\Facades\DB;
use Spekulatius\PHPScraper\PHPScraper;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $competitors = Competitor::all();

        return view('gfc.products.create', compact('competitors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $competitors = Competitor::all();

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    $web = new PHPScraper();
                    $web->go($request->input('competitor-'.$value->id));
                    $string = $web->filter($value->filtro)->text();
                    $string = Str::remove('€', $string);                    

                    $product = new Product();
                    $product->nombre = $request->nombre;
                    $product->url = $request->input('competitor-'.$value->id);
                    $product->precio = floatval(Str::replace(',', '.', $string));
                    $product->competitor_id = $value->id;
                    $product->save();
                }
            }            

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return $th->getMessage();
            /* return redirect()->route('gfc.monprice'); */
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
