<?php 
require "../config/Conexion.php";

class Articulo{	

	//implementamos nuestro constructor
	public function __construct(){

	}

	function consultaMarcaPorId($idMarca) {
		
	}

	//metodo insertar registro
	public function insertar($codigo, $costo, $publico, $taller, $credito_taller, $mayoreo, $barcode, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $pasillo,$stock, $unidades, $idsucursal, $imagen, $dibujo, $stock_ideal, $bandera_inventariable){						
		
		if ($publico == '' && $taller == '' && $credito_taller == '' && $mayoreo == '') {
			$sql = "SELECT * FROM marca WHERE idMarca='$marca'";
			$data = ejecutarConsulta($sql);		
			while($fila=$data->fetch_array(MYSQLI_ASSOC)){
				$publico = ((($fila["utilidad_1"] / 100) * $costo) + $costo);
				$taller = ((($fila["utilidad_2"] / 100) * $costo) + $costo);
				$credito_taller = ((($fila["utilidad_3"] / 100) * $costo) + $costo);
				$mayoreo = ((($fila["utilidad_4"] / 100) * $costo) + $costo);
			}	
		}		
		
		$sql="INSERT INTO articulo (codigo, costo, barcode, credito_taller, descripcion, fmsi, idcategoria, idproveedor, marca, mayoreo, pasillo, publico, stock, stock_ideal, taller, unidades, estado, idsucursal, imagen, dibujo_tecnico, bandera_inventariable) VALUES ('$codigo', '$costo', '$barcode', '$credito_taller', '$descripcion', '$fmsi', $idcategoria, '$idproveedor', '$marca', '$mayoreo', '$pasillo', '$publico', '$stock', '$stock_ideal', '$taller', '$unidades', '1', '$idsucursal', '$imagen', '$dibujo', '$bandera_inventariable')";		
		return ejecutarConsulta($sql);
		//return $marca;
	}		

	public function guardarPedido($clave, $marca, $cantidad, $fecha, $estadoPedido, $notas, $fecha_registro, $idsucursalProducto, $idsucursal) {
		$sql = "INSERT INTO pedidos (clave, marca, cantidad, idsucursalProducto, idsucursal, fecha_pedido, fecha_registro, estadoPedido, notas, status) VALUES 
									('$clave', '$marca', '$cantidad', '$idsucursalProducto', '$idsucursal', '$fecha', '$fecha_registro', '$estadoPedido', '$notas', '1')";		
		return ejecutarConsulta($sql);
	}
		
