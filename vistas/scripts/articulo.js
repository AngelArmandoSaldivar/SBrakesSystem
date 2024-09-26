var tabla;
//funcion que se ejecuta al inicio
function init(){
	mostrarform(false);
	obtener_registros();
	$("#formulario").on("submit",function(e){
		guardaryeditar(e);
	})

	$("#formEditArticulos").on("submit",function(e){
		actualizarPrecios(e);
	});

   //cargamos los items al select categoria
	$.post("../ajax/articulo.php?op=selectCategoria", function(r){		
			$("#idcategoria").html(r);
			$("#idcategoria").selectpicker('refresh');
	});

   	$.post("../ajax/articulo.php?op=selectMarca", function(r){
		$("#marca").html(r);
		$("#marca").selectpicker('refresh');
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
	$("#imagenmuestra").hide();
	$("#dibujoMuestra").hide();
	$("#registerProduct").hide();
	$("#containerProveedor").hide();
	$("#containerMarca").hide();
	$("#containerCategoria").hide();
}

$('#submit-file').on("click",function(e){
	e.preventDefault();
	$('#files').parse({
		config: {
			delimiter: "auto",
			complete: displayHTMLTable,
		},
		before: function(file, inputElem)
		{
			//console.log("Parsing file...", file);
		},
		error: function(err, file)
		{
			//console.log("ERROR:", err, file);
		},
		complete: function()
		{
			//console.log("Done with all files");
		}
	});
});

function displayHTMLTable(results) {
    let data = results.data;
	console.log("DATA: ", data);
	var table = "<table class='responsive-table table table-hover table-bordered' style='border-radius: 15px;' id='tableArticulosRegister'>";
	table += "<thead class='table-light' style='font-size:12px'>";
	table += "<tr>";
	table += "<th class='bg-info' scope='col' width:50px; style='width:80px;'>Clave</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Fmsi</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Categoria</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Unidad</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Marca</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Proveedor</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Stock</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Pasillo</th>";
	table += "<th id='' class='bg-info' scope='col' style='width:80px;'>Descripción</th>";
	table += "<th id='' class='bg-info' scope='col'>Costo</th>";
	table += "<th id='' class='bg-info' scope='col'>Publico</th>";
	table += "<th id='' class='bg-info' scope='col'>Taller</th>";
	table += "<th id='' class='bg-info' scope='col'>Credito Taller</th>";
	table += "<th id='' class='bg-info' scope='col'>Mayoreo</th>";
	table += "</tr>";
	table += "</thead>";
	table += "<tbody>";	
    for (i = 0; i < data.length; i++) {		
		for (let index = 0; index < data[i].length; index++) {
			let extraccion = data[i][0].split(";");
			if(extraccion[3] != '' && extraccion[3] != undefined) {				
				table += "<tr style='font-size:11px; width:15px;' class='filas' name='fila"+[i]+"' id='fila"+[i]+"'>";
				table += "<td id='claveArt[]' name='claveArt[]' value='"+extraccion[0]+"'>"; table += extraccion[0]; table += "</td>";
				table += "<td id='fmsiArt[]' name='fmsiArt[]' value='"+extraccion[1]+"'>"; table += extraccion[1]; table += "</td>";
				table += "<td id='productoArt[]' name='productoArt[]' value='"+extraccion[2]+"'>"; table += extraccion[2]; table += "</td>"; 
				table += "<td id='unidadArt[]' name='unidadArt[]' value='"+extraccion[3]+"'>"; table += extraccion[3]; table += "</td>";
				table += "<td id='marcaArt[]' name='marcaArt[]' value='"+extraccion[4]+"'>"; table += extraccion[4]; table += "</td>";
				table += "<td id='provArt[]' name='provArt[]' value='"+extraccion[5]+"'>"; table += extraccion[5]; table += "</td>";
				table += "<td id='stockArt[]' name='stockArt[]' value='"+extraccion[6]+"'>"; table += extraccion[6]; table += "</td>";
				table += "<td id='pasilloArt[]' name='pasilloArt[]' value='"+extraccion[7]+"'>"; table += extraccion[7]; table += "</td>";
				table += "<td id='descripcionArt[]' name='descripcionArt[]' value='"+extraccion[8]+"'>"; table += extraccion[8]; table += "</td>";				
				table += "<td id='costoArt[]' name='costoArt[]' value='"+extraccion[9]+"'>"; table += extraccion[9]; table += "</td>";
				table += "<td id='publicoArt[]' name='publicoArt[]' value='"+extraccion[10]+"'>"; table += extraccion[10]; table += "</td>";
				table += "<td id='tallerArt[]' name='tallerArt[]' value='"+extraccion[11]+"'>"; table += extraccion[11]; table += "</td>";
				table += "<td id='creditoArt[]' name='creditoArt[]' value='"+extraccion[12]+"'>"; table += extraccion[12]; table += "</td>";
				table += "<td id='mayoreoArt[]' name='mayoreoArt[]' value='"+extraccion[13]+"'>"; table += extraccion[13]; table += "</td>";
				table += "</tr>";
			}
		}		
    }	
	table += "</tbody>";
    table += "</table>";
    $("#parsed_csv_list").html(table);
}

function actualizarPrecios() {	
	let claves = document.getElementsByName("claveArt[]");
	let costos = document.getElementsByName("costoArt[]");
	let publicos = document.getElementsByName("publicoArt[]");
	let talleres = document.getElementsByName("tallerArt[]");
	let creditos = document.getElementsByName("creditoArt[]");
	let mayoreos = document.getElementsByName("mayoreoArt[]");
	let array = [];
	let arrayCosto = [];
	let arrayPublico = [];
	let arrayTaller = [];
	let arrayCredito = [];
	let arrayMayoreo = [];
	let productosArray = [];
	let productsNotFound = [];
	let jsonArray = [];
	let art = {};

	for (let index = 0; index < claves.length; index++) {
		array.push(claves[index].innerHTML);
		arrayCosto.push(costos[index].innerHTML);
		arrayPublico.push(publicos[index].innerHTML);
		arrayTaller.push(talleres[index].innerHTML);
		arrayCredito.push(creditos[index].innerHTML);
		arrayMayoreo.push(mayoreos[index].innerHTML);
	}
	for (let index = 0; index < array.length; index++) {
		art.clave = array[index]
		art.costo = arrayCosto[index]
		art.publico = arrayPublico[index]
		art.taller = arrayTaller[index]
		art.credito = arrayCredito[index]
		art.mayoreo = arrayMayoreo[index]
		art.fila = index;		
		jsonArray.push({...art})		
	}
	$.ajax({
		url : '../ajax/articulo.php?op=listarArticulos',
		type : 'POST',
		dataType : 'html',
		data: {},
		contentType: false,
		processData: false,
		success: function(resultado){
			resultado = JSON.parse(resultado)
			jsonArray.forEach(element => {				
				let found = resultado.find(res => res == element.clave)			
				if(found != undefined) {
					if(found != '') {
						productosArray.push(element.fila);
					}
				} else {
					if(element != '') {						
						productsNotFound.push(element.fila);
					}
				}
			});
		},		
		complete: function() {
			$.ajax({
				url : '../ajax/articulo.php?op=actualizarPrecios',
				type : 'POST',
				data: {"arrayJson": jsonArray},
				beforeSend: () => {
					swal({
						title: 'Actualizando!',
						html: 'Actualizando precios <b></b> de productos.',
						timer: 2500,
						timerProgressBar: true,
						showConfirmButton: false,
						imageUrl: '../files/images/loader.gif',
					})
				},
				error: () => {
					swal({
						title: 'Error!',
						html: 'Ha surgido un error',
						timer: 1000,					
						showConfirmButton: false,
						type: 'warning',					
					})
				},
				success: (res) => {
					swal({
						position: 'top-end',
						type: 'success',
						title: res,
						showConfirmButton: false,
						timer: 1500
					});
					for (let index = 0; index < productosArray.length; index++) {
						$("#fila"+productosArray[index]).remove();
					}
					$("#msgProducts").html("<h4>"+productsNotFound.length+" Productos no encontrados</h4>");
					$("#containerFile").hide();
					$("#containerUpdatesFiles").hide();
					$("#registerProduct").show();
					$("#containerProveedor").show();
				}
			})
		},
		dataType: 'html'
	});	
}

$('#registerProduct').on("click",function(e){
	e.preventDefault();
	let claves = document.getElementsByName("claveArt[]");
	let categorias = document.getElementsByName("productoArt[]");
	let unidades = document.getElementsByName("unidadArt[]");
	let marcas = document.getElementsByName("marcaArt[]");
	let pasillos = document.getElementsByName("pasilloArt[]");
	let descripciones = document.getElementsByName("descripcionArt[]");
	let fmsis = document.getElementsByName("fmsiArt[]");
	let proveedor = $("#idproveedorProducto").val();
	let costos = document.getElementsByName("costoArt[]");
	let publicos = document.getElementsByName("publicoArt[]");
	let talleres = document.getElementsByName("tallerArt[]");
	let creditos = document.getElementsByName("creditoArt[]");
	let mayoreos = document.getElementsByName("mayoreoArt[]");	
	let array = [],  arrayMarca = [], arrayPasillo = [], arrayDescripcion = [], arrayFmsi = [], arrayCosto = []; arrayPublico = [],
	arrayTaller = [], arrayCredito = [], arrayMayoreo = [], arrayCategoria = [], arrayUnidad = [], jsonArray = [], art = {};


	for (let index = 0; index < claves.length; index++) {
		array.push(claves[index].innerHTML);
		arrayCosto.push(costos[index].innerHTML);
		arrayPublico.push(publicos[index].innerHTML);
		arrayTaller.push(talleres[index].innerHTML);
		arrayCredito.push(creditos[index].innerHTML);
		arrayMayoreo.push(mayoreos[index].innerHTML);
		arrayMarca.push(marcas[index].innerHTML);
		arrayPasillo.push(pasillos[index].innerHTML);
		arrayDescripcion.push(descripciones[index].innerHTML);
		arrayFmsi.push(fmsis[index].innerHTML);
		arrayCategoria.push(categorias[index].innerHTML);
		arrayUnidad.push(unidades[index].innerHTML);
	}
	for (let index = 0; index < array.length; index++) {
		art.clave = array[index];
		art.costo = arrayCosto[index];
		art.publico = arrayPublico[index];
		art.taller = arrayTaller[index];
		art.credito = arrayCredito[index];
		art.mayoreo = arrayMayoreo[index];
		art.marca = arrayMarca[index];
		art.pasillo = arrayPasillo[index];
		art.descripcion = arrayDescripcion[index];
		art.fmsi = arrayFmsi[index];
		art.categoria = arrayCategoria[index];
		art.unidad = arrayUnidad[index];
		art.idproveedor = proveedor;
		if(art.clave != '') {
			jsonArray.push({...art});
		}		
	}

	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);
	
	$.ajax({
		url : '../ajax/articulo.php?op=registrarProductos',
		type : 'POST',
		data: {"arrayJsonProductos": jsonArray, "fecha_ingreso": today},
		beforeSend: () => {
			swal({
				title: 'Registro de productos!',
				html: 'Registrando productos <b></b>.',				
				showConfirmButton: true,
				imageUrl: '../files/images/loader.gif',
			})
		},
		error: (err) => {
			swal({
				title: 'Error!',
				html: err,
				timer: 1000,					
				showConfirmButton: false,
				type: 'warning',
			})
		},
		success: (res) => {
			swal({
				title: "Felicidades!",
				text: res,
				type: 'warning',
				showConfirmButton: true,
			})
			$("#containerFile").show();
			$("#containerUpdatesFiles").show();
			$("#registerProduct").hide();
			$("#msgProducts").hide();
			$("#containerProveedor").hide();
			$("#containerRegisterProducts").hide();
			$("#tableArticulosRegister").remove();
		}
	})

});

function ocultarStock() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide();
		$("#botonStock").show();
		$("#thStock").hide();
  		$('td:nth-child(6)').hide();
	}, 400);	
}


