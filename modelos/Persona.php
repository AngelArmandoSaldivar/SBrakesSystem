<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Persona{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$telefono_local,$email, $rfc, $credito, $placas, $marca, $modelo, $ano, $color, $kms){	
	$sw=true;
	$num_elementos=0;

	if($nombre != "") {
		$sql="INSERT INTO persona (tipo_persona,nombre,tipo_precio,direccion,telefono,telefono_local,email, rfc, credito) VALUES ('$tipo_persona','$nombre','$tipo_precio','$direccion','$telefono','$telefono_local','$email', '$rfc', '$credito')";
		$idPersonaNew=ejecutarConsulta_retornarID($sql);
	}
	
	if($placas != "") {
		$sql_auto="INSERT INTO autos (placas, marca, modelo, ano, color, kms, idcliente) VALUES('$placas', '$marca', '$modelo', '$ano', '$color', '$kms', '$idPersonaNew')";
		ejecutarConsulta($sql_auto) or $sw=false;
	}	

	sleep(1);
	return $sw;

}

/*public function insertarCliente($tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$telefono_local,$email, $rfc, $credito){	
	$sql="INSERT INTO persona (tipo_persona,nombre,tipo_precio,direccion,telefono,telefono_local,email, rfc, credito) VALUES ('$tipo_persona','$nombre','$tipo_precio','$direccion','$telefono','$telefono_local','$email', '$rfc', '$credito')";
	$idPersonaNew=ejecutarConsulta_retornarID($sql);

	$sw=true;
	$num_elementos=0;
	
	sleep(1);
	return $sw;

}*/

public function editar($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$telefono_local,$email, $rfc, $credito, $placas, $marca, $modelo, $ano, $color, $kms){
	$sw=true;
	if($nombre != "") {
		$sql="UPDATE persona SET tipo_persona='$tipo_persona', nombre='$nombre',tipo_precio='$tipo_precio',direccion='$direccion',telefono='$telefono',email='$email', rfc='$rfc', credito='$credito'
		WHERE idpersona='$idpersona'";
		ejecutarConsulta($sql);
	}
	
	
	if($placas != "") {
		$sql_auto="INSERT INTO autos (placas, marca, modelo, ano, color, kms, idcliente) VALUES('$placas', '$marca', '$modelo', '$ano', '$color', '$kms', '$idpersona')";
		ejecutarConsulta($sql_auto);
	}

	sleep(1);
	return $sw;
}

public function editarPersona($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$telefono_local, $email, $rfc, $credito){
	$sql="UPDATE persona SET tipo_persona='$tipo_persona', nombre='$nombre',tipo_precio='$tipo_precio',direccion='$direccion',telefono='$telefono',telefono_local='$telefono_local', email='$email', rfc='$rfc', credito='$credito'
	WHERE idpersona='$idpersona'";
	ejecutarConsulta($sql);
	$sw=true;
	sleep(1);
	return $sw;
}

//funcion para eliminar datos
public function eliminar($idpersona){
	$sql="DELETE FROM persona WHERE idpersona='$idpersona'";
	sleep(1);
	return ejecutarConsulta($sql);
}

//funcion para eliminar auto
public function eliminarAuto($idauto){
	$sql="DELETE FROM autos WHERE idauto='$idauto'";
	sleep(1);
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idpersona){
	$sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
	sleep(1);
	return ejecutarConsultaSimpleFila($sql);
}

//listar registros
public function listarp(){
	$sql="SELECT * FROM persona WHERE tipo_persona='Proveedor'";
	sleep(1);
	return ejecutarConsulta($sql);
}
public function listarc(){
	$sql="SELECT * FROM persona WHERE tipo_persona='Cliente'";
	sleep(1);
	return ejecutarConsulta($sql);
}

public function listarAutos($idpersona) {
	$sql = "SELECT * FROM autos WHERE idcliente='$idpersona'";
	sleep(1);
	return ejecutarConsulta($sql);
}

}

 ?>
