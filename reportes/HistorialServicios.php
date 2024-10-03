<?php
require('../fpdf21/fpdf.php');
define('EURO', chr(128) );
define('EURO_VAL', 6.55957 );

// Xavier Nicolay 2004
// Version 1.02

//////////////////////////////////////
// Public functions                 //
//////////////////////////////////////
//  function sizeOfText( $texte, $larg )
//  function addSociete( $nom, $adresse )
//  function fact_dev( $libelle, $num )
//  function addDevis( $numdev )
//  function addFacture( $numfact )
//  function addDate( $date )
//  function addClient( $ref )
//  function addPageNumber( $page )
//  function addClientAdresse( $adresse )
//  function addReglement( $mode )
//  function addEcheance( $date )
//  function addNumTVA($tva)
//  function addReference($ref)
//  function addCols( $tab )
//  function addLineFormat( $tab )
//  function lineVert( $tab )
//  function addLine( $ligne, $tab )
//  function addRemarque($remarque)
//  function addCadreTVAs()
//  function addCadreEurosFrancs()
//  function addTVAs( $params, $tab_tva, $invoice )
//  function temporaire( $texte )

class PDF extends FPDF
{
// private variables
var $colonnes;
var $format;
var $angle=0;

// private functions
function RoundedRect($x, $y, $w, $h, $r, $style = '')
{
    $k = $this->k;
    $hp = $this->h;
    if($style=='F')
        $op='f';
    elseif($style=='FD' || $style=='DF')
        $op='B';
    else
        $op='S';
    $MyArc = 4/3 * (sqrt(2) - 1);
    $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
    $xc = $x+$w-$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));

    $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
    $xc = $x+$w-$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
    $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
    $xc = $x+$r ;
    $yc = $y+$h-$r;
    $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
    $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
    $xc = $x+$r ;
    $yc = $y+$r;
    $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k ));
    $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc - $r);
    $this->_out($op);
}

function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
{
    $h = $this->h;
    $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1*$this->k, ($h-$y1)*$this->k,
                        $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
}

function Rotate($angle, $x=-1, $y=-1)
{
    if($x==-1)
        $x=$this->x;
    if($y==-1)
        $y=$this->y;
    if($this->angle!=0)
        $this->_out('Q');
    $this->angle=$angle;
    if($angle!=0)
    {
        $angle*=M_PI/180;
        $c=cos($angle);
        $s=sin($angle);
        $cx=$x*$this->k;
        $cy=($this->h-$y)*$this->k;
        $this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
    }
}

function _endpage()
{
    if($this->angle!=0)
    {
        $this->angle=0;
        $this->_out('Q');
    }
    parent::_endpage();
}

// public functions
function sizeOfText( $texte, $largeur )
{
    $index    = 0;
    $nb_lines = 0;
    $loop     = TRUE;
    while ( $loop )
    {
        $pos = strpos($texte, "\n");
        if (!$pos)
        {
            $loop  = FALSE;
            $ligne = $texte;
        }
        else
        {
            $ligne  = substr( $texte, $index, $pos);
            $texte = substr( $texte, $pos+1 );
        }
        $length = floor( $this->GetStringWidth( $ligne ) );
        $res = 1 + floor( $length / $largeur) ;
        $nb_lines += $res;
    }
    return $nb_lines;
}

// Company
function addSociete( $nom, $adresse, $logo  )
{
    // $x1 = 10;
    // $y1 = 4;
    // //Positionnement en bas
    // //$this->Image($logo, 5 , 3 , 25 , $ext_logo);
    // $this->SetXY( $x1, $y1 );
    // $this->SetFont('Arial','B',12);
    // $length = $this->GetStringWidth( $nom );
    // $this->Cell( $length, 2, $nom);
    // $this->SetXY( $x1, $y1 + 4 );
    // $this->SetFont('Arial','',10);
    // $length = $this->GetStringWidth( $adresse );
    // //Coordonnées de la société
    // $lignes = $this->sizeOfText( $adresse, $length) ;
    // $this->MultiCell($length, 4, $adresse);
}
function LoadData($file)
{
    // Leer las líneas del fichero
    $lines = file($file);
    $data = array();
    foreach($lines as $line)
        $data[] = explode(';',trim($line));
    return $data;
}
// Label and number of invoice/estimate
function fact_dev($num )
{

    $rt  = $this->w - 110;
    $rt2  = $rt + 0;
    $this->SetXY( $rt+2, 30,6+2);
    $this->SetFont( "Arial", "B", 16 );
    $this->Cell($rt2-$rt -1,6, "Historial de servicios", 'B', 0, "C", );
}

