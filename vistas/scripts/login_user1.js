$("#frmAcceso").on('submit', function(e)
{
	e.preventDefault();
	logina=$("#logina").val();
	clavea=$("#clavea").val();    
    
	$.post("../ajax/usuario.php?op=verificar",
        {"logina":logina, "clavea":clavea},
        function(data)
        {
            console.log("DATA: " + data);
            data = !data ? null : JSON.parse(data);
            console.log("DATA: ", data);
            if (!data) {
                swal({
                    type: "error", 
                    title: 'Oops...',
                    text: 'Usuario y/o contraseña incorrectos!',
                    footer: 'Vuelva a intentarlo.'
                });
                return;
            } else {                               
                if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {                      
                    //Si es aceptada guardamos lo latitud y longitud
                    var lat = pos.coords.latitude;
                    var lon = pos.coords.longitude;
    
                    if(data.claveRol != "admin") {
                        swal({
                            title: `Bienvenido ${data.nombre}!`,
                            text: "",
                            imageUrl: '../files/images/unlock.gif',
                            imageWidth: 300,
                            imageHeight: 150,
                            timer: 1100,
                            timerProgressBar: true,
                            showConfirmButton: false,
                            imageAlt: 'Custom image',
                        });
                                        
                        let distance = calculateDistance(
                            lon,
                            lat,
                            data.lng,
                            data.lat
                        );                     

                        if (distance <= 7) {
                            console.log("DATA: ", data);
                            setTimeout(() => {
                                $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                            }, 1100);
                        } else {
                            console.log("DATA: ", data);                           
                            swal({
                                type: "warning", 
                                title: 'Un momento!',
                                text: 'No hay sucursales cerca!',
                                footer: 'Lamentablemente no se ha encontrado una sucursal cercana a ti, revisa que las coordenadas sean correctas al momento de dar de alta una sucursal o contacta con tu admnistrador.',
                                showConfirmButton: false,
                                timerProgressBar: true,
                                timer: 3000
                            });
                        }
                           
                    } else {                        
                            swal({
                                title: `Bienvenido ${data.nombre}!`,
                                text: "",
                                imageUrl: '../files/images/unlock.gif',
                                imageWidth: 300,
                                imageHeight: 150,
                                timer: 1100,
                                timerProgressBar: true,
                                showConfirmButton: false,
                                imageAlt: 'Custom image',
                            });  
                            setTimeout(() => {
                                $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                            }, 1000);
                    }
                
                }, function(error) {                    
                    if (error.code == 1) {
                        swal({
                            type: "info", 
                            title: 'Espera...',
                            text: 'DEBE ACTIVAR LA UBICACIÓN PARA ACCEDER!',
                            footer: 'Active la ubicación y vuelva a intentarlo.',
                            showConfirmButton: false,
                            timerProgressBar: true,
                            timer: 2000
                        });
                    }                    
                });
            }
        });
        
    })

// Converts numeric degrees to radians
function toRad(Value) {
    return Value * Math.PI / 180;
}

function calculateDistance(lon1, lat1, lon2, lat2) {
    var R = 6371; // km
    var dLat = toRad(lat2-lat1);
    var dLon = toRad(lon2-lon1);
    var lat1 = toRad(lat1);
    var lat2 = toRad(lat2);

    var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.sin(dLon/2) * Math.sin(dLon/2) * Math.cos(lat1) * Math.cos(lat2); 
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = R * c;
    return d;
}

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
