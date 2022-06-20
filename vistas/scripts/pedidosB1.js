var tabla;

//funcion que se ejecuta al inicio
function init(){
   listar();   
   $("#fecha_inicio").change(listar);
   $("#fecha_fin").change(listar);
}

function listar(){
	var  fecha_inicio = $("#fecha_inicio").val();
 	var fecha_fin = $("#fecha_fin").val();
	 	
		tabla=$('#tbllistado').dataTable({
			"aProcessing": true,//activamos el procedimiento del datatable
			"aServerSide": true,//paginacion y filrado realizados por el server
			dom: 'Bfrtip',//definimos los elementos del control de la tabla
			buttons: [
					  'copyHtml5',
					  'excelHtml5',					  
					  'pdf'
			],
			"ajax":
			{
				url:'../ajax/pedido.php?op=listarPedidos',
				data:{fecha_inicio:fecha_inicio, fecha_fin:fecha_fin},
				type: "get",
				dataType : "json",
				error:function(e){
					console.log(e.responseText);
				}
			},
			"bDestroy":true,
			"iDisplayLength":10,//paginacion
			"order":[[0,"desc"]]//ordenar (columna, orden)
		}).DataTable();
}


function desactivar(idpedido){

	swal({
		title: '¿Está seguro de desactivar el pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, desactivar pedido!'
	  }).then(function(result){
	
		if(result.value){				
				$.post("../ajax/pedido.php?op=anular&" + "idpedido=" + idpedido, function(e){
					swal({
						title:'Pedido desactivado!',
						text: 'Se desactivo correctamente el pedido.',
						type: 'success',
						showConfirmButton: false,
						timer: 1500
					})
				listar()	
			});	
		}
	})
}
function activar(idpedido) {
	swal({
		title: '¿Está seguro de activar el pedido?',
		text: "¡Si no lo está puede cancelar la accíón!",
		type: 'warning',
		showCancelButton: true,
		confirmButtonColor: '#3085d6',
		cancelButtonColor: '#d33',
		cancelButtonText: 'Cancelar',
		confirmButtonText: 'Si, activar pedido!'
	  }).then(function(result){
	
		if(result.value){				
				$.post("../ajax/pedido.php?op=activar&" + "idpedido=" + idpedido, function(e){
					swal({
						title:'Pedido activado!',
						text: 'Se activo correctamente el pedido.',
						type: 'success',
						showConfirmButton: false,
						timer: 1500
					})
				listar()	
			});	
		}
	})
}
init();  