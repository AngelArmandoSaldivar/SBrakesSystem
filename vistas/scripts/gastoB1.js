var tabla;
var now = new Date();
var day =("0"+now.getDate()).slice(-2);
var month=("0"+(now.getMonth()+1)).slice(-2);
const TODAY = now.getFullYear()+"-"+(month)+"-"+(day);
//funcion que se ejecuta al inicio
function init(){	
   mostrarform(false);   
   obtener_registros();

   $("#formulario").on("submit",function(e){
   	guardaryeditar(e);
   });   

   totalGastos();
   $("#total_gasto").val("Hola");

   	var now = new Date();
	var day =("0"+now.getDate()).slice(-2);
	var month=("0"+(now.getMonth()+1)).slice(-2);
	var today=now.getFullYear()+"-"+(month)+"-"+(day);

	$("#fecha_hora").val(today);
	evaluarCaja();
}

function evaluarCaja() {
	$.ajax({
        url : '../ajax/caja.php?op=mostrarCaja',
        type : 'POST',
        data: {"fecha_actual" : TODAY},
        error: () => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,					
                showConfirmButton: false,
                type: 'warning',					
            })
        },
        success: (data) => {
            data=JSON.parse(data); 
			            
            if(data == null) {				
				swal({
					title: 'Ups!',
					text: "La caja aun no ha sido abierta.",
					imageUrl: '../files/images/unlock.gif',
					imageWidth: 300,
					imageHeight: 150,
					showConfirmButton: true,
					imageAlt: 'Custom image',
				});
				setTimeout(() => {
					window.open(
						`caja.php`,
						"_self"
					);
				}, 1500);
			} else if(data.estado == "CERRADO") {
				swal({
					title: 'Ups!',
					text: "La caja aun no ha sido abierta.",
					imageUrl: '../files/images/unlock.gif',
					imageWidth: 300,
					imageHeight: 150,
					showConfirmButton: true,
					imageAlt: 'Custom image',
				});
				setTimeout(() => {
					window.open(
						`caja.php`,
						"_self"
					);
				}, 1500);
			}		
        }
    })
}

function totalGastos() {
	$.post("../ajax/gasto.php?op=totalGasto",
	function(data,status)
	{
		console.log(data);
		var p = $("#total_gasto");
		var c = p.children();
		let total = new Intl.NumberFormat('es-MX').format(data)		
		p.text("Total $" + total);		
	});
}

//funcion limpiar
function limpiar(){
	$("#idgasto").val("");	
	$("#descripcion").val("");
    $("#cantidad").val("");
    $("#total_gasto").val("");
    $("#metodoPago").val("");
    $("#metodoPago").selectpicker('refresh');
    $("#informacionAdicional").val("");
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
function obtener_registros(gastos){
	$('.loader').show();
	$.ajax({
		url : '../ajax/gasto.php?op=listar',
		type : 'POST',
		dataType : 'html',
		data : { gastos: gastos },
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
     	url: "../ajax/gasto.php?op=guardaryeditar",
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

function mostrar(idgasto){
	console.log("ID GASTO: ", idgasto);
	$.post("../ajax/gasto.php?op=mostrar",{idgasto : idgasto},
		function(data,status)
		{
			data=JSON.parse(data);
			console.log(data);
			mostrarform(true);
			$("#descripcion").val(data.descripcion);
            $("#cantidad").val(data.cantidad);
            $("#total_gasto").val(data.total_gasto);            
			$("#metodoPago").val(data.metodo_pago).prop("disabled", false);
			$("#metodoPago").selectpicker('refresh');	
            $("#informacionAdicional").val(data.informacion_adicional);
			$("#idgasto").val(data.idgasto);
		});
}


//funcion para desactivar
function desactivar(idgasto){
	swal({
		title: '¿Está seguro de eliminar es gasto?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  cancelButtonText: 'Cancelar',
		  confirmButtonText: 'Si, eliminar gasto!'
	  }).then(function(result){
	
		if(result.value){
	
			$.post("../ajax/gasto.php?op=desactivar", {idgasto : idgasto}, function(e){
				swal({
					title:'Gasto eliminado!',
					text: 'Se elimino correctamente el gasto.',
					type: 'success',
					showConfirmButton: false,
					timer: 1500
				})
				obtener_registros();
			});	
		}
	})
}

function activar(idgasto){
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
			$.post("../ajax/gasto.php?op=activar" , {idgasto : idgasto}, function(e){
				swal(
				'Gasto activado!',
				'Se activo correctamente el gasto.',
				'success'
				)
				obtener_registros();
			});
		  }
		})
}

init();