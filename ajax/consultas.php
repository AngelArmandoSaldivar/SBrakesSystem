<?php 
require_once "../modelos/Consultas.php";

if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

$consulta = new Consultas();


switch ($_GET["op"]) {
	

    case 'comprasfecha':
    $fecha_inicio=$_REQUEST["fecha_inicio"];
    $fecha_fin=$_REQUEST["fecha_fin"];

		$rspta=$consulta->comprasfecha($fecha_inicio,$fecha_fin);
		$data=Array();

		while ($reg=$rspta->fetch_object()) {
			$data[]=array(
            "0"=>$reg->fecha,
            "1"=>$reg->usuario,
            "2"=>$reg->proveedor,
            "3"=>$reg->tipo_comprobante,
            "4"=>$reg->serie_comprobante,
            "5"=>$reg->total_compra,
            "6"=>$reg->impuesto,
            "7"=>($reg->estado=='Aceptado')?'<span class="label bg-green">Aceptado</span>':'<span class="label bg-red">Anulado</span>'
              );
		}
		$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
		echo json_encode($results);
		break;

     case 'ventasfechacliente':
      $fecha_inicio=$_REQUEST["fecha_inicio"];
      $fecha_fin=$_REQUEST["fecha_fin"];

      function setInterval($f, $milliseconds)
      {        
        $seconds=(int)$milliseconds/1000;
        while(true)
        {
          $f();
          sleep($seconds);
          echo "Hola;";
       }
      }    

        $rspta=$consulta->ventasfechacliente($fecha_inicio,$fecha_fin);
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
          if($reg->estado != 'ANULADO') {
            if ($reg->tipo_comprobante =='Ticket') {
              $url='../reportes/exTicket.php?id=';
            }else{
              $url='../reportes/exFactura.php?id=';
            }
            $data[]=array(
            "0"=>($reg->estado!='ANULADO' && $reg->estado!='NORMAL')? "<button class='btn btn-warning btn-xs' onclick='mostrar(".$reg->idventa.")'><i class='fa fa-eye'></i></button> 
            <button class='btn btn-danger btn-xs' onclick='anular(".$reg->idventa.")'><i class='fa fa-close'></i></button>
            <a target='_blank' href='".$url.$reg->idventa."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a>" : "<button class='btn btn-warning btn-xs' onclick='mostrar(".$reg->idventa.")'><i class='fa fa-eye'></i></button> 
            <button class='btn btn-danger btn-xs' onclick='anular(".$reg->idventa.")'><i class='fa fa-close'></i></button>
            <button class='btn btn-default btn-xs' onclick='cobrar(".$reg->idventa.")'><i class='fa fa-credit-card'></i></button>
            <a target='_blank' href='".$url.$reg->idventa."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a>",
            "1"=>$reg->idventa,
            "2"=>$reg->fecha_hora,
            "3"=>$reg->cliente,
            "4"=>$reg->usuario,
            "5"=>$reg->forma_pago,
            "6"=>$reg->pagado,
            "7"=>$reg->total_venta,
            "8"=>($reg->estado=='PAGADO')?'<span class="label bg-green">PAGADO</span>':'<span class="label bg-red">'.$reg->estado.'</span>'
              );
          }
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;

        case 'kardex':          
          $fecha_inicio=$_REQUEST["fecha_inicio"];
          $fecha_fin=$_REQUEST["fecha_fin"];

          $rspta=$consulta->kardex($fecha_inicio,$fecha_fin);
          $data=Array();          

          while ($reg=$rspta->fetch_object()) {
            if($reg->estado != 'ANULADO' && $reg->idsucursalArticulo == $idsucursal) {             
            $importe = number_format($reg->importe);
            $totalImporte = number_format($reg->importe * $reg->cantidad);
            $data[]=array(
                  "0"=>$reg->fecha,
                  "1"=>$reg->articuloSucursal,
                  "2"=>$reg->sucursalVenta,
                  "3"=>$reg->tipoMov,
                  "4"=>$reg->folio,
                  "5"=>$reg->clave,
                  "6"=>$reg->fmsi,
                  "7"=>$reg->nombre,
                  "8"=>($reg->tipoMov=='RECEPCION')?'<center><span class="label bg-green">+'.$reg->cantidad.'</span></center>' : '<center><span class="label bg-red">-'.$reg->cantidad.'</span></center>',
                  "9"=>"$".$importe,
                  "10"=>"$".$totalImporte
                    );
            }
          }
          $results=array(
                  "sEcho"=>1,//info para datatables
                  "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
                  "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
                  "aaData"=>$data); 
          echo json_encode($results);
              break;
}
 ?>