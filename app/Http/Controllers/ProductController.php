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

            $product = new Product();
            $product->nombre = $request->nombre;
            $product->save();

            foreach ($competitors as $key => $value) {
                if ($request->has('competitor-'.$value->id) && $request->input('competitor-'.$value->id) != null) {
                    try {
                        $web = new PHPScraper();
                        $web->go($request->input('competitor-'.$value->id));
                        $string = $web->filter($value->filtro)->text();
                        $string = Str::remove('€', $string);
                        $string = Str::replace('.', '', $string);
                        $string = Str::replace(',', '.', $string);
                    } catch (\Throwable $th) {
                        $string = null;
                    }

                    $product->competidor()->attach($value->id, [
                        'url'       => $request->input('competitor-'.$value->id),
                        'precio'    => floatval($string),
                    ]);

                    /* Product::updateOrCreate(
                        ['nombre' => $request->nombre, 'competitor_id' => $value->id], 
                        ['url' => $request->input('competitor-'.$value->id), 'precio' => floatval(Str::replace(',', '.', $string))]
                    ); */
                }
            }            

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
            //return $th->getMessage();
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
        try {
            DB::beginTransaction();

            $product->delete();

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
