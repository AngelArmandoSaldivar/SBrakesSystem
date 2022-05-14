<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Venta{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_venta,$idarticulo,$clave,$fmsi,$marca,$descripcion,$cantidad,$precio_venta,$descuento,$idsucursal){
	$sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,fecha_hora,impuesto,total_venta,pagado,estado,idsucursal,status) VALUES ('$idcliente','$idusuario','$tipo_comprobante','$fecha_hora','$impuesto','$total_venta','0', 'PENDIENTE', '$idsucursal', 'PENDIENTE')";
	 $idventanew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {
	 	$sql_detalle="INSERT INTO detalle_venta (idventa,idarticulo,clave,fmsi,marca, descripcion,tipoMov,cantidad,precio_venta,descuento) VALUES('$idventanew','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$marca[$num_elementos]','$descripcion[$num_elementos]','VENTA','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
	 	ejecutarConsulta($sql_detalle) or $sw=false;
		$sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idventa, idarticulo) VALUES ('$fecha_hora', $idventanew,'$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idcliente', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', 'VENTA', 'ACTIVO', '$idventanew', '$idarticulo[$num_elementos]')";
		ejecutarConsulta($sql_kardex) or $sw=false;
	 	$num_elementos=$num_elementos+1;
	 }	 
	 return $sw;
}

public function eliminarCobro($idcobro, $importe, $idventa) {
	$sw = true;
	$sql = "DELETE FROM formas_pago WHERE idpago='$idcobro'";
	ejecutarConsulta($sql);
	$sql_venta = "UPDATE venta SET pagado=pagado - '$importe' WHERE idventa='$idventa'";
	ejecutarConsulta($sql_venta);
	return $sw;
}

public function guardarCobro($metodoPago, $banco, $importeCobro, $referenciaCobro, $idventa, $fechaCobro, $idsucursal, $idcliente) {
	$sw = true;
	$sql = "UPDATE venta SET pagado=pagado + '$importeCobro' WHERE idventa='$idventa'";
	ejecutarConsulta($sql);

	$sql_cobro = "INSERT INTO formas_pago (forma_pago, banco, importe, referencia, idventa, fecha_hora, idsucursal, idcliente) VALUES ('$metodoPago', '$banco', '$importeCobro', '$referenciaCobro', '$idventa', '$fechaCobro', '$idsucursal', '$idcliente')";
	ejecutarConsulta($sql_cobro);
	return $sw;
}

public function addProductoVenta($idarticulo,$articulo,$fmsi,$marca,$descripcion,$publico,$stock,$idVenta, $fecha, $idcliente) {
	$bandera = true;
	$sql = "INSERT INTO detalle_venta 
			(idventa,idarticulo,clave,fmsi,marca, descripcion,tipoMov,cantidad,precio_venta,descuento) 
			VALUES('$idVenta','$idarticulo', '$articulo','$fmsi','$marca','$descripcion','VENTA','$stock','$publico','0')";			
	ejecutarConsulta($sql);
	$sql_venta = "UPDATE venta SET total_venta=total_venta+'$publico' WHERE idventa='$idVenta'";
	ejecutarConsulta($sql_venta);
	$sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado, idarticulo, idventa) 
								VALUES ('$fecha', '$idVenta', '$articulo', '$fmsi', '$idcliente', '$stock', '$publico', 'VENTA','ACTIVO', '$idarticulo', '$idVenta')";
	ejecutarConsulta($sql_kardex);
	sleep(1);
	return $bandera;
}

public function mostrarProductoEdit($idarticulo, $idventa) {
	$sql = "SELECT * FROM detalle_venta WHERE idventa='$idventa' AND idarticulo='$idarticulo'";
	return ejecutarConsultaSimpleFila($sql);
}

