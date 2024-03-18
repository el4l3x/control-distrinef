<?php
session_start();
error_reporting(E_ALL ^ E_DEPRECATED);
$link = mysqli_connect('localhost','user_presta','&a0aY3m0');
if (!$link) {
    die('Could not connect: ' . mysqli_error($link));
}

mysqli_select_db($link,'presta17');

mysqli_query($link,"SET NAMES utf8");

if(!isset($_COOKIE['cambia_precios_contexto'])) {
	setcookie('cambia_precios_contexto', 'todos', time() + (86400 * 365), "/");
	$_COOKIE['cambia_precios_contexto']='todos';
}
if(isset($_GET['cambia_precios_contexto']) && $_GET['cambia_precios_contexto']=='todos'){
	setcookie('cambia_precios_contexto', 'todos', time() + (86400 * 365), "/");
	$_COOKIE['cambia_precios_contexto']='todos';
}

if(isset($_GET['cambia_precios_contexto']) && $_GET['cambia_precios_contexto']=='clientes'){
	setcookie('cambia_precios_contexto', 'clientes', time() + (86400 * 365), "/");
	$_COOKIE['cambia_precios_contexto']='clientes';
}

if(isset($_GET['cambia_precios_contexto']) && $_GET['cambia_precios_contexto']=='visitantes'){
	setcookie('cambia_precios_contexto', 'visitantes', time() + (86400 * 365), "/");
	$_COOKIE['cambia_precios_contexto']='visitantes';
}
const GRUPO_VISITANTE=1;
const GRUPO_CLIENTE=3;

const _DB_PREFIX_='gfc_';
const id_lang=1;
const id_shop=1;

const impuesto_21=1.21;
const impuesto_4=1.04;
const impuesto_10=1.1;

$sql = 'SELECT trg.id_tax_rules_group
				FROM `'._DB_PREFIX_.'tax_rules_group` trg				
				WHERE trg.`deleted` = 0
				AND trg.name like "%4%"
                  ';
$id_tax_rule_group_4=mysqli_fetch_assoc(mysqli_query($link,$sql));
if($id_tax_rule_group_4){
    $id_tax_rule_group_4=(int)$id_tax_rule_group_4['id_tax_rules_group'];
}else{
    die('Problemas sacando el id de impuestos 4%');
}


$sql = 'SELECT trg.id_tax_rules_group
				FROM `'._DB_PREFIX_.'tax_rules_group` trg
				WHERE trg.`deleted` = 0
				AND trg.name like "%10%"
                  ';
$id_tax_rule_group_10=mysqli_fetch_assoc(mysqli_query($link,$sql));
if($id_tax_rule_group_10){
    $id_tax_rule_group_10=(int)$id_tax_rule_group_10['id_tax_rules_group'];
}else{
    die('Problemas sacando el id de impuestos 10%');
}

$sql = 'SELECT trg.id_tax_rules_group
				FROM `'._DB_PREFIX_.'tax_rules_group` trg
				WHERE trg.`deleted` = 0
				AND trg.name like "%21%"
                  ';
$id_tax_rule_group_21=mysqli_fetch_assoc(mysqli_query($link,$sql));
if($id_tax_rule_group_21){
    $id_tax_rule_group_21=(int)$id_tax_rule_group_21['id_tax_rules_group'];
}else{
    die('Problemas sacando el id de impuestos 21%');
}


$id_group=0;
if ($_COOKIE['cambia_precios_contexto']=='todos'){
    $id_group=0;
}else if ($_COOKIE['cambia_precios_contexto']=='clientes'){
    $id_group=GRUPO_CLIENTE;
}else if ($_COOKIE['cambia_precios_contexto']=='visitantes'){
    $id_group=GRUPO_VISITANTE;
}

