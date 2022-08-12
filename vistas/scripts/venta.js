var tabla;
var pesosMexicanos = Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'});
//funcion que se ejecuta al inicio
function init(){

   mostrarform(false);
   obtener_registros();
   obtener_registrosProductos();
	
	$("#formulario").on("submit",function(e){
		guardaryeditar(e);
	});
	//cargamos los items al select clientes
	selectCliente();

	//Revisamos si la paginación esta en 1 para no mostrar el boton de Anterior
	let cachaPaginaNumber = Number($("#pagina").val());
	if(cachaPaginaNumber <= 1) {
		$("#anterior").hide();
	}

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_entrada").val(today).prop("disabled", false);
}

function ocultarStock() {	
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide();
		$("#botonStock").show();
		$("#thStock").hide();
  		$('td:nth-child(5)').hide();
	}, 400);	
}


function mostrarStock() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide();
		$("#botonStock").hide();
		$("#thStock").show();
  		$('td:nth-child(5)').show();	
	}, 400);	
}

function ocultarMayoreo() {	
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMayoreo").show();
		$("#thMayoreo").toggle();
  		$('td:nth-child(6)').toggle();
	}, 400);	
}

function mostrarMayoreo() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMayoreo").hide();
		$("#thMayoreo").toggle();
		$('td:nth-child(6)').toggle();
	}, 400);  	
}

function ocultarTaller() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonTaller").show();
		$("#thTaller").toggle();
  		$('td:nth-child(7)').toggle();
	}, 400);	
}

function mostrarTaller() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonTaller").hide();
		$("#thTaller").toggle();
		$('td:nth-child(7)').toggle();
	}, 400);  	
}

function ocultarCredito() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCredito").show();
		$("#thCredito").toggle();
  		$('td:nth-child(8)').toggle();
	}, 400);	
}

function mostrarCredito() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCredito").hide();
		$("#thCredito").toggle();
		$('td:nth-child(8)').toggle();
	}, 400);  	
}

function ocultarPublico() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMostrador").show();
		$("#thPublico").toggle();
  		$('td:nth-child(9)').toggle();
	}, 400);	
}

function mostrarPublico() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMostrador").hide();
		$("#thPublico").toggle();
		$('td:nth-child(9)').toggle();
	}, 400);  	
}

function ocultarCosto() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCosto").show();
		$("#thCosto").toggle();
  		$('td:nth-child(10)').toggle();
	}, 400);	
}

function mostrarCosto() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCosto").hide();
		$("#thCosto").toggle();
		$('td:nth-child(10)').toggle();
	}, 400);
}

function ocultarDescripcion() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonDescripcion").show();
		$("#thDescripcion").toggle();
  		$('td:nth-child(4)').toggle();
	}, 400);	
}

function mostrarDescripcion() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonDescripcion").hide();
		$("#thDescripcion").toggle();
		$('td:nth-child(4)').toggle();
	}, 400);
}

function ocultarMarca() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMarca").show();
		$("#thMarca").toggle();
  		$('td:nth-child(3)').toggle();
	}, 400);	
}

function mostrarMarca() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMarca").hide();
		$("#thMarca").toggle();
		$('td:nth-child(3)').toggle();
	}, 400);
}

function ocultarFmsi() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonFmsi").show();
		$("#thFmsi").toggle();
  		$('td:nth-child(2)').toggle();
	}, 400);	
}

function mostrarFmsi() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonFmsi").hide();
		$("#thFmsi").toggle();
		$('td:nth-child(2)').toggle();
	}, 400);
}

function ocultarClave() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonClave").show();
		$("#thClave").toggle();
  		$('td:nth-child(1)').toggle();
	}, 400);	
}

function mostrarClave() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonClave").hide();
		$("#thClave").toggle();
		$('td:nth-child(1)').toggle();
	}, 400);
}

function buscarCotizacion() {
	let busquedaCotizacion = document.getElementById("busquedaCotizacion").value;
	$('.loader').show();
	$.post("../ajax/venta.php?op=buscarCotizacion&busquedaCotizacion=" + busquedaCotizacion,
		function(data,status)
		{
		data=JSON.parse(data);
		if(data == null) {
			$('.loader').hide();
			limpiar();
			swal({
				position: 'top-end',
				type: 'warning',
				title: "Ningun resultado!",
				showConfirmButton: false,
				timer: 900
			});
		} else {
			$('.loader').hide();
			$("#idcliente").val(data.idcliente).prop("disabled", false);
			$("#idcliente").selectpicker('refresh');
			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
			$("#telefono_local").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.credito).prop("disabled", true);
			if(data.tipo_precio == "publico") {
				$("#tipoPrecio").val("Publico / Mostrador").prop("disabled", true);
			} else if(data.tipo_precio == "taller") {
				$("#tipoPrecio").val("Taller").prop("disabled", true);
			} else if(data.tipo_precio == "credito_taller") {
				$("#tipoPrecio").val("Credito Taller").prop("disabled", true);
			} else if(data.tipo_precio == "mayoreo") {
				$("#tipoPrecio").val("Mayoreo").prop("disabled", true);
			}
			detalleCotizacion(data.idcotizacion, data.idsucursal);
		}				
			
	})
	
}

var detalles=0;

function detalleCotizacion(idcotizacion, idsucursal) {
	var cantidad=1;
	var descuento=0;
	var sub = document.getElementsByName("subtotal");

	$(".filas").remove();

	$.post("../ajax/venta.php?op=detallesCotizacion&idcotizacion=" + idcotizacion,
	function(data,status)
	{		
		data = JSON.parse(data);
		for (let index = 0; index < data.length; index++) {
			var precioDouble = parseFloat(data[index].precio_cotizacion);
			var fila='<tr style="font-size:12px" class="filas" id="fila'+index+'">'+
			'<td><button style="width: 40px;" title="Eliminar" type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle('+index+')">X</button></td>'+        
			'<td><input type="hidden" name="clave[]" value="'+data[index].codigo+'"> <input class="form-control" type="hidden" name="idsucursalArticulo[]" value="'+idsucursal+'"> <input class="form-control" type="hidden" name="idarticulo[]" value="'+data[index].idarticulo+'">'+data[index].codigo+'</td>'+
			'<td><input type="hidden" name="fmsi[]" id="fmsi[]" value="'+data[index].fmsi+'">'+data[index].fmsi+'</td>'+
			'<td><input type="hidden" name="marca[]" id="marca[]" value="'+data[index].marca+'">'+data[index].marca+'</td>'+
			'<td><textarea class="form-control" id="descripcion[]" name="descripcion[]" rows="2" style="width: 280px;" value="'+data[index].descripcion+'">'+data[index].descripcion+'</textarea></td>'+
			'<td><input style="width: 55px;" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" max="'+data[index].cantidad+'" min="1"></td>'+		
			'<td><input style="width: 70px;" type="number" name="precio_venta[]" id="precio_venta[]" value="'+precioDouble+'"></td>'+
			'<td><input style="width: 70px;" type="number" name="descuento[]" value="'+descuento+'"></td>'+
			'<td><span id="subtotal'+index+'" name="subtotal" value="'+sub+'"></span></td>'+
			'<td><button type="button" title="Actualizar" onclick="modificarSubtotales()" class="btn btn-info btn-xs" style="width: 40px;"><i class="fa fa-refresh"></i></button></td>'+
			'</tr>';
			$('#detalles').append(fila);
			modificarSubtotales();
			detalles ++;
		}		
		if(detalles > 0) {
			$("#btnGuardar").show();
		} else {
			$("#btnGuardar").hide();
		}
		
	})
}

