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

	$("#nombre").val("");
	$("#num_documento").val("");
	$("#direccion").val("");
	$("#telefono").val("");
	$("#email").val("");
	$("#idpersona").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){
		$("#listadoregistros").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
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

function obtener_registros(personas){
	$.ajax({
		url : '../ajax/persona.php?op=listarp',
		type : 'POST',
		dataType : 'html',
		data : { personas: personas },
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

//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/persona.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
     		bootbox.alert(datos);
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(idpersona){
	$.post("../ajax/persona.php?op=mostrar",{idpersona : idpersona},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#tipo_documento").val(data.tipo_documento);
			$("#tipo_documento").selectpicker('refresh');
			$("#num_documento").val(data.num_documento);
			$("#direccion").val(data.direccion);
			$("#telefono").val(data.telefono);
			$("#email").val(data.email);
			$("#idpersona").val(data.idpersona);
		})
}


//funcion para desactivar
function eliminar(idpersona){
	bootbox.confirm("¿Esta seguro de eliminar este dato?", function(result){
		if (result) {

			$.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona }, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}


init();