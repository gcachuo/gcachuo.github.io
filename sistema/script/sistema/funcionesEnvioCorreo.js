function correosEnvioOrden(idProveedor, idOrden)
{
    $("#idOrdenEnvio").val(idOrden);
    $("#myModal1").modal("toggle");
    $("#divModalInfo").html('<div style="height:100px !important; width:100px !important;" id="cargando"></div>');
    $("#divModalInfo").load("../seccion/ajax/correosProveedor.php", {idProveedor: idProveedor});
}

function enviarCorreo(opcion)
{
    if ($("input[name^=correos]:checked").length <= 0)
        alert("Seleccione al menos un correo");
    else
    {
        var correos = $("input:checkbox").serialize();
        var idPoliza = $("#idPoliza").val();
        $("#div_articulos").load("../seccion/ajax/enviar_orden.php", {idPoliza: idPoliza, correos: correos},
        function () {
            alert("Orden enviada");
            $("#myModal1").modal("hide");
            $("#idOrdenEnvio").val("");
        });
    }
}