@extends('adminlte::page')

@section('title', 'Gasfriocalor')

@section('content_header')
    <h1>Nuevo Competidor</h1>
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("gfc.competidors.store") }}" method="post">
                        @csrf
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <x-adminlte-input name="nombre" label="Nombre" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>

                            <x-adminlte-input name="filtro" label="Filtro" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('filtro')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" role="button" href="{{ route("gfc.monprice") }}">Volver</a>
                        </div>
                    </form>

                </div>    
            </div>

        </div>
    </div>

@stop