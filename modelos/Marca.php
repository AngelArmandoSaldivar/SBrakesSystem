<?php 
require "../config/Conexion.php";

class Marca {	

	//implementamos nuestro constructor
	public function __construct(){

	}

    public function select(){
        $sql="SELECT * FROM marca WHERE estatus=1";
        return ejecutarConsulta($sql);
    }
}