function mostrarArticulosGarantias(idservicio) {
	$.post("../ajax/venta.php?op=listarDetalleGarantias&id="+idservicio,function(r){
		$('.loader').show();
		if(r.length < 0) {
			$("#detallesGarantias").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detallesGarantias").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});	
}

function mostrarArticuloGarantia(idarticulo, descripcion, cantidad, idventa, total) {	
	$("#idservicioGarantia").val(idventa).prop("disabled", true);
	$("#idProductoGarantia").val(idarticulo).prop("disabled", true);
	$("#descripcionProductoGarantia").val(descripcion).prop("disabled", false);
	$("#cantidadProductoGarantia").val(cantidad).prop("disabled", false);
	$("#precioProductoGarantia").val(total).prop("disabled", false);
}

function editarGuardarProductoGarantia() {
	let idventa = document.getElementById("idservicioGarantia").value;
	let idarticulo = document.getElementById("idProductoGarantia").value;
	let descripcion = document.getElementById("descripcionProductoGarantia").value;
	let cantidad = document.getElementById("cantidadProductoGarantia").value;
	let precioGarantia = document.getElementById("precioProductoGarantia").value;
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);	

	$.ajax({
		url: "../ajax/venta.php?op=guardarGarantia&idventa="+idventa+"&idarticulo="+ idarticulo
		+"&descripcion=" + descripcion + "&cantidad=" + cantidad + "&fecha_hora=" + today + "&precioGarantia=" + precioGarantia,
		type: "POST",
		data: {},
		contentType: false,
		processData: false,
		success: function(datos){
			alert(datos);
			$("#editProductServicioGarantia").modal('hide');	
			mostrarArticulosGarantias(idventa);
		},
	});
}


function mostrarSolicitudArticulo(idarticulo) {
	console.log("Solicitar", idarticulo);
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			console.log(data);
			$("#clave_productoSolicitud").val(data.codigo).prop("disabled", true)
			$("#marcaProductoSolicitud").val(data.marca).prop("disabled", true)
			$("#idarticuloSolicitud").val(data.idarticulo);
			$("#idsucursalSolicitud").val(data.idsucursal);			
	})	
}

function guardarArticuloSolicitud() {	
	var idarticulo = $("#idarticuloSolicitud").val();
	var marcaproducto = $("#marcaProductoSolicitud").val();
	var claveProducto = $("#clave_productoSolicitud").val();
	var cantidad = $("#cantidadSolicitud").val();
	var fechaSolicitud = $("#fechaProductoSolicitud").val();
	var estadoSolicitud = $("#estadoProductoSolicitud").val();
	var notaAdicional = $("#notaAdicionalSolicitud").val();
	var idsucursalProducto = $("#idsucursalSolicitud").val();

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);	
	
	$.ajax({
		url: "../ajax/venta.php?op=guardarSolicitud&idarticulo=" + idarticulo + "&marca=" + marcaproducto+ 
		"&clave=" + claveProducto + "&cantidad=" + cantidad + "&fecha=" + fechaSolicitud + "&estadoPedido=" + estadoSolicitud +
		"&nota=" + notaAdicional + "&fecha_registro=" + today + "&idsucursalProducto=" + idsucursalProducto,
		method: "POST",
		success: function(data) {
			alert("Pedido guardado correctamente")
			$("#idarticuloSolicitud").val("");
			$("#marcaProductoSolicitud").val("");
			$("#clave_productoSolicitud").val("");
			$("#cantidadSolicitud").val("");			
			$("#fechaProductoSolicitud").val("");
			$("#estadoProductoSolicitud").val("");
			$("#notaAdicionalSolicitud").val("");
			$("#idsucursalSolicitud").val("");
			$("#modalSolicitudArticulo").modal('hide');			
		}		
	});
	
}

function onBlurText() {
	//var busqueda = document.getElementById("searchSelect").value;
	var busqueda = $("#searchSelect").val();
	console.log("BUSQUEDA: ", busqueda);
	$.ajax({
		url : '../ajax/persona.php?op=listarClientes',
		type : 'POST',
		dataType : 'html',
	}).done(function(data){
		let array = JSON.parse(data);
		const filterItems = query => {
			return array.filter((el) =>
				el.toLowerCase().indexOf(query.toLowerCase()) > -1
			);
		}
		if(filterItems(busqueda).length > 0) {
			console.log("Si existe en la bd");
		} else {
			$("#agregarCliente").modal('show');
			$("#nombre").val(busqueda);
			
		}
	})
}

/*========================================================================================== */
/*===============================FILTROS==================================================== */
/*========================================================================================== */

function paginaSiguiente() {	

	let cachaPaginaNumber = Number($("#pagina").val());
	cachaPaginaNumber = cachaPaginaNumber + 1;
	$('.loaderSearch').show();

	document.getElementById("pagina").value=cachaPaginaNumber;
	if(cachaPaginaNumber > 1) {
		$("#anterior").show();
	}
	obtenerRegistrosSiguiente(cachaPaginaNumber);
}

function obtenerRegistrosSiguiente(pagina) {

	window.scroll({
		top: 50,
		left: 0,
		behavior: 'smooth'
	});
	
	let busqueda = $("#busqueda").val();		
	obtener_registros(busqueda);

}

function paginaAnterior() {
	let cachaPaginaNumber = Number($("#pagina").val());	
	cachaPaginaNumber = cachaPaginaNumber - 1;
	document.getElementById("pagina").value=cachaPaginaNumber;
	$('.loaderSearch').show();

	if(cachaPaginaNumber <= 1) {
		$("#anterior").hide();
	}	
	obtenerRegistrosAnterior(cachaPaginaNumber);
}

function obtenerRegistrosAnterior(pagina) {

	window.scroll({
		top: 50,
		left: 0,
		behavior: 'smooth'
	});
	let busqueda = $("#busqueda").val();	
	obtener_registros(busqueda);
	
}

//LIMITE REGISTROS
function mostrarRegistrosLimite(limites) {
	let busqueda = $("#busqueda").val();
	obtener_registros(busqueda)
}

var select = document.getElementById('limite_registros');
select.addEventListener('change',
function(){
	$('.loaderSearch').show();
	let selectedOption = this.options[select.selectedIndex];
	let limites = selectedOption.value;

	mostrarRegistrosLimite(limites);

});

//FECHA INICIO
function registrosFechaInicio(fechas) {
	let busqueda = $("#busqueda").val();
	obtener_registros(busqueda);
}

$("#fecha_inicio").change(fechaInicio);
function fechaInicio() {
	$('.loaderSearch').show();	
	fechas = $("#fecha_inicio").val();
	registrosFechaInicio(fechas);
}

//FECHA FIN
function registrosFechaFin(fechas) {
	let busqueda = $("#busqueda").val();
	obtener_registros(busqueda)
}

$("#fecha_fin").change(fechaFin);
function fechaFin() {
	$('.loaderSearch').show();
	fechas = $("#fecha_fin").val();
	registrosFechaFin(fechas);
}

function activarPopover() {
	$(function () {
		$('[data-toggle="popover"]').popover()		
	})
}

