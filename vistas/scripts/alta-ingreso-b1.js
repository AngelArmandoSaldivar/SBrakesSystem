var tabla;
var pesosMexicanos = Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'});
var now = new Date();
var day =("0"+now.getDate()).slice(-2);
var month=("0"+(now.getMonth()+1)).slice(-2);
const TODAY = now.getFullYear()+"-"+(month)+"-"+(day);
//funcion que se ejecuta al inicio
function init(){    

   //mostrarform(false);
   mostrarform(true);
   obtener_registrosProductos();
   obtener_registrosProductosEdit();
   let params = new URLSearchParams(location.search);
   var idIngreso = params.get('idIngreso');   

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

   //cargamos los items al select proveedor
   selectProvider();

   	//Revisamos si la paginación esta en 1 para no mostrar el boton de Anterior
	let cachaPaginaNumber = Number($("#pagina").val());
	if(cachaPaginaNumber <= 1) {
		$("#anterior").hide();
	}
	evaluarCaja();	
}

function cancelarRecepcion() {
		
	let params = new URLSearchParams(location.search);
	var idIngreso = params.get('idIngreso');			

	$.post("../ajax/ingreso.php?op=anular", {idingreso : idIngreso}, function(e){
		swal({
			title:'Recepción eliminada!',
			text: 'Se elimino correctamente la recepción.',
			type: 'success',
			showConfirmButton: false,
			timer: 500
		});
		window.location = 'ingreso.php';
	});
}

function actualizarProveedor(idProveedor) {

	//var idIngreso = $("#idingreso").val();
	let params = new URLSearchParams(location.search);
	var idIngreso = params.get('idIngreso');
	$('.loader').show();
	$.ajax({
        url : '../ajax/ingreso.php?op=actualizaProveedorIngreso',
        type : 'POST',
        data: {"idProveedor" : idProveedor, "idIngreso" : idIngreso},
        error: () => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,					
                showConfirmButton: false,
                type: 'warning',					
            });
			$('.loader').hide();
			let params = new URLSearchParams(location.search);
			var idIngreso = params.get('idIngreso');
			window.location = './alta-ingreso.php' + '?idIngreso=' + idIngreso;
        },
        success: (data) => {
			$('.loader').hide();
            console.log(data)
		}
	})
	
}

function actualizaSerie(serie) {
	let params = new URLSearchParams(location.search);
	var idIngreso = params.get('idIngreso');
	$('.loader').show();
	$.ajax({
        url : '../ajax/ingreso.php?op=actualizaSerieIngreso',
        type : 'POST',
        data: {"serie" : serie, "idIngreso" : idIngreso},
        error: () => {
			swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,					
                showConfirmButton: false,
                type: 'warning',					
            });
			$('.loader').hide();
			let params = new URLSearchParams(location.search);
			var idIngreso = params.get('idIngreso');
			window.location = './alta-ingreso.php' + '?idIngreso=' + idIngreso;        
        },
        success: (data) => {
			$('.loader').hide();
            console.log(data)
		}
	})
}

function evaluarCaja() {
	$.ajax({
        url : '../ajax/caja.php?op=mostrarCaja',
        type : 'POST',
        data: {"fecha_actual" : TODAY},
        error: () => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,					
                showConfirmButton: false,
                type: 'warning',					
            })
        },
        success: (data) => {
            data=JSON.parse(data); 						

			if(data == null) {				
				swal({
					title: 'Ups!',
					text: "La caja aun no ha sido abierta.",
					imageUrl: '../files/images/unlock.gif',
					imageWidth: 300,
					imageHeight: 150,
					showConfirmButton: true,
					imageAlt: 'Custom image',
				});
				setTimeout(() => {
					window.open(
						`caja.php`,
						"_self"
					);
				}, 1500);
			} else if(data.estado == "CERRADO") {
				swal({
					title: 'Ups!',
					text: "La caja aun no ha sido abierta.",
					imageUrl: '../files/images/unlock.gif',
					imageWidth: 300,
					imageHeight: 150,
					showConfirmButton: true,
					imageAlt: 'Custom image',
				});
				setTimeout(() => {
					window.open(
						`caja.php`,
						"_self"
					);
				}, 1500);
			}
						
        }
    })
}

