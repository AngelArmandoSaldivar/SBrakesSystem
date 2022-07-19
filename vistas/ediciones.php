<div class="modal fade" id="myModalProductsEdit" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Seleccionar articulos</h4>
      </div>
      <div class="modal-body">
        <div class="panel-body table-responsive">
          <div class="form-group col-lg-10 col-md-8 col-xs-12">
            <section>            
              <center><input class="form-control me-2" type="text" name="busquedaProductEdit" id="busquedaProductEdit" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center><br><br>
            </section>
          </div>
          <div class="form-group col-lg-2 col-md-8 col-xs-12">              
            <a data-toggle="modal" href="#agregarProducto">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarModalEdit()"><span class="fa fa-plus"></span>Registrar Producto</button>
            </a>
          </div>

          <div class="form-group col-lg-2 col-md-8 col-xs-12">
            <a data-toggle="modal" href="#myModalProductsAlmacenEdit">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarModalEdit()"><span class="fa fa-search-plus"></span>Buscar en otro almacen</button>
            </a>
          </div>
          <div id="global">
            <div id="tablaResultadosModal">
              <section id="tabla_resultadoProductoEdit"> </section>
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

<div class="modal fade" id="editProductventa" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Editar producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <!--<div class="modal-body">-->
      <form action="" name="" id="" method="POST">
        <div class="panel-body table-responsive">
            <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">ID</label>
              <input class="form-control" type="text" id="idproducto" name="idproducto"></input>
            </div>
            <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Descripción </label>
              <textarea class="form-control" id="descripcionEdit" name="descripcionEdit" rows="5" style="width: 280px;"></textarea>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Cantidad </label>
              <input class="form-control" type="number" name="cantidadEdit" id="cantidadEdit">
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Precio Venta </label>
              <input class="form-control" type="number" name="precioVentaEdit" id="precioVentaEdit">
            </div>
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoVenta" data-dismiss="modal" onclick="editarGuardarProductoVenta()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProductServicio" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 60% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
            <h4 class="modal-title">Editar producto</h4>z
          </div>
        <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
          <input class="form-control" type="text" id="claveProduct" name="claveProduct" style="border:none; background-color:transparent; color:black"></input>
        </div>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <!--<div class="modal-body">-->
      <form action="" name="formularioProductoServicio" id="formularioProductoServicio" method="POST">
        <div class="panel-body table-responsive">
            <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">ID</label>
              <input class="form-control" type="text" id="idProducto" name="idProducto"></input>
            </div>
            <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Descripción </label>
              <textarea class="form-control" id="descripcionProducto" name="descripcionProducto" rows="5" style="width: 280px;" required></textarea>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Cantidad </label>
              <input class="form-control" type="number" name="cantidadProducto" id="cantidadProducto" required>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Precio Venta </label>
              <input class="form-control" type="number" name="precioProducto" id="precioProducto" required>
            </div>
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoServicio" onclick="editarGuardarProductoServicio()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="editProductRecepcion" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Editar producto</h4>
      </div>
      <!--<div class="modal-body">-->
      <form action="" name="formularioProductoRecepcion" id="formularioProductoRecepcion" method="POST">
        <div class="panel-body table-responsive">
            <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">ID</label>
              <input class="form-control" type="text" id="idProductoRec" name="idProductoRec"></input>
            </div>
            <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Descripción </label>
              <textarea class="form-control" id="descripcionProductoRec" name="descripcionProductoRec" rows="5" style="width: 280px;" required></textarea>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Cantidad </label>
              <input class="form-control" type="number" name="cantidadProductoRec" id="cantidadProductoRec" required>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Precio </label>
              <input class="form-control" type="number" name="precioProductoRec" id="precioProductoRec" required>
            </div>
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoServicio" onclick="editarGuardarProductoRecepcion()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalAddCobro" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
      <h4 class="modal-title">Método pago</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="panel-body table-responsive">
        <div class="form-group col-lg-3 col-md-2 col-xs-6" id="">
          <label for="">Fecha </label>
          <input class="form-control" style="border:none; background-color: transparent; padding: 0;" id="" name="" type="" value="<?php echo date("Y-m-d"); ?>" disabled></input>
        </div>
        <div class="form-group col-lg-3 col-md-2 col-xs-6" id="">
          <label for="">Cliente </label>
          <input class="form-control" style="border:none; background-color: transparent; padding: 0;" id="clienteCobro" name="clienteCobro" type=""></input>
        </div>
        <div class="form-group col-lg-3 col-md-2 col-xs-6" id="">
          <label for="">Total </label>
          <input class="form-control" style="border:none; background-color: transparent; padding: 0;" id="totalCobro" name="totalCobro" type=""></input>
        </div>
        <div class="form-group col-lg-3 col-md-2 col-xs-6" id="">
          <label for="">Por pagar </label>
          <input class="form-control" style="border:none; background-color: transparent; padding: 0;" id="porPagar" name="porPagar" type=""></input>
        </div>     
      </div>
      <!--<div class="modal-body">-->
      <form action="" name="formularioAddCobro" id="formularioAddCobro" method="POST">
        <div class="panel-body table-responsive">
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Importe </label>
              <input class="form-control" type="hidden" name="idpago" id="idpago">
              <input class="form-control" id="importeCobro" name="importeCobro" type="number" placeholder="$" required></input>
            </div>
            <div class="form-group col-lg-4 col-md-6 col-xs-12" id="">
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
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
              <label for="">Banco(*): </label>
                <select name="banco" id="banco" class="form-control selectpicker">     
                <option value="" selected disabled hidden>Banco</option>                       
                  <option value="IXE">IXE</option>
                  <option value="HSBC">HSBC</option>
                  <option value="BANORTE">BANORTE</option>
                  <option value="BANAMEX">BANCOMER</option>
                  <option value="SANTANDER">SANTANDER</option>
                  <option value="SCOTIA BANK">SCOTIA BANK</option>                    
                </select>
              </div>
            <div class="form-group col-lg-12 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Referencia </label>
              <textarea class="form-control" id="referenciaCobro" name="referenciaCobro" rows="5" style="width: 100%;"></textarea>
            </div>
        </div>
      </form>
      <div class="panel-body table-responsive">
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarCobro" id="btnGuardarCobro" onclick="guardarCobro()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="cancelarFormPago()"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal" onclick="cancelarFormPago()">Cerrar</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="myModalProductsAlmacenEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Seleccionar articulos</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body">
        <div class="panel-body table-responsive">
          <div class="form-group col-lg-10 col-md-8 col-xs-12">
            <section>            
              <center><input class="form-control me-2" type="text" name="busquedaProductAlmacenEdit" id="busquedaProductAlmacenEdit" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center><br><br>
            </section>
          </div>
          <div class="form-group col-lg-2 col-md-8 col-xs-12">              
            <a data-toggle="modal" href="#agregarProducto">            
            </a>
          </div>

          <div class="form-group col-lg-2 col-md-8 col-xs-12">
            <a data-toggle="modal" href="#myModalProductsEdit">
              <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="cerrarSucursalesEdit()"><span class="fa fa-arrow-left"></span> Regresar a mi almacen</button>
            </a>
          </div>
          <div id="global">
            <div id="tablaResultadosModal">
              <section id="tabla_resultadoProductoAlmacenEdit"> </section>
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

