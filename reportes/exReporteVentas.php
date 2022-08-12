<?php 
ob_start();
if (strlen(session_id())<1) 
  session_start();
  $idsucursal = $_SESSION['idsucursal'];
if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['servicios']==1) {

require('ReporteVentas.php');
$logo="logo.png";
$ext_logo="png";
$empresa="BrakeOne";
$documento="1074528547";
$direccion="Calle Portales";
$telefono="958524158";
$email="brakeone@gmail.com";
require_once "../modelos/Venta.php";
$venta= new Venta();

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


$pdf = new PDF('L','mm','A4');
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


$rspta=$venta->reporteVentas($_GET["fecha_inicio"], $_GET["fecha_final"]);
$header = array('Folio', 'Entrada', 'Salida', 'Cliente', 'Estatus', 'Vehiculo', 'Placas', 'Mano obra', 'Total', 'Remision');
$pdf->ImprovedTable($header);

while ($reg=$rspta->fetch_object()) {	
    if($idsucursal == $reg->idsucursal) {
      $remision = $reg->remision == 0 ? "" : "R-".$reg->remision;
      $data = array($reg->idventa, $reg->fecha_entrada, $reg->fecha_salida, substr($reg->nombre, 0, 22), "EMITIDO", "", "", "$".number_format($reg->total_venta, 2), "$".number_format($reg->total_venta, 2), $remision);
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

$rspta=$venta->reporteVentas($_GET["fecha_inicio"], $_GET["fecha_final"]);
$total_servicios = 0.0;
$totalArray = array();
while ($reg=$rspta->fetch_object()) {	
  if($idsucursal == $reg->idsucursal) {
    array_push($totalArray, $reg->total_venta);
  }
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