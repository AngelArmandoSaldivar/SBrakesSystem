<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Servicios{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_entrada,$fecha_salida,$remision,$impuesto,$total_servicio,$marca, $modelo, $ano, $color, $kms,$placas,$idarticulo,$clave,$marcaArticulo,$fmsi,$descripcion,$cantidad,$precio_servicio,$descuento, $idsucursal, $idsucursalproducto){
	$total_servicio = substr($total_servicio, 1);
	$caracter = ",";
	$posicion = strpos($total_servicio, $caracter);

	$extraer1 = $posicion > 0 ? substr($total_servicio, 0, $posicion) : $total_servicio;
	$extraer2 = $posicion > 0 ? substr($total_servicio, $posicion + 1, 50) : "";
	$nuevo_total = $extraer2 != "" ? intval($extraer1.$extraer2) : $total_servicio;
	$sql="INSERT INTO servicio (idcliente,idusuario,tipo_comprobante,fecha_entrada,fecha_salida, is_remision, impuesto,total_servicio,pagado,marca, modelo, ano, color, kms, placas, estado,idsucursal,status) 
						VALUES ('$idcliente','$idusuario','$tipo_comprobante','$fecha_entrada','$fecha_salida','$remision', '$impuesto','$nuevo_total','0','$marca', '$modelo', '$ano', '$color', '$kms','$placas', 'PENDIENTE', '$idsucursal', 'PENDIENTE')";	
	 $idservicionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {
	 	$sql_detalle="INSERT INTO detalle_servicio (idservicio,idarticulo,codigo,fmsi,descripcion,tipoMov,cantidad,precio_servicio,descuento, marca) VALUES('$idservicionew','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','SERVICIO','$cantidad[$num_elementos]','$precio_servicio[$num_elementos]','$descuento[$num_elementos]', '$marcaArticulo[$num_elementos]')";
	 	ejecutarConsulta($sql_detalle) or $sw=false;

		 $sql_kardex = "INSERT INTO kardex (fecha_entrada, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idsucursalArticulo, idsucursalVenta) VALUES ('$fecha_entrada', $idservicionew, '$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idcliente', '$cantidad[$num_elementos]', '$precio_servicio[$num_elementos]', 'SERVICIO', 'ACTIVO', '$idsucursalproducto[$num_elementos]', '$idsucursal')";
		 ejecutarConsulta($sql_kardex) or $sw=false;
	 	$num_elementos=$num_elementos+1;
	 }	
	 sleep(1);
	 return $sw;
}

public function guardarGarantia($idservicio, $idarticulo, $descripcion, $cantidad, $idsucursal, $fecha_hora, $precioGarantia) {
	$sql = "INSERT INTO garantias (idservicio, idarticulo, idsucursal, descripcion, cantidad, tipo_mov, fecha_hora, precio_garantia)
						VALUES ('$idservicio', '$idarticulo', '$idsucursal', '$descripcion', '$cantidad', 'SERVICIO', '$fecha_hora', '$precioGarantia')";
	ejecutarConsulta($sql);
	usleep(80000);
	$sw = true;
	$sqlupdate = "UPDATE detalle_servicio SET descripcion='$descripcion'
			WHERE idarticulo='$idarticulo' AND idservicio='$idservicio'";
			ejecutarConsulta($sqlupdate);
	usleep(80000);
	return $sw;
}

public function maxRemision() {
	$sql = "SELECT MAX(remision) as maxRemision from servicio";
	return ejecutarConsulta($sql);
}

