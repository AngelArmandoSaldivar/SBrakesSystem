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

//cargamos los items al select cliente
function selectCliente (){
	$.post("../ajax/servicio.php?op=selectCliente", function(r){
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

	$("#idcliente").change(modIdCliente);
	function modIdCliente() {
		var idcliente = $("#idcliente option:selected").val();
		mostrarInfoClient(idcliente);
		document.getElementById("idclient").value=idcliente;

			$.post("../ajax/servicio.php?op=selectAuto&id="+idcliente,function(r){
				$("#idauto").html(r);
				$('#idauto').selectpicker('refresh');
			});				
			$("#idauto").change(modIdAuto);
			function modIdAuto() {	
				$('.loaderInfoAuto').show();			
				var idauto = $("#idauto option:selected").val();
				$.post("../ajax/servicio.php?op=mostrarInfoAuto",{idauto : idauto},
					function(data,status){
						$('.loaderInfoAuto').hide();
						data=JSON.parse(data);
						$("#placas").val(data.placas).prop("disabled", false);
						$("#marca").val(data.marca).prop("disabled", false);						
						$("#modelo").val(data.modelo).prop("disabled", false);
						$("#ano").val(data.ano).prop("disabled", false);
						$("#color").val(data.color).prop("disabled", false);
						$("#kms").val(data.kms).prop("disabled", false);
				});	
			}

	}

	$("#tipo_precio").change(modTipoPrecio);
	function modTipoPrecio() {
		var tipo_precio = $("#tipo_precio option:selected").val();			
		document.getElementById("caja_valor").value=tipo_precio;	
	}
}


//funcion limpiar
function limpiar() {

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	$("#fecha_hora").val(today);

	$("#btnAgregarArt").show();
	$("#btnGuardar").show();

	$("#idcliente").val("").prop("disabled", false);
	$("#idcliente").selectpicker('refresh');
	$("#tipo_comprobante").val("").prop("disabled", false);
	$("#tipo_comprobante").selectpicker('refresh');		
	$("#impuesto").val("").prop("disabled", false);
	$("#marca").val("").prop("disabled", false);
	$("#placas").val("").prop("disabled", false);
	$("#modelo").val("").prop("disabled", false);
	$("#color").val("").prop("disabled", false);
	$("#ano").val("").prop("disabled", false);
	$("#kms").val("").prop("disabled", false);
	$("#estado").val("").prop("disabled", false);
	$("#rfc").val("").prop("disabled", false);
	$("#direccion").val("").prop("disabled", false);			
	$("#email").val("").prop("disabled", false);
	$("#telefono").val("").prop("disabled", false);
	$("#credito").val("").prop("disabled", false);
	$("#tipoPrecio").val("").prop("disabled", false);

	$(".filas").remove();
	$("#total").html("0");

	//marcamos el primer tipo_documento
	$("#tipo_comprobante").val("Factura");
	$("#tipo_comprobante").selectpicker('refresh');		
	
}

//funcion mostrar formulario
function mostrarform(flag){	
	if(flag){	
		if(document.getElementById("idservicio")=="") {
			$("#btnAgregarArt").show();
			$("#btnGuardar").show();
		}
		
		//Ocultamos detalle_cobro
		$("#detalle_cobro").hide();	
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregarservicio").hide();
		obtener_registros();

		//$("#btnGuardar").show();
		$("#btnCancelar").show();
		//$("#btnAgregarArt").show();
		$("#btnAgregarArticulosEdit").hide();
		detalles=0;		
	}else{
		//$("#btnAgregarArt").show();
		//$("#btnGuardar").hide();
		//$("#btnCancelar").show();		
		$("#listadoregistros").show();
		$("#formularioregistros").hide();			
		$("#btnagregarservicio").show();
	}
}

function crearServicio() {
	mostrarform(true);
	$("#btnGuardar").show();
	$("#btnAddArt").show();
	$("#addCliente").show();
}

//Salir form
function salirForm() {
	limpiar();
	mostrarform(false);	
}

function obtener_registros(servicios){
	$.ajax({
		url : '../ajax/servicio.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { servicios: servicios },
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
	console.log(document.getElementById("busqueda").value);
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
		url : '../ajax/servicio.php?op=listarProductos',
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


//Productos de otros almaneces
function obtener_registrosProductos_almacen(productos){		

	var tiposPrecios = document.getElementById("caja_valor").value;
	
	$.ajax({
		url : '../ajax/servicio.php?op=listarProductosSucursal',
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

function obtener_registrosProductosEdit(productosEdit){

	var tiposPrecios = document.getElementById("caja_valor").value;
	
	$.ajax({
		url : '../ajax/servicio.php?op=listarProductosEdit',
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
		limpiar();
		obtener_registrosProductosEdit();
	}
});

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);	 
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/servicio.php?op=guardaryeditar",
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
				title: 'Se guardo correctamente el servicio',
				showConfirmButton: false,
				timer: 1500
			});	
     		mostrarform(false);
     		obtener_registros();
			limpiar();
     	},
		 complete: function() {
			$('.loader').hide();
			$.post("../ajax/servicio.php?op=ultimoServicio",
			function(data,status)
			{
				let idServicio = document.getElementById("idservicio").value;
				if(idServicio == "") {
					let ultimoIdServicio = data.replace(/['"]+/g, '');
				window.open(
					`../reportes/exFacturaServicio.php?id=${ultimoIdServicio}`,
					'_blank'
				);
				}
			});
			
		},dataType: 'html'
     });

     limpiar();
}

function eliminarProductoServicio(idservicio, idarticulo, stock, precio_servicio) {

	console.log("ID SERVICIO: ", idservicio);

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
			$.post("../ajax/servicio.php?op=eliminarProductoServicio", {idservicio : idservicio, idarticulo : idarticulo, stock:stock, precio_servicio:precio_servicio}, function(e){
				swal({
					title:"Articulo eliminado!",
					text: 'Se elimino correctamente el articulo del servicio.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				detallesServicioEditar(idservicio);
			});	
		}
	})
}

function viewClient(idservicio) {
	$('.loader').show();
	$.post("../ajax/servicio.php?op=mostrar",{idservicio : idservicio},
		function(data,status)
		{
			data=JSON.parse(data);			
			$('.loader').hide();

			$("#detalle_cobro").show();
			$("#divImpuesto").hide();			
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").hide();
			//$("#btnAddArt").hide();

			let estadoServicio = data.pagado < data.total_servicio ? "PENDIENTE" : "PAGADO";

			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#marca").val(data.marca).prop("disabled", true);
			$("#placas").val(data.placas).prop("disabled", true);
			$("#modelo").val(data.modelo).prop("disabled", true);
			$("#color").val(data.color).prop("disabled", true);
			$("#ano").val(data.ano).prop("disabled", true);
			$("#kms").val(data.kms).prop("disabled", true);
			$("#estado").val(estadoServicio).prop("disabled", true);
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
			$("#idservicio").val(data.idservicio);


			$("#btnCancelar").hide();
			$("#btnRegresar").show();
			//$("#btnAgregarArt").hide();
			//$("#btnAgregarArticulosEdit").show();
		}
	)
}

function detallesServicio(idservicio) {	
	$.post("../ajax/servicio.php?op=listarDetalle&id="+idservicio,function(r){			
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

function mostrar(idservicio){	
	mostrarform(true);
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").hide();
	$('.loader').show();
	viewClient(idservicio);
	$("#btnAgregarArticulo").hide();
	$("#btnGuardar").hide();
	detallesServicio(idservicio);
}

function detallesServicioEditar(idServicio) {
	

	/*$.post("../ajax/servicio.php?op=mostrarDetalleServicio&id="+idServicio,function(r){		
		$("#detalles").html(r);
	});*/

	$.post("../ajax/servicio.php?op=mostrarDetalleServicio&id="+idServicio,function(r){			
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

function editarProductoServicio(idarticulo) {	
	var idServicio = document.getElementById("idservicio").value;
	$.post("../ajax/servicio.php?op=mostrarProductoServicio&idarticulo="+idarticulo+"&idServicio="+idServicio,function(data){		
		data = JSON.parse(data);
		$("#idProducto").val(data.idarticulo).prop("disabled", true);
		$("#descripcionProducto").val(data.descripcion).prop("disabled", false);
		$("#cantidadProducto").val(data.cantidad).prop("disabled", false);
		$("#precioProducto").val(data.precio_servicio).prop("disabled", false);		
	});	
}

function editarGuardarProductoServicio() {	

	var idProducto = document.getElementById("idProducto").value;
	var idServicio = document.getElementById("idservicio").value;
	var descripcion = document.getElementById("descripcionProducto").value;
	var cantidad = document.getElementById("cantidadProducto").value;
	var precio = document.getElementById("precioProducto").value;	

	var formData=new FormData($("#formularioProductoServicio")[0]);

	$.post("../ajax/servicio.php?op=mostrarProductoServicio&idarticulo="+document.getElementById("idProducto").value+"&idServicio="+idServicio,function(data){		
		data = JSON.parse(data);
		$.ajax({
			url: "../ajax/servicio.php?op=editarGuardarProductoServicio&idarticulo="+idProducto+"&idservicio="
			+ idServicio+"&precioViejo="+data.precio_servicio+"&stockViejo="+data.cantidad 
			+ "&descripcion="+descripcion  + "&cantidad=" + cantidad + "&precio=" + precio,
			type: "POST",
			data: formData,
			contentType: false,
			processData: false,
   
			success: function(datos){   
				console.log("DATOS: ", datos);
			   swal({
				   title: datos,
				   text: 'Se actualizo correctamente el articulo del servicio.',
				   type: 'success',
				   showConfirmButton: true,
				   //timer: 1500
			   })			
			   $("#formularioProductoServicio")[0].reset();
			   $("#editProductServicio").modal('hide');
			   detallesServicioEditar(idServicio);
			},
	   });				
	});
}


function editar(idServicio){
	mostrarform(true);
	$("#addCliente").show();
	$("#btnAgregarArt").hide();
	$("#btnAgregarArticulosEdit").show();
	$("#btnAgregarClient").show();
	//Mostrando info del cliente
	viewClient(idServicio);	
	$("#btnGuardar").show();
	detallesServicioEditar(idServicio);	
}

function cobrarServicio(idservicio){

	$('.loader').show();
	$.post("../ajax/servicio.php?op=mostrar",{idservicio : idservicio},
		function(data,status)
		{
			data=JSON.parse(data);
			console.log(data);
			mostrarform(true);
			$("#btnGuardar").show();
			$('#importe').focusout(function() {
				var datas =  parseInt(data.total_servicio);				
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
			
			$('.loader').hide();
			$("#detalle_cobro").show();
			$("#divImpuesto").hide();
			$("#addCliente").hide();

			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#forma_pago").val(data.forma_pago).prop("disabled", true);
			$("#forma_pago").selectpicker('refresh');

			$("#idauto").prop("disabled", true);
			$("#idauto").selectpicker('refresh');

			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
			$("#marca").val(data.marca).prop("disabled", true);
			$("#placas").val(data.placas).prop("disabled", true);
			$("#modelo").val(data.modelo).prop("disabled", true);
			$("#color").val(data.color).prop("disabled", true);
			$("#ano").val(data.ano).prop("disabled", true);
			$("#kms").val(data.kms).prop("disabled", true);
			$("#estado").val(data.estado).prop("disabled", true);
			$("#idservicio").val(data.idservicio);
			$("#rfc").val(data.rfc).prop("disabled", true);
			$("#direccion").val(data.direccion).prop("disabled", true);			
			$("#email").val(data.email).prop("disabled", true);
			$("#telefono").val(data.telefono).prop("disabled", true);
			$("#credito").val(data.credito).prop("disabled", true);	
			
			
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

			//idauto
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
			$("#btnCancelar").show();
			$("#btnAgregarArt").hide();
			$("#btnAgregarArticulosEdit").hide();
		});
	$.post("../ajax/servicio.php?op=listarDetalle&id="+idservicio,function(r){
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

//funcion para desactivar
function anular(idservicio){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$('.loader').show();
			$.post("../ajax/servicio.php?op=anular", {idservicio : idservicio}, function(e){
				bootbox.alert(e);
				// tabla.ajax.reload();
				obtener_registros();
				$('.loader').hide();
			});
		}
	})	
}
function cobrar(idservicio) {

	bootbox.confirm("¿Esta seguro de cobrar este servicio?", function(result){
		
		if (result) {
			$.post("../ajax/servicio.php?op=cobrar", {idservicio : idservicio}, function(e){
				bootbox.alert(e);
				// tabla.ajax.reload();
				obtener_registros();
			});
		}
	})
}

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

function agregarDetalle(idarticulo,articulo,fmsi, descripcion,publico){
	console.log("id Articulo: ", idarticulo);
	console.log("Codigo: ", articulo);
	console.log("Fmsi: ", fmsi);
	console.log("Descripción: ", descripcion);
	console.log("Precio: ", publico);
	var cantidad=1;
	var descuento=0;

	if (idarticulo!="") {
		var fila='<tr class="filas" id="fila'+cont+'">'+
        '<td><button style="width: 40px;" type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
        '<td><input type="hidden" name="idarticulo[]" value="'+idarticulo+'">'+idarticulo+'</td>'+
		'<td><input type="hidden" name="clave[]" value="'+articulo+'">'+articulo+'</td>'+
		'<td><input type="hidden" name="fmsi[]" id="fmsi[]" value="'+fmsi+'">'+fmsi+'</td>'+
		'<td><textarea class="form-control" id="descripcion[]" name="descripcion[]"rows="3" style="width: 150px;" value="'+descripcion+'">'+descripcion+'</textarea></td>'+
        '<td><input style="width: 55px;" type="number" name="cantidad[]" id="cantidad[]" value="'+cantidad+'"></td>'+
        '<td><input style="width: 70px;" type="number" name="precio_servicio[]" id="precio_servicio[]" value="'+publico+'"></td>'+
        '<td><input style="width: 70px;" type="number" name="descuento[]" value="'+descuento+'"></td>'+
        '<td><span id="subtotal'+cont+'" name="subtotal">'+publico*cantidad+'</span></td>'+
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

function agregarDetalleEdit(idarticulo,articulo,fmsi, marca, descripcion,publico, stock){	
	stock = 1;

	//console.log("ID ARTICULO: ", idarticulo, "\nCÓDIGO: ", articulo, "\nFMSI: ", fmsi, "\nMARCA: ", marca, "\nDESCRIPCIÓN: ", descripcion, "\nCOSTO: ", publico, "\nCANTIDAD: ", stock);	
	var idservicio = document.getElementById("idservicio").value;	
	console.log(`ID SERVICIO:${idservicio.trim()}`);

	if (idarticulo!="") {

		$.ajax({
			url: "../ajax/servicio.php?op=guardarProductoServicio&idservicios=" + idservicio + "&idArticulo=" + idarticulo + "&codigoArticulo="+articulo
			+ "&fmsiArticulo="+ fmsi + "&marcaArticulo="+marca + "&descripcionArticulo="+descripcion
			+ "&costoArticulo="+publico + "&cantidadArticulo="+stock+"&servicioId="+idservicio,
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
			   detallesServicioEditar(idservicio);
			},
		});

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function modificarSubtotales(){
	var cant=document.getElementsByName("cantidad[]");
	var prev=document.getElementsByName("precio_servicio[]");
	var desc=document.getElementsByName("descuento[]");
	var sub=document.getElementsByName("subtotal");
	var fmsi=document.getElementsByName("fmsi[]");
	var descripcion=document.getElementsByName("descripcion[]");
	
	for (var i = 0; i < cant.length; i++) {
		var inpV=cant[i];		
		var inpP=prev[i];
		var inpS=sub[i];
		var des=desc[i];
		var fmsis = fmsi[i];
		var descrip = descripcion[i];		
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
	$("#total_servicio").val(total);
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
