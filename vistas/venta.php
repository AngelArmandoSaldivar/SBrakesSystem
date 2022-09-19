<?php
//activamos almacenamiento en el buffer
require "../config/Conexion.php";
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
  $idsucursal = $_SESSION['idsucursal'];
}else{


require 'header.php';

if ($_SESSION['ventas']==1) {

 ?>
<div class="content-wrapper">
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">
        
          <div class="box-header with-border">    
              <center><h4 class="box-title">Ventas </h4></center>
              <div class="box-tools pull-right"></div>
              <div class="panel-body table-responsive" id="listadoregistros">       
              
              <section>
                <div class="form-group col-lg-2 col-md-6 col-xs-12">
                  <button title="Registrar" id="btnagregarservicio" data-toggle='popover' data-trigger='hover' data-content='Registrar nueva venta' data-placement='right' class='btn btn-success' onclick="crearVenta(true)"><i class="fa fa-plus-circle"></i> Agregar Nuevo</button>
                </div>
                <div class="form-group col-lg-2 col-md-6 col-xs-12">
                  <button title="Exportar" id="" data-toggle='popover' data-trigger='hover' data-content='Exportar a excel' data-placement='right' class='btn btn-success' title="" onclick="exportarExcel()"><i class="fa fa-file-excel-o"></i> Exportar a Excel</button>
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

            <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
              <label>Fecha Fin</label>
              <input type="date" class="form-control" name="fecha_fin" id="fecha_fin" value="">
            </div>
            <div class="form-group col-lg-2 col-md-6 col-xs-12" style="text-align:left;">
              <label>Limte de registros</label>
              <select name="limite_registros" id="limite_registros" class="form-control selectpicker">
                <option value="" disabled selected>Seleccionar limite</option>                  
                <option value="50">50 / Registros</option>
                <option value="100">100 / Registros</option>
                <option value="200">200 / Registros</option>
                <option value="500">500 / Registros</option>
                <option value="1000">1000 / Registros</option>
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

          <div class="box-header with-border" id="formularioregistros"> 
            <div class="panel-body table-responsive">   
              <form action="" name="formulario" id="formulario" method="POST">
                <div class="list-group-item col-lg-12" id="infoCliente">
                  <div class="form-group col-lg-12 col-md-6 col-xs-12">
                    <center><h4 aling="center">Información del cliente</h4></center>
                  </div>
                  <div class="form-group col-lg-2 col-md-8 col-xs-12">
                    <label for="">Cliente(*):</label>
                    <input class="form-control" type="hidden" name="idventa" id="idventa">                 
                    <select name="idcliente" id="idcliente" class="form-control selectpicker" data-live-search="true">                   
                      <option value="" disabled selected>Seleccionar cliente</option>
                    </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-8 col-xs-12" id="buscadorCotizador">
                    <label for="">Folio Cotización:</label>
                      <input type="number" class="form-control form-control-lg" name="busquedaCotizacion" id="busquedaCotizacion" placeholder="Buscar...">
                  </div>
                  <div class="form-group col-lg-2 col-md-8 col-xs-12">
                    <label for=""></label>
                    <div class="input-group">
                      <button type="button" id="btnBuscadorCotizacion" class="btn btn-primary mb-2" onclick="buscarCotizacion()"><i class="fa fa-search"></i> Buscar</button>
                    </div>
                  </div>
                  <div class="loader form-group col-lg-1 col-md-8 col-xs-12" style="margin-top: 15px;">
                    <img src="../files/images/loader.gif" alt="" width="50px;">
                  </div>
                  <div class="form-group col-lg-2 col-md-4 col-xs-12" id="addCliente">
                    <label for="">Agregar Cliente</label><br>
                      <a data-toggle="modal" href="#agregarCliente">
                        <button id="addCliente" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Cliente</button>
                      </a>
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">RFC: </label>
                    <input class="form-control" type="text" name="rfc" id="rfc">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Dirección: </label>
                    <input class="form-control" type="text" name="direccion" id="direccion">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Tipo de precio: </label>
                    <input class="form-control" type="text" name="tipoPrecio" id="tipoPrecio">
                  </div>
                  <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Email: </label>
                    <input class="form-control" type="text" name="email" id="email">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Número móvil: </label>
                    <input class="form-control" type="text" name="telefono" id="telefono">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Telefono Local: </label>
                    <input class="form-control" type="text" name="telefono_local" id="telefono_local">
                  </div>
                  <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
                    <label for="">Días Crédito: </label>
                    <input class="form-control" type="text" name="credito" id="credito">
                  </div>
                  <div class="form-group col-lg-2 col-md-4 col-xs-12" id="editarDetalleServicio"> 
                    <label for="">Editar: </label><br>
                      <a data-toggle="modal" href="#editarDetalleVenta">                   
                        <button class="btn btn-primary" type="submit" id="" onclick="mostrarDetalleVentaEdit()">Editar</button>
                      </a>                   
                  </div>
                </div>
                <div class="col-lg-12" id="detalleAutoDivider"><hr class="mt-5 mb-3"  style="width:80%"/></div>

                <div class="list-group-item col-lg-12" id="divDetallesVenta">
                  <div class="form-group col-lg-12 col-md-6 col-xs-12">
                    <center><h4 aling="center">Detalles venta</h4></center>
                  </div>
                  <div class="form-group col-lg-2 col-md-4 col-xs-12">
                    <label for="">Entrada(*): </label>
                    <input class="form-control" type="date" name="fecha_entrada" id="fecha_entrada" required>
                  </div>  
                  <div class="form-group col-lg-2 col-md-4 col-xs-12">
                    <label for="">Salida: </label>
                    <input class="form-control" type="date" name="fecha_salida" id="fecha_salida">
                  </div>  
                  <div class="form-group col-lg-2 col-md-6 col-xs-12">
                    <label for="">Tipo Comprobante(*): </label>
                      <select name="tipo_comprobante" id="tipo_comprobante" class="form-control selectpicker" required>
                        <option value="Ticket">Ticket</option>
                      </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-6 col-xs-12">
                    <label for="">Tipo de precio </label>
                      <select name="tipo_precio" id="tipo_precio" class="form-control selectpicker">
                      <option value="" disabled selected>Tipo Precio</option>                    
                        <option value="publico">Publico / Mostrador</option>
                        <option value="taller">Taller</option>
                        <option value="credito_taller">Credito Taller</option>
                        <option value="mayoreo">Mayoreo</option>
                      </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-6 col-xs-12">
                    <label for="">Remisionar </label>
                      <select name="remision" id="remision" class="form-control selectpicker">                    
                        <option value="1">Remisionar</option>
                        <option value="0">No remisionar</option>
                      </select>
                  </div>
                  <div class="form-group col-lg-2 col-md-4 col-xs-12" id="estatus">
                    <label for="">Estatus(*): </label>
                    <input class="form-control" type="text" name="estado" id="estado">
                  </div>
                </div>
                <div class="col-lg-12" id="detalleAutoDivider"><hr class="mt-5 mb-3"  style="width:80%"/></div>
                
                <div id="detalle_cobro">
                  <div class="list-group-item col-lg-12" id="infoCliente">
                    <div class="form-group col-lg-12 col-md-6 col-xs-12">
                      <center><h4 aling="center">Detalle cobro</h4></center>
                    </div>              
                    <div class="form-group col-lg-12 col-md-4 col-xs-12">
                      <a data-toggle="modal" href="#modalAddCobro">
                        <button class="btn btn-primary" type="submit" id="btnAddPago" onclick="infoPago()"> + </i>  Añadir método de pago</button>
                      </a>
                    </div>
                    <div class="form-group col-lg-12 col-md-12 col-xs-12">
                      <div class="panel-body table-responsive">
                        <table id="detallesPagos" class="table table-striped table-bordered table-condensed table-hover">
                          <thead style="background-color:#A9D0F5; font-size: 12px;">
                            <th>Opciones</th>
                            <th>ID PAGO</th>
                            <th>Importe</th>
                            <th>Método pago</th>
                            <th>Banco</th>
                            <th>Referencia</th>                        
                            <th>Acciones</th>
                          </thead>                      
                          <tbody>                
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12" id="detalleAutoDivider"><hr class="mt-5 mb-3"  style="width:80%"/></div>

                <div class="list-group-item col-lg-12" id="infoCliente">
                  <div class="form-group col-lg-6 col-md-3 col-sm-6 col-xs-12" id="btnAgregarArticulo">
                    <a data-toggle="modal" href="#myModal">
                      <button id="btnAgregarArticulo" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Articulos</button>
                    </a>
                  </div>
                  <div class="form-group col-lg-8 col-md-3 col-sm-6 col-xs-12" id="btnAgregarArticulosEdit">
                    <a data-toggle="modal" href="#myModalProductsEdit">
                      <button id="btnAgregarArticulosEdit" type="button" class="btn btn-primary"><span class="fa fa-plus"></span>Agregar Articulos</button>
                    </a>
                  </div>
                  <div class="form-group col-lg-12 col-md-6 col-xs-12">
                    <center><h4 aling="center">Productos venta</h4></center>
                  </div> 
                  <div class="form-group col-lg-12 col-md-12 col-xs-12">
                    <div class="panel-body table-responsive">
                      <table id="detalles" class="table table-striped table-bordered table-condensed table-hover">
                        <thead style="background-color:#A9D0F5; font-size: 12px;">
                          <th>Opciones</th>                        
                          <th>Clave</th>
                          <th>Fmsi</th>
                          <th>Marca</th>
                          <th>Descripción</th>
                          <th>Cantidad</th>
                          <th>Precio Venta</th>
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
                          <th><p id="total">$ 0.00</p><input type="hidden" name="total_venta" id="total_venta"></th>
                          <th></th>
                        </tfoot>
                        <tbody>                
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="col-lg-12" id="detalleAutoDivider"><hr class="mt-5 mb-3"  style="width:80%"/></div>
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
    </div>
  </section>
</div>

<?php 
  require("ediciones.php");
?>

  <!--Modal-->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 95% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Seleccione un Articulo</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="panel-body table-responsive">

        <div class="form-group col-lg-4 col-md-8 col-xs-12">              
            <a data-toggle="modal" href="#agregarProducto">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarModal()"><span class="fa fa-plus"></span> Registrar Producto</button>
            </a>
          </div>

          <div class="form-group col-lg-4 col-md-8 col-xs-12">
            <section>            
              <center><input class="form-control me-2" type="text" name="busquedaProduct" id="busquedaProduct" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center><br><br>
            </section>
          </div>          

          <div class="form-group col-lg-4 col-md-8 col-xs-12" style="text-align:right">              
            <a data-toggle="modal" href="#agregarProductoAlmacen">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarModal()"><span class="fa fa-search-plus"></span> Buscar en otro almacen</button>
            </a>
          </div>
          <div id="global">
            <div id="tablaResultadosModal">
              <section id="tabla_resultadoProducto"> </section>
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

  <!--Modal registrar nuevo cliente-->
  <div class="modal fade" id="agregarCliente" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
      <div class="modal-content" style="border-radius: 20px;">
        <div class="modal-header">
          <h4 class="modal-title">Registrar Cliente</h4>
          <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>          
        </div>
        <div class="modal-body">
          <div class="panel-body table-responsive">
            <form action="" name="formCliente" id="formCliente" method="POST">
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Nombre</label>
                <input class="form-control" type="hidden" name="idpersona" id="idpersona">
                <input class="form-control" type="hidden" name="tipo_persona" id="tipo_persona" value="Cliente">
                <input class="form-control" type="text" name="nombre" id="nombre" maxlength="100" placeholder="Nombre del cliente" required>
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Direccion</label>
                <input class="form-control" type="text" name="direccionCliente" id="direccionCliente" maxlength="70" placeholder="Direccion">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Telefono</label>
                <input class="form-control" type="text" name="telefonoCliente" id="telefonoCliente" maxlength="20" placeholder="Número de Telefono">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Telefono local</label>
                <input class="form-control" type="text" name="telefono_localCliente" id="telefono_localCliente" maxlength="20" placeholder="Número de Telefono">
              </div>
              <div class="form-group col-lg-6 col-md-6 col-xs-12">
                <label for="">Email</label>
                <input class="form-control" type="email" name="emailCliente" id="emailCliente" maxlength="50" placeholder="Email">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">RFC</label>
                <input class="form-control" type="text" name="rfcCliente" id="rfcCliente" placeholder="RFC">
              </div>
              <div class="form-group col-lg-4 col-md-6 col-xs-12">
                <label for="">Días de crédito</label>
                <input class="form-control" type="number" name="creditoCliente" id="creditoCliente" min="0" placeholder="Crédito">
              </div>
              <div class="form-group col-lg-4 col-md-12 col-xs-12">
                <label for="">Tipo de precio(*): </label>
                <select name="tipo_precioCliente" id="tipo_precioCliente" class="form-control selectpicker" required>     
                  <option value="publico">Publico</option>
                  <option value="taller">Taller</option>
                  <option value="credito_taller">Crédito Taller</option>
                  <option value="mayoreo">Mayoreo</option>
                </select>
              </div>
            </form>
            <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
              <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardarCliente()">Guardar</button>
              <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left" onclick="cancelarCli()"></i> Cancelar</button>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <?php 
    require('ediciones.php')
  ?>
  
  <!-- fin Modal-->

<?php 
}else{
 require 'noacceso.php'; 
}

require 'footer.php';
 ?>
 <script src="scripts/ventas.js"></script>
 <?php 
}

ob_end_flush();
  ?>