<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['almacen']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Pedidos <a class="btn btn-success" href="pedidos.php"><i class="fa fa-refresh"></i> Recargar Pagina</a></h1>
  <div class="box-tools pull-right">
    
  </div>            
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Fecha Inicio</label>
    <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
    <label>Fecha Fin</label>
    <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="<?php echo date("Y-m-d"); ?>">
  </div>
  <div class='loader'>
    <img src='../files/images/loader.gif' alt=''>
  </div>
  <table id="tbllistado" class="responsive-table table table-hover table-bordered' style='border-radius: 15px;'">
    <thead>
      <th>Acciones</th>
      <th>Clave</th>
      <th>Marca</th>
      <th>Cantidad</th>
      <th>Fecha Llegada</th>
      <th>Estado Pedido</th>
      <th>Notas</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Acciones</th>
      <th>Clave</th>
      <th>Marca</th>
      <th>Cantidad</th>
      <th>Fecha Llegada</th>
      <th>Estado Pedido</th>
      <th>Notas</th>
    </tfoot>
  </table>
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
 <script src="scripts/pedidosB1.js"></script>
 <?php 
}

ob_end_flush();
  ?>

