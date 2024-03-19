@php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
$link = mysqli_connect('localhost','user_presta','&a0aY3m0');
/* $link = mysqli_connect('localhost','root',''); */
if (!$link) {
    die('Could not connect: ' . mysqli_error($link));
}

mysqli_select_db($link,'presta17test');

mysqli_query($link,"SET NAMES utf8");    
@endphp

@extends('adminlte::page')

@section('title', 'Herramienta para Desbloquear Pedidos - Gasfriocalor')

@section('content_header')
    <h1>Herramienta para Desbloquear Pedidos</h1>
@stop

@section('content')
    <div id="contenedor" style="font-size:2em; text-align:center;marigin:0 auto;">
    <form id="formDesbloquearPedido" action="{{ route('gfc.privado.desbloquear-pedidos') }}" method="post" onsubmit="return confirmation()">
    <p><label>Indica el nº de Pedido a Desbloquear.</label>
    <input type="number" id="idpedido" name="idpedido" style="padding:10px;border:2px solid;"></p>
    <p><label>Deseas Borrar la Fra. y el Albarán ?</label>
    <input type="checkbox" id="borrarfra" name="borrarfra" checked></p>
    <p><input type="submit"></p>
    </form>
    </div>
@stop

@section('css')
    
@stop

@section('js')
    <script type="text/javascript">
        function confirmation() 
        {
        if(confirm("Seguro que Deseas Desbloquear El Pedido " + document.getElementById("idpedido").value) + " ?")
        return true;
        else
        return false;
        }
    </script>
@stop
@php
    if(!empty($_POST)){
        $idpedido=$_POST['idpedido'];
        if($_POST['borrarfra']!=null) {
            $sql = 'DELETE FROM gfc_order_history WHERE id_order='.$idpedido.' AND id_order_state>=4;UPDATE gfc_orders SET current_state=2 WHERE id_order='.$idpedido.';UPDATE gfc_orders SET invoice_number=0 WHERE id_order='.$idpedido.';DELETE FROM gfc_order_invoice WHERE gfc_order_invoice.id_order='.$idpedido.';DELETE FROM gfc_order_invoice_payment WHERE gfc_order_invoice_payment.id_order='.$idpedido.';UPDATE gfc_order_detail SET id_order_invoice = 0 WHERE gfc_order_detail.id_order='.$idpedido.';';
        }
        else{
            $sql = 'DELETE FROM gfc_order_history WHERE id_order='.$idpedido.' AND id_order_state>=4;UPDATE gfc_orders SET current_state=3 WHERE id_order='.$idpedido.';';
        }
        mysqli_multi_query($link,$sql);
        $algunerror=mysqli_error($link);
        if($algunerror == ''){
            die('SE HA DESBLOQUEADO EL PEDIDO CORRECTAMENTE');
        }else{
            die('Ha ocurrido un problema y NO SE HA PODIDO DESBLOQUEAR EL PEDIDO');
        }
    }
@endphp