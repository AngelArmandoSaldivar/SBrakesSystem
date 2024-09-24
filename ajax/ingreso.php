<?php 
require_once "../modelos/Ingreso.php";
if (!isset($_SESSION["nombre"])){
	$ingreso=new Ingreso();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];
	$acceso = $_SESSION['acceso'];

$idingreso=isset($_POST["idingreso"])? limpiarCadena($_POST["idingreso"]):"";
$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$serie_comprobante=isset($_POST["serie_comprobante"])? limpiarCadena($_POST["serie_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$total_compra=isset($_POST["total_compra"])? limpiarCadena($_POST["total_compra"]):"";

switch ($_GET["op"]) {

	case 'guardaryeditar':
	if (empty($idingreso)) {		
		$rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$fecha_hora,$impuesto,$total_compra,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_compra"], $idsucursal, $_POST["idarticuloSucursal"], $_POST["descuento"]);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
	break;
	
	case 'registroTemporal':
		$rspta = $ingreso->registroTemporal($idsucursal, $idusuario);
		echo $rspta;
		break;

	case 'actualizaProveedorIngreso':
		$idproveedor = $_POST["idProveedor"];
		$idRecepcion = $_POST["idIngreso"];
		$rspta = $ingreso->actualizaProveedorIngreso($idRecepcion, $idproveedor);
		echo $rspta ? "Proveedor actualizado correctamente" : "No se pudo actualizar el proveedor";
		break;

	case 'actualizaSerieIngreso':
		$idRecepcion = $_POST["idIngreso"];
		$serie = $_POST["serie"];
		$rspta = $ingreso->actualizaSerieIngreso($idRecepcion, $serie);
		echo $rspta ? "Serie actualizada correctamente" : "No se pudo actualizar la serie";
		break;

	case 'guardarProductoIngreso':
		$idIngreso=$_GET['idingreso'];
		$idarticulo=$_GET['idArticulo'];
		$articulo=$_GET['codigoArticulo'];
		$fmsi=$_GET['fmsiArticulo'];
		$marca=$_GET['marcaArticulo'];
		$descripcion=$_GET['descripcionArticulo'];
		$publico=$_GET['costoArticulo'];
		$stock=$_GET['cantidadArticulo'];
		$idproveedor=$_GET['idproveedor'];
		$datetime=$_GET['dateTime'];
		$folio=$_GET['serieComprobante'];
		$idarticuloSucursal=$_GET['idarticuloSucursal'];
		$rspta=$ingreso->addProductoIngreso($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idIngreso, $idproveedor, $datetime, $folio, $idsucursal, $idarticuloSucursal);		
		echo $rspta ? "Producto agregado correctamente" : "No se pudo agregar el producto";
		break;
	case 'editarGuardarProductoIngreso':
		$idarticulo=$_GET['idarticulo'];	
		$idIngreso=$_GET['idIngreso'];
		$precioViejo=$_GET['precioViejo'];
		$stockViejo=$_GET['stockViejo'];

		$descripcionProducto = $_GET["descripcion"];
		$cantidadProducto = $_GET["cantidad"];
		$precioProducto = $_GET["precio"];

		$rspta=$ingreso->editarGuardarProductoIngreso($descripcionProducto, $cantidadProducto, $precioProducto, $idarticulo, $idIngreso, $precioViejo, $stockViejo);
		echo $rspta ? "Producto actualizado correctamente": "No se pudo actualizar el producto";
		break;

	case 'anular':
		$rspta=$ingreso->anular($idingreso);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	case 'eliminarProductoIngreso':
		$idIngresoProducto=$conexion->real_escape_string($_POST['idingreso']);
		$idProductoIngreso = $conexion->real_escape_string($_POST['idarticulo']);
		$stock = $conexion->real_escape_string($_POST['stock']);
		$precio_compra = $conexion->real_escape_string($_POST['precio_compra']);
		
		$rspta=$ingreso->eliminarProductoIngreso($idIngresoProducto, $idProductoIngreso, $stock, $precio_compra);		
		break;
	
	case 'mostrar':
		$rspta=$ingreso->mostrar($idingreso);
		echo json_encode($rspta);
		break;
	case 'mostrarProductoIngreso':
		$idarticulo=$_GET['idarticulo'];
		$idIngreso=$_GET['idIngreso'];
		$rspta=$ingreso->mostrarProductoEdit($idarticulo, $idIngreso);
		echo json_encode($rspta);
	break;
	
	case 'mostrarDetalleRecepcion' :
		$id=$_GET['id'];

		$rspta=$ingreso->listarDetalle($id);
		$total=0;
		$des = 0;
		$sub = 0;
		$total1 = 0;
		echo ' <thead style="background-color:#A9D0F5">
		<th>Opciones</th>
        <th>Código</th>
        <th>Clave</th>
		<th>Fmsi</th>
		<th>Descripción</th>
		<th>Cantidad</th>
        <th>Costo</th>
		<th>Descuento</th>
        <th>Subtotal</th>
		<th>Acciones</th>
		</thead>';
		while ($reg=$rspta->fetch_object()) {
			$des = $reg->descuento / 100;
			$total1 = $reg->cantidad * $reg->precio_compra;
			$sub = ($total1 - ($total1 * $des));
			echo '<tr class="filas" id="filas" style="font-size:12px;">
			<td><button style="width: 40px;" title="Eliminar" type="button" class="btn btn-danger btn-xs" onclick="eliminarProductoIngreso('.$reg->idingreso.', '.$reg->idarticulo.', '.$reg->cantidad.', '.$reg->precio_compra.')">X</button></td>			
			<td>'.$reg->idarticulo.'</td>
			<td>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.substr($reg->descripcion, 0, 30).'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_compra, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td><a data-toggle="modal" title="Editar" href="#editProductRecepcion"><button style="width: 40px;" type="button" class="btn btn-warning btn-xs" onclick="editarProductoRecepcion('.$reg->idarticulo.', '.$reg->idingreso.')"><i class="fa fa-pencil"></i></button></a></td></tr>'
			;
			number_format($total+=$sub, 2);
		}
		$cont++;
		echo '<tfoot style="background-color:#A9D0F5">			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>			
			<th>TOTAL</th>
			<th><p id="total">$'.number_format($total, 2).'</p><input type="hidden" name="total_compra" id="total_compra"></th>
			<th></th>
		</tfoot>';
	break;

	case 'listarDetalle':
		//recibimos el idingreso
		$id=$_GET['id'];
		
		echo "ID: " . $id."\n";

		$rspta=$ingreso->listarDetalle($id);
		$total=0;
		$des = 0;
		$sub = 0;
		$total1 = 0;
		echo ' <thead style="background-color:#A9D0F5; font-size: 11px;">
        <th>Opciones</th>
        <th>Código</th>
        <th>Clave</th>
		<th>Fmsi</th>
		<th>Descripción</th>
		<th>Cantidad</th>
        <th>Precio Compra</th>
		<th>Descuento</th>
        <th>Subtotal</th>
		<th>Acciones</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			$des = $reg->descuento / 100;
			$total1 = $reg->cantidad * $reg->precio_compra;
			$sub = ($total1 - ($total1 * $des));
			echo '<tr class="filas" id="filas" style="font-size:12px;">
			<td></td>
			<td>'.$reg->idarticulo.'</td>
			<td>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.substr($reg->descripcion, 0, 30).'</td>	
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_compra,2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($sub,2).'</td>	
			<td></td>		
			</tr>';
			$total += $sub;
		}
		echo '<tfoot style="background-color:#A9D0F5">         
         <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
         <th></th>
		 <th>Total</th>		 		 
         <th><p id="total">$'.number_format($total,2).'</p><input type="hidden" name="total_compra" id="total_compra"></th>
		 <th></th>
       </tfoot>';
		break;

    case 'listar':
			//$consulta="SELECT i.idsucursal, i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC LIMIT 40";
			
			$consulta = $ingreso->filtroPaginado(50, 0, "", "", "");
			$termino= "";
			
			if(!empty($_POST['ingresos']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){				
				$termino=$conexion->real_escape_string($_POST['ingresos']);
				$consulta=$ingreso->filtroPaginado(5,0, $termino, "", "");
			}
			else if(empty($_POST['ingresos']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$consulta=$ingreso->filtroPaginado($limites,0, "", "", "");
			}
			else if(!empty($_POST['ingresos']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){								
				$termino=$conexion->real_escape_string($_POST['ingresos']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
				
				$consulta=$ingreso->filtroPaginado($limites,0, $termino, $fecha_inicio, "");
			}
			else if(!empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){								
				$termino=$conexion->real_escape_string($_POST['busqueda']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros = $conexion->real_escape_string($_POST['inicio_registros']);
				
				$consulta=$ingreso->filtroPaginado($limites,$inicio_registros, $termino, $fecha_inicio, "");
			}
			else if(empty($_POST['ingresos']) && !empty($_POST['total_registros']) && empty($_POST["inicio_registros"]) && !empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){								
				$limites=$conexion->real_escape_string($_POST['total_registros']);							
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
				
				$consulta=$ingreso->filtroPaginado($limites,0, "", $fecha_inicio, "");
			}
			else if(!empty($_POST['ingresos']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])){
				$termino=$conexion->real_escape_string($_POST['ingresos']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($limites,0, $termino, "", "");
			}
			else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {					
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);				
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$ingreso->filtroPaginado(5,0, $busqueda, $fecha_inicio, "");

			}
			else if(!empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);														
				$consulta=$ingreso->filtroPaginado(5,0, "", $fecha_inicio, "");

			}
			else if(empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$consulta=$ingreso->filtroPaginado(5,$inicio_registros, "", "", "");

			}
			else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado(5,$inicio_registros, $busqueda, "", "");

			}
			else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado($limites,$inicio_registros, $busqueda, "", "");

			}
			else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$ingreso->filtroPaginado(5,$inicio_registros, $busqueda, $fecha_inicio, "");

			}
			else if(empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$ingreso->filtroPaginado(5,$inicio_registros, "", $fecha_inicio, "");

			}
			else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, "", "", "");

			}
			else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, "", "", "");

			}
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, "");

			}

			//Solo fecha fin, pagina 1
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$consulta=$ingreso->filtroPaginado(5,0, "", "", $fecha_fin);
			}
			//Fecha fin y busqueda , pagina 1
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado(50,0, $busqueda, "", $fecha_fin);
			}
			//Fecha fin, busqueda, limites, pagina 1
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,0, $busqueda, "", $fecha_fin);
			}
			//Fecha fin, limites, pagina 1
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,0, "", "", $fecha_fin);
			}
			//Solo fecha inicio, fecha fin
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
				$consulta=$ingreso->filtroPaginado(5,0, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, busquedas
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado(5,0, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, busquedas, limites
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,0, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,0, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha fin, pagina
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$consulta=$ingreso->filtroPaginado(5,$inicio_registros, "", "", $fecha_fin);
			}
			//Solo fecha inicio, pagina, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, "", "", $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, pagina, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, pagina, limites, busqueda
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha fin, pagina, limites, busqueda
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$ingreso->filtroPaginado($total_registros,$inicio_registros, $busqueda, "", $fecha_fin);
			}

			$consultaBD=$consulta;
			if($consultaBD->num_rows>=1){
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:11px' id='example'>
					<thead class='table-light'>
						<tr>							
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Entrada</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Proveedor</th>
							<th class='bg-info' scope='col'>Usuario</th>
							<th class='bg-info' scope='col'>Total</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";

				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["idsucursal"] == $idsucursal) {
						if($acceso == "1") {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFactura.php?id=';
							}
							$miles = number_format($fila['total_compra'], 2);
							
							$ventas_pagina = 3;
							$paginas = 13;

							echo "<tr>								
								<td>".$fila['folio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['estado']."</td>
								<td><p>".$fila['proveedor']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".number_format($fila["total_compra"],2)."</td>
								<td>
									<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar recepcion' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idingreso"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar recepcion' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idingreso"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar recepcion' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idingreso"].")'><i class='fa fa-close'></i></button>
								</td>
							</tr>
							";
						} else {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFactura.php?id=';
							}
							$miles = number_format($fila['total_compra']);
							
							$ventas_pagina = 3;
							$paginas = 13;

							echo "<tr>								
								<td>".$fila['folio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['estado']."</td>
								<td><p>".$fila['proveedor']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".number_format($fila["total_compra"],2)."</td>
								<td>
									<div class='emergente'>										
										<span data-tooltip='Mostrar recepción'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idingreso"].")'><i class='fa fa-eye'></i></button></span>
										<span data-tooltip='Eliminar recepción'><button class='btn btn-danger btn-xs' onclick='anular(".$fila["idingreso"].")'><i class='fa fa-close'></i></button></td></span>
									</div>
							</tr>
							";
						}
							
					} 
				}
				echo "</tbody>
				<tfoot>
					<tr>												
						<th class='bg-info' scope='col'>Folio</th>
						<th class='bg-info' scope='col'>Entrada</th>
						<th class='bg-info' scope='col'>Estatus</th>
						<th class='bg-info' scope='col'>Proveedor</th>
						<th class='bg-info' scope='col'>Usuario</th>
						<th class='bg-info' scope='col'>Total</th>
						<th class='bg-info' scope='col'>Acciones</th>
					</tr>
				</tfoot>
				</table>
				";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
			}


		break;

		case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarp();

			echo '<option value="" disabled selected>Seleccionar proveedor</option>';
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
		break;

		case 'listarArticulos':
			$consulta="SELECT m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
			a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
			a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock, a.imagen
			FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
			INNER JOIN marca m ON a.marca = m.idmarca
			ORDER BY a.stock > 0 DESC, m.descripcion ASC LIMIT 50";
					$termino= "";
					if(isset($_POST['productos']))
					{
						$termino=$conexion->real_escape_string($_POST['productos']);
						usleep(10000);
						$consulta="SELECT m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
						a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
						a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock, a.imagen
						FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
						INNER JOIN marca m ON a.marca = m.idmarca
						WHERE 
						a.codigo LIKE '%".$termino."%' OR
						a.fmsi LIKE '%".$termino."%' OR
						a.barcode LIKE '%".$termino."%' OR
						a.descripcion LIKE '%".$termino."%' OR
						m.descripcion LIKE '%".$termino."%' ORDER BY a.stock > 0 DESC, m.descripcion ASC LIMIT 50";
					}
					$consultaBD=$conexion->query($consulta);
					if($consultaBD->num_rows>=1){
						echo "						
						<table class='responsive-table table table-hover table-bordered' style='font-size:11px'>
							<thead class='table-light'>
								<tr>
									<th class='bg-info' scope='col'>Clave</th>
									<th class='bg-info' scope='col'>FMSI</th>
									<th class='bg-info' scope='col'>Marca</th>
									<th class='bg-info' scope='col'>Descripción</th>
									<th class='bg-info' scope='col'>Costo</th>
									<th class='bg-info' scope='col'>Publico Mostrador</th>
									<th class='bg-info' scope='col'>Taller</th>
									<th class='bg-info' scope='col'>Crédito Taller</th>
									<th class='bg-info' scope='col'>Mayoreo</th>
									<th class='bg-info' scope='col'>Stock</th>									
									<th class='bg-info' scope='col'>Acciones</th>
								</tr>
							</thead>
						<tbody>";
						while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){							
							$costoMiles = number_format($fila['costo']);
							$publicMiles = number_format($fila['publico']);
							$tallerMiles = number_format($fila['taller']);
							$creditoMiles = number_format($fila['credito_taller']);
							$mayoreoMiles = number_format($fila['mayoreo']);
							$descrip = $fila['descripcion'];
							$delit = substr($descrip, 0,30);
							$tipo_precio = "costo";
							if($fila["idsucursal"] == $idsucursal) {
								if($fila["stock"] >=1) {
									echo "<tr style='color:blue;'>
										<td>".$fila['codigo']."</td>
										<td>".$fila['fmsi']."</td>
										<td>".$fila['descripcionMarca']."</td>
										<td>".$delit."...</td>
										<td><p>$ ".$costoMiles."</p></td>
										<td><p>$ ".$publicMiles."</p></td>
										<td><p>$ ".$tallerMiles."</p></td>
										<td><p>$ ".$creditoMiles."</p></td>
										<td><p>$ ".$mayoreoMiles."</p></td>
										<td><p>".$fila["stock"]." pz</p></td>										
										<td><button style='width: 40px' class='btn btn-warning btn-xs' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["idsucursal"]."\" )'><span class='fa fa-plus'></span></button></td>
									</tr>";
								} else {
									echo "<tr style='color:red;'>
										<td>".$fila['codigo']."</td>
										<td>".$fila['fmsi']."</td>
										<td>".$fila['descripcionMarca']."</td>
										<td>".$delit."...</td>
										<td><p>$ ".$costoMiles."</p></td>
										<td><p>$ ".$publicMiles."</p></td>
										<td><p>$ ".$tallerMiles."</p></td>
										<td><p>$ ".$creditoMiles."</p></td>
										<td><p>$ ".$mayoreoMiles."</p></td>
										<td><p>".$fila["stock"]." pz</p></td>										
										<td><button style='width: 40px' class='btn btn-warning btn-xs' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["idsucursal"]."\" )'><span class='fa fa-plus'></span></button></td>
									</tr>";
								}					
							}
						}
						echo "</tbody>
						<tfoot>
							<tr>								
								<th class='bg-info' scope='col'>Clave</th>
								<th class='bg-info' scope='col'>FMSI</th>
								<th class='bg-info' scope='col'>Marca</th>
								<th class='bg-info' scope='col'>Descripción</th>
								<th class='bg-info' scope='col'>Costo</th>
								<th class='bg-info' scope='col'>Publico Mostrador</th>
								<th class='bg-info' scope='col'>Taller</th>
								<th class='bg-info' scope='col'>Crédito Taller</th>
								<th class='bg-info' scope='col'>Mayoreo</th>
								<th class='bg-info' scope='col'>Stock</th>									
								<th class='bg-info' scope='col'>Acciones</th>
							</tr>
						</tfoot>
						</table>";
					}else{
						echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
						echo "<br><br>";
					}

		break;

		case 'listarProductosEdit':
				$termino = '';
				$consulta="SELECT m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
				a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
				a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock, a.imagen
				FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
				INNER JOIN marca m ON a.marca = m.idmarca
				ORDER BY a.stock > 0 DESC, m.descripcion ASC LIMIT 50";
				$termino;
				if(isset($_POST['productosEdit']))
				{
					$termino=$conexion->real_escape_string($_POST['productosEdit']);
					usleep(10000);
					$consulta="SELECT m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
					a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
					a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock, a.imagen
					FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
					INNER JOIN marca m ON a.marca = m.idmarca
					WHERE 
					a.codigo LIKE '%".$termino."%' OR
					a.fmsi LIKE '%".$termino."%' OR
					a.barcode LIKE '%".$termino."%' OR
					a.descripcion LIKE '%".$termino."%' OR
					m.descripcion LIKE '%".$termino."%' ORDER BY a.stock > 0 DESC, m.descripcion ASC LIMIT 50";
				}
				$consultaBD=$conexion->query($consulta);
				if($consultaBD->num_rows>=1){
					echo "
					<table class='responsive-table table table-hover table-bordered' style='font-size:11px'>
						<thead class='table-light'>
							<tr>
								<th class='bg-info' scope='col'>Clave</th>
								<th class='bg-info' scope='col'>FMSI</th>
								<th class='bg-info' scope='col'>Marca</th>
								<th class='bg-info' scope='col'>Descripción</th>
								<th class='bg-info' scope='col'>Costo</th>
								<th class='bg-info' scope='col'>Publico Mostrador</th>
								<th class='bg-info' scope='col'>Taller</th>
								<th class='bg-info' scope='col'>Crédito Taller</th>
								<th class='bg-info' scope='col'>Mayoreo</th>
								<th class='bg-info' scope='col'>Stock</th>									
								<th class='bg-info' scope='col'>Acciones</th>
							</tr>
						</thead>
					<tbody>";
					while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){							
						$costoMiles = number_format($fila['costo']);
						$publicMiles = number_format($fila['publico']);
						$tallerMiles = number_format($fila['taller']);
						$creditoMiles = number_format($fila['credito_taller']);
						$mayoreoMiles = number_format($fila['mayoreo']);
						$descrip = $fila['descripcion'];
						$delit = substr($descrip, 0,30);
						$selectTypes ="";
						if($fila["idsucursal"] == $idsucursal) {
							if($fila["stock"] >=1 ) {
								echo "<tr style='color:blue;'>
									<td>".$fila['codigo']."</td>
									<td>".$fila['fmsi']."</td>
									<td>".$fila['descripcionMarca']."</td>
									<td>".$delit."...</td>
									<td><p>$ ".$costoMiles."</p></td>
									<td><p>$ ".$publicMiles."</p></td>
									<td><p>$ ".$tallerMiles."</p></td>
									<td><p>$ ".$creditoMiles."</p></td>
									<td><p>$ ".$mayoreoMiles."</p></td>
									<td><p>".$fila["stock"]." pz</p></td>										
									<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$costoMiles."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
								</tr>";
							} else if($fila["stock"] < 1){
								echo "<tr style='color:red;'>
									<td>".$fila['codigo']."</td>
									<td>".$fila['fmsi']."</td>
									<td>".$fila['descripcionMarca']."</td>
									<td>".$delit."...</td>
									<td><p>$ ".$costoMiles."</p></td>
									<td><p>$ ".$publicMiles."</p></td>
									<td><p>$ ".$tallerMiles."</p></td>
									<td><p>$ ".$creditoMiles."</p></td>
									<td><p>$ ".$mayoreoMiles."</p></td>
									<td><p>".$fila["stock"]." pz</p></td>
									<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$costoMiles."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>";
							}
						}
					}
					echo "</tbody>
					<tfoot>
						<tr>								
							<th class='bg-info' scope='col'>Clave</th>
							<th class='bg-info' scope='col'>FMSI</th>
							<th class='bg-info' scope='col'>Marca</th>
							<th class='bg-info' scope='col'>Descripción</th>
							<th class='bg-info' scope='col'>Costo</th>
							<th class='bg-info' scope='col'>Publico Mostrador</th>
							<th class='bg-info' scope='col'>Taller</th>
							<th class='bg-info' scope='col'>Crédito Taller</th>
							<th class='bg-info' scope='col'>Mayoreo</th>
							<th class='bg-info' scope='col'>Stock</th>									
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</tfoot>
					</table>";
				}else{
					echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
					echo "<br><br>";
				}
			break;
}
}
 ?>