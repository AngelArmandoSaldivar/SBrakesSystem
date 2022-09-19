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
            if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {    
                //Si es aceptada guardamos lo latitud y longitud
                var lat = pos.coords.latitude;
                var lon = pos.coords.longitude;

                if(data.acceso != "admin") {
                    console.log("LONGITUD: ", lon + -1);
                    console.log("LONGITUD: ", typeof(data.lng));
                    let latidud = parseFloat(data.lng);
                    console.log(typeof(latidud));
                    console.log("SUMA: ", latidud + -1);
                    if(data.lat < lat + 1 && latidud > lon + -1) {
                        $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                    }
                    else {
                        alert("NO TE ENCUENTRAS EN LA UBICACIÓN CORRECTA, POR FAVOR TRASLADATE A LA UBICACIÓN CORRECTA DE TU SUCURSAL ASIGNADA.");
                        $(location).attr("href","login.html");
                    }
                    /*if(data.lat < lat + 1 && data.lng < lon + 1) {                                               
                    } else {                        
                    }*/
                } else {
                    $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                }
            
            }, function(error) {                           
                alert("DEBE ACTIVAR LA UBICACIÓN PARA ACCEDER.");
            });
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
