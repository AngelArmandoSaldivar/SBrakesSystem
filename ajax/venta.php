<?php 
require_once "../modelos/Venta.php";
require_once "../modelos/Persona.php";
require_once "../modelos/Articulo.php";
require_once "../modelos/Cotizacion.php";
if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];
	$acceso = $_SESSION['acceso'];

$venta = new Venta();
$persona = new Persona();
$articulo = new Articulo();
$cotizacion = new Cotizacion();

$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
$tipo_precio=isset($_POST["tipo_precio"])? limpiarCadena($_POST["tipo_precio"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$telefono_local=isset($_POST["telefono_local"])? limpiarCadena($_POST["telefono_local"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$rfc=isset($_POST["rfc"])? limpiarCadena($_POST["rfc"]):"";
$credito=isset($_POST["credito"])? limpiarCadena($_POST["credito"]):"";

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$factura=isset($_POST["factura"])? limpiarCadena($_POST["factura"]):"";
$fecha_entrada=isset($_POST["fecha_entrada"])? limpiarCadena($_POST["fecha_entrada"]):"";
$fecha_salida=isset($_POST["fecha_salida"])? limpiarCadena($_POST["fecha_salida"]):"";
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
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";
$remision=isset($_POST["remision"])? limpiarCadena($_POST["remision"]):"";
//EDICIÓN PRODUCTO
$descripcion = isset($_POST["descripcionEdit"])? limpiarCadena($_POST["descripcionEdit"]):"";
$cantidad = isset($_POST["cantidadEdit"])? limpiarCadena($_POST["cantidadEdit"]):"";
$precio = isset($_POST["precio"])? limpiarCadena($_POST["precio"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idventa)) {
		$rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_entrada,$fecha_salida,$impuesto,$total_venta,$remision,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["marca"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"], $idsucursal, $_POST["idsucursalArticulo"]); 		
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";		
	}
	break;

	case 'buscarCotizacion':
		$busquedaCotizacion=$_GET['busquedaCotizacion'];
		$rspta=$cotizacion->mostrarCotizacion($busquedaCotizacion);
		echo $rspta ? json_encode($rspta) : "404";
	break;

	case 'detallesCotizacion':
		$idcotizacion=$_GET['idcotizacion'];
		$array = [];
		$rspta=$cotizacion->listarDetalle($idcotizacion);
		while ($reg=$rspta->fetch_object()) {
			array_push($array, $reg);
		}
		echo json_encode($array);
	break;

	case 'guardarGarantia':
		$idventa=$_GET['idventa'];
		$idarticulo=$_GET['idarticulo'];
		$descripcion=$_GET['descripcion'];
		$cantidad=$_GET['cantidad'];
		$fecha_hora=$_GET['fecha_hora'];
		$precioGarantia=$_GET['precioGarantia'];
		$rspta=$venta->guardarGarantia($idventa, $idarticulo, $descripcion, $cantidad, $idsucursal, $fecha_hora, $precioGarantia);
		echo $rspta ? "Se guardo correctamente la garantia" : "No se pudo guardar la garantia";
		break;

	case 'listarDetalleGarantias':
		$id=$_GET['id'];
		$rspta=$venta->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5; font-size: 12px;">		
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
			<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.$reg->marca.'</td>
			<td>'.$reg->descripcion.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_servicio, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td>
			<a data-toggle="modal" href="#editProductServicioGarantia">
				<button style="width: 40px;" type="button" title="Editar" class="btn btn-warning btn-xs" onclick="mostrarArticuloGarantia('.$reg->idarticulo.', \''.$reg->descripcion.'\',\''.$reg->cantidad.'\', \''.$reg->idventa.'\', \''.$reg->subtotal.'\')">
					<i class="fa fa-pencil"></i>
				</button>
			</a>
			</td>';
			$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);			
		}
		echo '<tfoot style="background-color:#A9D0F5; font-size: 12px;">
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

	case 'guardarCliente':
		$nombreCliente=$_GET['nombreCliente'];
		$tipo_precio=$_GET['tipo_precio'];
		$direccion=$_GET['direccion'];
		$telefono=$_GET['telefono'];
		$telefono_local=$_GET['telefono_local'];
		$email=$_GET['email'];
		$rfc=$_GET['rfc'];
		$credito=$_GET['credito'];
		$rspta=$persona->insertar("","Cliente",$nombreCliente,$tipo_precio,$direccion,$telefono,$telefono_local,$email, $rfc, $credito, "","","","","","");			
		echo "CREDITO: ". $credito;
		echo $rspta ? "Cliente registrado correctamente" : "No se pudo registrar el cliente";
		break;

	case 'guardarSolicitud':
		$idarticulo=$_GET['idarticulo'];
		$marca=$_GET['marca'];
		$clave=$_GET['clave'];
		$cantidadProducto=$_GET['cantidad'];
		$fecha=$_GET['fecha'];
		$estadoPedido=$_GET['estadoPedido'];
		$nota=$_GET['nota'];
		$fecha_registro=$_GET['fecha_registro'];
		$idsucursalProducto = $_GET['idsucursalProducto'];
		$rspta=$articulo->guardarPedido($clave, $marca, $cantidadProducto, $fecha, $estadoPedido, $nota, $fecha_registro, $idsucursalProducto, $idsucursal);
		echo $rspta ? "Se guardo correctamente el pedido" : "No se pudo guardar el pedido";
		break;

	case 'guardarDetalleVenta':
		$idventa=$_GET['idventa'];
		$is_rem=$_GET['is_rem'];
		$remision=$_GET['remision'];
		$fecha_salida=$_GET['fecha_salida'];
		$fecha_entrada=$_GET['fecha_entrada'];
		$idcliente=$_GET['idcliente'];
		$rspta = $venta->editarDetalleVenta($idventa, $idcliente, $fecha_entrada, $fecha_salida, $is_rem, $remision);
		echo $rspta ? "Se guardo correctamente el detalle": "No se pudo guardar el detalle";
		break;

	case 'guardarCobro':		
		$idventa=$_GET['idventa'];
		$idcliente=$_GET['idcliente'];
		$importeCobro=$_GET['importeCobro'];
		$metodoPago=$_GET['metodoPago'];
		$banco=$_GET['banco'];		
		$referenciaCobro=$_GET['referenciaCobro'];
		$fechaCobro=$_GET['fechaCobro'];
		$rspta=$venta->guardarCobro($metodoPago, $banco, $importeCobro, $referenciaCobro, $idventa, $fechaCobro, $idsucursal, $idcliente);
		echo $rspta ? "Se guardo correctamente el pago": "No se pudo guardar";
		break;
	case 'editarCobro':
		$idpago=$_GET['idpago'];
		$idventa=$_GET['idventa'];		
		$importeCobro=$_GET['importeCobro'];
		$metodoPago=$_GET['metodoPago'];
		$banco=$_GET['banco'];	
		$importeviejo = $_GET["importeviejo"];	
		$referenciaCobro=$_GET['referenciaCobro'];		
		$rspta=$venta->editarPago($idpago, $metodoPago, $banco, $importeCobro, $referenciaCobro, $importeviejo, $idventa);
		echo $rspta ? "Pago actualizado correctamente": "No se pudo actualizar";
		break;

	case 'editarGuardarProductoVenta':
		$idarticulo=$_GET['idarticulo'];
		$idventa=$_GET['idventa'];
		$precioViejo=$_GET['precioViejo'];
		$stockViejo=$_GET['stockViejo'];
		$cantidadNueva = $_GET['stockViejo'];

		$precioNuevo = $_GET['precioNuevo'];
		$descripcionNuevo = $_GET['descripcionNuevo'];
		$cantidadNueva = $_GET['cantidadNueva'];

		$rspta=$venta->editarGuardarProductoVenta($descripcionNuevo, $cantidadNueva, $precioNuevo, $idarticulo, $idventa, $precioViejo, $stockViejo);
		echo $rspta ? "Producto actualizado correctamente": "No se pudo actualizar el producto";

		break;

	case 'guardarProductoVenta':
		$idVenta=$_GET['idVenta'];
		$idarticulo=$_GET['idArticulo'];
		$articulo=$_GET['codigoArticulo'];
		$fmsi=$_GET['fmsiArticulo'];
		$marca=$_GET['marcaArticulo'];
		$descripcion=$_GET['descripcionArticulo'];
		$publico=$_GET['costoArticulo'];
		$stock=$_GET['cantidadArticulo'];
		$fecha=$_GET['fecha'];
		$idcliente=$_GET['idcliente'];
		$idsucursalArticulo=$_GET['idsucursalArticulo'];

		$rspta=$venta->addProductoVenta($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idVenta, $fecha, $idcliente, $idsucursal, $idsucursalArticulo);
		echo $rspta ? "Producto agregado correctamente" : "No se pudo agregar el producto";
		break;

	case 'ultimaVenta' :
	$rspta=$venta->ultimaVenta();		
	while ($reg=$rspta->fetch_object()) {
		echo json_encode($reg);
	}
	break;

	case 'editarRemision':
		$idventa=$_GET["idventa"];
		$remision=$_GET["remision"];
		$rspta=$venta->editarRemision($idventa, $remision);
		echo $rspta ? "Remision actualizada" : "No se pudo actualizar la remisión";
		break;
	case 'guardarDetalleVentaEdit':
			$idventa=$_GET["idventa"];
			$cliente=$_GET["cliente"];
			$entrada=$_GET["entrada"];			
			$rspta=$venta->editarGuardarDetalleVenta($idventa, $cliente, $entrada);
			echo $rspta ? "Detalles actualizados correctamente!" : "No se pudo actualizar los detalles";
		break;

	case 'actualizarStatusRem':
		$idventa=$_GET["idventa"];
		$remision=$_GET["remision"];			
		$rspta=$venta->actualizarStatusRem($idventa, $remision);		
		echo $rspta ? "Remision actualizada" : "No se pudo actualizar la remisión";
		break;

	case 'editarFechaSalida':
		$idventa=$_GET["idventa"];
		$fechaSalida=$_GET["fechaSalida"];
		$rspta=$venta->editarFechaSalida($idventa, $fechaSalida);
		echo $rspta ? "Remision actualizada" : "No se pudo actualizar la remisión";
		break;

	case 'maxRemision' :
		$rspta=$venta->maxRemision();
		while ($reg=$rspta->fetch_object()) {
			echo $reg->maxRemision;
		}
		break;

	case 'ultimoCliente' :		
		$nombreCliente = $_GET["nombreCliente"];
		$rspta=$venta->ultimoCliente($nombreCliente);	
		while ($reg=$rspta->fetch_object()) {
			echo $reg->idpersona;
		}
	break;
		
	case 'anular':
		$rspta=$venta->anular($idventa);
		echo $rspta ? "Venta anulada correctamente" : "No se pudo anular la venta";
		break;
	case 'eliminarProductoVenta':
		$idventaProducto=$conexion->real_escape_string($_POST['idventa']);
		$idProductoVenta = $conexion->real_escape_string($_POST['idarticulo']);
		$stock = $conexion->real_escape_string($_POST['stock']);
		$precio_venta = $conexion->real_escape_string($_POST['precio_venta']);
		
		$rspta=$venta->eliminarProductoVenta($idventaProducto, $idProductoVenta, $stock, $precio_venta);		
		break;
	case 'eliminarCobro' :
		$idcobro=$_GET["idcobro"];
		$importe=$_GET['importe'];
		$idventa=$_GET['idventa'];
		$rspta=$venta->eliminarCobro($idcobro, $importe, $idventa);
		break;
	
	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
		echo json_encode($rspta);
	break;

	case 'mostrarPagoEdit':
		$idpago = $_GET["idpago"];
		$rspta=$venta->mostrarPagoEdit($idpago);
		echo json_encode($rspta);
	break;


	case 'mostrarProductoVenta':
		$idarticulo=$_GET['idarticulo'];
		$idVenta=$_GET['idVenta'];
		$rspta=$venta->mostrarProductoEdit($idarticulo, $idVenta);
		echo json_encode($rspta);
	break;

	case 'listarDetalleCobro':
		//recibimos el idventa
		$id=$_GET['id'];
		$totalCobro = 0;
		$rspta=$venta->listarDetallesCobro($id);
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
			<td>'.$reg->importe.'</td>
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
		$rspta=$venta->listarDetallesCobro($id);
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
			<td><button style="width: 40px;" title="Eliminar" id="btnEliminarCobro" name="btnEliminarCobro" type="button" class="btn btn-danger" onclick="eliminarCobro('.$reg->idpago.', '.$reg->importe.', '.$reg->idventa.')">X</button></td>
			<td>'.$reg->idpago.'</td>
			<td>'.$reg->importe.'</td>
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

	case 'mostrarDetalleVenta' :
		$id=$_GET['id'];

		$rspta=$venta->listarDetalle($id);
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
			<td><button style="width: 40px;" title="Eliminar" type="button" class="btn btn-danger btn-xs" onclick="eliminarProductoVenta('.$reg->idventa.', '.$reg->idarticulo.', '.$reg->cantidad.', '.$reg->precio_venta.')">X</button></td>						
			<td> <input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.$reg->marca.'</td>
			<td>'.$reg->descripcion.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_venta, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td><a data-toggle="modal" title="Editar" href="#editProductventa"><button style="width: 40px;" type="button" class="btn btn-warning btn-xs" onclick="editarProductoVenta('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button></a></td></tr>'
			;
			number_format($total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento), 2);			
		}
		$cont++;
		echo '<tfoot style="background-color:#A9D0F5; font-size: 12px;">        
         <th></th>
         <th></th>
         <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
		 <th></th>
         <th>TOTAL</th>
         <th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_venta" id="total_venta"></th>
		 <th></th>
       </tfoot>';
		break;

	case 'editar':
		$rspta=$venta->editar($idventa,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["marca"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"]);
	break;

	case 'mostrarInfoClient':
		$rspta=$venta->mostrarInfoClient($idcliente);
		echo json_encode($rspta);
	break;
	
	case 'listarDetalle':
		//recibimos el idventa
		$id=$_GET['id'];

		$rspta=$venta->listarDetalle($id);
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
			echo '<tr class="filas" id="filas" style="font-size: 12px;">
			<td></td>			
			<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
			<td>'.$reg->fmsi.'</td>
			<td>'.$reg->marca.'</td>
			<td>'.$reg->descripcion.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>$'.number_format($reg->precio_venta, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td>
			<td></td>';
			number_format($total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento), 2);
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
         <th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_venta" id="total_venta"></th>
		 <th></th>
       </tfoot>';

		break;
		case 'listarDetalleAnulado':
			//recibimos el idventa
			$id=$_GET['id'];
	
			$rspta=$venta->listarDetallesTodo($id);
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
				echo '<tr class="filas" id="filas" style="font-size: 12px;">
				<td></td>				
				<td><input type="hidden" value="'.$reg->idarticulo.'" id="idarticulo" name="idarticulo"></input>'.$reg->codigo.'</td>
				<td>'.$reg->fmsi.'</td>
				<td>'.$reg->marca.'</td>
				<td>'.$reg->descripcion.'</td>
				<td>'.$reg->cantidad.'</td>
				<td>$'.number_format($reg->precio_venta, 2).'</td>
				<td>'.$reg->descuento.'</td>
				<td>$'.number_format($reg->subtotal, 2).'</td>
				<td></td>';
				number_format($total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento), 2);
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
			 <th><p id="total">$ '.number_format($total, 2).'</p><input type="hidden" name="total_venta" id="total_venta"></th>
			 <th></th>
		   </tfoot>';
			break;

    case 'listar':

			$consulta = $venta->filtroPaginado(150, 0, "", "", "");
			$termino= "";
			
			if(!empty($_POST['ventas']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){			
				$termino=$conexion->real_escape_string($_POST['ventas']);
				$consulta=$venta->filtroPaginado(150,0, $termino, "", "");
			}
			else if(empty($_POST['ventas']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$consulta=$venta->filtroPaginado($limites,0, "", "", "");
			}
			else if(!empty($_POST['ventas']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){								
				$termino=$conexion->real_escape_string($_POST['ventas']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);				
				$consulta=$venta->filtroPaginado($limites,0, $termino, $fecha_inicio, "");
			}
			else if(!empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])){								
				$termino=$conexion->real_escape_string($_POST['busqueda']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);				
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros = $conexion->real_escape_string($_POST['inicio_registros']);				
				$consulta=$venta->filtroPaginado($limites,$inicio_registros, $termino, $fecha_inicio, "");
			}
			else if(empty($_POST['ventas']) && !empty($_POST['total_registros']) && empty($_POST["inicio_registros"]) && !empty($_POST["fecha_inicio"]) && empty($_POST["fecha_fin"])){											
				$limites=$conexion->real_escape_string($_POST['total_registros']);							
				$fecha_inicio = $conexion->real_escape_string($_POST['fecha_inicio']);				
				$consulta=$venta->filtroPaginado($limites,0, "", $fecha_inicio, "");
			}
			else if(!empty($_POST['ventas']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])){				
				$termino=$conexion->real_escape_string($_POST['ventas']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($limites,0, $termino, "", "");
			}
			else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {									
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);				
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
				$consulta=$venta->filtroPaginado(150,0, $busqueda, $fecha_inicio, "");
			}
			else if(!empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && empty($_POST['total_registros']) && empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);														
				$consulta=$venta->filtroPaginado(150,0, "", $fecha_inicio, "");
			}
			else if(empty($_POST["fecha_inicio"]) && empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$consulta=$venta->filtroPaginado(150,$inicio_registros, "", "", "");
			}
			else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado(150,$inicio_registros, $busqueda, "", "");
			}
			else if(empty($_POST["fecha_inicio"]) && !empty($_POST['busqueda']) && !empty($_POST['total_registros']) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$limites=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado($limites,$inicio_registros, $busqueda, "", "");
			}
			else if(!empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$venta->filtroPaginado(150,$inicio_registros, $busqueda, $fecha_inicio, "");

			}
			else if(empty($_POST['busqueda']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$venta->filtroPaginado(150,$inicio_registros, "", $fecha_inicio, "");
			}
			else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {			
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, "", "", "");
			}
			else if(empty($_POST['busqueda']) && empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, "", "", "");
			}
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_inicio"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_fin"])) {				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);								
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, "");
			}

			//FECHAS FIN

			//Solo fecha fin, pagina 1
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$consulta=$venta->filtroPaginado(150,0, "", "", $fecha_fin);
			}
			//Fecha fin y busqueda , pagina 1
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado(150,0, $busqueda, "", $fecha_fin);
			}
			//Fecha fin, busqueda, limites, pagina 1
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,0, $busqueda, "", $fecha_fin);
			}
			//Fecha fin, limites, pagina 1
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,0, "", "", $fecha_fin);
			}
			//Solo fecha inicio, fecha fin
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
				$consulta=$venta->filtroPaginado(150,0, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, busquedas
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);				
				$consulta=$venta->filtroPaginado(150,0, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, busquedas, limites
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,0, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);				
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,0, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha fin, pagina
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$consulta=$venta->filtroPaginado(150,$inicio_registros, "", "", $fecha_fin);
			}
			//Solo fecha inicio, pagina, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, "", "", $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, pagina, limites
			else if(empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, "", $fecha_inicio, $fecha_fin);
			}
			//Solo fecha inicio, fecha fin, pagina, limites, busqueda
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, $busqueda, $fecha_inicio, $fecha_fin);
			}
			//Solo fecha fin, pagina, limites, busqueda
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && !empty($_POST['total_registros']) && empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);				
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);
				$total_registros=$conexion->real_escape_string($_POST['total_registros']);
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado($total_registros,$inicio_registros, $busqueda, "", $fecha_fin);
			}
			//Solo fecha fin, fecha inicio, busqueda, pagina
			else if(!empty($_POST['busqueda']) && !empty($_POST["fecha_fin"]) && !empty($_POST['inicio_registros']) && empty($_POST['total_registros']) && !empty($_POST["fecha_inicio"])) {				
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$inicio_registros=$conexion->real_escape_string($_POST['inicio_registros']);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$consulta=$venta->filtroPaginado(150,$inicio_registros, $busqueda, $fecha_inicio, $fecha_fin);
			}
			
			$consultaBD=$consulta;
			if($consultaBD->num_rows>=1){
				echo "
				<div class='loader'>
                  <img src='../files/images/loader.gif' alt=''>
                </div>
				<table class='responsive-table table table-hover table-bordered' style='font-size:11px' id='example'>
					<thead class='table-light'>
						<tr>							
							<th class='bg-info' scope='col' style='width:10px'># Folio</th>
							<th class='bg-info' scope='col'>Entrada</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col' style='width:15px'>Estado</th>
							<th class='bg-info' scope='col' style='width:25px'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
							<th class='bg-info' scope='col'>Remisión</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";

				$resta = 0;

					while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
						$resta = $fila["total_venta"] - $fila["pagado"];
						if($fila["idsucursal"] == $idsucursal && $acceso === "admin") {
							if($fila["estado"] != 'ANULADO') {
								if ($fila["tipo_comprobante"]=='Ticket') {
									$url='../reportes/exTicket.php?id=';
								}else{
									$url='../reportes/exFactura.php?id=';
								}
								$miles = (int)$fila['total_venta'];
								$pagado = (int)$fila['pagado'];																					
								$ventas_pagina = 3;
								$paginas = 13;

								$color = "";
								$estadoVenta = "";
								$botones = "";
								$remision = "";
								$fechaSalida = "";

								if($fila["is_remision"] == 0 && $fila["remision"] == 0 && $fila["fecha_salida"] != '') {
									
									$color = "black";
									$estadoVenta = "PENDIENTE";
									$remision = "";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar venta' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idventa"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#remOrSalida'>
										<button title='Remisionar o dar salida' data-toggle='popover' data-trigger='hover' data-content='Remisionar o dar salida' data-placement='top' class='btn btn-default btn-xs' onclick='remisionarOrSalida(".$fila["idventa"].")'><i class='fa fa-money'></i></button>
									</a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
								} else if($fila["is_remision"] == 0 && $fila["fecha_salida"] == '') {
									
									$color = "blue";
									$estadoVenta = "PENDIENTE";
									$fechaSalida = "";
									$botones = "<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar venta' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idventa"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#remOrSalida'>
										<button title='Remisionar o dar salida' data-toggle='popover' data-trigger='hover' data-content='Remisionar o dar salida' data-placement='top' class='btn btn-default btn-xs' onclick='remisionarOrSalida(".$fila["idventa"].")'><i class='fa fa-money'></i></button>
									</a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>
									";


								} else if(intval($fila["pagado"]) == $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '') {
									$color = "black";
									$estadoVenta = "PAGADO";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar venta' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idventa"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>										
									<a target='_blank' href='".$url.$fila["idventa"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir ticket' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$remision = "R-".$fila["remision"];
								} else if(intval($fila["pagado"]) < $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '' && $fila["remision"] > 0 ) {
									$color = "red";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar venta' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idventa"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>	
									<button title='Cobrar' data-toggle='popover' data-trigger='hover' data-content='Cobrar venta' data-placement='top' class='btn btn-default btn-xs' onclick='cobrarVenta(".$fila["idventa"].")'><i class='fa fa-credit-card'></i></button>									
									<a target='_blank' href='".$url.$fila["idventa"]."'><button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir ticket' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$estadoVenta = "PENDIENTE";
									$remision = "R-".$fila["remision"];
								} else if(intval($fila["pagado"]) < $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '' && $fila["remision"] == 0 ) {
									$color = "red";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar venta' data-placement='top' class='btn btn-warning btn-xs' onclick='editar(".$fila["idventa"].")'><i class='fa fa-pencil'></i></button>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$estadoVenta = "PENDIENTE";
									$remision = "";
								} 

								echo "<tr style='color:".$color."'>
										<td>".$fila['idventa']."</td>
										<td>".$fila['fecha_entrada']."</td>
										<td>".$fechaSalida."</td>
										<td>".$estadoVenta."</td>
										<td><p>".$fila['cliente']."</td>
										<td><p>".$fila['usuario']."</td>											
										<td><p>$ ".number_format($resta, 2)."</td>
										<td><p>$ ".number_format($miles,2)."</td>
										<td><p>".$remision."</td>
										<td>.$botones.</td>
										</tr>
									";

							
							} else {	
									$miles = (int)$fila['total_venta'];
									$pagado = (int)$fila['pagado'];								
									echo "<tr style='color:#00000082'>
											<td>".$fila['idventa']."</td>
											<td>".$fila['fecha_entrada']."</td>
											<td>".""."</td>
											<td>".$fila["estado"]."</td>
											<td><p>".$fila['cliente']."</td>
											<td><p>".$fila['usuario']."</td>
											<td><p>$ ".number_format($resta,2)."</td>
											<td><p>$ ".number_format($miles, 2)."</td>	
											<td><p>".""."</td>
											<td>											
												<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrarAnulado(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
											</td>											
										</tr>
									";								
							}								
						} else if($fila["idsucursal"] == $idsucursal && $acceso != "admin"){
							
							if($fila["estado"] != 'ANULADO') {
								if ($fila["tipo_comprobante"]=='Ticket') {
									$url='../reportes/exTicket.php?id=';
								}else{
									$url='../reportes/exFactura.php?id=';
								}
								$miles = (int)$fila['total_venta'];
								$pagado = (int)$fila['pagado'];																					
								$ventas_pagina = 3;
								$paginas = 13;

								$color = "";
								$estadoVenta = "";
								$botones = "";
								$remision = "";
								$fechaSalida = "";

								if($fila["is_remision"] == 0 && $fila["remision"] == 0 && $fila["fecha_salida"] != '') {
									
									$color = "black";
									$estadoVenta = "PENDIENTE";
									$remision = "";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#remOrSalida'>
										<button title='Remisionar o dar salida' data-toggle='popover' data-trigger='hover' data-content='Remisionar o dar salida' data-placement='top' class='btn btn-default btn-xs' onclick='remisionarOrSalida(".$fila["idventa"].")'><i class='fa fa-money'></i></button>
									</a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
								} else if($fila["is_remision"] == 0 && $fila["fecha_salida"] == '') {
									
									$color = "blue";
									$estadoVenta = "PENDIENTE";
									$fechaSalida = "";
									$botones = "<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#remOrSalida'>
										<button title='Remisionar o dar salida' data-toggle='popover' data-trigger='hover' data-content='Remisionar o dar salida' data-placement='top' class='btn btn-default btn-xs' onclick='remisionarOrSalida(".$fila["idventa"].")'><i class='fa fa-money'></i></button>
									</a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";


								} else if(intval($fila["pagado"]) == $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '') {
									$color = "black";
									$estadoVenta = "PAGADO";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>										
									<a target='_blank' href='".$url.$fila["idventa"]."'> <button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir ticket' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$remision = "R-".$fila["remision"];
								} else if(intval($fila["pagado"]) < $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '' && $fila["remision"] > 0 ) {
									$color = "red";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>	
									<button title='Cobrar' data-toggle='popover' data-trigger='hover' data-content='Cobrar venta' data-placement='top' class='btn btn-default btn-xs' onclick='cobrarVenta(".$fila["idventa"].")'><i class='fa fa-credit-card'></i></button>									
									<a target='_blank' href='".$url.$fila["idventa"]."'><button title='Imprimir' data-toggle='popover' data-trigger='hover' data-content='Imprimir ticket' data-placement='top' class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$estadoVenta = "PENDIENTE";
									$remision = "R-".$fila["remision"];
								} else if(intval($fila["pagado"]) < $fila["total_venta"] && $fila["is_remision"] == 1 && $fila["fecha_salida"] != '' && $fila["remision"] == 0 ) {
									$color = "red";
									$fechaSalida = $fila["fecha_salida"];
									$botones = "<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button>
									<button title='Cancelar' data-toggle='popover' data-trigger='hover' data-content='Cancelar venta' data-placement='top' class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
									<a data-toggle='modal' href='#garantias'>
										<button title='Garantias' data-toggle='popover' data-trigger='hover' data-content='Enviar articulo(s) a garantias' data-placement='top' class='btn btn-default btn-xs' onclick='mostrarArticulosGarantias(".$fila["idventa"].")'><i class='fa fa-reply'></i></button>
									</a>";
									$estadoVenta = "PENDIENTE";
									$remision = "";
								}

								echo "<tr style='color:".$color."'>
											<td>".$fila['idventa']."</td>
											<td>".$fila['fecha_entrada']."</td>
											<td>".$fechaSalida."</td>
											<td>".$estadoVenta."</td>
											<td><p>".$fila['cliente']."</td>
											<td><p>".$fila['usuario']."</td>											
											<td><p>$ ".number_format($resta, 2)."</td>
											<td><p>$ ".number_format($miles,2)."</td>
											<td><p>".$remision."</td>
											<td>.$botones.</td>
										</tr>
									";

							
							} else {	
									$miles = (int)$fila['total_venta'];
									$pagado = (int)$fila['pagado'];								
									echo "<tr style='color:#00000082'>
											<td>".$fila['idventa']."</td>
											<td>".$fila['fecha_entrada']."</td>
											<td>".""."</td>
											<td>".$fila["estado"]."</td>
											<td><p>".$fila['cliente']."</td>
											<td><p>".$fila['usuario']."</td>
											<td><p>$ ".number_format($resta,2)."</td>
											<td><p>$ ".number_format($miles, 2)."</td>
											<td><p>".""."</td>
											<td> <button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar venta' data-placement='top' class='btn btn-success btn-xs' onclick='mostrarAnulado(".$fila["idventa"].")'><i class='fa fa-eye'></i></button></td>											
										</tr>
									";								
							}
							
						}
					} 			
				echo "</tbody>
				<tfoot>
					<tr>					
							<th class='bg-info' scope='col'># Documento</th>
							<th class='bg-info' scope='col'>Entrada</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estado</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>							
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
							<th class='bg-info' scope='col'>Fac / Rem</th>
							<th class='bg-info' scope='col'>Acciones</th>
					</tr>
				</tfoot>
				</table>
				";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
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

			case 'listarProductos':
	
				$consulta="SELECT * FROM articulo ORDER BY stock DESC LIMIT 5";
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
						marca LIKE '%".$termino."%' ORDER BY stock DESC LIMIT 50";
					}
					$consultaBD=$conexion->query($consulta);
					if($consultaBD->num_rows>=1){
						echo "						
						<button id='botonClave' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarClave()'><i class='fa fa-eye'></i> Mostrar Clave</button>
						<button id='botonFmsi' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarFmsi()'><i class='fa fa-eye'></i> Mostrar Fmsi</button>
						<button id='botonMarca' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarMarca()'><i class='fa fa-eye'></i> Mostrar Marca</button>
						<button id='botonDescripcion' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarDescripcion()'><i class='fa fa-eye'></i> Mostrar Descripción</button>
						<button id='botonStock' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarStock()'><i class='fa fa-eye'></i> Mostrar Stock</button>
						<button id='botonMayoreo' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarMayoreo()'><i class='fa fa-eye'></i> Mostrar Mayoreo</button>
						<button id='botonTaller' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarTaller()'><i class='fa fa-eye'></i> Mostrar Taller</button>
						<button id='botonCredito' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarCredito()'><i class='fa fa-eye'></i> Mostrar Credito</button>
						<button id='botonMostrador' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarPublico()'><i class='fa fa-eye'></i> Mostrar Publico</button>
						<button id='botonCosto' data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='mostrarCosto()'><i class='fa fa-eye'></i> Mostrar Costo</button>
						<table class='responsive-table table table-hover table-bordered' style='font-size:11px' id='tableArticulos'>
							<thead class='table-light'>
								<tr>
								<th id='thClave' class='bg-info w-40' scope='col' style='width: 100px;'>Clave
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarClave()'><i class='fa fa-eye-slash'></i></button>							
								</th>
								<th id='thFmsi' class='bg-info' scope='col' style='width:80px;'>FMSI
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarFmsi()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thMarca' class='bg-info' scope='col'>Marca
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarMarca()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thDescripcion' class='bg-info' scope='col' style='width:200px;'>Descripción
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarDescripcion()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thStock' class='bg-info' scope='col'>Stock
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarStock()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thMayoreo' class='bg-info' scope='col'>Mayoreo
									<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarMayoreo()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thTaller' class='bg-info' scope='col'>Taller
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarTaller()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thCredito' class='bg-info' scope='col'>Crédito Taller
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarCredito()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thPublico' class='bg-info' scope='col'>Publico Mostrador
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarPublico()'><i class='fa fa-eye-slash'></i></button></th>
								<th id='thCosto' class='bg-info' scope='col'>Costo
								<button data-trigger='hover' data-placement='top' class='btn btn-primary btn-xs' onclick='ocultarCosto()'><i class='fa fa-eye-slash'></i></button></th>
									<th class='bg-info' scope='col' style='width: 10px;'>Acciones</th>
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
											<td><p>".$fila["stock"]." pz</p></td>
											<td><p>$ ".$mayoreoMiles."</p></td>
											<td><p>$ ".$tallerMiles."</p></td>
											<td><p>$ ".$creditoMiles."</p></td>											
											<td><p>$ ".$publicMiles."</p></td>
											<td><p>$ ".$costoMiles."</p></td>
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
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
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\" , \"".$fila["stock"]."\", \"".$fila["idsucursal"]."\")'><span class='fa fa-plus'></span></button></td>
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
						</table>";
					}else{
						echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
						echo "<img src='../public/img/discoBrembo.jpg'>";
					}
				break;


				case 'listarProductosEdit':

					echo $idventa;
	
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
							</table>";
						}else{
							echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
							echo "<br><br>";
						}
					break;



					case 'listarProductosAlmacenEdit':

						echo $idventa;
		
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
								</table>";
							}else{
								echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
								echo "<br><br>";
							}
						break;

				case 'listarProductosSucursal':

						$consulta = $venta->listarProductosAlmacenFiltros("", "");							
						$termino= "";
						/*if(isset($_POST['productos']))
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
						}*/

						if(!empty($_POST['productos']) && empty($_POST['idsucursal'])) {											
							$termino=$conexion->real_escape_string($_POST['productos']);
							usleep(100000);								
							$consulta=$venta->listarProductosAlmacenFiltros($termino, "");			
						} else
						if(!empty($_POST['productos']) && !empty($_POST['idsucursal'])) {							
							$termino=$conexion->real_escape_string($_POST['productos']);
							$idalmacen=$conexion->real_escape_string($_POST['idsucursal']);
							usleep(100000);								
							$consulta=$venta->listarProductosAlmacenFiltros($termino, $idalmacen);	
						}

						$consultaBD=$consulta;
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
												<td>													
													<a data-toggle='modal' href='#modalSolicitudArticulo'>                   
														<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Eliminar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrarSolicitudArticulo(".$fila["idarticulo"].")'>Solicitar</button>
													</a>
												</td>
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
												<td>													
													<a data-toggle='modal' href='#modalSolicitudArticulo'>                   
														<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Eliminar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrarSolicitudArticulo(".$fila["idarticulo"].")'>Solicitar</button>
													</a>
												</td>
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
}
 ?>