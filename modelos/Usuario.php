<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clave,$idsucursal, $foto){	
	$sw=true;
	$sql="INSERT INTO usuario (nombre,direccion,telefono,email,cargo,acceso,login,clave,imagen, idsucursal, foto_perfil) 
						VALUES ('$nombre','$direccion','$telefono','$email','$cargo','$acceso','$login','$clave','1', '$idsucursal', '$foto')";	
	$idusuarionew=ejecutarConsulta_retornarID($sql);
	$sql_sucursales = "INSERT INTO sucursales_usuario (idusuario, idsucursal)
					VALUES ('$idusuarionew', '$idsucursal')";
	ejecutarConsulta($sql_sucursales);	
	return $sw;
}

public function editar($idusuario,$nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clave,$idsucursal, $foto){	
	$sql="UPDATE usuario SET nombre='$nombre',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',acceso='$acceso',login='$login',clave='$clave', idsucursal='$idsucursal', foto_perfil='$foto'
	WHERE idusuario='$idusuario'";
	ejecutarConsulta($sql);	
	$sw = true; 
	return $sw;
}
public function desactivar($idusuario){
	$sql="UPDATE usuario SET condicion='0' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
public function activar($idusuario){
	$sql="UPDATE usuario SET condicion='1' WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idusuario){
	$sql="SELECT * FROM usuario WHERE idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listar(){
	$sql="SELECT nombre, telefono, email, cargo,acceso, condicion, idusuario, login FROM usuario";
	return ejecutarConsulta($sql);
}

//metodo para listar permmisos marcados de un usuario especifico
public function listarmarcados($nivUsuario){
	$sql="SELECT * FROM permiso_usuario WHERE nivel_usuario='$nivUsuario'";
	return ejecutarConsulta($sql);
}
//funcion que verifica el acceso al sistema

public function verificar($login,$clave){
	$sql="SELECT r.nombre AS claveRol, s.lat,s.lng, u.idusuario,u.nombre,u.telefono,u.email,u.cargo, u.acceso, u.idsucursal, u.login FROM usuario u INNER JOIN sucursal s ON u.idsucursal = s.idsucursal 
			INNER JOIN ROL r ON u.acceso = r.idrol WHERE u.login='$login' AND u.clave='$clave' AND u.condicion='1'";
	return ejecutarConsulta($sql);
}

public function mostrarUsuario($idusuario) {
	$sql = "SELECT s.lat,s.lng, u.idusuario,u.nombre,u.telefono,u.email,u.cargo, u.acceso, u.idsucursal, u.login FROM usuario u INNER JOIN sucursal s ON u.idsucursal = s.idsucursal WHERE u.idusuario='$idusuario'";
	return ejecutarConsultaSimpleFila($sql);
}

public function sucursales($idusuario) {
	$sql = "SELECT s.nombre, s.idsucursal FROM sucursales_usuario su INNER JOIN usuario u ON su.idusuario=u.idusuario INNER JOIN sucursal s ON s.idsucursal = su.idsucursal WHERE su.idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}

public function todasSucursales() {
	$sql = "SELECT * FROM sucursal";
	return ejecutarConsulta($sql);
}

}

 ?>
