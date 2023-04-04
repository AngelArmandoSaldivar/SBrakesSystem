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
	$("#idrol").val("");
	$("#nombre").val("");
	$("#descripcion").val("");	
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

//funcion listar
function obtener_registros(roles){
	$('.loader').show();
	$.ajax({
		url : '../ajax/rol.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { roles: roles },
	})
	.done(function(resultado){
		$('.loader').hide();
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

     $.ajax({
     	url: "../ajax/rol.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente el rol',
				showConfirmButton: false,
				timer: 1500
			});
     		mostrarform(false);     		
			obtener_registros();
     	}
     });

     limpiar();
}

function mostrar(idrol){
	$.post("../ajax/rol.php?op=mostrar",{idrol : idrol},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#descripcion").val(data.descripcion);
			$("#idrol").val(data.idrol);
		});
}


//funcion para desactivar
function desactivar(idrol){
	swal({
		title: '¿Está seguro de eliminar el rol?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar rol!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/rol.php?op=desactivar", {idrol : idrol}, function(e){
				swal({
					title:'Rol eliminado!',
					text: 'Se elimino correctamente el rol.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idrol){
	swal({
		title: '¿Está seguro de activar el rol?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar rol!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/rol.php?op=activar" , {idrol : idrol}, function(e){
				swal(
				'Rol activado!',
				'Se activo correctamente el rol.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

init();