<div class="modal fade" id="agregarProductoAlmacen" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Articulos almacenes</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <div class="panel-body table-responsive">
          <div class="form-group col-lg-4 col-md-8 col-xs-12">              
          <select name="almacenId"  id="almacenId" class="form-control selectpicker">
            <option value="" disabled selected>Filtrar por almacen</option>
            <?php               
              $sql="SELECT * FROM sucursal WHERE idsucursal != '$idsucursal'";
              $result = ejecutarConsulta($sql);
              while($row = $result->fetch_assoc()) {
                echo "<option value='$row[idsucursal]'>$row[nombre]</option>";
              }
            ?>
          </select>
          </div>
          <div class="form-group col-lg-4 col-md-8 col-xs-12">
            <section>
              <center><input class="form-control me-2" type="text" name="busquedaProductAlmacen" id="busquedaProductAlmacen" placeholder="Buscar..." style="width:250px; border-radius: 8px; box-shadow: -2px 2px 5px #3300ff99;"></center><br><br>
            </section>
          </div>
          <div class="form-group col-lg-4 col-md-8 col-xs-12 text-right">              
          <a data-toggle="modal" href="#myModal">
            <button id="btnAgregarArt" type="button" class="btn btn-primary" onclick="regresarMiSucursal()"><span class="fa fa-arrow-left"></span> Regresar a mi almacen</button>
          </a>
        </div>
        <div id="global">
          <div id="tablaResultadosModal">
            <section id="tabla_resultadoProducto_almacen"> </section>
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

