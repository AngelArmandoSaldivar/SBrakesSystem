import { mesLetra } from "./otros.js";
var tabla;
var now = new Date();
var day =("0"+now.getDate()).slice(-2);
var month=("0"+(now.getMonth()+1)).slice(-2);
const TODAY = now.getFullYear()+"-"+(month)+"-"+(day);

function init(){
	mostrarCheques();
    mostrarEfectivos();
    mostrarGastos();
    mostrarTotales();
    mostrarTraspasos();
    abrirCerrarCaja();
    montoInicial();
    conciliacion();
    saldoInicialConciliacion();
	$("#fecha_salida_traspaso").val(TODAY).prop("disabled", false);
    $("#fecha_conciliacion").val(`${mesLetra(Number(month))}` + " " + now.getFullYear());
    $("#txtFechaConciliacion").val(TODAY);

    $.post("../ajax/caja.php?op=listarClientes", function(r){
		$("#idcliente").html(r);
		$('#idcliente').selectpicker('refresh');
	});

    listarFolios();
}

/*===================================================
====================FILTROS==========================
===================================================== */

$(document).on('keyup', '#buscadorConciliacion', function(){
	
	var valorBusqueda=$(this).val();	
	
	if (valorBusqueda!="")
	{		
		obtener_registros(valorBusqueda);		

	} else {				
		obtener_registros();
	}
});

function obtener_registros(busqueda){	
	
    $.ajax({
        url : '../ajax/caja.php?op=conciliacion',
        type : 'POST',
        dataType : 'html',
        data : {busqueda:busqueda},
    })
    .done(function(resultado){
        //$('.loaderSearch').hide();
        $("#tableConciliacion").html(resultado);
        //activarPopover()
    })
	
}

function listarFolios() {

    $.post("../ajax/caja.php?op=listarFolios", function(r){        
		$("#idservicio").html(r);
		$('#idservicio').selectpicker('refresh');
        return r;
	});

}

$("#btnGuardarConciliacion").on("click", () => {
    
    let fecha = $("#txtFechaConciliacion").val();
    let cargo = $("#txtCargoConciliacion").val();
    let abono = $("#txtAbonoConciliacion").val();
    let tipoMov = $("#txtTipoMovConciliacion").val();
    let idCliente = $("#idcliente").val();
    let descripicion = $("#txtDescripcionConciliacion").val();
    let observaciones = $("#txtObservacionesConciliacion").val();
    let idservicio = $("#idservicio").val();    

    if(idservicio != null) {
        $.ajax({ 
            url: "../ajax/caja.php?op=listarFoliosValidar", 
            type: "POST",
            data: {servicioid:idservicio},
            error: (err) => {
                swal({
                    title: 'Error!',
                    html: 'Ha surgido un error',
                    timer: 1000,
                    showConfirmButton: false,
                    type: 'warning',
                })
                console.log("Error: ", err);
            },        
            success: (data) => {            
                data = JSON.parse(data)                
                var total_servicio = Number(data.pagado) + Number(abono);                
                if(total_servicio > data.total_servicio && idservicio != null) {
                    swal({
                        title: "Espera!",
                        html: 'Sobrepasas el total del servicio, vuelve a intentarlo',
                        //timer: 1000,
                        showConfirmButton: true,
                        type: 'warning',
                    })
                } else {
                    $.ajax({ 
                        url: "../ajax/caja.php?op=guardarConciliacion", 
                        type: "POST",
                        data: {fecha:fecha, cargo:cargo, abono:abono,tipoMov:tipoMov, idCliente:idCliente, descripicion:descripicion,
                                observaciones:observaciones, idservicio:idservicio},
                        error: (err) => {
                            swal({
                                title: 'Error!',
                                html: 'Ha surgido un error',
                                timer: 1000,
                                showConfirmButton: false,
                                type: 'warning',
                            })
                            console.log("Error: ", err);
                        },        
                        success: (data) => {            
                            swal({
                                title: data,
                                html: 'Correcto!',
                                //timer: 1000,
                                showConfirmButton: true,
                                type: 'success',
                            }).then(res => {
                                setTimeout(() => {
                                    window.open(
                                        `caja.php`,
                                        "_self"
                                    );
                                }, 1500);
                            })
                        }
                    })
                    
                }

            }
        })
    
    } else {                
        $.ajax({ 
            url: "../ajax/caja.php?op=guardarConciliacion", 
            type: "POST",
            data: {fecha:fecha, cargo:cargo, abono:abono,tipoMov:tipoMov, idCliente:idCliente, descripicion:descripicion,
                    observaciones:observaciones, idservicio:idservicio},
            error: (err) => {
                swal({
                    title: 'Error!',
                    html: 'Ha surgido un error',
                    timer: 1000,
                    showConfirmButton: false,
                    type: 'warning',
                })
                console.log("Error: ", err);
            },        
            success: (data) => {            
                swal({
                    title: data,
                    html: 'Correcto!',
                    //timer: 1000,
                    showConfirmButton: true,
                    type: 'success',
                }).then(res => {
                    setTimeout(() => {
                        window.open(
                            `caja.php`,
                            "_self"
                        );
                    }, 1500);
                })
            }
        })
    }
})

