@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row">
        <div class="col-sm-6 col-lg-9">
            <h1>Gasfriocalor.com</h1>
        </div>
        <div class="col-sm-6 col-lg-3" align="right">
            <form action="{{ route('dashboard.dates') }}" method="post" id="frmDateRange">
                @csrf
                <input type="hidden" id="start" name="start">
                <input type="hidden" id="end" name="end">
                <div class="form-group">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <div class="input-group-text bg-dark">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input id="range-date" class="form-control" name="range-date">
                    </div>
                </div>
            </form>

        </div>
    </div>    
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            {{-- Productos Activos Hoy --}}
            <x-adminlte-small-box title="{{ $productsAct }}" text="Productos Activos" icon="fas fa-shopping-bag" theme="info" url="#" url-text="Mas Información"/>
        </div>
        
        <div class="col-md-3">
            {{-- Productos Desactivados Hoy --}}
            <x-adminlte-small-box title="{{ $productsDes }}" text="Productos Desactivados" icon="fas fa-shopping-bag" theme="warning" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos Actualizados Hoy --}}
            <x-adminlte-small-box title="{{ $productsUpd }}" text="Productos Actualizados" icon="fas fa-shopping-bag" theme="success" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos Existentes en la Distribase --}}            
            <x-adminlte-small-box title="{{ $productsDis }}" text="Productos de la Distribase" icon="fas fa-shopping-bag" theme="indigo" url="#" url-text="Mas Información"/>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between align-items-center">
                                <h2 class="card-title">Productos Mas Vendidos</h2>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                            $heads = [
                                'Reference',
                                'Nombre',
                                ['label' => 'Ordenes'],
                                ['label' => 'Cantidad'],
                            ];

                            $config = [                                
                                'order' => [[1, 'asc']],
                                'columns' => [null, null, null, null],
                            ];
                            @endphp
                            <x-adminlte-datatable id="table7" :heads="$heads" :config="$config" striped hoverable with-buttons>
                                @foreach ($productsMasVendidos as $row)
                                    <tr>
                                        <td>{{ $row->SKU }}</td>
                                        <td>{{ $row->Product_Name }}</td>
                                        <td>{{ $row->ordered_qty }}</td>
                                        <td>{{ $row->total_products }}</td>
                                    </tr>
                                @endforeach 
                            </x-adminlte-datatable>
                        </div>
                    </div>    
                </div>    
            </div>

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
                                @foreach ($monitorPrice as $row)
                                    <tr>
                                        <td>{{ $row['nombre'] }}</td>
                                        <td>{{ $row['gfc'] }}</td>
                                        <td>{{ $row['climahorro'] }}</td>
                                        <td>{{ $row['ahorraclima'] }}</td>
                                        <td>{{ $row['expertclima'] }}</td>
                                        <td>{{ $row['tucalentadoreconomico'] }}</td>
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

@section('css')
    
@stop

@section('js')
    <script>
        $(() => {
            const defaultRanges = {
                'Hoy': [
                    moment().startOf('day'),
                    moment().endOf('day')
                ],
                'Ayer': [
                    moment().subtract(1, 'days').startOf('day'),
                    moment().subtract(1, 'days').endOf('day')
                ],
                'Ultimos 7 Dias': [
                    moment().subtract(6, 'days'),
                    moment()
                ],
                'Ultimos 30 Dias': [
                    moment().subtract(29, 'days'),
                    moment()
                ],
                'Este Mes': [
                    moment().startOf('month'),
                    moment().endOf('month')
                ],
                'Ultimo Mes': [
                    moment().subtract(1, 'month').startOf('month'),
                    moment().subtract(1, 'month').endOf('month')
                ],
                'Test': [
                    moment().subtract(25, 'years'),
                    moment().endOf('day')
                ]
            }

            const startDate = @json($startDate);
            const endDate = @json($endDate);

            $('#range-date').daterangepicker({
                opens: 'left',
                ranges: defaultRanges,
                alwaysShowCalendars: true,
                startDate: startDate, 
                endDate: endDate,
            }, function(start, end, label) {
                $("input#start").val(start);
                $("input#end").val(end);
                $('form#frmDateRange').submit();
            });
        })
    </script>
@stop