<?php 
require_once "../modelos/Persona.php";
if(!isset($_SESSION["nombre"])) {
	$persona=new Persona();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

	$idpersona=isset($_POST["idpersona"])? limpiarCadena($_POST["idpersona"]):"";
	$tipo_persona=isset($_POST["tipo_persona"])? limpiarCadena($_POST["tipo_persona"]):"";
	$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
	$tipo_documento=isset($_POST["tipo_documento"])? limpiarCadena($_POST["tipo_documento"]):"";
	$tipo_precio=isset($_POST["tipo_precio"])? limpiarCadena($_POST["tipo_precio"]):"";
	$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
	$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
	$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
	$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";

	switch ($_GET["op"]) {
		case 'guardaryeditar':
		if (empty($idpersona)) {
			$rspta=$persona->insertar($tipo_persona,$nombre,$tipo_documento,$tipo_precio,$num_documento,$direccion,$telefono,$email);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		}else{
			$rspta=$persona->editar($idpersona,$tipo_persona,$nombre,$tipo_documento,$tipo_precio,$num_documento,$direccion,$telefono,$email);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		}
			break;
		

		case 'eliminar':
			$rspta=$persona->eliminar($idpersona);
			echo $rspta ? "Datos eliminados correctamente" : "No se pudo eliminar los datos";
			break;
		
		case 'mostrar':
			$rspta=$persona->mostrar($idpersona);
			echo json_encode($rspta);
			break;

		case 'listarp':
			$rspta=$persona->listarp();
			$data=Array();

			while ($reg=$rspta->fetch_object()) {
				$data[]=array(
				"0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
				"1"=>$reg->nombre,
				"2"=>$reg->tipo_documento,
				"3"=>$reg->num_documento,
				"4"=>$reg->telefono,
				"5"=>$reg->email
				);
			}
			$results=array(
				"sEcho"=>1,//info para datatables
				"iTotalRecords"=>count($data),//enviamos el total de registros al datatable
				"iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
				"aaData"=>$data); 
			echo json_encode($results);
			break;

			case 'listarc':
			// $rspta=$persona->listarc();
			// $data=Array();

			// while ($reg=$rspta->fetch_object()) {
			// 	$data[]=array(
			// 	"0"=>'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idpersona.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="eliminar('.$reg->idpersona.')"><i class="fa fa-trash"></i></button>',
			// 	"1"=>$reg->nombre,
			// 	"2"=>$reg->tipo_documento,
			// 	"3"=>$reg->num_documento,
			// 	"4"=>$reg->telefono,
			// 	"5"=>$reg->email
			// 	);
			// }
			// $results=array(
			// 	"sEcho"=>1,//info para datatables
			// 	"iTotalRecords"=>count($data),//enviamos el total de registros al datatable
			// 	"iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
			// 	"aaData"=>$data); 
			// echo json_encode($results);
			$consulta="SELECT * FROM persona WHERE tipo_persona='Cliente'";
			$termino= "";
			if(isset($_POST['clientes']))
			{
				$termino=$conexion->real_escape_string($_POST['clientes']);
				$consulta="SELECT * FROM persona WHERE tipo_persona='Cliente'
				AND
				nombre LIKE '%".$termino."%' OR
				direccion LIKE '%".$termino."%' OR
				email LIKE '%".$termino."%' OR
				telefono LIKE '%".$termino."%'";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Cliente</th>
							<th class='bg-info' scope='col'>Dirección</th>
							<th class='bg-info' scope='col'>Telefono</th>
							<th class='bg-info' scope='col'>Email</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
							echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['direccion']."</td>
								<td>".$fila['telefono']."</td>
								<td><p>".$fila['email']."</td>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idpersona"].")'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idpersona"].")')><i class='fa fa-close'></i></button><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idpersona"].")'><i class='fa fa-check'></i></button></td>					
							</tr>";
				}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
			}
			break;
	}
}
 ?>