<div class="modal fade" id="agregarProducto" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Registrar Producto</h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
      <div class="modal-body">
        <div class="panel-body table-responsive">

        <form action="" name="formularioProduct" id="formularioProduct" method="POST">
          <!--CLAVE DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Clave(*):</label>
            <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
            <input class="form-control" type="text" name="codigo" id="codigo" maxlength="100" placeholder="Clave" required>
          </div>


          <!--CLAVE DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Fmsi(*):</label>
            <input class="form-control" type="text" name="fmsi" id="fmsi" maxlength="100" placeholder="Fmsi">
          </div>


          <!--CATEGORIA DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Categoria(*):</label>
            <select name="idcategoria" id="idcategoria" class="form-control selectpicker" data-Live-search="true" required>

            <?php 
            
              $sql="SELECT * FROM categoria";
              $result = ejecutarConsulta($sql);

              while($row = $result->fetch_assoc()) {
                echo "<option value='$row[idcategoria]'>$row[nombre]</option>";
              }
            
            ?>
            </select>
          </div>

          <!--MARCA DEL PRODUCTO-->
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label for="">Marca(*):</label>
            <input class="form-control" type="text" name="marca" id="marca" maxlength="100" placeholder="Marca" required>
          </div>

          <!--PROVEEDOR DEL PRODUCTO-->
          <div class="form-group col-lg-6 col-md-8 col-xs-12">
            <label for="">Proveedor(*):</label>
            <select name="idproveedor" id="idproveedor" class="form-control selectpicker" data-live-search="true" required>
              <?php 
                $sql="SELECT * FROM persona WHERE tipo_persona='Proveedor'";
                $result = ejecutarConsulta($sql);

                while($row = $result->fetch_assoc()) {
                  echo "<option value='$row[idpersona]'>$row[nombre]</option>";
                }
            
              ?>
            </select>
          </div>  

          <div class="form-group col-lg-12 col-md-6 col-xs-12">
            <label for="">Descripción(*):</label>
            <input class="form-control" type="text" name="descripcion" id="descripcion" maxlength="500" placeholder="Unidades (JUEGO / SET / PIEZA)" required>
          </div> 

          <!--UNIDADES DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Unidades(*):</label>
            <input class="form-control" type="text" name="unidades" id="unidades" maxlength="50" placeholder="Unidades (JUEGO / SET / PIEZA)" required>
          </div>    

          <!--STOCK DEL PRODUCTO-->
            <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Stock</label>
            <input class="form-control" type="number" name="stock" id="stock"  required>
          </div>    

          <!--PASILLO DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Pasillo(*):</label>
            <input class="form-control" type="text" name="pasillo" id="pasillo" maxlength="50" placeholder="Pasillo / Corredor" required>
          </div>

          <!--COSTOS DEL PRODUCTO-->
          <div class="form-group col-lg-2 col-md-6 col-xs-12">
            <label for="">Costo</label>
            <input class="form-control" type="number" name="costo" id="costo"  required placeholder="$">
          </div>
          <div class="form-group col-lg-2 col-md-6 col-xs-12">
            <label for="">Precio Público</label>
            <input class="form-control" type="number" name="publico" id="publico"  required placeholder="$">
          </div>
          <div class="form-group col-lg-2 col-md-6 col-xs-12">  
            <label for="">Precio Taller</label>
            <input class="form-control" type="number" name="taller" id="taller"  required placeholder="$">
          </div>
          <div class="form-group col-lg-2 col-md-6 col-xs-12">
            <label for="">Precio Credito Taller</label>
            <input class="form-control" type="number" name="credito_taller" id="credito_taller"  required placeholder="$">
          </div>
          <div class="form-group col-lg-2 col-md-6 col-xs-12">
            <label for="">Mayoreo</label>
            <input class="form-control" type="number" name="mayoreo" id="mayoreo"  required placeholder="$">
          </div>    

          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label for="">Codigo barras:</label>
            <input class="form-control" type="text" name="barcode" id="barcode" placeholder="Código del producto" >              
          </div>            
        </form>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardaryeditarProducto()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div class="modal fade" id="solicitarArticulo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h4 class="modal-title">Solicitar Articulo </h4>
      </div>
      <div class="modal-body">
      <div class="modal-body">
        <div class="panel-body table-responsive">

        <form action="" name="formularioProduct" id="formularioProduct" method="POST">
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label for="">Clave:</label>            
            <input class="form-control" type="hidden" name="idarticuloPedido" id="idarticuloPedido">
            <input class="form-control" type="text" name="clave_producto" id="clave_producto" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label for="">Marca:</label>
            <input class="form-control" type="text" name="marcaProducto" id="marcaProducto" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <!--CLAVE DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Cantidad(*):</label>
            <input class="form-control" type="hidden" name="idarticulo" id="idarticulo">
            <input class="form-control" type="number" name="cantidad" id="cantidad" placeholder="Cantidad" required>
          </div>      
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Solicitar para el día:</label>
              <input class="form-control" type="date" name="fechaSolicitud" id="fechaSolicitud" required>
          </div>
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Estado de solicitud</label>
              <select name="estado_solicitud" id="estado_solicitud" class="form-control selectpicker">
              <option value="" disabled selected>Estado de solicitud</option>                    
                <option value="Bajo">Bajo</option>
                <option value="Regular">Regular</option>
                <option value="Urgente">Urgente</option>
              </select>
          </div> 
          <div class="form-group col-lg-12 col-md-6 col-xs-12">
            <label for="">Nota adicional:</label>
            <textarea class="form-control" id="notaAdicional" name="notaAdicional" rows="5" style="width: 100%;"></textarea>
          </div>
        </form>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardarSolicitudArticulo()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div class="modal fade" id="remOrSalida" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Remisionar o dar salida </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body">
      <div class="modal-body">
        <div class="panel-body table-responsive">

        <form action="" name="formularioProduct" id="formularioProduct" method="POST">
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for=""># Folio:</label>                        
            <input class="form-control" type="text" name="folioRem" id="folioRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Cliente:</label>
            <input class="form-control" type="text" name="clienteRem" id="clienteRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Total venta o servicio:</label>
            <input class="form-control" type="text" name="total_ventaRem" id="total_ventaRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <!--CLAVE DEL PRODUCTO-->
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
          <label for="">Remisionar </label>
              <select name="remisionSalida" id="remisionSalida" class="form-control selectpicker">                    
                <option value="1">Remisionar</option>
                <option value="0">No remisionar</option>
              </select>
          </div>
          <div class="form-group col-lg-6 col-md-6 col-xs-12">
            <label for="">Salida:</label>
              <input class="form-control" type="date" name="fecha_salidaRem" id="fecha_salidaRem" required>
          </div>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProveedor" onclick="guardarRemOrSalida()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div class="modal fade" id="editarDetalleVenta" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
      <h4 class="modal-title">Editar detalle venta </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body">
        <div class="modal-body">
          <div class="panel-body table-responsive">
          
              <div class="form-group col-lg-4 col-md-8 col-xs-12">
                <label for="">Cliente(*):</label>
                <select name="idclienteDetalleVenta" id="idclienteDetalleVenta" class="form-control selectpicker" data-live-search="true" required>                   
                  <option value="" disabled selected>Seleccionar cliente</option>
                </select>
              </div>
                                
              <div class="form-group col-lg-3 col-md-4 col-xs-12">
                <label for="">Entrada(*): </label>
                <input class="form-control" type="date" name="fecha_entradaDetalle" id="fecha_entradaDetalle">
              </div>  

              <div class="form-group col-lg-3 col-md-4 col-xs-12">
                <label for="">Salida: </label>
                <input class="form-control" type="date" name="fecha_salidaDetalle" id="fecha_salidaDetalle" required>
              </div>  

              <div class="form-group col-lg-2 col-md-6 col-xs-12">
              <label for="">Remisionar </label>
                  <select name="remisionDetalle" id="remisionDetalle" class="form-control selectpicker">                    
                    <option value="1">Remisionar</option>
                    <option value="0">No remisionar</option>
                  </select>
              </div> 
              <div class="form-group col-lg-12 col-md-6 col-xs-12">
                <br><br><br><br><br><br><br><br><br>
              </div>
                
              <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <button class="btn btn-success" type="button" name="" onclick="guardarDetalleVentaEditar()">Guardar</button>
                <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div class="modal fade" id="modalSolicitudArticulo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 80% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Solicitar Articulo </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>       
      </div>
      <div class="modal-body">
      <div class="modal-body">
        <div class="panel-body table-responsive">

        <form action="" name="formularioProductSolicitud" id="formularioProductSolicitud" method="POST">
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Clave:</label>            
            <input class="form-control" type="hidden" name="idarticuloSolicitud" id="idarticuloSolicitud">
            <input class="form-control" type="text" name="clave_productoSolicitud" id="clave_productoSolicitud" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Marca:</label>
            <input class="form-control" type="text" name="marcaProductoSolicitud" id="marcaProductoSolicitud" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">ID SUCURSAL:</label>
            <input class="form-control" type="text" name="idsucursalSolicitud" id="idsucursalSolicitud" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <!--CLAVE DEL PRODUCTO-->
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Cantidad(*):</label>            
            <input class="form-control" type="number" name="cantidadSolicitud" id="cantidadSolicitud" placeholder="Cantidad" required>
          </div>      
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Solicitar para el día:</label>
              <input class="form-control" type="date" name="fechaProductoSolicitud" id="fechaProductoSolicitud" required>
          </div>
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Estado de solicitud</label>
              <select name="estadoProductoSolicitud" id="estadoProductoSolicitud" class="form-control selectpicker">
              <option value="" disabled selected>Estado de solicitud</option>                    
                <option value="Bajo">Bajo</option>
                <option value="Regular">Regular</option>
                <option value="Urgente">Urgente</option>
              </select>
          </div> 
          <div class="form-group col-lg-12 col-md-6 col-xs-12">
            <label for="">Nota adicional:</label>
            <textarea class="form-control" id="notaAdicionalSolicitud" name="notaAdicionalSolicitud" rows="5" style="width: 100%;"></textarea>
          </div>
        </form>

        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="" onclick="guardarArticuloSolicitud()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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

