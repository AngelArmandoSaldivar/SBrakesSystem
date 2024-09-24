<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['servicios']==1) {

?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="../public/css/tickets.css">
</head>
<body onload="window.print();">
	<?php 
// incluimos la clase venta
require_once "../modelos/Servicios.php";

$servicio = new Servicios();

//en el objeto $rspta obtenemos los valores devueltos del metodo ventacabecera del modelo
$rspta = $servicio->serviciocabecera($_GET["id"]);

$reg=$rspta->fetch_object();
$idsucursal = $_SESSION['idsucursal'];

//establecemos los datos de la empresa
$calle = $idsucursal == 1 ? "***********" : "*********";
$colonia = $idsucursal == 1 ? "**********" : "*****";
$direccion = $idsucursal == 1 ? "******" : "*****";
$ciudad = $idsucursal == 1 ? "****" : "***";
$cp = $idsucursal == 1 ? "C.P. ****" : "****";
$phone1 = $idsucursal == 1 ? "*****" : "*****";
$phone2 = $idsucursal == 1 ? "****" : "*****";
$phone3 = $idsucursal == 1 ? "(55) 52733450" : "";
$phone4 = $idsucursal == 1 ? "(55) 43834342" : "";

	 ?>
<div class="zona_impresion">
	<!--codigo imprimir-->
	<br>
	<table border="0" align="center" width="300px">
		<tr>
			<td align="center" style="font-size:12px;">
				<!--mostramos los datos de la empresa en el doc HTML-->
				<strong> <img src="../files/images/logo-sbrakes.png" alt="BrakeOne" style="width: 160px;"></strong><br><br>
				<?php echo $calle; ?><br>
				<?php echo $colonia; ?><br>
				<?php echo $direccion; ?><br>
				<?php echo $ciudad." ". $cp; ?><br>
				<?php echo "Tel: ".$phone1." / ". $phone2; ?><br>
				<?php echo "Tel: ".$phone3." / ".$phone4; ?><br>
			</td>
		</tr>
		<tr>
	</table>

	<div style="text-align:center; font-family:Calibri;">
		<h3>TICKET SIN VALOR FISCAL</h3>
	</div>

	<table border="0" align="center" width="250px">
		<tr>
			<td  align="left" style="font-size:12px;"><b>Folio: </b><?php echo $reg->idservicio; ?></td>
			<td align="right" style="font-size:12px;"><b>Fecha: </b><?php echo $reg->fecha_entrada; ?></td>
		</tr>
	</table>
	<br><br>

	<table border="0" align="center" width="260px">
			<td  align="left" style="font-size:12px;"><b>Cliente: </b><?php echo $reg->cliente; ?></td>
	</table>
	<table border="0" align="center" width="260px">
			<td  align="left" style="font-size:12px;"><b>Tel. </b><?php echo $reg->telefono; ?></td>
	</table>

	<table border="0" align="center" width="260px">
			<td  align="left" style="font-size:12px;"><b>Dirección: </b><?php echo $reg->direccion; ?></td>
	</table>

	<br>
	<br>	
	<!--mostramos lod detalles de la venta -->

	<table border="0" align="center" width="260px" style="font-size: 12px;">
		<tr>
			<td colspan="1">CANT.</td>
			<td colspan="1">CONCEPTO</td>
			<td colspan="1">PRECIO <br> UNITARIO</td>
			<td colspan="1" align="right">TOTAL</td>
		</tr>
		<tr>
			<td colspan="4">===========================================</td>
		</tr>
		<?php		
		$rsptad = $servicio->serviciodetalles($_GET["id"]);
		$cantidad=0;		
		while ($regd = $rsptad->fetch_object()) {						
			$descripcion = substr($regd->descripcion, 0,40);
		 	echo "<tr>";
		 	echo "<td>".$regd->cantidad."</td>";
		 	echo "<td>".$regd->clave." <br>".$regd->fmsi." <br>".$regd->marca."...</td>";
			echo "<td>$".number_format($regd->precio_servicio, 2)."</td>";
		 	echo "<td align='right'>$".number_format($regd->subtotal, 2)."</td>";
		 	echo "</tr>";
		 	$cantidad+=$regd->cantidad;
		 }

		 ?>
		 <!--mostramos los totales de la venta-->
		 <tr><td><br></td></tr>
		 <tr>
			<td colspan="4" style="font-size:15px;">N° de articulos: <?php echo $cantidad; ?> </td>
		</tr>
		<tr>
			<td></td><td></td>
			<td align="right"><b>TOTAL:</b></td>
			<td align="right"><b>$<?php echo number_format($reg->total_servicio, 2); ?></b></td>			
		</tr>	
		<tr>
			<td colspan="4" align="justify"><?php 
				require_once "Letras.php";
				$V = new EnLetras();
				$total = $reg->total_servicio;
				$con_letra=strtoupper($V->convertir($total));
				echo $con_letra;
			?></td>
		</tr>
		<tr>
			<td colspan="4">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="4" align="justify">SOLO SE PODRÁ HACER CAMBIO FISICO DEL PRODUCTO EN CASO DE DEFECTO DE FABRICA.
				NO MALTRATE EL EMPAQUE NI LA MERCANCIA. <br><br>
				RECOMENDACIÓN: NO ABUSE DE LOS FRENOS DURANTE LOS PRIMEROS 200 A 300 KMS.
			</td>
		</tr><br>
		<tr>
			<td colspan="4" align="center">Aviso de privacidad / Términos y condiciones en:</td>
		</tr>
		<tr>
			<td colspan="4" align="center">www.sbrakes.mx</td>
		</tr>
		<tr>
			<td colspan="4" align="center"> <img src="../files/images/QR.jpeg" alt="" style="width:180px;"> </td>
		</tr>
	</table>
	<br>
</div>
<p>&nbsp;</p>
</body>
</html>



<?php

	}else{
echo "No tiene permiso para visualizar el reporte";
}

}


ob_end_flush();
  ?>