public function editarRemision($idservicio, $remision) {
	$sql = "UPDATE servicio SET remision='$remision', is_remision='1' WHERE idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

public function editarFechaSalida($idservicio, $fecha_salida) {
	$sql = "UPDATE servicio SET fecha_salida='$fecha_salida' WHERE idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

public function editarPago($idpago, $metodoPago, $banco, $importeCobro, $referenciaCobro, $importeviejo, $idservicio) {	
	$sw = true;
	$sql = "UPDATE servicio SET pagado=pagado - $importeviejo WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql);
	usleep(80000);

	$sql_fp = "UPDATE formas_pago SET forma_pago='$metodoPago', banco='$banco', importe='$importeCobro', referencia='$referenciaCobro' WHERE idpago='$idpago'";
	ejecutarConsulta($sql_fp);

	$sql_servicio = "UPDATE servicio SET pagado=pagado + $importeCobro WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql_servicio);
	
	usleep(80000);
	return $sw;
}

public function listarDetallesCobro($idservicio) {
	$sql = "SELECT * FROM formas_pago WHERE idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

public function mostrarPagoEdit($idpago) {
	$sql="SELECT * FROM formas_pago WHERE idpago='$idpago'";
	usleep(80000);
	return ejecutarConsultaSimpleFila($sql);
}

public function eliminarCobro($idcobro, $importe, $idservicio) {
	$sw = true;
	$sql = "DELETE FROM formas_pago WHERE idpago='$idcobro'";
	ejecutarConsulta($sql);
	$sql_servicio = "UPDATE servicio SET pagado=pagado - '$importe' WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql_servicio);
	return $sw;
}

public function guardarCobro($metodoPago, $banco, $importeCobro, $referenciaCobro, $idservicio, $fechaCobro, $idsucursal, $idcliente) {
	$sw = true;
	$sql = "UPDATE servicio SET pagado=pagado + '$importeCobro' WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql);

	$sql_cobro = "INSERT INTO formas_pago (forma_pago, banco, importe, referencia, idservicio, fecha_hora, idsucursal, idcliente) VALUES ('$metodoPago', '$banco', '$importeCobro', '$referenciaCobro', '$idservicio', '$fechaCobro', '$idsucursal', '$idcliente')";
	ejecutarConsulta($sql_cobro);
	return $sw;
}

public function addProductoServicio($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idServicio, $fecha, $idcliente, $idsucursalArticulo, $idsucursal) {
	$bandera = true;
	$sql = "INSERT INTO detalle_servicio 
			(idservicio,idarticulo,codigo,fmsi,marca, descripcion,tipoMov,cantidad,precio_servicio,descuento) 
			VALUES('$idServicio','$idarticulo', '$articulo','$fmsi','$marca','$descripcion','SERVICIO','$stock','$publico','0')";			
	ejecutarConsulta($sql);
	$sql_servicio = "UPDATE servicio SET total_servicio=total_servicio+'$publico' WHERE idservicio='$idServicio'";
	ejecutarConsulta($sql_servicio);
	$sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idarticulo, idventa, idsucursalArticulo, idsucursalVenta) 
								VALUES ('$fecha', '$idServicio', '$articulo', '$fmsi', '$idcliente', '$stock', '$publico', 'SERVICIO','ACTIVO', '$idarticulo', '$idServicio', '$idsucursalArticulo', '$idsucursal')";
	ejecutarConsulta($sql_kardex);
	sleep(1);
	return $bandera;
}

public function mostrarProductoEdit($idarticulo, $idservicio) {
	$sql = "SELECT * FROM detalle_servicio WHERE idservicio='$idservicio' AND idarticulo='$idarticulo' AND estado='0'";
	return ejecutarConsultaSimpleFila($sql);
}

public function editarGuardarProductoServicio($p1, $p2, $p3, $idarticulo, $idservicio, $precioViejo, $stockViejo) {
	$bandera = true;
	$sql_servicio = "UPDATE servicio SET total_servicio=total_servicio-($precioViejo*$stockViejo) WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql_servicio);
	$sql_articulo = "UPDATE articulo SET stock=stock+$stockViejo WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articulo);
	sleep(1);

	$sql = "UPDATE detalle_servicio SET descripcion='$p1', cantidad='$p2', precio_servicio='$p3' WHERE idservicio='$idservicio' AND idarticulo='$idarticulo' AND estado='0'";
	ejecutarConsulta($sql);	

	$sql_kardex = "UPDATE kardex SET cantidad='$p2', importe='$p3' WHERE idventa='$idservicio' AND idarticulo='$idarticulo' AND estado='ACTIVO'";
	ejecutarConsulta($sql_kardex);

	$sql_servicioTotalNew = "UPDATE servicio SET total_servicio = total_servicio + ($p2 * $p3) WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql_servicioTotalNew);

	$sql_articuloStockNew = "UPDATE articulo SET stock=stock-$p2 WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articuloStockNew);
	sleep(1);
	return $bandera;
}

public function eliminarProductoServicio($p1, $p2, $p3, $p4) {
	$sql = "UPDATE detalle_servicio SET estado='1' WHERE idservicio='$p1' AND idarticulo='$p2'";
	$sw = true;
	ejecutarConsulta($sql);

	$sql_producto = "UPDATE articulo SET stock=stock+'$p3' WHERE idarticulo='$p2'";
	ejecutarConsulta($sql_producto);

	$sql_kardex = "UPDATE kardex SET estado='ANULADO' WHERE idventa='$p1' AND idarticulo='$p2'";
	ejecutarConsulta($sql_kardex);

	$sql_servicio = "UPDATE servicio SET total_servicio=total_servicio-($p3*$p4) WHERE idservicio='$p1'";
	ejecutarConsulta($sql_servicio);

	return $sw;
}

public function actualizarKilometraje($idcliente, $idauto, $kmAuto) {
	$sql = "UPDATE autos SET kms='$kmAuto' WHERE idcliente='$idcliente' AND idauto='$idauto'";
	sleep(1);
	return ejecutarConsulta($sql);
}

public function anular($idservicio){
	$num_elementos=0;
	 $sw=true;
		
		$sqlIdArticulo = "SELECT idarticulo, idservicio,cantidad FROM detalle_servicio WHERE idservicio='$idservicio'";
		$sqlIdArticulo2 = ejecutarConsulta($sqlIdArticulo);

		while ($reg=$sqlIdArticulo2->fetch_object()) {
			//SELECT OLD STOCK ARTICULO
			$sqlOldStock = "SELECT * FROM articulo WHERE idarticulo='$reg->idarticulo'";
			$sqlOldStock2 = ejecutarConsulta($sqlOldStock);
			while($reg2 = $sqlOldStock2->fetch_object()) {
				$sum = $reg->cantidad + $reg2->stock;
				//echo "PRODUCTO: ".$reg2->codigo. " NEW STOCK: ".$sum."<br>";
				//UPDATE NEW STOCK ARTICULO
				$sql_update_articulo = "UPDATE articulo SET stock='$sum' WHERE idarticulo='$reg->idarticulo'";
				ejecutarConsulta($sql_update_articulo);
			}			
		}

		$stateSell = "UPDATE servicio SET status='ANULADO' WHERE idservicio='$idservicio'";
		ejecutarConsulta($stateSell);

		$stateDetalle = "UPDATE detalle_servicio SET estado='1' WHERE idservicio='$idservicio'";
		ejecutarConsulta($stateDetalle);

		$stateDetalle = "UPDATE kardex SET estado='ANULADO' WHERE folio='$idservicio'";
		ejecutarConsulta($stateDetalle);
		sleep(1);
	 return $sw;
}

//implementar un metodopara mostrar los datos de unregistro a modificar
public function mostrar($idservicio){
	$sql="SELECT v.remision, v.is_remision, DATE(v.fecha_salida) as fecha_salida, v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,p.rfc, p.direccion, p.email, p.telefono, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio, v.pagado, v.impuesto,v.estado,v.marca, v.modelo, v.ano, v.kms, v.color, v.placas FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idservicio='$idservicio'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

public function mostrarInfoAuto($idauto){
	$sql="SELECT * FROM autos WHERE idauto='$idauto'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

//Lista los articulos de la servicio
public function listarDetalle($idservicio){
	$sql="SELECT dv.idservicio,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_servicio,dv.descuento,(dv.cantidad*dv.precio_servicio-dv.descuento) as subtotal, dv.marca FROM detalle_servicio dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idservicio='$idservicio' AND dv.estado='0'";
	return ejecutarConsulta($sql);
}

public function listarDetalleTodo($idservicio){
	$sql="SELECT dv.idservicio,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_servicio,dv.descuento,(dv.cantidad*dv.precio_servicio-dv.descuento) as subtotal FROM detalle_servicio dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idsucursal,v.idservicio,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.marca, v.modelo, v.ano, v.estado FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idservicio DESC";
	return ejecutarConsulta($sql);
}


public function serviciocabecera($idservicio){
	$sql= "SELECT v.idservicio, v.idcliente, p.nombre AS cliente, p.direccion,p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, DATE(v.fecha_entrada) AS fecha, v.impuesto, v.total_servicio, v.marca, v.modelo, v.color, v.kms, v.placas FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

public function serviciodetalles($idservicio){
	$sql="SELECT d.idservicio, a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi, d.descripcion, d.precio_servicio, d.descuento, d.codigo, (d.cantidad*d.precio_servicio-d.descuento) AS subtotal FROM detalle_servicio d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idservicio='$idservicio' AND d.estado=0";
    	return ejecutarConsulta($sql);
}

public function serviciodetallesHistorial($idservicio) {
	$sql="SELECT a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi, d.descripcion, d.precio_servicio, d.descuento, d.codigo, (d.cantidad*d.precio_servicio-d.descuento) AS subtotal FROM detalle_servicio d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idservicio='$idservicio' AND d.estado=0";
	return ejecutarConsulta($sql);
}

public function ultimoServicio() {
	$sql = "SELECT * FROM servicio ORDER BY idservicio DESC limit 1";
	return ejecutarConsulta($sql);
}

public function filtroPaginado($limit, $limit2, $busqueda, $fecha_inicio, $fecha_fin) {

	$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";	

	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin == "") {		
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE  tipo_comprobante LIKE '%$busqueda%' OR v.idservicio LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
		
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin == "") {		
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_entrada) >= '$fecha_inicio' AND  tipo_comprobante LIKE '%$busqueda%' OR v.idservicio LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin == "") {
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_entrada) >= '$fecha_inicio' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda == "" && $fecha_inicio == "" && $fecha_fin != "") {
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_entrada) <= '$fecha_fin' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin != "") {
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_entrada) <= '$fecha_fin' AND  tipo_comprobante LIKE '%$busqueda%' OR v.idservicio LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin != "") {				
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_entrada) <= '$fecha_fin' AND DATE(v.fecha_entrada) <= '$fecha_fin' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin != "") {		
		$sql = "SELECT DATE(v.fecha_entrada) as fecha_entrada,v.remision,v.is_remision,DATE(v.fecha_salida) as fecha_salida,v.pagado,v.status,v.idsucursal,v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.modelo, v.marca, v.ano FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_entrada) <= '$fecha_fin' AND DATE(v.fecha_entrada) <= '$fecha_fin' AND tipo_comprobante LIKE '%$busqueda%' OR v.idservicio LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idservicio DESC LIMIT $limit OFFSET $limit2";
	}
	usleep(80000);
	return ejecutarConsulta($sql);
}

