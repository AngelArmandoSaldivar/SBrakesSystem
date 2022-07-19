<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Cotizacion{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_cotizacion,$marca, $modelo, $ano, $color, $kms,$placas,$idarticulo,$clave,$marcaArticulo,$fmsi,$descripcion,$cantidad,$precio_cotizacion,$descuento, $idsucursal, $idsucursalproducto){        

	$sql="INSERT INTO cotizacion (idcliente,idusuario,tipo_comprobante,fecha_hora, impuesto,total_cotizacion,marca, modelo, ano, color, kms, placas,idsucursal) 
						VALUES ('$idcliente','$idusuario','$tipo_comprobante','$fecha_hora', '$impuesto','$total_cotizacion','$marca', '$modelo', '$ano', '$color', '$kms','$placas', '$idsucursal')";	
	 $idservicionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;     
	 while ($num_elementos < count($idarticulo)) {        
	 	$sql_detalle="INSERT INTO detalle_cotizacion (idcotizacion,idarticulo,codigo,fmsi,descripcion,tipoMov,cantidad,precio_cotizacion,descuento, marca) 
                                            VALUES('$idservicionew','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','COTIZACION','$cantidad[$num_elementos]','$precio_cotizacion[$num_elementos]','$descuento[$num_elementos]', '$marcaArticulo[$num_elementos]')";
	 	ejecutarConsulta($sql_detalle) or $sw=false;
        $num_elementos=$num_elementos+1;
	 }
	 sleep(1);
	 return $sw;
}

public function maxRemision() {
	$sql = "SELECT MAX(remision) as maxRemision from servicio";
	return ejecutarConsulta($sql);
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
	$sql = "SELECT * FROM detalle_cotizacion WHERE idcotizacion='$idservicio' AND idarticulo='$idarticulo' AND estado='0'";
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

//implementar un metodopara mostrar los datos de unregistro a modificar
public function mostrar($idservicio){
	$sql="SELECT DATE(v.fecha_hora) as fecha_hora, v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,p.rfc, p.direccion, p.email, p.telefono, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion, v.impuesto,v.marca, v.modelo, v.ano, v.kms, v.color, v.placas FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcotizacion='$idservicio'";
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
	$sql="SELECT dv.idcotizacion,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_cotizacion,dv.descuento,(dv.cantidad*dv.precio_cotizacion-dv.descuento) as subtotal, dv.marca FROM detalle_cotizacion dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idcotizacion='$idservicio' AND dv.estado='0'";
	return ejecutarConsulta($sql);
}

public function listarDetalleTodo($idservicio){
	$sql="SELECT dv.idcotizacion,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_servicio,dv.descuento,(dv.cantidad*dv.precio_servicio-dv.descuento) as subtotal FROM detalle_servicio dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idcotizacion='$idservicio'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.marca, v.modelo, v.ano, v.estado FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idcotizacion DESC";
	return ejecutarConsulta($sql);
}


public function serviciocabecera($idservicio){
	$sql= "SELECT v.idcotizacion, v.idcliente, p.nombre AS cliente, p.direccion,p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_cotizacion, v.marca, v.modelo, v.color, v.kms, v.placas FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcotizacion='$idservicio'";
	return ejecutarConsulta($sql);
}

public function serviciodetalles($idservicio){
	$sql="SELECT d.idservicio, a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi, d.descripcion, d.precio_servicio, d.descuento, d.codigo, (d.cantidad*d.precio_servicio-d.descuento) AS subtotal FROM detalle_servicio d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idservicio='$idservicio' AND d.estado=0";
    	return ejecutarConsulta($sql);
}

public function ultimoServicio() {
	$sql = "SELECT * FROM servicio ORDER BY idservicio DESC limit 1";
	return ejecutarConsulta($sql);
}

public function filtroPaginado($limit, $limit2, $busqueda, $fecha_inicio, $fecha_fin) {

    $sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";	
	
    if($busqueda != "" && $fecha_inicio == "" && $fecha_fin == "") {
	$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE  tipo_comprobante LIKE '%$busqueda%' OR v.idcotizacion LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin == "") {		
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' AND  tipo_comprobante LIKE '%$busqueda%' OR v.idcotizacion LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin == "") {
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) >= '$fecha_inicio' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";		
	}
	if($busqueda == "" && $fecha_inicio == "" && $fecha_fin != "") {
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE DATE(v.fecha_hora) <= '$fecha_fin' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio == "" && $fecha_fin != "") {
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_hora) <= '$fecha_fin' AND  tipo_comprobante LIKE '%$busqueda%' OR v.idcotizacion LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda == "" && $fecha_inicio != "" && $fecha_fin != "") {				
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_hora) <= '$fecha_fin' AND DATE(v.fecha_hora) <= '$fecha_fin' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";
	}
	if($busqueda != "" && $fecha_inicio != "" && $fecha_fin != "") {		
		$sql = "SELECT DATE(v.fecha_hora) as fecha_hora,v.idsucursal,v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion,v.impuesto,v.modelo, v.marca, v.ano FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario  WHERE DATE(v.fecha_hora) <= '$fecha_fin' AND DATE(v.fecha_hora) <= '$fecha_fin' AND tipo_comprobante LIKE '%$busqueda%' OR v.idcotizacion LIKE '%$busqueda%' OR p.nombre LIKE '%$busqueda%' OR v.modelo LIKE '%$busqueda%' OR v.marca LIKE '%$busqueda%' OR v.ano LIKE '%$busqueda%' ORDER BY v.idcotizacion DESC LIMIT $limit OFFSET $limit2";
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

public function editarDetalleServicio($idservicio, $idcliente, $fecha_entrada, $fecha_salida, $is_rem, $remision) {	
	echo $idservicio;
	$sql = "UPDATE servicio SET idcliente='$idcliente', fecha_entrada='$fecha_entrada', fecha_salida='$fecha_salida', 
				is_remision='$is_rem', remision='$remision' WHERE idservicio='$idservicio'";
	usleep(200000);
	return ejecutarConsulta($sql);
}

public function mostrarCotizacion($busqueda){
	$sql="SELECT DATE(v.fecha_hora) as fecha_hora, v.idcotizacion,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,p.rfc, p.direccion, p.email, p.telefono, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_cotizacion, v.impuesto,v.marca, v.modelo, v.ano, v.kms, v.color, v.placas, v.idsucursal FROM cotizacion v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idcotizacion='$busqueda'";
	usleep(200000);
	return ejecutarConsultaSimpleFila($sql);
}

}

 ?>
