<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
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
  $fechasv='';
  $totalesv='';
  while ($regfechav=$ventas12->fetch_object()) {
    $fechasv=$fechasv.'"'.$regfechav->fecha.'",';
    $totalesv=$totalesv.$regfechav->total.',';
  }


  //quitamos la ultima coma
  $fechasv=substr($fechasv, 0, -1);
  $totalesv=substr($totalesv, 0,-1);
 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Escritorio</h1>
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



<div class="col-lg-3 col-xs-6">
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

<div class="col-lg-3 col-xs-6">

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

<div class="col-lg-3 col-xs-6">

  <div class="small-box bg-yellow">
    
    <div class="inner">
    
      <h3><?php echo number_format($totalClientes); ?></h3>

      <p>Clientes</p>
  
    </div>
    
    <div class="icon">
    
      <i class="fa fa-users"></i>
    
    </div>
    
    <a href="cliente.php" class="small-box-footer">

      Más info <i class="fa fa-arrow-circle-right"></i>

    </a>

  </div>

</div>


<div class="col-lg-3 col-xs-6">

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


<div class="col-lg-6 col-xs-6">

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

</div>

<div class="panel-body">

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      Productos más vendidos sección ventas
    </div>
    <div class="box-body">
      <canvas id="ventasProductos" width="400" height="300"></canvas>
    </div>
  </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      Compras de los ultimos 10 dias
    </div>
    <div class="box-body">
      <canvas id="compras" width="400" height="300"></canvas>
    </div>
  </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      Ventas de los ultimos 12 meses
    </div>
    <div class="box-body">
      <canvas id="ventas" width="400" height="300"></canvas>
    </div>
  </div>
</div>

<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
  <div class="box box-primary">
    <div class="box-header with-border">
      Ventas
    </div>
    <div class="box-body">
      <canvas id="productosMasVendidos" width="400" height="300"></canvas>      
    </div>
  </div>
</div>

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

