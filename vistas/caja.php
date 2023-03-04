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

<div class="content-wrapper" id="contenedor-principal">
    <section class="content">
        <div class="row" style="margin-left:30px; margin-right:20px;">     
               
            <div class="col-12 col-md-12">
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

            <div class="col-lg-12 col-xs-6 shadow p-3 mb-5 bg-body rounded" id="divEfectivo">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"></h4>                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 bg-body rounded">
                                <div class="panel-heading">
                                    <h6 class="panel-title"></h6>
                                </div>
                                <div class="panel panel-flat">                                        
                                    <div class="panel-body">
                                        <div class="tabbable">                                            
                                            <ul class="nav nav-tabs nav-tabs-highlight">
                                                <li class="active"><a href="#label-tab-efectivos" data-toggle="tab">ENTRADAS EFECTIVO <span id="span-dev" class="label bg-success position-right">*</span></a></li>      
                                                <li><a href="#label-tab-tarjetas" data-toggle="tab">ENTRADAS BANCOS <span id="span-ing" class="label label-success position-right">*</span></a></li>
                                                <li><a href="#label-tab-caja2" data-toggle="tab">CAJA 2 <span id="span-ing" class="label label-success position-right">*</span></a></li>
                                            </ul>                                            
                                            <div class="tab-content">
                                                <div class="tab-pane active" id="label-tab-efectivos">                                                     
                                                    <div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded" id="divEfectivo">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Entradas cheques / Efectivo</h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                    <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                    <a href="#" class="btn btn-tool btn-sm">
                                                                    <i class="fas fa-download"></i>
                                                                    </a>     
                                                                    </div>
                                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                    <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <h6 class="panel-title">Entradas</h6>
                                                                        </div>
                                                                        <div class="panel panel-flat">                                        
                                                                            <div class="panel-body">
                                                                                <div class="tabbable">
                                                                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                                                                        <li class="active"><a href="#label-tab1" data-toggle="tab">EFECTIVO <span id="span-dev" class="label bg-success position-right">*</span></a></li>      
                                                                                        <li><a href="#label-tab2" data-toggle="tab">CHEQUES <span id="span-ing" class="label label-success position-right">3</span></a></li>                                                                                                                                               
                                                                                    </ul>
                                                                                    <div class="tab-content">
                                                                                        <div class="tab-pane active" id="label-tab1">  
                                                                                            <section id="tabla_efectivos"></section>                                               
                                                                                        </div>
                                                                                        <div class="tab-pane" id="label-tab2">
                                                                                            <section id="tabla_cheques"></section>                                                    
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

                                                    <div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded" id="divVales">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Salidas traspasos / vales / recibos</h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                        <a href="#" class="btn btn-tool btn-sm">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>     
                                                                    </div>
                                                                        <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>
                                                                </div>
                                                            </div>                        
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <h6 class="panel-title">Salidas</h6>
                                                                        </div>
                                                                        <div class="panel panel-flat">                                           
                                                                            <div class="panel-body">
                                                                                <div class="tabbable">
                                                                                    <ul class="nav nav-tabs nav-tabs-highlight">
                                                                                        <li class="active"><a href="#label-tab3" data-toggle="tab">VALES / RECIBOS <span id="span-dev" class="label bg-danger position-right">0</span></a></li>      
                                                                                        <li><a href="#label-tab4" data-toggle="tab">TRASPASO CAJA DOS <span id="span-ing" class="label label-success position-right">3</span></a></li>                                                                                                                                               
                                                                                    </ul>
                                                                                    <div class="tab-content">
                                                                                        <div class="tab-pane active" id="label-tab3">  
                                                                                            <section id="tabla_vales_recibos"></section>                                                  
                                                                                        </div>
                                                                                            <div class="tab-pane" id="label-tab4">
                                                                                                <section id="caja_uno"></section>                                                        
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

                                                    <div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded" id="divDepositos">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Depositos</h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                        <a href="#" class="btn btn-tool btn-sm">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>     
                                                                    </div>
                                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <h6 class="panel-title">Depositos</h6>
                                                                        </div>
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
                                                                                        <div class="tab-pane active" id="label-tab-depositos">
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
                                                                </div> 
                                                            </div>     
                                                        </div>       
                                                    </div>

                                                    <div class="col-lg-6 col-xs-6 shadow p-3 mb-5 bg-body rounded" id="divTotales">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Totales</h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                        <a href="#" class="btn btn-tool btn-sm">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>     
                                                                    </div>
                                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <h6 class="panel-title">Totales</h6>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <section id="totales_generales"></section>
                                                                        </div>
                                                                    </div>   
                                                                </div> 
                                                            </div>     
                                                        </div>       
                                                    </div>

                                                </div>
                                                <div class="tab-pane" id="label-tab-tarjetas">                                                 
                                                    <div class="col-lg-12 col-xs-12 shadow p-3 mb-5 bg-body rounded" id="divConciliiacion">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Conciliación bancaria Banamex al mes de <input type="text" id="fecha_conciliacion" disabled style="border:none;"></h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Minimizar">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                        <a class="btn btn-tool btn-sm" id="btnEditarGuardarConciliacion" name="btnEditarGuardarConciliacion" title="Guardar cambios">
                                                                            <i class="fas fa-floppy-o"></i>
                                                                        </a>     
                                                                    </div>
                                                                    <button type="button" class="btn btn-tool" data-toggle="modal" href="#modal-agregar-conciliacion" title="Añadir conciliacion">
                                                                        <i class="fas fa-plus"></i>
                                                                    </button>
                                                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Cerrar">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <div class="col-md-4">
                                                                                <div class="form-group col-lg-12 col-md-2 col-xs-6" id="divImpuesto">       
                                                                                    <div class="form-group">
                                                                                        <div class="input-group bootstrap-touchspin">
                                                                                            <span class="input-group-btn">                                
                                                                                            </span><span class="input-group-addon bootstrap-touchspin-prefix">Buscar: </span>                                                                            
                                                                                            <input class="form-control" type="input" name="buscadorConciliacion" id="buscadorConciliacion"></input>
                                                                                            <span class="input-group-addon bootstrap-touchspin-postfix"><i class="fa fa-search pull-right" aria-hidden="true"></i></span>                                                    
                                                                                        </div>
                                                                                    </div>                                           
                                                                                </div>                                                                      
                                                                            </div>
                                                                            <div class="col-md-4 justify-content-end">                                                                                                              
                                                                            </div>
                                                                            <div class="alert alert-info alert-styled-left text-blue-800 col-md-4 row " style="margin-bottom:50px;">                                                                        
                                                                                Saldo Mes Pasado $<section id="saldo_inicial_conciliacion" style="color:black"></section></span>
                                                                                <input type="hidden" id="txtProceso" name="txtProceso" class="form-control" value="">                                        
                                                                            </div>
                                                                            <div class="table-responsive">   
                                                                                <div class="tab-pane active" id="label-tab-conciliacion">                                 
                                                                                    <section id="contenedor_conciliacion"></section>
                                                                                </div>
                                                                            </div>
                                                                        </div>   
                                                                    </div> 
                                                                </div>     
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="tab-pane" id="label-tab-caja2">
                                                    <div class="col-lg-12 col-xs-12 shadow p-3 mb-5 bg-body rounded" id="divCajaDos">
                                                        <div class="card">
                                                            <div class="card-header">
                                                                <h4 class="card-title">Movimientos caja 2</h4>
                                                                <div class="card-tools">
                                                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                                                        <i class="fas fa-minus"></i>
                                                                    </button>
                                                                    <div class="btn-group">
                                                                        <a href="#" class="btn btn-tool btn-sm">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>     
                                                                    </div>
                                                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                            </div>  
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 bg-body rounded">
                                                                        <div class="panel-heading">
                                                                            <h6 class="panel-title">Movimientos caja 2</h6>
                                                                        </div>
                                                                        <div class="table-responsive">
                                                                            <!--<section id="totales_generales"></section>-->
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
                                </div>   
                            </div> 
                        </div>     
                    </div>       
                </div>
            </div>
        </div>
        <div class="col-12 col-md-12" id="divInicioCaja">   
            
        <div class="card"> 
            <div class="container-fluid"> 
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-center rounded">                    
                            <h1 class="text-center"><i class="fa fa-lock" aria-hidden="true"></i> Caja cerrada</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        </div>                               
    </section>
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
 <script type="module" src="scripts/cajas.js"></script>
 <script type="module" src="scripts/otros.js"></script>
 <?php 
}

ob_end_flush();
  ?>