//funcion listar REGISTROS INGRESOS CREADOS
function obtener_registros(ventas){

	let limitesRegistros = $("#limite_registros").val();
	let paginado = $("#pagina").val();

	let fecha_inicio = $("#fecha_inicio").val();
	let fecha_fin = $("#fecha_fin").val();	

	//FILTROOS BUSQUEDA
	if(ventas != "" && limitesRegistros == null && paginado == 1 && fecha_inicio == "" && fecha_fin == "") {		
		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : { ventas: ventas},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	} 
	if(ventas == "" && limitesRegistros == null && paginado == 1 && fecha_inicio == "" && fecha_fin == "") {
		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros > 0 && paginado == 1 && fecha_inicio == "" && fecha_fin == "") {
		console.log("Llegaste 3");
		$('.loaderSearch').show();
		$("#siguiente").show();

		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {ventas:ventas, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})

	}
	if(ventas == "" && limitesRegistros > 0 && paginado == 1 && fecha_inicio == "" && fecha_fin == "") {
		$('.loaderSearch').show();
		$("#siguiente").show();

		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros > 0 && paginado == 1 && fecha_inicio != "" && fecha_fin == "") {
		$('.loaderSearch').show();
		$("#siguiente").show();

		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {ventas:ventas, total_registros: limitesRegistros, fecha_inicio: fecha_inicio},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	}	
	if(ventas != "" && limitesRegistros == null && paginado == 1 && fecha_inicio != "" && fecha_fin == "") {
		console.log("Llegaste 5");
		let total_registros = Number(((limitesRegistros * paginado) - paginado) + 1);		
		let inicio_registros = (total_registros - limitesRegistros) + 1;
	
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:ventas, fecha_inicio: fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros == null && paginado == 1 && fecha_inicio != "" && fecha_fin == "") {

		console.log("Llegaste 6");

		let total_registros = Number(((limitesRegistros * paginado) - paginado) + 1);		
		let inicio_registros = (total_registros - limitesRegistros) + 1;
	
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:ventas, fecha_inicio: fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros > 0 && paginado == 1 && fecha_inicio != "" && fecha_fin == "") {
		$('.loaderSearch').show();
		$("#siguiente").show();

		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {ventas:ventas, total_registros: limitesRegistros, fecha_inicio: fecha_inicio},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros == null && paginado > 1 && fecha_inicio == "" && fecha_fin == "") {		
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - 5);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros:inicio_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros == null && paginado > 1 && fecha_inicio == "" && fecha_fin == "") {
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - 5);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, inicio_registros:inicio_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros != null && paginado > 1 && fecha_inicio == "" && fecha_fin == "") {
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, inicio_registros:inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros != null && paginado > 1 && fecha_inicio != "" && fecha_fin == "") {		
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, inicio_registros:inicio_registros, total_registros: limitesRegistros, fecha_inicio:fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas != "" && limitesRegistros == null && paginado > 1 && fecha_inicio != "" && fecha_fin == "") {		
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, inicio_registros:inicio_registros, fecha_inicio:fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros == null && paginado > 1 && fecha_inicio != "" && fecha_fin == "") {			
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros:inicio_registros, fecha_inicio:fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros != null && paginado > 1 && fecha_inicio == "" && fecha_fin == "") {			
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros:inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	if(ventas == "" && limitesRegistros != null && paginado > 1 && fecha_inicio != "" && fecha_fin == "") {			
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros:inicio_registros, total_registros: limitesRegistros, fecha_inicio: fecha_inicio},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}

	//FECHAS FIN
	//Solo fecha fin, pagina 1
	if(ventas == "" && limitesRegistros == null && paginado == 1 && fecha_fin != "" && fecha_inicio == "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_fin: fecha_fin},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, busqueda, pagina 1
	if(ventas != "" && limitesRegistros == null && paginado == 1 && fecha_fin != "" && fecha_inicio == "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:ventas, fecha_fin: fecha_fin},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, busqueda, limite, pagina 1
	if(ventas != "" && limitesRegistros != null && paginado == 1 && fecha_fin != "" && fecha_inicio == "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:ventas, fecha_fin: fecha_fin, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin,limite, pagina 1
	if(ventas == "" && limitesRegistros != null && paginado == 1 && fecha_fin != "" && fecha_inicio == "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_fin: fecha_fin, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha inicio, fecha fin
	if(ventas == "" && limitesRegistros == null && paginado == 1 && fecha_fin != "" && fecha_inicio != "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha inicio, fecha fin, busquedas
	if(ventas != "" && limitesRegistros == null && paginado == 1 && fecha_fin != "" && fecha_inicio != "") {
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, busqueda: ventas},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha inicio, fecha fin, busquedas, limites
	if(ventas != "" && limitesRegistros != null && paginado == 1 && fecha_fin != "" && fecha_inicio != "") {
		let total_registros = Number(limitesRegistros * paginado);		
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, busqueda: ventas, total_registros: total_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha inicio, fecha fin, limites
	if(ventas == "" && limitesRegistros != null && paginado == 1 && fecha_fin != "" && fecha_inicio != "") {
		let total_registros = Number(limitesRegistros * paginado);		
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, total_registros: total_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, pagina
	if(ventas == "" && limitesRegistros == null && paginado > 1 && fecha_fin != "" && fecha_inicio == "") {
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - 5);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_fin: fecha_fin, inicio_registros: inicio_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, pagina, limites
	if(ventas == "" && limitesRegistros != null && paginado > 1 && fecha_fin != "" && fecha_inicio == "") {
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_fin: fecha_fin, inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, pagina, limites
	if(ventas == "" && limitesRegistros != null && paginado > 1 && fecha_fin != "" && fecha_inicio != "") {
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {fecha_inicio:fecha_inicio, fecha_fin: fecha_fin, inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, pagina, limites
	if(ventas != "" && limitesRegistros != null && paginado > 1 && fecha_fin != "" && fecha_inicio != "") {
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, fecha_inicio:fecha_inicio, fecha_fin: fecha_fin, inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha fin, pagina, limites, busqueda
	if(ventas != "" && limitesRegistros != null && paginado > 1 && fecha_fin != "" && fecha_inicio == "") {		
		let total_registros = Number(limitesRegistros * paginado);
		let inicio_registros = (total_registros - limitesRegistros);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, fecha_fin: fecha_fin, inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
	//Fecha inicio, fecha fin, pagina, busquedas
	if(ventas != "" && limitesRegistros == null && paginado > 1 && fecha_fin != "" && fecha_inicio != "") {
		let total_registros = Number(5 * paginado);
		let inicio_registros = (total_registros - 5);
		$.ajax({
			url : '../ajax/venta.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda: ventas, fecha_inicio: fecha_inicio, fecha_fin: fecha_fin, inicio_registros: inicio_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})
	}
}

$(document).on('keyup', '#busqueda', function(){

	var valorBusqueda=$(this).val();
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);
	}
	else
	{
		obtener_registros();
	}
});

setInterval(() => {
	let busqueda = document.getElementById("busqueda").value;
	$('.loaderSearch').show();
	setTimeout(() => {
		obtener_registros(busqueda);
		$('.loaderSearch').hide();
	}, 500);
}, 5000);

/*========================================================================================== */
/*===============================FIN FILTROS================================================ */
/*========================================================================================== */

function mostrarInfoClient(idcliente) {
	$('.loader').show();
	$.post("../ajax/venta.php?op=mostrarInfoClient",{idcliente : idcliente},
		function(data,status)
		{
			$('.loader').hide();
			data=JSON.parse(data);
			$("#idcliente").val(idcliente).prop("disabled", false);
			$("#idcliente").selectpicker('refresh');
			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
			$("#telefono_local").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.credito).prop("disabled", true);
			if(data.tipo_precio == "publico") {
				$("#tipoPrecio").val("Publico / Mostrador").prop("disabled", true);
			} else if(data.tipo_precio == "taller") {
				$("#tipoPrecio").val("Taller").prop("disabled", true);
			} else if(data.tipo_precio == "credito_taller") {
				$("#tipoPrecio").val("Credito Taller").prop("disabled", true);
			} else if(data.tipo_precio == "mayoreo") {
				$("#tipoPrecio").val("Mayoreo").prop("disabled", true);
			}
		});
		setTimeout(() => {
			window.scroll({
				top: 250,
				left: 0,
				behavior: 'smooth'
			});
		}, 1200);
}

$("#fecha_salida").change(fechaSalida);
function fechaSalida() {
	setTimeout(() => {
		window.scroll({
			top: 450,
			left: 0,
			behavior: 'smooth'
		});
	}, 1200);
}

function selectCliente() {
	
	//cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function(r){
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

	$.post("../ajax/venta.php?op=selectCliente", function(r){
		$("#idclienteDetalleVenta").html(r);
		$('#idclienteDetalleVenta').selectpicker('refresh');
	});

	$("#idcliente").change(modIdCliente);
	function modIdCliente() {
		var idcliente = $("#idcliente option:selected").val();
		mostrarInfoClient(idcliente);
	}
}

//funcion limpiar
function limpiar(){

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_entrada").val(today).prop("disabled", false);	
	$("#btnAddArt").show();
	$("#btnGuardar").show();
	$("#idcliente").val("").prop("disabled", false);
	$("#busquedaCotizacion").val("").prop("disabled", false);
	$("#idcliente").selectpicker('refresh');
	$("#tipo_comprobante").val("").prop("disabled", false);
	$("#tipo_comprobante").selectpicker('refresh');	
	$("#tipo_precio").val("").prop("disabled", false);
	$("#tipo_precio").selectpicker('refresh');
	$("#telefono_local").val("").prop("disabled", false);		
	$("#impuesto").val("").prop("disabled", false);	
	$("#estado").val("").prop("disabled", false);
	$("#rfc").val("").prop("disabled", false);
	$("#direccion").val("").prop("disabled", false);			
	$("#email").val("").prop("disabled", false);
	$("#telefono").val("").prop("disabled", false);
	$("#credito").val("").prop("disabled", false);
	$("#tipoPrecio").val("").prop("disabled", false);
	$("#idventa").val("")

	$(".filas").remove();

	//document.getElementById("filas").remove;
	$("#total").html("0");

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Factura");
	$("#tipo_comprobante").selectpicker('refresh');	

}

//funcion mostrar formulario
function mostrarform(flag){
	if(flag){
		//console.log("CHECK: ", $("#flexCheckDefault")[0].checked);
		//Ocultamos detalle_cobro
		$("#detalle_cobro").hide();
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		obtener_registros();

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();
		$("#btnAgregarArticulosEdit").hide();
		
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

function crearVenta() {	
	mostrarform(true);
	$("#btnGuardar").show();
	$("#btnBuscadorCotizacion").show();
	$("#buscadorCotizador").show();
	$("#btnAgregarArticulo").show();
	$("#addCliente").show();
	$("#editarDetalleServicio").hide();
	$("#divDetallesVenta").show();
	$("#remision").val("1").prop("disabled", false);
	$("#remision").selectpicker('refresh');
	$("#fecha_salida").val("").prop("disabled", false);	
	limpiar();
}

//cancelar form
function salirForm() {
	limpiar();
	mostrarform(false);	
}

function obtener_registrosProductos(productos){
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductos',
		type : 'POST',
		dataType : 'html',
		data : { productos: productos, types: "publico" },
	})
	.done(function(resultado){
		$("#tabla_resultadoProducto").html(resultado);
	})
}

$(document).on('keyup', '#busquedaProduct', function(){	
	var valorBusqueda=$(this).val();
	
	if (valorBusqueda!="")
	{	
		obtener_registrosProductos(valorBusqueda);
	}
	else
	{
		obtener_registrosProductos();
	}
});

function obtener_registrosProductosEdit(productosEdit){
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductosEdit',
		type : 'POST',
		dataType : 'html',
		data : { productosEdit: productosEdit, types: "publico" },
	})
	.done(function(resultado){
		$("#tabla_resultadoProductoEdit").html(resultado);
	})
}

$(document).on('keyup', '#busquedaProductEdit', function(){	
	var valorBusqueda=$(this).val();

	console.log(valorBusqueda);
	
	if (valorBusqueda!="")
	{	
		obtener_registrosProductosEdit(valorBusqueda);
	}
	else
	{
		obtener_registrosProductosEdit();
	}
});

function almacenEdit(productosEdit){
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductosAlmacenEdit',
		type : 'POST',
		dataType : 'html',
		data : { productosEdit: productosEdit, types: "publico" },
	})
	.done(function(resultado){
		$("#tabla_resultadoProductoAlmacenEdit").html(resultado);
	})
}

$(document).on('keyup', '#busquedaProductAlmacenEdit', function(){	
	var valorBusqueda=$(this).val();

	console.log(valorBusqueda);
	
	if (valorBusqueda!="")
	{	
		almacenEdit(valorBusqueda);
	}
	else
	{
		almacenEdit();
	}
});

var select = document.getElementById('almacenId');
	select.addEventListener('change',
	function(){
		//$('.loaderSearch').show();
		let productos = document.getElementById("busquedaProductAlmacen").value;
		let selectedOption = this.options[select.selectedIndex];
		let limites = selectedOption.value;		
		obtener_registrosProductos_almacen(productos, limites);

	});

function obtener_registrosProductos_almacen(productos, almacenId){	

	almacenId = document.getElementById("almacenId").value;

	if(productos != "" && almacenId == "") {		
		$.ajax({
			url : '../ajax/venta.php?op=listarProductosSucursal',
			type : 'POST',
			dataType : 'html',
			data : { productos: productos, types: "publico" },
		})
		.done(function(resultado){
			$("#tabla_resultadoProducto_almacen").html(resultado);
		})
	}
	if(productos != "" && almacenId != "") {		
		$.ajax({
			url : '../ajax/venta.php?op=listarProductosSucursal',
			type : 'POST',
			dataType : 'html',
			data : { productos: productos, types: "publico", idsucursal:  almacenId },
		})
		.done(function(resultado){
			$("#tabla_resultadoProducto_almacen").html(resultado);
		})
	}

}

$(document).on('keyup', '#busquedaProductAlmacen', function(){	
	var valorBusqueda=$(this).val();
	
	if (valorBusqueda!="")
	{	
		obtener_registrosProductos_almacen(valorBusqueda);
	}
	else
	{
		obtener_registrosProductos_almacen();
	}
});

/*setInterval(() => {	
	let productos = document.getElementById("busquedaProduct").value;		
	let productosEdit = document.getElementById("busquedaProductEdit").value;
	let productosAlmacenEdit = document.getElementById("busquedaProductAlmacenEdit").value;
	let productosAlmacen = document.getElementById("busquedaProductAlmacen").value;
	
	obtener_registrosProductos(productos);
	obtener_registrosProductosEdit(productosEdit);
	almacenEdit(productosAlmacenEdit);
	obtener_registrosProductos_almacen(productosAlmacen);

}, 5000);*/

//funcion para guardaryeditar
function guardaryeditar(e){
	e.preventDefault();//no se activara la accion predeterminada      

	let is_rem = document.getElementById("remision").value;
	let fecha_salida = document.getElementById("fecha_salida").value

	if(is_rem == 1 && fecha_salida == "") {
		swal({
			position: 'top-end',
			type: 'warning',
			title: "Debes ingresar una fecha de salida!",
			showConfirmButton: false,
			timer: 1500
		});
	} else {
		var formData=new FormData($("#formulario")[0]);	

		$.ajax({
		url: "../ajax/venta.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		beforeSend: function() {
			$('.loader').show();
		},
		contentType: false,
		processData: false,

		success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: datos,
				showConfirmButton: false,
				timer: 1500
			});		 
			mostrarform(false);
			obtener_registros();
			limpiar();
		},
			complete: function() {
			$('.loader').hide();
			$.post("../ajax/venta.php?op=ultimaVenta",
			function(data,status)
			{
				data=JSON.parse(data);			
				let idVenta = document.getElementById("idventa").value;			
				if(idVenta == "" && data.is_remision == 1 && data.fecha_salida != '0000-00-00 00:00:00') {
					agregarRemision(data.idventa)
				}
			});
		},
		dataType: 'html'
		});
	}		
}

function remisionarOrSalida(idventa) {
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
	function(data,status)
	{
		console.log("DATA: ", data);
		data=JSON.parse(data);
		if(data.fecha_salida != null) {
			$("#fecha_salidaRem").val(data.fecha_salida).prop("disabled", true);			
			$("#remisionSalida").val(data.remision).prop("disabled", false);
			$("#remisionSalida").selectpicker('refresh');
			$("#folioRem").val(data.idventa).prop("disabled", false);
			$("#clienteRem").val(data.cliente).prop("disabled", false);
			$("#total_ventaRem").val(data.total_venta).prop("disabled", false);
		} else {
			$("#fecha_salidaRem").val(data.fecha_salida).prop("disabled", false);
			$("#remisionSalida").val(data.remision).prop("disabled", false);
			$("#remisionSalida").selectpicker('refresh');
			$("#folioRem").val(data.idventa).prop("disabled", false);
			$("#clienteRem").val(data.cliente).prop("disabled", false);
			$("#total_ventaRem").val(data.total_venta).prop("disabled", false);
		}		
	})	
}

function guardarRemOrSalida() {
	let fecha_salida = document.getElementById("fecha_salidaRem").value;
	let is_rem = document.getElementById("remisionSalida").value;
	let idventa = document.getElementById("folioRem").value;

	if(!fecha_salida && is_rem == 1) {
		console.log("Ingresar fecha de salida");
	} else if(fecha_salida != "" && is_rem == 1) {
		agregarRemision(idventa);
		actualizarFechaSalida(idventa, fecha_salida);
	} else if(fecha_salida != "" && is_rem == 0) {
		actualizarFechaSalida(idventa, fecha_salida);
	}
}

function actualizarFechaSalida(idventa, fecha_salida) {
	$.ajax({
		url: "../ajax/venta.php?op=editarFechaSalida&idventa="+idventa+"&fechaSalida="+ fecha_salida,
		type: "POST",
		data: {},
		contentType: false,
		processData: false,

		success: function(datos){

			console.log("Fecha de salida actualizada: ", datos);
			$("#remOrSalida").modal('hide');
			obtener_registrosProductos();
			/*swal({
				position: 'top-center',
				type: 'warning',
				title: 'Fecha de salida actualizada!',
				showConfirmButton: false,
				timer: 1500
			});
			$("#remOrSalida").modal('hide');
			obtener_registrosProductos();	*/	
		},
	});
}

function agregarRemision(idventa) {	
	$.post("../ajax/venta.php?op=maxRemision",
		function(data,status)
		{
		let sumaRem = Number(data) + 1;		
		$.ajax({
			url: "../ajax/venta.php?op=editarRemision&idventa="+idventa+"&remision="+ sumaRem,
			type: "POST",
			data: {},
			contentType: false,
			processData: false,
	
			success: function(datos){   
				$("#remOrSalida").modal('hide');
				obtener_registrosProductos();	
				window.open(
					`../reportes/exTicket.php?id=${idventa}`,
					'_blank'
				);					
			},
		});
		});
}

function mostrarDetalleVentaEdit() {	
	let idventa = document.getElementById("idventa").value;
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
	function(data,status)
	{
		data=JSON.parse(data);
		console.log("DATA: ", data);
		$("#idclienteDetalleVenta").val(data.idcliente).prop("disabled", false);
		$("#idclienteDetalleVenta").selectpicker('refresh');
		$("#fecha_salidaDetalle").val(data.fecha_salida).prop("disabled", false);
		$("#fecha_entradaDetalle").val(data.fecha_entrada).prop("disabled", false);
		$("#remisionDetalle").val(data.is_remision).prop("disabled", false);
		$("#remisionDetalle").selectpicker('refresh');
	})
}


