@php
error_reporting(E_ALL);
ini_set('display_errors', '0');

if (!function_exists('conectarBd')) {
	function conectarBd(){
		$x=new mysqli(
			"localhost", // URL
			/* "root", // Usuario
			"", // Clave
			"presta17test" // Nombre  */
			"user_presta", // Usuario
			"&a0aY3m0", // Clave
			"presta17" // Nombre 
		);
		if ($x->connect_errno){
			die ("Error: ".$x->mysqli_connect_errno().$x->mysqli_connect_error());
			exit(); 
		}
		return $x;
	}
}
$c=conectarBd();
  
if(isset($_POST["exportarMarcas"])) {
	$consulta=$c->query("SELECT id_manufacturer,name FROM gfc_manufacturer WHERE active=1 ORDER BY id_manufacturer ASC");
    if(!empty($consulta)) {
        $ficheroExcel="Marcas_Activas_GFC ".date("d-m-Y H_i_s").".csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "idmarca;marca\n";    

        while($marca=$consulta->fetch_array()){
                echo $marca['id_manufacturer'].";";
                echo $marca['name']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarPedidosValidos"])) {
	$consulta=$c->query("SELECT DISTINCT a.id_order AS idpedido, (SELECT d.email FROM gfc_customer d WHERE d.id_customer=a.id_customer LIMIT 1) AS Emailcliente, (SELECT UCASE(CONCAT(c.firstname,".'" "'.",c.lastname)) FROM gfc_address c WHERE c.id_customer=a.id_customer LIMIT 1) AS nombrecliente, (SELECT CONCAT(c.dni) FROM gfc_address c WHERE c.id_customer=a.id_customer LIMIT 1) AS DNIcliente, a.date_add AS fechapedido, b.product_reference AS refproducto, b.product_name AS nombreproducto, b.product_quantity AS cantidad, REPLACE(ROUND(b.unit_price_tax_excl,2),'.',',') AS PrecioProducto_SinIVA,REPLACE(ROUND(b.total_price_tax_excl,2),'.',',') AS TotalProductos_SinIVA, REPLACE(ROUND(a.total_shipping_tax_excl,2),'.',',') AS Transporte_SinIVA, REPLACE(ROUND(a.total_paid_tax_excl,2),'.',',') AS TOTALPEDIDO_SinIVA, REPLACE(ROUND(a.total_paid,2),'.',',') AS TOTALPEDIDO, a.payment AS FORMAPAGO
FROM gfc_orders AS a
INNER JOIN gfc_order_detail AS b ON b.id_order = a.id_order
INNER JOIN gfc_address AS c ON c.id_customer = a.id_customer
WHERE a.date_add BETWEEN ".'"'.$_POST['fromDate'].'"'." AND ".'"'.$_POST['toDate'].'"'." AND a.current_state IN (4,5,20,21)
ORDER BY a.id_order ASC;");
	
    if(!empty($consulta)) {
        $ficheroExcel="Pedidos_Validos_GFC.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_Pedido;EMAIL_cliente;NOMBRE_cliente;DNI_cliente;FECHA_Pedido;Referencia_Producto;Nombre_Producto;Cantidad;PrecioProducto_SinIVA;TotalProductos_SinIVA;Transporte_SinIVA;TOTALPEDIDO_SinIVA;TOTALPEDIDO;FORMA_PAGO\n";
        while($pedido=$consulta->fetch_array()){
                echo $pedido['idpedido'].";";
				echo $pedido['Emailcliente'].";";
                echo $pedido['nombrecliente'].";";
				echo $pedido['DNIcliente'].";";
				echo $pedido['fechapedido'].";";
				echo $pedido['refproducto'].";";
				echo $pedido['nombreproducto'].";";
				echo $pedido['cantidad'].";";
				echo $pedido['PrecioProducto_SinIVA'].";";
				echo $pedido['TotalProductos_SinIVA'].";";
				echo $pedido['Transporte_SinIVA'].";";
				echo $pedido['TOTALPEDIDO_SinIVA'].";";
                echo $pedido['TOTALPEDIDO'].";";
				echo $pedido['FORMAPAGO']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarProductosVendidos"])) {
	$consulta=$c->query("SELECT DISTINCT o.id_order,REPLACE(ROUND(o.total_paid_tax_excl,2),'.',',') AS TOTALPEDIDO_SinIVA,o.date_add AS FECHAPEDIDO,od.product_id,od.product_name,od.product_quantity,m.name AS marca,cl.name AS categoria, c.name AS provincia
FROM gfc_order_detail od
LEFT JOIN gfc_orders o ON o.id_order=od.id_order
LEFT JOIN gfc_product p ON p.id_product=od.product_id
LEFT JOIN gfc_manufacturer m ON m.id_manufacturer=p.id_manufacturer
LEFT JOIN gfc_category_lang cl ON (p.id_category_default = cl.id_category AND cl.id_lang = 1 AND cl.id_shop = 1)
INNER JOIN gfc_address a ON a.id_address = o.id_address_delivery
INNER JOIN gfc_state c ON a.id_state = c.id_state
WHERE o.date_add BETWEEN ".'"'.$_POST['fromDate'].'"'." AND ".'"'.$_POST['toDate'].'"'." AND o.current_state IN (2,3,4,5,9,11,17,19,20,21)
ORDER BY o.id_order ASC;");
	
    if(!empty($consulta)) {
        $ficheroExcel="Productos_Vendidos_GFC.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_pedido;total_pedido;fecha_pedido;id_producto;nombre_producto;cantidad;marca;categoria;provincia\n";
        while($producto=$consulta->fetch_array()){
                echo $producto['id_order'].";";
				echo $producto['TOTALPEDIDO_SinIVA'].";";
				echo $producto['FECHAPEDIDO'].";";
                echo $producto['product_id'].";";
				echo $producto['product_name'].";";
				echo $producto['product_quantity'].";";
				echo $producto['marca'].";";
				echo $producto['categoria'].";";
                echo $producto['provincia']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarPedidosExceptoError"])) {
	$consulta=$c->query("SELECT SQL_CALC_FOUND_ROWS a.id_order,reference,REPLACE(ROUND(total_paid_tax_excl,2),'.',',') AS total_pedido,payment, a.date_add AS date_add, 
		UCASE(CONCAT(LEFT(c.firstname, 1), "."'. '".", c.lastname)) AS customer,
		UCASE(osl.name) AS osname,
		UCASE(state.name) as cname
		FROM gfc_orders a 
		LEFT JOIN gfc_customer c ON (c.id_customer = a.id_customer)
		INNER JOIN gfc_address address ON address.id_address = a.id_address_delivery
		INNER JOIN gfc_state state ON address.id_state = state.id_state
		LEFT JOIN gfc_order_state os ON (os.id_order_state = a.current_state)
		LEFT JOIN gfc_order_state_lang osl ON (os.id_order_state = osl.id_order_state AND osl.id_lang = 1) 
 		LEFT JOIN gfc_shop shop
        ON a.id_shop = shop.id_shop WHERE 1  AND a.date_add >= ".'"'.$_POST['fromDate'].'"'." AND a.date_add <= ".'"'.$_POST['toDate'].'"'." AND os.id_order_state != 8  AND a.id_shop IN (1) 
 ORDER BY a.id_order DESC");
	
    if(!empty($consulta)) {
        $ficheroExcel="PedidosEntrados_Excepto_ErrorPago.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_pedido;referencia;total_pedido_sin_iva;forma_de_pago;fecha_pedido;cliente;estado_pedido;provincia\n";    
        while($producto=$consulta->fetch_array()){
                echo $producto['id_order'].";";
                echo $producto['reference'].";";
				echo $producto['total_pedido'].";";
				echo $producto['payment'].";";
				echo $producto['date_add'].";";
				echo $producto['customer'].";";
				echo $producto['osname'].";";
				echo $producto['cname']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["productosMarca"])) {
	$consulta=$c->query("SELECT p.id_product,pl.name,p.reference FROM gfc_product p LEFT JOIN gfc_product_lang pl ON (p.id_product=pl.id_product) WHERE id_manufacturer=".$_POST['marca']." AND active=1");
	
    if(!empty($consulta)) {
        $ficheroExcel="ProductosDeUnaMarca.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_producto;nombre_producto;referencia\n";    
        while($producto=$consulta->fetch_array()){
                echo $producto['id_product'].";";
                echo $producto['name'].";";
				echo $producto['reference']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarTodosProductos"])) {
	$consulta=$c->query("SELECT 
	p.id_product AS 'ProductID',
	m.name AS 'Marca',  
	pl.name AS 'Producto',
	cl.name AS 'Categoria',
    p.minimal_quantity AS 'Cant_Min',
    ROUND(ps.wholesale_price,2) AS 'Precio_Coste',
    ROUND(p.price,2) AS 'Precio_Venta',
    ROUND(sp.reduction*100,2) AS 'Dto_Venta',
    IFNULL(pa.reference, p.reference) AS 'Referencia',
    IFNULL(pa.mpn, p.mpn) AS 'RefDistribase',
    al.name AS 'atributo',
    ROUND(pa.price,2) AS 'Adicional',
    IFNULL(pa.weight, p.weight) AS 'Peso',
    p.additional_shipping_cost AS 'ExtraTransporte',
    IFNULL(pa.CodAuna, p.CodAuna) AS 'CodAuna',
    IFNULL(pa.CodTelematel, p.CodTelematel) AS 'CodTelematel',
    IFNULL(pa.ean13, p.ean13) AS 'EAN13',
    ROUND(p.PvpTarifa,2) AS 'PVPTarifa_API_Prod',
    ROUND(pa.PvpTarifa,2) AS 'PVPTarifa_API_Comb',
    IFNULL(pa.DtoCompra, p.DtoCompra) AS 'Dto_Compra',
    IFNULL(pa.Estado, p.Estado) AS 'Estado_API',
    IFNULL(pa.FechaTarifa, p.FechaTarifa) AS 'FechaTarifa_API',
    IFNULL(pa.FechaUpdateAPI, p.FechaUpdateAPI) AS 'FechaUpdate_API',
    IFNULL(p.stock_mags,'ND') AS stock_mags,
    IFNULL(p.stock_abad,'ND') AS stock_abad,
    IFNULL(p.stock_cale,'ND') AS stock_cale,
    p.stock_ferre,
    IFNULL(pa.pcompra_mags,p.pcompra_mags) AS pcompra_mags,
    IFNULL(pa.pcompra_abad,p.pcompra_abad) AS pcompra_abad,
    IFNULL(pa.pcompra_cale,p.pcompra_cale) AS pcompra_cale,
    p.pcompra_ferre
FROM gfc_product p
INNER JOIN gfc_product_shop ps ON (p.id_product = ps.id_product)
INNER JOIN gfc_product_lang pl ON (p.id_product = pl.id_product and pl.id_lang=1)
INNER JOIN gfc_specific_price sp ON (p.id_product = sp.id_product)
INNER JOIN gfc_manufacturer m ON (p.id_manufacturer = m.id_manufacturer)
INNER JOIN gfc_category_product cp ON (p.id_product = cp.id_product)
INNER JOIN gfc_category_lang cl ON (p.id_category_default = cl.id_category and cl.id_lang=1)
LEFT JOIN gfc_product_attribute pa ON (p.id_product = pa.id_product)
LEFT JOIN gfc_product_attribute_combination pac ON (pac.id_product_attribute = pa.id_product_attribute)
LEFT JOIN gfc_attribute_lang al ON (al.id_attribute = pac.id_attribute and al.id_lang=1)
WHERE p.active=1
GROUP BY p.id_product,pac.id_product_attribute  
ORDER BY ProductID ASC");
    if(!empty($consulta)) {
        $ficheroExcel="TodosProductos_Activos_GFC ".date("d-m-Y H_i_s").".csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "IDproduct;Marca;Producto;Categoria;Precio_Coste;Precio_Venta;Dto_Venta;Dto_Compra;Margen;Referencia;RefDistribase;Cod_Auna;Cod_Telematel;Atributo;Adicional;Cant_Min;Peso;ExtraTransporte;EAN;PVPTarifa_API_Prod;PVPTarifa_API_Comb;Estado_API;FechaTarifa_API;FechaUpdate_API;STOCK_MAGS;STOCK_ABAD;STOCK_CALE;STOCK_FERRE;NETO_MAGS;NETO_ABAD;NETO_CALE;NETO_FERRE\n";    
        $pcompra_mags="";
        $pcompra_abad="";
        $pcompra_cale="";
        while($producto=$consulta->fetch_array()){
                //$precio_coste_prod=$producto['Precio_Venta']*((100-$producto['Dto_Compra'])/100);
                $precio_coste=($producto['Precio_Venta']+$producto['Adicional'])*((100-$producto['Dto_Compra'])/100);
                $precio_venta=$producto['Precio_Venta']+$producto['Adicional'];
                $margenproducto = 1-((100-$producto['Dto_Compra'])/(100-$producto['Dto_Venta']));
                echo $producto['ProductID'].";";
				echo $producto['Marca'].";";
				echo $producto['Producto'].";";
				echo $producto['Categoria'].";";
                echo round($precio_coste,2).";";
                echo round($precio_venta,2).";";
				//echo $producto['Precio_Venta'].";";
				echo $producto['Dto_Venta'].";";
                echo $producto['Dto_Compra'].";";
                echo round($margenproducto*100, 2).";";
				echo $producto['Referencia'].";";
				echo $producto['RefDistribase'].";";
                echo $producto['CodAuna'].";";
                echo $producto['CodTelematel'].";";
				echo $producto['atributo'].";";
				echo $producto['Adicional'].";";
                echo $producto['Cant_Min'].";";
                echo round($producto['Peso'],2).";";
                echo round($producto['ExtraTransporte'],2).";";
                echo $producto['EAN13'].";";
                echo $producto['PVPTarifa_API_Prod'].";";
                echo $producto['PVPTarifa_API_Comb'].";";
                echo $producto['Estado_API'].";";
                echo $producto['FechaTarifa_API'].";";
				echo $producto['FechaUpdate_API'].";";
                echo $producto['stock_mags'].";";
                echo $producto['stock_abad'].";";
                echo $producto['stock_cale'].";";
                echo $producto['stock_ferre'].";";
                $pcompra_mags=str_replace(",", ".", $producto['pcompra_mags']);
                $pcompra_abad=str_replace(",", ".", $producto['pcompra_abad']);
                $pcompra_cale=str_replace(",", ".", $producto['pcompra_cale']);
                echo $pcompra_mags.";";
                echo $pcompra_abad.";";
                echo $pcompra_cale.";";
                echo round($producto['pcompra_ferre'],2)."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarPedidosInstalacion"])) {
	$consulta=$c->query("SELECT DISTINCT a.id_order AS idpedido, (SELECT d.email FROM gfc_customer d WHERE d.id_customer=a.id_customer LIMIT 1) AS Emailcliente, (SELECT UCASE(CONCAT(c.firstname,".'" "'.",c.lastname)) FROM gfc_address c WHERE c.id_customer=a.id_customer LIMIT 1) AS nombrecliente, (SELECT CONCAT(c.dni) FROM gfc_address c WHERE c.id_customer=a.id_customer LIMIT 1) AS DNIcliente, (SELECT CONCAT(c.phone,".'"-"'.",c.phone_mobile) FROM gfc_address c WHERE c.id_customer=a.id_customer LIMIT 1) AS Telcliente, a.date_add AS fechapedido, b.product_reference AS refproducto, b.product_name AS nombreproducto, b.product_quantity AS cantidad, REPLACE(ROUND(b.unit_price_tax_excl,2),'.',',') AS PrecioProducto_SinIVA,REPLACE(ROUND(b.total_price_tax_excl,2),'.',',') AS TotalProductos_SinIVA, REPLACE(ROUND(a.total_shipping_tax_excl,2),'.',',') AS Transporte_SinIVA, REPLACE(ROUND(a.total_paid_tax_excl,2),'.',',') AS TOTALPEDIDO_SinIVA, REPLACE(ROUND(a.total_paid,2),'.',',') AS TOTALPEDIDO, a.payment AS FORMAPAGO
FROM gfc_orders AS a
INNER JOIN gfc_order_detail AS b ON b.id_order = a.id_order
INNER JOIN gfc_address AS c ON c.id_customer = a.id_customer
WHERE a.date_add BETWEEN ".'"'.$_POST['fromDate'].'"'." AND ".'"'.$_POST['toDate'].'"'." AND a.current_state IN (2,3,4,5,7,10,18,20,21,31,32,34,35,36,37,39,40,41,42,45,46,47,48,49,50) AND b.product_name LIKE '%instala%'
ORDER BY a.id_order ASC;");
	
    if(!empty($consulta)) {
        $ficheroExcel="Pedidos_con_Instalacion.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_Pedido;EMAIL_cliente;NOMBRE_cliente;DNI_cliente;TEL_Cliente;FECHA_Pedido;Referencia_Producto;Nombre_Producto;Cantidad;PrecioProducto_SinIVA;TotalProductos_SinIVA;Transporte_SinIVA;TOTALPEDIDO_SinIVA;TOTALPEDIDO;FORMA_PAGO\n";
        while($pedido=$consulta->fetch_array()){
                echo $pedido['idpedido'].";";
				echo $pedido['Emailcliente'].";";
                echo $pedido['nombrecliente'].";";
				echo $pedido['DNIcliente'].";";
                echo $pedido['Telcliente'].";";
				echo $pedido['fechapedido'].";";
				echo $pedido['refproducto'].";";
				echo $pedido['nombreproducto'].";";
				echo $pedido['cantidad'].";";
				echo $pedido['PrecioProducto_SinIVA'].";";
				echo $pedido['TotalProductos_SinIVA'].";";
				echo $pedido['Transporte_SinIVA'].";";
				echo $pedido['TOTALPEDIDO_SinIVA'].";";
                echo $pedido['TOTALPEDIDO'].";";
				echo $pedido['FORMAPAGO']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarPedidosConHistorial"])) {
	$consulta=$c->query("SELECT SQL_CALC_FOUND_ROWS a.id_order,reference,REPLACE(ROUND(total_paid_tax_excl,2),'.',',') AS total_pedido,payment, oh.date_add AS date_add, 
    UCASE(CONCAT(c.firstname, "."' '".", c.lastname)) AS customer,
    UCASE(osl.name) AS osname,
    UCASE(state.name) as cname
    FROM gfc_orders a 
    LEFT JOIN gfc_customer c ON (c.id_customer = a.id_customer)
    INNER JOIN gfc_address address ON address.id_address = a.id_address_delivery
    INNER JOIN gfc_state state ON address.id_state = state.id_state
    LEFT JOIN gfc_order_history oh ON (oh.id_order = a.id_order)
    LEFT JOIN gfc_order_state_lang osl ON (oh.id_order_state = osl.id_order_state AND osl.id_lang = 1)
 	LEFT JOIN gfc_shop shop ON a.id_shop = shop.id_shop WHERE 1 AND a.date_add >= ".'"'.$_POST['fromDate'].'"'." AND a.date_add <= ".'"'.$_POST['toDate'].'"'."  AND a.id_shop IN (1) 
 ORDER BY a.id_order DESC");
	
    if(!empty($consulta)) {
        $ficheroExcel="PedidosEntrados_ConHistorial.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "id_pedido;referencia;total_pedido_sin_iva;forma_de_pago;fecha_pedido;cliente;estado_pedido;provincia\n";    
        while($producto=$consulta->fetch_array()){
                echo $producto['id_order'].";";
                echo $producto['reference'].";";
				echo $producto['total_pedido'].";";
				echo $producto['payment'].";";
				echo $producto['date_add'].";";
				echo $producto['customer'].";";
				echo $producto['osname'].";";
				echo $producto['cname']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
if(isset($_POST["exportarPedidosFluorados"])) {
	$consulta=$c->query("SELECT o.id_order AS num_pedido, o.reference, o.date_add AS fecha_pedido, a.address1 AS Direccion, a.postcode AS cod_postal, a.city AS Poblacion , c.name AS provincia, cu.firstname AS NombreCliente, cu.lastname AS ApellidosCliente,cu.email,a.phone_mobile, a.phone, a.dni, b.product_reference AS refproducto, b.product_name AS nombreproducto, m.name AS marca
FROM gfc_orders o
INNER JOIN gfc_order_detail b ON b.id_order = o.id_order
LEFT JOIN gfc_category_product cp ON cp.id_product=b.product_id
LEFT JOIN gfc_product p ON p.id_product=b.product_id
LEFT JOIN gfc_manufacturer m ON m.id_manufacturer=p.id_manufacturer
LEFT JOIN gfc_customer cu ON o.id_customer = cu.id_customer
INNER JOIN gfc_address a ON o.id_address_delivery = a.id_address
LEFT JOIN gfc_order_state os ON o.current_state = os.id_order_state
LEFT JOIN gfc_shop s ON o.id_shop = s.id_shop
INNER JOIN gfc_state c ON a.id_state = c.id_state
WHERE os.paid=1 AND o.date_add BETWEEN ".'"'.$_POST['fromDate'].'"'." AND ".'"'.$_POST['toDate'].'"'." AND (cp.id_category=2299) AND o.id_order NOT IN (SELECT id_order FROM gfc_fluoradosorders)
ORDER BY o.id_order desc;");
	
    if(!empty($consulta)) {
        $ficheroExcel="Pedidos_Fluorados_GFC.csv";
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=".$ficheroExcel);
        echo "num_pedido;reference;fecha_pedido;Direccion;cod_postal;Poblacion;NombreCliente;ApellidosCliente;email;phone_mobile;phone;dni;refproducto;nombreproducto;marca\n";
        while($pedido=$consulta->fetch_array()){
                echo $pedido['num_pedido'].";";
				echo $pedido['reference'].";";
                echo $pedido['fecha_pedido'].";";
				echo $pedido['Direccion'].";";
				echo $pedido['cod_postal'].";";
				echo $pedido['Poblacion'].";";
				echo $pedido['NombreCliente'].";";
				echo $pedido['ApellidosCliente'].";";
				echo $pedido['email'].";";
				echo $pedido['phone_mobile'].";";
                echo $pedido['phone'].";";
                echo $pedido['dni'].";";
                echo $pedido['refproducto'].";";
                echo $pedido['nombreproducto'].";";
				echo $pedido['marca']."\n";
        }                
    }else{
        echo "No hay datos a exportar";
    }
    //Para que se cree el Excel correctamente, hay que añadir la sentencia exit;
    exit;
}
@endphp

@extends('adminlte::page')

@section('title', 'Herramienta Para Descargar Extracciones en Excel CSV')

@section('content_header')
    <h1>Herramienta Para Descargar Extracciones en Excel CSV</h1>
@stop

@section('content')
    <div style="text-align:center;background-color:#F5F6E3;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
    <p>
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
            <button type="submit" id="exportarMarcas" name="exportarMarcas">Exportar MARCAS ACTIVAS a Excel (CSV)</button>
        </form>
    </p>
    </div>
    <div style="text-align:center;background-color:#F5F6E3;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: si se quieren extraer los pedidos del último dia hasta las 23:59:59h se debe poner siempre UN DIA MAS en el campo HASTA. Por ejemplo, para incluir todos los pedidos del mes de Junio, se debe poner DE 01/06/2020 HASTA 01/07/2020.
            <p><button type="submit" id="exportarPedidosValidos" name="exportarPedidosValidos">Exportar PEDIDOS VALIDOS a Excel (CSV)</button></p>
        </form>
     </div>
    <div style="text-align:center;background-color:#F5F6E3;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
            @csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: Se exportaran todos los productos vendidos entre las 2 fechas.
            <p><button type="submit" id="exportarProductosVendidos" name="exportarProductosVendidos">Exportar PRODUCTOS VENDIDOS a Excel (CSV)</button></p>
        </form>
     </div>
    <div style="text-align:center;background-color:#F5F6E3;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: Se exportaran todos los PEDIDOS EXCEPTO los que hayan entrado como ERROR EN PAGO entre las 2 fechas indicadas.
            <p><button type="submit" id="exportarPedidosExceptoError" name="exportarPedidosExceptoError">Exportar PEDIDOS ENTRADOS EXCEPTO ERROR EN PAGO a Excel (CSV)</button></p>
        </form>
     </div>
    <div style="text-align:center;background-color:#F5F6E3;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<select name="marca">
        	<option value="0">Seleccionar Marca:</option>
			@php
				$query = $c->query("SELECT id_manufacturer,name FROM gfc_manufacturer WHERE active=1");
				while ($valores = mysqli_fetch_array($query)) {
				echo "<option value=".$valores['id_manufacturer'].">".$valores['name']."</option>";
				}
			@endphp
      		</select>
            <p><button type="submit" id="productosMarca" name="productosMarca">Exportar TODOS LOS PRODUCTOS DE UNA MARCA a Excel (CSV)</button></p>
        </form>
     </div>
    <div style="text-align:center;background-color:#F5F609;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
    <p>
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
            <button type="submit" id="exportarTodosProductos" name="exportarTodosProductos">Exportar TODOS LOS PRODUCTOS ACTIVOS a Excel (CSV)</button>
        </form>
    </p>
    </div>
    <div style="text-align:center;background-color:#F5F609;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
    <p>
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: Se exportaran todos los PEDIDOS con INSTALACIÓN entre las 2 fechas indicadas.
            <p><button type="submit" id="exportarPedidosInstalacion" name="exportarPedidosInstalacion">Exportar PEDIDOS CON INSTALACIÓN</button></p>
        </form>
    </p>
    </div>
    <div style="text-align:center;background-color:#F5F609;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: Se exportaran todos los PEDIDOS ENTRADOS CON HISTORICO DE ESTADOS entre las 2 fechas indicadas.
            <p><button type="submit" id="exportarPedidosConHistorial" name="exportarPedidosConHistorial">Exportar PEDIDOS ENTRADOS CON HISTORICO DE ESTADOS a Excel (CSV)</button></p>
        </form>
     </div>
     <div style="text-align:center;background-color:#F5F609;width:80%;margin:20px auto;padding:20px;border:2px dotted #DDBF16;">
        <form action="{{ route('gfc.privado.descargar-excels') }}" method="post">
			@csrf
        	<p>RANGO DE FECHAS: DE <input type="text" id="fromDate" name="fromDate"> HASTA <input type="text" id="toDate" name="toDate"></p>
              <p>* NOTA: Se exportaran los PEDIDOS CON PRODUCTOS FLUORADOS RECIBIDO=NO entre las 2 fechas indicadas.
            <p><button type="submit" id="exportarPedidosFluorados" name="exportarPedidosFluorados">Exportar PEDIDOS FLUORADOS=NO a Excel (CSV)</button></p>
        </form>
     </div>
@stop

@section('css')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
@stop

@section('js')
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script type="text/javascript">
		$( function() {
	  	var from = $( "#fromDate" )
		  .datepicker({
			dateFormat: "yy-mm-dd",
			changeMonth: true
		  })
		  .on( "change", function() {
			to.datepicker( "option", "minDate", getDate( this ) );
		  }),
		to = $( "#toDate" ).datepicker({
		  dateFormat: "yy-mm-dd",
		  changeMonth: true
		})
		.on( "change", function() {
		  from.datepicker( "option", "maxDate", getDate( this ) );
		});
	
	  function getDate( element ) {
		var date;
		var dateFormat = "yy-mm-dd";
		try {
		  date = $.datepicker.parseDate( dateFormat, element.value );
		} catch( error ) {
		  date = null;
		}
	
		return date;
	  }
	});
	</script>
@stop    