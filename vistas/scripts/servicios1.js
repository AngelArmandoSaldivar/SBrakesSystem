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
function limpiar(){

	$("#idcliente").val("");
	$("#cliente").val("");
	$("#impuesto").val("");

	$("#total_servicio").val("");
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
	location.replace("servicio.php");
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
     		bootbox.alert(datos);
     		mostrarform(false);
     		obtener_registros();
     	},
		 complete: function() {
			$('.loader').hide();
			$.post("../ajax/servicio.php?op=ultimoServicio",
			function(data,status)
			{
				var ultimoIdServicio = data.replace(/['"]+/g, '');
				window.open(
					`../reportes/exFacturaServicio.php?id=${ultimoIdServicio}`,
					'_blank'
				);
			});
			
		},dataType: 'html'
     });

     limpiar();
}

function cobrarServicio(idservicio){

	$('.loader').show();
	$.post("../ajax/servicio.php?op=mostrar",{idservicio : idservicio},
		function(data,status)
		{
			data=JSON.parse(data);

			$("#btnAddArt").hide();
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
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
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

function mostrar(idservicio){
	$('.loader').show();
	$.post("../ajax/servicio.php?op=mostrar",{idservicio : idservicio},
		function(data,status)
		{
			data=JSON.parse(data);		
			console.log(data);
			mostrarform(true);
			$('.loader').hide();

			$("#detalle_cobro").show();
			$("#divImpuesto").hide();
			$("#addCliente").hide();
			//ocultar y mostrar los botones
			$("#btnGuardar").hide();
			$("#btnCancelar").show();
			$("#btnAddArt").hide();

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
									
		});
	$.post("../ajax/servicio.php?op=listarDetalle&id="+idservicio,function(r){		
		$("#detalles").html(r);
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
