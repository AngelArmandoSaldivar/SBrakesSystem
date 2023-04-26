<?php 
require "../config/Conexion.php";

class Articulo{	

	//implementamos nuestro constructor
	public function __construct(){

	}

	function consultaMarcaPorId($idMarca) {
		
	}

	//metodo insertar registro
	public function insertar($codigo, $costo, $barcode, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $pasillo,$stock, $unidades, $idsucursal, $imagen, $dibujo, $stock_ideal, $bandera_inventariable){						
				
		$sql = "SELECT * FROM marca WHERE idMarca='$marca'";
		$data = ejecutarConsulta($sql);
		$publico = 0.0;
		$taller = 0.0;
		$credito_taller = 0.0;
		$mayoreo = 0.0;
		while($fila=$data->fetch_array(MYSQLI_ASSOC)){
			$publico = ((($fila["utilidad_1"] / 100) * $costo) + $costo);
			$taller = ((($fila["utilidad_2"] / 100) * $costo) + $costo);
			$credito_taller = ((($fila["utilidad_3"] / 100) * $costo) + $costo);
			$mayoreo = ((($fila["utilidad_4"] / 100) * $costo) + $costo);
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
		
	public function editar($idarticulo,$codigo,$costo, $barcode, $descripcion,$fmsi,$idcategoria, $idproveedor, $marca, $pasillo, $stock, $unidades, $imagen, $dibujo, $stock_ideal, $bandera_inventariable){

		$sql = "SELECT * FROM marca WHERE idMarca='$marca'";
		$data = ejecutarConsulta($sql);
		$publico = 0.0;
		$taller = 0.0;
		$credito_taller = 0.0;
		$mayoreo = 0.0;
		while($fila=$data->fetch_array(MYSQLI_ASSOC)){
			$publico = ((($fila["utilidad_1"] / 100) * $costo) + $costo);
			$taller = ((($fila["utilidad_2"] / 100) * $costo) + $costo);
			$credito_taller = ((($fila["utilidad_3"] / 100) * $costo) + $costo);
			$mayoreo = ((($fila["utilidad_4"] / 100) * $costo) + $costo);
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
		$sql="SELECT * FROM articulo a
		INNER JOIN marca m
		ON a.marca = m.idmarca
		WHERE idarticulo='$idarticulo'";
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

	public function articulosPagination($limit, $limit2, $busqueda) {
		$sql = "SELECT m.descripcion AS descripcionMarca, c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
		a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
		a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
		FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
		INNER JOIN marca m ON a.marca = m.idmarca
		WHERE 
		(a.codigo LIKE '%$busqueda%' OR
		a.fmsi LIKE '%$busqueda%' OR
		m.descripcion LIKE '%$busqueda%' OR
		a.descripcion LIKE '%$busqueda%')
		AND estado = 1
		ORDER BY a.stock > 0 DESC, a.marca ASC LIMIT $limit OFFSET $limit2";
		//usleep(90000);
		return ejecutarConsulta($sql);
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

	public function listarArticulos() {
		$sql = "SELECT * FROM articulo";
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

	public function registrarProductos($object, $fecha_ingreso, $idsucursal) {
		$sw = true;
		foreach ($object as $key => $value) {
			$sql = "INSERT INTO articulo
							(categoria, idcategoria, codigo, descripcion, fmsi, marca, publico, taller, credito_taller,mayoreo, costo, idproveedor, pasillo, unidades, fecha_ingreso, idsucursal)
							VALUES('$value[categoria]', 21,  '$value[clave]', '$value[descripcion]', '$value[fmsi]', 
							'$value[marca]','$value[publico]', '$value[taller]', '$value[credito]', '$value[mayoreo]', 
							'$value[costo]', '$value[idproveedor]', '$value[pasillo]', '$value[unidad]', 
							'$fecha_ingreso', '$idsucursal')";
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
