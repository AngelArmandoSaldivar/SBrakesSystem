<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['marcas']==1) {

 ?>
    <div class="content-wrapper" id="contenedor-principal">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
    <div class="row">
      <div class="col-md-12">
        <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">
        <center>
          <div class="box-header with-border">
              <h4 class="box-title">Marcas</h4>
          </div>
        </center>

    <div class="panel-body table-responsive" id="listadoregistros">
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
    <button title="Registrar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Registrar nueva categoria' data-placement='right' class='btn btn-success' onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar Nuevo</button></h1>
    </div>
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
    <section>
        <center><input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center>
      </section>
    </div>     
      <div class="loader">
        <img src="../files/images/loader.gif" alt="" width="50px">
      </div>
      <div id="global">
        <div id="tablaResultados">
          <section id="tabla_resultado"></section>
        </div>      
      </div>
    </div>

    <div class="panel-body" style="height: 400px;" id="formularioregistros">
      <form action="" name="formulario" id="formulario" method="POST">        
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Descripción </label>                  
          <div class="input-group bootstrap-touchspin">
            <span class="input-group-btn"></span>
            <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
                <input class="form-control" type="hidden" name="idmarca" id="idmarca">
              <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="256" placeholder="Descripcion">
          </div>
        </div>

        <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Utilidad Publico Mostrador </label>                  
          <div class="input-group bootstrap-touchspin">
            <span class="input-group-btn"></span>
            <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
              <input class="form-control" type="number" name="utilidad_1" id="utilidad_1" placeholder="Utilidad Publico Mostrador">
          </div>        
        </div>   

        <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Utilidad Taller </label>                  
          <div class="input-group bootstrap-touchspin">
            <span class="input-group-btn"></span>
            <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
              <input class="form-control" type="number" name="utilidad_2" id="utilidad_2" placeholder="Utilidad Taller">
          </div>        
        </div>

        <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Utilidad Crédito Taller </label>                  
          <div class="input-group bootstrap-touchspin">
            <span class="input-group-btn"></span>
            <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
              <input class="form-control" type="number" name="utilidad_3" id="utilidad_3" placeholder="Utilidad Credito Taller">
          </div>        
        </div>

        <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Utilidad Mayoreo </label>                  
          <div class="input-group bootstrap-touchspin">
            <span class="input-group-btn"></span>
            <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
              <input class="form-control" type="number" name="utilidad_4" id="utilidad_4" placeholder="Utilidad Mayoreo">
          </div>        
        </div>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>

          <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      </form>
    </div>
      </div>
      </div>
      </div>
    </section>
  </div>
<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/marca.js"></script>
 <?php 
}

ob_end_flush();
  ?>

