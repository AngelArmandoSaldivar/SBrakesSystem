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
          <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">
          <center>
            <div class="box-header with-border">
                <h4 class="box-title">Articulos </h4>
            </div>
          </center>                    

          <div class="table-responsive" id="table-search">
            <section>
                <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <button title="Registrar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Registrar nueva articulo' data-placement='bottom' class='btn btn-success' title="Agregar nuevo articulo" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar Nuevo</button></h1>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-xs-12" style="position:relative;">
                  <center>
                    <input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;">
                  </center>
                </div>
            </section>
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <div class="loaderSearch">
                <img src="../files/images/loader.gif" alt="" width="35px">
              </div>
            </div>

            <div class="form-group col-lg-4 col-sm-12 col-xs-12" style="text-align:left;"></div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:left;"></div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:left;">              
            </div>             
            <div class="form-group col-lg-4 col-sm-4" style="text-align:left;">
              <button title="Exportar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Exportar articulos a Excel' data-placement='right' class='btn btn-success' title="Agregar nuevo articulo" onclick="exportarExcel()"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
            </div>
            
            <div class="form-group col-lg-4 col-sm-4" style="text-align:left;">
            <a data-toggle="modal" href="#cambiarPreciosProductos">
              <button title="Cambiar precios" id="btnCanbiarPrecios" data-toggle='popover' data-trigger='hover' data-content='Cambiar precios productos' data-placement='right' class='btn btn-primary' title="Cambiar precios"><i class="fa fa-usd"></i> Cambiar precios</button>
            </a>
            </div>
            <div class="form-group col-lg-4 col-sm-4" style="text-align:left;">
              <select name="limite_registros" id="limite_registros" class="form-control selectpicker">
                <option value="" disabled selected>Seleccionar limite</option>
                <option value="51">50 / Registros</option>
                <option value="101">100 / Registros</option>
                <option value="201">200 / Registros</option>
                <option value="501">500 / Registros</option>
                <option value="1001">1000 / Registros</option>
              </select>
            </div>  

            <div id="global">
              <div id="tablaResultados">
                <section id="tabla_resultado"></section>
              </div>
            </div>
    
            <div class="form-group col-lg-12 col-md-6 col-xs-12">
              <nav aria-label="Page navigation example" style="text-align:right; margin-right:5px">
                <ul class="pagination">
                  <input type="button" title="Anterior" data-toggle='popover' data-trigger='hover' data-content='Pagina anterior' data-placement='top' class='btn btn-primary me-md-2' value="Anterior" id="anterior" name="anterior" onclick="paginaAnterior();">
                  <li class="page-item"><input title="Pagina" data-toggle='popover' data-trigger='hover' data-content='Pagina actual' data-placement='top' class='btn btn-primary me-md-2' type="submit" id="pagina" name="pagina" value="1" onclick="paginasClick(<?php echo $i;?>)"></li>
                  <input type="submit" title="Siguiente" data-toggle='popover' data-trigger='hover' data-content='Pagina siguiente' data-placement='top' class='btn btn-primary me-md-2' value="Siguiente" id="siguiente" name="siguiente" onclick="paginaSiguiente()">                
                </ul>
              </nav>
            </div>
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
                <input class="form-control" type="text" name="fmsi" id="fmsi" maxlength="100" placeholder="Fmsi">
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
              <div class="form-group col-lg-2 col-md-6 col-xs-12">
                
              </div> 
              <div class="form-group col-lg-12 col-md-6 col-xs-12 text-center">
                <label for="">Imagen:</label>
                <input class="form-control" type="file" name="imagen" id="imagen" style="cursor:pointer">
                <input type="hidden" name="imagenactual" id="imagenactual">
                <img src="" alt="" width="600px" height="320" id="imagenmuestra" class="img-thumbnail">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12 text-center">                
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12 text-center">
                <label for="">Código de barras:</label>
                <input class="form-control" type="text" name="barcode" id="barcode" placeholder="Código de barras" >
                <br>
                <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar</button>
                <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir</button>
                <div id="print" class="text-center">
                  <svg id="barras"></svg>
                </div>
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12 text-center">
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
    <!-- /.content -->
</div>
<textarea name="comment" id="comment" cols="5" rows="2" style="height: 15px; border:none; color:transparent;" value="5"></textarea>
<?php 
  require('ediciones.php')
?>
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