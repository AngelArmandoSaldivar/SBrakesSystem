<?php 
require "../config/Conexion.php";

class Caja{	

	//implementamos nuestro constructor
	public function __construct(){

	}
	
    public function mostrarCheques($idscucursal) {              
        $sql = "SELECT DATE(fp.fecha_hora) AS fecha, fp.idpago AS idpagoVenta, fp.importe AS importeVenta, p.nombre AS nombreVenta, v.remision AS remisionVenta 
                FROM formas_pago fp 
                INNER JOIN persona p ON fp.idcliente = p.idpersona 
                INNER JOIN venta v ON v.idventa = fp.idventa 
                WHERE fp.forma_pago='Cheque' 
                AND p.tipo_persona='Cliente' 
                AND fp.idsucursal='$idscucursal'";
        return ejecutarConsulta($sql);
    }
    public function mostrarChequesServicio($idscucursal) {              
        $sql = "SELECT DATE(fp.fecha_hora) AS fecha, fp.idpago AS idpagoServicio, fp.importe AS importeServicio, p.nombre AS nombreServicio, s.remision AS remisionServicio 
                FROM formas_pago fp 
                INNER JOIN persona p ON fp.idcliente = p.idpersona
                INNER JOIN servicio s ON s.idservicio = fp.idservicio 
                WHERE forma_pago='Cheque' AND tipo_persona='Cliente' 
                AND fp.idsucursal='$idscucursal'";
        return ejecutarConsulta($sql);
    }

    public function mostrarEfectivos($idscucursal) {
        $sql = "SELECT DATE(fp.fecha_hora) AS fecha, fp.idpago AS idpagoVenta, fp.importe AS importeVenta, p.nombre AS nombreVenta, v.remision AS remisionVenta 
                FROM formas_pago fp 
                INNER JOIN persona p ON fp.idcliente = p.idpersona 
                INNER JOIN venta v ON v.idventa = fp.idventa 
                WHERE fp.forma_pago='Efectivo' 
                AND p.tipo_persona='Cliente' AND fp.idsucursal='$idscucursal'";
        return ejecutarConsulta($sql);
    }
    public function mostrarEfectivosServicio($idscucursal) {      
        $sql = "SELECT DATE(fp.fecha_hora) AS fecha, fp.idpago AS idpagoServicio, fp.importe AS importeServicio, p.nombre AS nombreServicio, s.remision AS remisionServicio 
                FROM formas_pago fp 
                INNER JOIN persona p ON fp.idcliente = p.idpersona 
                INNER JOIN servicio s ON s.idservicio = fp.idservicio 
                WHERE forma_pago='Efectivo' AND tipo_persona='Cliente'";        
        return ejecutarConsulta($sql);
    }

    public function mostrarGastos($idscucursal) {
        $sql = "SELECT DATE(fecha_hora) AS fecha, idgasto, descripcion, cantidad, (total_gasto * cantidad) AS total_gasto, metodo_pago, informacion_adicional 
                FROM gastos WHERE idsucursal='$idscucursal'";
        return ejecutarConsulta($sql);
    }

    public function mostrarTrasasos($idscucursal) {
        $sql = "SELECT idcaja, idsucursal, idusuario, detalle, DATE(fecha_hora) AS fecha, monto_final 
                FROM caja 
                WHERE idsucursal = '$idscucursal'
                ORDER BY idcaja DESC";
        return ejecutarConsulta($sql);
    }

    public function mostrar($idscucursal, $fecha) {
        $sql = "SELECT * FROM caja_1 WHERE idsucursal='$idscucursal' AND DATE(fecha_hora)='$fecha'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function mostrarTotales($idscucursal) {
        $sql = "SELECT SUM(importe) AS total, fecha_hora FROM formas_pago WHERE forma_pago='Efectivo' AND idsucursal='$idscucursal';";
        return ejecutarConsulta($sql);
    }

    public function registroTraspaso($monto, $idscucursal, $fecha) {
        $sql = "UPDATE caja SET monto_final = monto_final + '$monto' WHERE idsucursal='$idscucursal' AND fecha_hora='$fecha'";
        return ejecutarConsulta($sql);
    }

    public function mostrarCaja2($idscucursal) {
        $sql = "SELECT DATE(fecha_hora) AS fecha, idcaja, detalle, monto, idsucursal 
                FROM caja WHERE idsucursal='$idscucursal'";
        return ejecutarConsulta($sql);
    }

    public function montoInicial($idscucursal) {
        $sql = "SELECT * FROM caja_1 WHERE DATE(fecha_hora) = DATE(DATE(now())-1) AND idsucursal='$idscucursal';";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function abrirCaja($fecha, $monto, $detalle, $idsucursal, $idusuario) {        
        $sw = true;
        $sql = "INSERT INTO caja_1(monto_inicial, idsucursal, idusuario, fecha_hora, detalle, estado)
                VALUES ('$monto', '$idsucursal', '$idusuario', '$fecha', '$detalle', 'ABIERTO')";
        ejecutarConsulta($sql) or $sw=false;

        $sql2 = "INSERT INTO caja(idsucursal, idusuario, fecha_hora, estado, monto_inicial)
                 VALUES ('$idsucursal', '$idusuario', '$fecha', 'ABIERTO', '$monto')";
        ejecutarConsulta($sql2) or $sw=false;

        return $sw;
    }

    public function evaluarCaja($fecha, $idsucucursal) {
        $sql = "SELECT * FROM caja_1 WHERE DATE(fecha_hora)='$fecha'AND idsucursal='$idsucucursal';";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function abrirCajaEdit($fecha, $idscucursal) {
        $sw = true;
        $sql = "UPDATE caja SET estado='ABIERTO' WHERE idsucursal='$idscucursal' AND DATE(fecha_hora) = '$fecha'";
        ejecutarConsulta($sql) or $sw=false;
        $sql2 = "UPDATE caja_1 SET estado='ABIERTO' WHERE idsucursal='$idscucursal' AND DATE(fecha_hora) = '$fecha'";
        ejecutarConsulta($sql2) or $sw=false;
        return $sw;
    }

    public function montoFinal($fechaCierre, $montoFinal, $idscucursal) {
        $sw = true;        
        $sql = "UPDATE caja_1 
                SET monto_final='$montoFinal', estado='CERRADO' 
                WHERE idsucursal='$idscucursal' 
                AND DATE(fecha_hora)='$fechaCierre'";
        ejecutarConsulta($sql) or $sw = false;
        $sql2 = "UPDATE caja 
                SET estado='CERRADO' 
                WHERE idsucursal='$idscucursal' 
                AND DATE(fecha_hora)='$fechaCierre'";
        ejecutarConsulta($sql2) or $sw = false;
        return $sw;
    }

}

 ?>
