<?php 
session_start();
require_once "../modelos/Usuario.php";
require_once "../modelos/Sucursal.php";

$usuario=new Usuario();
$sucursal=new Sucursal();

//$idusuarioSession = !$_SESSION['idusuario'] ? null : $_SESSION['idusuario'];
$idusuarioSession = isset($_SESSION['idusuario']) == null ? null : $_SESSION['idusuario'];

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
$foto=isset($_POST["foto"])? limpiarCadena($_POST["foto"]):"";


switch ($_GET["op"]) {
	case 'guardaryeditar':	

	//Hash SHA256 para la contraseña
	if (!file_exists($_FILES['foto']['tmp_name'])|| !is_uploaded_file($_FILES['foto']['tmp_name'])) {
		$foto=$_POST["fotoactual"];
	}else{
		$ext=explode(".", $_FILES["foto"]["name"]);	
		if ($_FILES['foto']['type']=="image/jpg" || $_FILES['foto']['type']=="image/jpeg" || $_FILES['foto']['type']=="image/png") {			
			$foto=round(microtime(true)).'.'. end($ext);
			move_uploaded_file($_FILES["foto"]["tmp_name"], "../files/usuarios/".$foto);
		}
	}
	$clavehash=hash("SHA256", $clave);
	if (empty($idusuario)) {
		$rspta=$usuario->insertar($nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clavehash,$idsucursal, $foto);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
	}else{
		$rspta=$usuario->editar($idusuario,$nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clavehash,$idsucursal, $foto);
		echo $rspta ? "Datos registrados correctamente" : "No se pudo registrar todos los datos del usuario";
	}
	break;	

	case 'listarSucursales':		

		$rspta=$sucursal->listar();		

		require_once "../modelos/Permiso.php";
		$permiso=new Permiso();
		$rsptaper=$permiso->listar();
		//obtener permisos asigandos		
		
		//almacenar permisos asigandos
		/*while ($per=$marcados->fetch_object()) {
			array_push($valores, $per->idpermiso);
		}
				//mostramos la lista de permisos
		while ($reg=$rspta->fetch_object()) {
			$sw=in_array($reg->idpermiso,$valores)?'checked':'';
			echo '<li><input class="permisos" type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
		}*/

		echo ' 
		<div class="loader">
			<img src="../files/images/loader.gif" alt="">
		</div>
		<thead style="background-color:#A9D0F5">
			<th>Sucursal</th>
			<th>Permisos</th>			
		</thead>';
		$registrosPermisos = "";
		while ($res=$rsptaper->fetch_object()) {	
			$registrosPermisos = '<ul><li><input class="permisos" type="checkbox" name="permiso[]" value="'.$res->idpermiso.'">'.$res->nombre.'</li></ul>';
			echo $registrosPermisos;
			while ($reg=$rspta->fetch_object()) {											
				//echo json_encode($reg->nombre);	
				echo '<tr class="filas">				
				<td>'.$reg->nombre.'</td>
				<td><ul><li><input class="permisos" type="checkbox" name="permiso[]" value="'.$res->idpermiso.'">'.$res->nombre.'</li></ul></td>';			
			}
		}				
	break;

	case 'cambiarsucursal':
		unset($_SESSION['idsucursal']);
		header("Location: ../vistas/sucursales.php?idusuario=".$idusuarioSession);
		die();
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

	case 'mostrarUsuario':
		$rspta=$usuario->mostrarUsuario($idusuarioSession);
		echo json_encode($rspta);
		break;

	case 'listar':	
			$consulta="SELECT nombre, direccion, email, telefono, login, acceso, condicion, idusuario FROM usuario LIMIT 40";
			$termino= "";
			if(isset($_POST['usuarios']))
			{
				$termino=$conexion->real_escape_string($_POST['usuarios']);
				$consulta="SELECT nombre, direccion, email, telefono, login, acceso, condicion, idusuario FROM usuario
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
							<th class='bg-info' scope='col'>Nombre</th>
							<th class='bg-info' scope='col'>Telefono</th>
							<th class='bg-info' scope='col'>Email</th>
							<th class='bg-info' scope='col'>Usuario</th>
							<th class='bg-info' scope='col'>Nivel de usuario</th>
							<th class='bg-info' scope='col'>Status</th>
							<th class='bg-info' scope='col'>Acciones</th>
						</tr>
					</thead>
				<tbody>";				
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					if($fila["condicion"] != 0) {
						$status = $fila["condicion"] = 1 ? "ACTIVO" : "INACTIVO";
						echo "<tr>								
								<td>".$fila['nombre']."</td>
								<td>".$fila['telefono']."</td>
								<td><p>".$fila['email']."</td>
								<td><p>".$fila['login']."</td>
								<td><p>".$fila['acceso']."</td>
								<td><p>".$status."</td>
								<td>									
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar usuario' data-placement='top' class='btn btn-success btn-xs' onclick='mostrar(".$fila["idusuario"].")'><i class='fa fa-eye'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Eliminar usuario' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idusuario"].")'><i class='fa fa-close'></i></button>
								</td>
							</tr>
							";
					} else {						
						echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['telefono']."</td>
								<td><p>".$fila['email']."</td>
								<td><p>".$fila['login']."</td>
								<td><p>".$fila['acceso']."</td>
								<td><p>".$fila["condicion"] = 0 ? 'ACTIVO' : 'INACTIVO'."</td>
								<td>									
									<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar usuario' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idusuario"].")'><i class='fa fa-check'></i></button>																			
								</td>
							</tr>
							";
					}							
				}
				echo "</tbody>
				<tfoot>
					<tr>
						<th class='bg-info' scope='col'>Nombre</th>						
						<th class='bg-info' scope='col'>Telefono</th>
						<th class='bg-info' scope='col'>Email</th>
						<th class='bg-info' scope='col'>Usuario</th>
						<th class='bg-info' scope='col'>Nivel de usuario</th>
						<th class='bg-info' scope='col'>Status</th>
						<th class='bg-info' scope='col'>Acciones</th>
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
		echo '<li><input class="permisos" type="checkbox" '.$sw.' name="permiso[]" value="'.$reg->idpermiso.'">'.$reg->nombre.'</li>';
	}
	break;

	case 'listarSucursales': 
		$id=$_GET['id'];
		$listarMarcados = $usuario->sucursales($id);
		$valores=array();
		$allSucursales = $usuario->todasSucursales();
		while($reg = $listarMarcados->fetch_object()) {			
			array_push($valores, $reg->idsucursal);
		}
		while($res = $allSucursales->fetch_object()) {
			$sw = in_array($res->idsucursal, $valores)?'checked':'';
			echo '<li><input class="sucursales" type="checkbox" '.$sw.' name="sucursal[]" value="'.$res->idsucursal.'">'.$res->nombre.'</li>';
		}

		break;

	case 'sucursales': 
		$rspta = $usuario->todasSucursales();
		$valores=array();
		while ($reg=$rspta->fetch_object()) {
			$sw=in_array($reg->idsucursal,$valores)?'checked':'';
			echo '<li><input type="checkbox" '.$sw.' name="sucursal[]" value="'.$reg->idsucursal.'">'.$reg->nombre.'</li>';
		}
		break;

	case 'ingresarSucursal':
		$idsucursal=$_POST['idsucursal'];
		$_SESSION['idsucursal']=$idsucursal;

		//obtenemos los permisos
		$marcados=$usuario->listarmarcados($_SESSION['acceso']);

		//declaramos el array para almacenar todos los permisos
		$valores=array();

		//almacenamos los permisos marcados en al array
		while ($per = $marcados->fetch_object()) {
			array_push($valores, $per->permiso);
		}

		//determinamos lo accesos al usuario
		in_array(1, $valores)?$_SESSION['escritorio']=1:$_SESSION['escritorio']=0;
		in_array(2, $valores)?$_SESSION['almacen']=1:$_SESSION['almacen']=0;
		in_array(3, $valores)?$_SESSION['compras']=1:$_SESSION['compras']=0;
		in_array(4, $valores)?$_SESSION['ventas']=1:$_SESSION['ventas']=0;		
		in_array(5, $valores)?$_SESSION['accesos']=1:$_SESSION['accesos']=0;
		in_array(6, $valores)?$_SESSION['consultac']=1:$_SESSION['consultac']=0;
		in_array(7, $valores)?$_SESSION['consultav']=1:$_SESSION['consultav']=0;
		in_array(8, $valores)?$_SESSION['kardex']=1:$_SESSION['kardex']=0;
		in_array(9, $valores)?$_SESSION['caja']=1:$_SESSION['caja']=0;
		in_array(10, $valores)?$_SESSION['servicios']=1:$_SESSION['servicios']=0;
		in_array(11, $valores)?$_SESSION['cotizaciones']=1:$_SESSION['cotizaciones']=0;
		in_array(12, $valores)?$_SESSION['sucursal']=1:$_SESSION['sucursal']=0;
		echo "Ingresaste correctamente";
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
		$_SESSION['acceso']=$fetch->acceso;		
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

