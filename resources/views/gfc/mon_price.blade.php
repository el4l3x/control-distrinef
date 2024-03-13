@extends('adminlte::page')

@section('title', 'Gasfriocalor | Monitor de Precios')

@section('content_header')
    <div class="row justify-content-between">
        <h1>Gasfriocalor.com</h1>    

        <div class="dropdown">
            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Agregar
            </button>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{ route('gfc.competidors.create') }}">Competidor</a>
                <a class="dropdown-item" href="{{ route('gfc.products.create') }}">Producto</a>
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
                            $heads = $competitors;

                            $config = [                                
                                'order' => [[0, 'asc']],
                                /* 'ajax'  => route('gfc.datatable.monprice'), */
                                /* 'columns'   => [
                                    ['data' => 'nombre'],
                                    ['data' => 'gfc_price'],
                                    ['data' => 'climahorro_price'],
                                    ['data' => 'ahorraclima_price'],
                                    ['data' => 'expertclima_price'],
                                    ['data' => 'tucalentadoreconomico_price'],
                                ] */
                            ];
                            @endphp
                            <x-adminlte-datatable id="price-monitor" :heads="$heads" :config="$config" striped hoverable with-buttons>
                                @foreach ($products as $item)
                                    <tr>
                                        <td>{{ $item->nombre }}</td>
                                        @php
                                            $gfc_price = $gfcData->products()->find($item->id)->pivot->precio
                                        @endphp
                                        @for ($i = 0; $i < $competitors->count()-1; $i++)
                                            @if (isset($item->competidor[$i]))
                                                @php
                                                    $percent = number_format((((($gfc_price - $item->competidor[$i]->pivot->precio)/$gfc_price))*100)*-1, 2);
                                                @endphp
                                                <td>
                                                    {{ number_format($item->competidor[$i]->pivot->precio, 2, ",", ".") }} €
                                                    @if (Str::lower($item->competidor[$i]->nombre) != 'gasfriocalor')
                                                        <span @class([
                                                            'badge',
                                                            'badge-success' => $percent > 0,
                                                            'badge-danger' => $percent < 0,
                                                        ])>
                                                            {{ $percent }}%
                                                        </span>
                                                    @endif
                                                </td>
                                            @else
                                                <td>
                                                    
                                                </td>
                                            @endif                                            
                                        @endfor
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