<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Usuario{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clave,$permisos, $sucursales){
	//echo "Nombre: ".$nombre."<br>","Dirección: ". $direccion."<br>","Telefono: ".$telefono."<br>","Email: ".$email."<br>","Cargo: ".$cargo."<br>","Acceso: ".$acceso."<br>","Login: ".$login."<br>","Clave: ".$clave;
	$sql="INSERT INTO usuario (nombre,direccion,telefono,email,cargo,acceso,login,clave,imagen, idsucursal) 
						VALUES ('$nombre','$direccion','$telefono','$email','$cargo','$acceso','$login','$clave','1', '3')";
	//return ejecutarConsulta($sql);
	 $idusuarionew=ejecutarConsulta_retornarID($sql);
	 $num_elementos=0;
	 $sw=true;

	 while ($num_elementos < count($permisos)) {		
	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuarionew','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 $index=0;
	 while ($index < count($sucursales)) {		
		$sql_sucursales="INSERT INTO sucursales_usuario (idusuario,idsucursal) VALUES('$idusuarionew','$sucursales[$index]')";

		ejecutarConsulta($sql_sucursales) or $sw=false;

		$index=$index+1;
	}
	 return $sw;
}

public function editar($idusuario,$nombre,$direccion,$telefono,$email,$cargo,$acceso,$login,$clave,$permisos, $sucursales){	
	$sql="UPDATE usuario SET nombre='$nombre',direccion='$direccion',telefono='$telefono',email='$email',cargo='$cargo',acceso='$acceso',login='$login',clave='$clave', idsucursal='3'
	WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sql);

	 //eliminar permisos asignados
	 $sqldel="DELETE FROM usuario_permiso WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sqldel);

	 $sqldelsucursales="DELETE FROM sucursales_usuario WHERE idusuario='$idusuario'";
	 ejecutarConsulta($sqldelsucursales);

	 $num_elementos=0;
	 $sw=true;
	 while ($num_elementos < count($permisos)) {
	 	$sql_detalle="INSERT INTO usuario_permiso (idusuario,idpermiso) VALUES('$idusuario','$permisos[$num_elementos]')";

	 	ejecutarConsulta($sql_detalle) or $sw=false;

	 	$num_elementos=$num_elementos+1;
	 }
	 
	 $index = 0;
	 while ($index < count($sucursales)) {
		$sql_detalle="INSERT INTO sucursales_usuario (idusuario,idsucursal) VALUES('$idusuario','$sucursales[$index]')";

		ejecutarConsulta($sql_detalle) or $sw=false;

		$index=$index+1;
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
	$sql="SELECT nombre, telefono, email, cargo,acceso, condicion, idusuario, login FROM usuario";
	return ejecutarConsulta($sql);
}

//metodo para listar permmisos marcados de un usuario especifico
public function listarmarcados($idusuario){
	$sql="SELECT * FROM usuario_permiso WHERE idusuario='$idusuario'";
	return ejecutarConsulta($sql);
}
//funcion que verifica el acceso al sistema

public function verificar($login,$clave){
	$sql="SELECT s.lat,s.lng, u.idusuario,u.nombre,u.telefono,u.email,u.cargo, u.acceso, u.idsucursal, u.login FROM usuario u INNER JOIN sucursal s ON u.idsucursal = s.idsucursal WHERE u.login='$login' AND u.clave='$clave' AND u.condicion='1'";
	return ejecutarConsulta($sql);
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