// Estimate
function addDevis( $numdev )
{
    $string = sprintf("DEV%04d",$numdev);
    $this->fact_dev( "Devis", $string );
}

// Invoice
function addFacture( $numfact )
{
    // $string = sprintf("FA%04d",$numfact);
    // $this->fact_dev( "Facture", $string );
}

function addDate( $date )
{
  
}

function addClient( $ref )
{
  
}

function addPageNumber( $page )
{
  
}

// Client address
function addClientAdresse( $cliente, $domicilio ,$email, $telefono, $marca, $modelo, $placas, $color, $kms )
{
    $r1     = $this->w - 180;
    $r2Y = $this->w - 65;
    $r2E = $this->w - 145;
    $r2     = $r1 + 68;
    $y1     = 60;
    $this->Image('../files/images/S-BRAKES.PNG',45,8,120);

    $this->SetFont("Arial","B",2);
    $this->SetFont('Arial','',9);
}

function ImprovedTable($header)
{
    $re  = $this->w - 30;
    $rei  = $this->w - 0;
    $rf  = $this->w - 0;
    $y1  = $this->h - 278;
    $this->Line( $re+5, $y1+25, 30, $y1+25);
    $this->SetXY( $this->w - 180, 10 + 35 );
    // Anchuras de las columnas
    $w = array(21, 23, 28, 22, 20, 30, 15, 15);
    // Cabeceras
    for($i=0;$i<count($header);$i++)
        $this->Cell($w[$i],6,$header[$i],'B'); // La C centra el texto
    $this->Ln();
    // Línea de cierre    
    // $this->Cell(array_sum($w),0,'','T');    
}

function ImprovedTable2($data) {            
    $w = array(1, 3, 8, 1, 1, 15, 15, 15);

    for($i=0;$i<count($data);$i++) {
        // Move to 8 cm to the right
        $this->Cell(20);
        $this->Cell($w[$i],6,$data[$i]);
    }
    $this->Ln();

}

// Mode of payment
function addReglement( $mode )
{
    $r1  = 10;
    $r2  = $r1 + 60;
    $y1  = 80;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "MODE DE REGLEMENT", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 -5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$mode, 0,0, "C");
}

// Expiry date
function addEcheance( $date )
{
    $r1  = 80;
    $r2  = $r1 + 40;
    $y1  = 80;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + ($r2 - $r1)/2 - 5 , $y1+1 );
    $this->SetFont( "Arial", "B", 10);
    $this->Cell(10,4, "DATE D'ECHEANCE", 0, 0, "C");
    $this->SetXY( $r1 + ($r2-$r1)/2 - 5 , $y1 + 5 );
    $this->SetFont( "Arial", "", 10);
    $this->Cell(10,5,$date, 0,0, "C");
}

// VAT number
function addNumTVA($tva)
{
    $this->SetFont( "Arial", "B", 10);
    $r1  = $this->w - 80;
    $r2  = $r1 + 70;
    $y1  = 80;
    $y2  = $y1+10;
    $mid = $y1 + (($y2-$y1) / 2);
    $this->RoundedRect($r1, $y1, ($r2 - $r1), ($y2-$y1), 2.5, 'D');
    $this->Line( $r1, $mid, $r2, $mid);
    $this->SetXY( $r1 + 16 , $y1+1 );
    $this->Cell(40, 4, "TVA Intracommunautaire", '', '', "C");
    $this->SetFont( "Arial", "", 10);
    $this->SetXY( $r1 + 16 , $y1+5 );
    $this->Cell(40, 5, $tva, '', '', "C");
}