$("#btnEditarGuardarConciliacion").on("click", () => {
    let arrayFecha = [];
    let arrayTipoMovimiento = [];
    let arrayCargo = [];
    let arrayAbono = [];
    let arrayColorCargo = [];
    let arrayColorImporte = [];
    let arrayIdPago = [];
    let arrayObservaciones = [];
    let arrayIdServicio = [];
    let arrayMontoViejo = [];
    let conCount = document.getElementsByName("fecha_conciliacion[]").length;    
    for (let index = 0; index < conCount; index++) {
        arrayFecha[index] = document.getElementsByName("fecha_conciliacion[]")[index].value;
        arrayTipoMovimiento[index] = document.getElementsByName("forma_pago_conciliacion[]")[index].value;
        arrayCargo[index] = parseInt(document.getElementsByName("cargo_conciliacion[]")[index].value);
        arrayAbono[index] = parseInt(document.getElementsByName("importe_conciliarion[]")[index].value);
        arrayColorCargo[index] = document.getElementsByName("colorCargo[]")[index].value;
        arrayColorImporte[index] = document.getElementsByName("colorImporte[]")[index].value;
        arrayIdPago[index] = document.getElementsByName("idpago[]")[index].value;
        arrayIdServicio[index] = document.getElementsByName("idservicio[]")[index].value;
        arrayObservaciones[index] = document.getElementsByName("observaciones_conciliacion[]")[index].value;
    }

    var idServiciosUnicos = [...new Set(arrayIdServicio)];
    var idServicios = [];

    idServiciosUnicos.forEach(element => {
        $.ajax({ 
            url: "../ajax/caja.php?op=mostrarServicioMontoPagado", 
            type: "POST",
            data: {idservicio : element},
            error: (err) => {
                swal({
                    title: 'Error!',
                    html: 'Ha surgido un error',
                    timer: 1000,
                    showConfirmButton: false,
                    type: 'warning',
                })
                console.log("Error: ", err);
            },        
            success: (data) => {                
                data = JSON.parse(data);
                //console.log("DATA: ", data);
                //console.log("ID SERVICIOS: ", data.IDSERVICIO, "=", data.PAGADO_VIEJO);
                idServicios.push(data.IDSERVICIO);
                console.log("ID SERVICIOS ARRAY: ", idServicios);
                arrayMontoViejo.push(data.PAGADO_VIEJO);
                if(arrayMontoViejo.length == idServiciosUnicos.length) {
                    guardarEditarConciliacion(arrayMontoViejo, arrayFecha, arrayTipoMovimiento, arrayCargo, arrayAbono, arrayColorCargo, arrayColorImporte, arrayIdPago, arrayObservaciones, idServicios, arrayIdServicio);
                }                 
            }
        })
    });              
})


function guardarEditarConciliacion(arrayMontoViejo, arrayFecha, arrayTipoMovimiento, arrayCargo, arrayAbono, arrayColorCargo, arrayColorImporte, arrayIdPago, arrayObservaciones, idServiciosUnicos, arrayIdServicio) {
    $.ajax({ 
        url: "../ajax/caja.php?op=editarConciliacion", 
        type: "POST",
        data: {id_pago : arrayIdPago, fecha : arrayFecha, tipo_movimiento : arrayTipoMovimiento, cargo : arrayCargo, 
               abono : arrayAbono, colorCargo : arrayColorCargo, colorImporte : arrayColorImporte, 
               observaciones: arrayObservaciones, idServiciosUnicos: idServiciosUnicos, idservicio: arrayIdServicio, montoViejo: arrayMontoViejo},
        error: (err) => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,
                showConfirmButton: false,
                type: 'warning',
            })
            console.log("Error: ", err);
        },        
        success: (data) => {
            swal({
                title: data,
                html: 'Correcto!',
                //timer: 1000,
                showConfirmButton: true,
                type: 'success',
            }).then(res => {
                if(res) {
                    conciliacion();
                }
            })
        }
    })

}