function mostrarStock() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide();
		$("#botonStock").hide();
		$("#thStock").show();
  		$('td:nth-child(6)').show();	
	}, 400);	
}

function ocultarMayoreo() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMayoreo").show();
		$("#thMayoreo").toggle();
  		$('td:nth-child(11)').toggle();
	}, 400);	
}

function mostrarMayoreo() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMayoreo").hide();
		$("#thMayoreo").toggle();
		$('td:nth-child(11)').toggle();
	}, 400);  	
}

function ocultarTaller() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonTaller").show();
		$("#thTaller").toggle();
  		$('td:nth-child(9)').toggle();
	}, 400);	
}

function mostrarTaller() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonTaller").hide();
		$("#thTaller").toggle();
		$('td:nth-child(9)').toggle();
	}, 400);  	
}

function ocultarCredito() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCredito").show();
		$("#thCredito").toggle();
  		$('td:nth-child(10)').toggle();
	}, 400);	
}

function mostrarCredito() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCredito").hide();
		$("#thCredito").toggle();
		$('td:nth-child(10)').toggle();
	}, 400);  	
}

function ocultarPublico() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMostrador").show();
		$("#thPublico").toggle();
  		$('td:nth-child(8)').toggle();
	}, 400);	
}

function mostrarPublico() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMostrador").hide();
		$("#thPublico").toggle();
		$('td:nth-child(8)').toggle();
	}, 400);  	
}

