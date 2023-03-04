<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$clave,$fmsi,$descripcion,$cantidad,$precio_compra, $idsucursal, $idsucursalArticulo, $descuento){	
	$total_compra = substr($total_compra, 1);
	$caracter = ",";
	$posicion = strpos($total_compra, $caracter);

	$extraer1 = $posicion > 0 ? substr($total_compra, 0, $posicion) : $total_compra;
	$extraer2 = $posicion > 0 ? substr($total_compra, $posicion + 1, 50) : "";
	$nuevo_total = $extraer2 != "" ? intval($extraer1.$extraer2) : $total_compra;


	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,fecha_hora,impuesto,total_compra,tipoMov,estado, idsucursal) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$fecha_hora','$impuesto','$nuevo_total','RECEPCIÓN','NORMAL', '$idsucursal')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idproveedor,idusuario,serie_comprobante,tipo_comprobante,idarticulo,clave,fmsi,descripcion,cantidad,precio_compra,tipoMov, descuento) VALUES('$idingresonew','$idproveedor','$idusuario','$serie_comprobante','$tipo_comprobante','$idarticulo[$num_elementos]','$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','RECEPCIÓN', '$descuento[$num_elementos]')";		
	 	ejecutarConsulta($sql_detalle) or $sw=false;

		$sql_kardex = "INSERT INTO kardex (fecha_entrada, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idventa, idarticulo, idsucursalArticulo, idsucursalVenta) VALUES ('$fecha_hora', '$serie_comprobante', '$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idproveedor', '$cantidad[$num_elementos]', '$precio_compra[$num_elementos]', 'RECEPCION','ACTIVO', '$idingresonew', '$idarticulo[$num_elementos]', '$idsucursalArticulo[$num_elementos]', '$idsucursal')";
		ejecutarConsulta($sql_kardex) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }	 
	 return $sw;
}

public function addProductoIngreso($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idIngreso, $idproveedor, $datetime, $folio, $idsucursal, $idarticuloSucursal) {
	$bandera = true;	
	$sql = "INSERT INTO detalle_ingreso
			(idingreso,idarticulo,clave,fmsi,marca, descripcion,tipoMov,cantidad,precio_compra,descuento) 
			VALUES('$idIngreso','$idarticulo', '$articulo','$fmsi','$marca','$descripcion','RECEPCIÓN','$stock','$publico','0')";
	ejecutarConsulta($sql) or $bandera=false;
	
	$sql_ingreso = "UPDATE ingreso SET total_compra=total_compra+'$publico' WHERE idingreso='$idIngreso'";
	ejecutarConsulta($sql_ingreso) or $bandera=false;
	
	$sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idarticulo, idventa, idsucursalArticulo, idsucursalVenta) VALUES ('$datetime', '$folio', '$articulo', '$fmsi', '$idproveedor', '$stock', '$publico', 'RECEPCION','ACTIVO', '$idarticulo', '$idIngreso', '$idarticuloSucursal', '$idsucursal')";
		 ejecutarConsulta($sql_kardex) or $bandera=false;
	
	return $bandera;
}

public function editarGuardarProductoIngreso($p1, $p2, $p3, $idarticulo, $idingreso, $precioViejo, $stockViejo) {
	$bandera = true;
	$sql_ingreso = "UPDATE ingreso SET total_compra=total_compra-($precioViejo*$stockViejo) WHERE idingreso='$idingreso'";
	ejecutarConsulta($sql_ingreso) or $bandera=false;
	$sql_articulo = "UPDATE articulo SET stock=stock-$stockViejo WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articulo) or $bandera=false;	

	$sql = "UPDATE detalle_ingreso SET descripcion='$p1', cantidad='$p2', precio_compra='$p3' WHERE idingreso='$idingreso' AND idarticulo='$idarticulo'";
	ejecutarConsulta($sql) or $bandera=false;

	$sql_kardex = "UPDATE kardex SET cantidad='$p2', importe='$p3' WHERE idventa='$idingreso' AND idarticulo='$idarticulo'";
	ejecutarConsulta($sql_kardex) or $bandera=false;

	$sql_ventaTotalNew = "UPDATE ingreso SET total_compra = total_compra + ($p2 * $p3) WHERE idingreso='$idingreso'";
	ejecutarConsulta($sql_ventaTotalNew) or $bandera=false;

	$sql_articuloStockNew = "UPDATE articulo SET stock=stock+$p2 WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articuloStockNew) or $bandera=false;	
	return $bandera;
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
	 return $sw;
}

public function eliminarProductoIngreso($p1, $p2, $p3, $p4) {
	$sql = "UPDATE detalle_ingreso SET estado='1' WHERE idingreso='$p1' AND idarticulo='$p2'";
	$sw = true;
	ejecutarConsulta($sql);

	$sql_producto = "UPDATE articulo SET stock=stock-'$p3' WHERE idarticulo='$p2'";
	ejecutarConsulta($sql_producto);

	$sql_kardex = "UPDATE kardex SET estado='ANULADO' WHERE idventa='$p1' AND idarticulo='$p2'";
	ejecutarConsulta($sql_kardex);

	$sql_ingreo = "UPDATE ingreso SET total_compra=total_compra-($p3*$p4) WHERE idingreso='$p1'";
	ejecutarConsulta($sql_ingreo);

	return $sw;
}


//metodo para mostrar registros
public function mostrar($idingreso){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE idingreso='$idingreso'";	
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idingreso){
	$sql="SELECT di.descuento, di.idingreso,di.estado,di.idarticulo,di.clave,di.fmsi, di.descripcion,(di.cantidad*di.precio_compra) as subtotal, a.codigo,di.precio_compra, di.cantidad FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso' AND di.estado='0'";	
	return ejecutarConsulta($sql);
}

public function mostrarProductoEdit($idarticulo, $idingreso) {
	$sql = "SELECT * FROM detalle_ingreso WHERE idingreso='$idingreso' AND idarticulo='$idarticulo'";
	return ejecutarConsultaSimpleFila($sql);
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

public function filtroPaginado($limit, $limit2, $busqueda, $fecha_inicio, $fecha_fin) {
	
	$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";

	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin == "") {
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin == "") {		
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) >= '$fecha_inicio' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";	
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin == "") {		
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) >= '$fecha_inicio' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda == "" && $fecha_inicio == "" && $fecha_fin != "") {		
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) <= '$fecha_fin' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin != "") {			
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) <= '$fecha_fin' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin != "") {		
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin != "") {
		$sql = "SELECT i.idsucursal, i.estado, i.tipo_comprobante, i.total_compra, i.idingreso, DATE(i.fecha_hora) AS fecha, i.idproveedor, u.idusuario, p.nombre as proveedor, u.nombre as usuario FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE i.estado = 'NORMAL' AND DATE(i.fecha_hora) >= '$fecha_inicio' AND DATE(i.fecha_hora) <= '$fecha_fin' AND p.nombre LIKE '%$busqueda%' OR i.idingreso LIKE '%$busqueda%' OR i.estado LIKE '%$busqueda%' ORDER BY idingreso DESC LIMIT $limit OFFSET $limit2";				
	}
	usleep(80000);
	return ejecutarConsulta($sql);
}

}

 ?>
