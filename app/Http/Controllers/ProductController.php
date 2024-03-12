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
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            DB::beginTransaction();

            $product = new Product();
            $product->nombre = $request->nombre;
            $product->slug = Str::slug($request->nombre);
            $product->url = $request->url;
            $product->filter = $request->filtro;
            $product->save();

            $competitors = Competitor::select(['nombre', 'id'])->get();

            foreach ($competitors as $key => $competitor) {
                $url = ($request->has(Str::lower($competitor->nombre).'-url')) ? $request->input(Str::lower($competitor->nombre).'-url') : null;
                $filter = ($request->has(Str::lower($competitor->nombre).'-filter')) ? $request->input(Str::lower($competitor->nombre).'-filter') : null;

                if ($url != null && $filter != null) {
                    $competitor->productos()->attach($product->id, [
                        'url'       => $url,
                        'filter'    => $filter,
                    ]);
                }
            }

            DB::commit();

            return redirect(route('gfc.monprice'));
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->withInput()->withErrors([$th->getMessage()]);
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
