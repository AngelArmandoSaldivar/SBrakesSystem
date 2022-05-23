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
	$("#idcategoria").val("");
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
function obtener_registros(categorias){
	$('.loader').show();
	$.ajax({
		url : '../ajax/categoria.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { categorias: categorias },
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
     	url: "../ajax/categoria.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente la categoria',
				showConfirmButton: false,
				timer: 1500
			});
     		mostrarform(false);     		
			obtener_registros();
     	}
     });

     limpiar();
}

function mostrar(idcategoria){
	$.post("../ajax/categoria.php?op=mostrar",{idcategoria : idcategoria},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
			$("#descripcion").val(data.descripcion);
			$("#idcategoria").val(data.idcategoria);
		});
}


//funcion para desactivar
function desactivar(idcategoria){
	swal({
		title: '¿Está seguro de eliminar la categoria?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar categoria!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/categoria.php?op=desactivar", {idcategoria : idcategoria}, function(e){
				swal({
					title:'Categoria eliminada!',
					text: 'Se elimino correctamente la categoria.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idcategoria){
	swal({
		title: '¿Está seguro de activar la categoria?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar categoria!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/categoria.php?op=activar" , {idcategoria : idcategoria}, function(e){
				swal(
				'Categoria activado!',
				'Se activo correctamente la categoria.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

init();