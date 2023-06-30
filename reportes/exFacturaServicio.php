<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['servicios']==1) {

//incluimos el archivo factura
require('FacturaServicios.php');

$idsucursal = $_SESSION['idsucursal'];
//establecemos los datos de la empresa
$logo="logo.png";
$ext_logo="png";
$empresa="BrakeOne";
$documento="1074528547";
$direccion= $idsucursal == 1 "Calle Portales" ? "";
$telefono="958524158";
$email="brakeone@gmail.com";

//obtenemos los datos de la cabecera de la venta actual
require_once "../modelos/Servicios.php";
$venta= new Servicios();
$rsptav=$venta->serviciocabecera($_GET["id"]);

//recorremos todos los valores que obtengamos
$regv=$rsptav->fetch_object();


$rsptas=$venta->serviciodetalles($_GET["id"]);

//recorremos todos los valores que obtengamos
$regser=$rsptas->fetch_object();


//configuracion de la factura
$pdf = new PDF('p','mm','A4');
$pdf->AddPage();

//enviamos datos de la empresa al metodo addSociete de la clase factura
$pdf->addSociete(utf8_decode($empresa),
                 $documento."\n".
                 utf8_decode("Direccion: "). utf8_decode($direccion)."\n".
                 utf8_decode("Telefono: ").$telefono."\n".
                 "Email: ".$email,$logo);

$pdf->fact_dev("$regv->idservicio");
$pdf->temporaire( "" );
$pdf->addDate($regv->fecha);

//enviamos los datos del cliente al metodo addClientAddresse de la clase factura
$pdf->addClientAdresse("CLIENTE: ".utf8_decode($regv->cliente),
                       "DIRECCION: ".utf8_decode($regv->direccion), 
                       "EMAIL: ".$regv->email, 
                       "TELEFONO: ".$regv->telefono,
                        "MARCA: ". $regv->marca,
                        "MODELO: ". $regv->modelo,
                        "PLACAS: ". $regv->placas,
                        "COLOR: ". $regv->color,
                        "KMS: ". $regv->kms
                    );                    

//establecemos las columnas que va tener la seccion donde mostramos los detalles de la venta
$cols=array( "CODIGO"=>23,
	         "DESCRIPCION"=>78,
	         "CANTIDAD"=>22,
	         "P.U."=>25,
	         "DSCTO"=>20,
	         "SUBTOTAL"=>22);
// $pdf->addCols( $cols);
$cols=array( "CODIGO"=>"L",
             "DESCRIPCION"=>"L",
             "CANTIDAD"=>"C",
             "P.U."=>"R",
             "DSCTO"=>"R",
             "SUBTOTAL"=>"C" );
// $pdf->addLineFormat( $cols);
// $pdf->addLineFormat($cols);

//actualizamos el valor de la coordenada "y" quie sera la ubicacion desde donde empecemos a mostrar los datos 
$y=85;

//obtenemos todos los detalles del servicio actual
$rsptad=$venta->serviciodetalles($_GET["id"]);

//Header tabla productos.
$header = array('Clave', 'Concepto', 'Cantidad', 'Precio', 'Importe');
$pdf->ImprovedTable($header);
$totalArticulos = 0;

//Productos y detalle de productos.
while($regd=$rsptad->fetch_object()){
  $extrae = substr($regd->descripcion, 0,27);
  $importe = $regd->precio_servicio * $regd->cantidad;
  $data = array($regd->codigo, $extrae, $regd->cantidad, "$".number_format($regd->precio_servicio, 2), "$".number_format($importe, 2));
  $pdf->ImprovedTable2($data);
  $totalArticulos += $regd->cantidad;
}

/*aqui falta codigo de letras*/
require_once "Letras.php";
$V = new EnLetras();

$total=$regv->total_servicio; 
$V=new EnLetras(); 
$V->substituir_un_mil_por_mil = true;
// echo "Total: ", $total;
$pdf->totalArticulos($totalArticulos);
$con_letra=strtoupper($V->convertir($total));
$pdf->addCadreTVAs($con_letra. " M.N.");

//mostramos el impuesto
$pdf->addTVAs( $regv->impuesto, $regv->total_servicio, "$");
$pdf->addCadreEurosFrancs("IGV"." $regv->impuesto %");
$pdf->Output('Reporte de Servicio' ,'I');

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>