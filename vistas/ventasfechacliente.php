<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['consultav']==1) {

 ?>
    <div class="content-wrapper">
    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="row">
        <div class="col-md-12">
      <div class="box">
<div class="box-header with-border">
  <h1 class="box-title">Consulta de Ventas por Fecha <button class="btn btn-success" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
  <div class="box-tools pull-right">
    
  </div>     
          <div class="panel-body" style="height: 400px;" id="formularioregistros">
            <form action="" name="formulario" id="formulario" method="POST">
              <div class="form-group col-lg-4 col-md-8 col-xs-12">
                <label for="">Cliente(*):</label>
                <input class="form-control" type="hidden" name="idventa" id="idventa">
                <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true" required>                  
                </select>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-xs-12">
                <label for="">Agregar Cliente</label><br>
                  <a data-toggle="modal" href="#agregarCliente">
                    <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Cliente</button>
                  </a>
              </div>

              <div class="form-group col-lg-4 col-md-4 col-xs-12">
                <label for="">Fecha(*): </label>
                <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
              </div>  

              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Tipo Comprobante(*): </label>
                  <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                    <option value="Ticket">Ticket</option>
                  </select>
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Factura ? </label>
                  <select name="factura" id="factura" class="form-control selectpicker" required>
                    <option value="c/f">Con factura</option>
                    <option value="s/f">Sin factura</option>
                  </select>
              </div>
              <div class="form-group col-lg-2 col-md-2 col-xs-6">
                <label for="">Impuesto: </label>
                <input class="form-control" type="text" name="impuesto" id="impuesto">
              </div>
              <div class="form-group col-lg-8 col-md-6 col-xs-12">
                <label for="">Forma de pago(*): </label>
                  <select name="forma_pago" id="forma_pago" class="form-control selectpicker" required>                    
                    <option value="Cheque">CHEQUE</option>
                    <option value="Tarjeta">TARJETA</option>
                    <option value="Efectivo">EFECTIVO</option>
                    <option value="Deposito">DEPÓSITO</option>
                    <option value="Tarjeta">TARJETA</option>
                    <option value="Transferencia">TRASFERENCIA</option>
                    <option value="American Express">AMERICAN EXPRESS</option>
                  </select>
              </div>
              <div class="form-group col-lg-6 col-md-3 col-sm-6 col-xs-12">
                <a data-toggle="modal" href="#myModal">
                  <button id="btnAgregarArt" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Articulos</button>
                </a>
              </div>
              <div class="form-group col-lg-12 col-md-12 col-xs-12">         
                <div class="panel-body table-responsive">
                <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                  <thead style="background-color:#A9D0F5">
                    <th>Opciones</th>
                    <th>Código</th>
                    <th>Clave</th>
                    <th>Fmsi</th>
                    <th>Descripción</th>
                    <th>Cantidad</th>
                    <th>Precio Venta</th>
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
                    <th><h5 id="total">$ 0.00</h5><input type="hidden" name="total_venta" id="total_venta"></th>
                  </tfoot>
                  <tbody>                
                  </tbody>
                </table>
                </div>
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
  <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
    <thead>
      <th>Acciones</th>  
      <th>Folio</th>
      <th>Salida</th>
      <th>Cliente</th>      
      <th>Vendedor</th>
      <th>Forma pago</th>
      <th>Falta pagar</th>
      <th>Total</th>
      <th>Estado</th>
    </thead>
    <tbody>
    </tbody>
    <tfoot>
      <th>Acciones</th>  
      <th>Folio</th>
      <th>Salida</th>
      <th>Cliente</th>      
      <th>Vendedor</th>
      <th>Forma pago</th>
      <th>Falta pagar</th>
      <th>Total</th>
      <th>Estado</th>
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
 <script src="scripts/ventasfechaclientes.js"></script>
 <?php 
}

ob_end_flush();
  ?>

