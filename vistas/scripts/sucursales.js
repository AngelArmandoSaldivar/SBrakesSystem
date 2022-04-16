var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   listar();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

   $("#imagenmuestra").hide();
//mostramos los permisos
$.post("../ajax/sucursal.php?op=permisos&id=", function(r){
	$("#permisos").html(r);
});
}

//funcion limpiar
function limpiar(){
	$("#nombre").val("");
    $("#telefono").val("");
    $("#direccion").val("");
	$("#idsucursal").val("");
    $("#numVentas").val("");
    $("#lat").val("");
    $("#lng").val("");
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

//funcion listar
function listar(){
	tabla=$('#tbllistado').dataTable({
		"aProcessing": true,//activamos el procedimiento del datatable
		"aServerSide": true,//paginacion y filrado realizados por el server
		dom: 'Bfrtip',//definimos los elementos del control de la tabla
		buttons: [
                  'copyHtml5',
                  'excelHtml5',
                  'csvHtml5',
                  'pdf'
		],
		"ajax":
		{
			url:'../ajax/sucursal.php?op=listar',
			type: "get",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":5,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}
//funcion para guardaryeditar
function guardaryeditar(e){
     e.preventDefault();//no se activara la accion predeterminada 
     $("#btnGuardar").prop("disabled",true);
     var formData=new FormData($("#formulario")[0]);

     $.ajax({
     	url: "../ajax/sucursal.php?op=guardaryeditar",
     	type: "POST",
     	data: formData,
     	contentType: false,
     	processData: false,

     	success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente la sucursal',
				showConfirmButton: false,
				timer: 1500
			});
     		mostrarform(false);
     		tabla.ajax.reload();
     	}
     });

     limpiar();
}

function mostrar(idsucursal){
	$.post("../ajax/sucursal.php?op=mostrar",{idsucursal : idsucursal},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);

			$("#nombre").val(data.nombre);
            $("#telefono").val(data.telefono);
            $("#direccion").val(data.direccion);
            $("#idsucursal").val(data.idsucursal);
            $("#numVentas").val(data.numVentas);
            $("#lat").val(data.lat);
            $("#lng").val(data.lng);
		});
	$.post("../ajax/sucursal.php?op=permisos&id="+idsucursal, function(r){
	$("#permisos").html(r);
});
}


//funcion para desactivar
function desactivar(idsucursal){
	swal({
		title: '¿Está seguro de borrar la sucursal?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, borrar sucursal!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/sucursal.php?op=desactivar", {idsucursal : idsucursal}, function(e){
				swal({
					title:'Sucursal eliminada!',
					text: 'Se elimino correctamente la sucursal.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				tabla.ajax.reload();
			});	
		}
	})
}

function activar(idsucursal){
	swal({
		title: '¿Está seguro de activar la sucursal?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar sucursal!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/sucursal.php?op=activar", {idsucursal : idsucursal}, function(e){
				swal({
					title:'Sucursal activada!',
					text: 'Se activo correctamente la sucursal.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				tabla.ajax.reload();
			});
		  }
		})
}


init();