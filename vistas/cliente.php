<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['ventas']==1) {
 ?>
<div class="content-wrapper">    
  <section class="content">      
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h1 class="box-title">CLIENTES <button class="btn btn-success" onclick="agregarCliente()" id="btnagregar"><i class="fa fa-plus-circle"></i>Agregar</button></h1>
              <div class="box-tools pull-right">
              </div>
          </div>
          <div class="panel-body table-responsive" id="listadoregistros">
            <section>
              <center><input class="form-control me-2" type="text" name="busqueda" id="busqueda" placeholder="Buscar..." style="width:250px; border-radius: 16px; box-shadow: 5px 5px 8px #3300ff99;"></center>
            </section><br><br>
            <section id="tabla_resultado"></section>
          </div>
          <div class="panel-body" id="formularioregistros">
            <form action="" name="formulario" id="formulario" method="POST">
              
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Nombre</label>
                <input class="form-control" type="hidden" name="idpersona" id="idpersona">
                <input class="form-control" type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del cliente" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Direccion</label>
                <input class="form-control" type="text" name="direccion" id="direccion" maxlength="70" placeholder="Direccion">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Telefono</label>
                <input class="form-control" type="text" name="telefono" id="telefono" maxlength="20" placeholder="Número de Telefono">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Telefono Local</label>
                <input class="form-control" type="text" name="telefono_local" id="telefono_local" maxlength="20" placeholder="Número de Telefono">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Email</label>
                <input class="form-control" type="email" name="email" id="email" maxlength="50" placeholder="Email">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">RFC</label>
                <input class="form-control" type="text" name="rfc" id="rfc" placeholder="RFC">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Días de crédito</label>
                <input class="form-control" type="number" name="credito" id="credito" min="0" placeholder="Crédito">
              </div>
              <div class="form-group col-lg-4 col-md-12 col-xs-12">
                <label for="">Tipo de precio(*): </label>
                <select name="tipo_precio" id="tipo_precio" class="form-control selectpicker" required>     
                  <option value="publico">Publico</option>
                  <option value="taller">Taller</option>
                  <option value="credito_taller">Crédito Taller</option>
                  <option value="mayoreo">Mayoreo</option>
                </select>
              </div>
              <div class="form-group col-lg-6 col-md-3 col-sm-6 col-xs-12" id="btnAgregarAuto">
                <a data-toggle="modal" href="#addAuto">
                  <button id="btnAgregarAut" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Auto</button>
                </a>
              </div>
              <div class="form-group col-lg-12 col-md-4 col-xs-12">
                <label for=""><h3>Detalle Auto </h3></label>
              </div>   
              <div class="form-group col-lg-12 col-md-12 col-xs-12">         
                <div class="panel-body table-responsive">
                  <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                    <thead style="background-color:#A9D0F5">
                      <th>Acciones</th>
                      <th>No.</th>
                      <th>Placas</th>
                      <th>Marca</th>
                      <th>Modelo</th>
                      <th>Año</th>
                      <th>Color</th>
                      <th>Kms</th>                                     
                    </thead>
                    <tfoot>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>
                      <th></th>                   
                    </tfoot>
                    <tbody>                
                    </tbody>
                  </table>
                </div>
              </div>
              <br><br>
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i>  Guardar</button>
                <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<!--AÑADIR AUTO-->
<div class="modal fade" id="addAuto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" align="center">
  <div class="modal-dialog" style="width: 75% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Agregar automovil</h4>
      </div>
      <div class="modal-body">
        <div class="panel-body table-responsive">
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Placas</label>
            <input class="form-control" type="text" name="placas" id="placas" placeholder="Placas" value="" required>
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Marca</label>
            <input class="form-control" type="text" name="marca" id="marca" placeholder="Marca" value="" required>
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Modelo</label>
            <input class="form-control" type="text" name="modelo" id="modelo" placeholder="Modelo" value="" required>
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Año</label>
            <input class="form-control" type="text" name="ano" id="ano" placeholder="Año" value="" required>
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Color</label>
            <input class="form-control" type="text" name="color" id="color" placeholder="Color" value="" required>
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Kms</label>
            <input class="form-control" type="number" name="kms" id="kms" min="0" placeholder="Kms" value="" required>
          </div>
          <div class="form-group col-lg-2 col-md-3 col-sm-6 col-xs-12" id="">
            <button id="btnAgregar" type="button" class="btn btn-primary" onclick="agregarDetalleAuto(placas, marca, modelo, ano, color, kms)" data-dismiss="modal"><span class="fa fa-plus"></span>Agregar</button>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal" onclick="limpiarFormAuto()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<?php 
}else{
 require 'noacceso.php'; 
}
require 'footer.php';
 ?>
 <script src="scripts/clientesB1.js"></script>
 <?php 
}

ob_end_flush();
  ?>
