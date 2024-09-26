<?php 
  if (strlen(session_id())<1) 
  session_start();
  require "../config/Conexion.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>S-Brakes System</title>
  <link rel="shortcut icon" href="../files/images/logo-sbrakes.png"> 
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">  
  <link rel="stylesheet" href="plugins/fontawesome-free/css/alls.min.css">  
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbar.min.css">  
  <link rel="stylesheet" href="dist/css/adminltes.css">
  <link rel="stylesheet "type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="../public/css/font-awesomes.min.css">  
  <link rel="stylesheet" href="../public/css/estilos.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">  
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">  
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">  
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <script src="https://kit.fontawesome.com/9a94a93f86.js" crossorigin="anonymous"></script>


  <link href="https://cdn.datatables.net/v/bs/dt-1.13.4/datatables.min.css" rel="stylesheet"/>
  <script src="https://cdn.datatables.net/v/bs/dt-1.13.4/datatables.min.js"></script>


  <style>
    .loader {
      display: none;
      text-align: center;
    }
    #botonClave, #botonFmsi, #botonMarca, #botonDescripcion, #botonStock, #botonMayoreo, #botonTaller, #botonCredito, #botonMostrador, #botonCosto{
      display: none;
    }
    #thMarca, #thFmsi, #thClave {
      /*width: 90px;*/
    }
    .loaderSearch {
      display: none;
      text-align: center;
    }
    .loaderInfoAuto {
      display: none;
      text-align: center;
    }
    .modal-lg {
      max-width: 95% !important;
    }
    #checkArticulos {
      width: 19px;
      height: 19px;
      cursor: pointer;
      margin-left: 5px;
      margin-top: 2px
    }
    #contenedor-principal {
      background-color:white;
    }
    body {
      font-family: "Roboto", Helvetica Neue, Helvetica, Arial, sans-serif;
      
    }
  </style>
