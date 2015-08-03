// JavaScript Document
//VARIABLES GLOBALES


//Variable para insertar codigo html en span campos
var formulario = document.getElementById('campos');

function navegar_modulo(destino)
{
    document.frmSistema.modulo.value = destino;
    document.frmSistema.submit();
}

function navegar(destino)
{
    document.frmSistema.accion.value = destino;
    document.frmSistema.submit();
}

function navegar_accion(destino, accion)
{
    document.frmSistema.modulo.value = destino;
    document.frmSistema.accion.value = accion;
    document.frmSistema.submit();
}

function navegarPunto(destino)
{
    document.frmSistemaPunto.accion.value = destino;
    document.frmSistemaPunto.submit();
}
function enviarCorreo(opcion)
{

    debugger;
    if ($("input[name^=correos]:checked").length <= 0)
        alert("Seleccione al menos un correo");
    else
    {
        var correos = $("input:checkbox").serialize();
        var idPoliza = $("#idPoliza").val();
        var idRecordatorio = $("#idRecordatorioEnvio").val();
        var adjuntoEnviar=$("#").val();
        $("#div_enviar").load("../seccion/ajax/enviar_orden.php", {idPoliza: idPoliza, correos: correos, idRecordatorio: idRecordatorio, adjuntoEnviar: adjuntoEnviar},
        function () {
            alert("Orden enviada");
            $("#myModal1").modal("hide");
            $("#idOrdenEnvio").val("");
        });
    }
}
function checar_submodulos(modulo)
{
    var estatus;
    if (modulo.checked)
        estatus = "checked";
    else
        estatus = "";

    var papa = modulo.id.split("_");
    for (var i = 0; i < document.frmSistema.elements.length; i++)
    {
        var elemento = document.frmSistema.elements[i];
        if (elemento.type == "checkbox")
        {
            var hijo = elemento.id.split("_");
            if (hijo[1] == papa[1])
            {
                elemento.checked = estatus;
            }
        }

    }
}

function checa_padre(submodulo)
{
    //modulo_100_101
    var hijo = submodulo.id.split("_");
    var encontrado = 0;

    for (var i = 0; i < document.frmSistema.elements.length; i++)
    {
        var elemento = document.frmSistema.elements[i];
        if (elemento.type == "checkbox")
        {
            //modulo_100 info[0]=modulo info[1]= 100
            //modulo_100_101 info[0]=modulo info[1]= 100  info[2]= 101
            var info = elemento.id.split("_");
            if (info[1] == hijo[1] && info.length == 3 && elemento.checked)
            {
                encontrado++;
            }
        }

    }

    //document.getElementById(modulo_100).checked="checked";
    if (encontrado > 0)
        document.getElementById(hijo[0] + "_" + hijo[1]).checked = "checked";
    else
        document.getElementById(hijo[0] + "_" + hijo[1]).checked = "";

}


function validarLogIn() {
    if (validar(document.frmAcceso.txtUsuario) == true && validar(document.frmAcceso.txtPassword) == true)
    {
        //document.frmAcceso.txtPassword.value = calcMD5(document.frmAcceso.txtPassword.value);
        document.frmAcceso.submit();
    }
    else
    {
        alert("POR FAVOR INGRESA CORRECTAMENTE TUS DATOS DE ACCESOS");
    }
}

function detectaEnter(e)
{
    if (e.keyCode == 13) {
        validarLogIn();
    }
}

function abre_menu(menu) {
    var partes = new Array();
    partes = menu.split("-");

    var subobjs = new Array();
    subobjs = document.getElementById("menu_0_" + partes[1]).getElementsByTagName("a");
    var child;

    for (var j = 0; j < subobjs.length; j++) {
        child = subobjs[j];
        if (child.offsetHeight != 30) {
            if (child.offsetHeight > 0) {
                //new Effect.Morph(child,{duration:0.5,style:"height:0px"});
                child.style.height = "0px";
            } else {
                //new Effect.Morph(child,{duration:0.5,style:"height:25px"});
                child.style.height = "25px";
            }
        }
    }
    document.getElementById("menu_" + partes[1] + "_" + partes[0]).style.textDecoration = "underline";
}