<div class="modal fade" id="garantias" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 100% !important; box-shadow:5px 5px 5px 5px rgba(0, 0, 0, 0.2);">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Garantias </h4>
        <button name="addProduct" type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
      </div>
      <div class="modal-body">
      <div class="modal-body">
        <div class="panel-body table-responsive">
        <form action="" name="formularioProductosGarantias" id="formularioProductosGarantias" method="POST">
          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for=""># Folio:</label>                        
            <input class="form-control" type="text" name="folioRem" id="folioRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Cliente:</label>
            <input class="form-control" type="text" name="clienteRem" id="clienteRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-4 col-md-6 col-xs-12">
            <label for="">Total venta o servicio:</label>
            <input class="form-control" type="text" name="total_ventaRem" id="total_ventaRem" style="background-color:transparent; border:none;padding: 0px;">            
          </div>

          <div class="form-group col-lg-12 col-md-12 col-xs-12">
            <div class="panel-body table-responsive">
              <table id="detallesGarantias" class="table table-striped table-bordered table-condensed table-hover">
                <thead style="background-color:#A9D0F5; font-size: 12px;">                                              
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
                  <th></th>
                  <th></th>
                </tfoot>
                <tbody>                
                </tbody>
              </table>
            </div>
          </div>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">          
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
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


