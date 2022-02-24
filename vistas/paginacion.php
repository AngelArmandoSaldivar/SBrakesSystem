<?php 
    require('../modelos/Articulo.php');
    $articulo = NEW Articulo(); 
    $idsucursal = $_SESSION['idsucursal'];
    $data = Array(); // Total de registros
    $rspta=$articulo->totalArticulos();
    while($res=$rspta->fetch_array(MYSQLI_ASSOC)) {
      $data = $res["totalArticulos"];
    }

    $articulo_x_pagina = 10;
    $paginas = 1;
    $paginas = ceil($paginas);
    $inicio = ($_GET["pagina"]-1) * $articulo_x_pagina;

    echo "  <label for=''>Total de articulos:</label> ". $data;
?>

<div class="table-responsive" id="table-search">    
    <section> 
    <div class="form-group col-lg-4 col-md-4 col-xs-12">
      <a class="btn btn-info" href="articulo.php" role="button"><i style="color:white" class="fa fa-refresh"></i> Refrescar</a>
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
    <div class="loader">
      <img src="../files/images/cargaLlanta.gif" alt="" width="50px">
    </div><br>
  <!-- <section id="tabla_resultado"></section> --> 

<table class='responsive-table table table-hover table-bordered' style='border-radius: 15px;'>
       <thead class='table-light' style='font-size:12px'>
         <tr>
           <th class='bg-info' scope='col'>Clave</th>
           <th class='bg-info' scope='col'>FMSI</th>
           <th class='bg-info' scope='col'>Marca</th>
           <th class='bg-info' scope='col'>Descripción</th>
           <th class='bg-info' scope='col'>Costo</th>
           <th class='bg-info' scope='col'>Publico Mostrador</th>
           <th class='bg-info' scope='col'>Taller</th>
           <th class='bg-info' scope='col'>Crédito Taller</th>
           <th class='bg-info' scope='col'>Mayoreo</th>
           <th class='bg-info' scope='col'>Stock</th>
           <th class='bg-info' scope='col'>Acciones</th>
         </tr>
       </thead>
     <tbody>
       
  <?php

    if(!$_GET) {
        header('Location:articulo.php?pagina=1');
    }

    if (isset($_POST["numRegistros"])) {
      // collect value of input field
      $data = 1;
      $numRegistros = $_POST['numRegistros'];
      $articulo_x_pagina = $numRegistros;
    }

    $rspta=$articulo->articulosPagination($inicio, $articulo_x_pagina, "");

    if (isset($_POST["busqueda"])) {
    // collect value of input field
        $data = 1;
        $name = $_POST['busqueda'];
        $rspta=$articulo->articulosPagination($inicio, $articulo_x_pagina, $name);
    }

    $consultaBD=$rspta;

    if($consultaBD->num_rows>=1){

   while($fila=$consultaBD->fetch_array(MYSQLI_ASSOC)) {

     $costoMiles = number_format($fila['costo']);
        $publicMiles = number_format($fila['publico']);
        $tallerMiles = number_format($fila['taller']);
        $creditoMiles = number_format($fila['credito_taller']);
        $mayoreoMiles = number_format($fila['mayoreo']);
        $descrip = $fila['descripcion'];
        $delit = substr($descrip, 0,30);
        if($fila["idsucursal"] == $idsucursal && $fila["stock"] >= '' && $fila["estado"] != 0) {
          if($fila["stock"] >=1) {            
            echo "<tr style='color:blue; font-size:11px;'>
              <td >".$fila['codigo']."</td>
              <td>".$fila['fmsi']."</td>
              <td>".$fila['marca']."</td>
              <td>".$delit."...</td>
              <td><p>$ ".$costoMiles."</p></td>
              <td><p>$ ".$publicMiles."</p></td>
              <td><p>$ ".$tallerMiles."</p></td>
              <td><p>$ ".$creditoMiles."</p></td>
              <td><p>$ ".$mayoreoMiles."</p></td>
              <td><p>".$fila['stock']."pz</td>
              <td>
              <div class='emergente'>
                <span data-tooltip='Editar articulo'><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-pencil'></i></button></span>
                <span data-tooltip='Desactivar articulo'><button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idarticulo"].")')><i class='fa fa-close'></i></button></span>
                <!--<span data-tooltip='Activar articulo'><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idarticulo"].")'><i class='fa fa-check'></i></button></span>-->
              </td>
            </tr>";
          } else if($fila["stock"] <=0) {
            echo "<tr style='color:red; font-size:11px;'>
              <td>".$fila['codigo']."</td>
              <td>".$fila['fmsi']."</td>
              <td>".$fila['marca']."</td>
              <td>".$delit."...</td>
              <td><p>$ ".$costoMiles."</p></td>
              <td><p>$ ".$publicMiles."</p></td>
              <td><p>$ ".$tallerMiles."</p></td>
              <td><p>$ ".$creditoMiles."</p></td>
              <td><p>$ ".$mayoreoMiles."</p></td>
              <td><p>".$fila['stock']."pz</td>
              <td><button class='btn btn-warning btn-xs' onclick='mostrar(".$fila["idarticulo"].")'><i class='fa fa-pencil'></i></button> <button class='btn btn-danger btn-xs' onclick='desactivar(".$fila["idarticulo"].")')><i class='fa fa-close'></i></button><button class='btn btn-primary btn-xs' onclick='activar(".$fila["idarticulo"].")'><i class='fa fa-check'></i></button></td>					
            </tr>";	
          }
        }
    }
}
?>

</tbody>
        <tfoot style='font-size:12px'>
            <tr>
                <th class='bg-info' scope='col'>Clave</th>
                <th class='bg-info' scope='col'>FMSI</th>
                <th class='bg-info' scope='col'>Marca</th>
                <th class='bg-info' scope='col'>Descripción</th>
                <th class='bg-info' scope='col'>Costo</th>
                <th class='bg-info' scope='col'>Publico Mostrador</th>
                <th class='bg-info' scope='col'>Taller</th>
                <th class='bg-info' scope='col'>Crédito Taller</th>
                <th class='bg-info' scope='col'>Mayoreo</th>
                <th class='bg-info' scope='col'>Stock</th>
                <th class='bg-info' scope='col'>Acciones</th>
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
            <a class="page-link" href="articulo.php?pagina=<?php echo $_GET["pagina"] - 1?>">Anterior</a></li>
          <?php }?>
          <?php for($i=0; $i<$paginas; $i++):?>
          <li class="page-item <?php echo $_GET["pagina"] == $i + 1 ? 'active' : ''?>"><a class="page-link" 
            href="articulo.php?pagina=<?php echo $i+1?>"><?php echo $_GET["pagina"]?></a></li>
          <?php endfor?>
          <?php 
            if($inicio >= $data) {
                echo "";
            } else {
          ?>
          <li class="page-item">
            <a class="page-link"href="articulo.php?pagina=<?php echo $_GET["pagina"] + 1?>">Siguiente</a></li>
        </ul>
        <?php
            }
        ?>
      </nav>
  </div>