function actualizarStatusRem(idventa, remision) {
	$.ajax({
		url: "../ajax/venta.php?op=actualizarStatusRem&idventa="+idventa+"&remision="+ remision,
		type: "POST",
		data: {},
		contentType: false,
		processData: false,

		success: function(datos){			
			console.log("Remision actualizada");
			$("#editarDetalleVenta").modal('hide');
			viewClient(idventa);
		},
	});
}

function actualizarClienteVenta(idventa, cliente, entrada) {
	$.ajax({
		url: "../ajax/venta.php?op=guardarDetalleVentaEdit&idventa="+idventa+"&cliente="+ cliente + "&entrada=" + entrada,
		type: "POST",
		data: {},
		contentType: false,
		processData: false,

		success: function(datos){			
			swal({
				position: 'top-end',
				type: 'success',
				title: datos,
				showConfirmButton: false,
				timer: 1500
			});	
			$("#editarDetalleVenta").modal('hide');
			viewClient(idventa);
		},
	});
}

function cerrarModal() {
	$("#myModal").modal('hide');
}

function cerrarModalEdit() {
	$("#myModalProductsEdit").modal('hide');
}

function regresarMiSucursal() {
	$("#agregarProductoAlmacen").modal('hide');
}

function cerrarSucursalesEdit() {
	$("#myModalProductsAlmacenEdit").modal('hide');
}

