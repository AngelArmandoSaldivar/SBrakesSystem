<?php 
require "../config/Conexion.php";

class Articulo{	

	//implementamos nuestro constructor
	public function __construct(){

	}
	//metodo insertar registro
	public function insertar($codigo, $costo, $barcode, $credito_taller, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $mayoreo, $pasillo, $publico, $stock, $taller, $unidades, $idsucursal, $imagen){		
		$sql="INSERT INTO articulo (codigo, costo, barcode, credito_taller, descripcion, fmsi, idcategoria, idproveedor, marca, mayoreo, pasillo, publico, stock, taller, unidades, estado, idsucursal, imagen) VALUES ('$codigo', '$costo', '$barcode', '$credito_taller', '$descripcion', '$fmsi', '$idcategoria', '$idproveedor', '$marca', '$mayoreo', '$pasillo', '$publico', '$stock', '$taller', '$unidades', '1', '$idsucursal', '$imagen')";
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
		a.marca, a.publico, a.taller, a.credito_taller, a.mayoreo, a.costo, a.idproveedor,
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

}
//B22-131614

 ?>
