
<?php

function polizas_menuInicio() {
    $btnEdita = false;
    $btnVer = false;

//PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_convertir($acciones["accion"])) {
            case 'Alta':
                $btnAlta = true;
                break;
            case 'Ver polizas':
                $btnVer = true;
                break;
        }
    }

    $fechaFormt = date('Y-m-d');
    $fechaFormt = $fechaFormt . ' 23:59:59';
//ACTUALIZAMOS LAS POLIZAS YA NO VIGENTES
    liberar_bd();
    $selectPolizasCaducadas = 'CALL sp_sistema_select_polizas_caducadas("' . $fechaFormt . '");';
    $polizasCaducadas = consulta($selectPolizasCaducadas);
    $ctaPolizasCaducadas = cuenta_registros($polizasCaducadas);
    if ($ctaPolizasCaducadas != 0) {
        while ($poliz = siguiente_registro($polizasCaducadas)) {
            liberar_bd();
            $updatePolizaCaducada = 'CALL sp_sistema_update_poliza_caducada(' . $poliz["id"] . ')';
            $polizaCaduca = consulta($updatePolizaCaducada);
        }
    }

    $sumaPesos = 0;
    $sumaDolares = 0;
    $sumaUdis = 0;

    if ($_SESSION["idSubAgenteActual"] != "") {
        liberar_bd();
        $selPol = '	SELECT
clien.id_cliente AS idCliente,
clien.nombre_cliente AS nombre,
Count(poli.id_poliza) AS num,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 1
GROUP BY
poliza.id_cliente
) AS pesos,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 2
GROUP BY
poliza.id_cliente
) AS dolares,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 3
GROUP BY
poliza.id_cliente
) AS udis
FROM
cliente AS clien
INNER JOIN poliza AS poli ON poli.id_cliente = clien.id_cliente
WHERE
poli.id_agente = ' . $_SESSION["idAgenteActual"] . '
AND clien.estatus_cliente <> 0
AND poli.estatus_poliza <> 0
AND poli.estatus_poliza <> 3
AND poli.estatus_poliza <> 5
GROUP BY
clien.id_cliente,
clien.nombre_cliente
ORDER BY
nombre ASC';
    } else {
        liberar_bd();
        $selPol = '	SELECT
clien.id_cliente AS idCliente,
clien.nombre_cliente AS nombre,
Count(poli.id_poliza) AS num,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 1
GROUP BY
poliza.id_cliente
) AS pesos,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 2
GROUP BY
poliza.id_cliente
) AS dolares,
(
SELECT
Sum(poliza.prima_anual_poliza) AS total
FROM
poliza
WHERE
poliza.estatus_poliza <> 0
AND poliza.estatus_poliza <> 3
AND poliza.estatus_poliza <> 5
AND poliza.id_cliente = idCliente
AND poliza.tipo_moneda_poliza = 3
GROUP BY
poliza.id_cliente
) AS udis
FROM
cliente AS clien
INNER JOIN poliza AS poli ON poli.id_cliente = clien.id_cliente
INNER JOIN subagente AS subAgen ON clien.id_subagente = subAgen.id_subagente
INNER JOIN _usuarios AS usuar ON subAgen.id_usuario = usuar.id_usuario
INNER JOIN _perfiles AS perf ON usuar.id_perfil = perf.id_perfil
WHERE
perf.id_agente = ' . $_SESSION['idAgenteActual'] . '
AND clien.estatus_cliente <> 0
AND poli.estatus_poliza <> 0
AND poli.estatus_poliza <> 3
AND poli.estatus_poliza <> 5
GROUP BY
clien.id_cliente,
clien.nombre_cliente
ORDER BY
nombre ASC';
    }

    $selPoli = consulta($selPol);

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Tablero</a></li>    
        <li class="active">
            ' . $_SESSION["moduloPadreActual"] . '
        </li>
    </ol>
    <h1>' . $_SESSION["moduloPadreActual"] . '</h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idClienteActual" name="idClienteActual" value="" />
            <input type="hidden" name="txtIndice" />';
    if ($btnAlta) {
        $pagina.= '	<i title="Nueva poliza" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
                Nueva poliza
            </i>';
    }
    $pagina.= '			</div>
    </div>										