function limpiarFormCliente() {
	$("#nombre").val("").prop("disabled", false);
	$("#tipo_precioCliente").val("").prop("disabled", false);
	$("#tipo_precioCliente").selectpicker('refresh');
	$("#direccionCliente").val("").prop("disabled", false);
	$("#telefonoCliente").val("").prop("disabled", false);
	$("#telefono_localCliente").val("").prop("disabled", false);
	$("#emailCliente").val("").prop("disabled", false);
	$("#rfcCliente").val("").prop("disabled", false);
	$("#creditoCliente").val("").prop("disabled", false);
}

function guardarCliente() {
	var formData=new FormData($("#formCliente")[0]);
	var nombreCliente = document.getElementById("nombre").value;
	var tipo_precio = document.getElementById("tipo_precioCliente").value;
	var direccion = document.getElementById("direccionCliente").value;
	var telefono = document.getElementById("telefonoCliente").value;
	var telefono_local = document.getElementById("telefono_localCliente").value;
	var email = document.getElementById("emailCliente").value;
	var rfc = document.getElementById("rfcCliente").value;
	var credito = document.getElementById("creditoCliente").value;

	if(nombreCliente == "" || telefono == "") {
		swal({
			position: 'top-end',
			type: 'warning',
			title: 'Nombre y telefono requeridos!',
			showConfirmButton: false,
			timer: 1500
		});
	} else {

		$.ajax({
			url: "../ajax/venta.php?op=guardarCliente&nombreCliente=" + nombreCliente + "&tipo_precio=" + tipo_precio + 
			"&direccion="+direccion + "&telefono=" + telefono + "&telefono_local=" + telefono_local + 
			"&email="+email + "&rfc=" + rfc + "&credito="+credito,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,

			success: function(datos){

				console.log(datos);

				selectCliente();
				limpiarFormCliente();
				$("#agregarCliente").modal('hide');
				console.log("NOMBRE CLIENTE: ", nombreCliente);
				$.post("../ajax/venta.php?op=ultimoCliente&nombreCliente=" + nombreCliente,
				function(data,status)
				{
					let idCliente = data.replace(/['"]+/g, '');
					console.log("ID CLIENTE: ", idCliente);
					mostrarInfoClient(idCliente);				
				});
			}		 
		});

	}   
}

function guardaryeditarProducto() {
	var formData=new FormData($("#formularioProduct")[0]);

     $.ajax({
     	url: "../ajax/articulo.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
			selectProvider();
			$("#formulario")[0].reset();
			$("#formularioProduct")[0].reset();	
			$("#agregarProducto").modal('hide');
     	}
     });
}

function number_format (number, decimals, dec_point, thousands_sep) {
    // Strip all characters but numerical ones.
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}

function viewClient(idventa) {
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
		function(data,status)
		{
			data=JSON.parse(data);
			$('.loader').hide();
			$("#detalle_cobro").show();
			$("#divImpuesto").hide();
			$("#addCliente").hide();

			let estadoVenta = "";

			data.pagado < parseInt(data.total_venta) ? "PENDIENTE" : "PAGADO";
			if(data.estado == "PENDIENTE") {
				estadoVenta = data.pagado < parseInt(data.total_venta) ? "PENDIENTE" : "PAGADO";
			} else {
				estadoVenta = "ANULADO";
			}			
			$("#buscadorCotizador").hide();
			$("#btnBuscadorCotizacion").hide();
			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#remision").val(data.is_remision).prop("disabled", true);
			$("#remision").selectpicker('refresh');				
			$("#fecha_entrada").val(data.fecha_entrada).prop("disabled", true);
			$("#fecha_salida").val(data.fecha_salida).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#estado").val(estadoVenta).prop("disabled", true);
			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);			
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
			$("#telefono_local").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.telefono_local).prop("disabled", true);
			$("#tipoPrecio").val(data.tipo_precio).prop("disabled", true);
			$("#idventa").val(data.idventa);
			$("#tipo_precio").val(data.tipo_precio).prop("disabled", true);			
			$("#tipo_precio").selectpicker('refresh');			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnRegresar").show();			
		});
}

