<?php 
require_once "../modelos/Marca.php";

$marca=new Marca();

$idmarca=isset($_POST["idmarca"])? limpiarCadena($_POST["idmarca"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
$utilidad_1=isset($_POST["utilidad_1"])? limpiarCadena($_POST["utilidad_1"]):"";
$utilidad_2=isset($_POST["utilidad_2"])? limpiarCadena($_POST["utilidad_2"]):"";
$utilidad_3=isset($_POST["utilidad_3"])? limpiarCadena($_POST["utilidad_3"]):"";
$utilidad_4=isset($_POST["utilidad_4"])? limpiarCadena($_POST["utilidad_4"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idmarca)) {
		$rspta=$marca->insertar($descripcion, $utilidad_1, $utilidad_2, $utilidad_3, $utilidad_4);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$marca->editar($idmarca,$descripcion, $utilidad_1, $utilidad_2, $utilidad_3, $utilidad_4);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'desactivar':
		$rspta=$marca->desactivar($idmarca);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$marca->activar($idmarca);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$marca->mostrar($idmarca);
		echo json_encode($rspta);
		break;

    case 'listar':
		$consulta=" SELECT * FROM marca LIMIT 20";
			$termino= "";
			if(isset($_POST['marcas']))
			{
				$termino=$conexion->real_escape_string($_POST['marcas']);
				$consulta="SELECT * FROM marca WHERE				
				descripcion LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){			
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>							
							<th class='bg-info' scope='col'>Descripcion</th>
                            <th class='bg-info' scope='col'>Utilidad Publico Mostrador</th>
                            <th class='bg-info' scope='col'>Utilidad Taller</th>
                            <th class='bg-info' scope='col'>Utilidad Credito Taller</th>
                            <th class='bg-info' scope='col'>Utilidad Mayoreo</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["estatus"] != 0) {
						echo "<tr>								
								<td>".$fila['descripcion']."</td>
                                <td>".$fila['utilidad_1']."</td>
                                <td>".$fila['utilidad_2']."</td>
                                <td>".$fila['utilidad_3']."</td>
                                <td>".$fila['utilidad_4']."</td>
								<td>							
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar marca' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idmarca"].")'><i class='fa fa-pencil'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Desactivar marca' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idmarca"].")')><i class='fa fa-close'></i></button>									
								</td>
							</tr>";
					} else {
						echo "<tr>
                                <td>".$fila['descripcion']."</td>
                                <td>".$fila['utilidad_1']."</td>
                                <td>".$fila['utilidad_2']."</td>
                                <td>".$fila['utilidad_3']."</td>
                                <td>".$fila['utilidad_4']."</td>
								<td>
									<div class='emergente'>
										<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar marca' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idmarca"].")'><i class='fa fa-check'></i></button></span>
									</div>
								</td>
							</tr>";
					}
							
					}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ninguna marca (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";				
			}
		break;
}
 ?>