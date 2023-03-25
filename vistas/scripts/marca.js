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
	$("#idmarca").val("");
	$("#descripcion").val("");
    $("#utilidad_1").val("");
    $("#utilidad_2").val("");
	$("#utilidad_3").val("");	
    $("#utilidad_4").val("");	
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
function obtener_registros(marcas){
	$('.loader').show();
	$.ajax({
		url : '../ajax/marca.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { marcas: marcas },
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
     	url: "../ajax/marca.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente la marca',
				showConfirmButton: false,
				timer: 1500
			});
     		mostrarform(false);     		
			obtener_registros();
     	}
     });

     limpiar();
}

function mostrar(idmarca){
	$.post("../ajax/marca.php?op=mostrar",{idmarca : idmarca},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#descripcion").val(data.descripcion);
			$("#idmarca").val(data.idmarca);
            $("#utilidad_1").val(data.utilidad_1);
            $("#utilidad_2").val(data.utilidad_2);
            $("#utilidad_3").val(data.utilidad_3);
            $("#utilidad_4").val(data.utilidad_4);
		});
}


//funcion para desactivar
function desactivar(idmarca){
	swal({
		title: '¿Está seguro de eliminar la marca?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar marca!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/marca.php?op=desactivar", {idmarca : idmarca}, function(e){
				swal({
					title:'Marca eliminada!',
					text: 'Se elimino correctamente la marca.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idmarca){
	swal({
		title: '¿Está seguro de activar la marca?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar marca!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/marca.php?op=activar" , {idmarca : idmarca}, function(e){
				swal(
				'Marca activado!',
				'Se activo correctamente la marca.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

init();