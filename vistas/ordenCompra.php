<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['kardex']==1) {

 ?>
    <div class="content-wrapper" id="contenedor-principal">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h4 class="box-title">Ordenes de Compra</h4>
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
  <div class="form-group col-lg-12 col-md-12 col-sm-6 col-xs-12">    
  </div>
  <br/>
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Numero de Parte</th>
      <th>Marca</th>
      <th>Cantidad Pedida</th>
      <th>Descripcion</th>
      <th>Precio Unitario</th>
      <th>Descuento</th>
      <th>Precio Unitario con Descuento </th>
      <th>Monto</th>      
    </thead>
    <tbody>
    </tbody>
    <tfoot>
        <th>Numero de Parte</th>
      <th>Cantidad Pedida</th>
      <th>Descripcion</th>
      <th>Precio Unitario</th>
      <th>Descuento</th>
      <th>Precio Unitario con Descuento </th>
      <th>Monto</th>  
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
 <script src="scripts/ordenesCompra.js"></script>
 <?php 
}

ob_end_flush();
  ?>