//Mostrar proveedores
function selectProvider() {
	
	var lista = $.post("../ajax/ingreso.php?op=selectProveedor", function(r){
		$("#idproveedor").html(r);
		$('#idproveedor').selectpicker('refresh');		
		if (r.length > 0) {
			let params = new URLSearchParams(location.search);
			var idIngreso = params.get('idIngreso');
			mostrar(idIngreso);
		}
	});	
}

function activarPopover() {
	$(function () {
		$('[data-toggle="popover"]').popover()		
	})
}

//funcion limpiar
function limpiar(){	
		
	$("#serie_comprobante").val("");	
	$("#impuesto").val("");
	$("#total_compra").val("");
	$(".filas").remove();
	$("#total").html("0");

	//obtenemos la fecha actual
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Factura");
	$("#tipo_comprobante").selectpicker('refresh');
	$("#idproveedor").val("");
	$("#idproveedor").selectpicker('refresh');
	$("#idingreso").val("");

}

//funcion mostrar formulario
function mostrarform(flag){
	if(flag){
		var now = new Date();
		var day =("0"+now.getDate()).slice(-2);
		var month=("0"+(now.getMonth()+1)).slice(-2);
		var today=now.getFullYear()+"-"+(month)+"-"+(day);
		$("#fecha_hora").val(today);
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		detalles=0;
		$("#btnAgregarArt").show();
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//Salir form
function salirForm() {
	window.location = './ingreso.php';
}

//Listar productos
function obtener_registrosProductos(productos){
		$.ajax({
			url : '../ajax/ingreso.php?op=listarArticulos',
			type : 'POST',
			dataType : 'html',
			data : { productos: productos},
		})
		.done(function(resultado){
			$("#tabla_resultadoProducto").html(resultado);
		})	
}

$(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault();
        // o return false;
		console.log($("#busquedaProduct").val());
		var valorBusqueda=$("#busquedaProduct").val();
	
		if (valorBusqueda!="")
		{
			obtener_registrosProductos(valorBusqueda);

		} else {
			obtener_registrosProductos();
		}
    });
});

setInterval(() => {
	let producto = document.getElementById("busquedaProduct").value;
	obtener_registrosProductos(producto);
}, 5000);

function cerrarModal() {
	$("#myModal").modal('hide');
}

function obtener_registrosProductosEdit(productosEdit){		
	$.ajax({
		url : '../ajax/ingreso.php?op=listarProductosEdit',
		type : 'POST',
		dataType : 'html',
		data : { productosEdit: productosEdit},
	})
	.done(function(resultado){
		$("#tabla_resultadoProductoEdit").html(resultado);
	})
}

$(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault();
        // o return false;
		console.log($("#busquedaProductEdit").val());
		var valorBusqueda=$("#busquedaProductEdit").val();	
	
		if (valorBusqueda!="")
		{		
			obtener_registrosProductosEdit(valorBusqueda);

		} else {				
			obtener_registrosProductosEdit();
		}
    });
});


function viewClient(idingreso) {
	$('.loader').show();
	$.post("../ajax/ingreso.php?op=mostrar",{idingreso : idingreso},
	function(data,status){
		data=JSON.parse(data);
		$('.loader').hide();
		console.log("DATA: " + data.idproveedor);		
		$("#idproveedor").val(data.idproveedor);
		$("#idproveedor").selectpicker('refresh');
		$("#tipo_comprobante").val(data.tipo_comprobante);
		$("#tipo_comprobante").selectpicker('refresh');
		$("#serie_comprobante").val(data.serie_comprobante);
		$("#fecha_hora").val(data.fecha);
		$("#impuesto").val(data.impuesto);
		$("#idingreso").val(data.idingreso);
		$("#codigo").val(data.codigo);		
	});
}

function detalleMostrar(idingreso) {
	$.post("../ajax/ingreso.php?op=listarDetalle&id="+idingreso,function(r){
		//$('.loader').show();
		console.log("LONGITUD: " + r.length);
		if(r.length < 0) {
			$("#detalles").html(r);
			//$('.loader').hide();
		} else {			
			$("#detalles").html(r);
			//$('.loader').hide();
		}
	});
}

function mostrar(idingreso){
	viewClient(idingreso);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").show();
	$("#btnAgregarArticulo").hide();
	$("#btnGuardar").hide();
	$("#btnAgregarProveedor").show();
	$("#btnCancelar").show();
	$("#btnCancelar").show();
	detallesRecepcionEditar(idingreso);
}