function validaFormularioUsuario(opcion)
{
    if (validar(document.frmSistema.nombre_usuario) == true && validar(document.frmSistema.login_usuario) == true && validar(document.frmSistema.pswd_usuario) == true)
    {
        if (document.frmSistema.pswd_usuario.value == document.frmSistema.pswd_usuario_c.value)
        {
            navegar(opcion);
        }
        else
        {
            alert("Las claves de acceso no coinciden");
        }
    }
    else
    {
        if (document.frmSistema.tipo.value == "editar")
        {
            if (document.frmSistema.pswd_usuario.value == document.frmSistema.pswd_usuario_c.value)
            {
                navegar(opcion);
            }
            else
            {
                alert("Las claves de acceso no coinciden");
            }
        }
        else
        {
            alert("Por favor llene todos los campos");
        }
    }
}

/*-------------alta de cliente--------------*/
function nuevoClientePrueba(opcion)
{
    /*var idSubAgente = document.frmSistema.idSubAgente.value;
     if(idSubAgente != 0)
     {*/
    if (validar(document.frmSistema.nombreCliente) == true)
    {
        if (validar(document.frmSistema.correoCliente) == true)
        {
            if (validar(document.frmSistema.login_usuario) == true && validar(document.frmSistema.pswd_usuario) == true)
            {
                if (document.frmSistema.pswd_usuario.value == document.frmSistema.pswd_usuario_c.value)
                {
                    navegar(opcion);
                }
                else
                    alert("Las claves de acceso no coinciden");
            }
            else
                alert("Por favor llene todos los campos de acceso");
        }
        else
            alert("Capture correo del cliente");
    }
    else
        alert("Capture nombre del cliente");
    /*}
     else
     alert("Seleccione un subagente");*/
}

/*-------------alta de medio de contacto--------------*/

function nuevoTipoContacto(opcion)
{
    /*var idSubAgente = document.frmSistema.idSubAgente.value;
     if(idSubAgente != 0)
     {*/
    if (validar(document.frmSistema.nombreTipo) == true)
    {
        navegar(opcion);
    }
    else
    {
        alert("Capture nombre del medio de contacto");
    }
    /*}
     else
     alert("Seleccione un subagente");*/
}

/*-------------alta de forma de contacto--------------*/

function nuevoMedioContacto(opcion)
{
    if (validar(document.frmSistema.nombreContacto) == true)
    {
        var id_tipo = document.frmSistema.id_tipo.value;
        if (id_tipo != 0)
            navegar(opcion);
        else
            alert("Seleccione una forma de contacto");
    }
    else
        alert("Capture nombre del medio de contacto");
}


function nuevaImagen(opcion)
{
    if (validar(document.frmSistema.nombreImagen) == true)
    {
        var archivo = document.getElementById("adjunto").value;
        if (archivo == '')
        {
            alert('Debe seleccionar un archivo previamente');
            return false;
        }
        else
        {
            if (navigator.userAgent.indexOf('Linux') != -1)
            {
                var SO = "Linux";
            }
            else if ((navigator.userAgent.indexOf('Win') != -1) && (navigator.userAgent.indexOf('95') != -1))
            {
                var SO = "Win";
            }
            else if ((navigator.userAgent.indexOf('Win') != -1) && (navigator.userAgent.indexOf('NT') != -1))
            {
                var SO = "Win";
            }
            else if (navigator.userAgent.indexOf('Win') != -1)
            {
                var SO = "Win";
            }
            else if (navigator.userAgent.indexOf('Mac') != -1)
            {
                var SO = "Mac";
            }
            else
            {
                var SO = "no definido";
            }
            if (SO = "Win")
            {
                var arr_ruta = archivo.split("\\");
            }
            else
            {
                var arr_ruta = archivo.split("/");
            }
            var nombre_archivo = (arr_ruta[arr_ruta.length - 1]);
            var ext_validas = /\.(gif|jpg|png)$/i.test(nombre_archivo);
            if (!ext_validas)
            {
                borrar();
                alert("Archivo con extensión no válida.");
                return false;
            }
            else
            {
                navegar(opcion);
            }
        }
    }
    else
    {
        alert('Capture nombre de la imágen');
    }
}


