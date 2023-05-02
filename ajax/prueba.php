<?php 
require_once "../modelos/Articulo.php";

if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];
    $acceso = $_SESSION['acceso'];

$articulo = new Articulo();


switch ($_GET["op"]) {

        case 'listar':          
          $fecha_inicio=$_REQUEST["fecha_inicio"];
          $fecha_fin=$_REQUEST["fecha_fin"];

          $rspta=$articulo->listarArticulos();
          $data=Array();          

          while ($reg=$rspta->fetch_object()) {
            if($reg->idsucursal == $idsucursal) {             
            /*$importe = number_format($reg->importe);
            $totalImporte = number_format($reg->importe * $reg->cantidad);*/
            $desc = substr($reg->descripcion, 0,12);
            $data[]=array(
                  "0"=>$reg->codigo,
                  "1"=>$reg->fmsi,
                  "2"=>$reg->marca,
                  "3"=>$desc,
                  "4"=>$reg->stock,
                  "5"=>$reg->mayoreo,
                  "6"=>$reg->taller,
                  "7"=>$reg->credito_taller,
                  "8"=>$reg->publico,
                  "9"=>$reg->costo,
                  "10"=>($acceso != '0' && $acceso != '0')? "<button class='btn btn-warning btn-xs' onclick='mostrar(".$reg->idarticulo.")'><i class='fa fa-eye'></i></button> 
                    <button class='btn btn-danger btn-xs' onclick='anular(".$reg->idarticulo.")'><i class='fa fa-close'></i></button>
                    <a target='_blank' href='".$reg->idarticulo."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a>" : "<button class='btn btn-warning btn-xs' onclick='mostrar(".$reg->idarticulo.")'><i class='fa fa-eye'></i></button> 
                    <button class='btn btn-danger btn-xs' onclick='anular(".$reg->idarticulo.")'><i class='fa fa-close'></i></button>
                    <button class='btn btn-default btn-xs' onclick='cobrar(".$reg->idarticulo.")'><i class='fa fa-credit-card'></i></button>
                    <a target='_blank' href='".$url.$reg->idarticulo."'> <button class='btn btn-info btn-xs'><i class='fa fa-file'></i></button></a>"
                  /*"9"=>"$".$importe,
                  "10"=>"$".$totalImporte*/
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