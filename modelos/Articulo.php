<?php 
require "../config/Conexion.php";

class Articulo{	

	//implementamos nuestro constructor
	public function __construct(){

	}
	//metodo insertar registro
	public function insertar($codigo, $costo, $barcode, $credito_taller, $descripcion, $fmsi, $idcategoria, $idproveedor,$marca, $mayoreo, $pasillo, $publico, $stock, $taller, $unidades, $idsucursal){		
		$sql="INSERT INTO articulo (codigo, costo, barcode, credito_taller, descripcion, fmsi, idcategoria, idproveedor, marca, mayoreo, pasillo, publico, stock, taller, unidades, estado, idsucursal) VALUES ('$codigo', '$costo', '$barcode', '$credito_taller', '$descripcion', '$fmsi', '$idcategoria', '$idproveedor', '$marca', '$mayoreo', '$pasillo', '$publico', '$stock', '$taller', '$unidades', '1', '$idsucursal')";
		return ejecutarConsulta($sql);
	}

	public function editar($idarticulo,$codigo,$costo, $barcode, $credito_taller,$descripcion,$fmsi,$idcategoria, $idproveedor, $marca,$mayoreo,$pasillo,$publico,$stock,$taller,$unidades){
		$sql="UPDATE articulo SET codigo='$codigo', costo='$costo', barcode='$barcode', credito_taller='$credito_taller', descripcion='$descripcion', fmsi='$fmsi', idcategoria='$idcategoria', idproveedor='$idproveedor', marca='$marca', mayoreo='$mayoreo', pasillo='$pasillo', publico='$publico', stock='$stock', taller='$taller', unidades='$unidades'
		WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}
	public function desactivar($idarticulo){	
		$sql="UPDATE articulo SET estado='0' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}
	public function activar($idarticulo){
		$sql="UPDATE articulo SET estado='1' WHERE idarticulo='$idarticulo'";
		return ejecutarConsulta($sql);
	}

	//metodo para mostrar registros
	public function mostrar($idarticulo){
		$sql="SELECT * FROM articulo WHERE idarticulo='$idarticulo'";
		return ejecutarConsultaSimpleFila($sql);
	}

	//listar registros 
	public function listar(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria, a.idproveedor, c.nombre as proveedor, a.codigo, a.fmsi, a.pasillo, a.marca, a.costo, a.publico, a.taller, a.credito_taller, a.mayoreo,a.stock,a.descripcion,a.estado, idsucursal FROM articulo AS a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria INNER JOIN persona p ON a.idproveedor=p.idpersona WHERE a.estado='1'";
		return ejecutarConsulta($sql);
	}

	//listar registros activos
	public function listarActivos(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria, a.idproveedor, c.nombre as proveedor, a.codigo, a.fmsi, a.pasillo, a.marca, a.costo, a.publico, a.taller, a.credito_taller, a.mayoreo,a.stock,a.descripcion,a.estado, idsucursal FROM articulo AS a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria INNER JOIN persona p ON a.idproveedor=p.idpersona WHERE a.estado='1'";
		return ejecutarConsulta($sql);
	}

	//implementar un metodo para listar los activos, su ultimo precio y el stock(vamos a unir con el ultimo registro de la tabla detalle_ingreso)
	public function listarActivosVenta(){
		$sql="SELECT a.idarticulo,a.idcategoria,c.nombre as categoria,a.codigo, a.nombre,a.stock,(SELECT precio_venta FROM detalle_ingreso WHERE idarticulo=a.idarticulo ORDER BY iddetalle_ingreso DESC LIMIT 0,1) AS precio_venta,a.descripcion,a.imagen,a.estado FROM articulo a INNER JOIN Categoria c ON a.idcategoria=c.idcategoria WHERE a.condicion='1'";
		return ejecutarConsulta($sql);
	}

}

 ?>