	public function editar($idarticulo,$codigo,$costo, $publico, $taller, $credito_taller, $mayoreo, $barcode, $descripcion,$fmsi,$idcategoria, $idproveedor, $marca, $pasillo, $stock, $unidades, $imagen, $dibujo, $stock_ideal, $bandera_inventariable){

		if ($publico == '' && $taller == '' && $credito_taller == '' && $mayoreo == '') {			
			$sql = "SELECT * FROM marca WHERE idMarca='$marca'";
			$data = ejecutarConsulta($sql);
			while($fila=$data->fetch_array(MYSQLI_ASSOC)){
				$publico = ((($fila["utilidad_1"] / 100) * $costo) + $costo);
				$taller = ((($fila["utilidad_2"] / 100) * $costo) + $costo);
				$credito_taller = ((($fila["utilidad_3"] / 100) * $costo) + $costo);
				$mayoreo = ((($fila["utilidad_4"] / 100) * $costo) + $costo);
			}	
		}		

		$sql="UPDATE articulo SET codigo='$codigo', costo='$costo', barcode='$barcode', credito_taller='$credito_taller', descripcion='$descripcion', fmsi='$fmsi', idcategoria='$idcategoria', idproveedor='$idproveedor', marca='$marca', mayoreo='$mayoreo', pasillo='$pasillo', publico='$publico', stock='$stock', stock_ideal='$stock_ideal', taller='$taller', unidades='$unidades', imagen='$imagen', dibujo_tecnico='$dibujo', bandera_inventariable='$bandera_inventariable'
		WHERE idarticulo='$idarticulo'";
		usleep(140000);
		return ejecutarConsulta($sql);
	}
	public function desactivar($idarticulo){
		$sql="UPDATE articulo SET estado='0' WHERE idarticulo='$idarticulo'";
		usleep(140000);
		return ejecutarConsulta($sql);
	}
	public function activar($idarticulo){
		$sql="UPDATE articulo SET estado='1' WHERE idarticulo='$idarticulo'";
		usleep(140000);
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($idarticulo){
		$sql="SELECT *, a.descripcion AS descripcionArticulo FROM articulo a
		/*INNER JOIN marca m
		ON a.marca = m.idmarca*/
		WHERE a.idarticulo='$idarticulo'";
		usleep(140000);
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT *, m.descripcion AS descripcionMarca FROM articulo a INNER JOIN marca m ON a.marca = m.idmarca WHERE estado='1' ORDER BY stock DESC LIMIT 100;";
		return ejecutarConsulta($sql);
	}

	//listar registros activos
	public function listarActivos(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria, a.idproveedor, c.nombre as proveedor, a.codigo, a.fmsi, a.pasillo, a.marca, a.costo, a.publico, a.taller, a.credito_taller, a.mayoreo,a.stock,a.descripcion,a.estado, idsucursal FROM articulo AS a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria INNER JOIN persona p ON a.idproveedor=p.idpersona WHERE a.estado='1'";
		return ejecutarConsulta($sql);
	}

	//implementar un metodo para listar los activos, su ultimo precio y el stock(vamos a unir con el ultimo registro de la tabla detalle_ingreso)
	public function listarActivosVenta(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo AND estado='1' ORDER BY iddetalle_ingreso DESC LIMIT 0,1) AS precio_venta,a.descripcion,a.imagen,a.estado FROM articulo a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return ejecutarConsulta($sql);
	}

	public function listarActivosServicio(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre,a.stock,(SELECT precio_servicio FROM detalle_ingreso WHERE idarticulo=a.idarticulo AND estado='1' ORDER BY iddetalle_ingreso DESC LIMIT 0,1) AS precio_servicio,a.descripcion,a.imagen,a.estado FROM articulo a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return ejecutarConsulta($sql);
	}

	public function totalArticulos() {
		$sql = "SELECT COUNT(*) AS totalArticulos FROM articulo";
		return ejecutarConsulta($sql);
	}