function editarProductoRecepcion(idarticulo, idRecepcion) {
	console.log("ID RECEPCION: " + idRecepcion);	
	let params = new URLSearchParams(location.search);
	var idIngreso = params.get('idIngreso');

	$.post("../ajax/ingreso.php?op=mostrarProductoIngreso&idarticulo="+idarticulo+"&idIngreso="+idIngreso,function(data){		
		data = JSON.parse(data);
		console.log(data);
		$("#idProductoRec").val(data.idarticulo).prop("disabled", true);		
		$("#descripcionProductoRec").val(data.descripcion).prop("disabled", false);
		$("#cantidadProductoRec").val(data.cantidad).prop("disabled", false);
		$("#precioProductoRec").val(data.precio_compra).prop("disabled", false);			
	});	
}

function detallesRecepcionEditar(idingreso) {	

	$.post("../ajax/ingreso.php?op=mostrarDetalleRecepcion&id="+idingreso,function(r){		
		$('.loader').show();
		if(r.length < 0) {
			$("#detalles").html(r);
			$('.loader').hide();
		} else {
			setTimeout(() => {
				$("#detalles").html(r);
				$('.loader').hide();
			}, 500);
		}
	});
}

function editarGuardarProductoRecepcion() {

	var idProducto = document.getElementById("idProductoRec").value;	
	let params = new URLSearchParams(location.search);
	var idIngreso = params.get('idIngreso');			

	var descripcion = document.getElementById("descripcionProductoRec").value;
	var cantidad = document.getElementById("cantidadProductoRec").value;
	var precio = document.getElementById("precioProductoRec").value;
	
	var formData=new FormData($("#formularioProductoServicio")[0]);

	$.post("../ajax/ingreso.php?op=mostrarProductoIngreso&idarticulo="+document.getElementById("idProductoRec").value+"&idIngreso="+idIngreso,function(data){		
		data = JSON.parse(data);		

		$.ajax({
			url: "../ajax/ingreso.php?op=editarGuardarProductoIngreso&idarticulo="+idProducto+"&idIngreso="
			+ idIngreso+"&precioViejo="+data.precio_compra+"&stockViejo="+data.cantidad 
			+ "&descripcion="+descripcion  + "&cantidad=" + cantidad + "&precio=" + precio,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
   
			success: function(datos){   
				alert("Se actualizo correctamente el articulo del ingreso.");				
				/*swal({
					title: datos,
					text: 'Se actualizo correctamente el articulo del ingreso.',
					type: 'success',
					showConfirmButton: true,
					//timer: 1500
				})*/			
				$("#formularioProductoRecepcion")[0].reset();
				$("#editProductRecepcion").modal('hide');
				detallesRecepcionEditar(idIngreso);
			},			
	   });				
	});
}

function eliminarProductoIngreso(idingreso, idarticulo, stock, precio_compra) {

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
			$.post("../ajax/ingreso.php?op=eliminarProductoIngreso", {idingreso : idingreso, idarticulo : idarticulo, stock:stock, precio_compra:precio_compra}, function(e){
				swal({
					title:"Articulo eliminado!",
					text: 'Se elimino correctamente el articulo de la recepción.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				detallesRecepcionEditar(idingreso);
			});	
		}
	})
}

function guardaryeditarProveedor(e){

     var formData=new FormData($("#formularioProve")[0]);

     $.ajax({
     	url: "../ajax/persona.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente el proveedor',
				showConfirmButton: false,
				timer: 1500
			});
			selectProvider();
			$("#formulario")[0].reset();
			$("#formularioProve")[0].reset();
			$("#agregarProveedor").modal('hide');
     	}
     });	
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
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente el producto',
				showConfirmButton: false,
				timer: 1500
			});
			selectProvider();
			$("#formulario")[0].reset();
			$("#formularioProduct")[0].reset();	
			$("#agregarProducto").modal('hide');			
     	}
     });
}
//declaramos variables necesarias para trabajar con las compras y sus detalles
var impuesto=0;
var cont=0;
var detalles=0;

$("#btnGuardar").hide();
$("#tipo_comprobante").change(marcarImpuesto);

function marcarImpuesto(){
	var tipo_comprobante=$("#tipo_comprobante option:selected").text();
	if (tipo_comprobante=='Factura'	) {
		$("#impuesto").val(impuesto);
	}
}

