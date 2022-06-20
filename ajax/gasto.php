<?php 
require_once "../modelos/Gasto.php";    

$gasto=new Gasto();
session_start();
$idsucursal = $_SESSION['idsucursal'];

$idgasto=isset($_POST["idgasto"])? limpiarCadena($_POST["idgasto"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$cantidad=isset($_POST["cantidad"])? limpiarCadena($_POST["cantidad"]):"";
$total_gasto=isset($_POST["total_gasto"])? limpiarCadena($_POST["total_gasto"]):"";
$metodoPago=isset($_POST["metodoPago"])? limpiarCadena($_POST["metodoPago"]):"";
$fecha_hora=isset($_POST["fecha_hora"])? limpiarCadena($_POST["fecha_hora"]):"";
$informacionAdicional=isset($_POST["informacionAdicional"])? limpiarCadena($_POST["informacionAdicional"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idgasto)) {
		$rspta=$gasto->insertar($descripcion, $cantidad, $total_gasto, $metodoPago, $informacionAdicional, $idsucursal, $fecha_hora);        
		echo "ID GASTO: ".$idgasto;
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	} else {
        $rspta=$gasto->editar($idgasto, $descripcion, $cantidad, $total_gasto, $metodoPago, $informacionAdicional, $fecha_hora);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	
	case 'desactivar':
		$rspta=$gasto->desactivar($idgasto);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$gasto->activar($idgasto);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$gasto->mostrar($idgasto);
		echo json_encode($rspta);
		break;

    case 'listar':
		$consulta=" SELECT descripcion, cantidad, total_gasto, metodo_pago, informacion_adicional, estado, idgasto, DATE(fecha_hora) as fecha FROM gastos LIMIT 20";
			$termino= "";
			if(isset($_POST['gastos']))
			{
				$termino=$conexion->real_escape_string($_POST['gastos']);
				$consulta="SELECT descripcion, cantidad, total_gasto, metodo_pago, informacion_adicional, estado, idgasto, DATE(fecha_hora) as fecha FROM gastos WHERE
				descripcion LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){			
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Fecha</th>
							<th class='bg-info' scope='col'>Descripción</th>
							<th class='bg-info' scope='col'>Cantidad</th>
							<th class='bg-info' scope='col'>Precio unitario</th>
							<th class='bg-info' scope='col'>Total</th>
                            <th class='bg-info' scope='col'>Metodo de Pago</th>
                            <th class='bg-info' scope='col'>Información adicional</th>
                            <th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["estado"] != 0) {
						echo "<tr>
								<td>".$fila['fecha']."</td>
								<td>".$fila['descripcion']."</td>
								<td>".$fila['cantidad']."</td>
								<td>$".number_format($fila['total_gasto'], 2)."</td>
                                <td>$".number_format($fila['total_gasto'] * $fila['cantidad'], 2)."</td>
                                <td>".$fila['metodo_pago']."</td>
                                <td>".$fila['informacion_adicional']."</td>
								<td>							
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar gasto' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idgasto"].")'><i class='fa fa-pencil'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Desactivar gasto' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idgasto"].")')><i class='fa fa-close'></i></button>									
								</td>
							</tr>";
					} else {
						echo "<tr>
									<td>".$fila['fecha']."</td>
                                    <td>".$fila['descripcion']."</td>
                                    <td>".$fila['cantidad']."</td>
                                    <td>$".$fila['total_gasto']."</td>
                                    <td>".$fila['metodo_pago']."</td>
                                    <td>".$fila['informacion_adicional']."</td>
								<td>
									<div class='emergente'>
										<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar categoria' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idgasto"].")'><i class='fa fa-check'></i></button></span>
									</div>
								</td>
							</tr>";
					}
							
					}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";				
			}
		break;
}
 ?>