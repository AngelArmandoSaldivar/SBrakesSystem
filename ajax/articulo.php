<?php 
require_once "../modelos/Articulo.php";

if(!isset($_SESSION["nombre"])) {
	$articulo=new Articulo();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

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

		case 'listar':
			// $rspta=$articulo->listar();
			// $data=Array();

			// while ($reg=$rspta->fetch_object()) {
			// 	if($reg->idsucursal == $idsucursal) {
			// 		$descrip = $reg->descripcion;
			// 		$delit = substr($descrip, 0,8);
			// 		$costoMiles = number_format($reg->costo);
			// 		$publicMiles = number_format($reg->costo);
			// 		$tallerMiles = number_format($reg->costo);
			// 		$creditoMiles = number_format($reg->costo);
			// 		$mayoreoMiles = number_format($reg->costo);

			// 	$data[]=array(
			// 		"0"=>$reg->codigo,
			// 		"1"=>$reg->fmsi,
			// 		"2"=>$reg->categoria,
			// 		"3"=>$reg->marca,
			// 		"4"=>"<p>".$delit."...</p>",
			// 		"5"=>"<p> $".$costoMiles."</p>",
			// 		"6"=>"<p> $".$publicMiles."</p>",
			// 		"7"=>"<p> $".$tallerMiles."</p>",
			// 		"8"=>"<p> $".$creditoMiles."</p>",
			// 		"9"=>"<p> $".$mayoreoMiles."</p>",
			// 		"10"=>$reg->stock,
			// 		"11"=>($reg->estado)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
			// 		"12"=>($reg->estado)?'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idarticulo.')"><i class="fa fa-close"></i></button>':'<button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idarticulo.')"><i class="fa fa-pencil"></i></button>'.' '.'<button class="btn btn-primary btn-xs" onclick="activar('.$reg->idarticulo.')"><i class="fa fa-check"></i></button>',
			// 	);
			// 	}
			// }
			// $results=array(
			// 	"sEcho"=>1,//info para datatables
			// 	"iTotalRecords"=>count($data),//enviamos el total de registros al datatable
			// 	"iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
			// 	"aaData"=>$data); 
			// echo json_encode($results);					

			$cantidad = '';

			$consulta=" SELECT * FROM articulo 	WHERE estado='1' AND stock>='$cantidad' LIMIT 30";
			$termino= "";																

			if(isset($_POST['articulos']))
			{				
				$termino=$conexion->real_escape_string($_POST['articulos']);

				echo $termino;

				$consulta="SELECT * FROM articulo WHERE
				codigo LIKE '%".$termino."%' OR
				fmsi LIKE '%".$termino."%' OR
				descripcion LIKE '%".$termino."%'
				LIMIT 30";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
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
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					$costoMiles = number_format($fila['costo']);
					$publicMiles = number_format($fila['publico']);
					$tallerMiles = number_format($fila['taller']);
					$creditoMiles = number_format($fila['credito_taller']);
					$mayoreoMiles = number_format($fila['mayoreo']);
					$descrip = $fila['descripcion'];
					$delit = substr($descrip, 0,30);

					$stock_mdx = '';

					if (isset($_POST['profile_viewer_uids'])) {
						$stock_mdx .= $_POST['profile_viewer_uids'];
						echo "LLEGASTE! ". $stock_mdx;
					}
					
					if($fila["idsucursal"] == $idsucursal && $fila["stock"] >=$stock_mdx) {
							echo "<tr>
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
								<td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idarticulo"].")')><i class='fa fa-close'></i></button><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idarticulo"].")'><i class='fa fa-check'></i></button></td>					
							</tr>";
					}
				}
				echo "</tbody>
				<tfoot>
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
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";
				echo "<img src='../files/img/products_brembo.jpg'>";
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