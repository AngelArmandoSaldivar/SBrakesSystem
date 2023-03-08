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

        case 'ultimoRegistro':
            $rspta = $caja->ultimoRegistroCaja($idsucursal);
            echo json_encode($rspta);
            break;

        case 'mostrarServicioMontoPagado':
            $IDSERVICIO = $_POST["idservicio"];                        
            $rspta = $caja->pagosViejos($idsucursal, $IDSERVICIO);
            echo json_encode($rspta);
            break;
        case 'listarFoliosValidar':
            require_once "../modelos/Servicios.php";
            $servicios = new Servicios();            
            $rspta = $servicios->mostrar($_POST["servicioid"]);
            //echo '<option value="" disabled selected>Seleccionar folio</option>';
            /*while ($reg = $rspta->fetch_object()) {                
                if($reg->remision != "" && $reg->idsucursal == $idsucursal && $reg->pagado < $reg->total_servicio) {
                    echo '<option value='.$reg->idservicio.'>'."R-".$reg->remision.'</option>';
                }
            }*/

            echo json_encode($rspta);

            break;

        case 'saldoMesPasado':
            $rspta = $caja->saldoMesPasado($idsucursal);            
            echo json_encode($rspta);            
            break; 
        case 'listarFolios':
            require_once "../modelos/Servicios.php";
            $servicio = new Servicios();
            $rspta = $servicio->listar($idsucursal);
            echo '<option value="" disabled selected>Seleccionar folio</option>';
            while ($reg = $rspta->fetch_object()) {                
                if($reg->remision != "" && $reg->idsucursal == $idsucursal && $reg->pagado < $reg->total_servicio) {
                    echo '<option value='.$reg->idservicio.'>'."R-".$reg->remision.'</option>';
                }
            }
            break;
        case 'guardarConciliacion':
            $rspta = $caja->guardarConcicilacion($_POST["fecha"], $_POST["cargo"], $_POST["abono"], $_POST["tipoMov"], $_POST["idCliente"], $_POST["descripicion"], $_POST["observaciones"], $_POST["idservicio"], $idsucursal);
            echo $rspta ? "Conciliacion guardada correctamente" : "La conciliacion no se pudo guardar correctamente";
            break;
        case 'listarClientes':
            require_once "../modelos/Persona.php";
			$persona = new Persona();
            $rspta = $persona->listarc();
            echo '<option value="" disabled selected>Seleccionar cliente</option>';
			while ($reg = $rspta->fetch_object()) {
				echo '<option value='.$reg->idpersona.'>'.$reg->nombre.'</option>';
			}
            break; 
        case 'editarConciliacion':
            $IDPAGO = $_POST["id_pago"];
            $FECHA = $_POST["fecha"];
            $TIPO_MOVIMIENTO = $_POST["tipo_movimiento"];
            $CARGO = $_POST["cargo"];
            $ABONO = $_POST["abono"];
            $COLOR_CARGO = $_POST["colorCargo"];
            $COLOR_IMPORTE = $_POST["colorImporte"];
            $OBSERVACIONES = $_POST["observaciones"];
            $IDSERVICIO = $_POST["idservicio"];
            $IDSERVICIOSUNICOS = $_POST["idServiciosUnicos"];
            $MONTOVIEJO = $_POST["montoViejo"];
            $rspta = $caja->editarConciliacion($IDPAGO, $FECHA, $TIPO_MOVIMIENTO, $CARGO, $ABONO, $COLOR_CARGO, $COLOR_IMPORTE, $OBSERVACIONES, $MONTOVIEJO, $IDSERVICIO, $IDSERVICIOSUNICOS);
            echo $rspta ? "Conciliaciones actualizadas correctamente" : "Error al actualizar conciliaciones";
            break;
        case 'conciliacionSaldos':
            $rspta = $caja->saldoFila($idsucursal);
            $array = [];
            while ($reg=$rspta->fetch_object()) {
                array_push($array, $reg);
            }
            echo json_encode($array);
            break;
        case 'conciliacion':
            $rspta = $caja->mostrarConciliacion($idsucursal, "");
            if(!empty($_POST['busqueda'])) {
				$termino=$conexion->real_escape_string($_POST['busqueda']);
				usleep(100000);
				$rspta=$caja->mostrarConciliacion($idsucursal, $termino);
			} 
            $contador = 0;
            $consultaBD=$rspta;
            if($consultaBD->num_rows>=1){
            echo '            
            <table class="table table-xxs" style="font-size:12px" id="tableConciliacion">
                <tbody>
                    <thead class="table-light">
                        <tr>
                            <th id="thConc">Fecha</th>
                            <th id="thConc">Tipo de mov</th>
                            <th id="thConc">Cliente</th>
                            <th id="thConc">Cargo</th>  
                            <th id="thConc">Abono</th>
                            <th id="thConc">Saldo</th>
                            <th id="thConc">Cuenta No.</th>
                            <th id="thConc">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>';
            $total = 0.0;                    
            while ($fila=$rspta->fetch_array(MYSQLI_ASSOC)) {
                echo '
                    <tr>
                        <td>
                            <input type="hidden" id="idservicio[]" name="idservicio[]" value='.$fila["idservicio"].'></input>
                            <input type="hidden" id="idpago[]" name="idpago[]" value="'.$fila["idpago"].'">
                            </input><input class="touchspin-prefix form-control" name="fecha_conciliacion[]" id="fecha_conciliacion[]" type="date" value='.$fila["fecha"].'></input>
                        </td>
                        <td>
                            <select class="touchspin-prefix form-control" name="forma_pago_conciliacion[]" id="forma_pago_conciliacion[]" required>
                                <option value='.$fila["forma_pago"].' selected disabled hidden>'.$fila["forma_pago"].'</option>                                    
                                <option value="Tarjeta">TARJETA</option>
                                <option value="Deposito">DEPÓSITO</option>
                                <option value="Transferencia">TRASFERENCIA</option>
                            </select>
                        </td>
                        <td style="width:10%">'.$fila["nombre"] . " <b>REM." . $fila["remision"]. "</b>".'</td>
                        <td>
                            <div class="form-group">                            
                                <div class="col-sm-12">                                
                                    <div class="input-group bootstrap-touchspin">
                                    <span class="input-group-btn">                                
                                    </span><span class="input-group-addon bootstrap-touchspin-prefix">$</span>                                
                                    <input class="touchspin-prefix form-control" name="cargo_conciliacion[]" id="cargo_conciliacion[]" type="number" value='.$fila["cargo"].'></input>
                                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>                                
                                    <span class="input-group-btn"><input type="color" class="btn btn-default bootstrap-touchspin-up" name="colorCargo[]" id="colorCargo[]" value='.$fila["color_cargo"].' title="Seleccionar color" style="cursor:pointer;"></span></div>                                
                                </div>                               
                            </div>    
                        </td>                       
                        <td>
                            <div class="form-group">                            
                                <div class="col-sm-12">                                
                                    <div class="input-group bootstrap-touchspin"><span class="input-group-btn">                                
                                    </span><span class="input-group-addon bootstrap-touchspin-prefix">$</span>                                                                    
                                    <input class="touchspin-prefix form-control" name="importe_conciliarion[]" id="importe_conciliarion[]" type="number" value='.$fila["importe"].'></input>
                                    <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>                                
                                    <span class="input-group-btn"><input type="color" class="btn btn-default bootstrap-touchspin-up" name="colorImporte[]" id="colorImporte[]" value='.$fila["color_importe"].' title="Seleccionar color" style="cursor:pointer;"></span></div>                                
                                </div>                               
                            </div>                                                         
                        </td>
                        <td id='.'saldo'.$contador.'>$</td>
                        <td></td>
                        <td><input class="touchspin-prefix form-control" name="observaciones_conciliacion[]" id="observaciones_conciliacion[]" type="text" value='.$fila["observaciones"].'></input></td>
                    </tr>
                ';
                $contador ++;
            }
            echo '<tbody>
                    <tr>
                        <th>Fecha</th>                                                        
                        <th>Tipo de mov</th>                                                        
                        <th>Descripcion</th>
                        <th>Cargo</th>
                        <th>Abono</th>
                        <th>Saldo</th>
                        <th>Cuenta No.</th>
                        <th>Observaciones</th>                     
                    </tr>                     
                    </tbody>
                </table>
                </div>';

            }            
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

        case 'montoInicialHoy':
            $rspta = $caja->montoInicialHoy($_POST["fecha"], $idsucursal);
            echo json_encode($rspta);
            break;

        case 'montoInicial':
            $rspta = $caja->montoInicial($idsucursal);               
            echo json_encode($rspta);
            break;

        case 'mostrarCheques':
            $fecha = $_GET["fecha_actual"];            
            $rsptaserv = $caja->mostrarChequesServicio($idsucursal, $fecha);            
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
            
            echo '<tbody>
                    <tr>
                        <th></th>                                                        
                        <th style="text-align:right">Total cheques</th>
                        <th>$'.number_format($total_cheques_servicios, 2).'</th>
                    </tr>                     
                    </tbody>
                </table>';
            break;

            case 'mostrarEfectivos':
                $fecha = $_GET["fecha_actual"];
                $rsptaserv = $caja->mostrarEfectivosServicio($idsucursal, $fecha);                
                $total_efectivo = 0.0;
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
                              
                    while ($res = $rsptaserv->fetch_array(MYSQLI_ASSOC)) {
                    if($res["fecha"] == $fecha) {
                        $total_efectivo += $res["importeServicio"];
                        echo '
                            <tr>
                                <td>'.$res["idpagoServicio"].'</td>
                                <td>'.$res["nombreServicio"]."REM. ".$res["remisionServicio"].'</td>
                                <td>$'.number_format($res["importeServicio"], 2).'</td>
                            </tr>
                        ';
                                     
                    }
                }                
                echo '<tbody>
                        <tr>
                            <th></th>                                                        
                            <th style="text-align:right">Total efectivo</th>
                            <th>$'.number_format($total_efectivo, 2).'</th>
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
                    $regv=$rspta->fetch_object();
                    $totalv=$regv->total;
                    $fecha = $_GET["fecha_actual"];
                    //$rspta = $caja->mostrarEfectivos($idsucursal, $fecha);
                    $rsptaserv = $caja->mostrarEfectivosServicio($idsucursal, $fecha);
                    $total_efectivo_ventas = 0.0;
                    $total_efectivo_servicios = 0.0;
                    $gran_total_entradas = 0.0;
                    $gran_total_salidas = 0.0;
                
                   
                    while ($res = $rsptaserv->fetch_array(MYSQLI_ASSOC)) {
                        if($res["fecha"] == $fecha) {
                            $total_efectivo_servicios += $res["importeServicio"];                        
                        }
                    }
                    
                                        
                    $rsptaservche = $caja->mostrarChequesServicio($idsucursal, $fecha);
                    $total_cheques_ventas = 0.0;
                    $total_cheques_servicios = 0.0;                               
                   
                    while ($res = $rsptaservche->fetch_array(MYSQLI_ASSOC)) {
                        if($res["fecha"] == $fecha) {
                            $total_cheques_servicios += $res["importeServicio"];                               
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

                    $gran_total_entradas = $total_efectivo_servicios + $total_cheques_servicios;
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
                                <td>$'.number_format($total_efectivo_servicios, 2).'</td>
                            </tr>
                        ';
                        echo '
                            <tr>
                                <td></td>
                                <td style="text-align:right"><b>Total cheques</b></td>
                                <td>$'.number_format($total_cheques_servicios, 2).'</td>
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