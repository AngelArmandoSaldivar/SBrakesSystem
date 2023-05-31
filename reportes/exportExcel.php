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
    $hojaActiva->setCellValue("C1", "CATEGORIA");
    $hojaActiva->setCellValue("D1", "UNIDAD");
    $hojaActiva->setCellValue("E1", "MARCA");
    $hojaActiva->setCellValue("F1", "PROVEEDOR");
    $hojaActiva->setCellValue("G1", "STOCK");
    $hojaActiva->setCellValue("H1", "PASILLO");
    $hojaActiva->setCellValue("I1", "DESCRIPCIÓN");
    $hojaActiva->setCellValue("J1", "COSTO");
    $hojaActiva->setCellValue("K1", "PUBLICO");
    $hojaActiva->setCellValue("L1", "TALLER");
    $hojaActiva->setCellValue("M1", "CREDITO TALLER");
    $hojaActiva->setCellValue("N1", "MAYOREO");    

    $rspta=$articulo->listarArticulos();
    $fila = 2;

    while($rows = $rspta->fetch_assoc()) {
        $hojaActiva->setCellValue("A".$fila, $rows["codigo"]);
        $hojaActiva->setCellValue("B".$fila, $rows["fmsi"]);
        $hojaActiva->setCellValue("C".$fila, $rows["idcategoria"]);
        $hojaActiva->setCellValue("D".$fila, $rows["unidades"]);
        $hojaActiva->setCellValue("E".$fila, $rows["marca"]);
        $hojaActiva->setCellValue("F".$fila, $rows["idproveedor"]);
        $hojaActiva->setCellValue("G".$fila, $rows["stock"]);
        $hojaActiva->setCellValue("H".$fila, $rows["pasillo"]);
        $hojaActiva->setCellValue("I".$fila, $rows["descripcion"]);
        $hojaActiva->setCellValue("J".$fila, $rows["costo"]);
        $hojaActiva->setCellValue("K".$fila, $rows["publico"]);
        $hojaActiva->setCellValue("L".$fila, $rows["taller"]);
        $hojaActiva->setCellValue("M".$fila, $rows["credito_taller"]);
        $hojaActiva->setCellValue("N".$fila, $rows["mayoreo"]);
        $fila ++;
    }
    $fileName="ArticulosB1S.xlsx";
    $writer = new Xlsx($excel);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
    $writer->save('php://output');

?>