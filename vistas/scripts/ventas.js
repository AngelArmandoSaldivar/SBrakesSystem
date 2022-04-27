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
}


$('#searchSelect').on("keyup", function(){
	alert(this.value);
});

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

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today).prop("disabled", false);

	$("#btnAddArt").show();
	$("#btnGuardar").show();

	$("#idcliente").val("").prop("disabled", false);
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
	$("#btnAgregarArticulo").show();
	$("#addCliente").show();
	limpiar();
}

//cancelar form
function salirForm() {
	limpiar();
	mostrarform(false);
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

setInterval(() => {		
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda != "") {
		$('.loaderSearch').show();
		setTimeout(() => {
			obtener_registros(busqueda);
			$('.loaderSearch').hide();
		}, 500);		
	} else {
		$('.loaderSearch').show();		
		setTimeout(() => {
			obtener_registros();
			$('.loaderSearch').hide();
		}, 500);	
	}
}, 5000);

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
		obtener_registrosProductos();
	}
});


function obtener_registrosProductosEdit(productosEdit){

	var tiposPrecios = document.getElementById("caja_valor").value;
	
	$.ajax({
		url : '../ajax/venta.php?op=listarProductosEdit',
		type : 'POST',
		dataType : 'html',
		data : { productosEdit: productosEdit, types: tiposPrecios },
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
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente la venta',
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
				let idVenta = document.getElementById("idventa").value;
				if(idVenta == "") {
					let ultimoIdVenta = data.replace(/['"]+/g, '');
				window.open(
					`../reportes/exTicket.php?id=${ultimoIdVenta}`,
					'_blank'
				);
				}				
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
			console.log(data);
			mostrarform(true);
			$("#btnGuardar").show();
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
					$("#btnGuardar").show();
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
						$("#btnGuardar").show();
					}
					let calcular = x + x2;
					if(calcular === datas) {
						$("#btnGuardar").show();
						$("#importe3").prop("disabled", true);
					} else {						
						$("#btnGuardar").show();
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
							$("#btnGuardar").show();
						}		
						}
					});	
				});				
			});			
			
			//mostrarform(true);
			$('.loader').hide();	

			$("#detalle_cobro").show();
			$("#btnAgregarArticulo").hide();
			$("#divImpuesto").hide();
			$("#addCliente").hide();

			$("#idcliente").val(data.idcliente).prop("disabled", true);			
			$("#idcliente").selectpicker('refresh');

			$("#tipo_precio").val(data.tipo_precio).prop("disabled", true);			
			$("#tipo_precio").selectpicker('refresh');

			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#factura").val(data.factura).prop("disabled", true);
			$("#factura").selectpicker('refresh');	
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#idventa").val(data.idventa);
			$("#estado").val(data.estado).prop("disabled", true);

			$("#importe").val(data.importe).prop("disabled", false);
			$("#importe2").val(data.importe2).prop("disabled", false);
			$("#importe3").val(data.importe3).prop("disabled", false);

			$("#forma").val(data.forma_pago).prop("disabled", false);
			$("#forma").selectpicker('refresh');	
			$("#forma2").val(data.forma_pago2).prop("disabled", false);
			$("#forma2").selectpicker('refresh');
			$("#forma3").val(data.forma_pago3).prop("disabled", false);
			$("#forma3").selectpicker('refresh');	

			$("#banco").val(data.banco).prop("disabled", false);
			$("#banco").selectpicker('refresh');	
			$("#banco2").val(data.banco2).prop("disabled", false);
			$("#banco2").selectpicker('refresh');	
			$("#banco3").val(data.banco2).prop("disabled", false);
			$("#banco3").selectpicker('refresh');	

			$("#ref").val(data.referencia).prop("disabled", false);
			$("#ref2").val(data.referencia2).prop("disabled", false);
			$("#ref3").val(data.referencia3).prop("disabled", false);

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
			// $("#btnGuardar").hide();
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

			console.log(data.pagado, data.total_venta);

			let estadoVenta = data.pagado < parseInt(data.total_venta) ? "PENDIENTE" : "PAGADO";
			
			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			//$("#factura").val(data.factura).prop("disabled", true);
			//$("#factura").selectpicker('refresh');				
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);

			$("#estado").val(estadoVenta).prop("disabled", true);

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
			$("#telefono_local").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.telefono_local).prop("disabled", true);
			$("#tipoPrecio").val(data.tipo_precio).prop("disabled", true);
			$("#idventa").val(data.idventa);
			$("#tipo_precio").val(data.tipo_precio).prop("disabled", true);			
			$("#tipo_precio").selectpicker('refresh');
			
			//ocultar y mostrar los botones
			//$("#btnCancelar").hide();
			$("#btnRegresar").show();
		});
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

function mostrar(idventa){
	mostrarform(true);
	$('.loader').show();
	viewClient(idventa);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").hide();
	$("#btnAgregarArticulo").hide();
	$("#btnGuardar").hide();		
	detallesVenta(idventa);
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
		console.log(data);
		$("#idproducto").val(data.idarticulo).prop("disabled", true);
		$("#descripcion").val(data.descripcion).prop("disabled", false);
		$("#cantidad").val(data.cantidad).prop("disabled", false);
		$("#precio").val(data.precio_venta).prop("disabled", false);		
	});	
}

function editarGuardarProductoVenta() {	

	var idProducto = document.getElementById("idproducto").value;
	var idVenta = document.getElementById("idventa").value;	

	var formData=new FormData($("#formularioProductoVenta")[0]);     

	$.post("../ajax/venta.php?op=mostrarProductoVenta&idarticulo="+document.getElementById("idproducto").value+"&idVenta="+idVenta,function(data){		
		data = JSON.parse(data);
		console.log(data);
		$.ajax({
			url: "../ajax/venta.php?op=editarGuardarProductoVenta&idarticulo="+idProducto+"&idventa="+idVenta+"&precioViejo="+data.precio_venta+"&stockViejo="+data.cantidad,
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
			   $("#formularioProductoVenta")[0].reset();
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
	//Mostrando info del cliente
	viewClient(idventa);
	$("#btnGuardar").show();
	detallesVentaEditar(idventa);
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

function agregarDetalleEdit(idarticulo,articulo,fmsi, marca, descripcion,publico, stock){	
	stock = 1;

	//console.log("ID ARTICULO: ", idarticulo, "\nCÓDIGO: ", articulo, "\nFMSI: ", fmsi, "\nMARCA: ", marca, "\nDESCRIPCIÓN: ", descripcion, "\nCOSTO: ", publico, "\nCANTIDAD: ", stock);	
	let idVenta = document.getElementById("idventa").value;
	console.log(idVenta);

	if (idarticulo!="") {

		$.ajax({
			url: "../ajax/venta.php?op=guardarProductoVenta&idVenta=" + idVenta + "&idArticulo=" + idarticulo + "&codigoArticulo="+articulo
			+ "&fmsiArticulo="+ fmsi + "&marcaArticulo="+marca + "&descripcionArticulo="+descripcion
			+ "&costoArticulo="+publico + "&cantidadArticulo="+stock,
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