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
    <div class="content-wrapper" id="contenedor-principal">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
          <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">
          <center>
            <div class="box-header with-border">
                <h4 class="box-title">Productos </h4>
            </div>
          </center>                    

          <div class="table-responsive" id="table-search">
            <section>
              <div class="box-header with-border col-md-10">  
                <div class="btn-group" role="group">
                  <button title="Registrar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Registrar nueva articulo' data-placement='bottom' class='btn btn-success' title="Agregar nuevo articulo" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar Nuevo</button></h1>
                </div>                                                                      
                <div class="btn-group" role="group">
                  <button title="Exportar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Exportar articulos a Excel' data-placement='right' class='btn btn-success' title="Agregar nuevo articulo" onclick="exportarExcel()"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
                </div>                          
                <div class="btn-group" role="group" id="movimientos_caja">
                  <a data-toggle="modal" href="#cambiarPreciosProductos">
                    <button title="Cambiar precios" id="btnCanbiarPrecios" data-toggle='popover' data-trigger='hover' data-content='Cambiar precios productos' data-placement='right' class='btn btn-primary' title="Cambiar precios"><i class="fa fa-usd"></i> Cambiar precios</button>
                  </a>
                </div>
                <div class="btn-group" role="group" id="movimientos_caja">
                  <div class="form-group col-lg-12 col-md-2 col-xs-6" id="divImpuesto"></div>
                  <div class="form-group col-lg-12 col-md-2 col-xs-6" id="divImpuesto"></div>                  
                </div>
                <div class="btn-group" role="group" id="movimientos_caja">
                  <form onsubmit="event.preventDefault();">                                     
                      <div class="form-group col-lg-12 col-md-2 col-xs-6" id="divImpuesto">
                          <div class="form-group">
                              <div class="input-group bootstrap-touchspin">
                                  <span class="input-group-btn">                                
                                  </span><span class="input-group-addon bootstrap-touchspin-prefix">Buscar: </span>                                                                            
                                  <center><input class="form-control" type="input" name="busqueda" id="busqueda"></input></center>
                                  <span class="input-group-addon bootstrap-touchspin-postfix"><i class="fa fa-search pull-right" aria-hidden="true"></i></span>                                                    
                              </div>
                          </div>                                           
                      </div>                     
                  </form>    
                </div>
              </div>            
                <div class="btn-group" role="group" id="movimientos_caja">
                  <select name="limite_registros" id="limite_registros" class="form-control selectpicker">
                    <option value="" disabled selected>Seleccionar limite</option>
                    <option value="51">50 / Registros</option>
                    <option value="101">100 / Registros</option>
                    <option value="201">200 / Registros</option>
                    <option value="501">500 / Registros</option>
                    <option value="1001">1000 / Registros</option>
                  </select>                 
              </div>                  
            </section>
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <div class="loaderSearch">
                <img src="../files/images/loader.gif" alt="" width="35px">
              </div>
            </div>                            
                        
            <div id="global" class="table-responsive">
              <div id="tablaResultados">
                <section id="tabla_resultado"></section>
              </div>
            </div>

            <!--<div class="table-responsive-lg">
              <section id="tabla_resultado"></section>
            </div>-->
    
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

          <div class="panel-body table-responsive" id="formularioregistros">  
            <form action="" name="formulario" id="formulario" method="POST">
              <!--CLAVE DEL PRODUCTO-->    
              <div class="form-group col-lg-4 col-md-6 col-xs-12">                
                <label for="">Clave <span class="text-danger">*</span></label>
                <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
                <div class="input-group bootstrap-touchspin">
                  <span class="input-group-btn"></span>
                  <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                    <input class="form-control" type="text" name="codigo" id="codigo" maxlength="100" placeholder="Clave" required>     
                  <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span><span class="input-group-btn"></span>
                </div>                               
              </div>    

              <!--CLAVE DEL PRODUCTO-->
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Fmsi</label>                
                <div class="input-group bootstrap-touchspin">
                  <span class="input-group-btn"></span>
                  <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                    <input class="form-control" type="text" name="fmsi" id="fmsi" maxlength="100" placeholder="Fmsi">
                </div>
              </div>


              <!--CATEGORIA DEL PRODUCTO-->
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Categoria <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">                    
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                      <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required></select>
                  </div>        
              </div>

              <!--MARCA DEL PRODUCTO-->
              <!--<div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Marca <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                      <input class="form-control" type="text" name="marca" id="marca" maxlength="100" placeholder="Marca" required>
                  </div>
              </div>-->

              <!--MARCA DEL PRODUCTO-->
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Marca <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">                    
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                      <select name="marca" id="marca" class="form-control selectpicker" data-Live-search="true" required></select>
                  </div>
              </div>

              <!--PROVEEDOR DEL PRODUCTO-->
              <div class="form-group col-lg-6 col-md-12 col-xs-12">
                  <label for="">Proveedor <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
                      </select>
                  </div>  
              </div>    

              <div class="form-group col-lg-12 col-md-6 col-xs-12">
                <label for="">Descripción <span class="text-danger">*</span></label>
                <div class="input-group bootstrap-touchspin">
                  <span class="input-group-btn"></span>
                  <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-file-text-o"></i></span>
                    <textarea class="form-control" type="text" name="descripcion" id="descripcion" maxlength="500" placeholder="Desscripciòn" required ></textarea>
                </div>
              </div> 

              <!--UNIDADES DEL PRODUCTO-->
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Unidades <span class="text-danger">*</span></label>                  
                <div class="input-group bootstrap-touchspin">
                  <span class="input-group-btn"></span>
                  <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                    <input class="form-control" type="text" name="unidades" id="unidades" maxlength="50" placeholder="Unidades (JUEGO / SET / PIEZA)" required>
                </div>
              </div>

              <!--STOCK DEL PRODUCTO-->
              <div class="form-group col-lg-2 col-md-6 col-xs-12">                
                <label>Stock <span class="text-danger">*</span></label>
                    <div class="input-group bootstrap-touchspin"><span class="input-group-btn">
                      <button class="btn btn-default bootstrap-touchspin-down" type="button">-</button></span>
                        <input class="form-control" type="number" name="stock" id="stock"  required>
                          <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>
                          <span class="input-group-btn">
                            <button class="btn btn-default bootstrap-touchspin-up" type="button">+</button>
                          </span>
                    </div>                
              </div>   

              <div class="form-group col-lg-2 col-md-6 col-xs-12">                
                <label>Stock Ideal<span class="text-danger">*</span></label>
                    <div class="input-group bootstrap-touchspin"><span class="input-group-btn">
                      <button class="btn btn-default bootstrap-touchspin-down" type="button">-</button></span>
                        <input class="form-control" type="number" name="stock_ideal" id="stock_ideal"  required>
                          <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>
                          <span class="input-group-btn">
                            <button class="btn btn-default bootstrap-touchspin-up" type="button">+</button>
                          </span>
                    </div>                
              </div>

              <!--PASILLO DEL PRODUCTO-->
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                  <label for="">Pasillo <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">                    
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                      <input class="form-control" type="text" name="pasillo" id="pasillo" maxlength="50" placeholder="Pasillo / Corredor" required>
                </div>
              </div>

              <div class="form-group col-lg-12 col-md-6 col-xs-12"></div> 

              <!--COSTOS DEL PRODUCTO-->
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label>Precio Costo <span class="text-danger">*</span></label>
                <div class="input-group bootstrap-touchspin">                  
                  <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                    <input class="form-control" type="number" step="any" name="costo" id="costo"  required>
                  <span class="input-group-addon bootstrap-touchspin-postfix" style="display: none;"></span>                  
                </div>    
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label>Precio Público <span class="text-danger">*</span></label>
                  <div class="input-group bootstrap-touchspin">                  
                    <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                      <input class="form-control" type="text" step="any" name="publico" id="publico" placeholder="$">
                    <span class="input-group-addon bootstrap-touchspin-prefix" id="utilidadPublico">%                      
                    </span>
                  </div>  
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">  
                <label>Precio Taller <span class="text-danger">*</span></label>
                  <div class="input-group bootstrap-touchspin">                  
                    <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                      <input class="form-control" type="text" step="any" name="taller" id="taller" placeholder="$">
                      <span class="input-group-addon bootstrap-touchspin-prefix" id="utilidadTaller">%                      
                      </span>
                  </div>
              </div>
             
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label>Precio Credito Taller <span class="text-danger">*</span></label>
                  <div class="input-group bootstrap-touchspin">                  
                    <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                      <input class="form-control" type="text" step="any" name="credito_taller" id="credito_taller" placeholder="$">
                      <span class="input-group-addon bootstrap-touchspin-prefix" id="utilidadCreditoTaller">%                      
                      </span>
                  </div>
              </div>              
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label>Precio Mayoreo <span class="text-danger">*</span></label>
                  <div class="input-group bootstrap-touchspin">                  
                    <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                      <input class="form-control" type="text" step="any" name="mayoreo" id="mayoreo" placeholder="$">
                      <span class="input-group-addon bootstrap-touchspin-prefix" id="utilidadMayoreo">%                      
                      </span>
                  </div>
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">   
                <label for="">Inventariable? <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                    <select name="bandera_inventariable" id="bandera_inventariable" class="form-control selectpicker" required>
                      <option value="0">SI</option>
                      <option value="1">NO</option>
                    </select>
                  </div>
              </div>  
              <div class="form-group col-lg-6 col-md-6 col-xs-12 text-center">
                <label for="">Imagen del Producto </label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-camera-retro"></i></span>
                    <input class="form-control" type="file" name="imagen" id="imagen" style="cursor:pointer">
                    <input type="hidden" name="imagenactual" id="imagenactual">                    
                  </div>
              </div>                  

              <div class="form-group col-lg-6 col-md-6 col-xs-12 text-center">
                <label for="">Dibujo Ténico </label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-camera-retro"></i></span>
                    <input class="form-control" type="file" name="dibujoTecnico" id="dibujoTecnico" style="cursor:pointer">
                    <input type="hidden" name="dibujoactual" id="dibujoactual">                    
                  </div>
              </div>       
              <div class="form-group col-lg-6 col-md-6 col-xs-12 text-center">            
                <img src="" alt="" width="600px" height="320" id="imagenmuestra" class="img-thumbnail">
              </div>       
              <div class="form-group col-lg-6 col-md-6 col-xs-12 text-center">
                <img src="" alt="" width="600px" height="320" id="dibujoMuestra" class="img-thumbnail">
              </div>
                  
              
              <div class="form-group col-lg-12 col-md-6 col-xs-12 text-center"></div>                     
               
                              
              <div class="form-group col-lg-4 col-md-6 col-xs-12 text-center">
                <label for="">Código de Barras </label>                  
                <div class="input-group bootstrap-touchspin">
                  <span class="input-group-btn"></span>
                  <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-barcode"></i></span>
                    <input class="form-control" type="text" name="barcode" id="barcode" placeholder="Código de barras" >                    
                </div>
              </div>
              <br>                        
              
              <div class="form-group col-lg-4 col-md-6 col-xs-12 text-center">
                <button class="btn btn-success" type="button" onclick="generarbarcode()">Generar Código de Barras</button>
                <button class="btn btn-info" type="button" onclick="imprimir()">Imprimir Código de Barras</button>               
              </div>       
                            
              <div class="form-group col-lg-12 col-md-6 col-xs-12 text-center">
                <div id="print" class="text-center">
                  <svg id="barras"></svg>
                </div>
              </div> 

              <div class="form-group col-lg-4 col-md-6 col-xs-12"><h1>&nbsp;</h1></div>
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
<!--<textarea name="comment" id="comment" cols="5" rows="2" style="height: 15px; border:none; color:transparent;" value="5"></textarea>-->
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
 <script src="scripts/articulosB1.js"></script>
 <?php 
}

ob_end_flush();
  ?>