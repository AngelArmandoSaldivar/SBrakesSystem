<?php 
    require("../modelos/Articulo.php");
    require 'vendor/autoload.php';

    use PhpOffice\PhpSpreadsheet\Spreadsheet;
    use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    $articulo = new Articulo();
        
    $excel = new Spreadsheet();
    $hojaActiva = $excel->getActiveSheet();
    $hojaActiva->setTitle("Articulos B1S");

    $hojaActiva->setCellValue("A1", "CLAVE");
    $hojaActiva->setCellValue("B1", "FMSI");
    $hojaActiva->setCellValue("C1", "MARCA");
    $hojaActiva->setCellValue("D1", "DESCRIPCIÓN");
    $hojaActiva->setCellValue("E1", "COSTO");
    $hojaActiva->setCellValue("F1", "PUBLICO");
    $hojaActiva->setCellValue("G1", "TALLER");
    $hojaActiva->setCellValue("H1", "CREDITO TALLER");
    $hojaActiva->setCellValue("I1", "MAYOREO");
    $hojaActiva->setCellValue("J1", "STOCK");

    $rspta=$articulo->listarArticulos();
    $fila = 2;

    while($rows = $rspta->fetch_assoc()) {
        $hojaActiva->setCellValue("A".$fila, $rows["codigo"]);
        $hojaActiva->setCellValue("B".$fila, $rows["fmsi"]);
        $hojaActiva->setCellValue("C".$fila, $rows["marca"]);
        $hojaActiva->setCellValue("D".$fila, $rows["descripcion"]);
        $hojaActiva->setCellValue("E".$fila, $rows["costo"]);
        $hojaActiva->setCellValue("F".$fila, $rows["publico"]);
        $hojaActiva->setCellValue("G".$fila, $rows["taller"]);
        $hojaActiva->setCellValue("H".$fila, $rows["credito_taller"]);
        $hojaActiva->setCellValue("I".$fila, $rows["mayoreo"]);
        $hojaActiva->setCellValue("J".$fila, $rows["stock"]);
        $fila ++;
    }
    $fileName="ArticulosB1S.xlsx";
    $writer = new Xlsx($excel);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>