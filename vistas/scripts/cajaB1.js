var tabla;
//funcion que se ejecuta al inicio
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
	$("#fecha_salida_traspaso").val(TODAY).prop("disabled", false);
}

function montoInicial() {
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
            data=JSON.parse(data);
            if(data.estado == 'CERRADO')
            $("#fecha_apertura_caja").val(TODAY);
            $("#txtMontoInicial").val(data.monto_final);
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
            if(data == null)            
            $("#cerrarCaja").hide();
            $("#contenedor_entradas").hide();
            $("#contenedor_salidas").hide();
            $("#contenedor_depositos").hide();
            $("#contenedor_totales").hide();
            $("#movimientos_caja").hide();

            switch (data.estado) {
                case 'ABIERTO':
                    $("#abrirCaja").hide();
                    $("#inicio_caja").hide();
                    $("#movimientos_caja").show();
                    $("#contenedor_entradas").show();
                    $("#contenedor_salidas").show();
                    $("#contenedor_depositos").show();
                    $("#contenedor_totales").show();
                    break;

                case 'CERRADO':                    
                    $("#cerrarCaja").hide();
                    break;
            
                default:
                    break;
            }
        }
    })
}

$("#btnAperturaCaja").on("click", () => {

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
                        $("#cerrarCaja").show();
                        $("#contenedor_entradas").show();
                        $("#contenedor_salidas").show();
                        $("#contenedor_depositos").show();
                        $("#contenedor_totales").show();
                        $("#movimientos_caja").show();
                        $("#inicio_caja").hide();
                        $("#abrirCaja").hide();
                        
                        $("#modal-abrir-caja").modal('hide');

                        
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
                        $("#cerrarCaja").show();
                        $("#contenedor_entradas").show();
                        $("#contenedor_salidas").show();
                        $("#contenedor_depositos").show();
                        $("#contenedor_totales").show();
                        $("#movimientos_caja").show();
                        $("#inicio_caja").hide();
                        $("#abrirCaja").hide();
                        
                        $("#modal-abrir-caja").modal('hide');

                        
                    }
                })
            }
            
        }
    })

})

$('#cerrarCaja').on("click",function(){
    let saldoFinal = $("#precioFinal").val()
    $("#monto-final").val(saldoFinal).prop("disabled", true);    
    $("#fecha_cierre_caja").val(TODAY)
})

$('#btnTraspasoCaja').on("click", () => {
    let saldoFinal = $("#precioFinal").val();
    $("#saldoCajaChica").val(saldoFinal);        
})

$("#btnCierreCaja").on("click", () => {

    let saldoFinal = $("#precioFinal").val()
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

init();