function saldoInicialConciliacion() {

    $.ajax({ 
        url: "../ajax/caja.php?op=saldoMesPasado", 
        type: "POST", 
        data: {}, 
        error: (err) => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,
                showConfirmButton: false,
                type: 'warning',					
            })
            console.log("Error: ", err);
        },
        success: (data) => {               
            console.log("LLEGASTE");
            data = JSON.parse(data)                          
                $("#saldo_inicial_conciliacion").text(data.importe);               
        }
    }) 

}

function saldos() {
    //console.log("SALDO DEL MES PASADO: ", saldoMesPasado);
    $.ajax({ 
        url: "../ajax/caja.php?op=saldoMesPasado", 
        type: "POST", 
        data: {}, 
        error: (err) => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,
                showConfirmButton: false,
                type: 'warning',					
            })
            console.log("Error: ", err);
        },
        success: (dataImporte) => { 
            dataImporte = JSON.parse(dataImporte)            
            $.ajax({ 
                url: "../ajax/caja.php?op=conciliacionSaldos", 
                type: "POST", 
                data: {}, 
                error: (err) => {
                    swal({
                        title: 'Error!',
                        html: 'Ha surgido un error',
                        timer: 1000,
                        showConfirmButton: false,
                        type: 'warning',					
                    })
                    console.log("Error: ", err);
                },
                success: (data) => {
                    data = JSON.parse(data)
                    data.forEach((res, index) => {  
                        //console.log("RESPUESTA: ", res);     
                        var totalSaldos = Number(res.SALDO_TOTAL) + Number(dataImporte.importe);                                
                        $("#saldo"+index).text("$"+totalSaldos);
                    })  
                }
            })
        }
    })
}

function conciliacion() {
    $.ajax({ 
        url: "../ajax/caja.php?op=conciliacion", 
        type: "POST", 
        data: {}, 
        error: (err) => {
            swal({
                title: 'Error!',
                html: 'Ha surgido un error',
                timer: 1000,
                showConfirmButton: false,
                type: 'warning',					
            })
            console.log("Error: ", err);
        },
        success: (data) => {
            try {
                $("#contenedor_conciliacion").html(data);
                saldos();
            } catch (error) {
                console.log("Error: ", error);
            }           
        }
    })    
}

$("#abrirCaja").on("click", ()=> {
    montoInicial();
})

function montoInicial() {

    $.ajax({
        url : '../ajax/caja.php?op=montoInicialHoy',
        type : 'POST',
        data: {fecha:TODAY},
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
            data = data == null ? null : JSON.parse(data);
            if(data != null) {
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
                        data = JSON.parse(data)
                        console.log("DATA HOY==== ", data);
                        $("#fecha_apertura_caja").val(TODAY);                    
                        $("#txtMontoInicial").val(data.monto_inicial);
                        
                        
                    }
                })
                
            } else {
                console.log("DATA MONTO AYER");
                $.ajax({
                    url : '../ajax/caja.php?op=montoInicial',
                    type : 'POST',
                    data: {},
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
                        data = data == null ? null : JSON.parse(data);
                        console.log("DATA: ", data);
                        if(data == null) {
                            $.ajax({
                                url : '../ajax/caja.php?op=ultimoRegistro',
                                type : 'POST',
                                data: {},
                                error: () => {
                                    swal({
                                        title: 'Error!',
                                        html: 'Ha surgido un error',
                                        timer: 1000,
                                        showConfirmButton: false,
                                        type: 'warning',
                                    })
                                },
                                success: (dataUltimoRegistro) => {
                                    dataUltimoRegistro = JSON.parse(dataUltimoRegistro);                                    
                                    console.log("DATA ULTIMO REGISTRO: ", dataUltimoRegistro);
                                    if(dataUltimoRegistro.estado == "ABIERTO") {
                                        swal({
                                            title: 'La caja del dia anterior aun no se ha cerrado',
                                            text: "Cerrar caja!",
                                            icon: 'warning',
                                            type: 'warning',
                                            showCancelButton: true,
                                            confirmButtonColor: '#3085d6',
                                            cancelButtonColor: '#d33',
                                            confirmButtonText: 'Si, cerrar caja'
                                        })
                                        .then((result) => {
                                            if (result.value = true) {
                                                $("#fecha_cierre_caja").val(dataUltimoRegistro.fecha);
                                                $("#monto-final").val(dataUltimoRegistro.monto_final);
                                                $('#modal-abrir-caja').modal('hide');
                                                $('#modal-cerrar-caja').modal('show');                                                
                                            }
                                          })
                                    } else {
                                        $("#txtMontoInicial").val(dataUltimoRegistro.monto_final);
                                        $("#fecha_apertura_caja").val(TODAY);
                                    }
                                }
                            })
                        } else {
                            console.log("SI HAY REGISTRO DEL DIA DE AYER");
                            if(data.estado == "ABIERTO") {                               
                                swal({
                                    title: 'La caja del dia anterior aun no se ha cerrado',
                                    text: "Cerrar caja!",
                                    icon: 'warning',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                    confirmButtonText: 'Si, cerrar caja'
                                })
                                .then((result) => {                                
                                    if (result.value = true) {
                                        $("#fecha_cierre_caja").val(data.fecha);
                                        $("#monto-final").val(data.monto_final);
                                        $('#modal-abrir-caja').modal('hide');
                                        $('#modal-cerrar-caja').modal('show');
                                    }
                                  })
                            } else {
                                $("#txtMontoInicial").val(data.monto_final);
                                $("#fecha_apertura_caja").val(TODAY);
                            }
                        }
                    }
                })
            }        
                      
        }
    })    
}

