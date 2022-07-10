<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Garantia{


//implementamos nuestro constructor
public function __construct(){

}

public function filtros($busqueda, $limite, $fecha_inicio, $fecha_fin, $tipo_mov) {    

    if($fecha_inicio != '' && $fecha_fin != '' && $busqueda == " " && $limite != "" && $tipo_mov == "") {
        $sql = "SELECT g.precio_garantia,DATE(g.fecha_hora) as fecha_hora,a.codigo, a.fmsi, g.descripcion, g.cantidad, g.idsucursal, g.tipo_mov, g.idservicio FROM garantias AS g INNER JOIN articulo a ON g.idarticulo=a.idarticulo 
        WHERE DATE(g.fecha_hora) >= '$fecha_inicio' AND DATE(g.fecha_hora) <= '$fecha_fin' LIMIT $limite";
        usleep(80000);
        return ejecutarConsulta($sql);
    }
    
    if($fecha_inicio != '' && $fecha_fin != '' && $busqueda != " " && $limite != "" && $tipo_mov == "") {        
        $sql = "SELECT g.precio_garantia,DATE(g.fecha_hora) as fecha_hora,a.codigo, a.fmsi, g.descripcion, g.cantidad, g.idsucursal, g.tipo_mov, g.idservicio 
                FROM garantias g INNER JOIN articulo a ON g.idarticulo=a.idarticulo 
                WHERE DATE(g.fecha_hora) >= '$fecha_inicio' AND DATE(g.fecha_hora) <= '$fecha_fin' 
                AND codigo LIKE '%$busqueda%' OR 
                fmsi LIKE '%$busqueda%' LIMIT $limite";
        usleep(80000);
        return ejecutarConsulta($sql);
    }
    
}

}

 ?>
