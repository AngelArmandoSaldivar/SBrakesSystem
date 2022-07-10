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
  <title>BrakeOneSystem</title>
  <link rel="icon" href="../files/images/MainLogo.png" height="10" width="50">  
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">  
  <link rel="stylesheet" href="plugins/fontawesome-free/css/alls.min.css">  
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbar.min.css">  
  <link rel="stylesheet" href="dist/css/adminltes.css">
  <link rel="stylesheet "type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">
  <link rel="stylesheet" href="../public/css/font-awesomes.min.css">  
  <link rel="stylesheet" href="../public/css/style.css">
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">  
  <link rel="stylesheet" href="plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">  
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">  
  <link rel="stylesheet" href="plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
  <style>
    .loader {
      display: none;
      text-align: center;
    }
    #botonClave, #botonFmsi, #botonMarca, #botonDescripcion, #botonStock, #botonMayoreo, #botonTaller, #botonCredito, #botonMostrador, #botonCosto{
      display: none;
    }
    #thMarca, #thFmsi, #thClave {
      width: 90px;
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
  </style>
</head> 
<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">

   <!-- Preloader -->
   <div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="../files/images/BrakeOneBrembo.png" alt="BrakeOneSystem" height="30" width="250">
  </div>

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="escritorio.php" class="nav-link">Inicio</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="articulo.php" class="nav-link">Articulos</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="ingreso.php" class="nav-link">Compras</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="venta.php" class="nav-link">Ventas</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="servicio.php" class="nav-link">Servicios</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="cliente.php" class="nav-link">Clientes</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="usuario.php" class="nav-link">Usuarios</a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="sucursal.php" class="nav-link">Sucursales</a>
      </li>
      <li class="nav-item dropdown">
            <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Reportes</a>
            <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">              
              <li><a data-toggle='modal' href='#filtroFechaReportes' class="dropdown-item">Ventas </a></li>                            
              <li class="dropdown-divider"></li>
              <li><a data-toggle='modal' href='#filtroServiciosFechaReportes' class="dropdown-item">Servicios</a></li>
                          
              <!--<li class="dropdown-submenu dropdown-hover">
                <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">Hover for action</a>
                <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">
                  <li>
                    <a tabindex="-1" href="#" class="dropdown-item">level 2</a>
                  </li>                  
                  <li class="dropdown-submenu">
                    <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="dropdown-item dropdown-toggle">level 2</a>
                    <ul aria-labelledby="dropdownSubMenu3" class="dropdown-menu border-0 shadow">
                      <li><a href="#" class="dropdown-item">3rd level</a></li>
                      <li><a href="#" class="dropdown-item">3rd level</a></li>
                    </ul>
                  </li>
                  <li><a href="#" class="dropdown-item">level 2</a></li>
                  <li><a href="#" class="dropdown-item">level 2</a></li>
                </ul>
              </li>-->          
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
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header"><h5>Usuario: <?php echo $_SESSION['nombre']; ?></h5></span>
          <div class="dropdown-divider"></div>
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
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4" style="height: 1000px;">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="../files/images/BrakeOneBrembo.png" alt="B1S" class="" style="opacity: .8" height="20" width="100">
      <span class="brand-text font-weight-light">BrakeOne System</span>
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
                    <p>Articulos</p>
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
            if ($_SESSION['compras']==1) { 
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
              </ul>
            </li>';
            }
          ?>
          <?php 
            if ($_SESSION['ventas']==1) {
              echo '<li class="nav-item">
              <a href="#" class="nav-link">
                <i class="fa fa-shopping-cart"></i>
                <p>
                  Ventas
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="venta.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Ventas</p>
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
              </ul>
            </li>';
            }
          ?>
          <?php 
           if ($_SESSION['accesos']==1) {
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
            </li>';
            }
          ?>
          <?php 
           if ($_SESSION['sucursal']==1) {
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
  
</div>

