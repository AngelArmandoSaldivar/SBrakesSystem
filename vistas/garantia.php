<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['garantias']==1) {

 ?>
<div class="content-wrapper" id="contenedor-principal">    
  <section class="content">        
    <div class="row">
      <div class="col-md-12">
        <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">
          <center>
            <div class="box-header with-border">
                <h4 class="box-title">Garantias</h4>
            </div>
          </center>
          <div class="panel-body table-responsive" id="listadoregistros"> 
            <div class="form-group col-lg-4 col-md-12 col-xs-12" style="text-align:left;">
              <button title="Exportar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Exportar articulos a Excel' data-placement='right' class='btn btn-success' title="Agregar nuevo articulo" onclick="exportarExcel()"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
            </div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <section>
                <center><input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center>
              </section>      
            </div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12">                
              <select name="limite_registros" id="limite_registros" class="form-control selectpicker">
                <option value="" disabled selected>Seleccionar limite</option>
                <option value="50">50 / Registros</option>
                <option value="100">100 / Registros</option>
                <option value="200">200 / Registros</option>
                <option value="500">500 / Registros</option>
                <option value="1000">1000 / Registros</option>
              </select>          
            </div>            
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <label>Fecha Inicio</label>
              <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="">
            </div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <label>Fecha Fin</label>
              <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="">
            </div>
            <div class="form-group col-lg-2 col-md-6 col-xs-12"> 
              <label>Tipo de movimiento</label>
              <select name="tipo_movimiento" id="tipo_movimiento" class="form-control selectpicker">
                <option value="" disabled selected>Tipo de movimiento</option>                  
                <option value="VENTA">VENTA</option>
                <option value="SERVICIO">SERVICIO</option>               
              </select>                           
            </div>
            <div class="form-group col-lg-2 col-md-6 col-xs-12">               
              <button title="Filtro" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Filtrar' data-placement='right' class='btn btn-default' title="Filtrar" onclick="filtro()"><i class="fa fa-search"></i> Filtrar</button>
            </div>
            <div id="global">
              <div id="tablaResultados">
                <section id="tabla_resultado"></section>
              </div>
            </div>
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
 <script src="scripts/garantias.js"></script>
 <?php 
}

ob_end_flush();
  ?>

