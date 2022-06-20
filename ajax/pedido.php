<?php 
require_once "../modelos/Pedido.php";

if (strlen(session_id())<1)
	session_start();
	$idsucursal = $_SESSION['idsucursal'];

$pedido = new Pedido();
switch ($_GET["op"]) {    

     case 'listarPedidos':
        $fecha_inicio=$_REQUEST["fecha_inicio"];
        $fecha_fin=$_REQUEST["fecha_fin"];

        $rspta=$pedido->listar($fecha_inicio,$fecha_fin);
        
        $data=Array();

        while ($reg=$rspta->fetch_object()) {
            $data[]=array(
                "0"=>($reg->status=='1')?"<button class='btn btn-danger btn-xs' onclick='desactivar(".$reg->idpedido.")'><i class='fa fa-close'></i></button>" : "<button class='btn btn-primary btn-xs' onclick='activar(".$reg->idpedido.")'><i class='fa fa-check'></i></button>",
                "1"=>$reg->clave,
                "2"=>$reg->marca,
                "3"=>$reg->cantidad,
                "4"=>$reg->fecha,
                "5"=>$reg->estadoPedido,
                "6"=>substr($reg->notas, 0, 30),
            );          
        }
        $results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
        echo json_encode($results);
        break;

    case 'anular':
        $idpedido = $_GET["idpedido"];
        $rspta = $pedido->desactivar($idpedido);
        echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
        break;
    case 'activar':
        $idpedido = $_GET["idpedido"];
        $rspta = $pedido->activar($idpedido);
        echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
        break;
       
}
 ?>