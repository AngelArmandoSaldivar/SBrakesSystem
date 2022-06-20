<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Gasto{

//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar registro
public function insertar($descripcion, $cantidad, $total_gasto, $metodo_pago, $informacion_adicional, $idsucursal, $fecha_hora){
	$sql="INSERT INTO gastos (descripcion,cantidad,total_gasto, metodo_pago, informacion_adicional, estado, idsucursal, fecha_hora) VALUES ('$descripcion','$cantidad','$total_gasto','$metodo_pago','$informacion_adicional', '1', '$idsucursal', '$fecha_hora')";
	return ejecutarConsulta($sql);
}

public function editar($idgasto,$descripcion, $cantidad, $total_gasto, $metodo_pago, $informacion_adicional, $fecha_hora){
	$sql="UPDATE gastos SET descripcion='$descripcion', cantidad='$cantidad', total_gasto='$total_gasto', metodo_pago='$metodo_pago', informacion_adicional='$informacion_adicional', fecha_hora='$fecha_hora'
	WHERE idgasto='$idgasto'";
	return ejecutarConsulta($sql);
}
public function desactivar($idgasto){
	$sql="UPDATE gastos SET estado='0' WHERE idgasto='$idgasto'";
	return ejecutarConsulta($sql);
}
public function activar($idgasto){
	$sql="UPDATE gastos SET estado='1' WHERE idgasto='$idgasto'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idgasto){
	$sql="SELECT descripcion, cantidad, total_gasto, metodo_pago, informacion_adicional, DATE(fecha_hora) as fecha, idgasto FROM gastos WHERE idgasto='$idgasto'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM gastos";
	return ejecutarConsulta($sql);
}

}

 ?>
