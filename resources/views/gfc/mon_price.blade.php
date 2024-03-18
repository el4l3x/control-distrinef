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
                            $config = [                                
                                'order' => [[0, 'asc']],
                                'ajax'  => route('gfc.datatable.monprice'),
                                'columns'   => $arrayColumns,
                                'language'  => [
                                    'url'   => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json',
                                ],
                            ];
                            @endphp
                            <x-adminlte-datatable id="price-monitor" :heads="$arrayHeads" :config="$config" beautify striped hoverable with-buttons>
                                
                            </x-adminlte-datatable>
                        </div>
                    </div>    
                </div>    
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBorrar" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Pedidos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <form action="" method="post" id="frmDelete">
                <div class="modal-body">
                    <p></p>
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" id="idDel">
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="success" class="btn btn-primary">Continuar</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('plugins.DateRangePicker', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugin', true)

@section('js')
    <script>
        $(() => {

            $('#modalBorrar').on('show.bs.modal', function (event) {
                var button = $(event.relatedTarget)
                var id = button.data('id')
                var nombre = button.data('nombre')
                var modal = $(this)

                console.log(nombre);
                modal.find('.modal-body p').empty()
                modal.find('.modal-body p').append('Esta seguro de dar de baja al producto ' + nombre + '?')
                modal.find('.modal-title').empty()
                modal.find('.modal-title').text('Borrar ' + nombre)
                modal.find('#idDel').val(id)

                $("#frmDelete").attr('action', 'product/'+id)
            });
            
        });

    </script>
@stop