function guardarDetalleVentaEditar() {
	var formData=new FormData($("#formulario")[0]);	
	let idventa = document.getElementById("idventa").value;
	let cliente = document.getElementById("idclienteDetalleVenta").value;
	let entrada = document.getElementById("fecha_entradaDetalle").value;
	let salida = document.getElementById("fecha_salidaDetalle").value;
	let is_rem = document.getElementById("remisionDetalle").value;
	let remision = "";

	$('.loader').show();

	if(!salida && is_rem == 1) {
		$('.loader').hide();
		swal({
			title: "Un momento!",
			text: 'Debe ingresar una fecha de salida.',
			type: 'warning',
			showConfirmButton: true,
			//timer: 1500
		})
	} else if(salida != null && is_rem == 1){
		$.ajax({
			url: "../ajax/venta.php?op=guardarDetalleVenta&idventa="+idventa+"&remision="+ remision + "&fecha_entrada=" + 
				entrada + "&fecha_salida="+salida + "&idcliente=" + cliente + "&is_rem=" + is_rem,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
	
			success: function(datos){
			   $('.loader').hide();
			   console.log("Detalle guardado correctamente");
				$("#editarDetalleVenta").modal('hide');
				agregarRemision(idventa);
				viewClient(idventa);
			}
		})	
		.fail(function (jqXHR, textStatus, error) {
			console.log("ERROR: ", jqXHR.responseText);
		});
	} else if(salida != null && is_rem == 0) {
		$.ajax({
			url: "../ajax/venta.php?op=guardarDetalleVenta&idventa="+idventa+"&remision="+ "0" + "&fecha_entrada=" + 
				entrada + "&fecha_salida="+salida + "&idcliente=" + cliente + "&is_rem=" + is_rem,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
	
			success: function(datos){
				console.log("DATOS: ", datos);
			   $('.loader').hide();
			   console.log("Detalle guardado correctamente 2");
				$("#editarDetalleVenta").modal('hide');
				viewClient(idventa);
			}
		})	
		.fail(function (jqXHR, textStatus, error) {
			// Handle error here
			console.log("ERROR: ", jqXHR.responseText);
		});
	}

}

function limpiarPagoForm() {
	$("#idpago").val("").prop("disabled", false);
	$("#importeCobro").val("").prop("disabled", false);
	$("#metodoPago").val("").prop("disabled", false);
	$("#metodoPago").selectpicker('refresh');
	$("#banco").val("").prop("disabled", false);
	$("#banco").selectpicker('refresh');
	$("#referenciaCobro").val("").prop("disabled", false);
}

