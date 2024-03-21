@extends('adminlte::page')

@section('title', 'Perfil')

@section('content_header')
  <div class="row justify-content-between">
    <h1>Perfil del Usuario</h1>
  </div>
@stop

@section('content')
    <div class="card">
        
        <div class="card-body">
            
            <div class="row mb-3">
                <div class="col-md-12">

                    <form action="{{ route("perfil.update") }}" method="post" enctype="multipart/form-data">
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
                            <x-adminlte-input name="nombre" label="Nombre" value="{{ Auth::user()->name }}" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('nombre')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>

                            <x-adminlte-input name="username" label="Usuario" value="{{ Auth::user()->username }}" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('username')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>
                        </div>

                        <div class="row">
                            <x-adminlte-input name="clave" label="Contraseña Actual" type="password" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('clave')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>

                            <x-adminlte-input name="password" label="Nueva Contraseña" type="password" enable-old-support fgroup-class="col-lg-6 col-md-6 col-sm-12"/>
                            <x-slot name="bottomSlot">
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </x-slot>
                        </div>

                        <div class="row mb-3">
                            @php
                            $config = [
                                'allowedFileTypes'  => ['image'],
                                'language'          => 'es',
                                'required'          => false,
                                'browseOnZoneClick' => true,
                                'initialPreview'    => asset('storage/img/users/'.Auth::user()->profile_photo_path),
                                'initialPreviewAsData'  => true,
                            ];
                            @endphp
                            <x-adminlte-input-file-krajee name="image" label="Imagen de Perfil" :config="$config" fgroup-class="col-lg-6 col-md-6 col-sm-12" disable-feedback preset-mode="avatar"/>
                        </div>
                        
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a class="btn btn-secondary" role="button" href="{{ route("perfil") }}">Volver</a>
                        </div>
                    </form>

                </div>    
            </div>

        </div>
    </div>
@stop

@section('plugins.KrajeeFileinput', true)

@section('css')
    
@stop

@section('js')
        
@stop