function borrar()
{
    var vacio = document.getElementById('adjunto').value = "";
}

function guardaDatosEmpresa(opcion)
{
    var archivo = document.getElementById("adjunto").value;
    if (archivo != '')
    {
        if (navigator.userAgent.indexOf('Linux') != -1)
        {
            var SO = "Linux";
        }
        else if ((navigator.userAgent.indexOf('Win') != -1) && (navigator.userAgent.indexOf('95') != -1))
        {
            var SO = "Win";
        }
        else if ((navigator.userAgent.indexOf('Win') != -1) && (navigator.userAgent.indexOf('NT') != -1))
        {
            var SO = "Win";
        }
        else if (navigator.userAgent.indexOf('Win') != -1)
        {
            var SO = "Win";
        }
        else if (navigator.userAgent.indexOf('Mac') != -1)
        {
            var SO = "Mac";
        }
        else
        {
            var SO = "no definido";
        }
        if (SO = "Win")
        {
            var arr_ruta = archivo.split("\\");
        }
        else
        {
            var arr_ruta = archivo.split("/");
        }
        var nombre_archivo = (arr_ruta[arr_ruta.length - 1]);
        var ext_validas = /\.(gif|jpg|png)$/i.test(nombre_archivo);
        if (!ext_validas)
        {
            borrar();
            alert("Archivo con extensión no válida.");
            return false;
        }
    }
    if (validar(document.frmSistema.razonConf) == true)
    {
        if (validar(document.frmSistema.rfcConf) == true)
        {
            if (validar(document.frmSistema.domicilioConf) == true)
            {
                navegar(opcion);
            }
            else
            {
                alert("Por favor capture domicilio de la empresa");
            }
        }
        else
        {
            alert("Por favor capture rfc de la empresa");
        }
    }
    else
    {
        alert("Por favor capture razón de la empresa");
    }
}

function devolucionCompra(opcion)
{
    if (validar(document.frmSistema.motivoDevolver) == true)
    {
        navegar(opcion);
    }
    else
    {
        alert("Capture motivo de devolución");
    }
}



function addCommas(nStr)
{
    nStr += '';
    var x = nStr.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
        x1 = x1.replace(rgx, '$1' + ',' + '$2');
    }
    return x1 + x2;
}

function nuevoTipoPoliza(opcion)
{
    /*var idSubAgente = document.frmSistema.idSubAgente.value;
     if(idSubAgente != 0)
     {*/
    if (validar(document.frmSistema.nombreTipo) == true && validar(document.frmSistema.txtTipo) == true)
        navegar(opcion);
    else
        alert("Capture todos los campos");
    /*}
     else
     alert("Seleccione un subagente");*/
}

function nuevoTipoPago(opcion)
{
    if (validar(document.frmSistema.nombreTipo) == true && validar(document.frmSistema.numTipo) == true && validar(document.frmSistema.txtTipo) == true)
        navegar(opcion);
    else
        alert("Capture todos los campos");
}

function nuevoDocumento(opcion)
{
    var archivo = document.getElementById("adjunto").value;
    if (archivo != '')
    {
        if (validar(document.frmSistema.nombreDoc) == true)
            navegar(opcion);
        else
            alert("Capture nombre del documento");
    }
    else
        alert("Seleccione un archivo");
}

function guardarAsignacion()
{
    var idSubAgente = document.frmSistema.idSubAgente.value;
    if (idSubAgente != 0)
        navegar(opcion);
    else
        alert("Seleccione un subagente para el cliente");
}