function addReference($ref)
{
    $this->SetFont( "Arial", "", 10);
    $length = $this->GetStringWidth( "Références : " . $ref );
    $r1  = 10;
    $r2  = $r1 + $length;
    $y1  = 92;
    $y2  = $y1+5;
    $this->SetXY( $r1 , $y1 );
    $this->Cell($length,4, "Références : " . $ref);
}

function addCols( $tab )
{
    global $colonnes;
    
    $r1  = 10;
    $r2  = $this->w - ($r1 * 2) ;
    $y1  = 75;
    $y2  = $this->h - 50 - $y1;
    $this->SetXY( $r1, $y1 );
    $this->Rect( $r1, $y1, $r2, $y2, "D");
    $this->Line( $r1, $y1+6, $r1+$r2, $y1+6);
    $colX = $r1;
    $colonnes = $tab;
    while ( list( $lib, $pos ) = array_keys($tab) )
    {
        $this->SetXY( $colX, $y1+2 );
        $this->Cell( $pos, 1, $lib, 0, 0, "C");
        $colX += $pos;
        $this->Line( $colX, $y1, $colX, $y1+$y2);
    }
}

function addLineFormat( $tab )
{
    global $format, $colonnes;
    
    while ( list( $lib, $pos ) = array_keys($colonnes))
    {
        if ( isset( $tab["$lib"] ) )
            $format[ $lib ] = $tab["$lib"];
    }
}



function addRemarque($remarque)
{
    $this->SetFont( "Arial", "", 10);
    $length = $this->GetStringWidth( "Remarque : " . $remarque );
    $r1  = 10;
    $r2  = $r1 + $length;
    $y1  = $this->h - 45.5;
    $y2  = $y1+5;
    $this->SetXY( $r1 , $y1 );
    $this->Cell($length,4, "Remarque : " . $remarque);
}

function addCadreTVAs($letras)
{
 
}

function addCadreEurosFrancs($impuesto)
{
    $r1  = $this->w - 75;
    $r2  = $r1 + 60;
    $y1  = $this->h - 75;
    $y2  = $y1+20;
    $this->SetXY( $r1, $y1 + 55 );
    $this->SetFont( "Arial", "B", 8);
    $this->SetXY( $r1+22, $y1 );
    $this->Cell(35,4, "TOTAL SERVICIOS", 0, 0, "C");
}

function addTVAs( $impuesto, $total_venta, $simbolo, $idsucursal)
{
    $this->SetFont('Arial','',8);
    
    
    $re  = $this->w - 40;
    $rei  = $this->w - 0;
    $rf  = $this->w - 0;
    $y1  = $this->h - 74;
    $this->SetFont( "Arial", "", 9);

     $this->Line( $re+20, $y1+25, 12, $y1+25);

    // mostrando el total
    $this->SetXY( $re, $y1+10 );
    $length = $this->GetStringWidth( $simbolo );
    $this->Cell( $length, 2, $simbolo);
    $length = $this->GetStringWidth( $total_venta );
    $formatTotal = number_format($total_venta, 2);
    $this->Cell( $length, 2, $formatTotal);   
    
    $this->SetXY( $this->w - 165, $this->h - 59+19 );
    $this->SetFont( "Arial", "", 8);
    $direccion = $idsucursal == 1 ? "SARATOGA #313-C, COL. PORTALES NORTE C.P. 03300 ALCALDIA BENITO JUAREZ, CDMX." : "Belisario Dominguez 81, Las Misiones 76030, Santiago de Queretaro, Qro 76030";
    $this->Cell( 5, 15, $direccion);

    $this->SetXY( $this->w - 150, $this->h - 59+23 );
    $this->SetFont( "Arial", "", 8);
    $this->Cell( 5, 15, $idsucursal == 1 ? "TELS. (55) 4563 / 2063 (55) 6840 / 2850 (55) 7653 / 6116 (55) 5273 / 3450" : "(44) 2738-5075 / (55) 4563-2063"); 
}

// add a watermark (temporary estimate, DUPLICATA...)
// call this method first
function temporaire( $texte )
{
    $this->SetFont('Arial','B',50);
    $this->SetTextColor(203,203,203);
    $this->Rotate(45,55,190);
    $this->Text(55,190,$texte);
    $this->Rotate(0);
    $this->SetTextColor(0,0,0);
}

}
?>