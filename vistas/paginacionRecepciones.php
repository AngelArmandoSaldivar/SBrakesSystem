<?php 
    require('../modelos/Ingreso.php');
    $ingreso = NEW Ingreso();
    $idsucursal = $_SESSION['idsucursal'];
    $data = Array(); // Total de registros
    $rspta=$ingreso->totalIngresos();
    while($res=$rspta->fetch_array(MYSQLI_ASSOC)) {
      $data = $res["totalIngresos"];
    }

    $ingreso_x_pagina = 6;
    $paginas = 1;
    $paginas = ceil($paginas);
    $inicio = ($_GET["pagina"]-1) * $ingreso_x_pagina;
    echo "  <label for=''>Total de ingresos:</label> ". $data;
?>

<div class="panel-body table-responsive" id="listadoregistros">   
    <section> 
    <div class="form-group col-lg-4 col-md-4 col-xs-12">
    <a class="btn btn-info" href="ingreso.php" role="button"><i style="color:white" class="fa fa-refresh"></i> Refrescar</a>
    </div>
    <form action="" method="post">
        <div class="form-group col-lg-4 col-md-4 col-xs-12">
            <div class="input-group" style="width:250px;">
                <input type="text" class="form-control me-2" placeholder="Buscar..." name="busqueda">             
                <div class="input-group-btn">
                <!-- <button class="btn btn-default" type="submit"> -->
                <p><input type="submit" class="btn btn-primary" value="Buscar" name="buscar"></p>            
                </div>
            </div>           
        </div>    
        <div class="form-group col-lg-4 col-md-4 col-xs-12">
                <div class="input-group" style="width:200px;">
                    <select class="form-control selectpicker" class="form-control" name="numRegistros" id="numRegistros">
                        <option value="" disabled selected>Total de registros</option>
                        <option value="10">1-10</option>
                        <option value="500">1-50</option>
                        <option value="100">1-100</option>
                        <option value="200">1-200</option>
                        <option value="500">1-500</option>
                    </select>
                </div>
            </div>
    </form>

    </section><br>
  <div class="form-group col-lg-2 col-md-6 col-xs-12" style="align-items:center;">
    </div>
    <br>
    <div class='loader'>
        <img src='../files/images/loader.gif' alt=''>
    </div>
    <table class='responsive-table table table-hover table-bordered' style='font-size:12px' id='example'>
        <thead class='table-light'>
            <tr>
                <th class='bg-info' scope='col'>Acciones</th>
                <th class='bg-info' scope='col'>Folio</th>
                <th class='bg-info' scope='col'>Entrada</th>
                <th class='bg-info' scope='col'>Estatus</th>
                <th class='bg-info' scope='col'>Proveedor</th>
                <th class='bg-info' scope='col'>Usuario</th>
                <th class='bg-info' scope='col'>Total</th>
            </tr>
        </thead>
    <tbody>       
  <?php

    if(!$_GET) {
        header('Location:ingreso.php?pagina=1');
    }

    if (isset($_POST["numRegistros"])) {
        // collect value of input field
        $data = 1;
        $numRegistros = $_POST['numRegistros'];
        $ingreso_x_pagina = $numRegistros;
    }

    $rspta=$ingreso->ingresosPagination($inicio, $ingreso_x_pagina, "");

    if (isset($_POST["busqueda"])) {
        $data = 1;
        $name = $_POST['busqueda'];
        $rspta=$ingreso->ingresosPagination($inicio, $ingreso_x_pagina, $name);
    }

    $consultaBD=$rspta;

    if($consultaBD->num_rows>=1){
        while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)){
            if($fila["idsucursal"] == $idsucursal && $fila["estado"] != 'ANULADO') {
                    if ($fila["tipo_comprobante"]=='Ticket') {
                        $url='../reportes/exTicket.php?id=';
                    }else{
                        $url='../reportes/exFactura.php?id=';
                    }
                    $miles = number_format($fila['total_compra']);
                    echo "<tr>
                        <td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idingreso"].")'><i class='fa fa-eye'></i></button> <button class='btn btn-danger btn-xs' onclick='anular(".$fila["idingreso"].")'><i class='fa fa-close'></i></button></td>								
                        <td>".$fila['idingreso']."</td>
                        <td>".$fila['fecha_hora']."</td>
                        <td>".$fila['estado']."</td>
                        <td><p>".$fila['proveedor']."</td>
                        <td><p>".$fila['usuario']."</td>
                        <td><p>$ ".$fila["total_compra"]."</td>								
                    </tr>
                    ";
            }
        }    
    }
?>
</tbody>
<tfoot>
    <tr>						
        <th class='bg-info' scope='col'>Acciones</th>
        <th class='bg-info' scope='col'>Folio</th>
        <th class='bg-info' scope='col'>Entrada</th>
        <th class='bg-info' scope='col'>Estatus</th>
        <th class='bg-info' scope='col'>Proveedor</th>
        <th class='bg-info' scope='col'>Usuario</th>
        <th class='bg-info' scope='col'>Total</th>
    </tr>
</tfoot>
</table>

<nav aria-label="Page navigation example" style="text-align:right; margin-right: 15px;">
        <ul class="pagination">
            <?php 
                if($_GET["pagina"] <= 1) {
                    echo "";
                } else {
            ?>
          <li class="page-item">          
          <a class="page-link" href="ingreso.php?pagina=<?php echo $_GET["pagina"] - 1?>">Anterior</a></li>
          
          <?php }?>

          <?php for($i=0; $i<$paginas; $i++):?>
          <li class="page-item <?php echo $_GET["pagina"] == $i + 1 ? 'active' : ''?>"><a class="page-link" 
            href="ingreso.php?pagina=<?php echo $i+1?>"><?php echo $_GET["pagina"]?></a></li>
          <?php endfor?>

          <?php 
            if($inicio >= $data) {
                echo "";
            } else {
          ?>
          <li class="page-item"><a class="page-link"href="ingreso.php?pagina=<?php echo $_GET["pagina"] + 1?>">Siguiente</a></li>
        </ul>
        <?php
            }
        ?>
      </nav>
  </div>