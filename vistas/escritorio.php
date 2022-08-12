<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
$idsucursal = $_SESSION['idsucursal'];
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{
require 'header.php';
if ($_SESSION['escritorio']==1) {

  require_once "../modelos/Consultas.php";
  $consulta = new Consultas();
  $rsptac = $consulta->totalcomprahoy();
  $regc=$rsptac->fetch_object();
  $totalc=$regc->total_compra;

  $rsptav = $consulta->totalventahoy();
  $regv=$rsptav->fetch_object();
  $totalv=$regv->total_venta;

  $rsptaclientes = $consulta->totalClientes();
  $regClientes = $rsptaclientes->fetch_object();
  $totalClientes = $regClientes->total_clientes;

  $rsptaProductos = $consulta->totalProductos();
  $regProductos = $rsptaProductos->fetch_object();
  $totalProductos = $regProductos->total_productos;


  $rsptaServicios = $consulta->totalserviciohoy();
  $regServicios = $rsptaServicios->fetch_object();
  $totalServicios = $regServicios->total_servicio;

  //obtener valores para cargar al grafico de barras
  $compras10 = $consulta->comprasultimos_10dias();
  $fechasc='';
  $totalesc='';
  while ($regfechac=$compras10->fetch_object()) {
    $fechasc=$fechasc.'"'.$regfechac->fecha.'",';
    $totalesc=$totalesc.$regfechac->total.',';
  }


  //quitamos la ultima coma
  $fechasc=substr($fechasc, 0, -1);
  $totalesc=substr($totalesc, 0,-1);

  $ventasProductos = $consulta->sumaVentaProductos();
  $totalesP = "";
  $clavesProductos = "";
  while($regVentaP=$ventasProductos->fetch_object()) {   
    $totalesP=$totalesP.$regVentaP->totalVetasProductos.",";
    $clavesProductos = $clavesProductos.'"'.$regVentaP->codigo.'",';
  }
  
    //obtener valores para cargar al grafico de barras
  $ventas12 = $consulta->ventasultimos_12meses ();
  $servicios12 = $consulta->serviciossultimos_12meses();
  $ingresos12 = $consulta->ingresosultimos_12meses();
  $fechasv = '';
  $fechass = '';
  $fechasi = '';
  $totalesv = '';
  $totaless = '';
  $totalesi = '';

  while ($regfechaserv = $servicios12->fetch_object()) {      
    if($regfechaserv->idsucursal == $idsucursal) {      
      $fechass = $fechass.'"'.$regfechaserv->fecha.'",';
      $totaless = $totaless.$regfechaserv->total.',';
    }      
  }  
  while ($regfechav=$ventas12->fetch_object()) {   
    if($regfechav->idsucursal == $idsucursal) {
      $fechasv=$fechasv.'"'.$regfechav->fecha.'",';
      $totalesv=$totalesv.$regfechav->total.',';
    }    
  }
  while ($regfechai=$ingresos12->fetch_object()) {
    if($regfechai->idsucursal == $idsucursal) {
      $fechasi=$fechasi.'"'.$regfechai->fecha.'",';
      $totalesi=$totalesi.$regfechai->total.',';
    }    
  }


  //quitamos la ultima coma
  $fechasv=substr($fechasv, 0, -1);
  $totalesv=substr($totalesv, 0,-1);
  $fechass = substr($fechass, 0, -1);
  $totaless = substr($totaless, 0, -1);
  $fechasi = substr($fechasi, 0, -1);
  $totalesi = substr($totalesi, 0, -1);
  $ventasm = $consulta->ventas_mensuales ();
  $fechasms='';
  $totalesms='';
  while ($regfechams=$ventas12->fetch_object()) {
    $fechasms=$fechasms.'"'.$regfechams->fecha.'",';
    $totalesms=$totalesms.$regfechams->total.',';
  }


  //quitamos la ultima coma
  $fechasms=substr($fechasms, 0, -1);
  $totalesms=substr($totalesms, 0,-1);
 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <center><h5 class="box-title">Escritorio</h5></center>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body">
<!-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
  <div class="small-box bg-aqua">
    <div class="inner">
      <h4 style="font-size: 17px;">
        <strong>$<?php echo $totalc; ?> </strong>
      </h4>
      <p>Compras</p>
    </div>
    <div class="icon">
      <i class="ion ion-bag"></i>
    </div>
    <a href="ingreso.php" class="small-box-footer">Compras <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div> -->

<div class="col-lg-3 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="small-box bg-red">    
    <div class="inner">
    
      <h3>$<?php echo number_format($totalc); ?></h3>

      <p>Compras</p>
    
    </div>      
    <div class="icon">
      
      <i class="fa fa-money"></i>
    
    </div>      
    <a href="ingreso.php" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>
  </div>
</div>

<div class="col-lg-3 col-xs-6 shadow p-3 mb-5 bg-body rounded">

  <div class="small-box bg-aqua">
    
    <div class="inner">
      
      <h3>$<?php echo $totalv; ?></h3>

      <p>Ventas</p>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-cart-arrow-down"></i>
    
    </div>
    
    <a href="venta.php" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>


<div class="col-lg-3 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="small-box bg-red">    
    <div class="inner">
    
      <h3><?php echo number_format($totalProductos); ?></h3>

      <p>Productos</p>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-product-hunt"></i>
    
    </div>
    
    <a href="articulo.php" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>


<div class="col-lg-3 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="small-box bg-blue">    
    <div class="inner">
    
      <h3>$<?php echo number_format($totalServicios); ?></h3>

      <p>Servicios</p>
    
    </div>
    
    <div class="icon">
      
      <i class="fa fa-car"></i>
    
    </div>
    
    <a href="servicio.php" class="small-box-footer">
      
      Más info <i class="fa fa-arrow-circle-right"></i>
    
    </a>

  </div>

</div>

<div class="col-lg-12 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Reporte mensual</h4>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <div class="btn-group">
        <a href="#" class="btn btn-tool btn-sm">
          <i class="fas fa-download"></i>
        </a>     
        </div>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-8 bg-body rounded">
          <p class="text-center">
            <strong>Graficos mensuales</strong>
          </p>
          <div class="chart">            
            <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
          </div>          
        </div>        
        <div class="col-md-4">
          <p class="text-center">
            <strong>Resumen mensual</strong>
          </p>

          <div class="progress-group">
            Servicios
            <?php 
              $sql="SELECT estado, idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_servicio) AS total, (ROUND(SUM(total_servicio) * 100) / 1000000) AS porcentaje FROM servicio WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND status != 'ANULADO' AND idsucursal='$idsucursal';";
              $result = ejecutarConsulta($sql);

              while($row = $result->fetch_assoc()) {
                $total = number_format($row["total"], 2);
                echo "<span class='float-right'><b>$</b>$row[total]</span>";
              }
          
            ?>          
            <div class="progress progress-sm">
              <?php 
              $sql="SELECT estado, idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_servicio) AS total, (ROUND(SUM(total_servicio) * 100) / 1000000) AS porcentaje FROM servicio WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND status != 'ANULADO' AND idsucursal='$idsucursal'";
              $result = ejecutarConsulta($sql);
                while($row = $result->fetch_assoc()) {
                  echo "<div class='progress-bar bg-primary' style='width: $row[porcentaje]%'></div>";              
                }              
              ?>
            </div>
          </div>       

          <div class="progress-group">
            Ventas
            <?php 
              $sqlventa="SELECT estado, idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total, (ROUND(SUM(total_venta) * 100) / 1000000) AS porcentaje FROM venta WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND estado != 'ANULADO' AND idsucursal='$idsucursal'";
              $resultventa = ejecutarConsulta($sqlventa);

              while($row = $resultventa->fetch_assoc()) {
                $total = number_format($row["total"], 2);
                echo "<span class='float-right'><b>$</b>$row[total]</span>";              
              }
          
            ?> 
            <div class="progress progress-sm">
              <?php 
                $sqlventa="SELECT estado, idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total, (ROUND(SUM(total_venta) * 100) / 1000000) AS porcentaje FROM venta WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND estado != 'ANULADO' AND idsucursal='$idsucursal'";
                $resultventa = ejecutarConsulta($sqlventa);
                while($row = $resultventa->fetch_assoc()) {
                  echo "<div class='progress-bar bg-success' style='width: $row[porcentaje]%'></div>";              
                }                
              ?>
            </div>
          </div>
          <div class="progress-group">
            <span class="progress-text">Compras</span>
            <?php 
              $sqlventa="SELECT estado, idsucursal, DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_compra) AS total, (ROUND(SUM(total_compra) * 100) / 1000000) AS porcentaje FROM ingreso WHERE MONTH(fecha_hora) = MONTH(CURRENT_DATE()) AND estado='NORMAL' AND idsucursal='$idsucursal'";
              $resultventa = ejecutarConsulta($sqlventa);
              while($row = $resultventa->fetch_assoc()) {
                $total = number_format($row["total"], 2);
                echo "<span class='float-right'><b>$</b>$row[total]</span>";
              }          
            ?> 

            <div class="progress progress-sm">
              <?php 
                $sqlventa="SELECT estado, idsucursal, DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_compra) AS total, (ROUND(SUM(total_compra) * 100) / 1000000) AS porcentaje FROM ingreso WHERE MONTH(fecha_hora) = MONTH(CURRENT_DATE()) AND estado='NORMAL' AND idsucursal='$idsucursal'";
                $resultingreso = ejecutarConsulta($sqlventa);
                while($row = $resultingreso->fetch_assoc()) {
                  echo "<div class='progress-bar bg-success' style='width: $row[porcentaje]%'></div>";              
                }
              ?>               
            </div>
          </div>
        </div>       
      </div>     
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-sm-4 col-6">
          <div class="description-block border-right">            
            <?php 
              $sqlservicio="SELECT DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_servicio) AS total, (ROUND(SUM(total_servicio) * 100) / 1000000) AS porcentaje FROM servicio WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND status != 'ANULADO' AND idsucursal='$idsucursal';";
              $sqlserviciobefore="SELECT DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_servicio) AS total FROM servicio WHERE MONTH(fecha_entrada) = MONTH(DATE_ADD(CURDATE(),INTERVAL -1 MONTH)) AND status != 'ANULADO' AND idsucursal='$idsucursal';";
              $resultservicio = ejecutarConsulta($sqlservicio);
              $resultserviciobefore = ejecutarConsulta($sqlserviciobefore);
              $totalservicios = 0.0;
              $totalservicios_before = 0.0;
              $diferencia = 0.0;
              while($row = $resultservicio->fetch_assoc()) {
                $totalservicios = $row["total"];
              }
              while($row = $resultserviciobefore->fetch_assoc()) {
                $totalservicios_before = $row["total"];
              }              
              if($totalservicios < $totalservicios_before) {
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-danger'><i class='fas fa-caret-down'></i>$porcentajeTotales%</span>";
              } else if($totalservicios > $totalservicios_before){
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-success'><i class='fas fa-caret-up'></i>$porcentajeTotales%</span>";
              }
              $diferencia = number_format($totalservicios - $totalservicios_before, 2);
              echo "<h5 class='description-header'>$$diferencia</h5>";
            ?>
            <span class="description-text">Comparación / mes pasado / Servicios</span>
          </div>
        </div>
        <div class="col-sm-4 col-6">
        <div class="description-block border-right">            
            <?php 
              $sqlservicio="SELECT DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total, (ROUND(SUM(total_venta) * 100) / 1000000) AS porcentaje FROM venta WHERE MONTH(fecha_entrada) = MONTH(CURRENT_DATE()) AND estado != 'ANULADO' AND idsucursal='$idsucursal';";
              $sqlserviciobefore="SELECT DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total FROM venta WHERE MONTH(fecha_entrada) = MONTH(DATE_ADD(CURDATE(),INTERVAL -1 MONTH)) AND estado != 'ANULADO' AND idsucursal='$idsucursal';";
              $resultservicio = ejecutarConsulta($sqlservicio);
              $resultserviciobefore = ejecutarConsulta($sqlserviciobefore);
              $totalservicios = 0.0;
              $totalservicios_before = 0.0;
              $diferencia = 0.0;
              while($row = $resultservicio->fetch_assoc()) {
                $totalservicios = $row["total"];
              }
              while($row = $resultserviciobefore->fetch_assoc()) {
                $totalservicios_before = $row["total"];
              }              
              if($totalservicios < $totalservicios_before) {
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-danger'><i class='fas fa-caret-down'></i>$porcentajeTotales%</span>";
              } else if($totalservicios > $totalservicios_before){
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-success'><i class='fas fa-caret-up'></i>$porcentajeTotales%</span>";
              }
              $diferencia = number_format($totalservicios - $totalservicios_before, 2);
              echo "<h5 class='description-header'>$$diferencia</h5>";
            ?>
            <span class="description-text">Comparación / mes pasado / Ventas</span>
          </div>
        </div>
        <div class="col-sm-4 col-6">
          <div class="description-block border-right">            
            <?php 
              $sqlservicio="SELECT DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_compra) AS total, (ROUND(SUM(total_compra) * 100) / 1000000) AS porcentaje FROM ingreso WHERE MONTH(fecha_hora) = MONTH(CURRENT_DATE()) AND estado = 'NORMAL' AND idsucursal='$idsucursal';";
              $sqlserviciobefore="SELECT DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_compra) AS total FROM ingreso WHERE MONTH(fecha_hora) = MONTH(DATE_ADD(CURDATE(),INTERVAL -1 MONTH)) AND estado = 'NORMAL' AND idsucursal='$idsucursal';";
              $resultservicio = ejecutarConsulta($sqlservicio);
              $resultserviciobefore = ejecutarConsulta($sqlserviciobefore);
              $totalservicios = 0.0;
              $totalservicios_before = 0.0;
              $diferencia = 0.0;
              while($row = $resultservicio->fetch_assoc()) {
                $totalservicios = $row["total"];
              }
              while($row = $resultserviciobefore->fetch_assoc()) {
                $totalservicios_before = $row["total"];
              }              
              if($totalservicios < $totalservicios_before) {
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-danger'><i class='fas fa-caret-down'></i>$porcentajeTotales%</span>";
              } else if($totalservicios > $totalservicios_before){
                if(!$totalservicios_before) $totalservicios_before = 1.0;
                if(!$totalservicios_before) $totalservicios = 1.0;
                $porcentajeTotales = round((($totalservicios - $totalservicios_before) / $totalservicios_before) * 100, 2);
                echo "<span class='description-percentage text-success'><i class='fas fa-caret-up'></i>$porcentajeTotales%</span>";
              }
              $diferencia = number_format($totalservicios - $totalservicios_before, 2);
              echo "<h5 class='description-header'>$$diferencia</h5>";
            ?>
            <span class="description-text">Comparación / mes pasado / Compras</span>
          </div>
        </div>        
      </div>     
    </div>
    </div>
  </div>
</div>

<div class="panel-body">

<div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Compras en los ultimos 10 días</h4>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <div class="btn-group">
        <a href="#" class="btn btn-tool btn-sm">
          <i class="fas fa-download"></i>
        </a>     
        </div>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 bg-body rounded">
          <p class="text-center">
            <strong>Graficos compras</strong>
          </p>
          <div class="chart">            
            <canvas id="compras" width="400" height="300"></canvas>
          </div>          
        </div> 
      </div>     
    </div>       
  </div>
</div>

<div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Ventas en los ultimos 12 meses</h4>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <div class="btn-group">
        <a href="#" class="btn btn-tool btn-sm">
          <i class="fas fa-download"></i>
        </a>     
        </div>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 bg-body rounded">
          <p class="text-center">
            <strong>Graficos ventas</strong>
          </p>
          <div class="chart">            
          <canvas id="ventas" width="400" height="300"></canvas>
          </div>          
        </div> 
      </div>     
    </div>       
  </div>
</div>

<div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded">
  <div class="card">
    <div class="card-header">
      <h4 class="card-title">Productos mas vendidos</h4>
      <div class="card-tools">
        <button type="button" class="btn btn-tool" data-card-widget="collapse">
          <i class="fas fa-minus"></i>
        </button>
        <div class="btn-group">
        <a href="#" class="btn btn-tool btn-sm">
          <i class="fas fa-download"></i>
        </a>     
        </div>
        <button type="button" class="btn btn-tool" data-card-widget="remove">
          <i class="fas fa-times"></i>
        </button>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-12 bg-body rounded">
          <p class="text-center">
            <strong>Graficos ventas productos</strong>
          </p>
          <div class="chart">            
            <canvas id="ventasProductos" width="400" height="300"></canvas>
          </div>          
        </div> 
      </div>     
    </div>       
  </div>
</div>

<section class="col-lg-5 connectedSortable">


</div>
<!--fin centro-->
      </div>
      </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="../public/js/Chart.bundle.min.js"></script>
 <script src="../public/js/Chart.min.js"></script>
 <script>

  var salesChartCanvas = $('#salesChart').get(0).getContext('2d')
  var salesChartData = {
    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre" , "Nomviembre", "Diciembre"],
    datasets: [
      {
        label: 'Servicios',
        backgroundColor: [
                  '#337ab78f',
              ],
              borderColor: [
                  '#337ab7fc',
              ],              
        pointRadius: false,
        pointColor: '#337ab7fc',
        pointStrokeColor: '#000000',
        pointHighlightFill: '#00000',
        pointHighlightStroke: '#337ab7fc',
        data: [<?php echo $totaless ?>]
      },
      {
        label: 'Ventas',
        backgroundColor: [
                  '#dc354596',                  
              ],
              borderColor: [
                  '#dc3545',                  
              ],              
        pointRadius: false,
        pointColor: '#dc3545',
        pointStrokeColor: '#000000',
        pointHighlightFill: '#00000',
        pointHighlightStroke: '#dc3545',
        data: [<?php echo $totalesv ?>]
      },
      {
        label: 'Ingresos',
        backgroundColor: [
                  '#28a74596',                  
              ],
              borderColor: [
                  '#28a745',                  
              ],              
        pointRadius: false,
        pointColor: '#28a745',
        pointStrokeColor: '#000000',
        pointHighlightFill: '#00000',
        pointHighlightStroke: '#28a745',
        data: [<?php echo $totalesi ?>]
      },      
    ]
  }
  var salesChartOptions = {
    maintainAspectRatio: false,
    responsive: true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines: {
          display: false
        }
      }],
      yAxes: [{
        gridLines: {
          display: false
        }
      }]
    }
  }
  var salesChart = new Chart(salesChartCanvas, {
    type: 'line',
    data: salesChartData,
    options: salesChartOptions
  }
  )

  var ctx = document.getElementById("compras").getContext('2d');
  var compras = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [<?php echo $fechasc ?>],
          datasets: [{
              label: '# Compras en $ de los últimos 10 dias',
              data: [<?php echo $totalesc ?>],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
          }
      }
  });

  var ctx = document.getElementById("ventasProductos").getContext('2d');
  var compras = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [<?php echo $clavesProductos ?>],
          datasets: [{
              label: '# Productos más vendidos',
              data: [<?php echo $totalesP ?>],
              backgroundColor: [
                  'rgba(150, 255, 50, 1.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)'
              ],
              borderColor: [
                  'rgba(150, 255, 50, 5.2)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
          }
      }
  });

  var ctx = document.getElementById("ventas").getContext('2d');
  var ventas = new Chart(ctx, {
      type: 'bar',
      data: {
          labels: [<?php echo $fechasv ?>],
          datasets: [{
              label: '# Ventas en $ de los últimos 12 meses',
              data: [<?php echo $totalesv ?>],
              backgroundColor: [
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)',
                  'rgba(255, 99, 132, 0.2)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(153, 102, 255, 0.2)',
                  'rgba(255, 159, 64, 0.2)'
              ],
              borderColor: [
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)',
                  'rgba(255,99,132,1)',
                  'rgba(54, 162, 235, 1)',
                  'rgba(255, 206, 86, 1)',
                  'rgba(75, 192, 192, 1)',
                  'rgba(153, 102, 255, 1)',
                  'rgba(255, 159, 64, 1)'
              ],
              borderWidth: 1
          }]
      },
      options: {
          scales: {
              yAxes: [{
                  ticks: {
                      beginAtZero:true
                  }
              }]
          }
      }
  });

  var data= [{
  x: new Date(),
  y: 1
  }, {
      t: new Date(),
      y: 10
  }];

  var ctx = document.getElementById("productosMasVendidos").getContext('2d');
  var productosMasvendidos = new Chart(ctx, {
    type: 'radar',
    data: {
      datasets: [{
          label: 'First dataset',
          data: [50, 20, 40, 50, 1500, 2800, 48000, 0, 0, 0, 0, 35000]
      }],
      labels: ['Enero', 'Febrero', 'Marzo', 'Abril', "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"]
    },
    options: {
      scale: {
          ticks: {
              suggestedMin: 50,
              suggestedMax: 100
          }
      }
  }
  });

</script>
 <?php 
}

ob_end_flush();
  ?>

