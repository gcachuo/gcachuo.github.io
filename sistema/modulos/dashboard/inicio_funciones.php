<?php

function inicio_menuInicio() {

    $fIni = "01/" . date('m/Y');
    $fechaIni = $fIni . " 00:00:00";
    $fFin = date('d/m/Y');
    $fechaFin = $fFin . ' 23:59:59';
    $fechaActual = $fIni . ' - ' . $fFin;
    $fechaFormtHoy = date('Y-m-d');

    $i = 0;
    $divIni = '<div class="row">';
    $divFin = '</div>';
    $contenidoTablero = '';
    for ($j = 1; $j <= 10; $j++) {
//PERMISOS DEL PERFIL
        liberar_bd();
        $selectPermisosPerfil = 'CALL sp_sistema_select_permisos_tablero_perfil(' . $_SESSION['idPerfil'] . ', ' . $j . ');';
        $permisosPerfil = consulta($selectPermisosPerfil);
        $ctaPermisosPerfil = cuenta_registros($permisosPerfil);
        switch ($ctaPermisosPerfil) {
            case 0:
                switch ($j) {
                    case 1:
                        $contenidoTablero .= $divIni . $divFin;
                        break;
                    case 2:
                        $contenidoTablero .= $divIni . $divFin;
                        break;
                }
                break;
            default:
                $per = siguiente_registro($permisosPerfil);
                $contenidoTablero .= $divIni;
                switch ($j) {
                    case 1:
//LISTA DE ESTADOS
                        liberar_bd();
                        $selectEstados = 'CALL sp_sistema_lista_estados();';
                        $estados = consulta($selectEstados);
                        while ($est = siguiente_registro($estados)) {
                            $optEstados .= '<option ' . $selecEdo . ' value="' . $est["id"] . '">' . utf8_convertir($est["nombre"]) . '</option>';
                        }


                        $contenidoTablero .= '	
<div class="col-sm-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h4>' . utf8_convertir($per["nombre"]) . '</h4>
            <div class="options">   
                <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
            </div>
        </div>
        <div class="panel-body collapse in" style="display: none;">
            <div class="row">
                ' . $listaCuentas . '
            </div>
        </div>
    </div>
</div>';
                        $contenidoTablero .= $divFin;
                        break;
                    case 2:
//COMPROBAMOS RECORDATORIOS
                        liberar_bd();
                        $selectRecordatorios = 'CALL sp_sistema_select_recordatorios();';
                        $recordatorios = consulta($selectRecordatorios);
                        $records = siguiente_registro($recordatorios);
                        foreach ($recordatorios as $record) {
                            liberar_bd();
                            $selectCorreo = '
SELECT valor_correo_agenda correo FROM e11_segurosDev.correo_agenda where id_agenda=(
select id_agenda from agenda_cliente where id_cliente=(
select id_cliente from poliza where id_poliza=(
select id_poliza id from recordatorio where id_recordatorio=' . $record["id"] . ')));';
                            $select = consulta($selectCorreo);
                            $correo = siguiente_registro($select);

                            switch ($record["frecuencia"]) {
                                case "MENSUAL":
                                    $mes = 1;
                                    break;
                                case "BIMESTRAL":
                                    $mes = 2;
                                    break;
                                case "TRIMESTRAL":
                                    $mes = 3;
                                    break;
                                case "CUATRIMESTRAL":
                                    $mes = 4;
                                    break;
                                case "SEMESTRAL":
                                    $mes = 6;
                                    break;
                                case "ANUAL":
                                    $mes = 12;
                                    break;
                                default:
                                    $mes = 0;
                                    break;
                            }
                            $fecha = $record["fechaBase"];

                            $fecha = date('Y-m-d', strtotime("+" . $mes . " months", strtotime($fecha)));

                            $date = date('d/m/Y', strtotime($record["fecha"]));

                            $listaRecordatorios.='<tr>'
                                    . '<td>' . $record["nombre"] . '</td>'
                                    . '<td>' . $record["tipo"] . '</td>'
                                    . '<td style="color:#0000FF; text-decoration: underline;" '
                                    . 'onclick="{document.frmSistema.idPoliza.value = ' . $record["idPoliza"] . ';'
                                    . 'navegar_accion(4001,\'Ver detalles\');}">' . $record["numero"] . '</td>'
                                    . '<td>$' . $record["monto"] . ' (' . $record["frecuencia"] . ')' . '</td>';
                            if (new DateTime() > new DateTime($record["fecha"])) {
                                $listaRecordatorios.=
                                        '<td style="color:#FF0000;" >' . $date . '</td>';
                            } else {
                                $listaRecordatorios.=
                                        '<td>' . $date . '</td>';
                            }
                            $listaRecordatorios.= '<td>' . substr($record["vencimiento"], 0, 10) . '</td>'
                                    . '<td>'
                                    . '<a href="#modalPagar" role="button" class="btn" '
                                    . 'data-toggle="modal" onclick="idRecordatorio(' . $record["id"] . ')">Pagar</a> '
                                    . '<a href="#modalEnviar" role="button" class="btn" '
                                    . 'data-toggle="modal" onclick="setCorreo(' . $record["id"] . ',\'' . $correo["correo"] . '\')">Enviar</a></td>'
                                    . '</tr>';
                        }
                        $contenidoTablero .= '	
<div class="col-sm-12">
    <div class="panel panel-danger">
        <div class="panel-heading">
            <h4>' . utf8_convertir($per["nombre"]) . '</h4>
            <div class="options">   
                <input type="hidden" id="idPoliza" name="idPoliza" value="" />
                <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                <script type="text/javascript">
                    //when the webpage has loaded do this
                    $(document).ready(function () {
                        filtroTabla();
                    });
                </script>
            </div>
        </div>
        <div class="panel-body collapse in" style="display: none;">
            <div class="row">
                <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tablaRecordar">                
                    <thead>
                        <tr>
                            <th style="vertical-align: middle;">Cliente</th>
                            <th style="vertical-align: middle;">Tipo</th>
                            <th style="vertical-align: middle;">Folio</th>
                            <th style="vertical-align: middle;">Monto</th>
                            <th style="text-align: center;">Fecha de Pago</th>
                            <th style="text-align: center;">Vencimiento</th>
                            <th style="vertical-align: middle; text-align: center;">Mostrar Todos</th>
                        </tr>
                        <tr>
                            <th>Cliente</th>
                            <th>Tipo</th>
                            <th>Folio</th>
                            <th>Monto</th>
                            <th>Fecha de Pago</th>
                            <th>Vencimiento</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>                        
                        ' . $listaRecordatorios . '
                    </tbody>                    
                </table>
            </div>
        </div>
    </div>
</div>';
                        $contenidoTablero .= $divFin;
                        break;
                }
                break;
        }
    }

    $pagina = '
<div id="page-heading">
    <h1>Tablero</h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idCliente" name="idCliente" value="" />
        </div>
    </div>
</div>
<div class="container">	
    ' . $contenidoTablero . '			
</div>';
    $listaformas = "";
//SELECT FORMAS DE PAGO
    liberar_bd();
    $selectFormas = 'SELECT id_forma_pago id, nombre_forma_pago nombre FROM forma_pago where estatus_forma_pago = 1;';
    $formas = consulta($selectFormas);
    foreach ($formas as $forma) {
        $listaformas.='<option value="' . $forma["id"] . '">' . $forma["nombre"] . '</option>';
    }


    $pagina .=
             '<div class="modal fade" id="modalPagar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pagar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">            
                            <input type="hidden" class="form-control" id="idRecordatorioPagar" name="idRecordatorioPagar" maxlength="100"/>
                            <div class="form-group">
                                <label for="fechaPago" class="col-sm-4 control-label">Fecha de Pago:</label>
                                <div class="col-sm-8">                                
                                    <input type="text" class="form-control" id="datepicker" name="fechaPago" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="formaPago" class="col-sm-4 control-label">Forma de Pago:</label>
                                <div class="col-sm-8">
                                    <select class="select2" style="width:100% !important" id="formaPago" name="formaPago">
                                        ' . $listaformas . '                                            
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Adjuntar Pago</label>
                                <div class="col-sm-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Adjuntar</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="adjuntoPago" id="adjuntoPago">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" id="btnGuardarPagar" onclick="guardarPagar()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="modalEnviar2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">            
                            <input type="hidden" class="form-control" id="idRecordatorioEnviar" name="idRecordatorioEnviar" maxlength="100"/>
                            <div class="form-group">
                                <label for="correo" class="col-sm-4 control-label">Correo:</label>
                                <div class="col-sm-8">                                
                                    <input type="text" class="form-control" id="correo" name="correo" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-4 control-label">Adjuntar Poliza</label>
                                <div class="col-sm-8">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Adjuntar</span>
                                            <span class="fileinput-exists">Cambiar</span>
                                            <input type="file" name="adjuntoEnviar" id="adjuntoEnviar">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" id="btnEnviar" onclick="enviar(\'EnviarCorreo\')">Enviar</i> 
            </div>
        </div>
    </div>						
</div>
<div id="div_enviar"></div>';
    $pagina .= cargarPagina("correos");
    $pagina .= '
<div class="modal fade" id="modalEnviar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Enviar</h4>
            </div>
            <div class="modal-body">';

    $pagina .= cargarCorreos(343) .
            ' <!--
<div class="form-group" style="text-align: center;">
        <div class="col-sm-1"></div>
        <label class="col-sm-5 control-label" style="text-align: center;">Adjuntar Poliza</label>
        <div class="col-sm-6" style="text-align: left;">
            <div class="fileinput fileinput-new" data-provides="fileinput">
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new">Adjuntar</span>
                    <span class="fileinput-exists">Cambiar</span>
                    <input type="file" name="adjuntarEnvio" id="adjuntarEnvio" onclick="">
                </span>
                <span class="fileinput-filename"></span>
                <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
            </div>
        </div>
    </div>   -->             
</div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" id="btnEnviar" onclick="enviarCorreo(\'EnviarCorreo\')">Enviar</i> 
            </div>
        </div>
    </div>						
</div>';
    return $pagina;
}

function cargarPagina($nombre) {
    ob_start();
    include $nombre . ".php";
    $pagina = ob_get_contents();
    ob_end_clean();
    return $pagina;
}

function select($consulta) {
    liberar_bd();
    $select = consulta($consulta);
    if ($select) {
        $registro = siguiente_registro($select);
    } else {
        return "ERROR AL EJECUTAR";
    }
    return $registro;
}
function previewDoc()
{
    
}
function inicio_enviarCorreo() {

    $src = moverArchivo($_FILES["adjuntoEnviar"], 'envios');

    // Here we get all the information from the fields sent over by the form.
    $idRecordar = $_POST['idRecordatorio'];
    $email = $_POST['correo'];
    $message = "Correo enviado desde PHP";

    $consulta = select('select nombre_cliente nombre from cliente where id_cliente=(select id_cliente from poliza where id_poliza = (select id_poliza id from recordatorio where id_recordatorio = ' . $idRecordar . '));');
    $nombre = $consulta["nombre"];
}

function moverArchivo($nombre, $carpeta) {
    $adjunto = $_FILES[$nombre];
    //GUARDAMOS EL ARCHIVO     
    if ($adjunto["name"] != "") {
        if ($adjunto["error"] == 0) {
            $pos = strrpos($adjunto["name"], '.');
            $ext = substr($adjunto["name"], $pos);
            $src = date("YmdHis") . $ext;
            $ruta = "../" . $carpeta . "/" . $src;
            $mover = move_uploaded_file($adjunto["tmp_name"], $ruta);
            if (!$mover) {
                $error = "ERROR AL MOVER ARCHIVO";
            }
        } else {
            $src = $adjunto["error"];
        }
    } else {
        $src = '';
    }
    return $src;
}

function inicio_guardar() {
    $idRecordatorio = $_POST["idRecordatorioPagar"];
    $fechaPago = normalize_date2($_POST["fechaPago"]);
    $formaPago = $_POST["formaPago"];
    $bandera = 1;
    $values = $idRecordatorio . ',"' . $fechaPago . '",' . $formaPago /* . ',"' . $adjunto . '",' */ . $bandera;

    $src = moverArchivo("adjuntoPago", "pagos");

    liberar_bd();
    $insertPago = '
INSERT INTO pago (id_recordatorio,fecha_pago,id_forma_pago,adjunto_pago) VALUES 
(' . $idRecordatorio . ',"' . $fechaPago . '",' . $formaPago . ',"' . $src . '");';
    $insert = consulta($insertPago);
    if ($insert) {
        liberar_bd();
        $updateFlag = '
UPDATE recordatorio
SET
bandera_recordatorio = ' . $bandera . '
WHERE id_recordatorio = ' . $idRecordatorio . ';';
        $update = consulta($updateFlag);

        if ($update) {
            $error = "CORRECTO";
            $modulo.=inicio_menuInicio();
        } else {
            $error = '<div style="color: #FF000">ERROR AL ACTUALIZAR FLAG </div>' . $updateFlag;
            $modulo.=inicio_menuInicio();
        }
    } else {
        $error = '<div style="color: #FF000">ERROR AL INSERTAR PAGO </div>' . $insertPago;
        $modulo.=inicio_menuInicio();
    }
    return $error . $modulo;
}
