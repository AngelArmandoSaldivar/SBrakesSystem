<?php 
require "../config/Conexion.php";

class Articulo{	

	//implementamos nuestro constructor
	public function __construct(){

	}
	//metodo insertar registro
	public function insertar($codigo, $costo, $barcode, $credito_taller, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $mayoreo, $pasillo, $publico, $stock, $taller, $unidades, $idsucursal, $imagen){		
		$sql="INSERT INTO articulo (codigo, costo, barcode, credito_taller, descripcion, fmsi, idcategoria, categoria, idproveedor, marca, mayoreo, pasillo, publico, stock, taller, unidades, estado, idsucursal, imagen) VALUES ('$codigo', '$costo', '$barcode', '$credito_taller', '$descripcion', '$fmsi', 21, '$idcategoria', '$idproveedor', '$marca', '$mayoreo', '$pasillo', '$publico', '$stock', '$taller', '$unidades', '1', '$idsucursal', '$imagen')";
		usleep(140000);
		return ejecutarConsulta($sql);
	}

	public function guardarPedido($clave, $marca, $cantidad, $fecha, $estadoPedido, $notas, $fecha_registro, $idsucursalProducto, $idsucursal) {
		$sql = "INSERT INTO pedidos (clave, marca, cantidad, idsucursalProducto, idsucursal, fecha_pedido, fecha_registro, estadoPedido, notas, status) VALUES 
									('$clave', '$marca', '$cantidad', '$idsucursalProducto', '$idsucursal', '$fecha', '$fecha_registro', '$estadoPedido', '$notas', '1')";
		usleep(140000);
		return ejecutarConsulta($sql);
	}
		
	public function editar($idarticulo,$codigo,$costo, $barcode, $credito_taller,$descripcion,$fmsi,$idcategoria, $idproveedor, $marca,$mayoreo,$pasillo,$publico,$stock,$taller,$unidades, $imagen){
		$sql="UPDATE articulo SET codigo='$codigo', costo='$costo', barcode='$barcode', credito_taller='$credito_taller', descripcion='$descripcion', fmsi='$fmsi', idcategoria='$idcategoria', idproveedor='$idproveedor', marca='$marca', mayoreo='$mayoreo', pasillo='$pasillo', publico='$publico', stock='$stock', taller='$taller', unidades='$unidades', imagen='$imagen'
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
		$sql="SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
		usleep(140000);
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros
	public function listar(){
		$sql="SELECT * FROM articulo WHERE estado='1' ORDER BY stock DESC LIMIT 100";
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
		$sql = "SELECT c.nombre, a.codigo, a.fmsi, a.idarticulo, a.idcategoria, a.descripcion, a.estado,
		a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor, a.stock_ideal,
		a.pasillo, a.unidades, a.barcode, a.fecha_ingreso, a.ventas, a.idsucursal, a.stock
		FROM articulo a INNER JOIN categoria c ON a.idcategoria=c.idcategoria
		WHERE a.codigo LIKE '%$busqueda%' OR
		a.fmsi LIKE '%$busqueda%' OR
		a.marca LIKE '%$busqueda%' OR
		a.descripcion LIKE '%$busqueda%'
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
		//usleep(90000);
		return ejecutarConsulta($sql);
	}

	public function listarArticulos() {
		$sql = "SELECT * FROM articulo";
		return ejecutarConsulta($sql);
	}

	public function actualizarPrecios($clave) {				
		$sw = true;
		/*while ($index < count($clave)) {
			echo $clave[$index];
			ejecutarConsulta($sql) or $sw=false;
			$index=$index+1;
		}*/
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
			/*$categoria = $value["categoria"];
			$clave = $value["clave"];
			$descripcion = $value["descripcion"];
			$fmsi = $value["fmsi"];
			$marca = $value["marca"];
			$publico = $value["publico"];
			$taller = $value["taller"];
			$credito = $value["credito"];
			$idproveedor = $value["idproveedor"];
			/*$sql = "INSERT INTO articulo
							(categoria, idcategoria, codigo, idproveedor, idsucursal)
							VALUES('$categoria', 21, '$clave', '$idproveedor',$idsucursal)";
			ejecutarConsulta($sql) or $sw=false;*/
		}
		return $sw;
	}

}
//B22-131614

 ?>
