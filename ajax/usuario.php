<?php 
session_start();
require_once "../modelos/Usuario.php";

$usuario=new Usuario();

$idusuario=isset($_POST["idusuario"])? limpiarCadena($_POST["idusuario"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$acceso=isset($_POST["idNivelUsuario"])? limpiarCadena($_POST["idNivelUsuario"]):"";
$num_documento=isset($_POST["num_documento"])? limpiarCadena($_POST["num_documento"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$email=isset($_POST["email"])? limpiarCadena($_POST["email"]):"";
$cargo=isset($_POST["cargo"])? limpiarCadena($_POST["cargo"]):"";
$login=isset($_POST["login"])? limpiarCadena($_POST["login"]):"";
$clave=isset($_POST["clave"])? limpiarCadena($_POST["clave"]):"";
$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";
$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':	

	//Hash SHA256 para la contraseña
	$clavehash=hash("SHA256", $clave);
	if (empty($idusuario)) {
		$rspta=$usuario->insertar($nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clavehash,$_POST['permiso'], $idsucursal);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
	}else{
		$rspta=$usuario->editar($idusuario,$nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clavehash,$_POST['permiso'], $idsucursal);
		echo "Sucursal: '$idsucursal'";
	
	}
	break;	

	case 'desactivar':
	$rspta=$usuario->desactivar($idusuario);
	echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
	$rspta=$usuario->activar($idusuario);
	echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
	$rspta=$usuario->mostrar($idusuario);
	echo json_encode($rspta);
	break;

	case 'listar':
	// $rspta=$usuario->listar();
	// $data=Array();

	// while ($reg=$rspta->fetch_object()) {
	// 	$data[]=array(
	// 		"0"=>($reg->condicion)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idusuario.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idusuario.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idusuario.')"><i class="fa fa-check"></i></button>',
	// 		"1"=>$reg->nombre,
	// 		"2"=>$reg->tipo_documento,
	// 		"3"=>$reg->num_documento,
	// 		"4"=>$reg->telefono,
	// 		"5"=>$reg->email,
	// 		"6"=>$reg->login,
	// 		"7"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>'
	// 	);
	// }

	// $results=array(
    //          "sEcho"=>1,//info para datatables
    //          "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
    //          "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
    //          "aaData"=>$data);
	// echo json_encode($results);

	$consulta="SELECT * FROM usuario LIMIT 40";
			$termino= "";
			if(isset($_POST['usuarios']))
			{
				$termino=$conexion->real_escape_string($_POST['usuarios']);
				$consulta="SELECT * FROM usuario
				WHERE
				nombre LIKE '%".$termino."%' OR				
				direccion LIKE '%".$termino."%' OR
				email LIKE '%".$termino."%' OR
				telefono LIKE '%".$termino."%' OR
				login LIKE '%".$termino."%'
				LIMIT 40";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='example'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Acciones</th>
							<th class='bg-info' scope='col'>Nombre</th>
							<th class='bg-info' scope='col'>Telefono</th>
							<th class='bg-info' scope='col'>Email</th>
							<th class='bg-info' scope='col'>Usuario</th>
							<th class='bg-info' scope='col'>Nivel de usuario</th>
							<th class='bg-info' scope='col'>Status</th>
						</tr>
					</thead>
				<tbody>";				
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
							echo "<tr>
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idusuario"].")'><i class='fa fa-eye'></i></button> <button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idusuario"].")'><i class='fa fa-close'></i></button>
								<button class='btn btn-primary btn-xs' onclick='activar(".$fila["idusuario"].")'><i class='fa fa-check'></i></button>
								<td>".$fila['nombre']."</td>
								<td>".$fila['telefono']."</td>
								<td><p>".$fila['email']."</td>
								<td><p>".$fila['login']."</td>
								<td><p>".$fila['acceso']."</td>
								<td><p>".$fila["condicion"]."</td>							
							</tr>
							";
				}
				echo "</tbody>
				<tfoot>
					<tr>
						<th class='bg-info' scope='col'>Acciones</th>
						<th class='bg-info' scope='col'>Nombre</th>						
						<th class='bg-info' scope='col'>Telefono</th>
						<th class='bg-info' scope='col'>Email</th>
						<th class='bg-info' scope='col'>Usuario</th>
						<th class='bg-info' scope='col'>Nivel de usuario</th>
						<th class='bg-info' scope='col'>Status</th>
					</tr>
				</tfoot>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
			}
	break;

	case 'permisos':
			//obtenemos toodos los permisos de la tabla permisos
	require_once "../modelos/Permiso.php";
	$permiso=new Permiso();
	$rspta=$permiso->listar();
	//obtener permisos asigandos
	$id=$_GET['id'];
	$marcados=$usuario->listarmarcados($id);
	$valores=array();

//almacenar permisos asigandos
	while ($per=$marcados->fetch_object()) {
		array_push($valores, $per->idpermiso);
	}
			//mostramos la lista de permisos
	while ($reg=$rspta->fetch_object()) {
		$sw=in_array($reg->idpermiso,$valores)?'checked':'';
		echo '<li><input type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
	}
	break;

	case 'verificar':		
	//validar si el usuario tiene acceso al sistema
	$logina=$_POST['logina'];
	$clavea=$_POST['clavea'];

	//Hash SHA256 en la contraseña
	$clavehash=hash("SHA256", $clavea);
	
	$rspta=$usuario->verificar($logina, $clavehash);	

	$fetch=$rspta->fetch_object();
	if (isset($fetch)) {
		# Declaramos la variables de sesion
		$_SESSION['idusuario']=$fetch->idusuario;
		$_SESSION['nombre']=$fetch->nombre;
		$_SESSION['login']=$fetch->login;
		$_SESSION['idsucursal']=$fetch->idsucursal;
		$_SESSION['acceso']=$fetch->acceso;		 

		//obtenemos los permisos
		$marcados=$usuario->listarmarcados($fetch->idusuario);

		//declaramos el array para almacenar todos los permisos
		$valores=array();

		//almacenamos los permisos marcados en al array
		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}

		//determinamos lo accesos al usuario
		in_array(1, $valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
		in_array(2, $valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
		in_array(3, $valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
		in_array(4, $valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;
		in_array(4, $valores)?$_SESSION['servicios']=1:$_SESSION['servicios']=0;
		in_array(5, $valores)?$_SESSION['accesos']=1:$_SESSION['accesos']=0;
		in_array(6, $valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
		in_array(7, $valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
		in_array(7, $valores)?$_SESSION['sucursal']=1:$_SESSION['sucursal']=0;

	}
	echo json_encode($fetch);
	break;
	case 'salir':
	   //limpiamos la variables de la sesión
	session_unset();

	  //destruimos la sesion
	session_destroy();
		  //redireccionamos al login
	header("Location: ../index.php");
	break;

	case 'selectSucursal':
			require_once "../modelos/Sucursal.php";
			$sucursal = new Sucursal();

			$rspta = $sucursal->listar();
			echo '<option value="" disabled selected>Seleccionar sucursal</option>';
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idsucursal.'>'.$reg->nombre.'</option>';
			}
		break;
	
}
?>

