if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {    
    var lat = pos.coords.latitude;
    var lon = pos.coords.longitude;

    $.post("../ajax/usuario.php?op=mostrarUsuario",
        {},
        function(data)
        {       
            data=JSON.parse(data);
            let latidud = parseFloat(data.lng);           
            if(data.lat < lat + 1 && latidud > lon + -1) {
                console.log("Te estamos vigilando 7u7...");
                setInterval(() => {
                    if(data.lat < lat + 1 && latidud > lon + -1) {
                        console.log("Te estamos vigilando 7u7...");
                    } else {
                        alert("NO TE ENCUENTRAS EN LA UBICACIÓN CORRECTA, POR FAVOR TRASLADATE A LA UBICACIÓN CORRECTA DE TU SUCURSAL ASIGNADA.");
                        $(location).attr("href","login.html");
                    }
                }, 1000 * 60 * 30);
            }
            else {
                alert("NO TE ENCUENTRAS EN LA UBICACIÓN CORRECTA, POR FAVOR TRASLADATE A LA UBICACIÓN CORRECTA DE TU SUCURSAL ASIGNADA.");
                window.open(
                    `../ajax/usuario.php?op=salir`,
                    "_self"
                );
            }
        })

}, function(error) {      
    console.log("LLegaste");                     
    alert("DEBE ACTIVAR LA UBICACIÓN PARA ACCEDER.");
    window.open(
        `../ajax/usuario.php?op=salir`,
        "_self"
    );
});;

function generarReporte() {
	let fecha_inicio = document.getElementById("fecha_inicio_reporte").value;
	let fecha_final = document.getElementById("fecha_fin_reporte").value;

	window.open(
		`../reportes/exReporteVentas.php?fecha_inicio=${fecha_inicio}&fecha_final=${fecha_final}`,
		'_blank'
	);
}

function generarReporteServicio() {
	let fecha_inicio = document.getElementById("fecha_inicio_reporte_servicio").value;
	let fecha_final = document.getElementById("fecha_fin_reporte_servicio").value;

	window.open(
		`../reportes/exReporteServicios.php?fecha_inicio=${fecha_inicio}&fecha_final=${fecha_final}`,
		'_blank'
	);
}