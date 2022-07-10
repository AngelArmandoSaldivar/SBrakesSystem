<?php 
require_once "../modelos/Garantia.php";
session_start();
$idsucursal = $_SESSION['idsucursal'];
$garantia=new Garantia();

$idcategoria=isset($_POST["idcategoria"])? limpiarCadena($_POST["idcategoria"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$descripcion=isset($_POST["descripcion"])? limpiarCadena($_POST["descripcion"]):"";

switch ($_GET["op"]) {

	case 'filtrar':
			$consulta = $garantia->filtros("", "", "", "", "");
			$termino= "";
			if(!empty($_POST['busqueda']) && empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && empty($_POST['tipo_movimiento'])
			|| !empty($_POST['busqueda']) && !empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && empty($_POST['tipo_movimiento'])
			|| !empty($_POST['busqueda']) && !empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && !empty($_POST['tipo_movimiento'])
			|| empty($_POST['busqueda']) && !empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && empty($_POST['tipo_movimiento'])
			|| empty($_POST['busqueda']) && !empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && !empty($_POST['tipo_movimiento'])
			|| !empty($_POST['busqueda']) && empty($_POST['limite_registros']) && !empty($_POST['fecha_inicio']) && !empty($_POST['fecha_fin']) && !empty($_POST['tipo_movimiento'])
			||empty($_POST['busqueda']) && empty($_POST['limite_registros']) && empty($_POST['fecha_inicio']) && empty($_POST['fecha_fin']) && empty($_POST['tipo_movimiento'])){
				$busqueda=$conexion->real_escape_string($_POST['busqueda']);
				$fecha_inicio=$conexion->real_escape_string($_POST['fecha_inicio']);
				$fecha_fin=$conexion->real_escape_string($_POST['fecha_fin']);
				$limite_registros=$conexion->real_escape_string($_POST['limite_registros']);
				$tipo_movimiento=$conexion->real_escape_string($_POST['tipo_movimiento']);
				$consulta=$garantia->filtros($busqueda,$limite_registros, $fecha_inicio, $fecha_fin, $tipo_movimiento);
			}
			$consultaBD=$consulta;
			if($consultaBD->num_rows>=1){
				echo "
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
							<th class='bg-info' scope='col'>Folio Servicio / Venta</th>
							<th class='bg-info' scope='col'>Fecha</th>
							<th class='bg-info' scope='col'>Clave</th>
							<th class='bg-info' scope='col'>Fmsi</th>
							<th class='bg-info' scope='col'>Cantidad</th>
							<th class='bg-info' scope='col'>Descripción</th>							
							<th class='bg-info' scope='col'>Tipo de Movimiento</th>
							<th class='bg-info' scope='col'>Precio</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					echo "<tr>
							<td>".$fila['idservicio']."</td>
							<td>".$fila['fecha_hora']."</td>
							<td>".$fila['codigo']."</td>
							<td>".$fila['fmsi']."</td>
							<td>".$fila['cantidad']."</td>
							<td>".$fila['descripcion']."</td>
							<td>".$fila['tipo_mov']."</td>
							<td>$".number_format($fila['precio_garantia'], 2)."</td>
						</tr>";
				}
			} else {
				echo "Ningun resultado";
			}						
		break;

    case 'listar':
			$consulta="SELECT g.precio_garantia,DATE(g.fecha_hora) as fecha_hora, a.codigo, a.fmsi, g.descripcion, g.cantidad, g.idsucursal, g.tipo_mov, g.idservicio FROM garantias AS g INNER JOIN articulo a ON g.idarticulo=a.idarticulo LIMIT 20";
			$termino= "";
			if(isset($_POST['garantias']))
			{
				$termino=$conexion->real_escape_string($_POST['garantias']);
				$consulta="SELECT g.precio_garantia,DATE(g.fecha_hora) as fecha_hora,a.codigo, a.fmsi, g.descripcion, g.cantidad, g.idsucursal, g.tipo_mov, g.idservicio FROM garantias AS g INNER JOIN articulo a ON g.idarticulo=a.idarticulo WHERE
				codigo LIKE '%".$termino."%' OR
				fmsi LIKE '%".$termino."%' LIMIT 20";
			}
			$consultaBD=$conexion->query($consulta);
			if($consultaBD->num_rows>=1){
				echo "				
				<table class='responsive-table table table-hover table-bordered' style='font-size:12px'>
					<thead class='table-light'>
						<tr>
                            <th class='bg-info' scope='col'>Folio Servicio / Venta</th>
							<th class='bg-info' scope='col'>Fecha</th>
							<th class='bg-info' scope='col'>Clave</th>
							<th class='bg-info' scope='col'>Fmsi</th>
                            <th class='bg-info' scope='col'>Cantidad</th>
                            <th class='bg-info' scope='col'>Descripción</th>							
                            <th class='bg-info' scope='col'>Tipo de Movimiento</th>
							<th class='bg-info' scope='col'>Precio</th>
						</tr>
					</thead>
				<tbody>";
				while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
					//if($fila["idsucursal"] == $idsucursal) {
						echo "<tr>
                                <td>".$fila['idservicio']."</td>
								<td>".$fila['fecha_hora']."</td>
								<td>".$fila['codigo']."</td>
								<td>".$fila['fmsi']."</td>
                                <td>".$fila['cantidad']."</td>
                                <td>".$fila['descripcion']."</td>								
                                <td>".$fila['tipo_mov']."</td>
								<td>$".number_format($fila['precio_garantia'], 2)."</td>
							</tr>";
					/*} else {
						echo "<tr>
								<td>".$fila['nombre']."</td>
								<td>".$fila['descripcion']."</td>
								<td>
									<div class='emergente'>
										<button title='Activar' data-toggle='popover' data-trigger='hover' data-content='Activar categoria' data-placement='top' class='btn btn-primary btn-xs' onclick='activar(".$fila["idcategoria"].")'><i class='fa fa-check'></i></button></span>
									</div>
								</td>
							</tr>";
					}*/
							
					}
				echo "</tbody>
				</table>";
			}else{
				echo "<center><h4>No hemos encotrado ningun articulo (ง︡'-'︠)ง con: "."<strong class='text-uppercase'>".$termino."</strong><h4><center>";				
			}
		break;
}
 ?>