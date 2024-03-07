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
            {{-- Productos Actualizados Hoy --}}
            <x-adminlte-small-box title="{{ $productsUpd }}" text="Productos Actualizados" icon="fas fa-shopping-bag" theme="success" url="#" url-text="Mas Información"/>
        </div>

        <div class="col-md-3">
            {{-- Productos Existentes en la Distribase --}}            
            <x-adminlte-small-box title="{{ $productsDis }}" text="Productos de la Distribase" icon="fas fa-shopping-bag" theme="indigo" url="#" url-text="Mas Información"/>
        </div>
    </div>
@stop