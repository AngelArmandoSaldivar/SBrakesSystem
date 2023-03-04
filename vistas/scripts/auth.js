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
                    text: 'Usuario o contraseña incorrectos!',
                    footer: 'Vuelva a intentarlo.'
                });
                return;
            } else {                               
                if (navigator.geolocation) navigator.geolocation.getCurrentPosition(function(pos) {                      
                    //Si es aceptada guardamos lo latitud y longitud
                    var lat = pos.coords.latitude;
                    var lon = pos.coords.longitude;
    
                    if(data.acceso != "admin") {
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
                        let latidud = parseFloat(data.lng);                        
                        if(data.lat < lat && latidud > lon) {
                            console.log("DATA: ", data);
                            setTimeout(() => {
                                $(location).attr("href","sucursales.php?idusuario="+data.idusuario);
                            }, 1100);
                        } else {
                            console.log("DATA: ", data);
                            swal({
                                type: "warning", 
                                title: 'Un momento!',
                                text: 'NO TE ENCUENTRAS EN LA UBICACIÓN CORRECTA!',
                                footer: 'Por vavor trasladate a la ubicación correcta de tu sucursal asignada.',
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
                            }, 1100);
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
