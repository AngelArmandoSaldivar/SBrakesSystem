<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['sucursal']==1) {
 ?>
    <div class="content-wrapper" id="contenedor-principal">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h4 class="box-title">Sucursales <button title="Agregar nueva sucursal" class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button></h4>
  <div class="box-tools pull-right"> 
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>      
      <th>Denominación</th>
      <th>Direccion</th>
      <th>Telefono</th>
      <th>Ventas</th>
      <th>Estado</th>    
      <th>Opciones</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>      
      <th>Denominación</th>
      <th>Direccion</th>
      <th>Telefono</th>
      <th>Ventas</th>
      <th>Estado</th>    
      <th>Opciones</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">
    <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Nombre de Sucursal(*):</label>
      <input class="form-control" type="hidden" name="idsucursal" id="idsucursal">
      <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre" required>
    </div>   
    <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Dirección(*):</label>      
      <input class="form-control" type="text" name="direccion" id="direccion" maxlength="200" placeholder="Dirección" required>
    </div>  
       <div class="form-group col-lg-12 col-md-12 col-xs-12">
      <label for="">Telefono</label>
      <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Número de telefono">
    </div>    
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Latitud(*):</label>
      <input class="form-control" type="text" name="lat" id="lat" placeholder="lat" required>
    </div>
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Longitud(*):</label>
      <input class="form-control" type="text" name="lng" id="lng" placeholder="lng">
    </div>

    <div id="map" style="height: 400px; width:100%; "></div>
    <!--<script>
        function initMap() {
            var coordenadas = {lat: 19.4040032, lng: -98.9880654};
            var mapa = new google.maps.Map(document.getElementById("map"), {zoom:15, center: coordenadas});
            var marker = new google.maps.Marker({position: coordenadas, map:mapa});
        }
    </script>-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC36bGPspEJEk79AVJmUNWXcjMB7AlYtdg&callback=initMap" async defer></script>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12"><br>
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
      <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
    </div>
  </form>
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
 <script src="scripts/sucursales.js"></script>
 <?php 
}

ob_end_flush();
  ?>
