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
			bootbox.alert(datos);
			mostrarform(false);
			tabla.ajax.reload();
		}
	});

	limpiar();
	
}

function mostrar(idarticulo){
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			mostrarform(true);
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
		})
}

//funcion para desactivar
function desactivar(idarticulo){
	bootbox.confirm("¿Esta seguro de desactivar este dato?", function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=desactivar", {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
			});
		}
	})
}

function activar(idarticulo){
	bootbox.confirm("¿Esta seguro de activar este dato?" , function(result){
		if (result) {
			$.post("../ajax/articulo.php?op=activar" , {idarticulo : idarticulo}, function(e){
				bootbox.alert(e);
				tabla.ajax.reload();
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