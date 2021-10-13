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
  <h1 class="box-title">Articulo <button class="btn btn-success" onclick="mostrarform(true)" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button> <a target="_blank" href="../reportes/rptarticulos.php"><button class="btn btn-info">Reporte</button></a></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover" style="">
    <thead>      
      <th>Clave</th>
      <th>Fmsi</th>
      <th>Línea</th>
      <th>Marca</th>
      <th>Descripcion</th>
      <th>Costo</th>
      <th>Publico Mostrador</th>
      <th>Taller</th>
      <th>Credito Taller</th>
      <th>Mayoreo</th>
      <th>Stock</th>      
      <th>Estado</th>
      <th>Opciones</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>       
      <th>Clave</th>
      <th>Fmsi</th>
      <th>Línea</th>
      <th>Marca</th>
      <th>Descripcion</th>
      <th>Costo</th>
      <th>Publico Mostrador</th>
      <th>Taller</th>
      <th>Credito Taller</th>
      <th>Mayoreo</th>
      <th>Stock</th>
      <th>Estado</th>
      <th>Opciones</th>
    </tfoot>   
  </table>
</div>
<div class="panel-body" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">


    <!--CLAVE DEL PRODUCTO-->
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Clave(*):</label>
      <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
      <input class="form-control" type="text" name="codigo" id="codigo" maxlength="100" placeholder="Clave" required>
    </div>


    <!--CLAVE DEL PRODUCTO-->
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Fmsi(*):</label>
      <input class="form-control" type="text" name="fmsi" id="fmsi" maxlength="100" placeholder="Fmsi" required>
    </div>


    <!--CATEGORIA DEL PRODUCTO-->
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Categoria(*):</label>
      <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required></select>
    </div>


    <!--MARCA DEL PRODUCTO-->
    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Marca(*):</label>
      <input class="form-control" type="text" name="marca" id="marca" maxlength="100" placeholder="Marca" required>
    </div>

    <!--PROVEEDOR DEL PRODUCTO-->
    <div class="form-group col-lg-6 col-md-8 col-xs-12">
      <label for="">Proveedor(*):</label>
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>        
      </select>
    </div>    

    <!--DESCRIPCIÓN DEL PRODUCTO-->
    <!-- <div class="form-group col-lg-12 col-md-6 col-xs-12">
      <label for="">Descripción</label>
      <textarea class="form-control" type="text" name="descripcion" id="descripcion" maxlength="2000" rows="10" cols="35" placeholder="Descripcion"></textarea>
    </div> -->

    <div class="form-group col-lg-12 col-md-6 col-xs-12">
      <label for="">Descripción(*):</label>
      <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="500" placeholder="Unidades (JUEGO / SET / PIEZA)" required>
    </div> 

    <!--UNIDADES DEL PRODUCTO-->
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Unidades(*):</label>
      <input class="form-control" type="text" name="unidades" id="unidades" maxlength="50" placeholder="Unidades (JUEGO / SET / PIEZA)" required>
    </div>    

    <!--STOCK DEL PRODUCTO-->
       <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Stock</label>
      <input class="form-control" type="number" name="stock" id="stock"  required>
    </div>    

    <!--PASILLO DEL PRODUCTO-->
    <div class="form-group col-lg-4 col-md-6 col-xs-12">
      <label for="">Pasillo(*):</label>
      <input class="form-control" type="text" name="pasillo" id="pasillo" maxlength="50" placeholder="Pasillo / Corredor" required>
    </div>

    <!--COSTOS DEL PRODUCTO-->
    <div class="form-group col-lg-2 col-md-6 col-xs-12">
      <label for="">Costo</label>
      <input class="form-control" type="number" name="costo" id="costo"  required placeholder="$">
    </div>
    <div class="form-group col-lg-2 col-md-6 col-xs-12">
      <label for="">Precio Público</label>
      <input class="form-control" type="number" name="publico" id="publico"  required placeholder="$">
    </div>
    <div class="form-group col-lg-2 col-md-6 col-xs-12">  
      <label for="">Precio Taller</label>
      <input class="form-control" type="number" name="taller" id="taller"  required placeholder="$">
    </div>
    <div class="form-group col-lg-2 col-md-6 col-xs-12">
      <label for="">Precio Credito Taller</label>
      <input class="form-control" type="number" name="credito_taller" id="credito_taller"  required placeholder="$">
    </div>
    <div class="form-group col-lg-2 col-md-6 col-xs-12">
      <label for="">Mayoreo</label>
      <input class="form-control" type="number" name="mayoreo" id="mayoreo"  required placeholder="$">
    </div>    

    <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Codigo:</label>
      <input class="form-control" type="text" name="barcode" id="barcode" placeholder="Código del producto" >
      <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
      <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
      <div id="print">
        <svg id="barras"></svg>
      </div>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
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
require 'footer.php'
 ?>
 <script src="../public/js/JsBarcode.all.min.js"></script>
 <script src="../public/js/jquery.PrintArea.js"></script>
 <script src="scripts/articulo.js"></script>

 <?php 
}

ob_end_flush();
  ?>