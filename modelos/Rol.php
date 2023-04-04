<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Rol{

	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$descripcion){
	$sql="INSERT INTO rol (nombre,descripcion,condicion) VALUES ('$nombre','$descripcion','1')";
	return ejecutarConsulta($sql);
}

public function editar($idrol,$nombre,$descripcion){
	$sql="UPDATE rol SET nombre='$nombre',descripcion='$descripcion' 
	WHERE idrol='$idrol'";
	return ejecutarConsulta($sql);
}
public function desactivar($idrol){
	$sql="UPDATE rol SET condicion='0' WHERE idrol='$idrol'";
	return ejecutarConsulta($sql);
}
public function activar($idrol){
	$sql="UPDATE rol SET condicion='1' WHERE idrol='$idrol'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idrol){
	$sql="SELECT * FROM rol WHERE idrol='$idrol'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM rol";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM rol WHERE condicion=1";
	return ejecutarConsulta($sql);
}
}

 ?>
