<?php 
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['servicios']==1) {

require('HistorialServicios.php');
$logo="logo.png";
$ext_logo="png";
$empresa="BrakeOne";
$documento="1074528547";
$direccion="Calle Portales";
$telefono="958524158";
$email="brakeone@gmail.com";
require_once "../modelos/Servicios.php";
$venta= new Servicios();
$rsptav=$venta->historialServicios($_GET["id"]);

$idservicio = "";
$fecha = "";
$cliente = "";
$direccion = "";
$email = "";
$rtelefono = "";
$marca = "";
$modelo = "";
$placas = "";
$color = "";
$kms = "";
$impuesto = "";
$total_servicio = 0;

while ($rega=$rsptav->fetch_object()) {
  $idservicio = $rega->idservicio;
  $marca = $rega->marca;
}
$regv=$rsptav->fetch_object();
$rsptas=$venta->serviciodetalles($_GET["id"]);
$regser=$rsptas->fetch_object();
$pdf = new PDF('p','mm','A4');
$pdf->AddPage();
$pdf->addSociete(utf8_decode($empresa),
                 $documento."\n".
                 utf8_decode("Direccion: "). utf8_decode($direccion)."\n".
                 utf8_decode("Telefono: ").$telefono."\n".
                 "Email: ".$email,$logo);

$pdf->fact_dev("$idservicio");
$pdf->temporaire( "" );
$pdf->addDate($fecha);
$pdf->addClientAdresse("CLIENTE: ".utf8_decode($cliente),
                       "DIRECCION: ".utf8_decode($direccion), 
                       "EMAIL: ".$email, 
                       "TELEFONO: ".$telefono,
                        "MARCA: ". $marca,
                        "MODELO: ". $modelo,
                        "PLACAS: ". $placas,
                        "COLOR: ". $color,
                        "KMS: ". $kms
                    );
$cols=array( "CODIGO"=>23,
	         "DESCRIPCION"=>78,
	         "CANTIDAD"=>22,
	         "P.U."=>25,
	         "DSCTO"=>20,
	         "SUBTOTAL"=>22);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO"=>"R",
             "SUBTOTAL"=>"C" );
$y=85;


$rspta=$venta->historialServicios($_GET["id"]);
$header = array('Folio', 'Fecha', 'Vehiculo', 'Placas', 'Kms', 'Concepto', 'Cantidad');
$pdf->ImprovedTable($header);

while ($reg=$rspta->fetch_object()) {	
  $rsptad=$venta->serviciodetalles($reg->idservicio);
  while($regd=$rsptad->fetch_object()) {
    $extrae = substr($regd->descripcion, 0,27);
    $importe = $regd->precio_servicio * $regd->cantidad;
    $data = array($regd->idservicio, $reg->fecha_salida, $reg->marca." ".$reg->modelo, $reg->placas, $reg->kms, substr($regd->descripcion, 0, 15), $regd->cantidad);
    $pdf->ImprovedTable2($data);
  }

}

require_once "Letras.php";
$V = new EnLetras();

$total=$total_servicio; 
$V=new EnLetras(); 
$V->substituir_un_mil_por_mil = true;
$con_letra=strtoupper($V->convertir($total));
$pdf->addCadreTVAs($con_letra. " M.N.");

$rspta=$venta->historialServicios($_GET["id"]);
$total_servicios = 0.0;
$totalArray = array();
while ($reg=$rspta->fetch_object()) {	
  array_push($totalArray, $reg->total_servicio);
}


$pdf->addTVAs( $impuesto, array_sum($totalArray), "$");
$pdf->addCadreEurosFrancs("IGV"." $impuesto %");
$pdf->Output('Reporte de Servicio' ,'I');

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>