<?php 
require_once "../modelos/Rol.php";

$rol=new Rol();

$idrol=isset($_POST["idrol"])? limpiarCadena($_POST["idrol"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
		if (empty($idrol)) {
			$rspta=$rol->insertar($nombre,$descripcion);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		}else{
			$rspta=$rol->editar($idrol,$nombre,$descripcion);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
		break;
	

	case 'desactivar':
		$rspta=$rol->desactivar($idrol);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$rol->activar($idrol);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$rol->mostrar($idrol);
		echo json_encode($rspta);
		break;

    case 'listar':
			$consulta="SELECT * FROM rol LIMIT 20";	
			$termino= "";
			if(isset($_POST['roles']))
			{
				$termino=$conexion->real_escape_string($_POST['roles']);
				$consulta="SELECT * FROM rol WHERE
				nombre LIKE '%".$termino."%' OR
				descripcion LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Rol</th>
							<th class='bg-info' scope='col'>Descripcion</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["condicion"] != 0) {
						echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['descripcion']."</td>
								<td>							
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar rol' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idrol"].")'><i class='fa fa-pencil'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Desactivar rol' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idrol"].")')><i class='fa fa-close'></i></button>									
								</td>
							</tr>";
					} else {
						echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['descripcion']."</td>
								<td>
									<div class='emergente'>
										<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar rol' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idrol"].")'><i class='fa fa-check'></i></button></span>
									</div>
								</td>
							</tr>";
					}
							
					}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun rol (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";				
			}
		break;
		case 'selectRol':            
            $rspta=$rol->listar();
            echo '<option value="" disabled selected>Seleccionar Rol</option>';
            while ($reg=$rspta->fetch_object()) {
                echo '<option value=' . $reg->idrol.'>'.$reg->descripcion.'</option>';
            }
        break;    
}
 ?>