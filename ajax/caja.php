<?php 
require_once "../modelos/Caja.php";
require_once "../modelos/Gasto.php";

if(!isset($_SESSION["nombre"])) {
	$caja=new Caja();
	session_start();
	$idsucursal = $_SESSION['idsucursal'];
    $idusuario = $_SESSION['idusuario'];
	$acceso = $_SESSION['acceso'];    
	switch ($_GET["op"]) {
        
        case 'mostrarCaja':
            $fecha = $_POST["fecha_actual"];
            $rspta = $caja->mostrar($idsucursal, $fecha);
            echo json_encode($rspta);
            break;
        
        case 'cierreCaja':
            $fechaCierre = $_POST["fecha_cierre"];
            $montoFinal = $_POST["montoFinal"];
            $rspta = $caja->montoFinal($fechaCierre, $montoFinal, $idsucursal);
            echo $rspta ? "Cierre de caja con exito" : "No se pudo cerrar caja";
            break;

        case 'abrirCajaEdit':
            $fecha = $_POST["fecha_apertura"];
            $rspta = $caja->abrirCajaEdit($fecha, $idsucursal);
            echo $rspta ? "Caja abierta correctamente" : "No se pudo abrir la caja";
            break;       
        
        case 'evaluarCaja':
            $fecha = $_POST["fecha"];
            $rspta = $caja->evaluarCaja($fecha, $idsucursal);
            echo json_encode($rspta);
            break;

        case 'guardarGasto':
            $gasto = new Gasto();
            $txtdescripcionGasto = $_POST["txtdescripcionGasto"];
            $txtCantidad = $_POST["txtCantidad"];
            $txtMontoGasto = $_POST["txtMontoGasto"];
            $metodoPagoGasto = $_POST["metodoPagoGasto"];
            $fecha_gasto = $_POST["fecha_gasto"];
            $informacionAdicionalGasto = $_POST["informacionAdicionalGasto"];
            $rspta=$gasto->insertar($txtdescripcionGasto, $txtCantidad, $txtMontoGasto, $metodoPagoGasto, $informacionAdicionalGasto, $idsucursal, $fecha_gasto);
            echo $rspta ? "Se guardo correctamente el gasto" : "No se pudo guardar el gasto";
            break;

        case 'abrirCaja':
            $fecha = $_POST["fecha_apertura"];
            $monto = $_POST["montoInicial"];
            $detalle = $_POST["detalle_apertura"];
            $rspta = $caja->abrirCaja($fecha, $monto, $detalle, $idsucursal, $idusuario);
            echo $rspta ? "Caja abierta correctamente" : "No se pudo abrir la caja";
            break;

        case 'montoInicial':                
            $rspta = $caja->montoInicial($idsucursal);               
            echo json_encode($rspta);
            break;

        case 'mostrarCheques':
            $fecha = $_GET["fecha_actual"];
            $rspta = $caja->mostrarCheques($idsucursal, $fecha);
            $rsptaserv = $caja->mostrarChequesServicio($idsucursal, $fecha);
            $total_cheques_ventas = 0.0;
            $total_cheques_servicios = 0.0;           
            echo '<table class="table table-xxs" style="font-size:12px">
                    <tbody>
                        <thead class="table-light">
                            <tr>
                                <th>Número</th>                                                        
                                <th>Descripción</th>                                                        
                                <th>Importe</th>
                            </tr>                                               
                        </thead> 
                        <tbody>';
            while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {
                if($fila["fecha"] == $fecha) {
                    $total_cheques_ventas += $fila["importeVenta"];
                    echo '
                        <tr>
                            <td>'.$fila["idpagoVenta"].'</td>
                            <td>'.$fila["nombreVenta"]." REM. ".$fila["remisionVenta"].'</td>
                            <td>$'.number_format($fila["importeVenta"], 2).'</td>
                        </tr>
                    ';
                }
                while ($res = $rsptaserv->fetch_array(MYSQLI_ASSOC)) {
                    if($res["fecha"] == $fecha) {
                        $total_cheques_servicios += $res["importeServicio"];
                        echo '
                            <tr>
                                <td>'.$res["idpagoServicio"].'</td>
                                <td>'.$res["nombreServicio"]." REM. ".$res["remisionServicio"].'</td>
                                <td>$'.number_format($res["importeServicio"], 2).'</td>
                            </tr>
                        ';
                    }
                }                
            }
            echo '<tbody>
                    <tr>
                        <th></th>                                                        
                        <th style="text-align:right">Total cheques</th>
                        <th>$'.number_format($total_cheques_servicios + $total_cheques_ventas, 2).'</th>
                    </tr>                     
                    </tbody>
                </table>';
            break;

            case 'mostrarEfectivos':
                $fecha = $_GET["fecha_actual"];
                $rspta = $caja->mostrarEfectivos($idsucursal, $fecha);
                $rsptaserv = $caja->mostrarEfectivosServicio($idsucursal, $fecha);
                $total_cheques_ventas = 0.0;
                $total_cheques_servicios = 0.0;
                echo '<table class="table table-xxs" style="font-size:12px">
                        <tbody>
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>                                                        
                                    <th>Descripción</th>                                                        
                                    <th>Importe</th>
                                </tr>                                               
                            </thead> 
                            <tbody>';
                while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {
                    if($fila["fecha"] == $fecha) {
                        $total_cheques_ventas += $fila["importeVenta"];
                        echo '
                            <tr>
                                <td>'.$fila["idpagoVenta"].'</td>
                                <td>'.$fila["nombreVenta"]."REM. ".$fila["remisionVenta"].'</td>
                                <td>$'.number_format($fila["importeVenta"], 2).'</td>
                            </tr>
                        ';
                    }                    
                    while ($res = $rsptaserv->fetch_array(MYSQLI_ASSOC)) {
                    if($res["fecha"] == $fecha) {
                        $total_cheques_servicios += $res["importeServicio"];
                        echo '
                            <tr>
                                <td>'.$res["idpagoServicio"].'</td>
                                <td>'.$res["nombreServicio"]."REM. ".$res["remisionServicio"].'</td>
                                <td>$'.number_format($res["importeServicio"], 2).'</td>
                            </tr>
                        ';
                    }                    
                    }
                }                
                echo '<tbody>
                        <tr>
                            <th></th>                                                        
                            <th style="text-align:right">Total efectivo</th>
                            <th>$'.number_format($total_cheques_servicios + $total_cheques_ventas, 2).'</th>
                        </tr>                     
                        </tbody>
                    </table>';
                break;
            case 'mostrarGastos':
                $fecha = $_GET["fecha_actual"];
                $rspta = $caja->mostrarGastos($idsucursal);                        
                $total_gastos = 0.0;
                echo '<table class="table table-xxs" style="font-size:12px">
                        <tbody>
                            <thead class="table-light">
                                <tr>
                                    <th>Número</th>                                                        
                                    <th>Descripción</th>                                                        
                                    <th>Importe</th>
                                </tr>
                            </thead> 
                            <tbody>';
                while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {
                    if($fila["fecha"] == $fecha) {
                        $total_gastos += $fila["total_gasto"];
                        echo '
                            <tr>
                                <td>'.$fila["idgasto"].'</td>
                                <td>'.$fila["descripcion"].'</td>
                                <td>$'.number_format($fila["total_gasto"], 2).'</td>
                            </tr>
                        ';
                    }
                }
                echo '<tbody>
                        <tr>
                            <th></th>                                                        
                            <th style="text-align:right">Total Vales</th>
                            <th>$'.number_format($total_gastos, 2).'</th>
                        </tr>                     
                        </tbody>
                    </table>';
                break;


                case 'mostrarTraspasos':
                    $fecha = $_GET["fecha_actual"];                    
                    $rspta = $caja->mostrarTrasasos($idsucursal);
                    $total_traspasos = 0.0;
                    echo '<table class="table table-xxs" style="font-size:12px">
                            <tbody>
                                <thead class="table-light">
                                    <tr>
                                        <th>Número</th>                                                        
                                        <th>Proveedor</th>                                                        
                                        <th>Importe</th>
                                    </tr>
                                </thead> 
                                <tbody>';
                    while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {                        
                        if($fila["fecha"] == $fecha) {
                            $total_traspasos = $fila["monto_final"];
                            echo '
                                <tr>
                                    <td>'.$fila["idcaja"].'</td>
                                    <td>CAJA 2 $'.$fila["monto_final"].$fila["fecha"].'</td>
                                    <td>$'.number_format($fila["monto_final"], 2).'</td>
                                </tr>
                            ';
                        }
                    }
                    echo '<tbody>
                            <tr>
                                <th></th>                                                        
                                <th style="text-align:right">Total Facturas</th>
                                <th>$'.number_format($total_traspasos, 2).'</th>
                            </tr>                     
                            </tbody>
                        </table>';
                    break;


                case 'mostrarTotales':

                    $fecha = $_GET["fecha_actual"];
                    $rspta = $caja->mostrarTotales($idsucursal); 
                    $regv=$rspta->fetch_object();
                    $totalv=$regv->total;
                    $fecha = $_GET["fecha_actual"];
                    $rspta = $caja->mostrarEfectivos($idsucursal, $fecha);
                    $rsptaserv = $caja->mostrarEfectivosServicio($idsucursal, $fecha);
                    $total_efectivo_ventas = 0.0;
                    $total_efectivo_servicios = 0.0;
                    $gran_total_entradas = 0.0;
                    $gran_total_salidas = 0.0;
                
                    while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {
                        if($fila["fecha"] == $fecha) {
                            $total_efectivo_ventas += $fila["importeVenta"];                        
                        }
                        while ($res = $rsptaserv->fetch_array(MYSQLI_ASSOC)) {
                            if($res["fecha"] == $fecha) {
                                $total_efectivo_servicios += $res["importeServicio"];                        
                            }
                        }
                    }
                     
                    $rsptache = $caja->mostrarCheques($idsucursal, $fecha);
                    $rsptaservche = $caja->mostrarChequesServicio($idsucursal, $fecha);
                    $total_cheques_ventas = 0.0;
                    $total_cheques_servicios = 0.0;                               
                    while ($fila=$rsptache->fetch_array(MYSQLI_ASSOC)) {
                        if($fila["fecha"] == $fecha) {
                            $total_cheques_ventas += $fila["importeVenta"];                            
                        }
                        while ($res = $rsptaservche->fetch_array(MYSQLI_ASSOC)) {
                            if($res["fecha"] == $fecha) {
                                $total_cheques_servicios += $res["importeServicio"];                               
                            }
                        }                
                    }

                    $rspta_traspasos = $caja->mostrarTrasasos($idsucursal);
                    $total_traspasos = 0.0;                    
                    while ($fila=$rspta_traspasos->fetch_array(MYSQLI_ASSOC)) {                        
                        if($fila["fecha"] == $fecha) {
                            $total_traspasos = $fila["monto_final"];                            
                        }
                    }

                    
                    $rspta_gastos = $caja->mostrarGastos($idsucursal);                        
                    $total_gastos = 0.0;                   
                    while ($fila=$rspta_gastos->fetch_array(MYSQLI_ASSOC)) {
                        if($fila["fecha"] == $fecha) {
                            $total_gastos += $fila["total_gasto"];                           
                        }
                    }

                    $gran_total_entradas = $total_efectivo_servicios + $total_efectivo_ventas + $total_cheques_servicios + $total_cheques_ventas;
                    $gran_total_salidas = $total_traspasos + $total_gastos;


                    echo '<table class="table table-xxs" style="font-size:12px">
                        <tbody>
                            <thead class="table-light">
                                <tr>
                                </tr>
                            </thead> 
                            <tbody>';
                            echo '
                            <tr>
                                <td></td>
                                <td style="text-align:right"><b>Total Efectivo</b></td>
                                <td>$'.number_format($total_efectivo_servicios + $total_efectivo_ventas, 2).'</td>
                            </tr>
                        ';
                        echo '
                            <tr>
                                <td></td>
                                <td style="text-align:right"><b>Total cheques</b></td>
                                <td>$'.number_format($total_cheques_servicios + $total_cheques_ventas, 2).'</td>
                            </tr>
                        ';
                        echo '
                            <tr>
                                <td></td>
                                <td style="text-align:right"><b>Gran Total Entradas</b></td>
                                <td>$'.number_format($gran_total_entradas, 2).'</td>
                            </tr>
                        ';
                        echo '<tbody>                                                 
                                </tbody>
                            ';
                    echo '
                        <tr>
                            <td></td>                                   
                            <td style="text-align:center">
                                <label><h5>Salidas</h5></label>
                            </td>
                            <td></td>
                        </tr>';
                    echo '
                        <tr>
                            <td></td>
                            <td style="text-align:right"><b>Total Facturas</b></td>
                            <td>$'.$total_traspasos.'</td>
                        </tr>
                    ';
                    echo '
                        <tr>
                            <td></td>
                            <td style="text-align:right"><b>Total Vales</b></td>
                            <td>$'.$total_gastos.'</td>
                        </tr>
                    ';
                            
                    echo '
                        <tr>
                            <td></td>
                            <td style="text-align:right"><b>Total Depósitos</b></td>
                            <td>$0.0</td>
                        </tr>
                    ';
                    echo '
                        <tr>
                            <td></td>
                            <td style="text-align:right"><b>Gran Total Salidas</b></td>
                            <td>$'.number_format($gran_total_salidas, 2).'</td>
                        </tr>
                    ';
                    echo '<tbody>
                            <tr>
                                <th></th>                                                        
                                <th style="text-align:right"><b>Saldo final caja chica</b></th>
                                <th><input type="hidden" name="precioFinal" id="precioFinal" value="'.number_format($gran_total_entradas - $gran_total_salidas, 2).'"></input>$'.number_format($gran_total_entradas - $gran_total_salidas, 2).'</th>
                            </tr>                     
                            </tbody>
                        </table>';

                    break;

        case 'registroTraspaso':
            $monto = $_POST["montoTraspaso"];    
            $fecha = $_POST["fechaTraspaso"];
            $rspta = $caja->registroTraspaso($monto, $idsucursal, $fecha);
            echo $rspta ? "Traspaso realizado correctamente" : "No se pudo realizar el traspaso";
            break;
    }    
	
}

 ?>