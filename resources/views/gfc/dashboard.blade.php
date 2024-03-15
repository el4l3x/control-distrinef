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
@stop