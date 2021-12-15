<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Ingreso{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($idproveedor,$idusuario,$tipo_comprobante,$serie_comprobante,$fecha_hora,$impuesto,$total_compra,$idarticulo,$clave,$fmsi,$descripcion,$cantidad,$precio_compra, $idsucursal){
	$sql="INSERT INTO ingreso (idproveedor,idusuario,tipo_comprobante,serie_comprobante,fecha_hora,impuesto,total_compra,tipoMov,estado, idsucursal) VALUES ('$idproveedor','$idusuario','$tipo_comprobante','$serie_comprobante','$fecha_hora','$impuesto','$total_compra','RECEPCIÓN','Aceptado', '$idsucursal')";
	//return ejecutarConsulta($sql);
	 $idingresonew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($idarticulo)) {

	 	$sql_detalle="INSERT INTO detalle_ingreso (idingreso,idproveedor,idusuario,serie_comprobante,tipo_comprobante,idarticulo,clave,fmsi,descripcion,cantidad,precio_compra,tipoMov) VALUES('$idingresonew','$idproveedor','$idusuario','$serie_comprobante','$tipo_comprobante','$idarticulo[$num_elementos]','$clave[$num_elementos]','$fmsi[$num_elementos]','$descripcion[$num_elementos]','$cantidad[$num_elementos]','$precio_compra[$num_elementos]','RECEPCIÓN')";		
	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
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

		$stateIngreso = "UPDATE ingreso SET estado='Anulado' WHERE idingreso='$idingreso'";
		ejecutarConsulta($stateIngreso);

		$stateDetalle = "UPDATE detalle_ingreso SET estado='1' WHERE idingreso='$idingreso'";
		ejecutarConsulta($stateDetalle);

	 return $sw;
}


//metodo para mostrar registros
public function mostrar($idingreso){
	$sql="SELECT i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE idingreso='$idingreso'";
	return ejecutarConsultaSimpleFila($sql);
}

public function listarDetalle($idingreso){
	$sql="SELECT di.idingreso,di.idarticulo,a.codigo,di.precio_compra, di.cantidad FROM detalle_ingreso di INNER JOIN articulo a ON di.idarticulo=a.idarticulo WHERE di.idingreso='$idingreso'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar(){
	$sql="SELECT i.idsucursal, i.idingreso,DATE(i.fecha_hora) as fecha,i.idproveedor,p.nombre as proveedor,u.idusuario,u.nombre as usuario, i.tipo_comprobante,i.serie_comprobante,i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario ORDER BY i.idingreso DESC";
	return ejecutarConsulta($sql);
}

}

 ?>
