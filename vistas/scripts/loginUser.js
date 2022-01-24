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
           if (data!="null")
            {
            	$(location).attr("href","escritorio.php");
            } else {
            	alert("Nombre u contraseña incorrectos");
            }
        });
})
