<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Servicios{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$fecha_hora,$impuesto,$total_servicio,$marca, $modelo, $ano, $color, $kms,$placas,$idarticulo,$clave,$fmsi,$descripcion,$cantidad,$precio_servicio,$descuento, $idsucursal, $forma_pago, $forma_pago2, $forma_pago3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3){
	$sql="INSERT INTO servicio (idcliente,idusuario,tipo_comprobante,fecha_hora,impuesto,total_servicio,pagado,marca, modelo, ano, color, kms, placas, estado,idsucursal,status) 
						VALUES ('$idcliente','$idusuario','$tipo_comprobante','$fecha_hora','$impuesto','$total_servicio','$total_servicio','$marca', '$modelo', '$ano', '$color', '$kms','$placas', 'NORMAL', '$idsucursal', 'NORMAL')";	
	 $idservicionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {
	 	$sql_detalle="INSERT INTO detalle_servicio (idservicio,idarticulo,clave,fmsi,descripcion,tipoMov,cantidad,precio_servicio,descuento) VALUES('$idservicionew','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','SERVICIO','$cantidad[$num_elementos]','$precio_servicio[$num_elementos]','$descuento[$num_elementos]')";
	 	ejecutarConsulta($sql_detalle) or $sw=false;

		 $sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado) VALUES ('$fecha_hora', $idservicionew, '$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idcliente', '$cantidad[$num_elementos]', '$precio_servicio[$num_elementos]', 'SERVICIO', 'ACTIVO')";
		 ejecutarConsulta($sql_kardex) or $sw=false;
	 	$num_elementos=$num_elementos+1;
	 }

	 $sql_formas_pago = "INSERT INTO formas_pago (forma_pago, forma_pago2, forma_pago3, banco, banco2, banco3, importe, importe2, importe3, referencia, referencia2, referencia3, idservicio, fecha_hora, idsucursal) VALUES('$forma_pago', '$forma_pago2', '$forma_pago3', '$banco', '$banco2', '$banco3', '$importe', '$importe2', '$importe3', '$ref', '$ref2', '$ref3', '$idservicionew', '$fecha_hora', '$idsucursal')";
	 ejecutarConsulta($sql_formas_pago) or $sw=false;
	 
	 sleep(1);
	 return $sw;
}

public function cobrarServicio($forma_pago, $forma_pago2, $forma_pago3, $banco, $banco2, $banco3, $importe, $importe2, $importe3, $ref, $ref2, $ref3, $idservicio){
	$sql = "UPDATE servicio SET status='PAGADO', pagado=0 WHERE idservicio='$idservicio'";	
	ejecutarConsulta($sql);
	$sw=true;
	$sql_formas_pago = "UPDATE formas_pago SET forma_pago='$forma_pago', forma_pago2='$forma_pago2', forma_pago3='$forma_pago3', banco='$banco', banco2='$banco2', banco3='$banco3', importe='$importe', importe2='$importe2', importe3='$importe3', referencia='$ref', referencia2='$ref2', referencia3='$ref3' WHERE idservicio='$idservicio'";
	ejecutarConsulta($sql_formas_pago) or $sw=false;
	sleep(1);
	return $sw;
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
	$sql="SELECT v.idservicio,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,p.rfc, p.direccion, p.email, p.telefono, p.tipo_precio, p.credito, u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.estado,v.marca, v.modelo, v.ano, v.kms, v.color, v.placas, forma_pago,forma_pago2, forma_pago3,banco,banco2, banco3,importe, importe2, importe3,referencia, referencia2, referencia3 FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN formas_pago fp ON fp.idservicio=v.idservicio WHERE v.idservicio='$idservicio'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

public function mostrarInfoAuto($idauto){
	$sql="SELECT * FROM autos WHERE idauto='$idauto'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

//Lista los articulos de la venta
public function listarDetalle($idservicio){
	$sql="SELECT dv.idservicio,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_servicio,dv.descuento,(dv.cantidad*dv.precio_servicio-dv.descuento) as subtotal FROM detalle_servicio dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idsucursal,v.idservicio,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_servicio,v.impuesto,v.marca, v.modelo, v.ano, v.estado FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idservicio DESC";
	return ejecutarConsulta($sql);
}


public function serviciocabecera($idservicio){
	$sql= "SELECT v.idservicio, v.idcliente, p.nombre AS cliente, p.direccion,p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_servicio, v.marca, v.modelo, v.color, v.kms, v.placas FROM servicio v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idservicio='$idservicio'";
	return ejecutarConsulta($sql);
}

public function serviciodetalles($idservicio){
	$sql="SELECT a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi, d.descripcion, d.precio_servicio, d.descuento, d.clave, (d.cantidad*d.precio_servicio-d.descuento) AS subtotal FROM detalle_servicio d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idservicio='$idservicio'";
    	return ejecutarConsulta($sql);
}

public function ultimoServicio() {
	$sql = "SELECT * FROM servicio ORDER BY idservicio DESC limit 1";
	return ejecutarConsulta($sql);
}


}

 ?>