public function editarGuardarProductoVenta($p1, $p2, $p3, $idarticulo, $idventa, $precioViejo, $stockViejo) {
	$bandera = true;
	$sql_venta = "UPDATE venta SET total_venta=total_venta-($precioViejo*$stockViejo) WHERE idventa='$idventa'";
	ejecutarConsulta($sql_venta);
	$sql_articulo = "UPDATE articulo SET stock=stock+$stockViejo WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articulo);
	sleep(1);

	$sql = "UPDATE detalle_venta SET descripcion='$p1', cantidad='$p2', precio_venta='$p3' WHERE idventa='$idventa' AND idarticulo='$idarticulo'";
	ejecutarConsulta($sql);	

	$sql_kardex = "UPDATE kardex SET cantidad='$p2', importe='$p3' WHERE idventa='$idventa' AND idarticulo='$idarticulo'";
	ejecutarConsulta($sql_kardex);

	$sql_ventaTotalNew = "UPDATE venta SET total_venta = total_venta + ($p2 * $p3) WHERE idventa='$idventa'";
	ejecutarConsulta($sql_ventaTotalNew);

	$sql_articuloStockNew = "UPDATE articulo SET stock=stock-$p2 WHERE idarticulo='$idarticulo'";
	ejecutarConsulta($sql_articuloStockNew);
	sleep(1);
	return $bandera;
}

public function eliminarProductoVenta($p1, $p2, $p3, $p4) {
	$sql = "UPDATE detalle_venta SET estado='1' WHERE idventa='$p1' AND idarticulo='$p2'";
	$sw = true;
	ejecutarConsulta($sql);

	$sql_producto = "UPDATE articulo SET stock=stock+'$p3' WHERE idarticulo='$p2'";
	ejecutarConsulta($sql_producto);

	$sql_kardex = "UPDATE kardex SET estado='ANULADO' WHERE idventa='$p1' AND idarticulo='$p2'";
	ejecutarConsulta($sql_kardex);

	$sql_venta = "UPDATE venta SET total_venta=total_venta-($p3*$p4) WHERE idventa='$p1'";
	ejecutarConsulta($sql_venta);

	return $sw;
}

public function anular($idventa){
	$num_elementos=0;
	 $sw=true;
		
		$sqlIdArticulo = "SELECT idarticulo, idventa,cantidad FROM detalle_venta WHERE idventa='$idventa'";
		$sqlIdArticulo2 = ejecutarConsulta($sqlIdArticulo);

		while ($reg=$sqlIdArticulo2->fetch_object()) {
			//SELECT OLD STOCK ARTICULO
			$sqlOldStock = "SELECT * FROM articulo WHERE idarticulo='$reg->idarticulo'";
			$sqlOldStock2 = ejecutarConsulta($sqlOldStock);
			while($reg2 = $sqlOldStock2->fetch_object()) {
				$sum = $reg->cantidad + $reg2->stock;
				// echo "PRODUCTO: ".$reg2->codigo. " NEW STOCK: ".$sum."<br>";
				//UPDATE NEW STOCK ARTICULO
				$sql_update_articulo = "UPDATE articulo SET stock='$sum' WHERE idarticulo='$reg->idarticulo'";
				ejecutarConsulta($sql_update_articulo);
			}
		}

		$stateSell = "UPDATE venta SET estado='ANULADO' WHERE idventa='$idventa'";
		ejecutarConsulta($stateSell);

		$stateDetalle = "UPDATE detalle_venta SET estado='1' WHERE idventa='$idventa'";
		ejecutarConsulta($stateDetalle);

		$stateDetalle = "UPDATE kardex SET estado='ANULADO' WHERE folio='$idventa'";
		ejecutarConsulta($stateDetalle);
		sleep(1);
	 return $sw;
}

public function mostrarInfoClient($idcliente){
	$sql="SELECT * FROM persona WHERE tipo_persona='Cliente' AND idpersona='$idcliente'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetallesCobro($idventa) {
	$sql = "SELECT * FROM formas_pago WHERE idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function mostrarPagoEdit($idpago) {
	$sql="SELECT * FROM formas_pago WHERE idpago='$idpago'";
	usleep(80000);
	return ejecutarConsultaSimpleFila($sql);
}

//implementar un metodopara mostrar los datos de unregistro a modificar
public function mostrar($idventa){
	$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente, p.rfc, p.direccion, p.email, p.telefono, p.telefono_local, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario,v.tipo_comprobante,v.total_venta,v.impuesto,v.estado, v.pagado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
	usleep(80000);
	return ejecutarConsultaSimpleFila($sql);
}

