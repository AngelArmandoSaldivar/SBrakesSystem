var tabla;

//funcion que se ejecuta al inicio
function init(){

   listar();
   $("#fecha_inicio").change(listar);   
}

//funcion listar
function listar(){
var  fecha_inicio = $("#fecha_inicio").val(); 

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
			url:'../ajax/consultas.php?op=ordenCompra',
			data:{fecha_inicio:fecha_inicio},
			type: "POST",
			dataType : "json",
			error:function(e){
				console.log(e.responseText);
			}
		},
		"bDestroy":true,
		"iDisplayLength":20,//paginacion
		"order":[[0,"desc"]]//ordenar (columna, orden)
	}).DataTable();
}


init();