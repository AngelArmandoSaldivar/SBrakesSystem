<?php 
require_once "../modelos/Servicios.php";
if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

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
$marca=isset($_POST["marca"])? limpiarCadena($_POST["marca"]):"";
$modelo=isset($_POST["modelo"])? limpiarCadena($_POST["modelo"]):"";
$ano=isset($_POST["ano"])? limpiarCadena($_POST["ano"]):"";
$color=isset($_POST["color"])? limpiarCadena($_POST["color"]):"";
$kms=isset($_POST["kms"])? limpiarCadena($_POST["kms"]):"";
$placas=isset($_POST["placas"])? limpiarCadena($_POST["placas"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idservicio)) {
		$rspta=$servicio->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_servicio,$marca, $modelo, $ano, $color, $kms, $placas, $_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_servicio"],$_POST["descuento"], $idsucursal, $forma, $forma2, $forma3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
		$rspta=$servicio->cobrarServicio($forma, $forma2, $forma3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3, $idservicio);
		echo $rspta ? " Cobro exitoso!" : "No se pudo realizar el cobro";
	}
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

	case 'listarDetalle':
		//recibimos el idventa
		$id=$_GET['id'];

		$rspta=$servicio->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Venta</th>
        <th>Descuento</th>
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->codigo.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>'.$reg->precio_servicio.'</td>
			<td>'.$reg->descuento.'</td>
			<td>'.$reg->subtotal.'</td></tr>';
			$total=$total+($reg->precio_servicio*$reg->cantidad-$reg->descuento);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_servicio" id="total_servicio"></th>
       </tfoot>';
		break;

    case 'listar':
	
		$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idservicio DESC LIMIT 100";
			$termino= "";
			if(isset($_POST['servicios']))
			{
				$termino=$conexion->real_escape_string($_POST['servicios']);
				$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario
				WHERE 
				tipo_comprobante LIKE '%".$termino."%' OR
				v.idservicio LIKE '%".$termino."%' OR
				p.nombre LIKE '%".$termino."%' OR				
                v.modelo LIKE '%".$termino."%' OR
                v.marca LIKE '%".$termino."%' OR
                v.ano LIKE '%".$termino."%'
				LIMIT 100";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "
				<div class='loader'>
                  <img src='../files/images/loader.gif' alt=''>
                </div>
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='example'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Acciones</th>
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>
							<th class='bg-info' scope='col'>Falta por pagar</th>
							<th class='bg-info' scope='col'>Auto</th>
							<th class='bg-info' scope='col'>Total</th>
						</tr>
					</thead>
				<tbody>";				
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["idsucursal"] == $idsucursal && $fila["status"] != "ANULADO") {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFacturaServicio.php?id=';
							}
							$miles = number_format($fila['total_servicio']);
							
							$ventas_pagina = 3;
							$paginas = 13;

							if($fila["status"] != 'ANULADO' && $fila["status"] != 'NORMAL') {
								echo "<tr>
								<td>
								<div class='emergente'>
								<span data-tooltip='Mostrar servicio'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button></span>
								<span data-tooltip='Anular servicio'><button class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button></span>							
								<span data-tooltip='Imprimir remisión'><a target='_blank' href='".$url.$fila["idservicio"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a></div></span>
								</td>
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['status']."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".$fila["pagado"]."</td>
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$miles."</td>								
							</tr>
							";
							} else {
								echo "<tr style='color:red'>
								<td>
								<span data-tooltip='Mostrar servicio'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idservicio"].")'><i class='fa fa-eye'></i></button></span>
								<span data-tooltip='Anular servicio'><button class='btn btn-danger btn-xs' onclick='anular(".$fila["idservicio"].")'><i class='fa fa-close'></i></button></span>							
								<span data-tooltip='Cobrar servicio'><button class='btn btn-default btn-xs' onclick='cobrarServicio(".$fila["idservicio"].")'><i class='fa fa-credit-card'></i></button></span>
								<span data-tooltip='Imprimir remisión'><a target='_blank' href='".$url.$fila["idservicio"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a></div></span>
								<td>".$fila['idservicio']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['status']."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".$fila["pagado"]."</td>
								<td><p>".$fila["marca"]." ".$fila["modelo"]." ".$fila["ano"]."</td>
								<td><p>$ ".$miles."</td>
							</tr>
							";
							}
					}
				}
				echo "</tbody>
				<tfoot>
					<tr>
					<th class='bg-info' scope='col'>Acciones</th>
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>
							<th class='bg-info' scope='col'>Pagado</th>
							<th class='bg-info' scope='col'>Auto</th>
							<th class='bg-info' scope='col'>Total</th>
					</tr>
				</tfoot>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado el servicio (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
			}
		break;

		case 'selectCliente':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarc();

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
						<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
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
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\" )'><span class='fa fa-plus'></span></button></td>
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
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\" , \"".$fila["stock"]."\")'><span class='fa fa-plus'></span></button></td>
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
}
 ?>