function agregarDetalle(idarticulo,articulo,fmsi, descripcion,costo, idarticuloSucursal){
	var cantidad=1;	
	var descuento = 0;	

	if (idarticulo!="") {
		var fila='<tr style="font-size:11px" class="filas" id="fila'+cont+'">'+
        '<td><button style="width: 40px;" type="button" class="btn btn-danger btn-xs" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td>'+
		'<td><input type="hidden" name="idarticuloSucursal[]" id="idarticuloSucursal[]" value="'+idarticuloSucursal+'"><input type="hidden" name="clave[]" value="'+articulo+'">'+articulo+'</td>'+
		'<td><input type="hidden" name="fmsi[]" id="fmsi[]" value="'+fmsi+'">'+fmsi+'</td>'+
		'<td><textarea class="form-control" id="descripcion[]" name="descripcion[]" rows="2" style="width: 280px;" value="'+descripcion+'">'+descripcion+'</textarea></td>'+
        '<td><input style="width: 55px;" type="number" onblur="modificarSubtotales()" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input style="width: 70px;" type="number" onblur="modificarSubtotales()" name="precio_compra[]" id="precio_compra[]" step="any" value="'+costo+'"></td>'+
        '<td><input style="width: 70px;" type="number" onblur="modificarSubtotales()" name="descuento[]" id="descuento[]" value="'+descuento+'"></td>'+
        '<td><span id="subtotal'+cont+'" name="subtotal">'+costo*cantidad+'</span></td>'+        
		'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		modificarSubtotales();

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function agregarDetalleEdit(idarticulo,articulo,fmsi, marca, descripcion,publico, stock, idarticuloSucursal){	
	stock = 1;
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);

	//console.log("ID ARTICULO: ", idarticulo, "\nCÓDIGO: ", articulo, "\nFMSI: ", fmsi, "\nMARCA: ", marca, "\nDESCRIPCIÓN: ", descripcion, "\nCOSTO: ", publico, "\nCANTIDAD: ", stock);	
	let params = new URLSearchParams(location.search);
	var idingreso = params.get('idIngreso');			
	var folio = document.getElementById("serie_comprobante").value;
	var idproveedor = document.getElementById("idproveedor").value;
	
	if (idarticulo!="") {

		$.ajax({
			url: "../ajax/ingreso.php?op=guardarProductoIngreso&idingreso=" + idingreso + "&idArticulo=" + idarticulo + "&codigoArticulo="+articulo
			+ "&fmsiArticulo="+ fmsi + "&marcaArticulo="+marca + "&descripcionArticulo="+descripcion
			+ "&costoArticulo="+publico + "&cantidadArticulo="+stock+ "&idproveedor="+idproveedor + "&dateTime=" + today + "&serieComprobante=" + folio
			+ "&idarticuloSucursal=" + idarticuloSucursal,
			type: "POST",			
		   beforeSend: function() {
		   },
			contentType: false,
			processData: false,
			error: () => {
				swal({
					title: 'Error!',
					html: 'Ha surgido un error',
					timer: 1000,					
					showConfirmButton: false,
					type: 'warning',					
				});
				let params = new URLSearchParams(location.search);
				var idIngreso = params.get('idIngreso');
				window.location = './alta-ingreso.php' + '?idIngreso=' + idIngreso;

			},
			success: function(data){
			   swal({
				   position: 'top-end',
				   type: 'success',
				   title: data,
				   showConfirmButton: true,				   
			   });			   
			   console.log("DETALLE INGRESO ID: " + idingreso);
			   detallesRecepcionEditar(idingreso);
			}
		});

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function modificarSubtotales(){
	console.log("LLEGASTE");
	var cant=document.getElementsByName("cantidad[]");
	var prec=document.getElementsByName("precio_compra[]");
	var sub=document.getElementsByName("subtotal");
	var desc=document.getElementsByName("descuento[]");

	for (var i = 0; i < cant.length; i++) {
		var inpC=cant[i];
		var inpP=prec[i];
		var inpS=sub[i];
		var des=desc[i];
		var descuento = des.value / 100;
		var cantPrec = inpC.value * inpP.value;
		var nuevoCosto = (cantPrec - (cantPrec * descuento));

		inpS.value=nuevoCosto;
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
	$("#total_compra").val(pesosMexicanos.format(total));
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

init();