<?php
//activamos almacenamiento en el buffer
require "../config/Conexion.php";
require "../modelos/ingreso.php";
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';

if ($_SESSION['compras']==1) {

 ?>
<div class="content-wrapper" id="contenedor-principal">    
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">

          <div class="box-header with-border">
            <center><h4 class="box-title">Recepciones </h4></center>
            <div class="box-tools pull-right">
            </div>
            <div class="panel-body table-responsive" id="listadoregistros">
              <section>
                <div class="form-group col-lg-4 col-md-6 col-xs-12">
                  <button title="Registrar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Registrar nueva recepción' data-placement='right' class='btn btn-success' onclick="agregarRecepcion()"><i class="fa fa-plus-circle"></i> Agregar Nuevo</button>
                </div>
                <div class="form-group col-lg-4 col-md-6 col-xs-12">
                  <center><input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center>
                </div>
              </section>

              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <div class="loaderSearch">
                  <img src="../files/images/loader.gif" alt="" width="35px">
                </div>
              </div>            

              <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label>Fecha Inicio</label>
                <input type="date" class="form-control" name="fecha_inicio" id="fecha_inicio" value="">
              </div>

            <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <label>Fecha Fin</label>
              <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="">
            </div>

            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:left;"></div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:left;"></div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:left;">
              <select name="limite_registros" id="limite_registros" class="form-control selectpicker">
                <option value="" disabled selected>Seleccionar limite</option>                  
                <option value="5">50 / Registros</option>
                <option value="10">100 / Registros</option>
                <option value="20">200 / Registros</option>
                <option value="50">500 / Registros</option>
                <option value="100">1000 / Registros</option>
              </select>
            </div>

            <br><br><br>
            <div id="global">
              <div id="tablaResultados">
                <section id="tabla_resultado">                
                </section>
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

          <div class="box-header with-border" id="formularioregistros"> 
            <div class="panel-body table-responsive">   
              <form action="" name="formulario" id="formulario" method="POST">
                <div class="form-group col-lg-12 col-md-6 col-xs-12">
                  <center><h4 aling="center">Información del cliente</h4></center>
                </div>
                <div class="form-group col-lg-4 col-md-8 col-xs-12">
                <label for="">Proveedor <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                      <input class="form-control" type="hidden" name="idingreso" id="idingreso">
                      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>        
                      </select>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-xs-12" id="">
                  <label for="">Agregar proveedor</label><br>
                  <a data-toggle="modal" href="#agregarProveedor">
                    <button id="btnAgregarProveedor" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Proveedor</button>
                  </a>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-xs-12">
                <label for="">Fecha <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-calendar"></i></span>
                      <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
                  </div>      
                </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Tipo Comprobante <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-hand-o-right"></i></span>
                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                        <option value="Factura">Factura</option>
                      </select>
                  </div>
                </div>
                <div class="form-group col-lg-3 col-md-2 col-xs-6">
                <label for="">Serie <span class="text-danger">*</span></label>                  
                  <div class="input-group bootstrap-touchspin">
                    <span class="input-group-btn"></span>
                    <span class="input-group-addon bootstrap-touchspin-prefix"><i class="fa fa-keyboard-o"></i></span>
                      <input class="form-control" type="text" name="serie_comprobante" id="serie_comprobante" maxlength="50" placeholder="Serie" required>
                  </div>
                </div>
                <div class="form-group col-lg-3 col-md-2 col-xs-6">
                  <label>Impuesto <span class="text-danger">*</span></label>
                  <div class="input-group bootstrap-touchspin">                  
                    <span class="input-group-addon bootstrap-touchspin-prefix">$</span>
                    <input class="form-control" type="text" name="impuesto" id="impuesto">
                  </div>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" id="btnAgregarArt">
                  <a data-toggle="modal" href="#myModal" >
                    <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Articulos</button>
                  </a>
                </div>
                <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12" id="btnAgregarArticulosEdit">
                  <a data-toggle="modal" href="#myModalProductsEdit" >
                    <button id="btnAgregarArticulosEdit" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Add Articulos</button>
                  </a>
                </div>



                <div class="form-group col-lg-12 col-md-6 col-xs-12">
                    <center><h4 aling="center">Productos Recepción</h4></center>
                  </div>

                <div class="form-group col-lg-12 col-md-12 col-xs-12">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Opciones</th>
                      <th>Código</th>
                      <th>Clave</th>
                      <th>Fmsi</th>
                      <th>Descripción</th>
                      <th>Cantidad</th>
                      <th>Costo</th>
                      <th>Descuento</th>
                      <th>Subtotal</th>
                      <th>Acciones</th>
                    </thead>
                    <tfoot style="background-color:#A9D0F5">
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th>TOTAL</th>
                      <th><p id="total">$ 0.00</p><input type="hidden" name="total_compra" id="total_compra"></th>
                      <th></th>
                    </tfoot>
                    <tbody>                
                    </tbody>
                  </table>
                </div>

                <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
                  <button class="btn btn-info" onclick="salirForm()" type="button" id="btnRegresar"><i class="fa fa-arrow-circle-left"></i> Regresar</button>
                  <?php 
                    require('loader.php');
                  ?>
                </div>
              </form>
          </div>
          
        </div>
      </div>
    </div>  
  </section>
</div>

<?php 
  require("ediciones.php");
?>

   <!--Modal registrar nuevo proveedor-->
  <div class="modal fade" id="agregarProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 50% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header">
          <h4 class="modal-title">Agregar Proveedor</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>          
        </div>
        <div class="modal-body">
        <div class="modal-body">
          <div class="panel-body table-responsive">
          <form action="" name="formularioProve" id="formularioProve" method="POST">
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Nombre</label>
              <input class="form-control" type="hidden" name="idpersona" id="idpersona">
              <input class="form-control" type="hidden" name="tipo_persona" id="tipo_persona" value="Proveedor">
              <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del proveedor" required>
            </div>            
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Direccion</label>
              <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" placeholder="Direccion">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Telefono</label>
              <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Número de Telefono">
            </div>
                <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Email</label>
              <input class="form-control" type="email" name="email" id="email" maxlength="50" placeholder="Email">
            </div>
            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">RFC</label>
              <input class="form-control" type="text" name="rfc" id="rfc" maxlength="50" placeholder="RFC">
            </div>            
          </form>
          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardaryeditarProveedor()">Guardar</button>
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
          </div>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
   <!-- fin Modal-->

  <!--Modal agregar producto a la venta-->
  <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100% !important;">
      <div class="modal-content" style="border-radius: 20px;">     
        <div class="modal-header">
          <h4 class="modal-title">Seleccione un Articulo</h4>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>          
        </div>
        <div class="modal-body">
        <div class="modal-body">        
          <div class="panel-body table-responsive">   

          <div class="form-group col-lg-4 col-md-8 col-xs-12">
          </div>      

          <div class="form-group col-lg-4 col-md-8 col-xs-12">
            <section>            
              <center><input class="form-control me-2" type="text" name="busquedaProduct" id="busquedaProduct" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center><br><br>
            </section>
          </div>     
          <div class="form-group col-lg-4 col-md-8 col-xs-12" style="text-align:right">              
            <a data-toggle="modal" href="#agregarProducto">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarModal()"><span class="fa fa-plus"></span>Registrar Producto</button>
            </a>
          </div>       
            <div id="global">
              <div id="tablaResultadosModal">
                <section id="tabla_resultadoProducto"> </section>
              </div>
            </div>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin Modal-->

  <!--Modal registrar nuevo producto-->
  <div class="modal fade" id="agregarProducto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 80% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header">
          <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Registrar Producto</h4>
        </div>
        <div class="modal-body">
        <div class="modal-body">
          <div class="panel-body table-responsive">

          <form action="" name="formularioProduct" id="formularioProduct" method="POST">
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
              <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required>

              <?php 
              
                $sql="SELECT * FROM categoria";
                $result = ejecutarConsulta($sql);

                while($row = $result->fetch_assoc()) {
                  echo "<option value='$row[idcategoria]'>$row[nombre]</option>";
                }
              
              ?>
              </select>
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
                <?php 
                  $sql="SELECT * FROM persona WHERE tipo_persona='Proveedor'";
                  $result = ejecutarConsulta($sql);

                  while($row = $result->fetch_assoc()) {
                    echo "<option value='$row[idpersona]'>$row[nombre]</option>";
                  }
              
                ?>
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

            <div class="form-group col-lg-6 col-md-6 col-xs-12">
              <label for="">Codigo barras:</label>
              <input class="form-control" type="text" name="barcode" id="barcode" placeholder="Código del producto" >              
            </div>            
          </form>

          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardaryeditarProducto()">Guardar</button>
            <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
          </div>
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>
  <!-- fin Modal-->

<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/recepciones.js"></script>
 <?php 
}

ob_end_flush();
  ?>