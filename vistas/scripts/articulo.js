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

	//Revisamos si la paginación esta en 1 para no mostrar el boton de Anterior
	let cachaPaginaNumber = Number($("#pagina").val());	
	if(cachaPaginaNumber <= 1) {
		$("#anterior").hide();
	}	

}

/*========================================================================================== */
/*===============================FILTROS==================================================== */
/*========================================================================================== */

function paginaSiguiente() {

	let cachaPaginaNumber = Number($("#pagina").val());
	cachaPaginaNumber = cachaPaginaNumber + 1;

	document.getElementById("pagina").value=cachaPaginaNumber;
	if(cachaPaginaNumber > 1) {
		$("#anterior").show();
	}
	obtenerRegistrosSiguiente(cachaPaginaNumber);
}

function obtenerRegistrosSiguiente(pagina) {	

	window.scroll({
		top: 50,
		left: 0,
		behavior: 'smooth'
	});

	let cachaPaginaNumber = pagina;
	
	let limite_registros = Number($("#limite_registros").val());
	let busqueda = $("#busqueda").val();

	if(busqueda != "" && limite_registros > 0) {
		let total_registros = Number(((limite_registros * cachaPaginaNumber) - cachaPaginaNumber) + 1);
		let inicio_registros = (total_registros - limite_registros) + 1;
		$('.loaderSearch').show();

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:busqueda,inicio_registros: inicio_registros, total_registros: limite_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	} 
	if(busqueda != "" && limite_registros == 0 && cachaPaginaNumber > 1) {		
		let total_registros = Number(50 * cachaPaginaNumber);
		let inicio_registros = (total_registros - 50);
		$('.loaderSearch').show();

		console.log("Inicio 3: ", inicio_registros);
		console.log("Fin 3: ", 50);

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:busqueda,inicio_registros: inicio_registros, total_registros: 50},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	} 

	if(busqueda == "" && limite_registros == 0 && cachaPaginaNumber > 1) {
		let total_registros = Number(50 * cachaPaginaNumber);
		let inicio_registros = (total_registros - 50);
		$('.loaderSearch').show();			
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros: inicio_registros, total_registros: 50},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	} 
}

function paginaAnterior() {

	let cachaPaginaNumber = Number($("#pagina").val());	
	cachaPaginaNumber = cachaPaginaNumber - 1;
	document.getElementById("pagina").value=cachaPaginaNumber;

	if(cachaPaginaNumber <= 1) {
		$("#anterior").hide();
	}
	obtenerRegistrosAnterior(cachaPaginaNumber);
}

function obtenerRegistrosAnterior(cachaPaginaNumber) {

	window.scroll({
		top: 50, 
		left: 0, 
		behavior: 'smooth'
	});

	let limite_registros = Number($("#limite_registros").val());
	let busqueda = $("#busqueda").val();		

	$('.loaderSearch').show();

	if(busqueda != "" && limite_registros > 0 && cachaPaginaNumber > 1) {

		let total_registros = Number(((limite_registros * cachaPaginaNumber) - cachaPaginaNumber));
		let inicio_registros = (total_registros - limite_registros) + 1;			
		
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:busqueda,inicio_registros: inicio_registros, total_registros: limite_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	}

	if(busqueda != "" && limite_registros > 0 && cachaPaginaNumber == 1) {		
		let total_registros = Number(((limite_registros * cachaPaginaNumber) - cachaPaginaNumber));
		let inicio_registros = (total_registros - limite_registros) + 1;	

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:busqueda,inicio_registros: inicio_registros, total_registros: limite_registros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	}

	if(busqueda != "" && limite_registros === 0 && cachaPaginaNumber === 1) {
		obtener_registros(busqueda);		
	}	

	if(busqueda != "" && limite_registros == 0 && cachaPaginaNumber > 1) {

		let final_registros = Number(50 * cachaPaginaNumber);
		let inicio = (final_registros - 50);

		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : { busqueda: busqueda, inicio_registros: inicio, total_registros: 50},
		})
		.done(function(resultado){			
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
		})
	}

	if(busqueda == "" && limite_registros == 0 && cachaPaginaNumber > 1) {
		console.log("Llegaste solo paginado");
		let final_registros = Number(50 * cachaPaginaNumber);
		let inicio = (final_registros - 50);

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros: inicio, total_registros: 50},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	}

	if(busqueda == "" && limite_registros == 0 && cachaPaginaNumber == 1) {
		console.log("Llegaste solo paginado");
		let final_registros = Number(50 * cachaPaginaNumber);
		let inicio = (final_registros - 50);		

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {inicio_registros: inicio, total_registros: 50},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
		})
	}
}

function mostrarRegistrosLimite(limites) {

	let busqueda = $("#busqueda").val();

	if(limites > 0 && busqueda != "") {
		$("#siguiente").show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {articulos:busqueda, limites: limites},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
		})
	}	
}

