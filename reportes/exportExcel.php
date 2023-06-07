<?php 
    require("../modelos/Articulo.php");
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
    session_start();
	$idsucursal = $_SESSION['idsucursal'];

    $articulo = new Articulo();
        
    $excel = new Spreadsheet();
    $hojaActiva = $excel->getActiveSheet();
    $hojaActiva->setTitle("Articulos B1S");

    $hojaActiva->setCellValue("A1", "CLAVE");
    $hojaActiva->setCellValue("B1", "FMSI");
    $hojaActiva->setCellValue("C1", "CATEGORIA");
    $hojaActiva->setCellValue("D1", "UNIDAD");
    $hojaActiva->setCellValue("E1", "MARCA");
    $hojaActiva->setCellValue("F1", "PROVEEDOR");
    $hojaActiva->setCellValue("G1", "STOCK");
    $hojaActiva->setCellValue("H1", "STOCK IDEAL");
    $hojaActiva->setCellValue("I1", "PASILLO");
    $hojaActiva->setCellValue("J1", "DESCRIPCIÓN");
    $hojaActiva->setCellValue("K1", "COSTO");
    $hojaActiva->setCellValue("L1", "PUBLICO");
    $hojaActiva->setCellValue("M1", "TALLER");
    $hojaActiva->setCellValue("N1", "CREDITO TALLER");
    $hojaActiva->setCellValue("O1", "MAYOREO");
    $hojaActiva->setCellValue("P1", "CODIGO DE BARRAS");


    $rspta=$articulo->listarArticulos($idsucursal);
    $fila = 2;

    while($rows = $rspta->fetch_assoc()) {
        $hojaActiva->setCellValue("A".$fila, $rows["codigo"]);
        $hojaActiva->setCellValue("B".$fila, $rows["fmsi"]);
        $hojaActiva->setCellValue("C".$fila, $rows["idcategoria"]);
        $hojaActiva->setCellValue("D".$fila, $rows["unidades"]);
        $hojaActiva->setCellValue("E".$fila, $rows["marca"]);
        $hojaActiva->setCellValue("F".$fila, $rows["idproveedor"]);
        $hojaActiva->setCellValue("G".$fila, $rows["stock"]);
        $hojaActiva->setCellValue("H".$fila, $rows["stock_ideal"]);
        $hojaActiva->setCellValue("I".$fila, $rows["pasillo"]);
        $hojaActiva->setCellValue("J".$fila, $rows["descripcion"]);
        $hojaActiva->setCellValue("K".$fila, $rows["costo"]);
        $hojaActiva->setCellValue("L".$fila, $rows["publico"]);
        $hojaActiva->setCellValue("M".$fila, $rows["taller"]);
        $hojaActiva->setCellValue("N".$fila, $rows["credito_taller"]);
        $hojaActiva->setCellValue("O".$fila, $rows["mayoreo"]);
        $hojaActiva->setCellValue("P    ".$fila, $rows["barcode"]);
        $fila ++;
    }
    $fileName="ArticulosB1S.xlsx";
    $writer = new Xlsx($excel);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>