function nuevaPoliza(opcion) {
    var isFloatDscto = false;
    var isFloatPrmpg = false;
    var dscto = document.frmSistema.prima.value;
    var prmpg = document.frmSistema.primerPago.value;
    if (/^(\d)+((\.)(\d){1,2})?$/.test(dscto))
        isFloatDscto = true;
    if (/^(\d)+((\.)(\d){1,2})?$/.test(prmpg))
        isFloatPrmpg = true;
    if (isFloatDscto === true) {
        if (isFloatPrmpg === true)
            navegar(opcion);
        else
            alert("El formato del primer pago no es correcto");
    } else {
        alert("El formato de prima anual no es correcto");
    }
}
function cambiarFechaBase()
{
    var value = document.getElementsByName("fechaVigencia")[0].value;
    if (value !== "")
    {
        var value = value.substring(0, 10);
        var year = value.substring(6, 10);
        var month = value.substring(3, 5);
        var day = value.substring(0, 2) - 1;

        value = day + "/" + month + "/" + year;

        document.getElementsByName("fechaInicial")[0].value = value;
    }
}
function guardarPagar()
{
    var value = document.getElementsByName("fechaPago")[0].value;
    if (value !== "")
    {
        navegar('Guardar');
    }
    else
    {
        alert('Eliga Fecha de Pago');
    }

}
function EnviarCorreo(opcion)
{
    navegar(opcion);
}
function idRecordatorio(id)
{
    document.getElementById("idRecordatorioPagar").value = id;

}
function setCorreo(id, correo)
{
    document.getElementById("idRecordatorioEnvio").value = id;
    document.getElementById("correo").value = correo;
}
function parseDate(value)
{
    var year = value.split("/")[2];
    var month = value.split("/")[1];
    var day = value.split("/")[0];

    return value = year + "-" + month + "-" + day;
}
function getToday()
{
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!
    var yyyy = today.getFullYear();

    if (dd < 10) {
        dd = '0' + dd;
    }

    if (mm < 10) {
        mm = '0' + mm;
    }

    today = dd + '/' + mm + '/' + yyyy;
    return today;
}
function addDate(date, type, value)
{
    var day = parseInt(date.split("/")[0]);
    var month = parseInt(date.split("/")[1]);
    var year = parseInt(date.split("/")[2]);
    var date2 = new Date(year, month - 1, day);
    switch (type)
    {
        case "day":
            date2.setDate(date2.getDate() + value);
            break;
        case "month":
            date2.setMonth(date2.getMonth() + value);
            break;
        case "year":
            date2.setYear(date2.getYear() + value);
            break;
    }
    var day = date2.getDate();
    var month = date2.getMonth() + 1;
    var year = date2.getFullYear();

    if (month < 10) {
        month = '0' + month;
    }

    date = day + "/" + month + "/" + year;
    return date;
}
function getPagos()
{
    cambiarFechaBase();
    var monto = document.getElementById("primerPago").value;
    var prima = document.getElementById("prima").value;
    var vigencia = ($('input[name=fechaVigencia]').val().substring(13, 23));
    var inicio = ($('#fechaInicial').val());
    var num = $('#idTipPago').val();
    var mes;
    var i = 2;
    if (prima !== "" && monto !== "")
    {
        switch (num)
        {
            case "1":
                mes = 12;
                break;
            case "2":
                mes = 2;
                break;
            case "3":
                mes = 3;
                break;
            case "4":
                mes = 6;
                break;
            default:
                mes = 0;
                break;
        }
        inicio = addDate(inicio, "month", mes);
        while (new Date(parseDate(vigencia)) > new Date(parseDate(inicio)))
        {
            inicio = addDate(inicio, "month", mes);
            i++;
        }
        var o = 2;
        while (o <= i)
        {
            var calculo = (prima - monto) / (i - 3);
            if (calculo < 0)
            {
                calculo = 0;
            }
            document.getElementById(o + "Pago").value = round(calculo, 2);
            //alert((prima - monto) / (i - 2));
            inicio = addDate(inicio, "month", mes);
            o++;
        }
    }
}
function round(value, decimals) {
    return Number(Math.round(value + 'e' + decimals) + 'e-' + decimals);
}
function mostrarPagos()
{
    var prima = document.getElementById("prima").value;
    if (prima !== "")
    {
        document.getElementById("divPagos").style.visibility = "visible";
    }
    else
    {
        document.getElementById("divPagos").style.visibility = "hidden";
    }
}
function filtroTabla()
{
    $('#tablaRecordar').dataTable().columnFilter(
            {sPlaceHolder: "head:before",
                aoColumns: [
                    {
                        type: "select"
                    },
                    {
                        type: "select"
                    },
                    {
                        type: "text"
                    },
                    {
                        type: "number"
                    },
                    {
                        type: "date-range2"
                    },
                    {
                        type: "date-range"
                    },
                    {
                        type: "button"
                    }
                ]

            }
    );
    $('#tablaClientes').dataTable().columnFilter(
            {
                sPlaceHolder: "head:before",
                aoColumns: [
                    {
                        type: "select"
                    },
                    {
                        type: "select"
                    },
                    {
                        type: "text"
                    },
                    {
                        type: "select"
                    },
                    {
                        type: "select"
                    },
                    {
                        type: "select"
                    }
                ]
            }
    );
    $.datepicker.regional[""].dateFormat = 'dd/mm/yy';
    $.datepicker.setDefaults($.datepicker.regional['']);


}
function generarCampos()
{
    $('#idTipPago').change(function () {
        document.getElementById("primerPago").style.visibility = "visible";
        cambiarFechaBase();
        //get the number of fields required from the dropdown box
        var num = $('#idTipPago').val();
        var vigencia = ($('input[name=fechaVigencia]').val().substring(13, 23));
        var inicio = ($('#fechaInicial').val());

        var mes;

        //2=BIMESTRAL

        switch (num)
        {
            case "1":
                mes = 12;
                break;
            case "2":
                mes = 2;
                break;
            case "3":
                mes = 3;
                break;
            case "4":
                mes = 6;
                break;
            default:
                mes = 0;
                break;
        }
        var i = 1;
        var html = '';
        inicio = addDate(inicio, "month", mes);
        while (new Date(parseDate(vigencia)) > new Date(parseDate(addDate(inicio, "day", 1))))
        {
            html += '\
<div class="row">\n\
                        <div class="col-sm-4"> \n\
                            <div class="form-horizontal"> \n\
                                <div class="form-group"> \n\
                                    <label for="' + (i + 1) + 'Pago" class="col-sm-4 control-label">' + (i + 1) + ' Pago:</label> \n\
                                    <div class="col-sm-8"> \n\
                                        <div class="input-group"> \n\
                                            <span class="input-group-addon">$</span>  \n\
                                            <input readonly type="text" class="form-control" id="' + (i + 1) + 'Pago" name="' + (i + 1) + 'Pago"> \n\
                                        </div> \n\
                                    </div> \n\
                                </div> \n\
                            </div> \n\
                        </div>   \n\
   <div class="col-sm-4">      \n\
      <div class="form-horizontal">         \n\
         <div class="form-group">            \n\
            <label for="fechaPago-' + (i + 1) + '" class="col-sm-4 control-label">\n\
            Fecha del ' + (i + 1) + ' pago: \n\
            </label>\n\
            <div class="col-sm-8">               \n\
               <div class="input-group">\n\
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>\n\
                  <input readonly type="text" class="form-control" id="fechaPago-' + (i + 1) + '" name="fechaPago-' + (i + 1) + '" value="' + inicio + '"/>\n\
               </div>               \n\
            </div>            \n\
         </div>         \n\
      </div>      \n\
   </div>   \n\
</div>';
            //alert(inicio);
            inicio = addDate(inicio, "month", mes);
            i++;
        }
        document.getElementById("cantCampos").value = (i - 1);
        //insert this html code into the div with id catList
        $('#catList').html(html);
    });
}