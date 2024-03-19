@extends('adminlte::page')

@section('title', 'Gasfriocalor | Dashboard')

@section('content_header')
    <div class="row">
        <div class="col-sm-6 col-lg-9">
            <h1>Gasfriocalor.com</h1>
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
                  Sales
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
                  Sales Graph
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

@section('css')
    
@stop

@section('js')
    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
    $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- ChartJS -->
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('dist/js/pages/dashboard.js') }}"></script>
@stop