<?php 
require_once "../modelos/Servicios.php";
if (strlen(session_id())<1)
	session_start();
	//SESIONES
	$idsucursal = $_SESSION['idsucursal'];
	$acceso = $_SESSION['acceso'];

$servicio = new Servicios();

$idservicio=isset($_POST["idservicio"])? limpiarCadena($_POST["idservicio"]):"";
$idauto=isset($_POST["idauto"])? limpiarCadena($_POST["idauto"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$forma=isset($_POST["forma"])? limpiarCadena($_POST["forma"]):"";
$forma2=isset($_POST["forma2"])? limpiarCadena($_POST["forma2"]):"";
$forma3=isset($_POST["forma3"])? limpiarCadena($_POST["forma3"]):"";
$banco=isset($_POST["banco"])? limpiarCadena($_POST["banco"]):"";
$banco2=isset($_POST["banco2"])? limpiarCadena($_POST["banco2"]):"";
$banco3=isset($_POST["banco3"])? limpiarCadena($_POST["banco3"]):"";
$importe=isset($_POST["importe"])? limpiarCadena($_POST["importe"]):"";
$importe2=isset($_POST["importe2"])? limpiarCadena($_POST["importe2"]):"";
$importe3=isset($_POST["importe3"])? limpiarCadena($_POST["importe3"]):"";
$ref=isset($_POST["ref"])? limpiarCadena($_POST["ref"]):"";
$ref2=isset($_POST["ref2"])? limpiarCadena($_POST["ref2"]):"";
$ref3=isset($_POST["ref3"])? limpiarCadena($_POST["ref3"]):"";
$total_servicio=isset($_POST["total_servicio"])? limpiarCadena($_POST["total_servicio"]):"";
$marca=isset($_POST["marcaAuto"])? limpiarCadena($_POST["marcaAuto"]):"";
$modelo=isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]):"";
$ano=isset($_POST["ano"])? limpiarCadena($_POST["ano"]):"";
$color=isset($_POST["color"])? limpiarCadena($_POST["color"]):"";
$kms=isset($_POST["kms"])? limpiarCadena($_POST["kms"]):"";
$placas=isset($_POST["placas"])? limpiarCadena($_POST["placas"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idservicio)) {
		$rspta=$servicio->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_servicio,$marca, $modelo, $ano, $color, $kms, $placas, $_POST["idarticulo"],$_POST["clave"],$_POST["marca"], $_POST["fmsi"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_servicio"],$_POST["descuento"], $idsucursal, $_POST["idsucursalArticulo"]);		
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}
	break;

	case 'guardarAuto' :
		$idcliente=$_GET['idcliente'];
		$placas=$_GET['placas'];
		$marca=$_GET['marca'];
		$modelo=$_GET['modelo'];
		$ano=$_GET['ano'];
		$color=$_GET['color'];
		$kms=$_GET['kms'];

		$rspta=$servicio->guardarAuto($idcliente, $placas, $marca, $modelo, $ano, $color, $kms);
		echo $rspta ? "Se guardo correctamente el auto": "No se pudo guardar";
		break;
	case 'ultimoAuto' :
		$rspta=$servicio->ultimoAuto();		
		while ($reg=$rspta->fetch_object()) {
			echo json_encode($reg->idauto);
		}
		break;

	case 'guardarCobro':		
		$idservicio=$_GET['idservicio'];
		$idcliente=$_GET['idcliente'];
		$importeCobro=$_GET['importeCobro'];
		$metodoPago=$_GET['metodoPago'];
		$banco=$_GET['banco'];		
		$referenciaCobro=$_GET['referenciaCobro'];
		$fechaCobro=$_GET['fechaCobro'];
		$rspta=$servicio->guardarCobro($metodoPago, $banco, $importeCobro, $referenciaCobro, $idservicio, $fechaCobro, $idsucursal, $idcliente);
		echo $rspta ? "Se guardo correctamente el pago": "No se pudo guardar";
		break;
	case 'editarCobro':
		$idpago=$_GET['idpago'];
		$idservicio=$_GET['idservicio'];
		$importeCobro=$_GET['importeCobro'];
		$metodoPago=$_GET['metodoPago'];
		$banco=$_GET['banco'];	
		$importeviejo = $_GET["importeviejo"];	
		$referenciaCobro=$_GET['referenciaCobro'];		
		$rspta=$servicio->editarPago($idpago, $metodoPago, $banco, $importeCobro, $referenciaCobro, $importeviejo, $idservicio);
		echo $rspta ? "Pago actualizado correctamente": "No se pudo actualizar";
		break;
	case 'eliminarCobro' :
		$idcobro=$_GET["idcobro"];
		$importe=$_GET['importe'];
		$idservicio=$_GET['idservicio'];
		$rspta=$servicio->eliminarCobro($idcobro, $importe, $idservicio);
		break;
	case 'mostrarPagoEdit':
		$idpago = $_GET["idpago"];
		$rspta=$servicio->mostrarPagoEdit($idpago);
		echo json_encode($rspta);
	break;

	case 'listarDetalleCobro':
		//recibimos el idventa
		$id=$_GET['id'];
		$totalCobro = 0;
		$rspta=$servicio->listarDetallesCobro($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">
				<th>Opciones</th>
				<th>ID PAGO</th>
				<th>Importe</th>
				<th>Método pago</th>
				<th>Banco</th>
				<th>Referencia</th>                        
				<th>Acciones</th>
	  	</thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filasCobro" id="filasCobro" style="font-size: 12px;">
			<td></td>
			<td>'.$reg->idpago.'</td>
			<td>$'.number_format($reg->importe).'</td>
			<td>'.$reg->forma_pago.'</td>
			<td>'.$reg->banco.'</td>
			<td>'.$reg->referencia.'</td>
			<td></td>';			
			$total = $total + $reg->importe;
		}			
		echo '<tfoot font-size: 12px;">
		<th></th>
         <th></th>
         <th></th>
		 <th></th>		 
		 <th>Total pagado</th>
		 <th>$'.number_format($total, 2).'</th>
		 <th></th>
		</tfoot>';
		break;

		case 'listarEditarDetalleCobro':
			//recibimos el idventa
			$id=$_GET['id'];
			$totalCobro = 0;
			$rspta=$servicio->listarDetallesCobro($id);
			$total=0;
			echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">
					<th>Opciones</th>
					<th>ID PAGO</th>
					<th>Importe</th>
					<th>Método pago</th>
					<th>Banco</th>
					<th>Referencia</th>                        
					<th>Acciones</th>
				</thead>';
			while ($reg=$rspta->fetch_object()) {
				echo '<tr class="filasCobro" id="filasCobro" style="font-size: 12px;">
				<td><button style="width: 40px;" title="Eliminar" id="btnEliminarCobro" name="btnEliminarCobro" type="button" class="btn btn-danger" onclick="eliminarCobro('.$reg->idpago.', '.$reg->importe.', '.$reg->idservicio.')">X</button></td>
				<td>'.$reg->idpago.'</td>
				<td>$'.number_format($reg->importe).'</td>
				<td>'.$reg->forma_pago.'</td>
				<td>'.$reg->banco.'</td>
				<td>'.$reg->referencia.'</td>
				<td><a data-toggle="modal" href="#modalAddCobro"><button style="width: 40px;" type="button" title="editar" class="btn btn-warning" onclick="mostrarPagoEdit('.$reg->idpago.')"><i class="fa fa-pencil"></i></button></a></td>';
				$total = $total + $reg->importe;
			}
			echo '<tfoot font-size: 12px;">
			<th></th>
				<th></th>
				<th></th>
				<th></th>		 
				<th>Total pagado</th>
				<th>$'.number_format($total, 2).'</th>
				<th></th>
			</tfoot>';
			break;

	case 'editarGuardarProductoServicio':
		$idarticulo=$_GET['idarticulo'];
		$idservicio=$_GET['idservicio'];
		$precioViejo=$_GET['precioViejo'];
		$stockViejo=$_GET['stockViejo'];

		$descripcionProducto = $_GET["descripcion"];
		$cantidadProducto = $_GET["cantidad"];
		$precioProducto = $_GET["precio"];
		$rspta=$servicio->editarGuardarProductoServicio($descripcionProducto, $cantidadProducto, $precioProducto, $idarticulo, $idservicio, $precioViejo, $stockViejo);
		echo $rspta ? "Producto actualizado correctamente": "No se pudo actualizar el producto";
		break;
	case 'actualizarKilometraje' :
			$idcliente=$_GET['idcliente'];
			$idauto=$_GET['idauto'];
			$kmAuto=$_GET['kmAuto'];
			$rspta=$servicio->actualizarKilometraje($idcliente, $idauto, $kmAuto);
			echo $rspta ? "Kilometraje actualizado correctamente": "No se pudo actualizar el kilometraje";
		break;

	case 'guardarProductoServicio':
		$idServicio=$_GET['servicioId'];
		$idarticulo=$_GET['idArticulo'];
		$articulo=$_GET['codigoArticulo'];
		$fmsi=$_GET['fmsiArticulo'];
		$marca=$_GET['marcaArticulo'];
		$descripcion=$_GET['descripcionArticulo'];
		$publico=$_GET['costoArticulo'];
		$stock=$_GET['cantidadArticulo'];
		$fecha=$_GET['dateTime'];
		$idcliente=$_GET['idcliente'];
		$idarticuloSucursal=$_GET['idarticuloSucursal'];
		$rspta=$servicio->addProductoServicio($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idServicio, $fecha, $idcliente, $idarticuloSucursal, $idsucursal);
		echo $rspta ? "Producto agregado correctamente": "No se pudo agregar el producto";
		break;
	case 'eliminarProductoServicio':
		$idServicioProducto=$conexion->real_escape_string($_POST['idservicio']);
		$idProductoServicio = $conexion->real_escape_string($_POST['idarticulo']);
		$stock = $conexion->real_escape_string($_POST['stock']);
		$precio_servicio = $conexion->real_escape_string($_POST['precio_servicio']);
		
		$rspta=$servicio->eliminarProductoServicio($idServicioProducto, $idProductoServicio, $stock, $precio_servicio);		
		break;
	case 'mostrarProductoServicio':
		$idarticulo=$_GET['idarticulo'];
		$idServicio=$_GET['idServicio'];
		$rspta=$servicio->mostrarProductoEdit($idarticulo, $idServicio);
		echo json_encode($rspta);
		break;

	case 'ultimoServicio' :
	$rspta=$servicio->ultimoServicio();		
	while ($reg=$rspta->fetch_object()) {
		echo json_encode($reg->idservicio);
	}
	break;

	case 'anular':
		$rspta=$servicio->anular($idservicio);
		echo $rspta ? "Servicio anulado correctamente" : "No se pudo anular el servicio";
		break;
	
	case 'mostrar':
		$rspta=$servicio->mostrar($idservicio);
		echo json_encode($rspta);
	break;

	case 'mostrarInfoAuto':
		$rspta=$servicio->mostrarInfoAuto($idauto);
		echo json_encode($rspta);
	break;

	case 'mostrarDetalleServicio' :
		$id=$_GET['id'];

		$rspta=$servicio->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">
        <th>Opciones</th>		
		<th>Clave</th>
		<th>Fmsi</th>
		<th>Marca</th>
		<th>Descripción</th>       
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
		<th>Acciones</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas" id="filas" style="font-size:12px">
			<td><button style="width: 40px;" title="Eliminar" type="button" class="btn btn-danger" onclick="eliminarProductoServicio('.$reg->idservicio.', '.$reg->idarticulo.', '.$reg->cantidad.', '.$reg->precio_servicio.')">X</button></td>						
			<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.$reg->marca.'</td>
			<td>'.$reg->descripcion.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_servicio, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td>				
			<a data-toggle="modal" href="#editProductServicio">
				<button style="width: 40px;" type="button" title="Editar" class="btn btn-warning" onclick="editarProductoServicio('.$reg->idarticulo.')">
					<i class="fa fa-pencil"></i>
				</button>
			</a>
			</td>
			</tr>'
			;
			number_format($total=$total+($reg->precio_servicio*$reg->cantidad-$reg->descuento), 2);			
		}		
		echo '<tfoot style="background-color:#A9D0F5; font-size: 12px;">         
         <th></th>
         <th></th>
         <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
         <th>TOTAL</th>
         <th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_servicio" id="total_servicio"></th>
		 <th></th>
       </tfoot>';
		break;

	case 'listarDetalle':
		//recibimos el idservicio
		$id=$_GET['id'];

		$rspta=$servicio->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">
		<th>Opciones</th>		
		<th>Clave</th>
		<th>Fmsi</th>
		<th>Marca</th>
		<th>Descripción</th>       
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
		<th>Acciones</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas" id="filas">
			<td></td>			
			<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.$reg->marca.'</td>			
			<td>'.$reg->descripcion.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_servicio, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td></td>';
			$total=$total+($reg->precio_servicio*$reg->cantidad-$reg->descuento);
		}
		echo '<tfoot style="background-color:#A9D0F5; font-size: 12px;">		
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th></th>
		<th>TOTAL</th>
        <th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_servicio" id="total_servicio"></th>
		<th></th>
       </tfoot>';
		break;

		case 'listarDetalleAnulado':
			//recibimos el idservicio
			$id=$_GET['id'];
	
			$rspta=$servicio->listarDetalleTodo($id);
			$total=0;
			echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">
			<th>Opciones</th>			
			<th>Clave</th>
			<th>Fmsi</th>
			<th>Marca</th>
			<th>Descripción</th>       
			<th>Cantidad</th>
			<th>Precio Venta</th>
			<th>Descuento</th>
			<th>Subtotal</th>
			<th>Acciones</th>
		   </thead>';
			while ($reg=$rspta->fetch_object()) {
				echo '<tr class="filas" id="filas">
				<td></td>				
				<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
				<td>'.$reg->fmsi.'</td>
				<td>'.$reg->marca.'</td>			
				<td>'.$reg->descripcion.'</td>
				<td>'.$reg->cantidad.'</td>
				<td>$'.number_format($reg->precio_servicio, 2).'</td>
				<td>'.$reg->descuento.'</td>
				<td>$'.number_format($reg->subtotal, 2).'</td>
				<td></td>';
				$total=$total+($reg->precio_servicio*$reg->cantidad-$reg->descuento);
			}
			echo '<tfoot style="background-color:#A9D0F5; font-size: 12px;">			
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th>TOTAL</th>
			<th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_servicio" id="total_servicio"></th>
			<th></th>
		   </tfoot>';
			break;

    case 'listar':
	
		$consulta = $servicio->filtroPaginado(50, 0, "", "", "");
		$termino= "";
		
		if(!empty($_POST['servicios']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){
			echo "Llegaste 1";
			$termino=$conexion->real_escape_string($_POST['servicios']);
			echo $termino;
			$consulta=$servicio->filtroPaginado(50,0, $termino, "", "");
		}
		else if(empty($_POST['servicios']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
			echo "solo limites";
			$limites=$conexion->real_escape_string($_POST['total_registros']);				
			$consulta=$servicio->filtroPaginado($limites,0, "", "", "");
		}
		else if(!empty($_POST['servicios']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){				
			echo "Filtro busqueda, limites y fecha inicio";
			$termino=$conexion->real_escape_string($_POST['servicios']);
			$limites=$conexion->real_escape_string($_POST['total_registros']);				
			$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
			
			$consulta=$servicio->filtroPaginado($limites,0, $termino, $fecha_inicio, "");
		}
		else if(!empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){				
			echo "Filtro busqueda, limites, fecha inicio e inicio registros";
			$termino=$conexion->real_escape_string($_POST['busqueda']);
			$limites=$conexion->real_escape_string($_POST['total_registros']);				
			$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
			$inicio_registros = $conexion->real_escape_string($_POST['inicio_registros']);
			
			$consulta=$servicio->filtroPaginado($limites,$inicio_registros, $termino, $fecha_inicio, "");
		}
		else if(empty($_POST['servicios']) && !empty($_POST['total_registros']) && empty($_POST["inicio_registros"]) && !empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){				
			echo "Filtro solo limites y fecha inicio";				
			$limites=$conexion->real_escape_string($_POST['total_registros']);
			echo $limites;				
			$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
			
			$consulta=$servicio->filtroPaginado($limites,0, "", $fecha_inicio, "");
		}
		else if(!empty($_POST['servicios']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])){
			echo "Llegaste 2";
			$termino=$conexion->real_escape_string($_POST['servicios']);
			$limites=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($limites,0, $termino, "", "");
		}
		else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {					
			echo "Llegaste 3";

			$busqueda=$conexion->real_escape_string($_POST['busqueda']);				
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			
			$consulta=$servicio->filtroPaginado(5,0, $busqueda, $fecha_inicio, "");

		}
		else if(!empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
			echo "Solo fecha";

			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);														
			$consulta=$servicio->filtroPaginado(5,0, "", $fecha_inicio, "");

		}
		else if(empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
			echo "Solo paginado > 2";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				

			$consulta=$servicio->filtroPaginado(5,$inicio_registros, "", "", "");

		}
		else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1 y busqueda";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);

			$consulta=$servicio->filtroPaginado(5,$inicio_registros, $busqueda, "", "");

		}
		else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1, busqueda y limites ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$limites=$conexion->real_escape_string($_POST['total_registros']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);

			$consulta=$servicio->filtroPaginado($limites,$inicio_registros, $busqueda, "", "");

		}
		else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1, busqueda y fecha inicio ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);

			$consulta=$servicio->filtroPaginado(5,$inicio_registros, $busqueda, $fecha_inicio, "");

		}
		else if(empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1 y fecha inicio ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);

			$consulta=$servicio->filtroPaginado(5,$inicio_registros, "", $fecha_inicio, "");

		}
		else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1 total registros ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);

			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, "", "", "");

		}
		else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1 total registros ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);

			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, "", "", "");

		}
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {
			echo "Paginado > 1 total registros y fecha inicio ";
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);

			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, "");

		}

		//FECHAS FIN

		//Solo fecha fin, pagina 1
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Solo fecha final";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
			$consulta=$servicio->filtroPaginado(5,0, "", "", $fecha_fin);
		}
		//Fecha fin y busqueda , pagina 1
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Fecha fin y busqueda";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$consulta=$servicio->filtroPaginado(50,0, $busqueda, "", $fecha_fin);
		}
		//Fecha fin, busqueda, limites, pagina 1
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Fecha fin, busqueda y limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,0, $busqueda, "", $fecha_fin);
		}
		//Fecha fin, limites, pagina 1
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Fecha fin, limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,0, "", "", $fecha_fin);
		}
		//Solo fecha inicio, fecha fin
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha inicio y fecha fin";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
			$consulta=$servicio->filtroPaginado(5,0, "", $fecha_inicio, $fecha_fin);
		}
		//Solo fecha inicio, fecha fin, busquedas
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha inicio, fecha fin y busqueda";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			echo "BUSQUEDA: ". $busqueda;
			$consulta=$servicio->filtroPaginado(5,0, $busqueda, $fecha_inicio, $fecha_fin);
		}
		//Solo fecha inicio, fecha fin, busquedas, limites
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha inicio, fecha fin, busqueda y limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,0, $busqueda, $fecha_inicio, $fecha_fin);
		}
		//Solo fecha inicio, fecha fin, limites
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha inicio, fecha fin y limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,0, "", $fecha_inicio, $fecha_fin);
		}
		//Solo fecha fin, pagina
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin y pagina > 1";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$consulta=$servicio->filtroPaginado(5,$inicio_registros, "", "", $fecha_fin);
		}
		//Solo fecha inicio, pagina, limites
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin, pagina, limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, "", "", $fecha_fin);
		}
		//Solo fecha inicio, fecha fin, pagina, limites
		else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin, fecha inicio, pagina, limites";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, $fecha_fin);
		}
		//Solo fecha inicio, fecha fin, pagina, limites, busqueda
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin, fecha inicio, pagina, limites, busqueda";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, $busqueda, $fecha_inicio, $fecha_fin);
		}
		//Solo fecha fin, pagina, limites, busqueda
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin, pagina, limites, busqueda";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
			$total_registros=$conexion->real_escape_string($_POST['total_registros']);
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$consulta=$servicio->filtroPaginado($total_registros,$inicio_registros, $busqueda, "", $fecha_fin);
		}
		//Solo fecha fin, fecha inicio, busqueda, pagina
		else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {
			echo "Solo fecha fin, fecha inicio, busqueda, pagina";
			$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
			$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
			$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
			$busqueda=$conexion->real_escape_string($_POST['busqueda']);
			$consulta=$servicio->filtroPaginado(5,$inicio_registros, $busqueda, $fecha_inicio, $fecha_fin);
		}
		
			$consultaBD=$consulta;
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='tableArticulos'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>							
							<th class='bg-info' scope='col'>Auto</th>
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";				
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["idsucursal"] == $idsucursal && $acceso ==="admin") {
						if($fila["status"] != 'ANULADO') {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFacturaServicio.php?id=';
							}
							$miles = number_format($fila['total_servicio'], 2);							
							$servicios_pagina = 3;
							$paginas = 13;
							$totalServicio = 0;
							$importeTotal = 0;

							if($fila["total_servicio"] == intval($fila["pagado"])) {
								$color = "#0C9B00";
								echo "<tr style='color:".$color."'>
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>"."PAGADO"."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".number_format($totalServicio=$fila["total_servicio"] - $fila["pagado"], 2)."</td>
								<td><p>$ ".$miles."</td>
								<td>
									<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar servicio' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idservicio"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>
									<button title='Anular' data-toggle='popover' data-trigger='hover' data-content='Anular servicio' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button>															
									<a target='_blank' href='".$url.$fila["idservicio"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir servicio' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>																			
								</td>
								</tr>
								";
							} else {
								echo "<tr style='color:red'>								
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>"."PENDIENTE"."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$totalServicio=$fila["total_servicio"] - $fila["pagado"]."</td>
								<td><p>$ ".$miles."</td>
								<td>
									<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar servicio' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idservicio"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>
									<button title='Anular' data-toggle='popover' data-trigger='hover' data-content='Anular servicio' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button>															
									<button title='Cobrar' data-toggle='popover' data-trigger='hover' data-content='Cobrar articulo' data-placement='top' class='btn btn-default btn-xs' onclick='cobrarServicio(".$fila["idservicio"].")'><i class='fa fa-credit-card'></i></button>
									<a target='_blank' href='".$url.$fila["idservicio"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir servicio' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>										
								</div>
								</td>
								</tr>
								";
							}
						} else {
							$miles = number_format($fila['total_servicio'], 2);														
							$totalServicio = 0;							
							echo "<tr style='color:black'>								
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila["status"]."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$totalServicio=$fila["total_servicio"] - $fila["pagado"]."</td>
								<td><p>$ ".$miles."</td>
								<td>
									<div class='emergente'>																												
										<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrarAnulado(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>		
									</div>
								</td>
								</tr>
								";
						}
							
					} else if($fila["idsucursal"] == $idsucursal && $acceso != "admin"){
						if($fila["status"] != 'ANULADO') {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFacturaServicio.php?id=';
							}
							$miles = number_format($fila['total_servicio'], 2);							
							$servicios_pagina = 3;
							$paginas = 13;
							$totalServicio = 0;
							$importeTotal = 0;

							if($fila["total_servicio"] == intval($fila["pagado"])) {
								$color = "#0C9B00";
								echo "<tr style='color:".$color."'>
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>"."PAGADO"."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".number_format($totalServicio=$fila["total_servicio"] - $fila["pagado"], 2)."</td>
								<td><p>$ ".$miles."</td>
								<td>								
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>
									<button title='Anular' data-toggle='popover' data-trigger='hover' data-content='Anular servicio' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button>															
									<a target='_blank' href='".$url.$fila["idservicio"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir servicio' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
									</td>
								</tr>
								";
							} else {
								echo "<tr style='color:red'>								
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>"."PENDIENTE"."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$totalServicio=$fila["total_servicio"] - $fila["pagado"]."</td>
								<td><p>$ ".$miles."</td>
								<td>																
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>
									<button title='Anular' data-toggle='popover' data-trigger='hover' data-content='Anular servicio' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button>						
									<button title='Cobrar' data-toggle='popover' data-trigger='hover' data-content='Cobrar articulo' data-placement='top' class='btn btn-default btn-xs' onclick='cobrarServicio(".$fila["idservicio"].")'><i class='fa fa-credit-card'></i></button>
									<a target='_blank' href='".$url.$fila["idservicio"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir servicio' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
								</td>
								</tr>
								";
							}
						} else {
							$miles = number_format($fila['total_servicio'], 2);														
							$totalServicio = 0;							
							echo "<tr style='color:black'>								
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila["status"]."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>								
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$totalServicio=$fila["total_servicio"] - $fila["pagado"]."</td>
								<td><p>$ ".$miles."</td>
								<td>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar servicio' data-placement='top' class='btn btn-success btn-xs' onclick='mostrarAnulado(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button>
								</td>
								</tr>
								";
						}
					}
						
				}
				echo "</tbody>
				<tfoot>
					<tr>					
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>							
							<th class='bg-info' scope='col'>Auto</th>
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
							<th class='bg-info' scope='col'>Acciones</th>
					</tr>
				</tfoot>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado el servicio (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
			}
		break;

		case 'listarProductosEdit':

			$consulta="SELECT * FROM articulo ORDER BY stock DESC LIMIT 5";
				$termino= "";
				if(isset($_POST['productosEdit']))
				{
					$termino=$conexion->real_escape_string($_POST['productosEdit']);
					usleep(10000);
					$consulta="SELECT * FROM articulo
					WHERE
					codigo LIKE '%".$termino."%' OR
					fmsi LIKE '%".$termino."%' OR
					barcode LIKE '%".$termino."%' OR
					descripcion LIKE '%".$termino."%' OR
					marca LIKE '%".$termino."%' ORDER BY stock DESC LIMIT 50";
				}
				$consultaBD=$conexion->query($consulta);
				if($consultaBD->num_rows>=1){
					echo "
					<div id='container'>
					<table class='responsive-table table table-hover table-bordered' style='font-size:12px' <div id='container'>>
						<thead class='table-light'>
							<tr>
								<th class='bg-info' scope='col'>Claves</th>
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
			
						if(isset($_POST["types"])) {
							$tipo_precio = $_POST["types"];
						

							if($fila["idsucursal"] == $idsucursal) {
								if($fila["stock"] >=1 && $tipo_precio != null) {
									echo "<tr style='color:blue;'>
										<td>".$fila['codigo']."</td>
										<td>".$fila['fmsi']."</td>
										<td>".$fila['marca']."</td>
										<td>".$delit."...</td>
										<td><p>$ ".$costoMiles."</p></td>
										<td><p>$ ".$publicMiles."</p></td>
										<td><p>$ ".$tallerMiles."</p></td>
										<td><p>$ ".$creditoMiles."</p></td>
										<td><p>$ ".$mayoreoMiles."</p></td>
										<td><p>".$fila["stock"]." pz</p></td>										
										<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
									</tr>";
								} else if($fila["stock"] >=1 && $tipo_precio == null){
									$precio = "publico";
									echo "<tr style='color:blue;'>
										<td>".$fila['codigo']."</td>
										<td>".$fila['fmsi']."</td>
										<td>".$fila['marca']."</td>
										<td>".$delit."...</td>
										<td><p>$ ".$costoMiles."</p></td>
										<td><p>$ ".$publicMiles."</p></td>
										<td><p>$ ".$tallerMiles."</p></td>
										<td><p>$ ".$creditoMiles."</p></td>
										<td><p>$ ".$mayoreoMiles."</p></td>
										<td><p>".$fila["stock"]." pz</p></td>										
										<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
									</tr>";
								} else if($fila["stock"] < 1){
									echo "<tr style='color:red;'>
										<td>".$fila['codigo']."</td>
										<td>".$fila['fmsi']."</td>
										<td>".$fila['marca']."</td>
										<td>".$delit."...</td>
										<td><p>$ ".$costoMiles."</p></td>
										<td><p>$ ".$publicMiles."</p></td>
										<td><p>$ ".$tallerMiles."</p></td>
										<td><p>$ ".$creditoMiles."</p></td>
										<td><p>$ ".$mayoreoMiles."</p></td>
										<td><p>".$fila["stock"]." pz</p></td>";
								}
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
					</table></div>";
				}else{
					echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
					echo "<br><br>";
				}
			break;

		case 'selectCliente':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarc();
			echo '<option value="" disabled selected>Seleccionar cliente</option>';
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
		break;

		case 'selectAuto':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$id=$_GET['id'];

			echo $id;

			$rspta = $persona->listarAutos($id);
			echo '<option value="" disabled selected>Seleccionar auto</option>';
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idauto.'>'.$reg->marca." ".$reg->modelo." ".$reg->ano.'</option>';
			}

		break;

			case 'listarProductos':
	
				$consulta="SELECT * FROM articulo ORDER BY stock DESC LIMIT 100";
					$termino= "";
					if(isset($_POST['productos']))
					{
						$termino=$conexion->real_escape_string($_POST['productos']);
						usleep(10000);
						$consulta="SELECT * FROM articulo
						WHERE
						codigo LIKE '%".$termino."%' OR
						fmsi LIKE '%".$termino."%' OR
						barcode LIKE '%".$termino."%' OR
						descripcion LIKE '%".$termino."%' OR
						marca LIKE '%".$termino."%' ORDER BY stock DESC LIMIT 100";
					}
					$consultaBD=$conexion->query($consulta);
					if($consultaBD->num_rows>=1){
						echo "
						<div id='container'>
						<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='tableArticulos'>
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
							
							if(isset($_POST["types"])) {
								$tipo_precio = $_POST["types"];

								if($fila["idsucursal"] == $idsucursal) {
									if($fila["stock"] >=1 && $tipo_precio != null) {
										echo "<tr style='color:blue;'>
											<td>".$fila['codigo']."</td>
											<td>".$fila['fmsi']."</td>
											<td>".$fila['marca']."</td>
											<td>".$delit."...</td>
											<td><p>$ ".$costoMiles."</p></td>
											<td><p>$ ".$publicMiles."</p></td>
											<td><p>$ ".$tallerMiles."</p></td>
											<td><p>$ ".$creditoMiles."</p></td>
											<td><p>$ ".$mayoreoMiles."</p></td>
											<td><p>".$fila["stock"]." pz</p></td>										
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila["marca"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\" )'><span class='fa fa-plus'></span></button></td>
										</tr>";
									} else if($fila["stock"] >=1 && $tipo_precio == null){
										$precio = "publico";
										echo "<tr style='color:blue;'>
											<td>".$fila['codigo']."</td>
											<td>".$fila['fmsi']."</td>
											<td>".$fila['marca']."</td>
											<td>".$delit."...</td>
											<td><p>$ ".$costoMiles."</p></td>
											<td><p>$ ".$publicMiles."</p></td>
											<td><p>$ ".$tallerMiles."</p></td>
											<td><p>$ ".$creditoMiles."</p></td>
											<td><p>$ ".$mayoreoMiles."</p></td>
											<td><p>".$fila["stock"]." pz</p></td>										
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila["marca"]."\", \"".$fila[$precio]."\" , \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
										</tr>";
									} else if($fila["stock"] < 1){
										echo "<tr style='color:red;'>
											<td>".$fila['codigo']."</td>
											<td>".$fila['fmsi']."</td>
											<td>".$fila['marca']."</td>
											<td>".$delit."...</td>
											<td><p>$ ".$costoMiles."</p></td>
											<td><p>$ ".$publicMiles."</p></td>
											<td><p>$ ".$tallerMiles."</p></td>
											<td><p>$ ".$creditoMiles."</p></td>
											<td><p>$ ".$mayoreoMiles."</p></td>
											<td><p>".$fila["stock"]." pz</p></td>";
									}
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
						</table></div>";
					}else{
						echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
						echo "<br><br>";
					}
				break;

				case 'listarProductosSucursal':
	
					$consulta="SELECT a.codigo, a.fmsi, a.barcode, a.descripcion, a.marca, a.stock, a.costo, a.publico, a.taller, a.credito_taller, a.mayoreo, a.idarticulo,a.idsucursal, s.nombre FROM articulo AS a INNER JOIN sucursal s ON a.idsucursal=s.idsucursal WHERE s.idsucursal != $idsucursal";
						$termino= "";
						if(isset($_POST['productos']))
						{
							$termino=$conexion->real_escape_string($_POST['productos']);
							usleep(10000);
							$consulta="SELECT a.codigo, a.fmsi, a.barcode, a.descripcion, a.marca, a.stock, a.costo, a.publico, a.taller, a.credito_taller, a.mayoreo, a.idarticulo,a.idsucursal, s.nombre FROM articulo AS a INNER JOIN sucursal s ON a.idsucursal=s.idsucursal
							WHERE
							codigo LIKE '%".$termino."%' OR
							fmsi LIKE '%".$termino."%' OR
							barcode LIKE '%".$termino."%' OR
							descripcion LIKE '%".$termino."%' OR
							marca LIKE '%".$termino."%' AND stock > 0 ORDER BY stock DESC LIMIT 100";
						}					
						$consultaBD=$conexion->query($consulta);
						if($consultaBD->num_rows>=1){
							echo "
							<div id='container'>
							<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='tableArticulos'>
								<thead class='table-light'>
									<tr>
										<th class='bg-info' scope='col'>Sucursal</th>
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
					
								if(isset($_POST["types"])) {
									$tipo_precio = $_POST["types"];
									if($fila["idsucursal"] != $idsucursal) {
										if($fila["stock"] >=1 && $tipo_precio != null) {
											echo "<tr style='color:blue;'>
												<td>".$fila['nombre']."</td>
												<td>".$fila['codigo']."</td>
												<td>".$fila['fmsi']."</td>
												<td>".$fila['marca']."</td>
												<td>".$delit."...</td>
												<td><p>$ ".$costoMiles."</p></td>
												<td><p>$ ".$publicMiles."</p></td>
												<td><p>$ ".$tallerMiles."</p></td>
												<td><p>$ ".$creditoMiles."</p></td>
												<td><p>$ ".$mayoreoMiles."</p></td>
												<td><p>".$fila["stock"]." pz</p></td>										
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila["marca"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\" , \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
											</tr>";
										} else if($fila["stock"] >=1 && $tipo_precio == null){
											$precio = "publico";
											echo "<tr style='color:blue;'>
												<td>".$fila['nombre']."</td>
												<td>".$fila['codigo']."</td>
												<td>".$fila['fmsi']."</td>
												<td>".$fila['marca']."</td>
												<td>".$delit."...</td>
												<td><p>$ ".$costoMiles."</p></td>
												<td><p>$ ".$publicMiles."</p></td>
												<td><p>$ ".$tallerMiles."</p></td>
												<td><p>$ ".$creditoMiles."</p></td>
												<td><p>$ ".$mayoreoMiles."</p></td>
												<td><p>".$fila["stock"]." pz</p></td>										
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila["marca"]."\", \"".$fila[$precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
											</tr>";
										}
									}
								}
							}
							echo "</tbody>
							<tfoot>
								<tr>		
									<th class='bg-info' scope='col'>Sucursal</th>
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
							</table></div>";
						}else{
							echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
							echo "<br><br>";
						}
				break;

				case 'listarProductosAlmacenEdit':					
	
					$consulta="SELECT * FROM articulo ORDER BY stock DESC LIMIT 5";
						$termino= "";
						if(isset($_POST['productosEdit']))
						{
							$termino=$conexion->real_escape_string($_POST['productosEdit']);
							usleep(10000);
							$consulta="SELECT * FROM articulo
							WHERE
							codigo LIKE '%".$termino."%' OR
							fmsi LIKE '%".$termino."%' OR
							barcode LIKE '%".$termino."%' OR
							descripcion LIKE '%".$termino."%' OR
							marca LIKE '%".$termino."%' ORDER BY stock DESC LIMIT 50";
						}
						$consultaBD=$conexion->query($consulta);
						if($consultaBD->num_rows>=1){
							echo "
							<div id='container'>
							<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='tableArticulos'>
								<thead class='table-light'>
									<tr>
										<th class='bg-info' scope='col'>Claves</th>
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
					
								if(isset($_POST["types"])) {
									$tipo_precio = $_POST["types"];
								
	
									if($fila["idsucursal"] != $idsucursal) {
										if($fila["stock"] >=1 && $tipo_precio != null) {
											echo "<tr style='color:blue;'>
												<td>".$fila['codigo']."</td>
												<td>".$fila['fmsi']."</td>
												<td>".$fila['marca']."</td>
												<td>".$delit."...</td>
												<td><p>$ ".$costoMiles."</p></td>
												<td><p>$ ".$publicMiles."</p></td>
												<td><p>$ ".$tallerMiles."</p></td>
												<td><p>$ ".$creditoMiles."</p></td>
												<td><p>$ ".$mayoreoMiles."</p></td>
												<td><p>".$fila["stock"]." pz</p></td>										
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
											</tr>";
										} else if($fila["stock"] >=1 && $tipo_precio == null){
											$precio = "publico";
											echo "<tr style='color:blue;'>
												<td>".$fila['codigo']."</td>
												<td>".$fila['fmsi']."</td>
												<td>".$fila['marca']."</td>
												<td>".$delit."...</td>
												<td><p>$ ".$costoMiles."</p></td>
												<td><p>$ ".$publicMiles."</p></td>
												<td><p>$ ".$tallerMiles."</p></td>
												<td><p>$ ".$creditoMiles."</p></td>
												<td><p>$ ".$mayoreoMiles."</p></td>
												<td><p>".$fila["stock"]." pz</p></td>										
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalleEdit(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
											</tr>";
										} else if($fila["stock"] < 1){
											echo "<tr style='color:red;'>
												<td>".$fila['codigo']."</td>
												<td>".$fila['fmsi']."</td>
												<td>".$fila['marca']."</td>
												<td>".$delit."...</td>
												<td><p>$ ".$costoMiles."</p></td>
												<td><p>$ ".$publicMiles."</p></td>
												<td><p>$ ".$tallerMiles."</p></td>
												<td><p>$ ".$creditoMiles."</p></td>
												<td><p>$ ".$mayoreoMiles."</p></td>
												<td><p>".$fila["stock"]." pz</p></td>";
										}
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
							</table></div>";
						}else{
							echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
							echo "<br><br>";
						}
					break;
							
}
 ?>