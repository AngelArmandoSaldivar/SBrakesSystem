var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   obtener_registros();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });
}

//funcion limpiar
function limpiar(){

	detalles=0;
	$("#nombre").val("").prop("disabled", false);
	$("#direccion").val("").prop("disabled", false);
	$("#telefono").val("").prop("disabled", false);
	$("#telefono_local").val("").prop("disabled", false);
	$("#email").val("").prop("disabled", false);
	$("#rfc").val("").prop("disabled", false);
	$("#credito").val("").prop("disabled", false);
	$("#tipo_precio").val("").prop("disabled", false);
	$("#tipo_precio").selectpicker('refresh');
	$("#idpersona").val("");
	$(".filas").remove();
	
}

function agregarCliente() {
	mostrarform(true);	
	$("#btnAgregarAuto").show();
	$("#btnGuardar").show();
}

//funcion mostrar formulario 
function mostrarform(flag){	
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
		detalles=0;
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
}

function activarPopover() {
	$(function () {
		$('[data-toggle="popover"]').popover()		
	})
}

//funcion listar
function obtener_registros(clientes){
	$.ajax({
		url : '../ajax/persona.php?op=listarc',
		type : 'POST',
		dataType : 'html',
		data : { clientes: clientes },
	})
	.done(function(resultado){
		$("#tabla_resultado").html(resultado);
		activarPopover();
	})
}

$(document).on('keyup', '#busqueda', function()
{
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

//funcion para guardaryeditar
function guardaryeditar(e){	
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);
	 $("#btnAgregarAuto").show();

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
			title: datos,
			showConfirmButton: false,
			timer: 1500
		});
		obtener_registros();
		mostrarform(false);
		}
     });

     limpiar();
}
function editar(idpersona) {	
	$('.loader').show();
	$("#btnAgregarAuto").show();
	$("#btnGuardar").show();
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
			$('.loader').hide();

			$("#nombre").val(data.nombre);
			$("#tipo_documento").val(data.tipo_documento);
			$("#tipo_documento").selectpicker('refresh');
			$("#num_documento").val(data.num_documento);
			$("#direccion").val(data.direccion);
			$("#telefono").val(data.telefono);
			$("#telefono_local").val(data.telefono_local);
			$("#email").val(data.email);
			$("#rfc").val(data.rfc);
			$("#credito").val(data.credito);
			$("#tipo_precio").val(data.tipo_precio).prop("disabled", false);
			$("#tipo_precio").selectpicker('refresh');
			$("#idpersona").val(data.idpersona);
			//btnAgregarAut
		});
	$.post("../ajax/persona.php?op=listarAutos&id="+idpersona,function(r){		
		$("#detalles").html(r);
	});
}

function detallesAuto(idpersona) {
	$.post("../ajax/persona.php?op=listarAutos&id="+idpersona,function(r){
		$("#detalles").html(r);
	});
}

//funcion para desactivar
function eliminar(idpersona){

	swal({
		title: '¿Está seguro de eliminar el cliente?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar cliente!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona }, function(e){
				swal({
					title:'Cliente eliminado!',
					text: 'Se elimino correctamente el cliente.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function mostrar(idpersona){
	$('.loader').show();
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona},
	function(data,status)
	{
		data=JSON.parse(data);
		mostrarform(true);
		$('.loader').hide();

		$("#nombre").val(data.nombre).prop("disabled", true);
		$("#tipo_documento").val(data.tipo_documento).prop("disabled", true);
		$("#tipo_documento").selectpicker('refresh').prop("disabled", true);
		$("#num_documento").val(data.num_documento).prop("disabled", true);
		$("#direccion").val(data.direccion).prop("disabled", true);
		$("#telefono").val(data.telefono).prop("disabled", true);
		$("#telefono_local").val(data.telefono_local).prop("disabled", true);
		$("#email").val(data.email).prop("disabled", true);
		$("#rfc").val(data.rfc).prop("disabled", true);
		$("#credito").val(data.credito).prop("disabled", true);
		$("#tipo_precio").val(data.tipo_precio).prop("disabled", true);
		$("#tipo_precio").selectpicker('refresh').prop("disabled", true);
		$("#idpersona").val(data.idpersona);

		$("#btnGuardar").hide();
		$("#btnCancelar").show();
		$("#btnAgregarAuto").hide();
		//btnAgregarAut
	});
	detallesAuto(idpersona);
}

function eliminarAuto(idauto) {

	let idpersona = document.getElementById("idpersona").value;

	swal({
		title: '¿Está seguro de eliminar el auto?',
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
				url: "../ajax/persona.php?op=eliminarAuto&" + "idauto=" + idauto,
				type: "POST",
				data: "",
				contentType: false,
				processData: false,

				success: function(datos){				
				swal({
					title: datos,
					text: 'Se elimino correctamente el auto.',
					type: 'success',
					showConfirmButton: true,
					//timer: 1500
				})
				detallesAuto(idpersona);
				},
			});
		}
	})

}
function limpiarFormAuto() {
	console.log("Llegaste")
	$("#placas").val("");
	$("#marca").val("");
	$("#modelo").val("");
	$("#ano").val("");
	$("#color").val("");
	$("#kms").val("");

}
//placas, marca, modelo, ano, color, kms

$("#placas").change(placasAuto);
function placasAuto() {
	var placas = $("#placas").val();
	$("#marca").change(marcaAuto);
	function marcaAuto() {
		var marca = $("#marca").val();
		$("#modelo").change(modeloAuto);
		function modeloAuto() {
			var modelo = $("#modelo").val();
			$("#ano").change(anoAuto);
			function anoAuto() {
				var ano = $("#ano").val();
				$("#color").change(colorAuto);
				function colorAuto() {
					var color = $("#color").val();
					$("#kms").change(kmsAuto);
					function kmsAuto() {
						var kms = $("#kms").val();
						()=> {
							agregarDetalleAuto(placas, marca, modelo, ano, color, kms);
						}						
					}			
				}
			}
		}
	}
}

var cont=0;
var detalles=0;

function agregarDetalleAuto(placas, marca, modelo, ano, color, kms) {
	console.log(placas.value, marca.value, modelo.value, ano.value, color.value, kms.value);
	if (kms != "") {
		var fila='<tr class="filas" id="fila'+cont+'">'+		
        '<td><button style="width: 40px;" type="button" class="btn btn-danger" onclick="eliminarDetalle('+cont+')">X</button></td>'+
		'<td></td>' +
		'<td><input type="hidden" name="placas" value="'+placas.value+'">'+placas.value+'</td>'+
		'<td><input type="hidden" name="marca" value="'+marca.value+'">'+marca.value+'</td>'+
		'<td><input type="hidden" name="modelo" value="'+modelo.value+'">'+modelo.value+'</td>'+
		'<td><input type="hidden" name="ano" value="'+ano.value+'">'+ano.value+'</td>'+
		'<td><input type="hidden" name="color" value="'+color.value+'">'+color.value+'</td>'+
		'<td><input type="hidden" name="kms" value="'+kms.value+'">'+kms.value+'</td>'+
		'</tr>';
		cont++;
		detalles++;
		$('#detalles').append(fila);
		$("#btnAgregarAut").hide();
		// limpiarDetalle();
		limpiarFormAuto();

	}else{
		alert("error al ingresar el detalle, revisar las datos del articulo ");
	}
}

function eliminarDetalle(indice){
	$("#fila"+indice).remove();
	detalles=detalles-1;
	$("#btnAgregarAut").show();
}

init();