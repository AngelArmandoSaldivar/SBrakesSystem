<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Venta{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idcliente,$idusuario,$tipo_comprobante,$factura,$fecha_hora,$impuesto,$forma_pago,$total_venta,$idarticulo,$clave,$fmsi,$descripcion,$cantidad,$precio_venta,$descuento,$idsucursal){
	$sql="INSERT INTO venta (idcliente,idusuario,tipo_comprobante,factura,fecha_hora,impuesto,forma_pago,total_venta,pagado,estado,idsucursal,status) VALUES ('$idcliente','$idusuario','$tipo_comprobante','$factura','$fecha_hora','$impuesto','$forma_pago','$total_venta','$total_venta', 'NORMAL', '$idsucursal', 'NORMAL')";
	 $idventanew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_venta (idventa,idarticulo,clave,fmsi,descripcion,tipoMov,cantidad,precio_venta,descuento) VALUES('$idventanew','$idarticulo[$num_elementos]', '$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','VENTA','$cantidad[$num_elementos]','$precio_venta[$num_elementos]','$descuento[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

		 $sql_kardex = "INSERT INTO kardex (fecha_hora, folio, clave, fmsi, idcliente_proveedor, cantidad, importe, tipoMov, estado) VALUES ('$fecha_hora', $idventanew,'$clave[$num_elementos]', '$fmsi[$num_elementos]', '$idcliente', '$cantidad[$num_elementos]', '$precio_venta[$num_elementos]', 'VENTA', 'ACTIVO')";
		 ejecutarConsulta($sql_kardex) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 sleep(2);
	 return $sw;	 
}

public function cobrarVenta($idventa){
	$sql = "UPDATE venta SET estado='PAGADO', pagado=0 WHERE idventa='$idventa'";
	sleep(1);
	return ejecutarConsulta($sql);
}

// public function anular($idventa){
// 	$sql="UPDATE venta SET estado='Anulado' WHERE idventa='$idventa'";
// 	return ejecutarConsulta($sql);
// }

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


//implementar un metodopara mostrar los datos de unregistro a modificar
public function mostrar($idventa){
	$sql="SELECT v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.forma_pago,v.total_venta,v.impuesto,v.estado, v.factura FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE idventa='$idventa'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

//Lista los articulos de la venta
public function listarDetalle($idventa){
	$sql="SELECT dv.idventa,dv.idarticulo,a.codigo,dv.cantidad,dv.fmsi, dv.descripcion,dv.precio_venta,dv.descuento,(dv.cantidad*dv.precio_venta-dv.descuento) as subtotal FROM detalle_venta dv INNER JOIN articulo a ON dv.idarticulo=a.idarticulo WHERE dv.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT v.idsucursal,v.idventa,DATE(v.fecha_hora) as fecha,v.idcliente,p.nombre as cliente,u.idusuario,u.nombre as usuario, v.tipo_comprobante,v.total_venta,v.impuesto,v.estado FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario ORDER BY v.idventa DESC";
	return ejecutarConsulta($sql);
}


public function ventacabecera($idventa){
	$sql= "SELECT v.idventa, v.idcliente, p.nombre AS cliente, p.direccion, p.tipo_documento, p.num_documento, p.email, p.telefono, v.idusuario, u.nombre AS usuario, v.tipo_comprobante, DATE(v.fecha_hora) AS fecha, v.impuesto, v.total_venta FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario WHERE v.idventa='$idventa'";
	return ejecutarConsulta($sql);
}

public function ventadetalles($idventa){
	$sql="SELECT a.codigo AS articulo, a.codigo, d.cantidad,d.fmsi, d.descripcion, d.precio_venta, d.descuento, d.clave, (d.cantidad*d.precio_venta-d.descuento) AS subtotal FROM detalle_venta d INNER JOIN articulo a ON d.idarticulo=a.idarticulo WHERE d.idventa='$idventa'";
    	return ejecutarConsulta($sql);
}

}

 ?>
