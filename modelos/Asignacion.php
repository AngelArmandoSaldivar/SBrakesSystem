<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Asignacion{

	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($rol,$idmodulo){
	$sql="INSERT INTO rel_rol_modulo (idrol,idmodulo,condicion) VALUES ('$rol','$idmodulo','1')";
	return ejecutarConsulta($sql);
}

public function editar($idasignacion,$rol,$idmodulo){
	$sql="UPDATE rel_rol_modulo SET idrol='$rol',idmodulo='$idmodulo' 
	WHERE idrelrolmodulo='$idasignacion'";
	return ejecutarConsulta($sql);
}
public function desactivar($idasignacion){
	$sql="UPDATE rel_rol_modulo SET condicion='0' WHERE idrelrolmodulo='$idasignacion'";
	return ejecutarConsulta($sql);
}
public function activar($idrelrolmodulo){
	$sql="UPDATE rel_rol_modulo SET condicion='1' WHERE idrelrolmodulo='$idrelrolmodulo'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idrelrolmodulo){
	$sql="SELECT * FROM rel_rol_modulo WHERE idrelrolmodulo='$idrelrolmodulo'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT * FROM rel_rol_modulo";
	return ejecutarConsulta($sql);
}
//listar y mostrar en selct
public function select(){
	$sql="SELECT * FROM rel_rol_modulo WHERE condicion=1";
	return ejecutarConsulta($sql);
}

public function consultarPorRol($rol){
	$sql="SELECT * FROM rel_rol_modulo r INNER JOIN permiso s ON r.idmodulo = s.idpermiso WHERE idrol='$rol' AND r.condicion = 1";
	return ejecutarConsulta($sql);
}

}

 ?>
