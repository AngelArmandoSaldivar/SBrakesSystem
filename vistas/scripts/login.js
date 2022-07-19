$("#frmAcceso").on('submit', function(e)
{
	e.preventDefault();
	logina=$("#logina").val();
	clavea=$("#clavea").val();
    console.log(logina);

	$.post("../ajax/usuario.php?op=verificar",
        {"logina":logina, "clavea":clavea},
        function(data)
        {
            data=JSON.parse(data);
            console.log("DATA: ", data);
            if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {    
                //Si es aceptada guardamos lo latitud y longitud
                var lat = pos.coords.latitude;
                var lon = pos.coords.longitude;

                if(data.acceso != "admin") {
                    if(data.lat == lat && data.lng == lon) {
                        $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                    } else {
                        alert("NO TE ENCUENTRAS EN LA UBICACIÓN CORRECTA, POR FAVOR TRASLADATE A LA UBICACIÓN CORRECTA DE TU SUCURSAL ASIGNADA.");
                        $(location).attr("href","login.html");
                    }
                } else {
                    $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                }
            
            }, function(error) {                           
                alert("DEBE ACTIVAR LA UBICACIÓN PARA ACCEDER.");
            });;
        });
})

function sucursalSeleccionada(idsucursal) {
    $.post("../ajax/usuario.php?op=ingresarSucursal",
        {"idsucursal":idsucursal},
        function(data)
        {
            console.log(data);
            window.open(
                `escritorio.php`,
                "_self"
            );	

        })
}
