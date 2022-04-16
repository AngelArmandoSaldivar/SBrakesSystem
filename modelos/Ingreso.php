<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$clave,$fmsi,$descripcion,$cantidad,$precio_compra, $idsucursal){
	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,fecha_hora,impuesto,total_compra,tipoMov,estado, idsucursal) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$fecha_hora','$impuesto','$total_compra','RECEPCIÓN','NORMAL', '$idsucursal')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idproveedor,idusuario,serie_comprobante,tipo_comprobante,idarticulo,clave,fmsi,descripcion,cantidad,precio_compra,tipoMov) VALUES('$idingresonew','$idproveedor','$idusuario','$serie_comprobante','$tipo_comprobante','$idarticulo[$num_elementos]','$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','RECEPCIÓN')";		
	 	ejecutarConsulta($sql_detalle) or $sw=false;

		 $sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado) VALUES ('$fecha_hora', '$serie_comprobante', '$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idproveedor', '$cantidad[$num_elementos]', '$precio_compra[$num_elementos]', 'RECEPCION','ACTIVO')";
		 ejecutarConsulta($sql_kardex) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 sleep(1);
	 return $sw;
}

public function anular($idingreso){
	$num_elementos=0;
	 $sw=true;
		
		$sqlIdArticulo = "SELECT idarticulo, idingreso,cantidad FROM detalle_ingreso WHERE idingreso='$idingreso'";
		$sqlIdArticulo2 = ejecutarConsulta($sqlIdArticulo);

		while ($reg=$sqlIdArticulo2->fetch_object()) {
			//SELECT OLD STOCK ARTICULO
			$sqlOldStock = "SELECT * FROM articulo WHERE idarticulo='$reg->idarticulo'";
			echo $reg->cantidad."<br>";
			$sqlOldStock2 = ejecutarConsulta($sqlOldStock);
			while($reg2 = $sqlOldStock2->fetch_object()) {						
				$restar = $reg2->stock - $reg->cantidad;
				// echo "PRODUCTO: ".$reg2->codigo. " NEW STOCK: ".$sum."<br>";
				//UPDATE NEW STOCK ARTICULO
				$sql_update_articulo = "UPDATE articulo SET stock='$restar' WHERE idarticulo='$reg->idarticulo'";
				ejecutarConsulta($sql_update_articulo);
			}
		}

		$stateIngreso = "UPDATE ingreso SET estado='ANULADO' WHERE idingreso='$idingreso'";
		ejecutarConsulta($stateIngreso);

		$stateDetalle = "UPDATE detalle_ingreso SET estado='1' WHERE idingreso='$idingreso'";
		ejecutarConsulta($stateDetalle);

		$stateDetalle = "UPDATE kardex SET estado='ANULADO' WHERE folio='$idingreso'";
		ejecutarConsulta($stateDetalle);
		sleep(1);
	 return $sw;
}


//metodo para mostrar registros
public function mostrar($idingreso){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE idingreso='$idingreso'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idingreso){
	$sql="SELECT di.idingreso,di.idarticulo,a.codigo,di.precio_compra, di.cantidad FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
	sleep(1);
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT i.idsucursal, i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
	return ejecutarConsulta($sql);
}
public function totalIngresos() {
	$sql = "SELECT COUNT(*) AS totalIngresos FROM ingreso WHERE estado='NORMAL'";
	return ejecutarConsulta($sql);
}
public function busquedaTexto($busqueda, $limite, $limit2) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' LIMIT $limite, $limit2";
	return ejecutarConsulta($sql);
}

public function textoFecha($fecha_inicio, $fecha_fin, $limite, $limit2, $busqueda) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' LIMIT $limite, $limit2";
	return ejecutarConsulta($sql);
}

public function ingresosPagination($fecha_inicio, $fecha_fin, $limite, $limit2, $busqueda) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' LIMIT $limite, $limit2";
	return ejecutarConsulta($sql);
}

public function filtroFechas($fecha_inicio, $fecha_fin, $limite, $limit2) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' AND DATE(i.fecha_hora)<='$fecha_fin' LIMIT $limite, $limit2";
	return ejecutarConsulta($sql);
}

public function filtroLimite($limite, $limit2) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario LIMIT $limite, $limit2";
	sleep(1);
	return ejecutarConsulta($sql);	
}
public function filtroFechaInicial($fecha_inicio, $limite, $limit2) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_hora)>='$fecha_inicio' LIMIT $limite, $limit2";
	return ejecutarConsulta($sql);
}
public function filtroPaginado($paginaAnterior, $paginaSiguiente) {
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, i.fecha_hora, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario LIMIT $paginaAnterior, $paginaSiguiente;";
	return ejecutarConsulta($sql);
}

}

 ?>