function ocultarCosto() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCosto").show();
		$("#thCosto").toggle();
  		$('td:nth-child(7)').toggle();
	}, 400);	
}

function mostrarCosto() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonCosto").hide();
		$("#thCosto").toggle();
		$('td:nth-child(7)').toggle();
	}, 400);
}

function ocultarDescripcion() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonDescripcion").show();
		$("#thDescripcion").toggle();
  		$('td:nth-child(5)').toggle();
	}, 400);	
}

function mostrarDescripcion() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonDescripcion").hide();
		$("#thDescripcion").toggle();
		$('td:nth-child(5)').toggle();
	}, 400);
}

function ocultarMarca() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMarca").show();
		$("#thMarca").toggle();
  		$('td:nth-child(4)').toggle();
	}, 400);	
}

function mostrarMarca() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonMarca").hide();
		$("#thMarca").toggle();
		$('td:nth-child(4)').toggle();
	}, 400);
}

function ocultarFmsi() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonFmsi").show();
		$("#thFmsi").toggle();
  		$('td:nth-child(2)').toggle();
	}, 400);	
}

function mostrarFmsi() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonFmsi").hide();
		$("#thFmsi").toggle();
		$('td:nth-child(2)').toggle();
	}, 400);
}

function ocultarClave() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonClave").show();
		$("#thClave").toggle();
  		$('td:nth-child(1)').toggle();
	}, 400);	
}

