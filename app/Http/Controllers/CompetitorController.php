<?php

namespace App\Http\Controllers;

use App\Models\Competitor;
use App\Http\Requests\StoreCompetitorRequest;
use App\Http\Requests\UpdateCompetitorRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompetitorController extends Controller
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
    public function store(StoreCompetitorRequest $request)
    {
        try {
            DB::beginTransaction();

            $comuna = new Competitor();
            $comuna->nombre = $request->nombre;
            $comuna->slug = Str::slug($request->nombre);
            $comuna->save();

            DB::commit();

            return redirect()->route('gfc.monprice');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Competitor $competitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Competitor $competitor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompetitorRequest $request, Competitor $competitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Competitor $competitor)
    {
        //
    }
}