var select = document.getElementById('limite_registros');
select.addEventListener('change',
function(){
	$('.loaderSearch').show();
	let selectedOption = this.options[select.selectedIndex];
	let limites = selectedOption.value;

	mostrarRegistrosLimite(limites);

});

function activarPopover() {
	$(function () {
		$('[data-toggle="popover"]').popover()		
	})
}

function obtenerScrollY(scrollnumber) {
	$('#container').scroll(0,scrollnumber);
}


function obtener_registros(articulos){	

	var busqueda = articulos;

	let limitesRegistros = $("#limite_registros").val();
	let paginado = $("#pagina").val();		

	if(articulos == undefined && limitesRegistros == null && paginado == 1) {		
		//var ContainerElement = document.getElementById("container");
		//var y = ContainerElement.scrollTop;
		//ContainerElement.scroll(0,1000)

		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : { articulos: articulos},
		})
		.done(function(resultado){		
			//$('#container').scroll(0,1000);			

			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();	
			activarPopover();
		})

		
	}	
		
	if(articulos != undefined && limitesRegistros == null && paginado == 1) {

		console.log("Busqueda: ", articulos);

		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : { articulos: busqueda},
		})
		.done(function(resultado){
			/*let y = $( "#container" ).scrollTop();
			let x = $( "#container" ).scrollLeft();
			console.log("X: ", x);
			let numero = 0;
			
			$('#container').scroll(x,y);
			console.log("SCROLL TOP: ", y);*/

			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})

	}
	if(articulos != undefined && limitesRegistros > 0 && paginado == 1) {			
		$('.loaderSearch').show();
		$("#siguiente").show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {articulos:articulos, limites: limitesRegistros},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	} 
	
	if(articulos != undefined && limitesRegistros > 0 && paginado > 1) {			
		let total_registros = Number(((limitesRegistros * paginado) - paginado) + 1);		
		let inicio_registros = (total_registros - limitesRegistros) + 1;
	
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:articulos,inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})

	} 

	if(articulos != "" && limitesRegistros == null && paginado > 1) {			
		let total_registros = Number(50 * paginado);
		let inicio_registros = (total_registros - 50);

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:articulos,inicio_registros: inicio_registros, total_registros: 50},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})

	}
}

//Filtro busqueda
$(document).on('keyup', '#busqueda', function(){
	
	var valorBusqueda=$(this).val();	
	
	if (valorBusqueda!="")
	{		
		obtener_registros(valorBusqueda);		

	} else {				
		obtener_registros();
	}
});

setInterval(() => {
	let busqueda = document.getElementById("busqueda").value;
	$('.loaderSearch').show();
	setTimeout(() => {
		obtener_registros(busqueda);
		$('.loaderSearch').hide();
	}, 500);
}, 5000);

/*========================================================================================== */
/*=============================== FIN FILTROS=============================================== */
/*========================================================================================== */



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

function mostrarArticuloSolicitud(idarticulo) {
	console.log("Solicitar", idarticulo);
	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
		{
			data=JSON.parse(data);
			console.log(data);
			$("#clave_producto").val(data.codigo).prop("disabled", true)
			$("#marcaProducto").val(data.marca).prop("disabled", true)
			$("#idarticuloPedido").val(data.idarticulo);
	})
	/*swal({
		position: 'top-end',
		type: 'success',
		title: 'Función aun no disponible',
		showConfirmButton: false,
		timer: 1500
	});*/
}

function guardarSolicitudArticulo() {
	var idarticulo = $("#idarticuloPedido").val();
	var marcaproducto = $("#marcaProducto").val();
	var claveProducto = $("#clave_producto").val();
	var cantidad = $("#cantidad").val();
	var fechaSolicitud = $("#fechaSolicitud").val();
	var estadoSolicitud = $("#estado_solicitud").val();
	var notaAdicional = $("#notaAdicional").val();
	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);

	$.ajax({
		url: "../ajax/articulo.php?op=guardarSolicitud&" + "idarticulo=" + idarticulo + "&marca=" + marcaproducto + 
		"&clave=" + claveProducto + "&cantidad=" + cantidad + "&fecha=" + fechaSolicitud + "&estadoPedido=" + estadoSolicitud +
		"&nota=" + notaAdicional + "&fecha_registro=" + today,
		method: "POST",		
		success: function(data) {
			swal({
				title: data,
				text: 'Se guardo correctamente el pedido.',
				type: 'success',
				showConfirmButton: false,
				timer: 1500
			});
			$("#clave_producto").val("");
			$("#marcaProducto").val("");
			$("#cantidad").val("");
			$("#fechaSolicitud").val("");
			$("#estado_solicitud").val("");
			$("#notaAdicional").val("");
			$("#solicitarArticulo").modal('hide');
			
		},
		error: function(request, status, error) {
			alert("Error: ", error);
		}
	})
	
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

function exportarExcel() {
	window.open(
		`../reportes/exportExcel.php`,
		'_blank');
}

init();