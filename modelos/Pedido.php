<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Pedido {


	//implementamos nuestro constructor
public function __construct(){

}

public function desactivar($idpedido){
	$sql="UPDATE pedidos SET status='0' WHERE idpedido='$idpedido'";
	return ejecutarConsulta($sql);
}

public function activar($idpedido){
	$sql="UPDATE pedidos SET status='1' WHERE idpedido='$idpedido'";
	return ejecutarConsulta($sql);
}

//listar registros
public function listar($fecha_inicio,$fecha_fin){
    $sql="SELECT idpedido, clave, marca, cantidad, estadoPedido, notas, status, DATE(fecha_registro) as fecha FROM pedidos WHERE DATE(fecha_registro) >= '$fecha_inicio' AND DATE(fecha_registro) <= '$fecha_fin'";
	return ejecutarConsulta($sql);
}

}

 ?>
