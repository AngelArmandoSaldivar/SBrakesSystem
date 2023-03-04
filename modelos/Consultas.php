<?php 
//incluir la conexion de base de datos
require "../config/Conexion.php";
class Consultas{


	//implementamos nuestro constructor
public function __construct(){

}

//listar registros
public function comprasfecha($fecha_inicio,$fecha_fin){
	$sql="SELECT DATE(i.fecha_entrada) as fecha, u.nombre as usuario, p.nombre as proveedor, i.tipo_comprobante, i.serie_comprobante, i.total_compra,i.impuesto,i.estado FROM ingreso i INNER JOIN persona p ON i.idproveedor=p.idpersona INNER JOIN usuario u ON i.idusuario=u.idusuario WHERE DATE(i.fecha_entrada)>='$fecha_inicio' AND DATE(i.fecha_entrada)<='$fecha_fin'";
	return ejecutarConsulta($sql);
}

public function ventasfechacliente($fecha_inicio,$fecha_fin){
	$sql="SELECT DATE(v.fecha_entrada) as fecha, u.nombre as usuario, p.nombre as cliente, v.tipo_comprobante, forma_pago,forma_pago2, forma_pago3,v.total_venta, v.impuesto, v.estado,v.pagado, v.idventa, v.fecha_entrada FROM venta v INNER JOIN persona p ON v.idcliente=p.idpersona INNER JOIN usuario u ON v.idusuario=u.idusuario INNER JOIN formas_pago fp ON fp.idventa=v.idventa WHERE DATE(v.fecha_entrada)>='$fecha_inicio' AND DATE(v.fecha_entrada)<='$fecha_fin'";
	return ejecutarConsulta($sql);	
}
public function kardex($fecha_inicio,$fecha_fin){
	//$sql="SELECT DATE(v.fecha_entrada) as fecha, p.nombre, v.idcliente_proveedor, s.nombre AS articuloSucursal, sc.nombre AS sucursalVenta, folio, clave, fmsi, cantidad, importe, estado, tipoMov, idsucursalArticulo FROM kardex v INNER JOIN persona p ON v.idcliente_proveedor=p.idpersona INNER JOIN sucursal s ON idsucursalArticulo = s.idsucursal INNER JOIN sucursal sc ON idsucursalVenta = sc.idsucursal WHERE DATE(v.fecha_entrada)>='$fecha_inicio' AND DATE(v.fecha_entrada)<='$fecha_fin' ORDER BY clave, idkardex DESC";
	$sql="SELECT DATE(v.fecha_entrada) as fecha, p.nombre, v.idcliente_proveedor, s.nombre AS articuloSucursal, sc.nombre AS sucursalVenta, folio, clave, fmsi, cantidad, importe, estado, tipoMov, idsucursalArticulo FROM kardex v INNER JOIN persona p ON v.idcliente_proveedor=p.idpersona INNER JOIN sucursal s ON idsucursalArticulo = s.idsucursal INNER JOIN sucursal sc ON idsucursalVenta = sc.idsucursal ORDER BY clave, idkardex DESC";
	return ejecutarConsulta($sql);
}

public function totalcomprahoy(){
	$sql="SELECT IFNULL(SUM(total_compra),0) as total_compra FROM ingreso WHERE DATE(fecha_hora)=curdate()";
	return ejecutarConsulta($sql);
}

public function totalventahoy(){
	$sql="SELECT IFNULL(SUM(total_venta),0) as total_venta FROM venta WHERE DATE(fecha_entrada)=curdate()";
	return ejecutarConsulta($sql);
}

public function totalserviciohoy(){
	$sql="SELECT IFNULL(SUM(total_servicio),0) as total_servicio FROM servicio WHERE DATE(fecha_entrada)=curdate()";
	return ejecutarConsulta($sql);
}

public function comprasultimos_10dias(){
	$sql="SELECT CONCAT(DAY(fecha_hora),'-',MONTH(fecha_hora)) AS fecha, SUM(total_compra) AS total FROM ingreso GROUP BY fecha_hora ORDER BY fecha_hora DESC LIMIT 0,10";
	return ejecutarConsulta($sql);
}

public function ventas_mensuales(){
	$sql="SELECT DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total FROM venta GROUP BY MONTH(fecha_entrada) ORDER BY fecha_entrada DESC LIMIT 0,1;";
	return ejecutarConsulta($sql);
}

public function ventasultimos_12meses(){
	$sql="SELECT idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_venta) AS total FROM venta GROUP BY MONTH(fecha_entrada) ORDER BY fecha_entrada DESC LIMIT 0,12";
	return ejecutarConsulta($sql);
}

public function serviciossultimos_12meses(){
	$sql="SELECT idsucursal, DATE_FORMAT(fecha_entrada,'%M') AS fecha, SUM(total_servicio) AS total FROM servicio GROUP BY MONTH(fecha_entrada) ORDER BY fecha_entrada DESC LIMIT 0,12";
	return ejecutarConsulta($sql);
}

public function ingresosultimos_12meses(){
	$sql="SELECT idsucursal, DATE_FORMAT(fecha_hora,'%M') AS fecha, SUM(total_compra) AS total FROM ingreso GROUP BY MONTH(fecha_hora) ORDER BY fecha_hora DESC LIMIT 0,12";
	return ejecutarConsulta($sql);
}

public function totalClientes() {
	$sql = "SELECT COUNT(nombre) AS total_clientes FROM persona";
	return ejecutarConsulta($sql);
}

public function totalProductos() {
	$sql = "SELECT COUNT(codigo) AS total_productos FROM articulo";
	return ejecutarConsulta($sql);
}

public function sumaVentaProductos() {
	$sql = "SELECT codigo, SUM(ventas) as totalVetasProductos FROM articulo GROUP BY codigo ORDER BY totalVetasProductos DESC LIMIT 6";
	return ejecutarConsulta($sql);
}

public function ordenCompra($fecha_inicio) {
	$sql = "SELECT mr.descripcion AS descripcionMarca, ar.codigo, op.cantidad, ar.descripcion, ar.costo, op.descuento, op.fecha_orden
			FROM orden_compra op 
			INNER JOIN articulo ar ON op.idproducto = ar.idarticulo
			INNER JOIN marca mr ON ar.marca = mr.idmarca
			WHERE DATE(op.fecha_orden)='$fecha_inicio';";
	return ejecutarConsulta($sql);
}

}

 ?>
