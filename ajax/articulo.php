<?php 
require_once "../modelos/Articulo.php";

if(!isset($_SESSION["nombre"])) {
	$articulo=new Articulo();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];
	$acceso = $_SESSION['acceso'];

	$idarticulo=isset($_POST["idarticulo"])? limpiarCadena($_POST["idarticulo"]):"";
	$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
	$idproveedor=isset($_POST["idproveedor"])? limpiarCadena($_POST["idproveedor"]):"";
	$codigo=isset($_POST["codigo"])? limpiarCadena($_POST["codigo"]):"";
	$barcode=isset($_POST["barcode"])? limpiarCadena($_POST["barcode"]):"";
	$pasillo=isset($_POST["pasillo"])? limpiarCadena($_POST["pasillo"]):"";
	$unidades=isset($_POST["unidades"])? limpiarCadena($_POST["unidades"]):"";
	$fmsi=isset($_POST["fmsi"])? limpiarCadena($_POST["fmsi"]):"";
	$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
	$marca=isset($_POST["marca"])? limpiarCadena($_POST["marca"]):"";
	$costo=isset($_POST["costo"])? limpiarCadena($_POST["costo"]):"";
	$publico=isset($_POST["publico"])? limpiarCadena($_POST["publico"]):"";
	$taller=isset($_POST["taller"])? limpiarCadena($_POST["taller"]):"";
	$credito_taller=isset($_POST["credito_taller"])? limpiarCadena($_POST["credito_taller"]):"";
	$mayoreo=isset($_POST["mayoreo"])? limpiarCadena($_POST["mayoreo"]):"";
	$stock=isset($_POST["stock"])? limpiarCadena($_POST["stock"]):"";	
	$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";
	$imagen=isset($_POST["imagen"])? limpiarCadena($_POST["imagen"]):"";

	switch ($_GET["op"]) {
		case 'guardaryeditar':
				if (empty($idarticulo)) {
					$rspta=$articulo->insertar($codigo,$costo, $barcode, $credito_taller, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $mayoreo, $pasillo, $publico, $stock, $taller, $unidades, $idsucursal);			
					echo $rspta;
					echo $rspta ? "Articulo registrado correctamente" : "No se registro correctamente";
				}else{
					$rspta=$articulo->editar($idarticulo,$codigo,$costo, $barcode, $credito_taller, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $mayoreo, $pasillo, $publico, $stock, $taller, $unidades);
					echo $rspta ? " Articulo actualizado correctamente" : "No se pudo actualizar los datos";
				}
		break;	
		case 'desactivar':
			$rspta=$articulo->desactivar($idarticulo);
			echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
		break;
		case 'activar':
			$rspta=$articulo->activar($idarticulo);
			echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
		break;	
		case 'mostrar':
			$rspta=$articulo->mostrar($idarticulo);
			echo json_encode($rspta);
			break;	
		case 'listarArticulos': 
			$array = array();
			$rspta=$articulo->listarArticulos();			
			while ($reg=$rspta->fetch_object()) {
				array_push($array, $reg->codigo);
			}
			//$arrayData = implode(',', $array);
			echo json_encode($array);
			break;

		case 'listar':							

			$cantidad = '';			
			$termino= "";
									
			$consulta = $articulo->articulosPagination(50, 0, "");

			if(!empty($_POST['articulos']) && empty($_POST['limites']) && empty($_POST['inicio_registros']) && empty($_POST["total_registros"])) {				
				$termino=$conexion->real_escape_string($_POST['articulos']);
				usleep(100000);								
				$consulta=$articulo->articulosPagination(50,0, $termino);

			} 
			else if(!empty($_POST['articulos']) && !empty($_POST['limites'])) {	
				usleep(100000);								
				$termino=$conexion->real_escape_string($_POST['articulos']);
				$limites=$conexion->real_escape_string($_POST['limites']);
				$consulta=$articulo->articulosPagination($limites,0, $termino);

			} else if(!empty($_POST['busqueda']) && !empty($_POST['inicio_registros']) && !empty($_POST["total_registros"])) {								
				usleep(100000);				
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$inicio=$conexion->real_escape_string($_POST['inicio_registros']);
				$fin=$conexion->real_escape_string($_POST['total_registros']);				
				$consulta=$articulo->articulosPagination($fin,$inicio, $busqueda);

			} else if(!empty($_POST['inicio_registros']) && !empty($_POST["total_registros"])) {
				usleep(100000);				
				$inicio=$conexion->real_escape_string($_POST['inicio_registros']);
				$fin=$conexion->real_escape_string($_POST['total_registros']);				
				$consulta=$articulo->articulosPagination($fin,$inicio, "");

			}

			$consultaBD=$consulta;
			//2000000
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='border-radius: 15px;' id='tableArticulos'>
					<thead class='table-light' style='font-size:12px'>
						<tr background: linear-gradient(337deg, rgba(0, 1, 255, 0.682) 0%, rgba(255, 0, 0, 0.71) 50%, rgba(0, 246, 144, 0.737) 100%);>
							<th class='bg-info w-50' scope='col'>Clave</th>
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
					$delit = substr($descrip, 0,18);
					$delitCodigo = substr($fila['codigo'], 0, 50);
					$delitFmsi = substr($fila['fmsi'], 0, 50);
					$stock_mdx = '';					
					if($fila["idsucursal"] == $idsucursal && $acceso === "admin") {
						if($fila["stock"] >=1) {							
								echo "<tr style='color:blue; font-size:11px;'>
								<td >".$fila['codigo']."</td>
								<td style='width:10px;'>".$delitFmsi."</td>
								<td style='width:10px;'>".$fila['marca']."</td>
								<td>".$delit."...</td>
								<td><p>$ ".$costoMiles."</p></td>
								<td><p>$ ".$publicMiles."</p></td>
								<td><p>$ ".$tallerMiles."</p></td>
								<td><p>$ ".$creditoMiles."</p></td>
								<td><p>$ ".$mayoreoMiles."</p></td>
								<td><p>".$fila['stock']."pz</td>
								<td>
										<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-eye'></i></button>
										<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar articulo' data-placement='bottom' class='btn btn-warning btn-xs' onclick='editarArticulo(".$fila["idarticulo"].")'><i class='fa fa-pencil'></i></button>
										<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Eliminar articulo' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idarticulo"].")')><i class='fa fa-close'></i></button>
										<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Solicitar articulo' data-placement='bottom' class='btn btn-info btn-xs' onclick='solicitar(".$fila["idarticulo"].")')><i class='fa fa-paper-plane'></i></button>
										<!--<span data-tooltip='Activar articulo'><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idarticulo"].")'><i class='fa fa-check'></i></button></span>-->									
								</td>
							</tr>
							";							
						} else if($fila["stock"] <=0){							
								echo "<tr style='color:red; font-size:11px;'>
								<td>".$fila['codigo']."</td>
								<td>".$fila['fmsi']."</td>
								<td>".$fila['marca']."</td>
								<td>".$delit."...</td>
								<td><p>$ ".$costoMiles."</p></td>
								<td><p>$ ".$publicMiles."</p></td>
								<td><p>$ ".$tallerMiles."</p></td>
								<td><p>$ ".$creditoMiles."</p></td>
								<td><p>$ ".$mayoreoMiles."</p></td>
								<td><p>".$fila['stock']."pz</td>
								<td>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-eye'></i></button>
									<button title='Editar' data-toggle='popover' data-trigger='hover' data-content='Editar articulo' data-placement='bottom' class='btn btn-warning btn-xs' onclick='editarArticulo(".$fila["idarticulo"].")'><i class='fa fa-pencil'></i></button>
									<button title='Eliminar' data-toggle='popover' data-trigger='hover' data-content='Eliminar articulo' data-placement='top' class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idarticulo"].")')><i class='fa fa-close'></i></button>
									<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Solicitar articulo' data-placement='bottom' class='btn btn-info btn-xs' onclick='solicitar(".$fila["idarticulo"].")')><i class='fa fa-paper-plane'></i></button>
								</td>
							</tr>";								
						}
					}
					else if($fila["idsucursal"] == $idsucursal && $acceso != "admin"){
						if($fila["stock"] >=1) {
							echo "<tr style='color:blue; font-size:11px;'>
								<td >".$fila['codigo']."</td>
								<td>".$fila['fmsi']."</td>
								<td>".$fila['marca']."</td>
								<td>".$delit."...</td>
								<td><p>$ ".$costoMiles."</p></td>
								<td><p>$ ".$publicMiles."</p></td>
								<td><p>$ ".$tallerMiles."</p></td>
								<td><p>$ ".$creditoMiles."</p></td>
								<td><p>$ ".$mayoreoMiles."</p></td>
								<td><p>".$fila['stock']."pz</td>
								<td>
									<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-eye'></i></button>
									<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Solicitar articulo' data-placement='bottom' class='btn btn-info btn-xs' onclick='solicitar(".$fila["idarticulo"].")')><i class='fa fa-paper-plane'></i></button>
							</tr>";
						} 						
						else if($fila["stock"] <=0) {
							echo "<tr style='color:red; font-size:11px;'>
								<td>".$fila['codigo']."</td>
								<td>".$fila['fmsi']."</td>
								<td>".$fila['marca']."</td>
								<td>".$delit."...</td>
								<td><p>$ ".$costoMiles."</p></td>
								<td><p>$ ".$publicMiles."</p></td>
								<td><p>$ ".$tallerMiles."</p></td>
								<td><p>$ ".$creditoMiles."</p></td>
								<td><p>$ ".$mayoreoMiles."</p></td>
								<td><p>".$fila['stock']."pz</td>
								<td>
								<button title='Mostrar' data-toggle='popover' data-trigger='hover' data-content='Mostrar articulo' data-placement='top' class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-eye'></i></button>
								<button title='Solicitar' data-toggle='popover' data-trigger='hover' data-content='Solicitar articulo' data-placement='bottom' class='btn btn-info btn-xs' onclick='solicitar(".$fila["idarticulo"].")')><i class='fa fa-paper-plane'></i></button>
							</tr>";	
						}	
					}
				}
				echo "</tbody>
				<tfoot style='font-size:12px'>
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
				</table>				
				";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
			}
			break;

			case 'selectCategoria':
				require_once "../modelos/Categoria.php";
				$categoria=new Categoria();

				$rspta=$categoria->select();

				while ($reg=$rspta->fetch_object()) {
					echo '<option value=' . $reg->idcategoria.'>'.$reg->nombre.'</option>';
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
	}
	
}

 ?>