function abrirCerrarCaja() {    
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
            console.log("LLEGASTE DATA: ", data);
            if(data == null) {
                console.log("Entraste aqui");
                $("#cerrarCaja").hide();
                $("#divEfectivo").hide();
                $("#divVales").hide();
                $("#divDepositos").hide();
                $("#divTotales").hide();                
                $("#divConciliiacion").hide();
                $("#divCajaDos").hide();
                $("#movimientos_caja").hide();
                $("#divInicioCaja").show();
            } else {                  
                console.log(data.estado);     
                switch (data.estado) {                      
                    case 'ABIERTO':                        
                        $("#cerrarCaja").show();
                        $("#divEfectivo").show();
                        $("#divVales").show();
                        $("#divDepositos").show();
                        $("#divTotales").show();
                        $("#movimientos_caja").show();
                        $("#divConciliiacion").show();
                        $("#abrirCaja").hide();        
                        $("#divInicioCaja").hide();  
                        $("#modal-abrir-caja").modal('hide'); 
                        break;

                    case 'CERRADO':
                        console.log("Llegaste cerrados");
                        $("#divInicioCaja").show();
                        $("#divEfectivo").hide();
                        $("#divVales").hide();
                        $("#divDepositos").hide();
                        $("#divTotales").hide();
                        $("#cerrarCaja").hide();
                        $("#divConciliiacion").hide();                        
                        $("#divCajaDos").hide();
                        $("#movimientos_caja").hide();
                        break;                
                    default:
                        break;
                }
            }
        }
    })
}

$("#btnAperturaCaja").on("click", () => {

    console.log("LLEGASTE APERTURA CAJA");

    $.ajax({
        url : '../ajax/caja.php?op=evaluarCaja',
        type : 'POST',
        data: {"fecha": TODAY},
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
            console.log("DATA: ", data);   

            if (data == null) {
                let txtMontoInicial = $("#txtMontoInicial").val();
                let fecha_apertura_caja = $("#fecha_apertura_caja").val();
                let detalle_apertura_caja = $("#detalle_apertura_caja").val();
                $.ajax({
                    url : '../ajax/caja.php?op=abrirCaja',
                    type : 'POST',
                    data: {"fecha_apertura" : fecha_apertura_caja, "montoInicial" : txtMontoInicial, "detalle_apertura" : detalle_apertura_caja},
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
                        
                        swal({
                            title: 'Exito!',
                            text: data,
                            imageUrl: '../files/images/unlock.gif',
                            imageWidth: 300,
                            imageHeight: 150,
                            imageAlt: 'Custom image',
                        })
                        abrirCerrarCaja();                    
                    }
                })
            } else {
                
                let fecha_apertura_caja = $("#fecha_apertura_caja").val();
                $.ajax({
                    url : '../ajax/caja.php?op=abrirCajaEdit',
                    type : 'POST',
                    data: {"fecha_apertura" : fecha_apertura_caja},
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
                        
                        swal({
                            title: 'Exito!',
                            text: data,
                            imageUrl: '../files/images/unlock.gif',
                            imageWidth: 300,
                            imageHeight: 150,
                            imageAlt: 'Custom image',
                        })
                        abrirCerrarCaja();                        
                    }
                })
            }
            
        }
    })

})

