@extends('adminlte::page')

@section('title', 'Gasfriocalor | Competidores')

@section('content_header')
    <h1>Crear Competidor</h1>
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("gfc.competidor.store") }}" method="post">
                        @csrf
                        <div class="form-group">
                            <x-adminlte-input name="nombre" label="Nombre" placeholder="" enable-old-support>
                                <x-slot name="bottomSlot">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
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