var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
//    mostrarFormCobro(false);
   obtener_registros();
   obtener_registrosProductos();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });

//    $("#formularioCobrar").on("submit",function(e){
// 	cobrar(e);
// 	});

   //cargamos los items al select cliente
   $.post("../ajax/venta.php?op=selectCliente", function(r){
   	$("#idcliente").html(r);
   	$('#idcliente').selectpicker('refresh');
   });
}

//funcion limpiar
function limpiar(){

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
// function mostrarFormCobro(flag) {
// 	limpiar();
// 	if(flag){
// 		console.log("Flag Cobro: ", flag);
// 		$("#listadoregistros").hide();
// 		// $("#formularioCobro").show();
// 		$("#btnGuardar").prop("disabled",false);
// 		$("#btnagregar").hide();
// 		$("#btnGuardar").hide();
// 		$("#btnCancelar").show();
// 		detalles=0;
// 		$("#btnAgregarArt").show();
// 	}else{
// 		$("#listadoregistros").show();
// 		// $("#formularioCobro").hide();
// 		$("#btnagregar").show();
// 	}
// }

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
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
	$.ajax({
		url : '../ajax/venta.php?op=listarProductos',
		type : 'POST',
		dataType : 'html',
		data : { productos: productos },
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

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     //$("#btnGuardar").prop("disabled",true);	 
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/venta.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		obtener_registros();
     	}
     });

     limpiar();
}

function mostrar(idventa){
	$.post("../ajax/venta.php?op=mostrar",{idventa : idventa},
		function(data,status)
		{
			data=JSON.parse(data);			
			mostrarform(true);

			$("#idcliente").val(data.idcliente).prop("disabled", true);
			$("#idcliente").selectpicker('refresh');
			$("#tipo_comprobante").val(data.tipo_comprobante).prop("disabled", true);
			$("#tipo_comprobante").selectpicker('refresh');	
			$("#forma_pago").val(data.forma_pago).prop("disabled", true);
			$("#forma_pago").selectpicker('refresh');	
			$("#fecha_hora").val(data.fecha).prop("disabled", true);
			$("#impuesto").val(data.impuesto).prop("disabled", true);
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
			$.post("../ajax/venta.php?op=anular", {idventa : idventa}, function(e){
				bootbox.alert(e);
				// tabla.ajax.reload();
				obtener_registros();
			});
		}
	})	
}
function cobrar(idventa) {

	bootbox.confirm("¿Esta seguro de cobrar esta venta?", function(result){
		
		if (result) {
			$.post("../ajax/venta.php?op=cobrar", {idventa : idventa}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
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
        '<td><input style="width: 70px;" type="number" name="precio_venta[]" id="precio_venta[]" value="'+publico+'"></td>'+
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
	var prev=document.getElementsByName("precio_venta[]");
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