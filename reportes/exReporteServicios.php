<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['servicios']==1) {

$idsucursal = $_SESSION['idsucursal'];
//establecemos los datos de la empresa
require('../fpdf21/fpdf.php');
require_once "../modelos/Servicios.php";
$servicio= new Servicios();
$rspta=$servicio->ReporteServicios($_GET["fecha_inicio"], $_GET["fecha_final"]);
$regv=$rspta->fetch_object();

class pdf extends FPDF
{
	public function header()
	{        
		$this->SetFillColor(240, 240, 240);
		$this->Rect(0,0, 300, 50, 'F');
		$this->SetY(25);
		$this->SetFont('Arial', 'B', 8);
		$this->SetTextColor(0,0,0);
		//$this->Write(5, 'BRAKEONE');
        $this->Image('../files/images/S-BRAKES.PNG',100,8,80);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(265,10, "HISTORIAL DE SERVICIOS", 'B', 0, "C", );
		$this->Ln(20);
	}

	public function footer()
	{
    $idsucursal = $_SESSION['idsucursal'];
		$this->SetFillColor(240, 240, 240);
		$this->Rect(0, 250, 220, 50, 'F');
		$this->SetY(-20);
		$this->SetFont('Arial', '', 8);
		$this->SetTextColor(0,0,0);
		$this->SetX(36);
		$this->Write(4, $idsucursal == 1 ? 'SARATOGA #313-C, COL. PORTALES NORTE C.P. 03300 ALCALDIA BENITO JUAREZ, CDMX.' : 'BELISARIO DOMINGUEZ 81, LAS MISIONES 76030, SANTIAGO DE QUERETARO, QRO 76030');
    $this->Ln();
    $this->SetX(48);
		$this->Write(4, $idsucursal == 1 ? 'TELS. (55) 4563 / 2063 (55) 6840 / 2850 (55) 7653 / 6116 (55) 5273 / 3450' : '');
		$this->Ln();
		$this->SetX(68);    
		$this->Write(4, '');
		$this->SetX(120);
		$this->Ln();
		$this->SetX(141);
		$this->Write(5, '');
	}
}

function Ymd_dmY(string $fecha, string $separador = "/"):string
{
	$ano=substr($fecha,0,4);
	$mes=substr($fecha, 5,2);
	$dia=substr($fecha, 8,2);

	$_fecha=$dia.$separador.$mes.$separador.$ano;
	return $_fecha;
}

function SetCurrency(float $valor, string $signo = '$'):string
{
	return $signo.number_format($valor,2,'.','');
}

$fpdf = new PDF('L','mm','A4');
$fpdf->AddPage();
$fpdf->SetMargins(10,30,20,20);
$fpdf->SetFont('Arial', '', 9);
$fpdf->SetTextColor(255,255,255);
$order = 'ORDEN';
$customer = 'CUSTOMER';
$order_details = 'ORDER DETAILS';

/*$fpdf->SetY(15);
$fpdf->SetX(120);
$fpdf->Write(5, 'DETALLES DEL ENVIO ');
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Fecha de la orden: '.Ymd_dmY($order));
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Fecha de envío: '.Ymd_dmY($order));
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Dirección: '.$order);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, 'Ciudad: '.$order);*/

$fpdf->SetTextColor(0,0,0);
//$fpdf->Image('', 20, 55);
//$fpdf->Image('../files/images/QR.jpeg',20,55);
//INFORMACION DEL CLIENTE

$fpdf->SetFont('Arial', 'B');
//$fpdf->SetY(53);
//$fpdf->SetX(250);
/*$fpdf->Write(5, "FOLIO " . "");
$fpdf->Ln(8);*/
$fpdf->SetFont('Arial', '');
$fpdf->Ln();
//$fpdf->SetX(10);
//$fpdf->Write(5, "CLIENTE: ". $regv->cliente);
/*$fpdf->Ln();
$fpdf->SetX(10);
$fpdf->Write(5, "DIRECCION: ". $regv->direccion);
$fpdf->Ln();
$fpdf->SetX(10);
$fpdf->Write(5, "EMAIL: " . $regv->email);
$fpdf->Ln();
$fpdf->SetX(10);
$fpdf->Write(5, "TELEFONO: ". $regv->telefono);*/

//INFORMACION DEL AUTO

$fpdf->SetFont('Arial', 'B');
$fpdf->SetY(61);
$fpdf->SetX(120);
/*$fpdf->Write(5, "FECHA: " . "");
$fpdf->Ln(10);*/
$fpdf->SetFont('Arial', '');
$fpdf->SetX(120);
/*$fpdf->Write(5, "MARCA: ". $regv->marca);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, "MODELO: ". $regv->modelo);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, "PLACAS: " . $regv->placas);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, "COLOR: ". $regv->color);
$fpdf->Ln();
$fpdf->SetX(120);
$fpdf->Write(5, "KMS: ". $regv->kms);*/

$fpdf->SetY(60);
$fpdf->SetTextColor(255,255,255);
$fpdf->SetFillColor(100,100,100);
$fpdf->Cell(10, 10, 'FOLIO', 0, 0, 'C', 1);
$fpdf->Cell(26, 10, 'ENTRADA', 0, 0, 'C', 1);
$fpdf->Cell(26, 10, 'SALIDA', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'CLIENTE', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'ESTATUS', 0, 0, 'C', 1);
$fpdf->Cell(45, 10, 'VEHICULO', 0, 0, 'C', 1);
$fpdf->Cell(20, 10, 'PLACAS', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'MANO OBRA', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'TOTAL', 0, 0, 'C', 1);
$fpdf->Cell(30, 10, 'REMISION', 0, 0, 'C', 1);
$fpdf->Ln();

$fpdf->SetLineWidth(0.5);
$fpdf->SetTextColor(0,0,0);
$fpdf->SetFillColor(255,255,255);
$fpdf->SetDrawColor(80,80,80);
$total = 0;
$totalArticulos = 0;
while($regd=$rspta->fetch_object()){
    
    $remision = $regd->remision == 0 ? "" : "R-".$regd->remision;
 
    $fpdf->Cell(10, 10, $regd->idservicio, 'B', 0, 'C', 1);
    $fpdf->Cell(26, 10, $regd->fecha_entrada, 'B', 0, 'C', 1);
    $fpdf->Cell(26, 10, $regd->fecha_salida, 'B', 0, 'C', 1);
    $fpdf->Cell(32, 10, substr($regd->nombre, 0, 10), 'B', 0, 'C', 1);
    $fpdf->Cell(26, 10, "EMITIDO", 'B', 0, 'C', 1);
    $fpdf->Cell(30, 10, $regd->marca. " ". $regd->modelo, 'B', 0, 'C', 1);
    $fpdf->Cell(35, 10, $regd->placas, 'B', 0, 'C', 1);
    $fpdf->Cell(34, 10, "$".number_format($regd->total_servicio, 2), 'B', 0, 'C', 1);
    $fpdf->Cell(30, 10, "$".number_format($regd->total_servicio, 2), 'B', 0, 'C', 1);
    $fpdf->Cell(30, 10, $remision, 'B', 0, 'C', 1);
    $fpdf->Ln();

}
$iva = $total * 0.13;

$fpdf->SetFontSize(10);

$rsptatotal=$servicio->totalServiciosPorfecha($_GET["fecha_inicio"], $_GET["fecha_final"]);
$total_servicios = 0.0;
$totalArray = array();
while ($reg=$rsptatotal->fetch_object()) {	
  //if($reg->idsucursal == $idsucursal) {
    //$total_servicios += $reg->total_servicio;
    array_push($totalArray, $reg->total_servicio);
  //}
}


require_once "Letras.php";
$V = new EnLetras();

$con_letra=strtoupper($V->convertir(array_sum($totalArray)));

//$fpdf->Ln(60);
$fpdf->SetFont('Arial', 'B', 8);
$fpdf->Cell(30, 10, 'IMPORTE CON LETRA', 0, 0,'C');
$fpdf->SetFont('Arial', '', 8);
$fpdf->Cell(100, 10, $con_letra, 0, 0, 'C');

$fpdf->Cell(10, 10, '', 0, 0,'C');
$fpdf->Cell(20, 10, '', 0, 0,'C');
$fpdf->Cell(30, 10, '', 0, 0, 'C');

$fpdf->Ln();
$fpdf->SetTextColor(255,255,255);
$fpdf->SetFillColor(100,100,100);
$fpdf->Cell(120, 10, '', 0, 0,'C', 1);
$fpdf->SetFont('Arial', 'B', 10);
$fpdf->Cell(80, 10, 'Total', 0, 0,'C', 1);
$fpdf->Cell(10, 10, '', 0, 0,'C',1);
$fpdf->SetFont('Arial', '', 10);
$fpdf->Cell(70, 10, "$".number_format(array_sum($totalArray), 2), 0, 0, 'C', 1);
$fpdf->OutPut();

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}

ob_end_flush();
  ?>