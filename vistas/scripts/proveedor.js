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

function activarPopover() {
	$(function () {
		$('[data-toggle="popover"]').popover()		
	})
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
		activarPopover();
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
     	url: "../ajax/persona.php?op=guardareditarproveedor",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,
     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: datos,
				showConfirmButton: true,
				//timer: 1500
			});
     		mostrarform(false);     		
			obtener_registros();
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
			$("#direccion").val(data.direccion);
			$("#telefono").val(data.telefono);
			$("#email").val(data.email);
			$("#rfc").val(data.rfc);
			$("#idpersona").val(data.idpersona);
		})
}


//funcion para desactivar
function eliminar(idpersona){
	swal({
		title: '¿Está seguro de borrar el proveedor?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, borrar proveedor!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/persona.php?op=eliminar", {idpersona : idpersona }, function(e){
				swal({
					title:'Proveedor eliminado!',
					text: 'Se elimino correctamente el proveedor.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}


init();