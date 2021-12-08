<?php 
//activamos almacenamiento en el buffer
ob_start();
if (strlen(session_id())<1) 
  session_start();

if (!isset($_SESSION['nombre'])) {
  echo "debe ingresar al sistema correctamente para visualizar el reporte";
}else{

if ($_SESSION['ventas']==1) {

?>

<html>
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8"/>
	<link rel="stylesheet" href="../public/css/ticket.css">
</head>
<body onload="window.print();">
	<?php 
// incluimos la clase venta
require_once "../modelos/Venta.php";

$venta = new Venta();

//en el objeto $rspta obtenemos los valores devueltos del metodo ventacabecera del modelo
$rspta = $venta->ventacabecera($_GET["id"]);

$reg=$rspta->fetch_object();

//establecemos los datos de la empresa
$calle = "SARATOGA 313-C";
$colonia = "COL. PORTALES NORTE";
$direccion = "ALCALDIA BENITO JUAREZ";
$cp = "CDMX C.P. 03303";
$phone1 = "Tel: (55) 7653-6116 / (55) 68402850";
$phone = "Tel: (55) 52733450 / (55) 43834342";
	 ?>
<div class="zona_impresion">
	<!--codigo imprimir-->
	<br>
	<table border="0" align="center" width="300px">
		<tr>
			<td align="center">
				<!--mostramos los datos de la empresa en el doc HTML-->
				<strong> <img src="../files/images/BrakeOneBrembo.png" alt="BrakeOne" style="width: 280px;"></strong><br><br>
				<?php echo $calle; ?><br>
				<?php echo $colonia; ?><br>
				<?php echo $direccion; ?><br>
				<?php echo $cp; ?><br>
				<?php echo $phone1; ?><br>
				<?php echo $phone; ?><br>
			</td>
		</tr>
		<tr>
	</table>

	<div style="text-align:center;">
		<h4>TICKET SIN VALOR FISCAL</h4>
	</div>

	<table border="0" align="center" width="300px">
		<tr>
			<td  align="left"><b>Folio: </b><?php echo $reg->idventa; ?></td><p> <b></b> </p><p></p>
			<td align="right"><b>Fecha: </b><?php echo $reg->fecha; ?></td>
		</tr>
	</table>
	<br><br>
	<div style="margin-left:10px;">
		<b>	Cliente: </b><?php echo $reg->cliente; ?>
	</div><br>
	<div style="margin-left:10px;">
		<b>	Tel. </b><?php echo $reg->telefono; ?>
	</div><br>
	<div style="margin-left:10px;">
		<b>	Dirección. </b><?php echo $reg->direccion; ?>
	</div>
	<br>	
	<!--mostramos lod detalles de la venta -->

	<table border="0" align="center" width="300px">
		<tr>
			<td>CANT.</td>
			<td>CONCEPTO</td>
			<td align="right">IMPORTE</td>
		</tr>
		<tr>
			<td colspan="3">=============================================</td>
		</tr>
		<?php
		$rsptad = $venta->ventadetalles($_GET["id"]);
		$cantidad=0;
		while ($regd = $rsptad->fetch_object()) {
			$descripcion = substr($regd->descripcion, 0,30);
		 	echo "<tr>";
		 	echo "<td>".$regd->cantidad."</td>";
		 	echo "<td>".$descripcion."...</td>";
		 	echo "<td align='right'>$ ".number_format($regd->subtotal)."</td>";
		 	echo "</tr>";
		 	$cantidad+=$regd->cantidad;
		 } 

		 ?>
		 <!--mostramos los totales de la venta-->
		 <tr><td><br></td></tr>
		 <tr>
			<td colspan="3">N° de articulos: <?php echo $cantidad; ?> </td>
		</tr>
		<tr>
			<td></td>
			<td align="right"><b>TOTAL:</b></td>
			<td align="right"><b>$ <?php echo number_format($reg->total_venta); ?></b></td>
		</tr>		
		<tr>
			<td colspan="3">&nbsp;</td>
		</tr>
		<tr>
			<td colspan="3" align="center">Aviso de privacidad / Terminos y condiciones en:</td>
		</tr>
		<tr>
			<td colspan="3" align="center">www.brakeone.mx</td>
		</tr>
		<tr>
			<td colspan="3" align="center"> <img src="../files/images/QR.jpeg" alt="" style="width:150px;"> </td>
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