if(!empty($_POST)){
    if(in_array('id_product',array_keys($_POST))){//Guardamos producto
		$precio=$_POST['precio'];

		/*switch($_POST['impuesto']){
			case $id_tax_rule_group_21:
				$precio/=impuesto_21;
				break;

			case $id_tax_rule_group_10:
				$precio/=impuesto_10;
				break;

			case $id_tax_rule_group_4:
				$precio/=impuesto_4;
				break;
		}*/
		
		mysqli_query($link,"
			UPDATE "._DB_PREFIX_."product
			SET id_tax_rules_group=".$_POST['impuesto'].", price=".$precio.", reference='".trim($_POST['reference'])."', ean13='".$_POST['ean13']."', comentarios='".$_POST['comentarios']."'
			WHERE id_product=".$_POST['id_product']."
		");
		$error_1=mysqli_error($link);

		mysqli_query($link,"
			UPDATE "._DB_PREFIX_."product_shop
			SET id_tax_rules_group=".$_POST['impuesto'].", price=".$precio."
			WHERE id_product=".$_POST['id_product']."
		");
		$error_2=mysqli_error($link);

		echo json_encode(
			array(
				'success'		=>		$error_1=='' && $error_2=='',
				'error1'		=>		$error_1,
				'error2'		=>		$error_2
			)
		);

		exit;
	}
	
	if(in_array('id_combinacion',array_keys($_POST))){//Guardamos combinacion
	    $precio=$_POST['precio'];
	    $id_combinacion=$_POST['id_combinacion'];
	    $impuesto_producto_padre=mysqli_fetch_assoc(mysqli_query($link,"
			SELECT id_tax_rules_group,id_product FROM "._DB_PREFIX_."product			
			WHERE id_product= (SELECT id_product FROM "._DB_PREFIX_."product_attribute WHERE id_product_attribute=".$id_combinacion.")
		"));
	    
	    $id_product=(int)$impuesto_producto_padre['id_product'];
	    $impuesto_producto_padre=(int)$impuesto_producto_padre['id_tax_rules_group'];
	   
	    /*switch($impuesto_producto_padre){
	        case $id_tax_rule_group_21:
	            $precio/=impuesto_21;
	            break;
	            
	        case $id_tax_rule_group_10:
	            $precio/=impuesto_10;
	            break;
	            
	        case $id_tax_rule_group_4:
	            $precio/=impuesto_4;
	            break;
	    }*/
	    
	    mysqli_query($link,"
			UPDATE "._DB_PREFIX_."product_attribute
			SET  price=".$precio.", reference='".trim($_POST['reference'])."', ean13='".$_POST['ean13']."' 
			WHERE id_product_attribute=".$_POST['id_combinacion']."
		");
	    $error_1=mysqli_error($link);
	    
	    mysqli_query($link,"
			UPDATE "._DB_PREFIX_."product_attribute_shop
			SET  price=".$precio."
			WHERE id_product_attribute=".$_POST['id_combinacion']."
		");
	    $error_2=mysqli_error($link);
	    
	    
	    if(!$error_1 && !$error_2){
	       $texto_precio= cambio_precios_precio_combinaciones($id_product, $id_combinacion);
	    }
	    echo json_encode(
	        array(
	            'success'		=>		$error_1=='' && $error_2=='',
	            'error1'		=>		$error_1,
	            'error2'		=>		$error_2,
	            'texto_precio' => $texto_precio
	        )
	        );
	    
	    exit;
	}

	if(isset($_GET['quitar-descuento'])){
	    if(isset($_POST['ids_productos'])){
	        foreach($_POST['ids_productos'] as $id_producto_cant){

                $id_producto_cant_arr=explode("-", $id_producto_cant);

                $id_producto=$id_producto_cant_arr[0];
                $from_quantity=$id_producto_cant_arr[1];//siempre será uno

	            mysqli_query($link,"
				DELETE FROM "._DB_PREFIX_."specific_price
				WHERE id_product=".$id_producto." AND id_product_attribute=0 AND id_group=".(int)$id_group." AND from_quantity =1");
	        }
	    }
        if(isset($_POST['ids_productos_cantidad'])){
            foreach($_POST['ids_productos_cantidad'] as $id_producto_cant){//Borramos descuentos de cantidad

                $id_producto_cant_arr=explode("-", $id_producto_cant);

                $id_producto=$id_producto_cant_arr[0];
                $from_quantity=$id_producto_cant_arr[1];

                mysqli_query($link,"
				DELETE FROM "._DB_PREFIX_."specific_price
				WHERE id_product=".$id_producto." AND id_product_attribute=0 AND id_group=".(int)$id_group." AND from_quantity=".(int)$from_quantity);
            }
        }
	    if(isset($_POST['ids_combinaciones'])){
    	    foreach($_POST['ids_combinaciones'] as $id_prod_combi){//combinaciones
                $id_prod_combi_cantidad_arr=explode("-", $id_prod_combi);
    	        
    	        $id_producto=$id_prod_combi_cantidad_arr[0];
    	        $id_combinacion=$id_prod_combi_cantidad_arr[1];
    	        $from_quantity=$id_prod_combi_cantidad_arr[2];
    	        mysqli_query($link,"
    				DELETE FROM "._DB_PREFIX_."specific_price
    				WHERE id_product=".$id_producto." AND id_product_attribute=".$id_combinacion." AND id_group=".(int)$id_group." AND from_quantity=".(int)$from_quantity);
    	    }
	    }
		$url=str_replace('&quitar-descuento','',$_SERVER['REQUEST_URI']);
		header('location: '.$url);
	}

	$cp_errores_mssg=array();

	if(isset($_GET['anadir-descuento'])){
	    if(isset($_POST['ids_productos']) || isset($_POST['ids_productos_cantidad'])){
	        //Join
            $id_products_to_discount=array_unique(array_merge((array)$_POST['ids_productos'], (array)$_POST['ids_productos_cantidad']));
	        foreach($id_products_to_discount as $id_producto_quant){

                $id_producto_quant_arr=explode("-", $id_producto_quant);

                $id_producto=$id_producto_quant_arr[0];
                $from_quantity=$id_producto_quant_arr[1];
	            //nueva validación que la regla de descuento de un cliente sea superior a la de todos.

                $id_other_group=0;//TODOS

                if($id_group==0){//Miramos el descuento de clientes
                    $id_other_group=GRUPO_CLIENTE;
                }

                $cantidad=(int)$_POST['cantidad'];


                //De momento si es un descuento por cantidad en un producto con combinaciones no queremos aplicarlo

                if($cantidad>1 && cambio_precios_tieneCombinaciones($id_producto)){
                    continue;
                }

                $specific_price_row=mysqli_fetch_assoc( mysqli_query($link,"
				SELECT reduction*100 as reduction_other FROM "._DB_PREFIX_."specific_price
				WHERE id_product=".$id_producto." AND id_product_attribute=0 AND id_group=".(int)$id_other_group." AND from_quantity=".(int)$cantidad));


                $reduction_other=$specific_price_row['reduction_other'];
                $reduction=$_POST['descuento'];


                if($reduction_other) {//Si esta informado validamos.
                    if ($id_group == 0) {//Contexto todos. El otro es el contexto cliente
                        if ($reduction > $reduction_other) {
                            $cp_errores_mssg[] = 'El producto con identificador ' . $id_producto . ' no puede tener un descuento mayor en el contexto TODOS (' . round($reduction, 2) . '%)  que en CLIENTES (' . round($reduction_other, 2) . '%)';
                            continue;
                        }
                    } else {//Contexto clientes. El otro es el contexto todos.
                        if ($reduction < $reduction_other) {
                            $cp_errores_mssg[] = 'El producto con identificador ' . $id_producto . ' no puede tener un descuento mayor en el contexto TODOS (' . round($reduction_other, 2) . '%)  que en CLIENTES (' . round($reduction, 2) . '%)';
                            continue;
                        }
                    }
                }




	            mysqli_query($link,"
				DELETE FROM "._DB_PREFIX_."specific_price
				WHERE id_product=".$id_producto." AND id_product_attribute=0 AND id_group=".(int)$id_group." AND from_quantity=".$cantidad);
	            mysqli_query($link,"
					INSERT INTO "._DB_PREFIX_."specific_price
						(id_product,id_product_attribute,id_shop,id_shop_group,id_currency,id_country,id_group,id_customer,price,from_quantity,reduction,reduction_type)
					VALUES
						(".$id_producto.",0,0,0,0,0,".(int)$id_group.",0,-1.000000,".$cantidad.",".($_POST['descuento']/100).",'percentage')
				");
	        }
	    }
	   
	    if(isset($_POST['ids_combinaciones'])){
    	    foreach($_POST['ids_combinaciones'] as $id_prod_combi){//combinaciones
    	        $id_prod_combi_cantidad_arr=explode("-", $id_prod_combi);
    	        
    	        $id_producto=$id_prod_combi_cantidad_arr[0];
    	        $id_combinacion=$id_prod_combi_cantidad_arr[1];
    	        $from_quantity=$id_prod_combi_cantidad_arr[2];


                //nueva validación que la regla de descuento de un cliente sea superior a la de todos.

                $id_other_group=0;//TODOS

                if($id_group==0){//Miramos el descuento de clientes
                    $id_other_group=GRUPO_CLIENTE;
                }

                $cantidad=(int)$_POST['cantidad'];
                $specific_price_row=mysqli_fetch_assoc( mysqli_query($link,"
                    SELECT reduction*100 as reduction_other FROM "._DB_PREFIX_."specific_price
                    WHERE id_product=".$id_producto." AND id_product_attribute=".$id_combinacion." AND id_group=".(int)$id_other_group." AND from_quantity=".(int)$cantidad));


                $reduction_other=$specific_price_row['reduction_other'];
                $reduction=$_POST['descuento'];

                if($reduction_other) {//Si esta informado validamos.
                    if ($id_group == 0) {//Contexto todos. El otro es el contexto cliente
                        if ($reduction > $reduction_other) {
                            $cp_errores_mssg[] = 'La combinacion con identificador ' . $id_prod_combi . ' no puede tener un descuento mayor en el contexto TODOS (' . round($reduction, 2) . '%)  que en CLIENTES (' . round($reduction_other, 2) . '%)';
                            continue;
                        }
                    } else {
                        if ($reduction < $reduction_other) {
                            $cp_errores_mssg[] = 'La combinacion con identificador ' . $id_prod_combi . ' no puede tener un descuento mayor en el contexto TODOS (' . round($reduction_other, 2) . '%)  que en CLIENTES (' . round($reduction, 2) . '%)';
                            continue;
                        }
                    }
                }
    	        mysqli_query($link,"
    				DELETE FROM "._DB_PREFIX_."specific_price
    				WHERE id_product=".$id_producto." AND id_product_attribute=".$id_combinacion." AND id_group=".(int)$id_group." AND from_quantity=".(int)$cantidad);
    	        mysqli_query($link,"
    					INSERT INTO "._DB_PREFIX_."specific_price
    						(id_product,id_product_attribute,id_shop,id_shop_group,id_currency,id_country,id_group,id_customer,price,from_quantity,reduction,reduction_type)
    					VALUES
    						(".$id_producto.",".$id_combinacion.",0,0,0,0,".(int)$id_group.",0,-1.000000,".(int)$cantidad.",".($_POST['descuento']/100).",'percentage')
    				");
    	    }
	    }
		
		if(count($cp_errores_mssg)){
            $_SESSION['cp_errores_mssg']=$cp_errores_mssg;
        }
		$url=str_replace('&anadir-descuento','',$_SERVER['REQUEST_URI']);
		header('location: '.$url);
		die();
	}
}

$marcas=mysqli_query($link,"
	SELECT m.id_manufacturer, m.name
	FROM "._DB_PREFIX_."manufacturer m
	ORDER BY m.name
");

if(array_key_exists('id_marca',$_GET)){
	
	
	
	
	$productos=mysqli_query($link,"
		SELECT DISTINCT(p.id_product), pl.name, p.id_tax_rules_group, ps.price, sp.reduction, sp.reduction_type, p.ean13, p.reference, p.comentarios,sp.from_quantity
		FROM "._DB_PREFIX_."product p
		LEFT JOIN "._DB_PREFIX_."product_lang pl ON p.id_product=pl.id_product AND pl.id_lang=".id_lang."
		LEFT JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND ps.id_shop=".id_shop."
		LEFT JOIN "._DB_PREFIX_."specific_price sp ON (sp.id_product=p.id_product AND sp.id_product_attribute=0 AND sp.id_group=".$id_group.")
		WHERE p.id_manufacturer=".$_GET['id_marca']." AND p.active=1
		ORDER BY pl.name ASC,p.id_product ASC, sp.from_quantity ASC
	");

	$impuestos=mysqli_query($link,"
		SELECT trl.id_tax_rules_group, trl.name
		FROM "._DB_PREFIX_."tax_rules_group trl where trl.deleted=0
	");
	$impuestos_a=array();
	$impuestos_a[0]='';
	while($impuesto=@mysqli_fetch_array($impuestos)){
		$impuestos_a[$impuesto['id_tax_rules_group']]=$impuesto['name'];
	}
}	
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Cambio de precios | Gasfriocalor</title>
	<style>
		body {
			font-family:Arial;
			font-size:13px;
		}
		#cambia_precios_contexto{
			margin-bottom: 20px;
			font-size: 16px;
		}
		#cambia_precios_contexto .selected{
			font-weight: bold;
			color: darkred;
		}
		
		tr.tieneCombinaciones{
		      background-color: #ed7fa6  !important;
		}
		
		tr.combination_row_par{
		  background-color: #eaced8  !important;
		}
		tr.combination_row_impar{
		  background-color: #f6b5cc !important;
		}
		#cambia_precios_errores{
            border: 3px solid darkred;
            padding: 5px;
            background-color: pink;
            margin-bottom: 20px;
        }
        #cambia_precios_errores h2{
            color: darkred;
        }
        #cambia_precios_errores ul li{
            list-style: none;
            color: darkred;
            font-weight: bold;
        }
	</style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$(document).on('click', '.guardar-producto', function(event) {
				event.preventDefault();
				event.stopPropagation();

				var contenedor=$(this).closest('tr');
				var status=contenedor.find('.status');

				var id_product=contenedor.data('id-product');
				var impuesto=contenedor.find('.impuesto').val();
				var precio=contenedor.find('.precio').val();
				var ean13=contenedor.find('.ean13').val();
				var reference=contenedor.find('.reference').val();
				var comentarios=contenedor.find('.comentarios').val();

				status.html('<img src="ajax-loader.gif">');

				$.ajax({
					url: 'cambio-precios.php',
					type: 'POST',
					dataType: 'json',
					data: {id_product:id_product, impuesto:impuesto, precio:precio, reference:reference, ean13:ean13, comentarios:comentarios},
				})
				.done(function(data) {
					console.log(data);
					if(data.success){
						status.html('<img src="ok.png" style="width:20px;">');
					} else {
						status.html('<img src="ko.png" style="width:20px;">');
					}
				})
				.fail(function(data) {
					console.log(data);
					status.html('<img src="ko.png" style="width:20px;">');
				});
			});

			$(document).on('click','.check-all',function(event){
				event.preventDefault();
				event.stopPropagation();

				$('#tabla-productos').find('input[type="checkbox"][name="ids_productos[]"]').each(function(index,el){
					$(el).prop('checked',true);
				});
			});
            $(document).on('click','.check-all-cantidad',function(event){
                event.preventDefault();
                event.stopPropagation();

                $('#tabla-productos').find('input[type="checkbox"][name="ids_productos_cantidad[]"]').each(function(index,el){
                    $(el).prop('checked',true);
                });
            });
			
			$(document).on('click','.check-none',function(event){
				event.preventDefault();
				event.stopPropagation();

				$('#tabla-productos').find('input[type="checkbox"]').each(function(index,el){
					$(el).prop('checked',false);
				});
			});

			$(document).on('click','#quitar-descuento',function(event){
				event.preventDefault();
				event.stopPropagation();

				$('#form-descuentos').attr('action',$('#form-descuentos').attr('action')+'&quitar-descuento').submit();
			});

			$(document).on('click','#anadir-descuento',function(event){
				event.preventDefault();
				event.stopPropagation();

				console.log('hi');

				$('#form-descuentos').attr('action',$('#form-descuentos').attr('action')+'&anadir-descuento').submit();
			});

			$(document).on('click','#cambia_precios_contexto a',function(event){
				event.preventDefault();
				event.stopPropagation();

				var contexto=$(this).data('contexto');
				//var separator = (window.location.href.indexOf("?")===-1)?"?":"&";
				// window.location.href=window.location.href+separator+'cambia_precios_contexto='+contexto;
				


					if($('#lista_marcas a.selected').length >0){
						var id_marca=$('#lista_marcas a.selected').data('marca');
						window.location.href="./cambio-precios.php?id_marca="+id_marca+"&cambia_precios_contexto="+contexto;
					}else{
						window.location.href="./cambio-precios.php?cambia_precios_contexto="+contexto;
					}

					
			});

			$(document).on('click', '.guardar-combinacion', function(event) {
				event.preventDefault();
				event.stopPropagation();

				var contenedor=$(this).closest('tr');
				var status=contenedor.find('.status');

				var id_combinacion=contenedor.data('id-combinacion');
				var precio=contenedor.find('.precio').val();
				var ean13=contenedor.find('.ean13').val();
				var reference=contenedor.find('.reference').val();
				

				status.html('<img src="ajax-loader.gif">');

				$.ajax({
					url: 'cambio-precios.php',
					type: 'POST',
					dataType: 'json',
					data: {id_combinacion:id_combinacion, precio:precio, reference:reference, ean13:ean13},
				})
				.done(function(data) {
					console.log(data);
					if(data.success){
						status.html('<img src="ok.png" style="width:20px;">');
						contenedor.find('td').last().html('<strong>'+data.texto_precio+'</strong>');
					} else {
						status.html('<img src="ko.png" style="width:20px;">');
					}
				})
				.fail(function(data) {
					console.log(data);
					status.html('<img src="ko.png" style="width:20px;">');
				});
			});
			
		});
	</script>
</head>
<body>
	<h1>Cambio de precios | Gasfriocalor</h1>
	<?php 
	
	
	
	?>
	<div id="cambia_precios_contexto">
	<label style="font-weight: bold; font">CONTEXTO:</label><a <?php if ($_COOKIE['cambia_precios_contexto']=='todos'){ ?> class="selected" <?php } ?> data-contexto="todos" href="cambio-precios.php?cambia_precios_contexto=todos">TODOS</a>
	| <a <?php if ($_COOKIE['cambia_precios_contexto']=='clientes'){ ?> class="selected" <?php } ?> data-contexto="clientes" href="cambio-precios.php?cambia_precios_contexto=clientes">CLIENTES</a>
	<!-- de momento comentado | <a <?php if ($_COOKIE['cambia_precios_contexto']=='visitantes'){ ?> class="selected" <?php } ?> data-contexto="visitantes" href="cambio-precios.php?cambia_precios_contexto=visitantes">VISITANTES</a> -->
	</div>
	
	<?php
	    if(isset($_SESSION['cp_errores_mssg'])){
            $cp_errores_mssg=$_SESSION['cp_errores_mssg'];
            unset($_SESSION['cp_errores_mssg']);
            ?>
            <div id="cambia_precios_errores">
                <h2>Errores</h2>
                <ul>
                <?php foreach ($cp_errores_mssg as $cp_error){?>
                    <li><?php echo $cp_error ?></li>
                <?php }?>
                </ul>
            </div>

        <?php   } ?>




	<div id="lista_marcas" style="margin-bottom:15px;">
		<?php
		while($marca=@mysqli_fetch_array($marcas)){
		?>
			<a data-marca="<?php echo $marca['id_manufacturer']; ?>" <?php if(isset($_GET['id_marca']) && $_GET['id_marca']==$marca['id_manufacturer']){?> class="selected" <?php }?> href="cambio-precios.php?id_marca=<?php echo $marca['id_manufacturer']; ?>"><?php echo $marca['name']; ?></a> | 
		<?php
		}
		?>
	</div>
	<?php
	if(isset($productos)){
	?>
		<form id="form-descuentos" method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
			
			<div style="float:left; margin:20px 0px;">
				<div style="float:left; margin-right:20px; padding-right:20px; border-right:solid 1px #333333;">
					<input type="button" id="quitar-descuento" value="Quitar descuento">
				</div>
				<div style="float:left;">
					<strong>Descuento:</strong>
					<input type="text" name="descuento">

                    <strong>Cantidad:</strong>
                    <select name="cantidad">
                        <?php
                        for ($i=1;$i<=10;$i++){
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>

                    </select>

					<strong>Tipo:</strong>
					<select name="tipo_descuento">
						<option value="percentage">Porcentaje</option>
					</select>
					
					
					<input type="button" id="anadir-descuento" value="Añadir descuento">
				</div>
			</div>

			<table cellspacing="0" style="clear:both;" id="tabla-productos">
				<tr>
					<td style="width:70px; text-align:center;">
                        <a href="" class="check-all-cantidad" title="Todos los Productos con Descuento Cantidad" style="color:#FF0000; font-weight:bold; text-decoration:none;">TC</a> / <a href="" class="check-all" style="color:#FF0000; font-weight:bold; text-decoration:none;">T</a> / <a href="" class="check-none" style="color:#00FF00; font-weight:bold; text-decoration:none;">N</a>
					</td>
					<td><strong>ID</strong></td>
					<td style="width:400px;"><strong>Nombre</strong></td>
					<td><strong>IVA</strong></td>
					<td><strong>PVP (Sin IVA)</strong></td>
                    <td style="width:25px"><strong>Desde</strong></td>
					<td><strong>Referencia</strong></td>
					<td><strong>EAN</strong></td>
					<td><strong>Comentarios</strong></td>
					<td style="width:100px;"></td>
					<td style="width:150px;"></td>
				</tr>
				<?php
				$i=0;
				while($producto=@mysqli_fetch_array($productos)){
					$combinaciones=cambio_precios_combinaciones($producto['id_product']);
				
					$precio=$producto['price'];

					/*switch($producto['id_tax_rules_group']){
						case $id_tax_rule_group_21:
							$precio*=impuesto_21;
							break;
						case $id_tax_rule_group_10:
							$precio*=impuesto_10;
							break;
						case $id_tax_rule_group_4:
							$precio*=impuesto_4;
							break;
					}*/

					$precio=round($precio,2);
                    $id_cantidad=1;
                    if(isset($producto['from_quantity'])){
                        $id_cantidad=$producto['from_quantity'];
                    }
				?>
					<tr<?php if($combinaciones){ ?> class="tieneCombinaciones" <?php }?> <?php if($i%2==0){ ?> style="background:#DBDBEA;"<?php } ?> data-id-product="<?php echo $producto['id_product']; ?>">
						<td style="text-align:center;">

                            <?php if ($producto['from_quantity']>=2){ ?>
                                <input type="checkbox" name="ids_productos_cantidad[]" value="<?php echo $producto['id_product'].'-'.$id_cantidad; ?>" />
                            <?php }else{ ?>
                                <input type="checkbox" name="ids_productos[]" value="<?php echo $producto['id_product'].'-'.$id_cantidad; ?>" />
                            <?php } ?>

							<?php
							if(!is_null($producto['reduction_type'])){
							?>
								<span style="color:#FF0000; font-weight:bold;">D</span>
							<?php
							}
							?>
						</td>
                        <?php
                        $is_editable=false;
                        if ($producto['from_quantity']==1 || is_null($producto['from_quantity'])){
                            $is_editable=true;
                        }else if(cambio_precios_getNumDescuentos($producto['id_product'])==1){
                                $is_editable=true;//solo un descuento
                        }
                        ?>
						<td><?php echo $producto['id_product']; if ($producto['from_quantity']>=2){echo ' (<span style="font-weight:bold">*Q'.$producto['from_quantity'].'</span>)';}  ?></td>
						<td><?php echo $producto['name']; ?></td>
						<td>
                             <?php if($is_editable){ ?>
                                <select class="impuesto">
                                    <?php
                                    foreach($impuestos_a as $id_tax => $name){
                                    ?>
                                    <option value="<?php echo $id_tax; ?>"<?php if($id_tax==$producto['id_tax_rules_group']){ ?> selected="selected"<?php } ?>><?php echo $name; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                             <?php } ?>
						</td>

						<td><?php if($is_editable){ ?><input class="precio" style="width:70px;" type="text" value="<?php echo $precio; ?>">&nbsp;€ <?php } ?></td>
                        <td  style="text-align: center" ><span class="from_quantity"><?php echo $producto['from_quantity']; ?></span></td>
						<td><?php if($is_editable){ ?><input class="reference" style="width:100px;" maxlength="32" type="text" value="<?php echo $producto['reference']; ?>"><?php } ?></td>
						<td><?php if($is_editable){ ?><input class="ean13" style="width:100px;" type="text" value="<?php echo $producto['ean13']; ?>"><?php } ?></td>
						<td><?php if($is_editable){ ?><input class="comentarios" style="width:150px;" type="text" value="<?php echo $producto['comentarios']; ?>"><?php } ?></td>
						<td>
							<div style="float:left; margin-right:10px;"><?php if ($is_editable){?><input class="guardar-producto" type="button" value="Guardar"><?php }?></div>
							<div style="float:left;" class="status"></div>
						</td>
						<td>
							<strong>
								<?php
								if($producto['reduction_type']=='percentage'){
									echo $precio*(1-$producto['reduction']);
								?>
									€
									(<?php echo $producto['reduction']*100; ?>%)
								<?php
								}
								?>
							</strong>
						</td>
					</tr>
				
				
				
				<?php if($combinaciones && $is_editable){
				    $ic=0;
				    foreach ($combinaciones as $combinacion){


				        $diff_precio_combi=$combinacion['price'];
				        switch($producto['id_tax_rules_group']){
				            case $id_tax_rule_group_21:
				                $diff_precio_combi*=impuesto_21;
				                break;
				            case $id_tax_rule_group_10:
				                $diff_precio_combi*=impuesto_10;
				                break;
				            case $id_tax_rule_group_4:
				                $diff_precio_combi*=impuesto_4;
				                break;
				        }
				        
				        $diff_precio_combi=round($diff_precio_combi,2);
				        
				        $precio_final_prod=$precio+$diff_precio_combi;

				        $id_cantidad=1;
				        if(isset($combinacion['from_quantity'])){
                            $id_cantidad=$combinacion['from_quantity'];
                        }
				        
				 ?>
            				<tr class="<?php if($ic%2==0){ ?> combination_row_par <?php }else{ ?>combination_row_impar <?php }?>" data-id-combinacion="<?php echo $combinacion['id_product_attribute']; ?>">
            						<td style="text-align:center;">
                                        <input type="checkbox" name="ids_combinaciones[]" value="<?php echo $combinacion['id_product'].'-'.$combinacion['id_product_attribute'].'-'.$id_cantidad; ?>" />
            							<?php
            							if(!is_null($producto['reduction_type']) || !is_null($combinacion['reduction_type'])){
            							?>
            								<span style="color:#FF0000; font-weight:bold;">D</span>
            							<?php
            							}
            							?>
            						</td>
            						<td><?php echo $combinacion['id_visual'] ?></td>
            						<td><?php echo $combinacion['name'].' ('.implode(',',$combinacion['attributes']).') '; ?></td>
            						<td style="text-align: center;">
            							-------
            						</td>
            						<td><?php if ( $combinacion['is_editable']){?><input class="precio" style="width:70px;" type="text" value="<?php echo  $diff_precio_combi; ?>">&nbsp;€<?php } ?></td>
                                     <td  style="text-align: center" ><span class="from_quantity"><?php echo $combinacion['from_quantity']; ?></span></td>
            						<td><?php if ( $combinacion['is_editable']){?><input class="reference" style="width:100px;" maxlength="32" type="text" value="<?php echo $combinacion['reference']; ?>"><?php } ?></td>
            						<td><?php if ( $combinacion['is_editable']){?><input class="ean13" style="width:100px;" type="text" value="<?php echo $combinacion['ean13']; ?>"><?php } ?></td>
            						<td style="text-align: center;"><span class="comentarios" style="width:150px;" type="text"> ---- </span></td>
            						<td>
            							<div style="float:left; margin-right:10px;"><?php if ( $combinacion['is_editable']){?><input class="guardar-combinacion" type="button" value="Guardar"><?php } ?></div>
            							<div style="float:left;" class="status"></div>
            						</td>
            						<td>
            							<strong>
            								<?php
            								
            								if($combinacion['reduction_type']=='percentage'){//tiene descuento la combinación
            								    echo $precio_final_prod*(1-$combinacion['reduction']);
            								    ?> € (<?php echo $combinacion['reduction']*100; ?>%)
            								    
            								<?php 
            								}elseif($producto['reduction_type']=='percentage'){//si no miramos el producto entero
            								    echo $precio_final_prod*(1-$producto['reduction']);
            								?> € (<?php echo $producto['reduction']*100; ?>%)
            								<?php
            								}else{
            								    echo $precio_final_prod ?> € <?php 
            								}
            								?>
            							</strong>
            						</td>
            					</tr>
				
				<?php
    					$ic++;
    				}
				}
				?>
				
				
				<?php
					$i++;
				}
				?>
				
			</table>
		</form>
	<?php
	}
	?>
</body>
</html>

<?php 


function cambio_precios_precio_combinaciones ($id_product,$id_combinacion){
    
    global $link;
    global $id_group,$id_tax_rule_group_4,$id_tax_rule_group_21,$id_tax_rule_group_10;
   
    $producto=mysqli_fetch_assoc(mysqli_query($link,"
		SELECT p.id_tax_rules_group, ps.price, sp.reduction, sp.reduction_type
		FROM "._DB_PREFIX_."product p
		LEFT JOIN "._DB_PREFIX_."product_shop ps ON p.id_product=ps.id_product AND ps.id_shop=".id_shop."
		LEFT JOIN "._DB_PREFIX_."specific_price sp ON (sp.id_product=p.id_product AND sp.id_product_attribute=0 AND sp.id_group=".$id_group.")
		WHERE p.id_product=".$id_product."      
	"));
  
    $combinacion=mysqli_fetch_assoc(mysqli_query($link,'SELECT product_attribute_shop.price, sp.reduction, sp.reduction_type
				FROM `'._DB_PREFIX_.'product_attribute` pa
				INNER JOIN '._DB_PREFIX_.'product_attribute_shop product_attribute_shop
		         ON (product_attribute_shop.id_product_attribute = pa.id_product_attribute AND product_attribute_shop.id_shop = '.(int)id_shop.')
                LEFT JOIN '._DB_PREFIX_.'specific_price sp ON (sp.id_product=pa.id_product AND pa.`id_product_attribute`=sp.id_product_attribute AND sp.id_group='.(int)$id_group.')
				WHERE pa.`id_product_attribute` = '.(int)$id_combinacion
				));
    
    
    $precio=$producto['price'];
    $diff_precio_combi=$combinacion['price'];
/*    switch($producto['id_tax_rules_group']){
        case $id_tax_rule_group_21:
            $precio*=impuesto_21;
            $diff_precio_combi*=impuesto_21;
            break;
        case $id_tax_rule_group_10:
            $precio*=impuesto_10;
            $diff_precio_combi*=impuesto_10;
            break;
        case $id_tax_rule_group_4:
            $precio*=impuesto_4;
            $diff_precio_combi*=impuesto_4;
            break;
    }*/
    
    $precio=round($precio,2);
    $diff_precio_combi=round($diff_precio_combi,2);
    
    $precio_final_prod=$precio+$diff_precio_combi;
    
    if($combinacion['reduction_type']=='percentage'){
        $precio_final_prod= $precio_final_prod*(1-$combinacion['reduction']);
         return $precio_final_prod.' € ('.($combinacion['reduction']*100).'%)';
    }elseif($producto['reduction_type']=='percentage'){
        $precio_final_prod= $precio_final_prod*(1-$producto['reduction']);
        return $precio_final_prod.' € ('.($producto['reduction']*100).'%)';
    }else{
        return $precio_final_prod.' €';
    }
    
}

    function cambio_precios_combinaciones ($id_producto){
        
       global $link;
       global $id_group; 
        $sql = 'SELECT pa.id_product_attribute, pa.*, product_attribute_shop.*, ag.`id_attribute_group`, ag.`is_color_group`, agl.`name` AS group_name, al.`name` AS attribute_name,
					a.`id_attribute`,pl.name,sp.reduction, sp.reduction_type,sp.from_quantity
				FROM `'._DB_PREFIX_.'product_attribute` pa
				INNER JOIN '._DB_PREFIX_.'product_attribute_shop product_attribute_shop
		         ON (product_attribute_shop.id_product_attribute = pa.id_product_attribute AND product_attribute_shop.id_shop = '.(int)id_shop.')
				LEFT JOIN `'._DB_PREFIX_.'product_attribute_combination` pac ON pac.`id_product_attribute` = pa.`id_product_attribute`
				LEFT JOIN `'._DB_PREFIX_.'attribute` a ON a.`id_attribute` = pac.`id_attribute`
				LEFT JOIN `'._DB_PREFIX_.'attribute_group` ag ON ag.`id_attribute_group` = a.`id_attribute_group`
				LEFT JOIN `'._DB_PREFIX_.'attribute_lang` al ON (a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '.(int)id_lang.')
				LEFT JOIN `'._DB_PREFIX_.'attribute_group_lang` agl ON (ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = '.(int)id_lang.')
                LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = pa.`id_product` AND pl.`id_lang` = '.(int)id_lang.' AND pl.id_shop='.(int)id_shop.')
                LEFT JOIN '._DB_PREFIX_.'specific_price sp ON (sp.id_product=pa.id_product AND pa.`id_product_attribute`=sp.id_product_attribute AND sp.id_group='.(int)$id_group.')
				WHERE pa.`id_product` = '.(int)$id_producto.'
				GROUP BY pa.`id_product_attribute`, ag.`id_attribute_group`
				ORDER BY pa.`id_product_attribute` ASC, sp.from_quantity ASC';
        
        
        $combinaciones=mysqli_query($link,$sql);
        
        if(!mysqli_num_rows($combinaciones)){
            return false;
        }else{
            $comb_array = array();
            foreach ($combinaciones as $k => $combination) {
                $comb_array[$combination['id_product_attribute']]['id_product_attribute'] = $combination['id_product_attribute'];
                $comb_array[$combination['id_product_attribute']]['attributes'][] = $combination['group_name'].'-'.$combination['attribute_name'];
                
                $comb_array[$combination['id_product_attribute']]['price'] = $combination['price'];
                
                $comb_array[$combination['id_product_attribute']]['unit_impact'] = $combination['unit_price_impact'];
                $comb_array[$combination['id_product_attribute']]['reference'] = $combination['reference'];
                $comb_array[$combination['id_product_attribute']]['ean13'] = $combination['ean13'];
               
                $comb_array[$combination['id_product_attribute']]['name'] = $combination['name'];
                $comb_array[$combination['id_product_attribute']]['default_on'] = $combination['default_on'];
                $comb_array[$combination['id_product_attribute']]['id_product'] = $combination['id_product'];
                $comb_array[$combination['id_product_attribute']]['reduction'] = $combination['reduction'];
                $comb_array[$combination['id_product_attribute']]['reduction_type'] = $combination['reduction_type'];
                $comb_array[$combination['id_product_attribute']]['from_quantity'] = $combination['from_quantity'];
            }
            
             $comb_array;
        }

        $comb_array_final=array();
        foreach ($comb_array as $combinacion){

            $combinacion['id_visual']=$combinacion['id_product'].'-'.$combinacion['id_product_attribute'];
            $descuentos_cantidad_lista=cambio_precios_combinacion_getDescuentosCantidad($combinacion['id_product_attribute']);
            $combinacion['is_editable']=true;
            $count=1;
            if($descuentos_cantidad_lista){


                foreach ($descuentos_cantidad_lista as $desc_cantidad){


                    if($desc_cantidad['from_quantity'] >1){
                        $combinacion['id_visual']=$combinacion['id_product'].'-'.$combinacion['id_product_attribute'].'<span style="font-weight:bold">(*Q'.$desc_cantidad['from_quantity'] .')</span>';
                    }
                    $combinacion['reduction']=$desc_cantidad['reduction'];
                    $combinacion['from_quantity']=$desc_cantidad['from_quantity'];


                   if($count>1){
                       $combinacion['is_editable']=false;
                    }
                    $comb_array_final[]=$combinacion;
                    $count++;
                }

            }else{
                $comb_array_final[]=$combinacion;
            }

            //
        }
       return $comb_array_final;
    }
    
    function cambio_precios_getNumDescuentos($id_producto){
        global $link;
        global $id_group;
        $sql = 'SELECT count(*)	as num_descuentos
               FROM '._DB_PREFIX_.'specific_price sp WHERE  
               sp.id_product='.(int)$id_producto.' AND sp.id_product_attribute=0 AND sp.id_group='.(int)$id_group;


        $num_descuentos=mysqli_fetch_assoc(mysqli_query($link,$sql));
        $num_descuentos=$num_descuentos['num_descuentos'];
        return $num_descuentos;

    }

    function cambio_precios_combinacion_getDescuentosCantidad($id_combi){
        global $link;
        global $id_group;
        $sql = 'SELECT from_quantity, reduction
                   FROM '._DB_PREFIX_.'specific_price sp WHERE  
                   sp.id_product_attribute='.(int)$id_combi.' AND sp.id_group='.(int)$id_group.' ORDER by from_quantity ASC';


        $descuentos_combi=array();
        $query=mysqli_query($link,$sql);
        while($row=@mysqli_fetch_assoc($query)){
            $descuentos_combi[]=$row;//array($row['from_quantity'],$row['reduction']);
        }
        return $descuentos_combi;

    }

    function cambio_precios_tieneCombinaciones($id_producto){
        global $link;
        global $id_group;
        $sql = 'SELECT count(*)	as num_combi
                   FROM '._DB_PREFIX_.'product_attribute pa WHERE  
                   pa.id_product='.(int)$id_producto;


        $num_combi=mysqli_fetch_assoc(mysqli_query($link,$sql));
        $num_combi=$num_combi['num_combi'];
        return $num_combi>0;

    }
    
?>