function mostrarPagos(idventa) {
	console.log("LLEGASTE: ", idventa);
	$.post("../ajax/venta.php?op=listarDetalleCobro&id="+idventa,function(r){			
		$('.loader').show();		
		if(r.length < 0) {
			$("#detallesPagos").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detallesPagos").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});
}
function mostrarPagosEdit(idventa) {
	console.log("LLEGASTE: ", idventa);
	$.post("../ajax/venta.php?op=listarEditarDetalleCobro&id="+idventa,function(r){			
		$('.loader').show();		
		if(r.length < 0) {
			$("#detallesPagos").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detallesPagos").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});
}

function eliminarCobro(idcobro, importe, idventa) {
	swal({
		title: '¿Está seguro de eliminar el pago?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar!'
	  }).then(function(result){
		if(result.value){
			$.ajax({
				url: "../ajax/venta.php?op=eliminarCobro&idcobro=" + idcobro + "&importe=" + importe + "&idventa=" + idventa,
				type: "POST",
				data: "",
				contentType: false,
				processData: false,

				success: function(datos){				
				swal({
					title: datos,
					text: 'Se elimino correctamente el pago.',
					type: 'success',
					showConfirmButton: true,
					//timer: 1500
				})
				mostrarPagosEdit(idventa);
				},
			});
		}
	})
		
}

function detallesVenta(idventa) {	
	$.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){			
		$('.loader').show();
		if(r.length < 0) {
			$("#detalles").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detalles").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});
}

function detallesVentaAnulado(idventa) {	
	$.post("../ajax/venta.php?op=listarDetalleAnulado&id="+idventa,function(r){			
		$('.loader').show();
		if(r.length < 0) {
			$("#detalles").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detalles").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});
}

function mostrar(idventa){
	mostrarform(true);
	$('.loader').show();
	viewClient(idventa);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").hide();
	$("#btnAgregarArticulo").hide();	
	$("#btnGuardar").hide();
	$("#btnAddPago").hide();
	$("#divDetallesVenta").show();
	$("#editarDetalleServicio").hide();
	detallesVenta(idventa);
	mostrarPagos(idventa);
}

function mostrarAnulado(idventa) {
	mostrarform(true);
	$('.loader').show();
	viewClient(idventa);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").hide();
	$("#btnAgregarArticulo").hide();	
	$("#btnGuardar").hide();
	$("#btnAddPago").hide();	
	detallesVentaAnulado(idventa);
	mostrarPagos(idventa);
}

function cobrarVenta(idventa){
	mostrarform(true);
	$('.loader').show();
	viewClient(idventa);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").hide();
	$("#btnAgregarArticulo").hide();
	$("#btnEliminarCobro").hide();
	$("#btnGuardar").hide();
	$("#btnAddPago").show();
	$("#divDetallesVenta").hide();
	$("#editarDetalleServicio").hide();
	detallesVenta(idventa);
	mostrarPagosEdit(idventa);
}
function infoPago() {
	//$('#modalAddCobro').modal({backdrop: 'static', keyboard: false})
	let idventa = document.getElementById("idventa").value;
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
	function(data,status)
	{
		data=JSON.parse(data);		
		console.log(data);	
		let totalPagar = data.total_venta - data.pagado;
		$("#clienteCobro").val(data.cliente).prop("disabled", true);
		$("#totalCobro").val("$" + data.total_venta).prop("disabled", true);			
		$("#porPagar").val("$" + totalPagar).prop("disabled", true);

		if(totalPagar == 0) {
			$("#btnGuardarCobro").hide();
		} else {
			$("#btnGuardarCobro").show();
		}	
	});	
}

function mostrarPagoEdit(idpago) {	

	let idventa = document.getElementById("idventa").value;	
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
	function(data,status)
	{
		data=JSON.parse(data);
		let totalPagar = data.total_venta - data.pagado;
		$("#clienteCobro").val(data.cliente).prop("disabled", true);
		$("#totalCobro").val("$" + data.total_venta).prop("disabled", true);			
		$("#porPagar").val("$" + totalPagar).prop("disabled", true);		
		$("#btnGuardarCobro").show();
	});

	$.post("../ajax/venta.php?op=mostrarPagoEdit&" + "idpago=" + idpago,
	function(data,status)
	{
		data=JSON.parse(data);		
		console.log(data);
		
		$("#importeCobro").val(data.importe).prop("disabled", false);
		$("#metodoPago").val(data.forma_pago).prop("disabled", false);
		$("#metodoPago").selectpicker('refresh');
		$("#banco").val(data.banco).prop("disabled", false);
		$("#banco").selectpicker('refresh');
		$("#referenciaCobro").val(data.referencia).prop("disabled", false);
		$("#idpago").val(data.idpago).prop("disabled", false);

	});

}

function cancelarFormPago() {
	limpiarPagoForm();
}

function guardarCobro() {	
	let idPago = document.getElementById("idpago").value;

	//limpiarPagoForm();
	
	let idVenta = document.getElementById("idventa").value;
	let idcliente = document.getElementById("idcliente").value;
	let importeCobro = document.getElementById("importeCobro").value;
	let metodoPago = document.getElementById("metodoPago").value;
	let banco = document.getElementById("banco").value;
	let referenciaCobro = document.getElementById("referenciaCobro").value;		
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);	
	var formData=new FormData($("#formularioAddCobro")[0]);	
	$('.loader').show();

	if(idPago == "") {
		$.ajax({
			url: "../ajax/venta.php?op=guardarCobro&idventa="+idVenta+"&idcliente="+idcliente
			+"&importeCobro="+importeCobro+"&metodoPago="+metodoPago+"&banco="+banco
			+"&referenciaCobro="+referenciaCobro + "&fechaCobro=" + today,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
	
			success: function(datos){				
				$('.loader').hide();
				$("#formularioAddCobro")[0].reset();
				$("#modalAddCobro").modal('hide');
				mostrarPagosEdit(idVenta);
				limpiarPagoForm();
			},
		});
	} else {
		$.post("../ajax/venta.php?op=mostrarPagoEdit&" + "idpago=" + idPago,
		function(data,status)
		{
			data=JSON.parse(data);		
			let importeViejo = data.importe;
			$.ajax({
				url: "../ajax/venta.php?op=editarCobro&idpago="+idPago+"&importeCobro="
				+importeCobro+"&metodoPago="+metodoPago+"&banco="+banco + "&importeviejo=" + importeViejo + "&idventa=" + idVenta
				+"&referenciaCobro="+referenciaCobro,
				type: "POST",
				data: formData,
				contentType: false,
				processData: false,
		
				success: function(datos){				
					swal({
						title: datos,
						text: 'Se actualizo correctamente el pago.',
						type: 'success',
						showConfirmButton: false,
						timer: 1500
					})			
					$("#formularioAddCobro")[0].reset();
					$("#modalAddCobro").modal('hide');
					mostrarPagosEdit(idVenta);
					limpiarPagoForm();
				},
			});
		});	
	}
		
}

function detallesVentaEditar(idventa) {

	$.post("../ajax/venta.php?op=mostrarDetalleVenta&id="+idventa,function(r){
		$('.loader').show();
		if(r.length < 0) {
			$("#detalles").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detalles").html(r);
				$('.loader').hide();
			}, 1000);
		}
	});
}

function editarProductoVenta(idarticulo) {	
	var idVenta = document.getElementById("idventa").value;
	$.post("../ajax/venta.php?op=mostrarProductoVenta&idarticulo="+idarticulo+"&idVenta="+idVenta,function(data){		
		data = JSON.parse(data);
		$("#idproducto").val(data.idarticulo).prop("disabled", true);
		$("#descripcionEdit").val(data.descripcion).prop("disabled", false);
		$("#cantidadEdit").val(data.cantidad).prop("disabled", false);
		$("#precioVentaEdit").val(data.precio_venta).prop("disabled", false);		
	});	
}

function editarGuardarProductoVenta() {	

	var idProducto = document.getElementById("idproducto").value;
	var idVenta = document.getElementById("idventa").value;	
	var cantidadNuevo = document.getElementById("cantidadEdit").value;
	var precioNuevo = document.getElementById("precioVentaEdit").value;
	var descripcionNueva = document.getElementById("descripcionEdit").value;

	var formData=new FormData($("#formularioProductoVenta")[0]);     

	$.post("../ajax/venta.php?op=mostrarProductoVenta&idarticulo="+document.getElementById("idproducto").value+"&idVenta="+idVenta,function(data){		
		data = JSON.parse(data);
		console.log(data);
		$.ajax({
			url: "../ajax/venta.php?op=editarGuardarProductoVenta&idarticulo="+idProducto+"&idventa="+idVenta
			+"&precioViejo="+data.precio_venta+"&stockViejo="+data.cantidad + "&cantidadNueva=" + cantidadNuevo
			+ "&precioNuevo=" + precioNuevo + "&descripcionNuevo=" + descripcionNueva,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
   
			success: function(datos){   
				console.log("DATOS: ", datos);
			   swal({
				   title: datos,
				   text: 'Se actualizo correctamente el articulo de la venta.',
				   type: 'success',
				   showConfirmButton: true,
				   //timer: 1500
			   })			
			   limpiarFormCliente();
			   $("#editProductventa").modal('hide');
			   detallesVentaEditar(idVenta)			
			},
	   });				
	});
}

function editar(idventa){		
	mostrarform(true);
	$("#addCliente").show();
	$("#btnAgregarArticulo").hide();	
	$("#btnAgregarArticulosEdit").show();	
	$("#btnAddPago").show();
	$("#editarDetalleServicio").show();
	//Mostrando info del cliente
	viewClient(idventa);
	$("#btnGuardar").show();
	detallesVentaEditar(idventa);
	mostrarPagosEdit(idventa);
}

function eliminarProductoVenta(idventa, idarticulo, stock, precio_venta) {

	swal({
		title: '¿Está seguro de eliminar el articulo?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar!'
	  }).then(function(result){	
		if(result.value){
			$.post("../ajax/venta.php?op=eliminarProductoVenta", {idventa : idventa, idarticulo : idarticulo, stock:stock, precio_venta:precio_venta}, function(e){
				swal({
					title:"Articulo eliminado!",
					text: 'Se elimino correctamente el articulo de la venta.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				detallesVentaEditar(idventa);
			});	
		}
	})
}

//funcion para desactivar
function anular(idventa){
	swal({
		title: '¿Está seguro de cancelar la venta?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, cancelar venta!'
	  }).then(function(result){
	
		if(result.value){
			$('.loader').show();
			$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
				swal({
					title:'Venta cancelada!',
					text: 'Se cancelo correctamente la venta.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
				$('.loader').hide();
			});	
		}
	})
}

// $("#placas").change(placasAuto);
// function placasAuto() {
// 	var placas = $("#placas").val();
// 	$("#marca").change(marcaAuto);
// 	function marcaAuto() {
// 		var marca = $("#marcaAuto").val();
// 		$("#modelo").change(modeloAuto);
// 		function modeloAuto() {
// 			var modelo = $("#modelo").val();
// 			$("#ano").change(anoAuto);
// 			function anoAuto() {
// 				var ano = $("#ano").val();
// 				$("#color").change(colorAuto);
// 				function colorAuto() {
// 					var color = $("#color").val();
// 					$("#kms").change(kmsAuto);
// 					function kmsAuto() {
// 						var kms = $("#kms").val();
// 						()=> {
// 							agregarDetalleAuto(placas, marca, modelo, ano, color, kms);
// 						}						
// 					}			
// 				}
// 			}
// 		}
// 	}
// }

// var cont2=0;
// var detalles2=0;

// function agregarDetalleAuto(placas, marcaAuto, modelo, ano, color, kms) {
// 	console.log(placas.value, marca.value, modelo.value, ano.value, color.value, kms.value);
// 	if (kms != "") {
// 		var fila='<tr class="filas" id="filase'+cont+'">'+
//         '<td><button style="width: 40px;" type="button" class="btn btn-danger" onclick="eliminarDetalleAuto('+cont+')">X</button></td>'+
// 		'<td><input type="hidden" name="placas" value="'+placas.value+'">'+placas.value+'</td>'+
// 		'<td><input type="hidden" name="marca" value="'+marcaAuto.value+'">'+marcaAuto.value+'</td>'+
// 		'<td><input type="hidden" name="modelo" value="'+modelo.value+'">'+modelo.value+'</td>'+
// 		'<td><input type="hidden" name="ano" value="'+ano.value+'">'+ano.value+'</td>'+
// 		'<td><input type="hidden" name="color" value="'+color.value+'">'+color.value+'</td>'+
// 		'<td><input type="hidden" name="kms" value="'+kms.value+'">'+kms.value+'</td>'+
// 		'</tr>';
// 		cont2++;
// 		detalles2++;
// 		$('#detallesAuto').append(fila);
// 		$("#btnAgregarAut").hide();
// 		// limpiarDetalle();

// 	}else{
// 		alert("error al ingresar el detalle, revisar las datos del articulo ");
// 	}
// }

// function eliminarDetalleAuto(indice){
// 	$("#filase"+indice).remove();
// 	detalles2=detalles2-1;
// 	$("#btnAgregarAut").show();
// }

//declaramos variables necesarias para trabajar con las compras y sus detalles
var impuesto=0;
var cont=0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto(){
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	if (tipo_comprobante=='Factura') {
		$("#impuesto").val(impuesto);
	}else{
		$("#impuesto").val(0);
	}
}

function agregarDetalle(idarticulo,articulo,fmsi, marca, descripcion,publico, stock, idsucursal){

	var cantidad=1;
	var descuento=0;
	let sub = document.getElementsByName("subtotal").value;

	if (idarticulo!="") {
		var fila='<tr style="font-size:12px" class="filas" id="fila'+cont+'">'+
        '<td><button style="width: 40px;" title="Eliminar" type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle('+cont+')">X</button></td>'+        
		'<td><input type="hidden" name="clave[]" value="'+articulo+'"> <input class="form-control" type="hidden" name="idsucursalArticulo[]" value="'+idsucursal+'"> <input class="form-control" type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+articulo+'</td>'+
		'<td><input type="hidden" name="fmsi[]" id="fmsi[]" value="'+fmsi+'">'+fmsi+'</td>'+
		'<td><input type="hidden" name="marca[]" id="marca[]" value="'+marca+'">'+marca+'</td>'+
		'<td><textarea class="form-control" id="descripcion[]" name="descripcion[]" rows="2" style="width: 280px;" value="'+descripcion+'">'+descripcion+'</textarea></td>'+
        '<td><input style="width: 55px;" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" max="'+stock+'" min="1"></td>'+		
        '<td><input style="width: 70px;" type="number" name="precio_venta[]" id="precio_venta[]" value="'+publico+'"></td>'+
        '<td><input style="width: 70px;" type="number" name="descuento[]" value="'+descuento+'"></td>'+
        '<td><span id="subtotal'+cont+'" name="subtotal" value="'+sub+'"></span></td>'+
        '<td><button type="button" title="Actualizar" onclick="modificarSubtotales()" class="btn btn-info btn-xs" style="width: 40px;"><i class="fa fa-refresh"></i></button></td>'+
		'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubtotales();

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function modificarSubtotales(){	
	var cant=document.getElementsByName("cantidad[]");
	var prev=document.getElementsByName("precio_venta[]");	
	var desc=document.getElementsByName("descuento[]");	
	var sub=document.getElementsByName("subtotal");
	for (var i = 0; i < cant.length; i++) {
		var inpV=cant[i];
		var inpP=prev[i];
		var inpS=sub[i];
		var des=desc[i];
		inpS.value=(inpV.value*inpP.value)-des.value;
		document.getElementsByName("subtotal")[i].innerHTML=pesosMexicanos.format(inpS.value);		

	}
	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total=0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html(pesosMexicanos.format(total));
	$("#total_venta").val(pesosMexicanos.format(total));
	evaluar();
}

function evaluar(){

	if (detalles>0)
	{
		$("#btnGuardar").show();
	}
	else
	{
		$("#btnGuardar").hide();
		cont=0;
	}
}


function eliminarDetalle(indice){
	$("#fila"+indice).remove();
	calcularTotales();
	detalles=detalles-1;
}

function agregarDetalleEdit(idarticulo,articulo,fmsi, marca, descripcion,publico, stock, idsucursalArticulo){	
	stock = 1;

	//console.log("ID ARTICULO: ", idarticulo, "\nCÓDIGO: ", articulo, "\nFMSI: ", fmsi, "\nMARCA: ", marca, "\nDESCRIPCIÓN: ", descripcion, "\nCOSTO: ", publico, "\nCANTIDAD: ", stock);	
	let idVenta = document.getElementById("idventa").value;
	let idcliente = document.getElementById("idcliente").value;
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);

	if (idarticulo!="") {

		$.ajax({
			url: "../ajax/venta.php?op=guardarProductoVenta&idVenta=" + idVenta + "&idArticulo=" + idarticulo + "&codigoArticulo="+articulo
			+ "&fmsiArticulo="+ fmsi + "&marcaArticulo="+marca + "&descripcionArticulo="+descripcion
			+ "&costoArticulo="+publico + "&cantidadArticulo="+stock + "&fecha=" + today + "&idcliente=" + idcliente +"&idsucursalArticulo=" + idsucursalArticulo,
			type: "POST",			
		   beforeSend: function() {
		   },
			contentType: false,
			processData: false,
	
			success: function(data){
			   swal({
				   position: 'top-end',
				   type: 'success',
				   title: data,
				   showConfirmButton: true,				   
			   });
			   detallesVentaEditar(idVenta);
				// mostrarform(true);
				// obtener_registros();
			//    limpiar();
			},
		});

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

init();