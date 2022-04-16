var tabla;

//funcion que se ejecuta al inicio
function init(){
   mostrarform(false);
   obtener_registros();
   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   })

   //cargamos los items al select categoria
   $.post("../ajax/articulo.php?op=selectCategoria", function(r){	
   	$("#idcategoria").html(r);
   	$("#idcategoria").selectpicker('refresh');
   });

    //cargamos los items al select proveedor
	$.post("../ajax/ingreso.php?op=selectProveedor", function(r){
		$("#idproveedor").html(r);
		$('#idproveedor').selectpicker('refresh');
	});
}

// function refresh() {
// 	obtener_registros();
// }

//funcion limpiar
function limpiar(){
	$("#idarticulo").val("");
	$("#idcategoria").val("");
	$("#codigo").val("");
	$("#stock").val("");
	$("#descripcion").val("");
	$("#fmsi").val("");
	$("#marca").val("");
	$("#publico").val("");
	$("#taller").val("");
	$("#credito_taller").val("");
	$("#mayoreo").val("");
	$("#costo").val("");	
	$("#idproveedor").val("");
	$("#pasillo").val("");
	$("#unidades").val("");
	$("#barcode").val("");
	$("#print").hide();	
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){		
		$("#idcategoria").prop("disabled", false);
		$("#idcategoria").selectpicker('refresh');
		$("#idproveedor").prop("disabled", false);
		$("#idproveedor").selectpicker('refresh');
		$("#codigo").prop("disabled", false);
		$("#fmsi").prop("disabled", false);
		$("#marca").prop("disabled", false);
		$("#unidades").prop("disabled", false);
		$("#pasillo").prop("disabled", false);
		$("#nombre").prop("disabled", false);
		$("#stock").prop("disabled", false);
		$("#costo").prop("disabled", false);
		$("#publico").prop("disabled", false);
		$("#taller").prop("disabled", false);
		$("#credito_taller").prop("disabled", false);
		$("#mayoreo").prop("disabled", false);
		$("#descripcion").prop("disabled", false);
		$("#barcode").prop("disabled", false);
		$("#idarticulo").prop("disabled", false);
		$("#table-search").hide();
		$("#formularioregistros").show();
		$("#btnGuardar").prop("disabled",false);
		$("#btnagregar").hide();
	}else{
		$("#table-search").show();
		$("#formularioregistros").hide();
		$("#btnagregar").show();
	}
}

//cancelar form
function cancelarform(){
	limpiar();
	mostrarform(false);
}

function actualizarStock(profile_viewer_uid) {	

	$.ajax({
		url: "../ajax/articulo.php?op=listar",
		method: "POST",
		data: { profile_viewer_uids: profile_viewer_uid },
		success: function(data) {
			console.log(data);
		},
		error: function(request, status, error) {
			alert("Error: ", error);
		}
	})
	
}

function obtener_registros(articulos){
	$('.loader').show();
	$.ajax({
		url : '../ajax/articulo.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { articulos: articulos},
	})
	.done(function(resultado){
		setTimeout(() => {
			$("#tabla_resultado").html(resultado);
			$('.loader').hide();
		}, 50);
	})
}	

$(document).on('keyup', '#busqueda', function(){

		var valorBusqueda=$(this).val();
	
	if (valorBusqueda!="")
	{
		obtener_registros(valorBusqueda);		
	} else {
		obtener_registros(valorBusqueda);
	}
});

//funcion para guardaryeditar
function guardaryeditar(e){
	e.preventDefault();//no se activara la accion predeterminada 
	$("#btnGuardar").prop("disabled",true);
	var formData=new FormData($("#formulario")[0]);

	$.ajax({
		url: "../ajax/articulo.php?op=guardaryeditar",
		type: "POST",
		data: formData,
		contentType: false,
		processData: false,
		success: function(datos){
			swal({
				position: 'top-end',
				type: 'success',
				title: 'Se guardo correctamente el articulo',
				showConfirmButton: false,
				timer: 1500
			});
			mostrarform(false);
			// tabla.ajax.reload();
		}
	});

	limpiar();
	
}

function mostrar(idarticulo){
	$('.loader').show();
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);	
			$('.loader').hide();		
			$("#idcategoria").val(data.idcategoria).prop("disabled", true);
			$("#idcategoria").selectpicker('refresh');
			$("#idproveedor").val(data.idproveedor).prop("disabled", true);
			$("#idproveedor").selectpicker('refresh');
			$("#codigo").val(data.codigo).prop("disabled", true);
			$("#fmsi").val(data.fmsi).prop("disabled", true);
			$("#marca").val(data.marca).prop("disabled", true);
			$("#unidades").val(data.unidades).prop("disabled", true);
			$("#pasillo").val(data.pasillo).prop("disabled", true);
			$("#nombre").val(data.nombre).prop("disabled", true);
			$("#stock").val(data.stock).prop("disabled", true);
			$("#costo").val(data.costo).prop("disabled", true);
			$("#publico").val(data.publico).prop("disabled", true);
			$("#taller").val(data.taller).prop("disabled", true);
			$("#credito_taller").val(data.credito_taller).prop("disabled", true);
			$("#mayoreo").val(data.mayoreo).prop("disabled", true);
			$("#descripcion").val(data.descripcion).prop("disabled", true);
			$("#barcode").val(data.barcode).prop("disabled", true);
			$("#idarticulo").val(data.idarticulo).prop("disabled", true);
			$("#btnGuardar").hide();
		});
		limpiar();
}

function editarArticulo(idarticulo){
	$('.loader').show();
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
			$('.loader').hide();
			$("#idcategoria").val(data.idcategoria);
			$("#idcategoria").selectpicker('refresh');
			$("#idproveedor").val(data.idproveedor);
			$("#idproveedor").selectpicker('refresh');
			$("#codigo").val(data.codigo);
			$("#fmsi").val(data.fmsi);
			$("#marca").val(data.marca);
			$("#unidades").val(data.unidades);
			$("#pasillo").val(data.pasillo);
			$("#nombre").val(data.nombre);
			$("#stock").val(data.stock);
			$("#costo").val(data.costo);
			$("#publico").val(data.publico);
			$("#taller").val(data.taller);
			$("#credito_taller").val(data.credito_taller);
			$("#mayoreo").val(data.mayoreo);
			$("#descripcion").val(data.descripcion);
			$("#barcode").val(data.barcode);
			$("#idarticulo").val(data.idarticulo);
			$("#btnGuardar").show();
		});
		limpiar();
}

//funcion para desactivar
function desactivar(idarticulo){
	swal({
		title: '¿Está seguro de eliminar el articulo?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar articulo!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
				swal({
					title:'Articulo eliminado!',
					text: 'Se elimino correctamente el articulo.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idarticulo){
	swal({
		title: '¿Está seguro de activar el articulo?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'question',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, activar articulo!'
	  }).then(function(result){
		  if(result.value) {
			$.post("../ajax/articulo.php?op=activar" , {idarticulo : idarticulo}, function(e){
				swal(
				'Articulo activado!',
				'Se activo correctamente el articulo.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

function generarbarcode(){
	codigo_barras=$("#barcode").val();	
	JsBarcode("#barras",codigo_barras);
	$("#print").show();
}

function imprimir(){
	$("#print").printArea();
}

init();