public function guardarAuto($idcliente, $placas, $marca, $modelo, $ano, $color, $kms) {
	$sql_auto="INSERT INTO autos (placas, marca, modelo, ano, color, kms, idcliente) VALUES('$placas', '$marca', '$modelo', '$ano', '$color', '$kms', '$idcliente')";
	return ejecutarConsulta($sql_auto);
}

public function ultimoAuto() {
	$sql = "SELECT * FROM autos ORDER BY idauto DESC limit 1";
	return ejecutarConsulta($sql);
}

public function historialServicios($idcliente) {
	$sql = "SELECT v.remision, v.is_remision, DATE(v.fecha_salida) as fecha_salida, v.idservicio,DATE(v.fecha_entrada) as fecha,v.idcliente,p.nombre as cliente,p.rfc, p.direccion, p.email, p.telefono, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio, v.pagado, v.impuesto,v.estado,v.marca, v.modelo, v.ano, v.kms, v.color, v.placas FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcliente = '$idcliente'"; 
	return ejecutarConsulta($sql);
}

public function editarDetalleServicio($idservicio, $idcliente, $fecha_entrada, $fecha_salida, $is_rem, $remision) {	
	echo $idservicio;
	$sql = "UPDATE servicio SET idcliente='$idcliente', fecha_entrada='$fecha_entrada', fecha_salida='$fecha_salida', 
				is_remision='$is_rem', remision='$remision' WHERE idservicio='$idservicio'";
	usleep(200000);
	return ejecutarConsulta($sql);
}

public function reporteServicios($fecha_inicio, $fecha_fin) {
	$sql = "SELECT v.status, v.idsucursal, v.remision, v.idservicio, v.total_servicio, v.modelo, v.marca, v.placas, DATE(v.fecha_entrada) AS fecha_entrada, DATE(v.fecha_salida) AS fecha_salida, p.nombre 
			FROM servicio v 
			INNER JOIN persona p ON v.idcliente = p.idpersona 						
			WHERE DATE(fecha_entrada) >= '$fecha_inicio' 
			AND DATE(fecha_entrada) <= '$fecha_fin'
			AND v.status != 'ANULADO' ORDER BY v.idservicio DESC";
	return ejecutarConsulta($sql);
}

}

 ?>
