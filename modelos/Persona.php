<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Persona{


	//implementamos nuestro constructor
public function __construct(){

}

//metodo insertar regiustro
public function insertar($tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito, $placas, $marca, $modelo, $ano, $color, $kms){	
	$sql="INSERT INTO persona (tipo_persona,nombre,tipo_precio,direccion,telefono,email, rfc, credito) VALUES ('$tipo_persona','$nombre','$tipo_precio','$direccion','$telefono','$email', '$rfc', '$credito')";
	$idPersonaNew=ejecutarConsulta_retornarID($sql);

	$sw=true;
	$num_elementos=0;

	if($modelo != "") {
		$sql_auto="INSERT INTO autos (placas, marca, modelo, ano, color, kms, idcliente) VALUES('$placas', '$marca', '$modelo', '$ano', '$color', '$kms', '$idPersonaNew')";
		ejecutarConsulta($sql_auto) or $sw=false;
	}	

	sleep(1);
	return $sw;

}

public function insertarCliente($tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito){	
	$sql="INSERT INTO persona (tipo_persona,nombre,tipo_precio,direccion,telefono,email, rfc, credito) VALUES ('$tipo_persona','$nombre','$tipo_precio','$direccion','$telefono','$email', '$rfc', '$credito')";
	$idPersonaNew=ejecutarConsulta_retornarID($sql);

	$sw=true;
	$num_elementos=0;
	
	sleep(1);
	return $sw;

}

public function editar($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito, $placas, $marca, $modelo, $ano, $color, $kms){
	$sql="UPDATE persona SET tipo_persona='$tipo_persona', nombre='$nombre',tipo_precio='$tipo_precio',direccion='$direccion',telefono='$telefono',email='$email', rfc='$rfc', credito='$credito'
	WHERE idpersona='$idpersona'";
	ejecutarConsulta($sql);
	$sw=true;
	if($modelo != "") {
		$sql_auto="INSERT INTO autos (placas, marca, modelo, ano, color, kms, idcliente) VALUES('$placas', '$marca', '$modelo', '$ano', '$color', '$kms', '$idpersona')";
		ejecutarConsulta($sql_auto);
	}

	sleep(1);
	return $sw;
}

public function editarPersona($idpersona,$tipo_persona,$nombre,$tipo_precio,$direccion,$telefono,$email, $rfc, $credito){
	$sql="UPDATE persona SET tipo_persona='$tipo_persona', nombre='$nombre',tipo_precio='$tipo_precio',direccion='$direccion',telefono='$telefono',email='$email', rfc='$rfc', credito='$credito'
	WHERE idpersona='$idpersona'";
	ejecutarConsulta($sql);
	$sw=true;
	sleep(1);
	return $sw;
}

//funcion para eliminar datos
public function eliminar($idpersona){
	$sql="DELETE FROM persona WHERE idpersona='$idpersona'";
	return ejecutarConsulta($sql);
}

//metodo para mostrar registros
public function mostrar($idpersona){
	$sql="SELECT * FROM persona WHERE idpersona='$idpersona'";
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
	return ejecutarConsulta($sql);
}

public function listarAutos($idpersona) {
	$sql = "SELECT * FROM autos WHERE idcliente='$idpersona'";
	return ejecutarConsulta($sql);
}

}

 ?>
