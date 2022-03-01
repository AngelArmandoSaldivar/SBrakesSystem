var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   obtener_registros();
   obtener_registrosProductos();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

	selectCliente();
   
}

function mostrarInfoClient(idcliente) {
	$('.loader').show();
	$.post("../ajax/venta.php?op=mostrarInfoClient",{idcliente : idcliente},
		function(data,status)
		{
			$('.loader').hide();
			data=JSON.parse(data);				
			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
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
}

function selectCliente() {
	//cargamos los items al select cliente
	$.post("../ajax/venta.php?op=selectCliente", function(r){
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

	$("#tipo_precio").change(modTipoPrecio);
	function modTipoPrecio() {
		var tipo_precio = $("#tipo_precio option:selected").val();			
		document.getElementById("caja_valor").value=tipo_precio;	
	}

	$("#idcliente").change(modIdCliente);
	function modIdCliente() {
		var idcliente = $("#idcliente option:selected").val();
		mostrarInfoClient(idcliente);
	}
}

//funcion limpiar
function limpiar(){
	$("#busquedaProduct").val("");
	$("#idcliente").val("");
	$("#cliente").val("");
	$("#impuesto").val("");

	$("#total_venta").val("");
	$(".filas").remove();
	$("#total").html("0");

	//obtenemos la fecha actual
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Boleta");
	$("#tipo_comprobante").selectpicker('refresh');

}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
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
	}else{
		$("#listadoregistros").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
	$("#detalle_cobro").show();
	location.replace("venta.php");
}

function obtener_registros(ventas){	
	$.ajax({
		url : '../ajax/venta.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { ventas: ventas },
	}
	)
	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
	})
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

function obtener_registrosProductos(productos){		

	var tiposPrecios = document.getElementById("caja_valor").value;
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductos',
		type : 'POST',
		dataType : 'html',
		data : { productos: productos, types: tiposPrecios },
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
		limpiar();
		obtener_registrosProductos();
	}
});

function obtener_registrosProductos_almacen(productos){		

	var tiposPrecios = document.getElementById("caja_valor").value;
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductosSucursal',
		type : 'POST',
		dataType : 'html',
		data : { productos: productos, types: tiposPrecios },
	})
	.done(function(resultado){
		$("#tabla_resultadoProducto_almacen").html(resultado);
	})
}

$(document).on('keyup', '#busquedaProductAlmacen', function(){	
	var valorBusqueda=$(this).val();
	
	if (valorBusqueda!="")
	{	
		obtener_registrosProductos_almacen(valorBusqueda);
	}
	else
	{
		limpiar();
		obtener_registrosProductos_almacen();
	}
});

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);	 
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
     		bootbox.alert(datos);			 
     		mostrarform(false);			 
     		obtener_registros();
			limpiar();
     	},
		 complete: function() {
			$('.loader').hide();
			$.post("../ajax/venta.php?op=ultimaVenta",
			function(data,status)
			{
				var ultimoIdVenta = data.replace(/['"]+/g, '');
				window.open(
					`../reportes/exTicket.php?id=${ultimoIdVenta}`,
					'_blank'
				);
			});
		},
		dataType: 'html'
     });
}


function cobrarVenta(idventa){

	$('.loader').show();
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
		function(data,status)
		{				
			data=JSON.parse(data);

			$('#importe').focusout(function() {
				var datas =  parseInt(data.total_venta);
				x = parseInt($(this).val());
				if(x === datas) {
					$("#btnGuardar").show();
					$("#importe2").prop("disabled", true);
					$("#importe3").prop("disabled", true);
					$("#importe2").val("").prop("disabled", true);
					$("#importe3").val("").prop("disabled", true);
				} else if(x < datas) {
					$("#importe2").prop("disabled", false);
					$("#importe3").prop("disabled", false);
					$("#btnGuardar").hide();
				}
				$('#importe2').focusout(function() {
					x2 = parseInt($(this).val());
					x2 = parseInt($(this).val());
					if(x2 === datas) {
						$("#importe").val("").prop("disabled", true);
						$("#importe").prop("disabled", true);
						$("#importe3").val("").prop("disabled", true);
						$("#importe3").prop("disabled", true);
						$("#btnGuardar").show();
					} else if(x2 < datas) {
						$("#importe").prop("disabled", false);
						$("#importe3").prop("disabled", false);
						$("#btnGuardar").hide();
					}
					let calcular = x + x2;
					if(calcular === datas) {
						$("#btnGuardar").show();
						$("#importe3").prop("disabled", true);
					} else {						
						$("#btnGuardar").hide();
						$("#importe3").prop("disabled", false);
					}
					$('#importe3').focusout(function() {
						x3 = parseInt($(this).val());	
						if(x3 === datas) {
							$("#importe").val("").prop("disabled", true);
							$("#importe").prop("disabled", true);
							$("#importe2").val("").prop("disabled", true);
							$("#importe2").prop("disabled", true);
							$("#btnGuardar").show();
						} else if(x3 < datas) {
							$("#importe").prop("disabled", false);
							$("#importe2").prop("disabled", false);
							$("#btnGuardar").hide();
						}
						if(x != '' && x2 != '' && x3 != '') {
						let calcular = x + x2 + x3;
						if(calcular === datas) {
							$("#btnGuardar").show();
						} else {						
							$("#btnGuardar").hide();
						}		
						}
					});	
				});				
			});			
			
			mostrarform(true);
			$('.loader').hide();	

			$("#detalle_cobro").show();
			$("#btnAgregarArticulo").hide();
			$("#divImpuesto").hide();
			$("#addCliente").hide();

			$("#idcliente").val(data.idcliente).prop("disabled", true);			
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#factura").val(data.factura).prop("disabled", true);
			$("#factura").selectpicker('refresh');	
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#idventa").val(data.idventa);
			$("#estado").val(data.estado).prop("disabled", true);

			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);			
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
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
			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
	$.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){		
		$("#detalles").html(r);
	});	
	
}

