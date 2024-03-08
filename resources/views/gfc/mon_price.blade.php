@extends('adminlte::page')

@section('title', 'Gasfriocalor | Monitor de Precios')

@section('content_header')
    <div class="row">
        <div class="col-sm-6 col-lg-9">
            <h1>Gasfriocalor.com</h1>
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
                            ];
                            @endphp
                            <x-adminlte-datatable id="price-monitor" :heads="$heads" :config="$config" striped hoverable with-buttons>
                                @foreach ($products as $row)
                                    <tr>
                                        <td>{{ $row['nombre'] }}</td>
                                        <td>{{ number_format($row['gfc_price'], 2) }} €</td>
                                        <td>
                                            {{ number_format($row['climahorro_price'], 2) }} € 
                                            <span @class([
                                                'badge',
                                                'badge-success' => $row->climahorro_percent > 0,
                                                'badge-danger' => $row->climahorro_percent < 0,
                                            ])>
                                                {{ $row->climahorro_percent }}%
                                            </span>
                                        </td>
                                        <td>
                                            {{ number_format($row['ahorraclima_price'], 2) }} €
                                            <span @class([
                                                'badge',
                                                'badge-success' => $row->ahorraclima_percent > 0,
                                                'badge-danger' => $row->ahorraclima_percent < 0,
                                            ])>
                                                {{ $row->ahorraclima_percent }}%
                                            </span>
                                        </td>
                                        <td>
                                            {{ number_format($row['expertclima_price'], 2) }} €
                                            <span @class([
                                                'badge',
                                                'badge-success' => $row->expertclima_percent > 0,
                                                'badge-danger' => $row->expertclima_percent < 0,
                                            ])>
                                                {{ $row->expertclima_percent }}%
                                            </span>
                                        </td>
                                        <td>
                                            {{ number_format($row['tucalentadoreconomico_price'], 2) }} €
                                            <span @class([
                                                'badge',
                                                'badge-success' => $row->tucalentadoreconomico_percent > 0,
                                                'badge-danger' => $row->tucalentadoreconomico_percent < 0,
                                            ])>
                                                {{ $row->tucalentadoreconomico_percent }}%
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach 
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