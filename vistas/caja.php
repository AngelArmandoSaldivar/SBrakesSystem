<?php 
//activamos almacenamiento en el buffer
ob_start();
session_start();
if (!isset($_SESSION['nombre'])) {
  header("Location: login.html");
}else{

require 'header.php';
if ($_SESSION['caja']==1) {   
 ?> 
<div class="content-wrapper">
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box" style="box-shadow: 5px 7px 10px #3300ff99;border-radius: 16px;">

                    <div class="col-md-12">
                        <div class="box-header with-border col-md-4">
                            <h4 class="box-title">Administrar Caja - Movimientos de caja </h4>
                            <h5>Fecha de caja <?php echo date('d/m/y');?></h5>
                            <section id="saldo_inicial"></section>
                        </div>
                        <div class="box-header with-border col-md-4">                           
                        </div>
                        <div class="box-header with-border col-md-4">                                                    
                            <div class="btn-group" role="group">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="background-color:#455A64; color:white;">
                                <i class="fa fa-wrench">
                                    Acciones
                                </i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" data-toggle="modal" href="#modal-abrir-caja" id="abrirCaja"><i class="fa fa-unlock pull-right" aria-hidden="true"></i> Abrir caja</a>
                                    <a class="dropdown-item" data-toggle="modal" href="#modal-cerrar-caja" id="cerrarCaja"><i class="fa fa-lock pull-right" aria-hidden="true"></i> Cerrar caja</a>
                                    <a class="dropdown-item" href="#"></a>
                                </div>
                            </div>                          

                            <div class="btn-group" role="group" id="movimientos_caja">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="background-color:#29B6F6; color:white;">
                                Movimientos de caja
                                </button>
                                <div class="dropdown-menu">
                                <a class="dropdown-item" data-toggle="modal" href="#modal-traspasos" id="btnTraspasoCaja"><i class="fa fa-exchange pull-right" aria-hidden="true"></i> Traspasar a caja</a>
                                <a class="dropdown-item" data-toggle="modal" href="#modal-gasto"><i class="fa fa-money pull-right" aria-hidden="true"></i> Gasto</a>
                                </div>
                            </div>
                        </div>
                    </div>                                        

                    <div class="table-responsive" id="table-search">   
                        <div id="global">
                            <div id="">                                
                                <div class="col-md-6" id="contenedor_entradas">
								    <div class="panel panel-flat">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">Entradas</h6>
                                        </div>
									    <div class="panel-body">
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs nav-tabs-highlight">
                                                    <li class="active"><a href="#label-tab1" data-toggle="tab">CHEQUES <span id="span-ing" class="label
                                                    label-success position-right">3</span></a></li>
                                                    <li><a href="#label-tab2" data-toggle="tab">EFECTIVO <span id="span-dev" class="label bg-danger
                                                    position-right">0</span></a></li>                                                                                                     
                                                </ul>

											<div class="tab-content">
												<div class="tab-pane active" id="label-tab1">
                                                    <section id="tabla_cheques"></section>
												</div>

												<div class="tab-pane" id="label-tab2">
                                                <section id="tabla_efectivos"></section>                                                
												</div>												
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="col-md-6" id="contenedor_salidas">
								    <div class="panel panel-flat">
                                        <div class="panel-heading">
                                            <h6 class="panel-title">Salidas</h6>
                                        </div>
									    <div class="panel-body">
                                            <div class="tabbable">

                                                <ul class="nav nav-tabs nav-tabs-highlight">
                                                    <li class="active"><a href="#label-tab3" data-toggle="tab">TRASPASO CAJA DOS <span id="span-ing" class="label
                                                    label-success position-right">3</span></a></li>
                                                    <li><a href="#label-tab4" data-toggle="tab">VALES / RECIBOS <span id="span-dev" class="label bg-danger
                                                    position-right">0</span></a></li>                                                                                                     
                                                </ul>

											<div class="tab-content">
												<div class="tab-pane active" id="label-tab3">
                                                    <section id="caja_uno"></section>
												</div>

												<div class="tab-pane" id="label-tab4">
                                                    <section id="tabla_vales_recibos"></section>
												</div>												
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="col-md-6" id="contenedor_depositos">
								    <div class="panel panel-flat">
                                        <div class="panel-heading">
                                            <h6 class="panel-title"></h6>
                                        </div>
									    <div class="panel-body">
                                            <div class="tabbable">
                                                <ul class="nav nav-tabs nav-tabs-highlight">
                                                    <li class="active"><a href="#label-tab5" data-toggle="tab">DEPÓSITOS <span id="span-ing" class="label
                                                    label-success position-right">3</span></a></li>                                                    
                                                </ul>

											<div class="tab-content">
												<div class="tab-pane active" id="label-tab1">
                                                <table class="table table-xxs">
                                                    <tbody>
                                                        <thead class='table-light'>
                                                            <tr>
                                                                <th># Cheque</th>                                                        
                                                                <th>Concepto</th>                                                        
                                                                <th>Importe</th>
                                                            </tr>                                               
                                                        </thead> 
                                                        <tbody>
                                                            <tr>
                                                                <th></th>                                                        
                                                                <th style="text-align:right">Total</th>
                                                                <th></th>
                                                            </tr> 
                                                        </tbody>  
                                                    </tbody>
                                                </table>
												</div>																							
												</div>
											</div>
										</div>
									</div>
								</div>
                                <div class="col-md-6" id="contenedor_totales">
                                    <div class="panel panel-flat">
                                        <div class="table-responsive">
                                            <section id="totales_generales"></section>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12" id="inicio_caja">
                                    <div class="panel panel-flat">
                                        <div class="table-responsive">
                                        <h1 class="text-center"><i class="fa fa-lock" aria-hidden="true"></i> Caja cerrada</h1>
                                        </div>
                                    </div>
                                </div>
							</div>
                            </div>
                        </div>
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
 <script src="scripts/cajaB1.js"></script>
 <?php 
}

ob_end_flush();
  ?>