function guardarCliente() {
	var formData=new FormData($("#formularioCliente")[0]);

     $.ajax({
     	url: "../ajax/persona.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
			selectCliente();
			$("#formulario")[0].reset();
			$("#formularioCliente")[0].reset();
			$("#agregarCliente").modal('hide');
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
     		bootbox.alert(datos);
			selectProvider();
			$("#formulario")[0].reset();
			$("#formularioProduct")[0].reset();	
			$("#agregarProducto").modal('hide');
     	}
     });
}

function mostrar(idventa){
	$('.loader').show();
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
			$('.loader').hide();

			$("#detalle_cobro").show();
			$("#btnAgregarArticulo").hide();
			$("#divImpuesto").hide();
			$("#addCliente").hide();
			
			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#factura").val(data.factura).prop("disabled", true);
			$("#factura").selectpicker('refresh');				
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#estado").val(data.estado).prop("disabled", true);

			$("#importe").val(data.importe).prop("disabled", true);
			$("#forma").val(data.forma_pago).prop("disabled", true);
			$("#forma").selectpicker('refresh');	
			$("#banco").val(data.banco).prop("disabled", true);
			$("#banco").selectpicker('refresh');
			$("#ref").val(data.referencia).prop("disabled", true);

			$("#importe2").val(data.importe2).prop("disabled", true);
			$("#forma2").val(data.forma_pago2).prop("disabled", true);
			$("#forma2").selectpicker('refresh');	
			$("#banco2").val(data.banco2).prop("disabled", true);
			$("#banco2").selectpicker('refresh');
			$("#ref2").val(data.referencia2).prop("disabled", true);

			$("#importe3").val(data.importe3).prop("disabled", true);
			$("#forma3").val(data.forma_pago3).prop("disabled", true);
			$("#forma3").selectpicker('refresh');	
			$("#banco3").val(data.banco3).prop("disabled", true);
			$("#banco3").selectpicker('refresh');
			$("#ref3").val(data.referencia3).prop("disabled", true);


			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);			
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.credito).prop("disabled", true);
			$("#tipoPrecio").val(data.tipo_precio).prop("disabled", true);
			// if(data.tipo_precio == "publico") {
			// 	$("#tipoPrecio").val("Publico / Mostrador").prop("disabled", true);
			// } else if(data.tipo_precio == "taller") {
			// 	$("#tipoPrecio").val("Taller").prop("disabled", true);
			// } else if(data.tipo_precio == "credito_taller") {
			// 	$("#tipoPrecio").val("Credito Taller").prop("disabled", true);
			// } else if(data.tipo_precio == "mayoreo") {
			// 	$("#tipoPrecio").val("Mayoreo").prop("disabled", true);
			// }

			$("#idventa").val(data.idventa);
			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
		});
	$.post("../ajax/venta.php?op=listarDetalle&id="+idventa,function(r){
		$("#detalles").html(r);
	});	

}

//funcion para desactivar
function anular(idventa){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$('.loader').show();
			$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
				bootbox.alert(e);
				// tabla.ajax.reload();
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
var detalles=0;

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

function agregarDetalle(idarticulo,articulo,fmsi, marca, descripcion,publico, stock){

	var cantidad=1;
	var descuento=0;
	var sub = document.getElementsByName("subtotal");

	if (idarticulo!="") {
		var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button style="width: 40px;" type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td>'+
		'<td><input type="hidden" name="clave[]" value="'+articulo+'">'+articulo+'</td>'+
		'<td><input type="hidden" name="fmsi[]" id="fmsi[]" value="'+fmsi+'">'+fmsi+'</td>'+
		'<td><input type="hidden" name="marca[]" id="marca[]" value="'+marca+'">'+marca+'</td>'+
		'<td><textarea class="form-control" id="descripcion[]" name="descripcion[]"rows="3" style="width: 150px;" value="'+descripcion+'">'+descripcion+'</textarea></td>'+
        '<td><input style="width: 55px;" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'" max="'+stock+'" min="1"></td>'+		
        '<td><input style="width: 70px;" type="number" name="precio_venta[]" id="precio_venta[]" value="'+publico+'"></td>'+
        '<td><input style="width: 70px;" type="number" name="descuento[]" value="'+descuento+'"></td>'+
        '<td><span id="subtotal'+cont+'" name="subtotal" value="'+sub+'"></span></td>'+
        '<td><button type="button" onclick="modificarSubtotales()" class="btn btn-info"><i class="fa fa-refresh"></i></button></td>'+
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
		document.getElementsByName("subtotal")[i].innerHTML=inpS.value;		

	}
	calcularTotales();
}

function calcularTotales(){
	var sub = document.getElementsByName("subtotal");
	var total=0.0;

	for (var i = 0; i < sub.length; i++) {
		total += document.getElementsByName("subtotal")[i].value;
	}
	$("#total").html("$." + total);
	$("#total_venta").val(total);
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