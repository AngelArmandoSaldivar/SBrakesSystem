export function mesLetra(numero) {        
    let fechaLetra = "";
    switch (numero) {
        case 1:
            fechaLetra = "Enero";
            break;
        case 2:
            fechaLetra = "Febrero";
            break;
        case 3:
            fechaLetra = "Marzo";
            break;
        case 4:
            fechaLetra = "Abril";
            break;
        case 5:
            fechaLetra = "Mayo";
            break;
        case 6:
            fechaLetra = "Junio";
            break;
        case 7:
            fechaLetra = "Julio";
            break;
        case 8:
            fechaLetra = "Agosto";
            break;
        case 9:
            fechaLetra = "Septiembre";
            break;
        case 10:
            fechaLetra = "Octubre";
            break;
        case 11:
            fechaLetra = "Noviembre";
            break;
        case 12:
            fechaLetra = "Diciembre";
            break;
        default:
            break;
    }
    return fechaLetra;
}