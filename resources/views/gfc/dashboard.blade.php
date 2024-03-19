@extends('adminlte::page')

@section('title', 'Gasfriocalor | Dashboard')

@section('content_header')
  <div class="row justify-content-between">
    <h1>Gasfriocalor.com</h1>

    <form action="{{ route('gfc.dashboards.dates') }}" method="post" id="frmDateRange" class="form-inline">
      @csrf
      <input type="hidden" id="start" name="start">
      <input type="hidden" id="end" name="end">
      <div class="form-group">
        <label for="range-date" style="margin-right: 5px">Desde - Hasta</label>
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
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            {{-- Pedidos Entrados --}}
            <x-adminlte-small-box title="{{ $pedidosEntrados }}" text="Pedidos Entrados" icon="far fa-chart-bar" theme="info" url="#" url-text="Mas Información"/>
        </div>
        
        <div class="col-md-3">
            {{-- Importe Facturado --}}
            <x-adminlte-small-box title="{{ $importeFacturado }}" text="Importe Facturado" icon="far fa-chart-bar" theme="warning" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Carritos Totales --}}
            <x-adminlte-small-box title="{{ $carritosTotales }}" text="Carritos Totales" icon="fas fa-shopping-cart" theme="success" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Carritos Clientes --}}            
            <x-adminlte-small-box title="{{ $carritosClientes }}" text="Carritos Clientes" icon="fas fa-shopping-cart" theme="indigo" url="#" url-text="Mas Información"/>
        </div>
    </div>

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
            {{-- Combinaciones Activas --}}
            <x-adminlte-small-box title="{{ $productsUpd }}" text="Combinaciones Activas" icon="fas fa-shopping-bag" theme="success" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos Productos Nunca Vendidos --}}            
            <x-adminlte-small-box title="16.765*" text="Productos Nunca Vendidos" icon="fas fa-shopping-bag" theme="indigo" url="#" url-text="Mas Información"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            {{-- Matcheados con Distribase --}}
            <x-adminlte-small-box title="12.355*" text="Matcheados con Distribase" icon="fas fa-shopping-bag" theme="olive" url="#" url-text="Mas Información"/>
        </div>
        
        <div class="col-md-3">
            {{-- NO Matcheados con Distribase --}}
            <x-adminlte-small-box title="10.325*" text="NO Matcheados con Distribase" icon="fas fa-shopping-bag" theme="orange" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos con Stock en CSV --}}
            <x-adminlte-small-box title="6.780*" text="Productos con Stock en CSV" icon="fas fa-shopping-bag" theme="maroon" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos con Neto en CSV --}}            
            <x-adminlte-small-box title="5.890*" text="Productos con Neto en CSV" icon="fas fa-shopping-bag" theme="purple" url="#" url-text="Mas Información"/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">
                  <i class="fas fa-chart-pie mr-1"></i>
                  Pedidos
                </h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">
                    <li class="nav-item">
                      <a class="nav-link active" href="#revenue-chart" data-toggle="tab">Area</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" href="#sales-chart" data-toggle="tab">Donut</a>
                    </li>
                  </ul>
                </div>
              </div><!-- /.card-header -->
              <div class="card-body">
                <div class="tab-content p-0">
                  <!-- Morris chart - Sales -->
                  <div class="chart tab-pane active" id="revenue-chart"
                       style="position: relative; height: 300px;">
                      <canvas id="revenue-chart-canvas" height="300" style="height: 300px;"></canvas>
                   </div>
                  <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 300px;">
                    <canvas id="sales-chart-canvas" height="300" style="height: 300px;"></canvas>
                  </div>
                </div>
              </div><!-- /.card-body -->
            </div>
        </div>

        <div class="col-md-6">
            <div class="card bg-gradient-info">
              <div class="card-header border-0">
                <h3 class="card-title">
                  <i class="fas fa-th mr-1"></i>
                  Facturación
                </h3>

                <div class="card-tools">
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn bg-info btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <canvas class="chart" id="line-chart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
              </div>
              <!-- /.card-body -->
              <div class="card-footer bg-transparent">
                <div class="row">
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="20" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Mail-Orders</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="50" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">Online</div>
                  </div>
                  <!-- ./col -->
                  <div class="col-4 text-center">
                    <input type="text" class="knob" data-readonly="true" value="30" data-width="60" data-height="60"
                           data-fgColor="#39CCCC">

                    <div class="text-white">In-Store</div>
                  </div>
                  <!-- ./col -->
                </div>
                <!-- /.row -->
              </div>
              <!-- /.card-footer -->
            </div>
        </div>
    </div>
@stop

@section('plugins.DateRangePicker', true)

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
        }

        const startDate = @json($startDate);
        const endDate = @json($endDate);

        moment.locale('es');

        $('#range-date').daterangepicker({
            opens: 'left',
            ranges: defaultRanges,
            alwaysShowCalendars: true,
            startDate: startDate, 
            endDate: endDate,
            locale: {
                format: "DD/MM/YYYY"
            }
        }, function(start, end, label) {
            $("input#start").val(start);
            $("input#end").val(end);
            $('form#frmDateRange').submit();
        });        
      });
    </script>

    
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>

    {{-- <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    
    
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script> --}}
    
@stop