</head> 
<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">
   <!-- Preloader -->
   <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../files/images/logo-sbrakes.png" alt="SBrakesSystem" height="80" width="250">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['escritorio']==1) {
          echo '<a href="escritorio.php" class="nav-link">Escritorio</a>
          ';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['cotizaciones']==1) {
          echo '<a href="cotizaciones.php" class="nav-link">Cotizaciones</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['almacen']==1) {
          echo '<a href="articulo.php" class="nav-link">Productos</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['recepciones']==1) { 
          echo '<a href="ingreso.php" class="nav-link">Compras</a>';
        }
      ?>
      </li>      
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['servicios']==1) {
          echo '<a href="servicio.php" class="nav-link">Servicios</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['clientes']==1) {
          echo '<a href="cliente.php" class="nav-link">Clientes</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['usuarios']==1) {
          echo '<a href="usuario.php" class="nav-link">Usuarios</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['proveedores']==1) {
          echo '<a href="proveedor.php" class="nav-link">Proveedores</a>';
        }
      ?>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
      <?php 
        if ($_SESSION['sucursales']==1) {
          echo '<a href="sucursal.php" class="nav-link">Sucursales</a>';
        }
      ?>
      </li>
      <li class="nav-item dropdown">
        <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Reportes</a>
        <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
            <li><a data-toggle='modal' href='#filtroServiciosFechaReportes' class="dropdown-item">Servicios</a></li>                                             
          </ul>
        </li>
      </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">     
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>     
      <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="fas fa-th-large"></i>          
        </a>
        <div class="dropdown-menu dropdown-menu-sm w-150 dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><h5>Usuario: <?php echo $_SESSION['nombre']; ?></h5></span>
          <div class="dropdown-divider"></div>
          <a href="../ajax/usuario.php?op=cambiarsucursal" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i>Cambiar de sucursal
            <span class="float-right text-muted text-sm">Cambiar sucursal</span>
          </a>
          <a href="../ajax/usuario.php?op=salir" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i>Cerrar Sesión
            <span class="float-right text-muted text-sm">Cerrar sesión</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> Perfil
            <span class="float-right text-muted text-sm">Ver perfil</span>
          </a>
          <div class="dropdown-divider"></div>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">-----------------</a>
        </div>
      </li>
    </ul>
  </nav>
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="height: 890px;">
    <!-- Brand Logo -->
    <a href="https://angelarmandosaldivar.github.io/sbrakes/" class="brand-link">
      <img src="../files/images/logo-sbrakes.png" alt="B1S" class="" style="opacity: .8" height="25" width="100">
      <span class="brand-text font-weight-light">S-Brakes System</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex" style="color:white; text-align:center">       
        <div class="info">
          <a href="#" class="d-block">
            <?php 
              $idsucursal = $_SESSION['idsucursal'];
              $sql="SELECT * FROM sucursal WHERE idsucursal='$idsucursal'";
              $result = ejecutarConsulta($sql);
              while($row = $result->fetch_assoc()) {
                echo '<div style="text-align:center">';
                echo "<h4  style='font-family: ui-serif; color:white; font-size:16px'> Sucursal: ".$row["nombre"]."</h4>";
                echo '</div>';
              }
            ?>            
          </a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          
          <?php 
            if ($_SESSION['escritorio']==1) {
              echo '<li class="nav-item">
              <a href="escritorio.php" class="nav-link">
                <i class="nav-icon fas fa-th"></i>             
                <p>
                  Escritorio
                  <span class="right badge badge-danger">Solo admin</span>
                </p>
              </a>
            </li>';
            }
          ?>  
          
          <?php 
            if ($_SESSION['cotizaciones']==1) {
              echo '<li class="nav-item">
              <a href="cotizaciones.php" class="nav-link">
                <i class="nav-icon fas fa-mobile"></i>             
                <p>
                  Cotizaciones
                  <span class="right badge badge-danger">0</span>
                </p>
              </a>
            </li>';
            }
          ?> 

          <?php 
            if ($_SESSION['caja']==1) {
              echo ' <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-dropbox"></i>
                <p>
                  Caja
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="caja.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Caja</p>
                  </a>
                </li>                
              </ul>
            </li>';
            }
          ?> 
          
          <?php 
            if ($_SESSION['almacen']==1) {
              echo ' <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-laptop"></i>
                <p>
                  Almacen
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="articulo.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Productos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="kardex.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Kardex</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="categoria.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Categorias</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="marca.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Marcas</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="pedidos.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Pedidos</p>
                  </a>
                </li>
              </ul>
            </li>';
            }
          ?>       
          
          <?php 
            if ($_SESSION['recepciones']==1) { 
              echo '<li class="nav-item">
              <a href="#" class="nav-link">
              <i class="fa fa-th"></i>
                <p>
                  Compras
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="ingreso.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Recepciones</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="gastos.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Gastos</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="garantia.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Garantias</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="proveedor.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Proveedores</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="ordenCompra.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ordenen de compra </p>
                  </a>
                </li>
              </ul>
            </li>';
            }
          ?>         
          <?php 
           if ($_SESSION['servicios']==1) {
              echo '<li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-wrench"></i>
                <p>
                  Servicios
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="servicio.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Servicios</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="cliente.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Clientes</p>
                  </a>
                </li>   
              </ul>
            </li>';
            }
          ?>
          <?php 
           if ($_SESSION['usuarios']==1) {
              echo '<li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-users"></i>
                <p>
                  Accesos
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="usuario.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Usuarios</p>
                  </a>
                </li>
              </ul>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="permiso.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Permisos</p>
                  </a>
                </li>
              </ul>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="rol.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Roles</p>
                  </a>
                </li>
              </ul>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="asignacion.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Asignación</p>
                  </a>
                </li>
              </ul>
            </li>';
            }
          ?>
          <?php 
           if ($_SESSION['sucursales']==1) {
              echo '<li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-map-marker"></i>
                <p>
                  Sucursal
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="sucursal.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Sucursales</p>
                  </a>
                </li>
              </ul>
            </li>';
            }
          ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <div class="modal fade" id="filtroFechaReportes" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 50% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Generar reporte </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>       
      </div>    
      <form action="" name="formularioProductoServicio" id="formularioProductoServicio" method="POST">
        <div class="panel-body table-responsive">
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label>Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio_reporte" id="fecha_inicio_reporte" value="" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label>Fecha Fin</label>
            <input type="date" class="form-control" name="fecha_fin_reporte" id="fecha_fin_reporte" value="" required>
          </div>       
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoServicio" onclick="generarReporte()"><li class=" fa fa-file-pdf-o"> Gererar reporte</li></button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="filtroServiciosFechaReportes" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 50% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Generar reporte </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>       
      </div>
      <form action="" name="formularioProductoServicio" id="formularioProductoServicio" method="POST">
        <div class="panel-body table-responsive">
        <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label>Fecha Inicio</label>
            <input type="date" class="form-control" name="fecha_inicio_reporte_servicio" id="fecha_inicio_reporte_servicio" value="" required>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label>Fecha Fin</label>
            <input type="date" class="form-control" name="fecha_fin_reporte_servicio" id="fecha_fin_reporte_servicio" value="" required>
          </div>
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoServicio" onclick="generarReporteServicio()"><li class=" fa fa-file-pdf-o"> Gererar reporte</li></button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>
  
</div>
