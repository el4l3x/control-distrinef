@extends('adminlte::page')

@section('title', 'Gasfriocalor | Productos')

@section('content_header')
    <h1>Agregar Producto</h1>
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("gfc.product.store") }}" method="post">
                        @csrf

                        <div class="row">
                            <x-adminlte-input name="nombre" label="Nombre" fgroup-class="col-lg-6 col-md-6 col-sm-12" enable-old-support>
                                <x-slot name="bottomSlot">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>

                            <x-adminlte-input name="url" label="Url en Gasfriocalor" fgroup-class="col-lg-6 col-md-6 col-sm-12" enable-old-support>
                                <x-slot name="bottomSlot">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            <x-adminlte-input name="filtro" label="Filtro en Gasfriocalor" fgroup-class="col-lg-6 col-md-6 col-sm-12" enable-old-support>
                                <x-slot name="bottomSlot">
                                    @error('nombre')
                                        <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </x-slot>
                            </x-adminlte-input>
                        </div>

                        <div class="row">
                            @forelse ($competitors as $competitor)
                                <x-adminlte-input name="{{ $competitor->slug }}-url" label="Url {{ ucwords($competitor->nombre) }}" fgroup-class="col-lg-6 col-md-6 col-sm-12" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @error($competitor->slug)
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </x-slot>
                                </x-adminlte-input>

                                <x-adminlte-input name="{{ $competitor->slug }}-filter" label="Filtro" fgroup-class="col-lg-6 col-md-6 col-sm-12" enable-old-support>
                                    <x-slot name="bottomSlot">
                                        @error($competitor->slug)
                                            <span class="invalid-feedback">{{ $message }}</span>
                                        @enderror
                                    </x-slot>
                                </x-adminlte-input>
                                <hr>
                            @empty
                                <small>No hay competidores registrados aún.</small>
                            @endforelse
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