$('#cerrarCaja').on("click",function(){
    let saldoFinal = $("#precioFinal").val()    
    saldoFinal = saldoFinal.replace(",", "")
    $("#monto-final").val(parseInt(saldoFinal)).prop("disabled", false);    
    $("#fecha_cierre_caja").val(TODAY)
})

$('#btnTraspasoCaja').on("click", () => {
    let saldoFinal = $("#precioFinal").val();
    $("#saldoCajaChica").val(saldoFinal);        
})

$("#btnCierreCaja").on("click", () => {

    let saldoFinal = $("#monto-final").val();       
    let fecha_cierre_caja = $("#fecha_cierre_caja").val();
    
    $.ajax({
        url : '../ajax/caja.php?op=cierreCaja',
        type : 'POST',
        data: {"fecha_cierre": fecha_cierre_caja, "montoFinal" : saldoFinal},
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
            
            swal({
                title: 'Exito!',
                text: data,
                imageUrl: '../files/images/unlock.gif',
                imageWidth: 300,
                imageHeight: 150,
                showConfirmButton: true,
                imageAlt: 'Custom image',
            });
            window.open(
                `caja.php`,
                "_self"
            );
        }
    })
})

$('#btnGuardarTraspaso').on("click",function(){

  let monto = document.getElementById("txtMonto").value;
  let fecha = document.getElementById("fecha_salida_traspaso").value;
  let detalle = document.getElementById("detalle_traspaso").value;

  $.ajax({
    url : '../ajax/caja.php?op=registroTraspaso',
    type : 'POST',
    data: {"montoTraspaso" : monto, "fechaTraspaso" : fecha, "detalleTraspaso" : detalle},
    beforeSend: () => {
        swal({
            title: 'Realizando traspaso!',
            html: 'Traspaso <b></b> correctamente.',
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
        $("#modal-traspasos").modal('hide');   
        window.open(
            `caja.php`,
            "_self"
        );     
    }
})
  
})

$("#btnGuardarGasto").on("click", () => {
    let txtdescripcionGasto = $("#txtdescripcionGasto").val();
    let txtCantidad = $("#txtCantidadGasto").val();
    let txtMontoGasto = $("#txtMontoGasto").val();
    let metodoPagoGasto = $("#metodoPagoGasto").val();
    let fecha_gasto = $("#fecha_gasto").val();
    let informacionAdicionalGasto = $("#informacionAdicionalGasto").val();

    $.ajax({
        url : '../ajax/caja.php?op=guardarGasto',
        type : 'POST',
        data : {"txtdescripcionGasto" : txtdescripcionGasto, "txtCantidad" : txtCantidad, "txtMontoGasto" : txtMontoGasto,
        "metodoPagoGasto" : metodoPagoGasto, "fecha_gasto" : fecha_gasto, "informacionAdicionalGasto" : informacionAdicionalGasto},
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
            window.open(
                `caja.php`,
                "_self"
            );
        }
    })

})

function mostrarCheques() {
    $.ajax({
        url : '../ajax/caja.php?op=mostrarCheques&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#tabla_cheques").html("<h3>"+res+"</h3>");           
        }
    })
}

function mostrarEfectivos() {
    $.ajax({
        url : '../ajax/caja.php?op=mostrarEfectivos&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#tabla_efectivos").html("<h3>"+res+"</h3>");           
        }
    })
}

function mostrarGastos() {
    $.ajax({
        url : '../ajax/caja.php?op=mostrarGastos&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#tabla_vales_recibos").html("<h3>"+res+"</h3>");
        }
    })
}

function mostrarTraspasos() {
    $.ajax({
        url : '../ajax/caja.php?op=mostrarTraspasos&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#caja_uno").html("<h3>"+res+"</h3>");
        }
    })
}

function mostrarTotales() {
    $.ajax({
        url : '../ajax/caja.php?op=mostrarTotales&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#totales_generales").html("<h3>"+res+"</h3>");           
        }
    })
}

function conciliacionBancaria() {
    $.ajax({
        url : '../ajax/caja.php?op=conciliacionBancaria&fecha_actual='+TODAY,
        type : 'POST',
        data: {},
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
            $("#totales_generales").html("<h3>"+res+"</h3>");           
        }
    })
}

init();