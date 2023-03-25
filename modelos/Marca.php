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

    public function insertar($descripcion, $uti1, $uti2, $uti3, $uti4){
        $sql="INSERT INTO marca (descripcion, utilidad_1, utilidad_2, utilidad_3, utilidad_4, estatus) VALUES ('$descripcion', '$uti1', '$uti2', '$uti3', '$uti4', 1)";
        return ejecutarConsulta($sql);
    }
    
    public function editar($idmarca,$descripcion, $uti1, $uti2, $uti3, $uti4){
        $sql="UPDATE marca SET descripcion='$descripcion', utilidad_1='$uti1', utilidad_2='$uti2', utilidad_3='$uti3', utilidad_4='$uti4'
        WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
    public function desactivar($idmarca){
        $sql="UPDATE marca SET estatus='0' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
    public function activar($idmarca){
        $sql="UPDATE marca SET estatus='1' WHERE idmarca='$idmarca'";
        return ejecutarConsulta($sql);
    }
    public function mostrar($idmarca){
        $sql="SELECT * FROM marca WHERE idmarca='$idmarca'";
        return ejecutarConsultaSimpleFila($sql);
    }
}