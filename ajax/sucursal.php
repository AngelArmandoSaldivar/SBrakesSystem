<?php 
session_start();
require_once "../modelos/Sucursal.php";

$sucursal=new Sucursal();

$idsucursal=isset($_POST["idsucursal"])? limpiarCadena($_POST["idsucursal"]):"";
$nombre=isset($_POST["nombre"])? limpiarCadena($_POST["nombre"]):"";
$direccion=isset($_POST["direccion"])? limpiarCadena($_POST["direccion"]):"";
$telefono=isset($_POST["telefono"])? limpiarCadena($_POST["telefono"]):"";
$lat=isset($_POST["lat"])? limpiarCadena($_POST["lat"]):"";
$lng=isset($_POST["lng"])? limpiarCadena($_POST["lng"]):"";

switch ($_GET["op"]) {
	case 'guardaryeditar':

	if (empty($idsucursal)) {
		$rspta=$sucursal->insertar($nombre, $telefono, $direccion, $lat, $lng);
		echo $rspta ? "Sucursal registrada correctamente" : "No se pudo registrar la sucursal";
	}else{
		$rspta=$sucursal->editar($idsucursal,$nombre,$telefono,$direccion,$lat,$lng);
		echo $rspta ? "Datos actualizados correctamente" : "No se pudo actualizar la sucursal '$nombre' ";
	}
	break;	

	case 'desactivar':
	$rspta=$sucursal->desactivar($idsucursal);
	echo $rspta ? "Datos desactivados correctamente" : "No se pudo desactivar los datos";
	break;

	case 'activar':
	$rspta=$sucursal->activar($idsucursal);
	echo $rspta ? "Datos activados correctamente" : "No se pudo activar los datos";
	break;
	
	case 'mostrar':
	$rspta=$sucursal->mostrar($idsucursal);
	echo json_encode($rspta);
	break;

	case 'listar':
	$rspta=$sucursal->listar();
	$data=Array();

	while ($reg=$rspta->fetch_object()) {
		$data[]=array(			
			"0"=>$reg->nombre,
			"1"=>$reg->direccion,
			"2"=>$reg->telefono,
			"3"=>$reg->numVentas,								
			"4"=>($reg->condicion)?'<span class="label bg-green">Activado</span>':'<span class="label bg-red">Desactivado</span>',
			"5"=>($reg->condicion)?'<div class="emergente">
				<span data-tooltip="Mostrar sucursal"><button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idsucursal.')"><i class="fa fa-pencil"></i></button></span>'.' '.'<span data-tooltip="Eliminar sucursal"><button class="btn btn-danger btn-xs" onclick="desactivar('.$reg->idsucursal.')"><i class="fa fa-close"></i></button></span>':'<span data-tooltip="Mostrar sucursal"><button class="btn btn-warning btn-xs" onclick="mostrar('.$reg->idsucursal.')"><i class="fa fa-pencil"></i></button></span>'.' '.'<span data-tooltip="Activar sucursal"><button class="btn btn-primary btn-xs" onclick="activar('.$reg->idsucursal.')"><i class="fa fa-check"></i></button></span></div>'
		);
	}

	$results=array(
             "sEcho"=>1,//info para datatables
             "iTotalRecords"=>count($data),//enviamos el total de registros al datatable
             "iTotalDisplayRecords"=>count($data),//enviamos el total de registros a visualizar
             "aaData"=>$data); 
	echo json_encode($results);
	break;

	case 'selectSucursal':
		require_once "../modelos/Sucursal.php";
		$sucursal = new Sucursal();

		$rspta = $sucursal->listar();

		while ($reg = $rspta->fetch_object()) {
			echo '<option value='.$reg->idsucursal.'>'.$reg->nombre.'</option>';
		}
	break;
		
}
?>

