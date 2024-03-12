@extends('adminlte::page')

@section('title', 'Gasfriocalor | Monitor de Precios')

@section('content_header')
    <div class="row">
        <div class="col-sm-6 col-lg-9">
            <h1>Gasfriocalor.com</h1>
        </div>

        <div class="col-sm-6 col-lg-3">
            <div class="dropdown float-right">
                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuAgregar" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Agregar
                </button>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuBAgregar">
                    <a class="dropdown-item" href="{{ route('gfc.monprice.competidor.create') }}">Competidor</a>
                    <a class="dropdown-item" href="{{ route('gfc.monprice.product.create') }}">Producto</a>
                </div>
            </div>
        </div>
    </div>    
@stop

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Monitor de precios</h2>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                            $heads = [
                                'Producto',
                                'Gasfriocalor',
                                'Climahorro',
                                'Ahorraclima',
                                'Expertclima',
                                'Tucalentadoreconomico',
                            ];

                            $config = [                                
                                'order' => [[0, 'asc']],
                                'ajax'  => route('gfc.datatable.monprice'),
                                'columns'   => [
                                    ['data' => 'nombre'],
                                    ['data' => 'gfc_price'],
                                    ['data' => 'climahorro_price'],
                                    ['data' => 'ahorraclima_price'],
                                    ['data' => 'expertclima_price'],
                                    ['data' => 'tucalentadoreconomico_price'],
                                ]
                            ];
                            @endphp
                            <x-adminlte-datatable id="price-monitor" :heads="$heads" :config="$config" striped hoverable with-buttons>

                            </x-adminlte-datatable>
                        </div>
                    </div>    
                </div>    
            </div>
        </div>
    </div>
@stop

@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)