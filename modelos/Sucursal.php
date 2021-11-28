<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Sucursal{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre, $telefono, $direccion, $lat, $lng){
	$sql="INSERT INTO sucursal (nombre, telefono, direccion, lat, lng, condicion) VALUES ('$nombre', '$telefono', '$direccion', '$lat', '$lng', '1')";
	//return ejecutarConsulta($sql);
	 $idsucursalnew=ejecutarConsulta_retornarID($sql);	 
	 $sw=true;	 
	 return $sw;
}

public function editar($idsucursal,$nombre,$telefono, $direccion, $lat, $lng){
	$sql="UPDATE sucursal SET nombre='$nombre', telefono='$telefono',direccion='$direccion', lat='$lat',lng='$lng'
	WHERE idsucursal='$idsucursal'";
	 ejecutarConsulta($sql);
     $sw=true;
	 return $sw;
}
public function desactivar($idsucursal){
	$sql="UPDATE sucursal SET condicion='0' WHERE idsucursal='$idsucursal'";
	return ejecutarConsulta($sql);
}
public function activar($idsucursal){
	$sql="UPDATE sucursal SET condicion='1' WHERE idsucursal='$idsucursal'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idsucursal){
	$sql="SELECT * FROM sucursal WHERE idsucursal='$idsucursal'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM sucursal";
	return ejecutarConsulta($sql);
}

}

 ?>