<div class="modal fade" id="editProductServicioGarantia" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" style="width: 50% !important;">
    <div class="modal-content" style="border-radius: 20px;">
      <div class="modal-header">
        <h4 class="modal-title">Editar producto</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>        
        <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
          <input class="form-control" type="text" id="claveProduct" name="claveProduct" style="border:none; background-color:transparent; color:black"></input>
        </div>
      </div>      
      <form action="" name="formularioProductoServicio" id="formularioProductoServicio" method="POST">
        <div class="panel-body table-responsive">
            <div class="form-group col-lg-2 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">ID ARTICULO</label>
              <input class="form-control" type="hidden" id="idservicioGarantia" name="idservicioGarantia"></input>
              <input class="form-control" type="text" id="idProductoGarantia" name="idProductoGarantia"></input>
            </div>
            <div class="form-group col-lg-6 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Descripción </label>
              <textarea class="form-control" id="descripcionProductoGarantia" name="descripcionProductoGarantia" rows="5" style="width: 280px;" required></textarea>
            </div>
            <div class="form-group col-lg-4 col-md-2 col-xs-6" id="divImpuesto">
              <label for="">Cantidad </label>
              <input class="form-control" type="number" name="cantidadProductoGarantia" id="cantidadProductoGarantia" required>
              <label for="">Precio </label>
              <input class="form-control" type="number" name="precioProductoGarantia" id="precioProductoGarantia" required>
            </div>            
        </div>
      </form>
        <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
          <button class="btn btn-success" type="submit" name="btnGuardarProductoServicio" onclick="editarGuardarProductoGarantia()">Guardar</button>
          <button class="btn btn-danger" type="button" data-dismiss="modal"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
        </div>
      <!--</div>-->
      <div class="modal-footer">
        <button class="btn btn-default" type="button" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>


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

