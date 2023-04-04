<?php 
require_once "../modelos/Asignacion.php";

$asignacion=new Asignacion();

$idasignacion=isset($_POST["idasignacion"])? limpiarCadena($_POST["idasignacion"]):"";
$rol=isset($_POST["rol"])? limpiarCadena($_POST["rol"]):"";
$idmodulo=isset($_POST["idmodulo"])? limpiarCadena($_POST["idmodulo"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idasignacion)) {
		$rspta=$asignacion->insertar($rol,$idmodulo);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$asignacion->editar($idasignacion,$rol,$idmodulo);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'desactivar':
		$rspta=$asignacion->desactivar($idasignacion);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$asignacion->activar($idasignacion);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$asignacion->mostrar($idasignacion);
		echo json_encode($rspta);
		break;

    case 'listar':
		$consulta="SELECT a.condicion, p.nombre AS nombrePermiso, r.nombre AS nombreRol, a.idrelrolmodulo FROM rel_rol_modulo a 
        INNER JOIN rol r ON a.idrol = r.idrol 
        INNER JOIN permiso p ON a.idmodulo = p.idpermiso LIMIT 20";
			$termino= "";
			if(isset($_POST['asignaciones']))
			{
				$termino=$conexion->real_escape_string($_POST['asignaciones']);
				$consulta="SELECT a.condicion, p.nombre AS nombrePermiso, r.nombre AS nombreRol, a.idrelrolmodulo FROM rel_rol_modulo a 
                            INNER JOIN rol r ON a.idrol = r.idrol 
                            INNER JOIN permiso p ON a.idmodulo = p.idpermiso WHERE
                            p.nombre LIKE '%".$termino."%' OR
                            r.nombre LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){			
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>asignacion</th>
							<th class='bg-info' scope='col'>Rol</th>
							<th class='bg-info' scope='col'>Modulo</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["condicion"] != 0) {
						echo "<tr>
								<td>".$fila['nombrePermiso']."</td>
								<td>".$fila['nombreRol']."</td>
								<td>							
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar asignacion' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idrelrolmodulo"].")'><i class='fa fa-pencil'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Desactivar asignacion' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idrelrolmodulo"].")')><i class='fa fa-close'></i></button>									
								</td>
							</tr>";
					} else {
						echo "<tr>
								<td>".$fila['nombrePermiso']."</td>
								<td>".$fila['nombreRol']."</td>
								<td>
									<div class='emergente'>
										<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar asignacion' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idrelrolmodulo "].")'><i class='fa fa-check'></i></button></span>
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
        case 'selectModulo':
            require_once "../modelos/Permiso.php";
            $permiso=new Permiso();

            $rspta=$permiso->listar();
            echo '<option value="" disabled selected>Seleccionar Modulo</option>';
            while ($reg=$rspta->fetch_object()) {
                echo '<option value=' . $reg->idpermiso.'>'.$reg->nombre.'</option>';
            }
        break;       
        
        case 'selectRol':
            require_once "../modelos/Rol.php";
            $rol=new Rol();

            $rspta=$rol->listar();
            echo '<option value="" disabled selected>Seleccionar Rol</option>';
            while ($reg=$rspta->fetch_object()) {
                echo '<option value=' . $reg->idrol.'>'.$reg->descripcion.'</option>';
            }
        break;       
}
 ?>