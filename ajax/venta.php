<?php 
require_once "../modelos/Venta.php";
if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

$venta = new Venta();

$idventa=isset($_POST["idventa"])? limpiarCadena($_POST["idventa"]):"";
$idcliente=isset($_POST["idcliente"])? limpiarCadena($_POST["idcliente"]):"";
$idusuario=$_SESSION["idusuario"];
$tipo_comprobante=isset($_POST["tipo_comprobante"])? limpiarCadena($_POST["tipo_comprobante"]):"";
$factura=isset($_POST["factura"])? limpiarCadena($_POST["factura"]):"";
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
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idventa)) {
		$rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_venta,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["marca"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"], $idsucursal, $forma, $forma2, $forma3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3); 		
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";		
	}else{
		$rspta=$venta->cobrarVenta($forma, $forma2, $forma3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3, $idventa);
		echo $rspta ? " Cobro exitoso!" : "No se pudo realizar el cobro";
	}
	break;
	case 'ultimaVenta' :
	$rspta=$venta->ultimaVenta();		
	while ($reg=$rspta->fetch_object()) {
		echo json_encode($reg->idventa);
	}
	break;
		
	case 'anular':
		$rspta=$venta->anular($idventa);
		echo $rspta ? "Venta anulada correctamente" : "No se pudo anular la venta";
		break;
	
	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
		echo json_encode($rspta);
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
			<td>$'.number_format($reg->precio_venta, 2).'</td>
			<td>'.$reg->descuento.'</td>
			<td>$'.number_format($reg->subtotal, 2).'</td></tr>';
			number_format($total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento), 2);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">$ '.number_format($total, 2).'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;

    case 'listar':	
		$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC LIMIT 100";
			$termino= "";
			if(isset($_POST['ventas']))
			{
				$termino=$conexion->real_escape_string($_POST['ventas']);
				$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario
				WHERE
				tipo_comprobante LIKE '%".$termino."%' OR
				v.idventa LIKE '%".$termino."%' OR
				p.nombre LIKE '%".$termino."%' OR
				u.nombre LIKE '%".$termino."%'
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
							<th class='bg-info' scope='col'># Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estado</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["idsucursal"] == $idsucursal && $fila["estado"] != 'ANULADO') {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFactura.php?id=';
							}
							$miles = number_format($fila['total_venta']);
							
							$ventas_pagina = 3;
							$paginas = 13;
							if($fila["estado"] != 'ANULADO' && $fila["estado"] != 'PENDIENTE') {
								echo "<tr>
										<td><div class='emergente'>
										<span data-tooltip='Mostrar venta'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button></span>
										<span data-tooltip='Cancelar venta'><button class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button></span>										
										<span data-tooltip='Imprimir ticket'><a target='_blank' href='".$url.$fila["idventa"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a></span></div></td>							
										<td>".$fila['idventa']."</td>
										<td>".$fila['fecha']."</td>
										<td>".$fila['estado']."</td>
										<td><p>".$fila['cliente']."</td>
										<td><p>".$fila['usuario']."</td>
										<td><p>$ ".$fila["pagado"]."</td>
										<td><p>$ ".$miles."</td>
									</tr>
								";
							} else {
							$color = 'red';
							echo "<tr style='color:".$color."'>
									<td><div class='emergente'>
									<span data-tooltip='Mostrar venta'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button></span>
									<span data-tooltip='Cancelar venta'><button class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button></span>
									<span data-tooltip='Cobrar venta'><button class='btn btn-default btn-xs' onclick='cobrarVenta(".$fila["idventa"].")'><i class='fa fa-credit-card'></i></button></span>
									<span data-tooltip='Imprimir ticket'><a target='_blank' href='".$url.$fila["idventa"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-print'></i></button></a></span></div></td>
									<td>".$fila['idventa']."</td>
									<td>".$fila['fecha']."</td>
									<td>".$fila['estado']."</td>
									<td><p>".$fila['cliente']."</td>
									<td><p>".$fila['usuario']."</td>
									<td><p>$ ".$fila["pagado"]."</td>
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
							<th class='bg-info' scope='col'># Documento</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estado</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>							
							<th class='bg-info' scope='col'>Saldo pendiente</th>
							<th class='bg-info' scope='col'>Total</th>
					</tr>
				</tfoot>
				</table>";
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
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\" )'><span class='fa fa-plus'></span></button></td>
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
											<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\" , \"".$fila["stock"]."\")'><span class='fa fa-plus'></span></button></td>
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
							<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
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
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\", \"".$fila["stock"]."\" )'><span class='fa fa-plus'></span></button></td>
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
												<td><button class='btn btn-warning' data-dismiss='modal' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["marca"]."\", \"".$fila["descripcion"]."\", \"".$fila[$precio]."\" , \"".$fila["stock"]."\")'><span class='fa fa-plus'></span></button></td>
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
							</table>";
						}else{
							echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
							echo "<br><br>";
						}
					break;
}
 ?>