<?php 
require_once "../modelos/Categoria.php";

$categoria=new Categoria();

$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':
	if (empty($idcategoria)) {
		$rspta=$categoria->insertar($nombre,$descripcion);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
	}else{
         $rspta=$categoria->editar($idcategoria,$nombre,$descripcion);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
	}
		break;
	

	case 'desactivar':
		$rspta=$categoria->desactivar($idcategoria);
		echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
	case 'activar':
		$rspta=$categoria->activar($idcategoria);
		echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;
	
	case 'mostrar':
		$rspta=$categoria->mostrar($idcategoria);
		echo json_encode($rspta);
		break;

    case 'listar':
		$consulta=" SELECT * FROM categoria LIMIT 20";
			$termino= "";
			if(isset($_POST['categorias']))
			{
				$termino=$conexion->real_escape_string($_POST['categorias']);
				$consulta="SELECT * FROM categoria WHERE
				nombre LIKE '%".$termino."%' OR
				descripcion LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Categoria</th>
							<th class='bg-info' scope='col'>Descripcion</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
							echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['descripcion']."</td>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idcategoria"].")'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idcategoria"].")')><i class='fa fa-close'></i></button><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idcategoria"].")'><i class='fa fa-check'></i></button></td>					
							</tr>";
					}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";				
			}
		break;
}
 ?>