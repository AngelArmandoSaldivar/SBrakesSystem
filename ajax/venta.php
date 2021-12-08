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
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$impuesto=isset($_POST["impuesto"])? limpiarCadena($_POST["impuesto"]):"";
$forma_pago=isset($_POST["forma_pago"])? limpiarCadena($_POST["forma_pago"]):"";
$total_venta=isset($_POST["total_venta"])? limpiarCadena($_POST["total_venta"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idventa)) {
		$rspta=$venta->insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$forma_pago,$total_venta,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_venta"],$_POST["descuento"], $idsucursal); 		
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
	}
		break;

		case 'cobrar':	
		$rspta=$venta->cobrarVenta($idventa);
		echo $rspta ? "Cobro exitoso" : "No se pudo cobrar la venta";				
		break;

	case 'anular':
		$rspta=$venta->anular($idventa);
		echo $rspta ? "Venta anulada correctamente" : "No se pudo anular la venta";		
		break;
	
	case 'mostrar':
		$rspta=$venta->mostrar($idventa);
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
			<td>'.$reg->precio_venta.'</td>
			<td>'.$reg->descuento.'</td>
			<td>'.$reg->subtotal.'</td></tr>';
			$total=$total+($reg->precio_venta*$reg->cantidad-$reg->descuento);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>
         <th></th>
         <th><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_venta" id="total_venta"></th>
       </tfoot>';
		break;

    case 'listar':
	
		$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE estado='Aceptado' ORDER BY v.idventa DESC LIMIT 20";
			$termino= "";
			if(isset($_POST['ventas']))
			{
				$termino=$conexion->real_escape_string($_POST['ventas']);
				$consulta="SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario
				WHERE 
				tipo_comprobante LIKE '%".$termino."%' OR
				v.idventa LIKE '%".$termino."%' OR
				p.nombre LIKE '%".$termino."%' OR
				u.nombre LIKE '%".$termino."%' OR
				AND estado='Aceptado' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='example'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Acciones</th>
							<th class='bg-info' scope='col'>Folio</th>
							<th class='bg-info' scope='col'>Salida</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Vendedor</th>
							<th class='bg-info' scope='col'>Pagado</th>
							<th class='bg-info' scope='col'>Falta por pagar</th>
							<th class='bg-info' scope='col'>Total</th>							
						</tr>
					</thead>
				<tbody>";

				echo "Hola";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["idsucursal"] == $idsucursal && $fila["estado"] == 'Aceptado' && $fila["status"] == "NORMAL") {
							if ($fila["tipo_comprobante"]=='Ticket') {
								$url='../reportes/exTicket.php?id=';
							}else{
								$url='../reportes/exFactura.php?id=';
							}
							$miles = number_format($fila['total_venta']);
							
							$ventas_pagina = 3;
							$paginas = 13;

							echo "<tr>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idventa"].")'><i class='fa fa-eye'></i></button> <button class='btn btn-danger btn-xs' onclick='anular(".$fila["idventa"].")'><i class='fa fa-close'></i></button>
								<button class='btn btn-default btn-xs' onclick='cobrar(".$fila["idventa"].")'><i class='fa fa-credit-card'></i></button>
								<a target='_blank' href='".$url.$fila["idventa"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a></td>								
								<td>".$fila['idventa']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['estado']."</td>
								<td><p>".$fila['cliente']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".$fila["pagado"]."</td>
								<td><p>$ ".$miles."</td>
								<td><p>$ ".$miles."</td>								
							</tr>
							";
					}
				}
				echo "</tbody>
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

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
			break;

			case 'listarProductos':
	
				$consulta="SELECT * FROM articulo LIMIT 20";
					$termino= "";
					if(isset($_POST['productos']))
					{
						$termino=$conexion->real_escape_string($_POST['productos']);
						$consulta="SELECT * FROM articulo
						WHERE
						codigo LIKE '%".$termino."%' OR
						fmsi LIKE '%".$termino."%' OR
						descripcion LIKE '%".$termino."%' OR
						marca LIKE '%".$termino."%' LIMIT 20";
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
							$tipo_precio = "publico";
							if($fila["idsucursal"] == $idsucursal) {
									echo "<tr>
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
										<td><button class='btn btn-warning' onclick='agregarDetalle(".$fila["idarticulo"].",\"".$fila["codigo"]."\", \"".$fila["fmsi"]."\", \"".$fila["descripcion"]."\", \"".$fila[$tipo_precio]."\" )'><span class='fa fa-plus'></span></button></td>					
									</tr>";
							}
						}
						echo "</tbody>
						</table>";
					}else{
						echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";						
						echo "<br><br>";
					}
				break;			
}
 ?>