	public function articulosPagination($limit, $limit2, $busqueda, $busqueda2, $idsucursal) {

		if ($busqueda == '' && $busqueda2 == '') {			
			$sql = "SELECT c.nombre AS nombreCategoria, m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
				a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
				a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
				FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
				INNER JOIN marca m ON a.marca = m.idmarca	
				WHERE a.idsucursal = '$idsucursal'
				LIMIT 50	
				";
				return ejecutarConsulta($sql);		
		}		

		if ($busqueda != '' && $busqueda2 == '') {
			$sql = "SELECT c.nombre AS nombreCategoria, m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
			a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
			a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
			FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
			INNER JOIN marca m ON a.marca = m.idmarca
			WHERE
			(a.codigo LIKE '%$busqueda%' OR
			c.nombre LIKE '%$busqueda%' OR
			a.fmsi LIKE '%$busqueda%' OR
			m.descripcion LIKE '%$busqueda%' /*OR
			a.descripcion LIKE '%$busqueda%'*/)
			AND a.estado = 1
			AND a.idsucursal = '$idsucursal'
			ORDER BY (a.stock > 0) DESC, (FIELD (marca,'2', a.stock > 0)) DESC LIMIT 500";
			return ejecutarConsulta($sql);
		} else
		
		if ($busqueda != '' && $busqueda2 != '') {					
			$sql = "SELECT * FROM (SELECT c.nombre AS nombreCategoria, m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
					a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
					a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
					FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
					INNER JOIN marca m ON a.marca = m.idmarca
					WHERE
					(a.codigo LIKE '%$busqueda%' OR
					a.fmsi LIKE '%$busqueda%' OR
					c.nombre LIKE '%$busqueda%' OR
					m.descripcion LIKE '%$busqueda%' /*OR
					a.descripcion LIKE '%$busqueda%'*/)
					AND a.estado = 1
					AND a.idsucursal = '$idsucursal'
					ORDER BY (a.stock > 0) DESC, (FIELD (marca,'2', a.stock > 0)) DESC LIMIT 500
					) AS tabla1
					UNION ALL
					SELECT * FROM (SELECT cat.nombre AS nombreCategoria, mar.descripcion AS descripcionMarca, cat.nombre, ar.codigo, ar.fmsi, ar.idarticulo, ar.idcategoria, ar.descripcion, ar.estado,
					ar.marca, ar.publico, ar.taller, ar.credito_taller, ar.mayoreo, ar.costo, ar.idproveedor, ar.stock_ideal,
					ar.pasillo, ar.unidades, ar.barcode, ar.fecha_ingreso, ar.ventas, ar.idsucursal, ar.stock
					FROM articulo ar INNER JOIN categoria cat ON ar.idcategoria=cat.idcategoria
					INNER JOIN marca mar ON ar.marca = mar.idmarca
					WHERE
					(ar.codigo LIKE '%$busqueda2%' OR
					ar.fmsi LIKE '%$busqueda2%' OR
					cat.nombre LIKE '%$busqueda2%' OR
					mar.descripcion LIKE '%$busqueda2%' /*OR
					ar.descripcion LIKE '%$busqueda2%'*/)
					AND ar.estado = 1
					AND ar.idsucursal = '$idsucursal'
					ORDER BY (ar.stock > 0) DESC, (FIELD (marca,'2' , ar.stock > 0 )) DESC LIMIT 500
					) AS tabla2;";
					return ejecutarConsulta($sql);
		}
	}

	public function filtroArticulosCopy($busqueda) {
		$sql = "SELECT c.nombre AS categoria, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
		a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor,
		a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
		FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
		WHERE a.codigo LIKE '%$busqueda%' OR
		a.fmsi LIKE '%$busqueda%' OR
		a.marca LIKE '%$busqueda%' OR
		a.descripcion LIKE '%$busqueda%'
		ORDER BY a.stock DESC";
		return ejecutarConsulta($sql);
	}

	public function listarArticulos($idsucursal) {
		$sql = "SELECT * FROM articulo WHERE idsucursal='$idsucursal'";
		return ejecutarConsulta($sql);
	}

	public function actualizarPrecios($clave) {				
		$sw = true;		
		foreach ($clave as $key => $value) {			
			$sql = "UPDATE articulo 
					SET costo='$value[costo]', publico='$value[publico]', taller='$value[taller]', credito_taller='$value[credito]', mayoreo='$value[mayoreo]'
					WHERE codigo='$value[clave]'";
			ejecutarConsulta($sql) or $sw=false;
		}
		return $sw;
	}

	public function registrarProductos($object, $idsucursal) {
		$sw = true;
		foreach ($object as $key => $value) {
			$sql = "INSERT INTO articulo
							(idcategoria, codigo, descripcion, fmsi, marca, publico, taller, credito_taller,mayoreo, costo, idproveedor, pasillo, unidades, idsucursal)
							VALUES('$value[categoria]', '$value[clave]', '$value[descripcion]', '$value[fmsi]', 
							'$value[marca]','$value[publico]', '$value[taller]', '$value[credito]', '$value[mayoreo]', 
							'$value[costo]', '$value[idproveedor]', '$value[pasillo]', '$value[unidad]', 
							'$idsucursal')";
			ejecutarConsulta($sql) or $sw=false;			
		}
		return $sw;
	}	

	public function buscaMarcaPorId($id) {
		$sql = "SELECT * FROM marca WHERE idmarca = '$id'";
		return ejecutarConsultaSimpleFila($sql);
	}

}

 ?>