public function editarPago($idpago, $metodoPago, $banco, $importeCobro, $referenciaCobro, $importeviejo, $idventa) {	
	$sw = true;
	$sql = "UPDATE venta SET pagado=pagado - $importeviejo WHERE idventa='$idventa'";
	ejecutarConsulta($sql);
	usleep(80000);

	$sql_fp = "UPDATE formas_pago SET forma_pago='$metodoPago', banco='$banco', importe='$importeCobro', referencia='$referenciaCobro' WHERE idpago='$idpago'";
	ejecutarConsulta($sql_fp);

	$sql_venta = "UPDATE venta SET pagado=pagado + $importeCobro WHERE idventa='$idventa'";
	ejecutarConsulta($sql_venta);
	
	usleep(80000);
	return $sw;
}

public function editar($idventa,$idarticulo,$clave,$fmsi,$marca,$descripcion,$cantidad,$precio_venta,$descuento){
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_venta (idventa,idarticulo,clave,fmsi,marca, descripcion,tipoMov,cantidad,precio_venta,descuento) VALUES('$idventa','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$marca[$num_elementos]','$descripcion[$num_elementos]','VENTA','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";
	 	$queryEditar = ejecutarConsulta($sql_detalle) or $sw=false;

		 while ($reg=$queryEditar->fetch_object()) {
			echo "CLAVE".$reg->clave."</br>";
		 }
		// $sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado) VALUES ('$fecha_hora', $idventa,'$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idcliente', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', 'VENTA', 'ACTIVO')";
		// ejecutarConsulta($sql_kardex) or $sw=false;
	 	$num_elementos=$num_elementos+1;

	 }	 
	sleep(1);
	return $sw;
}

public function mostrarDetalleVenta($idventa) {
	$sql = "SELECT dv.idventa,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
	return ejecutarConsultaFila($sql);
}

//Lista los articulos de la venta
public function listarDetalle($idventa){
	$sql="SELECT dv.idventa,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.estado, dv.descripcion,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa' AND dv.estado='0'";
	return ejecutarConsulta($sql);
}
public function listarDetallesTodo($idventa) {
	$sql="SELECT dv.idventa,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.estado, dv.descripcion,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
	return ejecutarConsulta($sql);
}

public function ventacabecera($idventa){
	$sql= "SELECT v.idventa, v.idcliente, p.nombre AS cliente, p.direccion, p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function ventadetalles($idventa){
	$sql="SELECT a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi,d.marca, d.descripcion, d.precio_venta, d.descuento, d.clave, (d.cantidad*d.precio_venta-d.descuento) AS subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa' AND d.estado = 0";
    	return ejecutarConsulta($sql);
}

public function ultimaVenta() {
	$sql = "SELECT * FROM venta ORDER BY idventa DESC limit 1";
	return ejecutarConsulta($sql);
}
public function ultimoCliente($nombre) {
	$sql = "SELECT * FROM persona WHERE tipo_persona='Cliente' AND nombre = '$nombre' ORDER BY idpersona DESC limit 1";
	return ejecutarConsulta($sql);
}

public function totalVentas() {
	$sql = "SELECT COUNT(*) as totalVentas FROM venta WHERE estado != 'ANULADO'";
	return ejecutarConsulta($sql);
}

public function filtroPaginado($limit, $limit2, $busqueda, $fecha_inicio, $fecha_fin) {

	$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";	

	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin == "") {
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE tipo_comprobante LIKE '%$busqueda%' OR v.idventa LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin == "") {
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND tipo_comprobante LIKE '%$busqueda%' OR v.idventa LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin == "") {		
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio == "" && $fecha_fin != "") {				
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) <= '$fecha_fin' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin != "") {					
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) <= '$fecha_fin' AND tipo_comprobante LIKE '%$busqueda%' OR v.idventa LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin != "") {		
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin != "") {
		$sql = "SELECT v.pagado,v.status,v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND DATE(v.fecha_hora) <= '$fecha_fin' AND tipo_comprobante LIKE '%$busqueda%' OR v.idventa LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR u.nombre LIKE '%$busqueda%' ORDER BY v.idventa DESC LIMIT $limit OFFSET $limit2";
	}
	usleep(80000);
	return ejecutarConsulta($sql);
}

}

 ?>
