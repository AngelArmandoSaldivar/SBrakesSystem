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

    public function montoInicialHoy($FECHA, $IDSUCURSAL) {
        $sql = "SELECT * FROM caja_1 WHERE DATE(fecha_hora) = '$FECHA' AND idsucursal='$IDSUCURSAL'";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function ultimoRegistroCaja($idsucursal) {
        $sql = "SELECT detalle, estado, DATE(fecha_hora) AS fecha, idcaja, idsucursal, idusuario, monto_final, monto_inicial 
                FROM caja_1 
                WHERE idsucursal='$idsucursal' 
                ORDER BY idcaja DESC limit 1";
        return ejecutarConsultaSimpleFila($sql);
    }

    public function montoInicial($idscucursal) {
        $sql = "SELECT detalle, estado, DATE(fecha_hora) as fecha, idcaja, idsucursal, idusuario, monto_final, monto_inicial 
                FROM caja_1 
                WHERE DATE(fecha_hora) = DATE(DATE(now())-1)
                AND idsucursal='$idscucursal';";
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
        $montoFinal = floatval($montoFinal);        
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

    public function mostrarConciliacion($idsucursal, $busqueda) {
        $sql = "SELECT DATE(fp.fecha_hora) AS fecha, fp.color_cargo, fp.observaciones, fp.color_importe, fp.forma_pago, fp.importe, p.nombre, v.remision, fp.cargo, fp.idpago, fp.idservicio
        FROM formas_pago AS fp
        INNER JOIN persona AS p
        ON fp.idcliente = p.idpersona
        INNER JOIN servicio AS v
        ON fp.idservicio = v.idservicio
        /*INNER JOIN servicio AS s
        ON s.idservicio = fp.idservicio*/
        WHERE YEAR(DATE(fp.fecha_hora)) = YEAR(CURRENT_DATE()) 
        AND MONTH(DATE(fp.fecha_hora)) = MONTH(CURRENT_DATE())
        AND (fp.forma_pago = 'Tarjeta' || fp.forma_pago = 'Deposito' || fp.forma_pago = 'Transferencia')
        AND fp.idsucursal='$idsucursal'
        AND v.status!='ANULADO'
        AND p.nombre LIKE '%$busqueda%'";
        return ejecutarConsulta($sql);
    }

    public function saldoFila($idsucursal) {
        $sql = "SELECT fp.idservicio AS IDSERVICIO, fp.cargo AS CARGO, fp.idsucursal AS ID_SUCURSAL, fp.forma_pago AS FORMA_PAGO, DATE(fp.fecha_hora) AS fecha,fp.importe as IMPORTE, SUM(SUM(fp.importe - fp.cargo)) OVER (ORDER BY fp.idpago ASC) AS SALDO_TOTAL
                FROM formas_pago fp
                INNER JOIN persona AS p
                ON fp.idcliente = p.idpersona
                INNER JOIN servicio AS s
                ON fp.idservicio = s.idservicio
                WHERE YEAR(DATE(fp.fecha_hora)) = YEAR(CURRENT_DATE())
                AND MONTH(DATE(fp.fecha_hora)) = MONTH(CURRENT_DATE())
                AND (fp.forma_pago = 'Tarjeta' || fp.forma_pago = 'Deposito' || fp.forma_pago = 'Transferencia')
                AND fp.idsucursal='$idsucursal'
                AND s.status!='ANULADO'
                GROUP BY fp.idpago,
                fp.importe;";
        return ejecutarConsulta($sql);
    }

    public function saldoMesPasado($idsucursal) {

        $sql = "SELECT SUM(fp.importe - fp.cargo) AS importe
        FROM formas_pago fp
        INNER JOIN servicio AS s
        ON fp.idservicio = s.idservicio
        WHERE MONTH(DATE(fp.fecha_hora))  = MONTH(CURRENT_DATE()) -1
        AND (fp.forma_pago = 'Tarjeta' || fp.forma_pago = 'Deposito' || fp.forma_pago = 'Transferencia')
        AND fp.idsucursal='$idsucursal'
        AND s.status != 'ANULADO'";
        return ejecutarConsultaSimpleFila($sql);
    }
    
    public function editarConciliacion($IDPAGO, $FECHA, $TIPO_MOVIMIENTO, $CARGO, $ABONO, $COLOR_CARGO, $COLOR_IMPORTE, $OBSERVACIONES, $MONTOVIEJO, $IDSERVICIO, $IDSERVICIOSUNICOS) {
        $contador = 0;
        $contadorMontoViejo = 0;
        $contadorFormasPago = 0;
        $sw = true;
        while ($contadorMontoViejo < count($IDSERVICIOSUNICOS)) {           
            $sqlupdate = "UPDATE servicio 
                          SET pagado=pagado - $MONTOVIEJO[$contadorMontoViejo]
                          WHERE idservicio='$IDSERVICIOSUNICOS[$contadorMontoViejo]'";
            ejecutarConsulta($sqlupdate) or $sw = false;            
            $contadorMontoViejo = $contadorMontoViejo + 1;
        }

        while ($contador < count($IDPAGO)) {                  
            $sql_servicio = "UPDATE servicio 
                             SET pagado=pagado + $ABONO[$contador]
                             WHERE idservicio='$IDSERVICIO[$contador]'";
	        ejecutarConsulta($sql_servicio) or $sw = false;
            $sw = true;
            $contador = $contador + 1;            
        }

        while ($contadorFormasPago < count($IDPAGO)) {
            $sql = "UPDATE formas_pago 
            SET fecha_hora='$FECHA[$contadorFormasPago]', forma_pago='$TIPO_MOVIMIENTO[$contadorFormasPago]', cargo='$CARGO[$contadorFormasPago]', importe='$ABONO[$contadorFormasPago]', color_cargo='$COLOR_CARGO[$contadorFormasPago]', color_importe='$COLOR_IMPORTE[$contadorFormasPago]', observaciones='$OBSERVACIONES[$contadorFormasPago]'
            WHERE idpago=$IDPAGO[$contadorFormasPago]";
            ejecutarConsulta($sql) or $sw = false;
            $contadorFormasPago = $contadorFormasPago + 1;
        }
        return $sw;
    }    

    public function guardarConcicilacion($fecha, $cargo, $abono, $tipoMov, $idCliente, $descripicion, $observaciones, $idservicio, $idsucursal) {
        $sw = true;        
        $sql_servicio = "UPDATE servicio SET pagado=pagado + '$abono' WHERE idservicio='$idservicio'";
        ejecutarConsulta($sql_servicio) or $sw=false;
        $sql = "INSERT INTO formas_pago (forma_pago, importe, cargo, fecha_hora, observaciones, idsucursal, idservicio, idcliente)
                VALUES('$tipoMov', '$abono', '$cargo', '$fecha', '$observaciones', '$idsucursal', '$idservicio', '$idCliente')";
        ejecutarConsulta($sql) or $sw=false;
        return $sw;
    }

    public function pagosViejos($idsucursal, $idservicio) {
        $sql = "SELECT pagado AS PAGADO_VIEJO, idservicio AS IDSERVICIO
        FROM servicio        
        WHERE idsucursal='$idsucursal'
        AND idservicio='$idservicio'";
        return ejecutarConsultaSimpleFila($sql);
    }

}

 ?>
