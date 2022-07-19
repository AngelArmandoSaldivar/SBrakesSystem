<?php 
    require_once "../modelos/Usuario.php";
    $usuario=new Usuario();
    $idusuario = $_GET["idusuario"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BrakeOneSystem</title>
    <link rel="shortcut icon" href="../files/images/favicon.ico"> 
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
</head>
<body>
    <div style="color: white; text-align: center; font-size: 20px; padding: 0 50px; height: 64px; line-height: 64px; background: #001529">Selecciona la sucursal</div>
    <div class="row" style="margin-left: -8px; margin-right: -8px; height: 100%;">
        <?php 
            $rspta = $usuario->sucursales($idusuario);
            while($reg = $rspta->fetch_object()) {
                echo '<div class="form-group col-lg-2 col-md-4 col-xs-12" style="padding-left: 8px; padding-right: 8px; height: 200px; width: 200px; line-height: 200px; text-align: center; font-size: 16px; color: white; margin: 20px;">                            
                            <div class="small-box bg-info">                               
                                <div class="icon">
                                    <i class="fas fa-home"></i>
                                </div>
                                <a class="small-box-footer" onclick="sucursalSeleccionada('.$reg->idsucursal.')" style="cursor:pointer;">
                                '.$reg->nombre.' <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>';
            }
        ?>       
        
    </div>
</body>
</html>

<script src="../public/js/jquery.min.js"></script>
<script src="../public/js/bootstrap.min.js"></script>
<script src="dist/js/adminltes.js"></script>
<script src="../public/datatables/buttons.colVis.min.js"></script>
<script src="../public/datatables/button.html5.min.js"></script>
<script src="../public/datatables/dataTables.buttons.min.js"></script>
<script src="../public/datatables/jquery.dataTablesB1.min.js"></script>
<script src="../public/datatables/jszip.min.js"></script>
<script src="../public/datatables/pdfmake.min.js"></script>
<script src="../public/datatables/vfs_fonts.js"></script>
<script src="../public/datatables/datatablesB1.min.js"></script>
<script src="../public/js/bootbox.min.js"></script>
<script src="../public/js/bootstrapsB1-select.min.js"></script>
<script src="../public/plugins/sweetalert2/sweetalert2.all.js"></script>
<script src="plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/howler/2.2.1/howler.min.js"></script>

<!-- SCRIPT MATERIAL UI -->
<script src="https://unpkg.com/material-components-web@latest/dist/material-components-web.min.js"></script>
<script>
$(document).ready(function(){
    $('[data-toggle="popover"]').popover();   
});
</script>
<script src="scripts/login.js"></script>