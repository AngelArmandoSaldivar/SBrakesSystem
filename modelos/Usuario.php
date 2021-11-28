<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$permisos, $idsucursal){
	$sql="INSERT INTO usuario (nombre,tipo_documento,num_documento,direccion,telefono,email,cargo,login,clave,imagen, idsucursal) VALUES ('$nombre','$tipo_documento','$num_documento','$direccion','$telefono','$email','$cargo','$login','$clave','1', '$idsucursal')";
	//return ejecutarConsulta($sql);
	 $idusuarionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;

	 $sql_user_sucursal="INSERT INTO articulo_sucursal (idusuario,idsucursal) VALUES('$idusuarionew', '$idsucursal')";
	 ejecutarConsulta($sql_user_sucursal);

	 while ($num_elementos < count($permisos)) {
		 

	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuarionew','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 return $sw;
}

public function editar($idusuario,$nombre,$tipo_documento,$num_documento,$direccion,$telefono,$email,$cargo,$login,$clave,$permisos, $idsucursal){
	$sql="UPDATE usuario SET nombre='$nombre',tipo_documento='$tipo_documento',num_documento='$num_documento',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',login='$login',clave='$clave', idsucursal='$idsucursal'
	WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sql);

	 //eliminar permisos asignados
	 $sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sqldel);

	$num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($permisos)) {

	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuario','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
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
	$sql="SELECT nombre, tipo_documento, num_documento, telefono, email, cargo, condicion, idusuario, login FROM usuario";
	return ejecutarConsulta($sql);
}

//metodo para listar permmisos marcados de un usuario especifico
public function listarmarcados($idusuario){
	$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
//funcion que verifica el acceso al sistema

public function verificar($login,$clave){

	$sql="SELECT idusuario,nombre,tipo_documento,num_documento,telefono,email,cargo, idsucursal, login FROM usuario WHERE login='$login' AND clave='$clave' AND condicion='1'";
	 return ejecutarConsulta($sql);

}
}

 ?>