function mostrarClave() {
	$('.loaderSearch').show();
	setTimeout(() => {
		$('.loaderSearch').hide()
		$("#botonClave").hide();
		$("#thClave").toggle();
		$('td:nth-child(1)').toggle();
	}, 400);
}

function copiarCosto() {
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda == "") {
		swal({
			position: 'top-end',
			icon: 'danger',
			title: 'Buscador vacio!',
			showConfirmButton: false,
			timer: 900
		})
	} else {	
		$.ajax({
			url : '../ajax/articulo.php?op=copiarBusqueda',
			type : 'POST',
			dataType : 'html',
			data : { busquedaCopy: busqueda},
		})
		.done(function(resultado){
			swal({
				position: 'top-end',
				icon: 'success',
				title: 'Texto copiado!',
				showConfirmButton: false,
				timer: 900
			})
			resultado = JSON.parse(resultado)
			document.getElementById("comment").value += "*Clave*" + " ---- " + "*Marca*" + " ---- " + "*Costo*" + "\n\n";
			resultado.forEach(element => {
				console.log("ELEMENT: ", element);
				document.getElementById("comment").value += element.codigo + " --> " + element.marca + " --> " +"$" + (Math.ceil(element.costo / 5) * 5) + "\n";
				$("#comment").select();
				document.execCommand('copy');
			});
			$("#comment").val('');

		})
	}
	
}
function copiarPublico() {
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda == "") {
		swal({
			position: 'top-end',
			icon: 'danger',
			title: 'Buscador vacio!',
			showConfirmButton: false,
			timer: 900
		})
	} else {		
		$.ajax({
			url : '../ajax/articulo.php?op=copiarBusqueda',
			type : 'POST',
			dataType : 'html',
			data : { busquedaCopy: busqueda},
		})
		.done(function(resultado){
			swal({
				position: 'top-end',
				icon: 'success',
				title: 'Texto copiado!',
				showConfirmButton: false,
				timer: 900
			})
			resultado = JSON.parse(resultado)

			resultado.forEach(element => {
				console.log("ELEMENT: ", element);
				document.getElementById("comment").value += element.marca + " " + "$" + (Math.ceil(element.publico / 5) * 5) + "\n";
				$("#comment").select();
				document.execCommand('copy');
			});
			$("#comment").val('');

		})
	}
	
}
function copiarTaller() {	
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda == "") {
		swal({
			position: 'top-end',
			icon: 'danger',
			title: 'Buscador vacio!',
			showConfirmButton: false,
			timer: 900
		})
	} else {	
		$.ajax({
			url : '../ajax/articulo.php?op=copiarBusqueda',
			type : 'POST',
			dataType : 'html',
			data : { busquedaCopy: busqueda},
		})
		.done(function(resultado){
			swal({
				position: 'top-end',
				icon: 'success',
				title: 'Texto copiado!',
				showConfirmButton: false,
				timer: 900
			})
			resultado = JSON.parse(resultado)

			resultado.forEach(element => {
				console.log("ELEMENT: ", element);
				document.getElementById("comment").value += element.marca + " " + "$" + (Math.ceil(element.taller / 5) * 5) + "\n";
				$("#comment").select();
				document.execCommand('copy');
			});
			$("#comment").val('');

		})
	}
	
}
function copiarCredito() {	
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda == "") {
		swal({
			position: 'top-end',
			icon: 'danger',
			title: 'Buscador vacio!',
			showConfirmButton: false,
			timer: 900
		})
	} else {	
		$.ajax({
			url : '../ajax/articulo.php?op=copiarBusqueda',
			type : 'POST',
			dataType : 'html',
			data : { busquedaCopy: busqueda},
		})
		.done(function(resultado){
			swal({
				position: 'top-end',
				icon: 'success',
				title: 'Texto copiado!',
				showConfirmButton: false,
				timer: 900
			})
			resultado = JSON.parse(resultado)

			resultado.forEach(element => {
				console.log("ELEMENT: ", element);
				document.getElementById("comment").value += element.marca + " " + "$" + (Math.ceil(element.credito_taller / 5) * 5) + "\n";
				$("#comment").select();
				document.execCommand('copy');
			});
			$("#comment").val('');

		})
	}
	
}
function copiarMayoreo() {	
	let busqueda = document.getElementById("busqueda").value;
	if(busqueda == "") {
		swal({
			position: 'top-end',
			icon: 'danger',
			title: 'Buscador vacio!',
			showConfirmButton: false,
			timer: 900
		})
	} else {	
		$.ajax({
			url : '../ajax/articulo.php?op=copiarBusqueda',
			type : 'POST',
			dataType : 'html',
			data : { busquedaCopy: busqueda},
		})
		.done(function(resultado){
			swal({
				position: 'top-end',
				icon: 'success',
				title: 'Texto copiado!',
				showConfirmButton: false,
				timer: 900
			})
			resultado = JSON.parse(resultado)

			resultado.forEach(element => {
				console.log("ELEMENT: ", element);
				document.getElementById("comment").value += element.marca + " " + "$" + (Math.ceil(element.mayoreo / 5) * 5) + "\n";
				$("#comment").select();
				document.execCommand('copy');
			});
			$("#comment").val('');

		})
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


function obtener_registros(articulos, articulos2){	
	var busqueda = articulos;	
	var articulos2 = "";
	
	let limitesRegistros = $("#limite_registros").val();
	let paginado = $("#pagina").val();		

	var posicionCaracter = articulos != undefined ? articulos.indexOf("/") : articulos;

	if (posicionCaracter > 0) {						
		let extraida = articulos.substring(posicionCaracter - 1, -100).trim();
		let extraida2 = articulos.substring(posicionCaracter + 1, 100).trim();
		articulos2 = extraida2;
		busqueda = extraida;
	}

	console.log("BUSQUEDA 1 : " + articulos);
	console.log("BUSQUEDA 2 : " + articulos2);
	console.log("LIMITE REGISTROS : " + limitesRegistros);
	console.log("PAGINADO : " + paginado);	


	if(articulos == undefined && articulos2 == '' && limitesRegistros == null && paginado == 1) {		
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
		
	if(articulos != undefined && limitesRegistros == null && paginado == 1 && articulos2 == '') {

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

	if(articulos != undefined && limitesRegistros == null && paginado == 1 && articulos2 != '') {

		console.log("Entraste: ", busqueda + " / " + articulos2);

		$("#siguiente").show();
		$('.loaderSearch').show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : { articulos: busqueda, busqueda2 : articulos2},
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

	if(articulos != undefined && limitesRegistros > 0 && paginado == 1 && articulos2 != '') {			
		$('.loaderSearch').show();
		$("#siguiente").show();
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {articulos:articulos, limites: limitesRegistros, busqueda2 : articulos2},
		})
		.done(function(resultado){
			$("#tabla_resultado").html(resultado);
			$('.loaderSearch').hide();
			activarPopover()
		})
	}
	
	if(articulos != undefined && limitesRegistros > 0 && paginado > 1 && articulos2 != '') {			
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

	if(articulos != undefined && limitesRegistros > 0 && paginado > 1 && articulos2 != '') {			
		let total_registros = Number(((limitesRegistros * paginado) - paginado) + 1);		
		let inicio_registros = (total_registros - limitesRegistros) + 1;
	
		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda2 : articulos2, busqueda:articulos,inicio_registros: inicio_registros, total_registros: limitesRegistros},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})

	}

	if(articulos != "" && limitesRegistros == null && paginado > 1 && articulos2 != '') {			
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

	if(articulos != "" && limitesRegistros == null && paginado > 1 && articulos2 != '') {			
		let total_registros = Number(50 * paginado);
		let inicio_registros = (total_registros - 50);

		$.ajax({
			url : '../ajax/articulo.php?op=listar',
			type : 'POST',
			dataType : 'html',
			data : {busqueda:articulos,inicio_registros: inicio_registros, total_registros: 50, busqueda2 : articulos2},
		})
		.done(function(resultado){
			$('.loaderSearch').hide();
			$("#tabla_resultado").html(resultado);
			activarPopover()
		})

	}
}

//Filtro busqueda
/*$(document).on('keyup', '#busqueda', function(){
	
	var valorBusqueda=$(this).val();	
	
	if (valorBusqueda!="")
	{		
		obtener_registros(valorBusqueda);		

	} else {				
		obtener_registros();
	}
});*/

$(document).ready(function() {
    $('form').submit(function(e) {
        e.preventDefault();
        // o return false;
		console.log($("#busqueda").val());
		var valorBusqueda=$("#busqueda").val();			
		var posicionCaracter = valorBusqueda.indexOf("/");

		if (posicionCaracter > 0) {									
			let extraida = valorBusqueda.substring(posicionCaracter + 1, 100).trim();		
			obtener_registros(valorBusqueda, extraida);
		}

		if (valorBusqueda!="")
		{
			obtener_registros(valorBusqueda, "");			
		} else {					
			obtener_registros();
		}
    });
});


/*setInterval(() => {
	let busqueda = document.getElementById("busqueda").value;
	$('.loaderSearch').show();
	setTimeout(() => {
		obtener_registros(busqueda);
		$('.loaderSearch').hide();
	}, 500);
}, 5000);*/

/*========================================================================================== */
/*=============================== FIN FILTROS=============================================== */
/*========================================================================================== */

//funcion limpiar
function limpiar(){
	$("#idarticulo").val("");
	$("#idcategoria").val("");
	$("#codigo").val("");
	$("#stock").val("");
	$("#stock_ideal").val("");
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
	$("#imagenmuestra").attr("src","");
	$("#imagenactual").val("");
	$("#dibujoMuestra").attr("src","");
	$("#dibujoactual").val("");
	$("#utilidadPublico").val("");
	$("#utilidadTaller").val("");
	$("#utilidadCreditoTaller").val("");
	$("#utilidadMayoreo").val("");
}

//funcion mostrar formulario
function mostrarform(flag){
	limpiar();
	if(flag){		
		$("#idcategoria").prop("disabled", false);
		$("#idcategoria").selectpicker('refresh');
		$("#idproveedor").prop("disabled", false);
		$("#idproveedor").selectpicker('refresh');
		$("#bandera_inventariable").prop("disabled", false);
		$("#bandera_inventariable").selectpicker('refresh');
		$("#codigo").prop("disabled", false);
		$("#fmsi").prop("disabled", false);
		$("#marca").prop("disabled", false);
		$("#unidades").prop("disabled", false);
		$("#pasillo").prop("disabled", false);
		$("#nombre").prop("disabled", false);
		$("#stock").prop("disabled", false);
		$("#stock_ideal").prop("disabled", false);
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
	//limpiar();
	//mostrarform(false);
	location.reload();
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
		success: function(respuesta){
			swal({
				position: 'top-end',
				type: 'success',
				title: respuesta,
				showConfirmButton: false,
				timer: 1600
			})
			window.location.href = "articulo.php";
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

	const numberFormat = Intl.NumberFormat('es-MX', {style: 'currency', currency: 'MXN'});

	$.post("../ajax/articulo.php?op=mostrar",{idarticulo : idarticulo},
		function(data,status)
			{						

			data=JSON.parse(data);
			console.log("DATA PRODUCTO: ", data);
			console.log("DESCRIPCION: " + data.descripcionArticulo);
			
			publico = numberFormat.format(data.publico);
			taller = numberFormat.format(data.taller);
			credito = numberFormat.format(data.credito_taller);
			mayoreo = numberFormat.format(data.mayoreo);			
			mostrarform(true);
			$('.loader').hide();
			$("#idcategoria").val(data.idcategoria).prop("disabled", true);
			$("#idcategoria").selectpicker('refresh');
			$("#idproveedor").val(data.idproveedor).prop("disabled", true);
			$("#idproveedor").selectpicker('refresh');			
			$("#marca").val(data.marca).prop("disabled", true);
			$("#marca").selectpicker('refresh');

			$("#bandera_inventariable").val(data.bandera_inventariable).prop("disabled", true);
			$("#bandera_inventariable").selectpicker('refresh');
			$("#codigo").val(data.codigo).prop("disabled", true);
			$("#fmsi").val(data.fmsi).prop("disabled", true);
			//$("#marca").val(data.descripcion).prop("disabled", true);
			$("#unidades").val(data.unidades).prop("disabled", true);
			$("#pasillo").val(data.pasillo).prop("disabled", true);
			$("#nombre").val(data.nombre).prop("disabled", true);
			$("#stock").val(data.stock).prop("disabled", true);
			$("#stock_ideal").val(data.stock_ideal).prop("disabled", true);
			$("#costo").val(data.costo).prop("disabled", true);
			$("#publico").val(publico).prop("disabled", true);
			$("#taller").val(taller).prop("disabled", true);
			$("#credito_taller").val(credito).prop("disabled", true);
			$("#mayoreo").val(mayoreo).prop("disabled", true);
			$("#descripcion").val(data.descripcion).prop("disabled", true);
			$("#barcode").val(data.barcode).prop("disabled", true);
			$("#idarticulo").val(data.idarticulo).prop("disabled", true);
			$("#btnGuardar").hide();
			$("#imagenmuestra").show();
			$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);
			$("#imagenactual").val(data.imagen);		
			$("#dibujoMuestra").show();	
			$("#dibujoMuestra").attr("src","../files/dibujos/"+data.dibujo_tecnico);
			$("#dibujoactual").val(data.dibujo_tecnico);
			var precioCosto = Number(data.costo);
			var precioPublico = Number(data.publico);
			var precioTaller = Number(data.taller);
			var precioCreditoTaller = Number(data.credito_taller);
			var precioMayoreo = Number(data.mayoreo);

			var utilidadPublico = Math.trunc(((precioPublico / precioCosto) * 100) - 100);
			var utilidadTaller = Math.trunc(((precioTaller / precioCosto) * 100) - 100);
			var utilidadCredito = Math.trunc(((precioCreditoTaller / precioCosto) * 100) - 100);
			var utilidadMayoreo = Math.trunc(((precioMayoreo / precioCosto) * 100) - 100);

			$("#utilidadPublico").text(utilidadPublico + "%");
			$("#utilidadTaller").text(utilidadTaller + "%");
			$("#utilidadCreditoTaller").text(utilidadCredito + "%");
			$("#utilidadMayoreo").text(utilidadMayoreo + "%");

			/*$.post("../ajax/articulo.php?op=mostrarMarcaId",{idMarca : data.marca}, function(res) {
				dataMarca = JSON.parse(res);
				console.log("DATA MARCA: " + dataMarca.utilidad_1);
				$("#utilidadPublico").text(dataMarca.utilidad_1 + "%")
				$("#utilidadTaller").text(dataMarca.utilidad_2 + "%")
				$("#utilidadCreditoTaller").text(dataMarca.utilidad_3 + "%")
				$("#utilidadMayoreo").text(dataMarca.utilidad_4 + "%")
			})*/
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
			$("#bandera_inventariable").val(data.bandera_inventariable);
			$("#bandera_inventariable").selectpicker('refresh');
			$("#codigo").val(data.codigo);
			$("#fmsi").val(data.fmsi);
			$("#marca").val(data.marca).prop("disabled", false);
			$("#marca").selectpicker('refresh');
			//$("#marca").val(data.marca);
			$("#unidades").val(data.unidades);
			$("#pasillo").val(data.pasillo);
			$("#nombre").val(data.nombre);
			$("#stock").val(data.stock);
			$("#stock_ideal").val(data.stock_ideal);
			$("#costo").val(data.costo);
			$("#publico").val(data.publico);
			$("#taller").val(data.taller);
			$("#credito_taller").val(data.credito_taller);
			$("#mayoreo").val(data.mayoreo);
			$("#descripcion").val(data.descripcion);
			$("#barcode").val(data.barcode);
			$("#idarticulo").val(data.idarticulo);
			$("#btnGuardar").show();
			$("#imagenmuestra").show();
			$("#dibujoMuestra").show();
			$("#imagenmuestra").attr("src","../files/articulos/"+data.imagen);			
			$("#imagenactual").val(data.imagen);
			$("#dibujoMuestra").attr("src","../files/dibujos/"+data.dibujo_tecnico);
			$("#dibujoactual").val(data.dibujo_tecnico);
			$.post("../ajax/articulo.php?op=mostrarMarcaId",{idMarca : data.marca}, function(res) {
				dataMarca = JSON.parse(res);
				console.log("DATA MARCA: " + dataMarca.utilidad_1);
				$("#utilidadPublico").text(dataMarca.utilidad_1 + "%")
				$("#utilidadTaller").text(dataMarca.utilidad_2 + "%")
				$("#utilidadCreditoTaller").text(dataMarca.utilidad_3 + "%")
				$("#utilidadMayoreo").text(dataMarca.utilidad_4 + "%")
			})
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
		'_self')
}

function mostrarImagen(id) {
	$.post("../ajax/articulo.php?op=mostrarPorId" , {ìdArticulo : id}, function(e){				
		data = JSON.parse(e);
		console.log("IMAGEN DEL PRODUCTO: " + data.imagen);
		$("#modalImagenProducrto").attr("src","../files/articulos/"+data.imagen);
		$("#modalDibujoProducrto").attr("src","../files/dibujos/"+data.dibujo_tecnico);
		$("#claveProductoModal").val(data.codigo);		
	});
}

function ocultarFila(idArticulo) {
	let tr = document.querySelector("#fila_" + idArticulo);
	tr.remove();	
}

init();