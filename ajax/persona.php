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
	$rfc=isset($_POST["rfc"])? limpiarCadena($_POST["rfc"]):"";
	$credito=isset($_POST["credito"])? limpiarCadena($_POST["credito"]):"";

	switch ($_GET["op"]) {
		case 'guardaryeditar':
		if (empty($idpersona) && empty($_POST["placas"])) {
			$rspta=$persona->insertarCliente($tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito);			
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		} else if(empty($idpersona) && $_POST["placas"] != "") {
			$rspta = $persona->insertar($tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito,$_POST["placas"],$_POST["marca"],$_POST["modelo"],$_POST["ano"],$_POST["color"],$_POST["kms"]);
			echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar los datos";
		} else if($idpersona != null && empty($_POST["placas"])) {
			$rspta=$persona->editarPersona($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito);
			echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar los datos";
		} else if($idpersona != null && $_POST["placas"] != ""){
			$rspta = $persona->editar($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito, $_POST["placas"],$_POST["marca"],$_POST["modelo"],$_POST["ano"],$_POST["color"],$_POST["kms"]);
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
		case 'listarAutos':
			$id=$_GET['id'];

			$rspta=$persona->listarAutos($id);
			echo ' <thead style="background-color:#A9D0F5">
			<th>Acciones</th>
			<th>Placas</th>
			<th>Marca</th>
			<th>Modelo</th>
			<th>Año</th>
			<th>Color</th>
			<th>Kms</th>
		</thead>';
			while ($reg=$rspta->fetch_object()) {
				echo '<tr class="filas">
				<td><button disabled>eliminar</button></td>
				<td>'.$reg->placas.'</td>
				<td>'.$reg->marca.'</td>
				<td>'.$reg->modelo.'</td>
				<td>'.$reg->ano.'</td>
				<td>'.$reg->color.'</td>
				<td>'.$reg->kms.'</td></tr>';
			}
			echo '<tfoot>
				<th></th>
				<th></th>
				<th></th>
				<th></th>
			</tfoot>';
		break;

		case 'listarp':
			// $rspta=$persona->listarp();
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

			$consulta="SELECT * FROM persona WHERE tipo_persona='Proveedor' LIMIT 40";
			$termino= "";
			if(isset($_POST['personas']))
			{
				$termino=$conexion->real_escape_string($_POST['personas']);
				$consulta="SELECT * FROM persona
				WHERE 
				nombre LIKE '%".$termino."%' OR
				direccion LIKE '%".$termino."%' OR
				telefono LIKE '%".$termino."%' OR
				email LIKE '%".$termino."%'
				AND tipo_persona='Proveedor' LIMIT 40";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='example'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Acciones</th>
							<th class='bg-info' scope='col'>Nombre</th>
							<th class='bg-info' scope='col'>Documento</th>
							<th class='bg-info' scope='col'>Telefono</th>
							<th class='bg-info' scope='col'>Email</th>
							<th class='bg-info' scope='col'>Direccion</th>
						</tr>
					</thead>
				<tbody>";
				
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
							
							$ventas_pagina = 3;
							$paginas = 13;

							echo "<tr>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idpersona"].")'><i class='fa fa-eye'></i></button>								
								<button class='btn btn-danger btn-xs' onclick='eliminar(".$fila["idpersona"].")'><i class='fa fa-trash'></i></button>
								<td>".$fila['nombre']."</td>
								<td>".$fila['tipo_documento']."</td>
								<td>".$fila['telefono']."</td>
								<td><p>".$fila['email']."</td>
								<td><p>".$fila['direccion']."</td>
							</tr>
							";					
				}
				echo "</tbody>
				<tfoot>
					<tr>					
					<th class='bg-info' scope='col'>Acciones</th>
					<th class='bg-info' scope='col'>Nombre</th>
					<th class='bg-info' scope='col'>Documento</th>
					<th class='bg-info' scope='col'>Telefono</th>
					<th class='bg-info' scope='col'>Email</th>
					<th class='bg-info' scope='col'>Direccion</th>
					</tr>
				</tfoot>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
			}

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
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idpersona"].")'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs' onclick='eliminar(".$fila["idpersona"].")')><i class='fa fa-close'></i></button><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idpersona"].")'><i class='fa fa-check'></i></button></td>					
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