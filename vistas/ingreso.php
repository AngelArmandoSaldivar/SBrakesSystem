<?php
//activamos almacenamiento en el buffer
require "../config/Conexion.php";
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';

if ($_SESSION['compras']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Recepciones <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div>
</div>
<!--box-header-->
<!--centro-->
<div class="panel-body table-responsive" id="listadoregistros">  
    <section>
      <center><input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px"></center><br><br>
    </section>            
    <section id="tabla_resultado"></section>
</div>
<div class="panel-body" style="height: 400px;" id="formularioregistros">
  <form action="" name="formulario" id="formulario" method="POST">    
    <div class="form-group col-lg-4 col-md-8 col-xs-12">
      <label for="">Proveedor(*):</label>
      <input class="form-control" type="hidden" name="idingreso" id="idingreso">
      <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>        
      </select>
    </div>
    <div class="form-group col-lg-4 col-md-4 col-xs-12">
    <label for="">Agregar proveedor</label><br>
      <a data-toggle="modal" href="#agregarProveedor">
        <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Proveedor</button>
      </a>
    </div>
      <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <label for="">Fecha(*): </label>
      <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
    </div>
     <div class="form-group col-lg-6 col-md-6 col-xs-12">
      <label for="">Tipo Comprobante(*): </label>
     <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
       <option value="Factura">Factura</option>
     </select>
    </div>
     <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="">Serie: </label>
      <input class="form-control" type="text" name="serie_comprobante" id="serie_comprobante" maxlength="7" placeholder="Serie" required>
    </div>
    <div class="form-group col-lg-2 col-md-2 col-xs-6">
      <label for="">Impuesto: </label>
      <input class="form-control" type="text" name="impuesto" id="impuesto">
    </div>
    <div class="form-group col-lg-3 col-md-3 col-sm-6 col-xs-12">
     <a data-toggle="modal" href="#myModal" >
       <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Articulos</button>
     </a>
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
  </thead>
  <tfoot>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th></th>
    <th>TOTAL</th>
    <th><h5 id="total">$ 0.00</h5><input type="hidden" name="total_compra" id="total_compra"></th>
  </tfoot>
  <tbody>                
  </tbody>
</table>
    </div>
    <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
      <button class="btn btn-danger" onclick="cancelarform()" type="button" id="btnCancelar"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
      <?php 
        require('loader.php');
      ?>
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

   <!--Modal registrar nuevo proveedor-->
  <div class="modal fade" id="agregarProveedor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width: 50% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Agregar Proveedor</h4>
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
    <div class="modal-dialog" style="width: 95% !important;">
      <div class="modal-content" style="border-radius: 20px;">     
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Seleccione un Articulo</h4>
        </div>
        <div class="modal-body">
        <div class="modal-body">        
          <div class="panel-body table-responsive">            
            <div class="form-group col-lg-10 col-md-8 col-xs-12">
              <section>            
                <center><input class="form-control me-2" type="text" name="busquedaProduct" id="busquedaProduct" placeholder="Buscar..." style="width:250px"></center><br><br>
              </section>
            </div>     
            <div class="form-group col-lg-2 col-md-8 col-xs-12">              
              <a data-toggle="modal" href="#agregarProducto">
                <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Registrar Producto</button>
              </a>
            </div>       
            <section id="tabla_resultadoProducto"> </section>
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
 <script src="scripts/ingresos.js"></script>
 <?php 
}

ob_end_flush();
  ?>

