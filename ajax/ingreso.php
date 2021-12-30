<?php 
require_once "../modelos/Ingreso.php";
if (!isset($_SESSION["nombre"])){
	$ingreso=new Ingreso();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

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
		$rspta=$ingreso->insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$fecha_hora,$impuesto,$total_compra,$_POST["idarticulo"],$_POST["clave"],$_POST["fmsi"],$_POST["descripcion"],$_POST["cantidad"],$_POST["precio_compra"], $idsucursal);	
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
        
	}
		break;

	case 'anular':
		$rspta=$ingreso->anular($idingreso);
		echo $rspta ? "Ingreso anulado correctamente" : "No se pudo anular el ingreso";
		break;
	
	case 'mostrar':
		$rspta=$ingreso->mostrar($idingreso);
		echo json_encode($rspta);
		break;

	case 'listarDetalle':
		//recibimos el idingreso
		$id=$_GET['id'];

		$rspta=$ingreso->listarDetalle($id);
		$total=0;
		echo ' <thead style="background-color:#A9D0F5">
        <th>Opciones</th>
        <th>Articulo</th>
        <th>Cantidad</th>
        <th>Precio Compra</th>        
        <th>Subtotal</th>
       </thead>';
		while ($reg=$rspta->fetch_object()) {
			echo '<tr class="filas">
			<td></td>
			<td>'.$reg->codigo.'</td>
			<td>'.$reg->cantidad.'</td>
			<td>'.$reg->precio_compra.'</td>			
			<td>'.$reg->precio_compra*$reg->cantidad.'</td>
			<td></td>
			</tr>';
			$total=$total+($reg->precio_compra*$reg->cantidad);
		}
		echo '<tfoot>
         <th>TOTAL</th>
         <th></th>
         <th></th>
         <th></th>         
         <th><h4 id="total">$ '.$total.'</h4><input type="hidden" name="total_compra" id="total_compra"></th>
       </tfoot>';
		break;

    case 'listar':
		// $rspta=$ingreso->listar();
		// $data=Array();

		// while ($reg=$rspta->fetch_object()) {
		// 	if($reg->idsucursal == $idsucursal) {
		// 		$data[]=array(
		// 		"0"=>($reg->estado=='Aceptado')?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="anular('.$reg->idingreso.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idingreso.')"><i class="fa fa-eye"></i></button>',
		// 		"1"=>$reg->fecha,
		// 		"2"=>$reg->proveedor,
		// 		"3"=>$reg->usuario,
		// 		"4"=>$reg->tipo_comprobante,
		// 		"5"=>$reg->total_compra,
		// 		"6"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
		// 		);
		// 	}
		// }
		// $results=array(
        //      "sEcho"=>1,//info para datatables
        //      "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
        //      "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
        //      "aaData"=>$data); 
		// echo json_encode($results);

		$consulta="SELECT i.idsucursal, i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC LIMIT 40";
			$termino= "";
			if(isset($_POST['ingresos']))
			{
				$termino=$conexion->real_escape_string($_POST['ingresos']);
				$consulta="SELECT i.idsucursal, i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario
				WHERE 
				tipo_comprobante LIKE '%".$termino."%' OR
				p.nombre LIKE '%".$termino."%' OR
				i.idingreso LIKE '%".$termino."%' OR
				p.idpersona LIKE '%".$termino."%' OR
				u.nombre LIKE '%".$termino."%'
				LIMIT 40";
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
							<th class='bg-info' scope='col'>Entrada</th>
							<th class='bg-info' scope='col'>Estatus</th>
							<th class='bg-info' scope='col'>Proveedor</th>
							<th class='bg-info' scope='col'>Usuario</th>
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
							$miles = number_format($fila['total_compra']);
							
							$ventas_pagina = 3;
							$paginas = 13;

							echo "<tr>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idingreso"].")'><i class='fa fa-eye'></i></button> <button class='btn btn-danger btn-xs' onclick='anular(".$fila["idingreso"].")'><i class='fa fa-close'></i></button>								
								<a target='_blank' href='".$url.$fila["idingreso"]."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a></td>								
								<td>".$fila['idingreso']."</td>
								<td>".$fila['fecha']."</td>
								<td>".$fila['estado']."</td>
								<td><p>".$fila['proveedor']."</td>
								<td><p>".$fila['usuario']."</td>
								<td><p>$ ".$fila["total_compra"]."</td>								
							</tr>
							";
					}
				}
				echo "</tbody>
				<tfoot>
					<tr>						
						<th class='bg-info' scope='col'>Acciones</th>
						<th class='bg-info' scope='col'>Folio</th>
						<th class='bg-info' scope='col'>Entrada</th>
						<th class='bg-info' scope='col'>Estatus</th>
						<th class='bg-info' scope='col'>Proveedor</th>
						<th class='bg-info' scope='col'>Usuario</th>
						<th class='bg-info' scope='col'>Total</th>
					</tr>
				</tfoot>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
			}

		break;

		case 'selectProveedor':
			require_once "../modelos/Persona.php";
			$persona = new Persona();

			$rspta = $persona->listarp();

			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
		break;

		case 'listarArticulos':
			$consulta="SELECT * FROM articulo ORDER BY stock DESC LIMIT 100";
					$termino= "";
					if(isset($_POST['productos']))
					{
						$termino=$conexion->real_escape_string($_POST['productos']);
						$consulta="SELECT * FROM articulo
						WHERE
						codigo LIKE '%".$termino."%' OR
						fmsi LIKE '%".$termino."%' OR
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
							$tipo_precio = "costo";
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