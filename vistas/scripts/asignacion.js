var tabla;

//funcion que se ejecuta al inicio
function init(){	
    mostrarform(false);   
    obtener_registros();

    $("#formulario").on("submit",function(e){
        guardaryeditar(e);
    });   
    $.post("../ajax/asignacion.php?op=selectModulo", function(r){		
        $("#idmodulo").html(r);
        $("#idmodulo").selectpicker('refresh');
    });  
	$.post("../ajax/asignacion.php?op=selectRol", function(r){		
        $("#rol").html(r);
        $("#rol").selectpicker('refresh');
    });  
}

//funcion limpiar
function limpiar(){
	$("#idmodulo").val("");
	$("#nombre").val("");
	$("#descripcion").val("");	

	$("#rol").val("").prop("disabled", false);
	$("#rol").selectpicker('refresh');		
	$("#idmodulo").val("").prop("disabled", false);
	$("#idmodulo").selectpicker('refresh');
	
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
function obtener_registros(asignaciones){
	$('.loader').show();
	$.ajax({
		url : '../ajax/asignacion.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { asignaciones: asignaciones },
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
     	url: "../ajax/asignacion.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente la asignación',
				showConfirmButton: false,
				timer: 1500
			});
     		mostrarform(false);     		
			obtener_registros();
     	}
     });

     limpiar();
}

function mostrar(idasignacion){
	$.post("../ajax/asignacion.php?op=mostrar",{idasignacion : idasignacion},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#rol").val(data.idrol).prop("disabled", false);
			$("#rol").selectpicker('refresh');
            $("#idmodulo").val(data.idmodulo).prop("disabled", false);
			$("#idmodulo").selectpicker('refresh');            
			$("#idasignacion").val(data.idrelrolmodulo);
		});
}


//funcion para desactivar
function desactivar(idmodulo){
	swal({
		title: '¿Está seguro de eliminar el modulo?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar modulo!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/asignacion.php?op=desactivar", {idmodulo : idmodulo}, function(e){
				swal({
					title:'modulo eliminado!',
					text: 'Se elimino correctamente el modulo.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idmodulo){
	swal({
		title: '¿Está seguro de activar el modulo?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar modulo!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/asignacion.php?op=activar" , {idmodulo : idmodulo}, function(e){
				swal(
				'modulo activado!',
				'Se activo correctamente el modulo.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

init();