@extends('adminlte::page')

@section('title', 'Consultar Stock y Netos')

@section('content_header')
    <h1>Consultar Stock y Netos</h1>
@stop

@section('content')
    <div class="container" style="max-width: 98%;margin-top:30px;">
		<section>
			<h1 style="text-align: center;">Consultar Stock y/o Neto de Compra</h1>
			<h2 style="text-align: center;">Puedes Buscar por cualquier Campo</h2>
			<div class="demo-html">
				<table id="example" class="display" cellspacing="0" width="100%">
					<thead>
						<tr>
							<th>ID</th>
							<th>MARCA</th>
							<th>NOMBRE PRODUCTO</th>
							<th>REFERENCIA</th>
							<th>DISTRIBASE</th>
							<th>AUNABASE</th>
							<th>SK_MAGS</th>
							<th>SK_ABAD</th>
							<th>SK_CALE</th>
							<th>SK_FERRE</th>
							<th>NET_MAGS</th>
							<th>NET_ABAD</th>
							<th>NET_CALE</th>
							<th>NET_FERRE</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>MARCA</th>
							<th>NOMBRE PRODUCTO</th>
							<th>REFERENCIA</th>
							<th>DISTRIBASE</th>
							<th>AUNABASE</th>
							<th>SK_MAGS</th>
							<th>SK_ABAD</th>
							<th>SK_CALE</th>
							<th>SK_FERRE</th>
							<th>NET_MAGS</th>
							<th>NET_ABAD</th>
							<th>NET_CALE</th>
							<th>NET_FERRE</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</section>
	</div>
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/datetime/1.5.1/css/dataTables.dateTime.min.css">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/Editor-PHP-2.2.2/css/editor.dataTables.min.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/Editor-PHP-2.2.2/examples/resources/syntax/shCore.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('vendor/Editor-PHP-2.2.2/examples/resources/demo.css') }}">
    <style type="text/css">
		#example_filter{margin-bottom:25px;}
	</style>
@stop

@section('js')
    <script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.7.0.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
	<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/datetime/1.5.1/js/dataTables.dateTime.min.js"></script>
	<script type="text/javascript" language="javascript" src="{{ asset('vendor/Editor-PHP-2.2.2/js/dataTables.editor.min.js') }}"></script>
	<script type="text/javascript" language="javascript" src="{{ asset('vendor/Editor-PHP-2.2.2/examples/resources/syntax/shCore.js') }}"></script>
	<script type="text/javascript" language="javascript" src="{{ asset('vendor/Editor-PHP-2.2.2/examples/resources/demo.js') }}"></script>
	<script type="text/javascript" language="javascript" src="{{ asset('vendor/Editor-PHP-2.2.2/examples/resources/editor-demo.js') }}"></script>
    <script type="text/javascript" language="javascript">
		$(document).ready( function () {
            window.CSRF_TOKEN = '{{ csrf_token() }}';
		var editor = new DataTable.Editor({
			ajax: 'datatable/consulta-stock',
			fields: [
				{
					label: 'ID:',
					name: 'gfc_product.id_product'
				},
				{
					label: 'MARCA:',
					name: 'gfc_manufacturer.name'
				},
				{
					label: 'NOMBRE PRODUCTO:',
					name: 'gfc_product_lang.name'
				},
				{
					label: 'REFERENCIA:',
					name: 'gfc_product.reference'
				},
				{
					label: 'DISTRIBASE:',
					name: 'gfc_product.mpn'
				},
				{
					label: 'AUNABASE:',
					name: 'gfc_product.CodAuna'
				},
				{
					label: 'SK_MAGS:',
					name: 'gfc_product.stock_mags'
				},
				{
					label: 'SK_ABAD:',
					name: 'gfc_product.stock_abad'
				},
				{
					label: 'SK_CALE:',
					name: 'gfc_product.stock_cale'
				},
				{
					label: 'SK_FERRE:',
					name: 'gfc_product.stock_ferre'
				},
				{
					label: 'NET_MAGS:',
					name: 'gfc_product.pcompra_mags'
				},
				{
					label: 'NET_ABAD:',
					name: 'gfc_product.pcompra_abad'
				},
				{
					label: 'NET_CALE:',
					name: 'gfc_product.pcompra_cale'
				},
				{
					label: 'NET_FERRE:',
					name: 'gfc_product.pcompra_ferre'
				}
			],
			table: '#example'
		});

		$('#example').DataTable({
			ajax: {
				url: 'datatable/consulta-stock',
				type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': window.CSRF_TOKEN
                },
			},
			columns: [
				{ data: 'gfc_product.id_product' },
				{ data: 'gfc_manufacturer.name' },
				{ data: 'gfc_product_lang.name' },
				{ data: 'gfc_product.reference' },
				{ data: 'gfc_product.mpn' },
				{ data: 'gfc_product.CodAuna' },
				{ data: 'gfc_product.stock_mags' },
				{ data: 'gfc_product.stock_abad' },
				{ data: 'gfc_product.stock_cale' },
				{ data: 'gfc_product.stock_ferre' },
				{ data: 'gfc_product.pcompra_mags' },
				{ data: 'gfc_product.pcompra_abad' },
				{ data: 'gfc_product.pcompra_cale' },
				{ data: 'gfc_product.pcompra_ferre' }
			],
			dom: 'Bfrtip',
			responsive: true,
			pageLength: 50,
			language: {
				url: '//cdn.datatables.net/plug-ins/1.11.5/i18n/es-ES.json'
			}
		});
	});
	</script>
@stop