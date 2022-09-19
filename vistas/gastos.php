<?php
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{


require 'header.php';

if ($_SESSION['almacen']==1) {
    require_once "../modelos/Gasto.php";
    $gasto = new Gasto();

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
                                <h4 class="box-title">Gastos</h4>
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
                            <div class="form-group col-lg-4 col-md-6 col-xs-12">                                
                            </div> 
                            <div class="form-group col-lg-4 col-md-6 col-xs-12">                                
                            </div> 
                            <div class="form-group col-lg-4 col-md-6 col-xs-12" style="text-align:right">
                                <h5 id="total_gasto" name="total_gasto" width="400" height="300" value=""></h5>
                            </div>
                        </div>
                        <div class="panel-body" style="height: 400px;" id="formularioregistros">
                            <form action="" name="formulario" id="formulario" method="POST">
                                <div class="form-group col-lg-12 col-md-6 col-xs-12">
                                    <label for="">Descripción</label>
                                    <input class="form-control" type="hidden" name="idgasto" id="idgasto">
                                    <textarea class="form-control" id="descripcion" name="descripcion" rows="3" style="width: 100%;"></textarea>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-xs-12">
                                    <label for="">Cantidad</label>
                                    <input class="form-control" type="number" name="cantidad" id="cantidad" placeholder="Unidades">
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-xs-12">
                                    <label for="">Precio</label>
                                    <input class="form-control" type="number" name="total_gasto" id="total_gasto" placeholder="$0.0">
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-xs-12" id="">
                                    <label for="">Método pago: </label>
                                    <select name="metodoPago" id="metodoPago" class="form-control selectpicker" required>
                                    <option value="" selected disabled hidden>Forma de pago</option>
                                    <option value="Cheque">CHEQUE</option>
                                    <option value="Tarjeta">TARJETA</option>
                                    <option value="Efectivo">EFECTIVO</option>
                                    <option value="Deposito">DEPÓSITO</option>
                                    <option value="Tarjeta">TARJETA</option>
                                    <option value="Transferencia">TRASFERENCIA</option>
                                    </select>
                                </div>
                                <div class="form-group col-lg-3 col-md-6 col-xs-12" id="">
                                    <label for="">Fecha(*): </label>
                                    <input class="form-control" type="date" name="fecha_hora" id="fecha_hora" required>
                                </div>
                                <div class="form-group col-lg-12 col-md-6 col-xs-12">
                                    <label for="">Información adicional</label>                                    
                                    <textarea class="form-control" id="informacionAdicional" name="informacionAdicional" rows="3" style="width: 100%;"></textarea>
                                </div>
                                <div class="form-group col-lg-12 col-md-6 col-xs-12">
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
 <script src="scripts/gasto.js"></script>
 <?php 
}

ob_end_flush();
  ?>