</div>				
<div class="container">											
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>CLIENTE</th>
                                    <th>NUMERO DE POLIZAS</th>
                                    <th>MONTO PESOS</th>
                                    <th>MONTO DOLARES</th>
                                    <th>MONTO UDIS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($poli = siguiente_registro($selPoli)) {
        $sumaPesos = $sumaPesos + $poli["pesos"];
        $sumaDolares = $sumaDolares + $poli["dolares"];
        $sumaUdis = $sumaUdis + $poli["udis"];

        $pagina.= ' <tr>
                                    <td>' . utf8_convertir($poli["nombre"]) . '</td>
                                    <td>' . $poli["num"] . '</td>
                                    <td>' . number_format($poli["pesos"], 2) . '</td>
                                    <td>' . number_format($poli["dolares"], 2) . '</td>
                                    <td>' . number_format($poli["udis"], 2) . '</td>
                                    <td class="tdAcciones">';
        if ($btnVer) {
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idClienteActual.value = ' . $poli["idCliente"] . ';navegar(\'Ver polizas\');">
                                                       <i title="Ver polizas" class="fa fa-eye"></i>
                                        </a>';
        }
        $pagina.= '		</td>										
                                </tr>';
    }

    $pagina.= '								</tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2">TOTALES</td>
                                    <td class="tdNumerico">$ ' . number_format($sumaPesos, 2) . '</td>
                                    <td class="tdNumerico">$ ' . number_format($sumaDolares, 2) . '</td>
                                    <td class="tdNumerico">$ ' . number_format($sumaUdis, 2) . '</td>
                                </tr>
                            </tfoot>												
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_verPolizas() {
    if (isset($_POST["idClienteActual"]))
        $_SESSION["idClienteActual"] = $_POST["idClienteActual"];

//LISTA DE TIPOS DE POLIZAS
    liberar_bd();
    $selectListTipoPoliza = 'CALL sp_sistema_lista_tiposPoliza();';
    $listaTiposPoliza = consulta($selectListTipoPoliza);
    while ($pol = siguiente_registro($listaTiposPoliza)) {
        if ($_POST["idTipoPolizaReporte"] == $pol["id"])
            $selPoli = 'selected="selected"';
        else
            $selPoli = '';
        $polizas.= '<option ' . $selPoli . ' value="' . $pol["id"] . '">' . utf8_convertir($pol["nombre"]) . '</option>';
    }

//LISTA DE TIPOS DE PAGO
    liberar_bd();
    $selectListTiposPago = 'CALL sp_sistema_lista_tiposPago();';
    $listaTiposPago = consulta($selectListTiposPago);
    while ($pag = siguiente_registro($listaTiposPago)) {
        if ($_POST["idTipoPagoReporte"] == $pag["id"])
            $selPag = 'selected="selected"';
        else
            $selPag = '';
        $pagos.= '<option ' . $selPag . ' value="' . $pag["id"] . '">' . utf8_convertir($pag["nombre"]) . '</option>';
    }

    if ($_POST["datepicker"] != '') {
        $fechaInicial = str_replace('/', "-", $_POST["datepicker"]);
        $fechaInicial = normalize_date2($fechaInicial);
        $fechaInicial = $fechaInicial . ' 00:00:00';
        $selectFechaInicial = ' AND poli.fechaInicial_poliza >= "' . $fechaInicial . '" ';
    } else
        $selectFechaInicial = ' ';

    if ($_POST["datepicker3"] != '') {
        $fechaFinal = str_replace('/', "-", $_POST["datepicker3"]);
        $fechaFinal = normalize_date2($fechaFinal);
        $fechaFinal = $fechaFinal . ' 23:59:59';
        $selectFechaFinal = ' AND poli.fechaFin_poliza <= "' . $fechaFinal . '" ';
    } else
        $selectFechaFinal = ' ';

    if ($_POST["idTipoPolizaReporte"] != '')
        $selectPoliza = ' AND poli.id_tipo_poliza = ' . $_POST["idTipoPolizaReporte"];
    else
        $selectPoliza = '';

    if ($_POST["idTipoPagoReporte"] != '')
        $selectPago = ' AND poli.id_tipo_pago = ' . $_POST["idTipoPolizaReporte"];
    else
        $selectPago = '';

    if ($_POST["idEstatus"] != '') {
        switch ($_POST["idEstatus"]) {
            case 1:
                $selectEstatus = ' AND poli.estatus_poliza = 1 ';
                $selectActivas = ' selected="selected"';
                break;
            case 2:
                $selectEstatus = ' AND poli.estatus_poliza = 2 ';
                $selectInactivas = ' selected="selected"';
                break;
            case 3:
                $selectEstatus = ' AND poli.estatus_poliza = 0';
                $selectCanceladas = ' selected="selected"';
                break;
        }
    }

    liberar_bd();
    $selPol = '	
SELECT
poli.id_poliza AS id,
poli.id_cliente AS idcliente,
clien.nombre_cliente AS cliente,
poli.id_tipo_poliza AS idtipopoliza,
poli.prima_anual_poliza AS prima,
DATE_FORMAT(poli.fechainicial_poliza, "%d-%m-%Y") AS fechaIni,
DATE_FORMAT(poli.fechafin_poliza, "%d-%m-%Y") AS fechaFin,
poli.num_poliza AS num,
poli.estatus_poliza AS estatus,
DATE_FORMAT(poli.fecha_poliza, "%d-%m-%Y") AS fecha,
tippol.nombre_tipo_poliza AS tipo,
poli.tipo_moneda_poliza AS moneda,
frec.nombre_frecuencia AS frecuencia
FROM
poliza AS poli
INNER JOIN tipo_poliza AS tippol ON poli.id_tipo_poliza = tippol.id_tipo_poliza
INNER JOIN cliente AS clien ON poli.id_cliente = clien.id_cliente
INNER JOIN frecuencia AS frec ON poli.id_frecuencia = frec.id_frecuencia
WHERE      poli.id_cliente = ' . $_SESSION["idClienteActual"] . ' 
AND        poli.estatus_poliza <> 3 
AND        poli.estatus_poliza <> 5 
'
            . $selectEstatus . ' '
            . $selectFechaInicial . ' '
            . $selectFechaFinal . ' '
            . $selectPoliza . ' '
            . $selectPago .
            '
ORDER BY   fecha';


    $selPoli = consulta($selPol);

    $pagina = '	
<div id="page-heading">
    <h1>Polizas: ' . utf8_convertir($clien["nombre"]) . '</h1>
    <div class="options">
        <div class="btn-toolbar">								
            <input type="hidden" id="idPoliza" name="idPoliza" value="" />
            <input type="hidden" name="txtIndice" />
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Filtrar por</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in" style="display:none;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="idEstatus" class="col-sm-3 control-label">Estatus:</label>
                            <div class="col-sm-6">
                                <select id="idEstatus" name="idEstatus" style="width:100% !important" class="selectSerch">
                                    <option value="">Todas</option>
                                    <option ' . $selectActivas . ' value="1">Activas</option>
                                    <option ' . $selectInactivas . ' value="2">Vencidas</option>
                                    <option ' . $selectCanceladas . ' value="3">Canceladas</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idTipoPolizaReporte" class="col-sm-3 control-label">Tipos de poliza:</label>
                            <div class="col-sm-6">
                                <select id="idTipoPolizaReporte" name="idTipoPolizaReporte" style="width:100% !important" class="selectSerch">
                                    <option value="">Todos</option>
                                    ' . $polizas . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idTipoPagoReporte" class="col-sm-3 control-label">Tipos de pago:</label>
                            <div class="col-sm-6">
                                <select id="idTipoPagoReporte" name="idTipoPagoReporte" style="width:100% !important" class="selectSerch">
                                    <option value="">Todos</option>
                                    ' . $pagos . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="datepicker" class="col-sm-3 control-label">Fecha Inicial:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="datepicker" name="datepicker" value="' . $_POST["datepicker"] . '"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="datepicker3" class="col-sm-3 control-label">Fecha Limite:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="datepicker3" name="datepicker3" value="' . $_POST["datepicker3"] . '"/>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9" style="text-align:right;">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default" onclick="navegar(\'Ver polizas\');">Buscar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>N&Uacute;MERO</th>
                                    <th>CLIENTE</th>
                                    <th>TIPO</th>
                                    <th>PRIMA ANUAL</th>
                                    <th>TIPO DE MONEDA</th>
                                    <th>FRECUENCIA DE PAGO</th>
                                    <th>FECHA INICIAL</th>
                                    <th>FECHA LIMITE</th>
                                    <th>ESTATUS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>
                            <tbody>';
    while ($poli = siguiente_registro($selPoli)) {
        switch ($poli['estatus']) {
            case 1:
                $estatus = '<span class="tdNegro">ACTIVA</span>';
                $btnCanelar = '<i title="Cancelar" style="cursor:pointer;" '
                        . 'onclick="if(confirm(\'Desea cancelar esta póliza\'))'
                        . '{document.frmSistema.idPoliza.value=' . $poli["id"] . ';navegar(\'Cancelar\');}" class="fa fa-times-circle"></i>';
                $btnEditar = '<i title="Editar" style="cursor:pointer;" '
                        . 'onclick="document.frmSistema.idPoliza.value=' . $poli["id"] . ';navegar(\'Editar\');" class="fa fa-pencil"></i>';
                break;
            case 2:
                $estatus = '<span class="tdNaranja">VENCIDA</span>';
                $btnCanelar = '<i title="Cancelar" style="cursor:pointer;" '
                        . 'onclick="if(confirm(\'Desea cancelar esta póliza\'))'
                        . '{document.frmSistema.idPoliza.value=' . $poli["id"] . ';navegar(\'Cancelar\');}" class="fa fa-times-circle"></i>';
                $btnEditar = '<i title="Editar" style="cursor:pointer;" '
                        . 'onclick="document.frmSistema.idPoliza.value=' . $poli["id"] . ';navegar(\'Editar\');" class="fa fa-pencil"></i>';
                break;
            case 0:
                $estatus = '<span class="tdRojo">CANCELADA</span>';
                $btnCanelar = '';
                $btnEditar = '';
                break;
        }

        switch ($poli['moneda']) {
            case 1:
                $moneda = 'Pesos';
                break;
            case 2:
                $moneda = 'D&oacute;lares';
                break;
            case 3:
                $moneda = 'Udis';
                break;
        }


        $pagina.= '
                            <tr>
                                <td>' . $poli["fecha"] . '</td>
                                <td>' . utf8_convertir($poli["num"]) . '</td>
                                <td>' . utf8_convertir($poli["cliente"]) . '</td>
                                <td>' . utf8_convertir($poli["tipo"]) . '</td>
                                <td>' . number_format($poli["prima"], 2) . '</td>
                                <td>' . $moneda . '</td>
                                <td>' . utf8_convertir($poli["frecuencia"]) . '</td>
                                <td>' . $poli["fechaIni"] . '</td>
                                <td>' . $poli["fechaFin"] . '</td>
                                <td>' . $estatus . '</td>
                                <td>
                                    <i title="Ver detalles" style="cursor:pointer;" onclick="document.frmSistema.idPoliza.value = ' . $poli["id"] . ';navegar(\'Ver detalles\');" class="fa fa-eye"></i>
                                    ' . $btnEditar . '
                                    <i title="Historial" style="cursor:pointer;" onclick="document.frmSistema.idPoliza.value = ' . $poli["id"] . ';navegar(\'Historial\');" class="fa fa-list-ul"></i>
                                    <i title="Recordatorios" style="cursor:pointer;" onclick="document.frmSistema.idPoliza.value = ' . $poli["id"] . ';navegar(\'Recordatorios\');" class="fa fa-calendar"></i>
                                    <i title="Archivos" style="cursor:pointer;" onclick="document.frmSistema.idPoliza.value = ' . $poli["id"] . ';navegar(\'Ver archivos\');" class="fa fa-files-o"></i>
                                    ' . $btnCanelar . '	
                                    <i title="Eliminar" style="cursor:pointer;" onclick="if (confirm(\'Desea eliminar esta póliza\'))
                                                        {document.frmSistema.idPoliza.value = ' . $poli["id"] . ';navegar(\'Eliminar\');}" class="fa fa-trash-o"></i>								
                                </td>
                            </tr>
                            ';
    }
    $pagina.= '
                            </tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-success btn" onclick="navegar();">Regresar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_formularioNuevo() {
//LISTA DE TIPOS DE POLIZAS
    liberar_bd();
    $selectListTipoPoliza = 'CALL sp_sistema_lista_tiposPoliza();';
    $listaTiposPoliza = consulta($selectListTipoPoliza);
    while ($pol = siguiente_registro($listaTiposPoliza)) {
        $polizas.= '<option ' . $selPoli . ' value="' . $pol["id"] . '">' . utf8_convertir($pol["nombre"]) . '</option>';
    }

//LISTA DE CLIENTES
    liberar_bd();
    $selectListClientes = 'CALL sp_sistema_select_lista_clientes();';
    $listaClientes = consulta($selectListClientes);
    while ($cli = siguiente_registro($listaClientes)) {
        $clientes.= '<option ' . $selCli . ' value="' . $cli["id"] . '">' . utf8_convertir($cli["nombre"]) . '</option>';
    }

//LISTA DE TIPOS DE PAGO
    liberar_bd();
    $selectListTiposPago = 'CALL sp_sistema_lista_tiposPago();';
    $listaTiposPago = consulta($selectListTiposPago);
    while ($pag = siguiente_registro($listaTiposPago)) {
        $pagos.= '<option ' . $selPag . ' value="' . $pag["id"] . '">' . utf8_convertir($pag["nombre"]) . '</option>';
    }

//LISTA DE FRECUENCIAS
    liberar_bd();
    $selectListFrecuencias = 'CALL sp_sistema_select_lista_frecuencia();';
    $listaFrecuencias = consulta($selectListFrecuencias);
    while ($var = siguiente_registro($listaFrecuencias)) {
        $frecuencias .= '<option ' . $selVar . ' value="' . $var["id"] . '">' . utf8_convertir($var["nombre"]) . '</option>';
    }

    $pagina = '	
<div id="page-heading">
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Tablero</a></li>
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol>
    <h1>' . $_SESSION["moduloHijoActual"] . '</h1>
    <div class="options">
        <div class="btn-toolbar"> 
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading"></div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="idCliente" class=
                                           "col-sm-4 control-label">Cliente:</label>
                                    <div class="col-sm-8">
                                        <select id="idCliente" name="idCliente" style=
                                                "width:100% !important" class="selectSerch">
                                            <option value="0">
                                                Seleccione un cliente
                                            </option>
                                            ' . $clientes . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="idTipPol" class="col-sm-4 control-label">Tipo
                                        de poliza:</label>
                                    <div class="col-sm-8">
                                        <select id="idTipPol" name="idTipPol" style=
                                                "width:100% !important" class="selectSerch">
                                            <option value="0">
                                                Seleccione un tipo de p&oacute;liza                                                
                                            </option>
                                            ' . $polizas . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numPol" class="col-sm-4 control-label">Folio
                                        poliza:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numPol"
                                               name="numPol" maxlength="100" 
                                            value="' . $_POST["numPol"] . '">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="fechaReporte2" class=
                                           "col-sm-4 control-label">Vigencia:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input onfocusout="cambiarFechaBase();" type="text" class="form-control" id=
                                                   "fechaReporte2" name="fechaVigencia" 
                                            value="' . $_POST["fechaVigencia"] . '">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="prima" class="col-sm-4 control-label">Prima anual:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span> 
                                            <input onfocusout="mostrarPagos();" type="text" class="form-control" id="prima" name="prima" 
                                            value="' . $_POST["prima"] . '">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Tipo de moneda:</label>
                                    <div class="col-sm-8">
                                        <label class="radio-inline">
                                            <input type="radio" name="optMoneda" id="optMoneda" value="1" checked>Pesos</label> 
                                        <label class="radio-inline">
                                            <input type="radio" name="optMoneda" id="optMoneda" value="2">D&oacute;lares</label> 
                                        <label class="radio-inline">
                                            <input type="radio" name="optMoneda" id="optMoneda" value="3"> Udis</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>              
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Adjuntar poliza</label>
                                    <div class="col-sm-8">
                                        <div class="fileinput fileinput-new" data-provides=
                                             "fileinput">
                                            <div class="fileinput-preview thumbnail"
                                                 data-trigger="fileinput" style=
                                                 "width: 200px; height: 150px;"></div>
                                            <div>
                                                <span class="btn btn-default btn-file">
                                                    <span class="fileinput-new">Elija archivo</span> 
                                                    <span class="fileinput-exists">Cambiar</span> 
                                                    <input type="file" name="adjunto" id="adjunto"></span> 
                                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="txtPoliza" class=
                                           "col-sm-4 control-label">Observaciones:</label>
                                    <div class="col-md-8">
                                        <textarea class="form-control autosize" name=
                                                  "txtPoliza" id="txtPoliza" 
                                            value="' . $_POST["txtPoliza"] . '">
                                        </textarea>
                                    </div>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <hr>
                    <div id="divPagos" style="visibility: hidden;">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="idTipPago" class=
                                           "col-sm-4 control-label">Frecuencia de pago:</label>
                                    <div class="col-sm-8">
                                        <select id="idTipPago" name="idTipPago" style=
                                                "width:100% !important" class="selectSerch">
                                            <option value="0">
                                                Seleccione una frecuencia
                                            </option>
                                            ' . $frecuencias . '
                                        </select>
                                    </div>
                                    <input id="cantCampos" name="cantCampos" type="hidden"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="primerPago" class="col-sm-4 control-label">Primer Pago:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon">$</span> 
                                            <input oninput="getPagos();" type="text" class="form-control" id="primerPago" name="primerPago">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="fechaInicial" class="col-sm-4 control-label">Fecha del Primer Pago:</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span onclick="cambiarFechaBase();" class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            <input readonly type="text" class="form-control" id="fechaInicial" name="fechaInicial" maxlength="10"
                                                   onclick="cambiarFechaBase();">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script type="text/javascript">
                                                    //when the webpage has loaded do this
                                                    $(document).ready(function() {
                                            generarCampos();
                                            });    </script>                        
                        </div>';
    $pagina.='<div id="catList"></div>
    </div>
                    ';
    $pagina.='
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-success btn" onclick=
                                   "nuevaPoliza(\'Guardar\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
';
    $_SESSION['pagina'] = $pagina;
    return $pagina;
}

function polizas_guardar() {
    if (!isset($_POST["primerPago"])) {
        unlink($ruta);
        $error = 'No se ha podido guardar el recordatorio.';
        $msj = sistema_mensaje("error", $error . $insertRecordatorio . $pago . $prima . $cantCampos);

        $res = $msj . $_SESSION['pagina'];
        $res = $msj . polizas_formularioNuevo() . $error . '<br/>' . $insertPoliza;
    } else {
        $fechaInicial = $_POST["fechaInicial"];
        if ($_POST["fechaVigencia"] != '') {
            $iparr = split(" - ", $_POST["fechaVigencia"]);
            $iparr[0] = str_replace('/', "-", $iparr[0]);
            $iparr[1] = str_replace('/', "-", $iparr[1]);
            $fechaInicial = str_replace('/', "-", $fechaInicial);
            $fechaBase = normalize_date2($fechaInicial);
            $fechaIniFormat = normalize_date2($iparr[0]);
            $fechaFinFormat = normalize_date2($iparr[1]);
            $fechaIni = $fechaIniFormat . ' 00:00:00';
            $fechaFin = $fechaFinFormat . ' 23:59:59';
            switch ($_POST["idTipPago"]) {
                case 1:
                    $mes = 12;
                    break;
                case 2:
                    $mes = 2;
                    break;
                case 3:
                    $mes = 3;
                    break;
                case 4:
                    $mes = 6;
                    break;
                default:
                    $mes = 0;
                    break;
            }
            $fechaAviso = date('Y-m-d', strtotime("+" . $mes . " months", strtotime($fechaBase)));
        } else {
            $fechaIni = '0000-00-00 00:00:00';
            $fechaFin = '0000-00-00 00:00:00';
        }

        //GUARDAMOS EL ARCHIVO
        if ($_FILES["adjunto"]["name"] != "") {
            if ($_FILES["adjunto"]["error"] == 0) {
                $ext = substr($_FILES["adjunto"]["name"], strrpos($_FILES["adjunto"]["name"], '.'));
                $src = date("YmdHis") . '+' . $_POST["numPol"] . $ext;
                $ruta = "../polizas/" . $src;
                move_uploaded_file($_FILES["adjunto"]["tmp_name"], $ruta);
            }
        } else
            $src = '';

        liberar_bd();
        $insertPoliza = " CALL sp_sistema_insert_poliza("
                . $_POST["idTipPol"] . ", "
                . $_POST["idCliente"] . ", "
                . $_POST["prima"] . ", "
                . $_POST["optMoneda"] . ", "
                . $_POST["idTipPago"] . ", '"
                . $fechaIni . "', '"
                . $fechaFin . "','"
                . ($_POST["txtPoliza"]) . "', '"
                . ($_POST["numPol"]) . "','"
                . $fechaBase . "', "
                . $_SESSION[$varIdUsr] . ", "
                . $_SESSION["idAgenteActual"] . ", '"
                . $fechaAviso
                . "');";
        $insert = consulta($insertPoliza);

        if ($insert) {
            $vigencia = date('Y-m-d', strtotime("- " . 1 . " days", strtotime($fechaFinFormat)));
            $selectId = 'SELECT id_poliza from poliza ORDER BY id_poliza DESC LIMIT 1';

            $id = consulta($selectId);
            $id = siguiente_registro($id);

            $fecha = date('Y-m-d', strtotime("- " . 1 . " days", strtotime($fechaIniFormat)));
            $pago = $_POST["primerPago"];
            $prima = $_POST["prima"];
            $cantCampos = $_POST["cantCampos"];

            $insertRecordatorio = 'CALL sp_sistema_insert_recordatorio(' . $id["id_poliza"] . ',"' . $fecha . '",' . $pago . ');';
            $insertR = consulta($insertRecordatorio);
            $fecha = date('Y-m-d', strtotime("+" . $mes . " months", strtotime($fecha)));
            $pago = $_POST["2Pago"];

            while (new DateTime($fecha) < new DateTime($vigencia)) {
                $insertRecordatorio = 'CALL sp_sistema_insert_recordatorio(' . $id["id_poliza"] . ',"' . $fecha . '",' . $pago . ');';
                $insertR = consulta($insertRecordatorio);
                $fecha = date('Y-m-d', strtotime("+" . $mes . " months", strtotime($fecha)));
            }

            if ($insertR) {
                $res = $msj . polizas_menuInicio();
            } else {
                unlink($ruta);
                $error = 'No se ha podido guardar el recordatorio.';
                $msj = sistema_mensaje("error", $error . $insertRecordatorio);

                $res = $msj . $_SESSION['pagina'] . $error . '<br/>' . $insertRecordatorio;
                $res = $msj . polizas_formularioNuevo() . $error . '<br/>' . $insertRecordatorio;
            }
        } else {
            unlink($ruta);
            $error = 'No se ha podido guardar la póliza.';
            $msj = sistema_mensaje("error", $error . $insertPoliza);

            $res = $msj . $_SESSION['pagina'] . $error . '<br/>' . $insertPoliza;
            $res = $msj . polizas_formularioNuevo() . $error . '<br/>' . $insertPoliza;
        }
    }
    return $res;
}

function polizas_detalles() {
//DATOS DE LA POLIZA
    liberar_bd();
    $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_POST["idPoliza"] . ');';
    $datosPoliza = consulta($selectDatosPoliza);

    $poli = siguiente_registro($datosPoliza);
    $fechaInicial = normalize_date($poli["fechaIni"]);
    $fechaFinal = normalize_date($poli["fechaFin"]);
    $fechaInicial = str_replace('-', "/", $fechaInicial);
    $fechaFinal = str_replace('-', "/", $fechaFinal);
    $vigencia = $fechaInicial . ' - ' . $fechaFinal;

    switch ($poli['moneda']) {
        case 1:
            $moneda = 'Pesos';
            break;
        case 2:
            $moneda = 'D&oacute;lares';
            break;
        case 3:
            $moneda = 'Udis';
            break;
    }

//CHECAMOS SI TIENE ARCHIVO PARA DESCARGAR
    if ($poli["archivo"] != '')
        $btnDescarga = '<div class="form-group">
    <label class="col-sm-3 control-label">P&oacute;liza</label>
    <div class="col-sm-6">
        <a target="_blank" href="ajax/polizasDownload.php?file=' . $poli["archivo"] . '" class="btn btn-default">Descargar</a>
    </div>
</div>';
    else
        $btnDescarga = '';

    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de la p&oacute;liza</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                    <div class="form-group">
                            <label for="numPol" class="col-sm-3 control-label">Folio de poliza:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" 
                                id="numPol" name="numPol" maxlength="100" value="' . utf8_convertir($poli["num"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idTipPol" class="col-sm-3 control-label">Tipo de poliza:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="idTipPol" name="idTipPol" maxlength="100" value="' . utf8_convertir($poli["tipo"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idCliente" class="col-sm-3 control-label">Cliente:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="idCliente" name="idCliente" maxlength="100" value="' . utf8_convertir($poli["cliente"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prima" class="col-sm-3 control-label">Prima anual:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly="readonly" type="text" class="form-control" id="prima" name="prima" value="' . number_format($poli["prima"], 2) . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="moneda" class="col-sm-3 control-label">Tipo de moneda:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="moneda" name="moneda" maxlength="100" value="' . $moneda . '"/>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="idTipPago" class="col-sm-3 control-label">Frecuencia de pago:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" 
                                id="idTipPago" name="idTipPago" maxlength="100" value="' . utf8_convertir($poli["frecuencia"]) . '"/>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaIniFin" class="col-sm-3 control-label">Vigencia:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="fechaIniFin" name="fechaIniFin" maxlength="100" value="' . $vigencia . '"/>
                            </div>																													
                        </div>
                        <div class="form-group">
                            <label for="txtPoliza" class="col-sm-3 control-label">Observaciones:</label>
                            <div class="col-md-6">	
                                <textarea readonly="readonly" class="form-control autosize" name="txtPoliza" 
                                id="txtPoliza">' . utf8_convertir($poli["txt"]) . '</textarea>								
                            </div>													
                        </div>	
                        ' . $btnDescarga . '                        								
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-default btn" onclick="navegar(\'Ver polizas\');">Regresar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_cancelar() {
    liberar_bd();
    $deletePoliza = "CALL sp_sistema_cancelar_poliza('" . $_POST["idPoliza"] . "');";
    $delete = consulta($deletePoliza);
    if ($delete) {
        $res = $msj . polizas_verPolizas();
    } else {
        $error = 'No se ha podido cancelar la póliza.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . polizas_verPolizas();
    }

    return $res;
}

function polizas_recordatorios() {
//CLIENTE ACTUAL
    if ($_POST["idPoliza"] != '') {
        $_SESSION["idPolizaActual"] = $_POST["idPoliza"];
//DETALLES DE LA POLIZA
        liberar_bd();
        $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_SESSION["idPolizaActual"] . ');';
        $datosPoliza = consulta($selectDatosPoliza);
        $poli = siguiente_registro($datosPoliza);
        $fechaInicial = normalize_date($poli["fechaIni"]);
        $fechaFinal = normalize_date($poli["fechaFin"]);
        $fechaInicial = str_replace('-', "/", $fechaInicial);
        $fechaFinal = str_replace('-', "/", $fechaFinal);
        $_SESSION["vigenciaPolizaActual"] = $fechaInicial . ' - ' . $fechaFinal;
        $_SESSION["clientePolizaActual"] = utf8_convertir($poli["cliente"]);
    }

    liberar_bd();
    $selectRecordatorios = 'SELECT
recorda.id_recordatorio AS id,
recorda.asunto_recordatorio AS asunto,
recorda.fecha_recordatorio AS fecha,
recorda.fechaCreacion_recordatorio AS fechaRec,
recorda.estatus_recordatorio AS estatus,
recorda.tipo_recordatorio AS tipo
FROM
e11_almanzaseguros.recordatorio AS recorda
WHERE
recorda.id_poliza = ' . $_SESSION["idPolizaActual"] . '
AND recorda.estatus_recordatorio <> 0
ORDER BY
fechaRec DESC';

    /* $paginar = paginar($selectPolizas,200);
      $selectPolizas .= " LIMIT ".$paginar[0].",".$paginar[2]; */
    $recordatorios = consulta($selectRecordatorios);
    $ctaRecordatorios = cuenta_registros($recordatorios);

    $pagina = '	<div id="page-heading">	
    <h1>Recordatorios</h1>
    <div class="options">
        <div class="btn-toolbar">
            <a title="Nuevo recordatorio" onclick="navegar(\'Agregar\')" class="btn btn-warning" >
                <i class="icon-plus-sign"></i>Nuevo recordatorio
            </a>
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_SESSION["idPolizaActual"] . '" />
                   <input type="hidden" id="idRecord" name="idRecord" value="" />
            <input type="hidden" name="txtIndice" />
        </div>
    </div>										
</div>										
<div class="container">	
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>						
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>FECHA PARA RECORDATORIO</th>
                                    <th>ASUNTO</th>
                                    <th>TIPO</th>
                                    <th>ESTATUS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($record = siguiente_registro($recordatorios)) {
        //ESTATUS DEL RECORDATORIO
        switch ($record['estatus']) {
            case 1:
                $estatus = '<span class="tdNegro">ACTIVO</span>';
                break;
            case 2:
                $estatus = '<span class="tdRojo">PAGADO</span>';
                break;
        }

        switch ($record["tipo"]) {
            case 1:
                $tipoEnvio = "Cliente";
                break;
            case 2:
                $tipoEnvio = "Almanza";
                break;
            case 3:
                $tipoEnvio = "Ambos";
                break;
        }

        $pagina.= ' <tr>
                                <td>' . normalize_date2($record["fecha"]) . '</td>
                                <td>' . normalize_date2($record["fechaRec"]) . '</td>
                                <td>' . utf8_convertir($record["asunto"]) . '</td>	
                                <td>' . $tipoEnvio . '</td>
                                <td>' . $estatus . '</td>
                                <td>
                                    <i title="Ver detalles" style="cursor:pointer;" onclick="document.frmSistema.idRecord.value = ' . $record["id"] . ';navegar(\'Ver recordatorio\');" class="fa fa-eye"></i>
                                    <i title="Eliminar" style="cursor:pointer;"  onClick="if (confirm(\'Desea eliminar este recordatorio\')){document.frmSistema.idRecord.value=' . $record["id"] . ';navegar(\'EliminarAgregar\')};" class="fa fa-trash-o"></i>																		
                                </td>											
                            </tr>';
    }

    $pagina.= '								</tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar">
                                <button class="btn-default btn" onclick="navegar(\'Ver polizas\');">Regresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_nuevoRecordatorio() {
    $_SESSION["idPolizaActual"] = $_POST["idPoliza"];
    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">										
        </div>
    </div>										
</div>
<div class="container">
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>														
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Nuevo recordatorio</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">									
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="datepicker" class="col-sm-3 control-label">Fecha de recordatorio:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="datepicker" name="datepicker"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="asunto" class="col-sm-3 control-label">Asunto:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control frmSinFormat" id="asunto" name="asunto" maxlength="100"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="txtRecordatorio" class="col-sm-3 control-label">Mensaje:</label>
                            <div class="col-md-6">	
                                <textarea class="form-control autosize frmSinFormat" name="txtRecordatorio" id="txtRecordatorio"></textarea>											
                            </div>													
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo de env&iacute;o:</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="1" checked>
                                        Cliente
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="2">
                                        Almanza
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="3">
                                        Ambos
                                    </label>
                                </div>
                            </div>
                        </div>																															
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevoRecordatorio(\'GuardarAgregar\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Recordatorios\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_guardarRecordatorio() {
    $fechaRecuerda = str_replace('/', "-", $_POST["datepicker"]);
    $fechaRecuerda = normalize_date2($fechaRecuerda);
    $fechaRecuerda = $fechaRecuerda . ' 00:00:00';

    liberar_bd();
    $insertRecordatorio = " CALL sp_sistema_insert_recordatorio(" . $_SESSION["idPolizaActual"] . ",
" . $_POST['optTipo'] . ",
'" . ($_POST["asunto"]) . "',
'" . $fechaRecuerda . "',
'" . ($_POST["txtRecordatorio"]) . "',
" . $_SESSION['idUsuarioAlmanza'] . ");";
    $insert = consulta($insertRecordatorio);

    if ($insert) {
        $res = $msj . polizas_recordatorios();
    } else {
        switch ($_POST["optTipo"]) {
            case 1:
                $chk1 = "checked";
                break;
            case 2:
                $chk2 = "checked";
                break;
            case 3:
                $chk3 = "checked";
                break;
        }
        $error = 'No se ha podido guardar el recordatorio.';
        $msj = sistema_mensaje("error", $error);

        $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">										
        </div>
    </div>										
</div>
<div class="container">
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>														
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Nuevo recordatorio</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">									
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="datepicker" class="col-sm-3 control-label">Fecha de recordatorio:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="datepicker" name="datepicker" value="' . $_POST["datepicker"] . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="asunto" class="col-sm-3 control-label">Asunto:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control frmSinFormat" id="asunto" name="asunto" maxlength="100" value="' . $_POST["asunto"] . '"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="txtRecordatorio" class="col-sm-3 control-label">Mensaje:</label>
                            <div class="col-md-6">	
                                <textarea class="form-control autosize frmSinFormat" name="txtRecordatorio" id="txtRecordatorio">' . $_POST["txtRecordatorio"] . '</textarea>											
                            </div>													
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo de env&iacute;o:</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="1" ' . $chk1 . '>
                                        Cliente
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="2" ' . $chk2 . '>
                                        Almanza
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optTipo" id="optTipo" value="3" ' . $chk3 . '>
                                        Ambos
                                    </label>
                                </div>
                            </div>
                        </div>																															
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevoRecordatorio(\'GuardarAgregar\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Recordatorios\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

        $res = $msj . $pagina;
    }

    return $res;
}

function polizas_eliminarRecordatorio() {
    liberar_bd();
    $deleteRecordatorio = "CALL sp_sistema_delete_recordatorio('" . $_POST["idRecord"] . "');";
    $delete = consulta($deleteRecordatorio);
    if ($delete) {
        $res = $msj . polizas_recordatorios();
    } else {
        $error = 'No se ha podido eliminar el recordatorio.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . polizas_recordatorios();
    }

    return $res;
}

function polizas_archivos() {
//CLIENTE ACTUAL
    if ($_POST["idPoliza"] != '') {
        $_SESSION["idPolizaActual"] = $_POST["idPoliza"];
//DETALLES DE LA POLIZA
        liberar_bd();
        $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_SESSION["idPolizaActual"] . ');';
        $datosPoliza = consulta($selectDatosPoliza);
        $poli = siguiente_registro($datosPoliza);
        $fechaInicial = normalize_date($poli["fechaIni"]);
        $fechaFinal = normalize_date($poli["fechaFin"]);
        $fechaInicial = str_replace('-', "/", $fechaInicial);
        $fechaFinal = str_replace('-', "/", $fechaFinal);
        $_SESSION["vigenciaPolizaActual"] = $fechaInicial . ' - ' . $fechaFinal;
        $_SESSION["clientePolizaActual"] = utf8_convertir($poli["cliente"]);
    }

    liberar_bd();
    $selectArchivos = '	SELECT
archPoli.id_archivo_poliza AS id,
archPoli.nombre_archivo_poliza AS nombre,
archPoli.url_archivo_poliza AS url
FROM
archivo_poliza AS archPoli
WHERE
archPoli.id_poliza = ' . $_SESSION["idPolizaActual"] . '
AND archPoli.estatus_archivos_poliza <> 0';

    /* $paginar = paginar($selectPolizas,200);
      $selectPolizas .= " LIMIT ".$paginar[0].",".$paginar[2]; */
    $archivos = consulta($selectArchivos);

    $pagina = '	<div id="page-heading">	
    <h1>Archivos</h1>
    <div class="options">
        <div class="btn-toolbar">
            <a title="Nuevo archivo" onclick="navegar(\'NuevoArchivo\')" class="btn btn-warning" >
                <i class="icon-plus-sign"></i>Nuevo archivo
            </a>
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_SESSION["idPolizaActual"] . '" />
                   <input type="hidden" id="idArchivo" name="idArchivo" value="" />
            <input type="hidden" name="txtIndice" />
        </div>
    </div>										
</div>										
<div class="container">	
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>						
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($arch = siguiente_registro($archivos)) {
        if ($arch["url"] != "")
            $btnDowload = '	<a title="Descargar" target="_blank" href="ajax/polizasDownload.php?file=' . $arch["url"] . '">
                                               <i class="fa fa-download"></i>
                            </a>';
        else
            $btnDowload = '<i title="Descargar" style="cursor:pointer;" class="fa fa-ban"></i>';

        $pagina.= ' <tr>
                                <td>' . utf8_convertir($arch["nombre"]) . '</td>	
                                <td>
                                    ' . $btnDowload . '
                                    <i title="Eliminar" style="cursor:pointer;"  onClick="if (confirm(\'Desea eliminar este archivo\')){document.frmSistema.idArchivo.value=' . $arch["id"] . ';navegar(\'EliminarArchivo\');}" class="fa fa-trash-o"></i>																		
                                </td>											
                            </tr>';
    }

    $pagina.= '								</tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar">
                                <button class="btn-default btn" onclick="navegar(\'Ver polizas\');">Regresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_nuevoArchivo() {
    $_SESSION["idPolizaActual"] = $_POST["idPoliza"];
    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">										
        </div>
    </div>										
</div>
<div class="container">
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>														
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Nuevo archivo</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">									
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="100"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adjuntar documento</label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Elija archivo</span><span class="fileinput-exists">Cambiar</span><input type="file" name="adjunto" id="adjunto"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>																														
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevoArchivo(\'GuardarArchivo\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Ver archivos\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_guardarArchivo() {
//GUARDAMOS EL ARCHIVO
    if ($_FILES["adjunto"]["name"] != "") {
        if ($_FILES["adjunto"]["error"] == 0) {
            $ext = substr($_FILES["adjunto"]["name"], strrpos($_FILES["adjunto"]["name"], '.'));
            $src = date("YmdHis") . '+' . $_POST["numPol"] . $ext;
            $ruta = "../polizas/" . $src;
            move_uploaded_file($_FILES["adjunto"]["tmp_name"], $ruta);
        }
    } else
        $src = '';

    liberar_bd();
    $insertArchivo = " CALL sp_sistema_insert_archivo(" . $_SESSION["idPolizaActual"] . ",
'" . (strtoupper($_POST["nombre"])) . "',
'" . $src . "',
" . $_SESSION['idUsuarioAlmanza'] . ");";
    $insert = consulta($insertArchivo);

    if ($insert) {
        $res = $msj . polizas_archivos();
    } else {
        unlink($ruta);
        $error = 'No se ha podido guardar el archivo.';
        $msj = sistema_mensaje("error", $error);

        $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">										
        </div>
    </div>										
</div>
<div class="container">
    <div class="row">							
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de p&oacute;liza</h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">P&oacute;liza:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["idPolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="cliente" class="col-sm-3 control-label">Cliente:</label>
                            <label for="cliente" class="col-sm-6 control-label">' . $_SESSION["clientePolizaActual"] . '</label>												
                        </div>
                        <div class="form-group">
                            <label for="poliza" class="col-sm-3 control-label">Vigencia:</label>
                            <label for="poliza" class="col-sm-6 control-label">' . $_SESSION["vigenciaPolizaActual"] . '</label>												
                        </div>
                    </div>									
                </div>
            </div>
        </div>							
    </div>														
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Nuevo archivo</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">									
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombre" class="col-sm-3 control-label">Nombre:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="nombre" name="nombre" maxlength="100" value="' . $_POST["nombre"] . '"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adjuntar documento</label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Elija archivo</span><span class="fileinput-exists">Cambiar</span><input type="file" name="adjunto" id="adjunto"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>																														
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevoArchivo(\'GuardarArchivo\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Ver archivos\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

        $res = $msj . $pagina;
    }

    return $res;
}

function polizas_eliminarArchivo() {
//DATOS DEL ARCHIVO 
    liberar_bd();
    $selectDatosArchivo = "CALL sp_sistema_select_datos_archivo_poliza(" . $_POST["idArchivo"] . ");";
    $datosArchivo = consulta($selectDatosArchivo);
    $archivo = siguiente_registro($datosArchivo);
    $ruta = "../polizas/" . $archivo["url"];

    liberar_bd();
    $deleteArchivo = " CALL sp_sistema_delete_archivo(" . $_POST["idArchivo"] . ");";
    $delete = consulta($deleteArchivo);

    if ($delete) {
        unlink($ruta);
        $res = $msj . polizas_archivos();
    } else {
        $error = 'No se ha podido eliminar el archivo.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . polizas_archivos();
    }

    return $res;
}

function polizas_detallesRecordatorio() {
//DATOS DEL RECORDATORIO
    liberar_bd();
    $selectDatosRecordatorio = 'CALL sp_sistema_select_datos_recordatorio(' . $_POST["idRecord"] . ');';
    $datosRecord = consulta($selectDatosRecordatorio);
    $record = siguiente_registro($datosRecord);

    switch ($record["tipo"]) {
        case 1:
            $tipoEnvio = "Cliente";
            break;
        case 2:
            $tipoEnvio = "Almanza";
            break;
        case 3:
            $tipoEnvio = "Ambos";
            break;
    }

    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles del recordatorio</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="datepicker" class="col-sm-3 control-label">Fecha de recordatorio:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input readonly="readonly" type="text" class="form-control" id="datepicker" name="datepicker" value="' . normalize_date($record["fecha"]) . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="asunto" class="col-sm-3 control-label">Asunto:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="asunto" name="asunto" maxlength="100" value="' . utf8_convertir($record["asunto"]) . '"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="txtRecordatorio" class="col-sm-3 control-label">Mensaje:</label>
                            <div class="col-md-6">	
                                <textarea readonly="readonly" class="form-control autosize" name="txtRecordatorio" id="txtRecordatorio">' . utf8_convertir($record["txt"]) . '</textarea>											
                            </div>													
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo de env&iacute;o:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="asunto" name="asunto" maxlength="100" value="' . $tipoEnvio . '"/>
                            </div>
                        </div>										
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-default btn" onclick="navegar(\'Recordatorios\');">Regresar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_formularioEditar() {
//DATOS DE LA POLIZA
    liberar_bd();
    $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_POST["idPoliza"] . ');';
    $datosPoliza = consulta($selectDatosPoliza);
    $poli = siguiente_registro($datosPoliza);
    $fechaInicial = normalize_date($poli["fechaIni"]);
    $fechaFinal = normalize_date($poli["fechaFin"]);
    $fechaInicial = str_replace('-', "/", $fechaInicial);
    $fechaFinal = str_replace('-', "/", $fechaFinal);
    $vigencia = $fechaInicial . ' - ' . $fechaFinal;

    switch ($poli["moneda"]) {
        case 1:
            $chk1 = "checked";
            break;
        case 2:
            $chk2 = "checked";
            break;
        case 3:
            $chk3 = "checked";
            break;
    }

//LISTA DE TIPOS DE POLIZAS
    liberar_bd();
    $selectListTipoPoliza = 'CALL sp_sistema_lista_tiposPoliza();';
    $listaTiposPoliza = consulta($selectListTipoPoliza);
    while ($pol = siguiente_registro($listaTiposPoliza)) {
        $selPoli = '';
        if ($poli["idTipo"] == $pol["id"])
            $selPoli = 'selected="selected"';
        $polizas.= '<option ' . $selPoli . ' value="' . $pol["id"] . '">' . utf8_convertir($pol["nombre"]) . '</option>';
    }

//LISTA DE CLIENTES
    liberar_bd();
    $selectListClientes = 'CALL sp_sistema_lista_clientes();';
    $listaClientes = consulta($selectListClientes);
    while ($cli = siguiente_registro($listaClientes)) {
        $selCli = '';
        if ($poli["idCliente"] == $cli["id"])
            $selCli = 'selected="selected"';
        $clientes.= '<option ' . $selCli . ' value="' . $cli["id"] . '">' . utf8_convertir($cli["nombre"]) . '</option>';
    }

//LISTA DE TIPOS DE PAGO
    liberar_bd();
    $selectListTiposPago = 'CALL sp_sistema_lista_tiposPago();';
    $listaTiposPago = consulta($selectListTiposPago);
    while ($pag = siguiente_registro($listaTiposPago)) {
        $selPag = '';
        if ($poli["idPago"] == $pag["id"])
            $selPag = 'selected="selected"';
        $pagos.= '<option ' . $selPag . ' value="' . $pag["id"] . '">' . utf8_convertir($pag["nombre"]) . '</option>';
    }

    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_POST["idPoliza"] . '" />									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de la p&oacute;liza</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="idTipPol" class="col-sm-3 control-label">Tipo de poliza:</label>
                            <div class="col-sm-6">
                                <select id="idTipPol" name="idTipPol" style="width:100% !important" class="selectSerch">
                                    ' . $polizas . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idCliente" class="col-sm-3 control-label">Cliente:</label>
                            <div class="col-sm-6">
                                <select id="idCliente" name="idCliente" style="width:100% !important" class="selectSerch">
                                    ' . $clientes . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prima" class="col-sm-3 control-label">Prima anual:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" id="prima" name="prima" value="' . $poli["prima"] . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo de moneda:</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="1" ' . $chk1 . '>
                                        Pesos
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="2" ' . $chk2 . '>
                                        D&oacute;lares
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="3" ' . $chk3 . '>
                                        Udis
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idTipPago" class="col-sm-3 control-label">Frecuencia de pago:</label>
                            <div class="col-sm-6">
                                <select id="idTipPago" name="idTipPago" style="width:100% !important" class="selectSerch">
                                    ' . $pagos . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaReporte2" class="col-sm-3 control-label">Vigencia:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input type="text" class="form-control" id="fechaReporte2" name="fechaReporte2" value="' . $vigencia . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="txtPoliza" class="col-sm-3 control-label">Observaciones:</label>
                            <div class="col-md-6">	
                                <textarea class="form-control autosize" name="txtPoliza" id="txtPoliza">' . utf8_convertir($poli["txt"]) . '</textarea>											
                            </div>													
                        </div>
                        <div class="alert alert-dismissable alert-danger">
                            <strong>No escoja archivo para no modificar</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adjuntar poliza</label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Elija archivo</span><span class="fileinput-exists">Cambiar</span><input type="file" name="adjunto" id="adjunto"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="numPol" class="col-sm-3 control-label">N&uacute;mero de poliza:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="numPol" name="numPol" maxlength="100" value="' . utf8_convertir($poli["num"]) . '"/>
                            </div>
                        </div>								
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevaPoliza(\'GuardarEdit\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Ver polizas\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_editar() {
    $hoy = date("Y-m-d h:m:s");
// obtenemos la fecha actual en formato unix
    $fecha_actual = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
//DATOS DE LA POLIZA
    liberar_bd();
    $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_POST["idPoliza"] . ');';
    $datosPoliza = consulta($selectDatosPoliza);
    $poli = siguiente_registro($datosPoliza);
//GUARDAMOS LA POLIZA VIEJA
    liberar_bd();
    $insertPolizaAntigua = " CALL sp_sistema_insert_poliza_antigua(	" . $poli["idTipo"] . ",
" . $poli["idCliente"] . ",
" . $poli["prima"] . ",
" . $poli["moneda"] . ",
" . $poli["idPago"] . ", 
'" . $poli["fechaIni"] . "',
'" . $poli["fechaFin"] . "', 
'" . $poli["txt"] . "',
'" . $poli["archivo"] . "',
'" . $poli["num"] . "', 
" . $_SESSION['idUsuarioAlmanza'] . ");";

    $polizaAntigua = consulta($insertPolizaAntigua);

//ID DE LA POLIZA INSERTADA
    liberar_bd();
    $selectUltimaPoliza = "CALL sp_sistema_select_ultima_poliza_idUser(" . $_SESSION['idUsuarioAlmanza'] . ")";
    $ultimaPoliza = consulta($selectUltimaPoliza);
    $poliza = siguiente_registro($ultimaPoliza);
    $idPoliza = $poliza["id"];

//EDITAMOS LA POLIZA PADRE
    if ($_POST["fechaReporte2"] != '') {
        $iparr = split(" - ", $_POST["fechaReporte2"]);
        $iparr[0] = str_replace('/', "-", $iparr[0]);
        $iparr[1] = str_replace('/', "-", $iparr[1]);
        $fechaIniFormat = normalize_date2($iparr[0]);
        $fechaFinFormat = normalize_date2($iparr[1]);
        $fechaFinFormat2 = normalize_date($iparr[1]);
        $fechaIni = $fechaIniFormat . ' 00:00:00';
        $fechaFin = $fechaFinFormat . ' 23:59:59';
        $fechaFin2 = $fechaFinFormat2 . ' 23:59:59';
    } else {
        $fechaIniFormat = '00-00-0000';
        $fechaFinFormat = '00-00-0000';
        $fechaIni = '0000-00-00 00:00:00';
        $fechaFin = '0000-00-00 00:00:00';
        $fechaFin2 = '00-00-0000 00:00:00';
    }


    if ($_FILES["adjunto"]["name"] != "") {
        if ($_FILES["adjunto"]["error"] == 0) {
            $ext = substr($_FILES["adjunto"]["name"], strrpos($_FILES["adjunto"]["name"], '.'));
            $src = date("YmdHis") . '+' . $poli["num"] . $ext;
            $ruta = "../polizas/" . $src;
            move_uploaded_file($_FILES["adjunto"]["tmp_name"], $ruta);
        }
    } else
        $src = $poli["archivo"];

    liberar_bd();
    $updatePoliza = " CALL sp_sistema_update_poliza(	" . $_POST["idPoliza"] . ",
" . $_POST["idTipPol"] . ",
" . $_POST["idCliente"] . ",
" . $_POST["prima"] . ",
" . $_POST["optMoneda"] . ",
" . $_POST["idTipPago"] . ", 
'" . $fechaIni . "',
'" . $fechaFin . "', 
'" . ($_POST["txtPoliza"]) . "',
'" . $src . "',
'" . ($_POST["numPol"]) . "', 
" . $_SESSION['idUsuarioAlmanza'] . ");";
    $update = consulta($updatePoliza);

    if ($update) {
// separamos los valores de la fecha con la que queremos operar
        list($dia, $mes, $año) = explode('-', $fechaFinFormat2);
// redefinimos la variable $fechaFinFormat2 y le almacenamos el valor unix
        $fechaFinFormat2 = mktime(0, 0, 0, $mes, $dia, $año);
// ahora estamos listos para efectuar operaciones con ambas fechas
        if ($fecha_actual <= $fechaFinFormat2) {
//ACTUALISAMOS ESTATUS A ACTIVA
            liberar_bd();
            $updateEstatusPoliza = 'CALL sp_sistema_update_estatus_poliza(' . $_POST["idPoliza"] . ', 1, ' . $_SESSION["idUsuarioAlmanza"] . ');';
            $estatusPoliza = consulta($updateEstatusPoliza);
        }

//INSERTAMOS EN LA TABLA DE HISTORIAL LA POLIZA
        liberar_bd();
        $insertPolizaHistorial = 'CALL sp_sistema_insert_poliza_historial(' . $_POST["idPoliza"] . ', ' . $idPoliza . ', ' . $_SESSION["idUsuarioAlmanza"] . ');';
        $polizaHistorial = consulta($insertPolizaHistorial);

        $res = $msj . polizas_verPolizas();
    } else {
        unlink($ruta);
        $error = 'No se ha podido editar la póliza.';
        $msj = sistema_mensaje("error", $error);

//DATOS DE LA POLIZA
        liberar_bd();
        $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_POST["idPoliza"] . ');';
        $datosPoliza = consulta($selectDatosPoliza);
        $poli = siguiente_registro($datosPoliza);
        $fechaInicial = normalize_date($poli["fechaIni"]);
        $fechaFinal = normalize_date($poli["fechaFin"]);
        $fechaInicial = str_replace('-', "/", $fechaInicial);
        $fechaFinal = str_replace('-', "/", $fechaFinal);
        $vigencia = $fechaInicial . ' - ' . $fechaFinal;

        switch ($poli["moneda"]) {
            case 1:
                $chk1 = "checked";
                break;
            case 2:
                $chk2 = "checked";
                break;
            case 3:
                $chk3 = "checked";
                break;
        }

//LISTA DE TIPOS DE POLIZAS
        liberar_bd();
        $selectListTipoPoliza = 'CALL sp_sistema_lista_tiposPoliza();';
        $listaTiposPoliza = consulta($selectListTipoPoliza);
        while ($pol = siguiente_registro($listaTiposPoliza)) {
            $selPoli = '';
            if ($poli["idTipo"] == $pol["id"])
                $selPoli = 'selected="selected"';
            $polizas.= '<option ' . $selPoli . ' value="' . $pol["id"] . '">' . utf8_convertir($pol["nombre"]) . '</option>';
        }

//LISTA DE CLIENTES
        liberar_bd();
        $selectListClientes = 'CALL sp_sistema_lista_clientes();';
        $listaClientes = consulta($selectListClientes);
        while ($cli = siguiente_registro($listaClientes)) {
            $selCli = '';
            if ($poli["idCliente"] == $cli["id"])
                $selCli = 'selected="selected"';
            $clientes.= '<option ' . $selCli . ' value="' . $cli["id"] . '">' . utf8_convertir($cli["nombre"]) . '</option>';
        }

//LISTA DE TIPOS DE PAGO
        liberar_bd();
        $selectListTiposPago = 'CALL sp_sistema_lista_tiposPago();';
        $listaTiposPago = consulta($selectListTiposPago);
        while ($pag = siguiente_registro($listaTiposPago)) {
            $selPag = '';
            if ($poli["idPago"] == $pag["id"])
                $selPag = 'selected="selected"';
            $pagos.= '<option ' . $selPag . ' value="' . $pag["id"] . '">' . utf8_convertir($pag["nombre"]) . '</option>';
        }

        $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_POST["idPoliza"] . '" />									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de la p&oacute;liza</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="idTipPol" class="col-sm-3 control-label">Tipo de poliza:</label>
                            <div class="col-sm-6">
                                <select id="idTipPol" name="idTipPol" style="width:100% !important" class="selectSerch">
                                    ' . $polizas . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idCliente" class="col-sm-3 control-label">Cliente:</label>
                            <div class="col-sm-6">
                                <select id="idCliente" name="idCliente" style="width:100% !important" class="selectSerch">
                                    ' . $clientes . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prima" class="col-sm-3 control-label">Prima anual:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="text" class="form-control" id="prima" name="prima" value="' . $poli["prima"] . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Tipo de moneda:</label>
                            <div class="col-sm-6">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="1" ' . $chk1 . '>
                                        Pesos
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="2" ' . $chk2 . '>
                                        D&oacute;lares
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="optMoneda" id="optMoneda" value="3" ' . $chk3 . '>
                                        Udis
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idTipPago" class="col-sm-3 control-label">Frecuencia de pago:</label>
                            <div class="col-sm-6">
                                <select id="idTipPago" name="idTipPago" style="width:100% !important" class="selectSerch">
                                    ' . $pagos . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaReporte2" class="col-sm-3 control-label">Vigencia:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>

                                    <input type="text" class="form-control" id="fechaReporte2" name="fechaReporte2" value="' . $vigencia . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="txtPoliza" class="col-sm-3 control-label">Observaciones:</label>
                            <div class="col-md-6">	
                                <textarea class="form-control autosize" name="txtPoliza" id="txtPoliza">' . utf8_convertir($poli["txt"]) . '</textarea>											
                            </div>													
                        </div>
                        <div class="alert alert-dismissable alert-danger">
                            <strong>No escoja archivo para no modificar</strong>
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        </div>	
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Adjuntar poliza</label>
                            <div class="col-sm-9">
                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Elija archivo</span><span class="fileinput-exists">Cambiar</span><input type="file" name="adjunto" id="adjunto"></span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="numPol" class="col-sm-3 control-label">N&uacute;mero de poliza:</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="numPol" name="numPol" maxlength="100" value="' . utf8_convertir($poli["num"]) . '"/>
                            </div>
                        </div>								
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-primary btn" onclick="nuevaPoliza(\'GuardarEdit\');">Guardar</i>
                                <i class="btn-default btn" onclick="navegar(\'Ver polizas\');">Cancelar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

        $res = $msj . $pagina;
    }

    return $res;
}

function polizas_historial() {
//POLIZA ACTUAL
    if ($_POST["idPoliza"] != '') {
        $_SESSION["idPolizaActual"] = $_POST["idPoliza"];
//DETALLES DE LA POLIZA
        liberar_bd();
        $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_SESSION["idPolizaActual"] . ');';
        $datosPoliza = consulta($selectDatosPoliza);
        $poli = siguiente_registro($datosPoliza);
        $_SESSION["clientePolizaActual"] = utf8_convertir($poli["cliente"]);
    }

    liberar_bd();
    $selectPolizasHijo = '	SELECT
histPol.id_historial_poliza AS idHistorial,
poli.id_tipo_poliza AS idTipo,
tipPol.nombre_tipo_poliza AS tipo,
poli.prima_anual_poliza AS prima,
poli.id_tipo_pago AS idPago,
tipPa.nombre_tipo_pago AS pago,
poli.fechaInicial_poliza AS fechaIni,
poli.fechaFin_poliza AS fechaFin,
poli.num_poliza AS num,
poli.fecha_poliza AS fecha,
poli.id_poliza AS idPoliza,
poli.tipo_moneda_poliza AS moneda
FROM
historial_poliza AS histPol
INNER JOIN poliza AS poli ON histPol.id_poliza_hijo = poli.id_poliza
INNER JOIN tipo_poliza AS tipPol ON poli.id_tipo_poliza = tipPol.id_tipo_poliza
INNER JOIN tipo_pago AS tipPa ON poli.id_tipo_pago = tipPa.id_tipo_pago
WHERE
histPol.id_poliza_padre = ' . $_SESSION["idPolizaActual"] . '
AND 
poli.estatus_poliza = 5
ORDER BY
fecha DESC';

    $polizasHijo = consulta($selectPolizasHijo);

    $pagina = '	<div id="page-heading">	
    <h1>Historial de P&oacute;lizas ' . $_SESSION["clientePolizaActual"] . '</h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_SESSION["idPolizaActual"] . '" />
                   <input type="hidden" id="idPolizaHijo" name="idPolizaHijo" value="' . $_SESSION["idPolizaActual"] . '" />								
                   <input type="hidden" id="idHistorial" name="idHistorial" value="" />
            <input type="hidden" name="txtIndice" />
        </div>
    </div>										
</div>										
<div class="container">												
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>N&Uacute;MERO</th>
                                    <th>TIPO</th>
                                    <th>PRIMA ANUAL</th>
                                    <th>TIPO DE MONEDA</th>
                                    <th>TIPO DE PAGO</th>														
                                    <th>FECHA INICIAL</th>
                                    <th>FECHA LIMITE</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($poli = siguiente_registro($polizasHijo)) {
        switch ($poli['moneda']) {
            case 1:
                $moneda = 'Pesos';
                break;
            case 2:
                $moneda = 'D&oacute;lares';
                break;
            case 3:
                $moneda = 'Udis';
                break;
        }

        $pagina.= ' <tr>
                                    <td>' . normalize_date2($poli["fecha"]) . '</td>
                                    <td>' . utf8_convertir($poli["num"]) . '</td>
                                    <td>' . utf8_convertir($poli["tipo"]) . '</td>
                                    <td>' . number_format($poli["prima"], 2) . '</td>
                                    <td>' . $moneda . '</td>
                                    <td>' . utf8_convertir($poli["pago"]) . '</td>	
                                    <td>' . normalize_date2($poli["fechaIni"]) . '</td>
                                    <td>' . normalize_date2($poli["fechaFin"]) . '</td>	
                                    <td>
                                        <i title="Ver detalles" style="cursor:pointer;" onclick="document.frmSistema.idPolizaHijo.value = ' . $poli["idPoliza"] . ';navegar(\'Ver detalles hijos\');" class="fa fa-eye"></i>
                                        <i title="Recordatorios" style="cursor:pointer;" onclick="document.frmSistema.idPolizaHijo.value = ' . $poli["idPoliza"] . ';navegar(\'RecordatoriosHijos\');" class="fa fa-calendar"></i>
                                    </td>											
                                </tr>';
    }

    $pagina.= '								</tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar">
                                <button class="btn-default btn" onclick="navegar(\'Ver polizas\');">Regresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_detallesHijos() {
//DATOS DE LA POLIZA
    liberar_bd();
    $selectDatosPoliza = 'CALL sp_sistema_select_datos_poliza(' . $_POST["idPolizaHijo"] . ');';
    $datosPoliza = consulta($selectDatosPoliza);
    $poli = siguiente_registro($datosPoliza);
    $fechaInicial = normalize_date($poli["fechaIni"]);
    $fechaFinal = normalize_date($poli["fechaFin"]);
    $fechaInicial = str_replace('-', "/", $fechaInicial);
    $fechaFinal = str_replace('-', "/", $fechaFinal);
    $vigencia = $fechaInicial . ' - ' . $fechaFinal;

    switch ($poli['moneda']) {
        case 1:
            $moneda = 'Pesos';
            break;
        case 2:
            $moneda = 'D&oacute;lares';
            break;
        case 3:
            $moneda = 'Udis';
            break;
    }

//CHECAMOS SI TIENE ARCHIVO PARA DESCARGAR
    if ($poli["archivo"] != '')
        $btnDescarga = '<div class="form-group">
    <label class="col-sm-3 control-label">P&oacute;liza</label>
    <div class="col-sm-6">
        <a target="_blank" href="ajax/polizasDownload.php?file=' . $poli["archivo"] . '" class="btn btn-default">Descargar</a>
    </div>
</div>';
    else
        $btnDescarga = '';

    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles de la p&oacute;liza</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="idTipPol" class="col-sm-3 control-label">Tipo de poliza:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="idTipPol" name="idTipPol" maxlength="100" value="' . utf8_convertir($poli["tipo"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="idCliente" class="col-sm-3 control-label">Cliente:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="idCliente" name="idCliente" maxlength="100" value="' . utf8_convertir($poli["cliente"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="prima" class="col-sm-3 control-label">Prima anual:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input readonly="readonly" type="text" class="form-control" id="prima" name="prima" value="' . number_format($poli["prima"], 2) . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="moneda" class="col-sm-3 control-label">Tipo de moneda:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="moneda" name="moneda" maxlength="100" value="' . $moneda . '"/>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="idTipPago" class="col-sm-3 control-label">Frecuencia de pago:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="idTipPago" name="idTipPago" maxlength="100" value="' . utf8_convertir($poli["pago"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="fechaIniFin" class="col-sm-3 control-label">Vigencia:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="fechaIniFin" name="fechaIniFin" maxlength="100" value="' . $vigencia . '"/>
                            </div>																													
                        </div>
                        <div class="form-group">
                            <label for="txtPoliza" class="col-sm-3 control-label">Observaciones:</label>
                            <div class="col-md-6">	
                                <textarea readonly="readonly" class="form-control autosize" name="txtPoliza" id="txtPoliza">' . utf8_convertir($poli["txt"]) . '</textarea>											
                            </div>													
                        </div>	
                        ' . $btnDescarga . '
                        <div class="form-group">
                            <label for="numPol" class="col-sm-3 control-label">N&uacute;mero de poliza:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="numPol" name="numPol" maxlength="100" value="' . utf8_convertir($poli["num"]) . '"/>
                            </div>
                        </div>								
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-default btn" onclick="navegar(\'Historial\');">Regresar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_recordatoriosHijos() {
    liberar_bd();
    $selectRecordatorios = 'SELECT
recorda.id_recordatorio AS id,
recorda.asunto_recordatorio AS asunto,
recorda.fecha_recordatorio AS fecha,
recorda.fechaCreacion_recordatorio AS fechaRec,
recorda.estatus_recordatorio AS estatus
FROM
e11_almanzaseguros.recordatorio AS recorda
WHERE
recorda.id_poliza = ' . $_SESSION["idPolizaActual"] . '
AND recorda.estatus_recordatorio <> 0
ORDER BY
fechaRec DESC';

    /* $paginar = paginar($selectPolizas,200);
      $selectPolizas .= " LIMIT ".$paginar[0].",".$paginar[2]; */
    $recordatorios = consulta($selectRecordatorios);
    $ctaRecordatorios = cuenta_registros($recordatorios);

    $pagina = '	<div id="page-heading">	
    <h1>Recordatorios</h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idPoliza" name="idPoliza" value="' . $_SESSION["idPolizaActual"] . '" />
                   <input type="hidden" id="idPolizaHijo" name="idPolizaHijo" value="' . $_SESSION["idPolizaActual"] . '" />								
                   <input type="hidden" id="idRecord" name="idRecord" value="" />
        </div>
    </div>										
</div>										
<div class="container">													
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                    <div class="options">   
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
                            <thead>
                                <tr>
                                    <th>FECHA</th>
                                    <th>FECHA PARA RECORDATORIO</th>
                                    <th>ASUNTO</th>
                                    <th>ESTATUS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($record = siguiente_registro($recordatorios)) {
        //ESTATUS DEL RECORDATORIO
        switch ($record['estatus']) {
            case 1:
                $estatus = '<span class="tdNegro">ACTIVO</span>';
                break;
            case 2:
                $estatus = '<span class="tdRojo">PAGADO</span>';
                break;
        }

        $pagina.= ' <tr>
                                <td>' . normalize_date2($record["fecha"]) . '</td>
                                <td>' . normalize_date2($record["fechaRec"]) . '</td>
                                <td>' . utf8_convertir($record["asunto"]) . '</td>	
                                <td>' . $estatus . '</td>
                                <td>
                                    <i title="Ver detalles" style="cursor:pointer;" onclick="document.frmSistema.idRecord.value = ' . $record["id"] . ';navegar(\'Ver recordatorio hijos\');" class="fa fa-eye"></i>																	
                                </td>											
                            </tr>';
    }

    $pagina.= '								</tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar">
                                <button class="btn-default btn" onclick="navegar(\'Historial\');">Regresar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_detallesRecordatorioHijos() {
//DATOS DEL RECORDATORIO
    liberar_bd();
    $selectDatosRecordatorio = 'CALL sp_sistema_select_datos_recordatorio(' . $_POST["idRecord"] . ');';
    $datosRecord = consulta($selectDatosRecordatorio);
    $record = siguiente_registro($datosRecord);

    $pagina = '	<div id="page-heading">	
    <h1></h1>
    <div class="options">
        <div class="btn-toolbar">									
        </div>
    </div>										
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>Detalles del recordatorio</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="datepicker" class="col-sm-3 control-label">Fecha de recordatorio:</label>
                            <div class="col-sm-6">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    <input readonly="readonly" type="text" class="form-control" id="datepicker" name="datepicker" value="' . normalize_date($record["fecha"]) . '"/>
                                </div>
                            </div>																														
                        </div>
                        <div class="form-group">
                            <label for="asunto" class="col-sm-3 control-label">Asunto:</label>
                            <div class="col-sm-6">
                                <input readonly="readonly" type="text" class="form-control" id="asunto" name="asunto" maxlength="100" value="' . utf8_convertir($record["asunto"]) . '"/>
                            </div>
                        </div>	
                        <div class="form-group">
                            <label for="txtRecordatorio" class="col-sm-3 control-label">Mensaje:</label>
                            <div class="col-md-6">	
                                <textarea readonly="readonly" class="form-control autosize" name="txtRecordatorio" id="txtRecordatorio">' . utf8_convertir($record["txt"]) . '</textarea>											
                            </div>													
                        </div>										
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3">
                            <div class="btn-toolbar">
                                <i class="btn-default btn" onclick="navegar(\'RecordatoriosHijos\');">Regresar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function polizas_eliminar() {
    liberar_bd();
    $deletePoliza = "CALL sp_sistema_delete_poliza('" . $_POST["idPoliza"] . "');";
    $delete = consulta($deletePoliza);
    if ($delete) {
        $res = $msj . polizas_verPolizas();
    } else {
        $error = 'No se ha podido eliminar la póliza.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . polizas_verPolizas();
    }

    return $res;
}
