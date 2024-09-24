<?php 
require_once "global.php";

$conexion=new mysqli('localhost','root','','dbsistema'/*, 25060*/);
//$conexion=new mysqli("dbaas-db-9308649-do-user-4187525-0.b.db.ondigitalocean.com","doadmin","AVNS_7cgC06x5ktFMDFKiXNW","brakeone", 25060);

mysqli_query($conexion, 'SET NAMES "'.DB_ENCODE.'"');

//muestra posible error en la conexion
if (mysqli_connect_errno()) {
	printf("Falló en la conexion con la base de datos: %s\n",mysqli_connect_error());
	exit();
}

if (!function_exists('ejecutarConsulta')) {

	Function ejecutarConsulta($sql){ 
		global $conexion;
		$query=$conexion->query($sql);
		return $query;
	}

	function ejecutarConsultaSimpleFila($sql){
		global $conexion;
		$query=$conexion->query($sql);
		$row=$query->fetch_assoc();
		return $row;
	}

	function ejecutarConsultaFila($sql){
		global $conexion;
		$query=$conexion->query($sql);
		$row=$query->fetch_assoc();
		return $row;
	}

	function ejecutarConsulta_retornarID($sql){
		global $conexion;
		$query=$conexion->query($sql);
		return $conexion->insert_id;
	}

	function limpiarCadena($str){
		global $conexion;
		$str=mysqli_real_escape_string($conexion,trim($str));
		return htmlspecialchars($str);
	}

}

 ?>