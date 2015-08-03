<?php

function clientes_menuInicio() {
    $btnAlta = false;
    $btnEdita = false;
    $btnElimina = false;
    $btnDoc = false;

//PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_convertir($acciones["accion"])) {
            case 'Alta':
                $btnAlta = true;
                break;
            case 'Modificación':
                $btnEdita = true;
                break;
            case 'Ver detalles':
                $btnDetalle = true;
                break;
            case 'Eliminación':
                $btnElimina = true;
                break;
            case 'Documentos de cliente':
                $btnDoc = true;
                break;
        }
    }

//CLIENTES DEL AGENTE
    liberar_bd();
    $selectCliente = "	
SELECT
clien.id_cliente AS id,
clien.nombre_cliente AS nombre,
clien.tipo_cliente AS tipo,
clien.razon_cliente AS razon,
city.nombre_ciudades AS ciudad,
state.nombre_estados estado,
clien.razon_cliente AS razon,
clien.rfc_cliente AS rfc,
clien.estatus_cliente AS estatus
FROM
cliente AS clien
INNER JOIN ciudades AS city ON clien.id_ciudad = city.id_ciudades
INNER JOIN estados state ON clien.id_estado = state.id_estados
WHERE
clien.id_agente = " . $_SESSION['idAgenteActual'] . "
AND clien.estatus_cliente = 1";

    $cliente = consulta($selectCliente);

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
            <input type="hidden" id="idCliente" name="idCliente" value="" />
            <input type="hidden" name="txtIndice" /> ';
    if ($btnAlta)
        $pagina.= '	<i title="Nuevo Cliente" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
                Nuevo Cliente
            </i>';
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
                        <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                <script type="text/javascript">
                    //when the webpage has loaded do this
                    $(document).ready(function () {
                        filtroTabla();
                    });
                    </script>
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="tablaClientes">
                            <thead>
                             <tr>
                                    <th>NOMBRE</th>
                                    <th>TIPO</th>
                                    <th>RFC</th>
                                    <th>CIUDAD</th>
                                    <th>ESTADO</th>													
                                    <th>ESTATUS</th>
                                </tr>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>TIPO</th>
                                    <th>RFC</th>
                                    <th>CIUDAD</th>
                                    <th>ESTADO</th>														
                                    <th>ESTATUS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($cli = siguiente_registro($cliente)) {
        //ESTATUS DEL CLIENTE
        switch ($cli['estatus']) {
            case 1:
                $estatus = '<span class="tdNegro">ACTIVO</span>';
                break;
            case 2:
                $estatus = '<span class="tdRojo">INACTIVO</span>';
                break;
        }

        //TIPO DEL CLIENTE
        switch ($cli['tipo']) {
            case 1:
                $tipoCliente = 'CLIENTE';
                break;
            case 2:
                $tipoCliente = 'EMPRESA';
                break;
        }

        //CHECAMOS PERMISO VER DETALLES
        if ($btnDetalle)
            $verDetalle = '<i title="Ver detalles" style="cursor:pointer;" onClick="document.frmSistema.idCliente.value = ' . $cli["id"] . '; navegar(\'Detalles\');">' . utf8_convertir($cli["nombre"]) . '</i>';
        else
            $verDetalle = utf8_convertir($cli["nombre"]);

        $pagina.= ' <tr>
                                <td>' . $verDetalle . '</td>
                                <td>' . $tipoCliente . '</td>
                                <td>' . utf8_convertir($cli["rfc"]) . '</td>
                                <td>' . utf8_convertir($cli["ciudad"]) . '</td>
                                <td>' . utf8_convertir($cli["estado"]) . '</td>																	
                                <td>' . $estatus . '</td>
                                <td class="tdAcciones">';
        if ($btnEdita)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idCliente.value = ' . $cli["id"] . ';navegar(\'Editar\');">
                                                   <i title="Editar" class="fa fa-pencil"></i>
                                    </a> ';
        if ($btnDoc)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idCliente.value = ' . $cli["id"] . ';navegar(\'Documentos de cliente\');">
                                                   <i title="Documentos de cliente" class="fa fa-file"></i>
                                    </a> ';
        if ($btnElimina)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if (confirm(\'Desea eliminar este usuario\')){document.frmSistema.idCliente.value=' . $cli["id"] . '; navegar(\'Eliminar\');}">
                                                   <i title="Eliminar" class="fa fa-trash-o"></i>
                                    </a> ';
        $pagina.= '		</td>											
                            </tr>';
    }

    $pagina.= '								</tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
    $_SESSION["pagina"] = $pagina;
    return $pagina;
}

function clientes_formularioNuevo() {
//LISTA DE ESTADOS
    liberar_bd();
    $selectEstados = 'CALL sp_sistema_lista_estados();';
    $estados = consulta($selectEstados);
    while ($est = siguiente_registro($estados)) {
        $optEstados .= '<option value="' . $est["id"] . '">' . utf8_convertir($est["nombre"]) . '</option>';
    }

    $pagina = '		<div id="page-heading">	
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
                <div class="panel-heading">
                    <h4>DATOS GENERALES</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Tipo</label>
                                    <div class="col-sm-6">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoCliente" id="tipoCliente" value="1" checked>
                                                Persona
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoCliente" id="tipoCliente" value="2">
                                                Empresa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreCliente" class="col-sm-4 control-label">Nombre comercial:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="rfcCliente" class="col-sm-4 control-label">RFC:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="rfcCliente" name="rfcCliente" maxlength="13"/>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="col-sm-3" id="divDatosEmpresa" style="display:none;">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="razon" class="col-sm-4 control-label">Razón social:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="razon" name="razon" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="id_estado" class="col-sm-4 control-label">Estado:</label>
                                    <div class="col-sm-8">
                                        <select id="id_estado" name="id_estado" style="width:100% !important" class="selectSerch">
                                            <option value="0">Seleccione un estado</option>
                                            ' . $optEstados . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group" id="divCiudad">
                                    <label for="id_ciudad" class="col-sm-4 control-label">Ciudad:</label>
                                    <div class="col-sm-8">
                                        <select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">	
                                            <option value="0">Seleccione un estado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="localCliente" class="col-sm-4 control-label">Localidad:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="localCliente" name="localCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>											
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="calleCliente" class="col-sm-4 control-label">Calle:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="calleCliente" name="calleCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numExtCliente" class="col-sm-4 control-label">Num Ext:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numExtCliente" name="numExtCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numIntCliente" class="col-sm-4 control-label">Num Int:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numIntCliente" name="numIntCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="coloniaCliente" class="col-sm-4 control-label">Colonia:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="coloniaCliente" name="coloniaCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="cpCliente" class="col-sm-4 control-label">CP:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="cpCliente" name="cpCliente" maxlength="5"/>
                                    </div>
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
                    <h4>DATOS DE CONTACTO</h4>
                </div>
                <div class="panel-body collapse in">
                    <div class="row">										
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreContactoCliente" class="col-sm-4 control-label">Nombre de contacto:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreContactoCliente" name="nombreContactoCliente" maxlength="100"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="correoCliente" class="col-sm-4 control-label">Correo:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control frmCorreo" id="correoCliente" name="correoCliente" maxlength="255"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ladaCliente" class="col-sm-4 control-label">Tel&eacute;fono:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="ladaCliente" name="ladaCliente" style="width:30%; float:left;" maxlength="3"/>
                                        <input type="text" class="form-control" id="telCliente" name="telCliente" style="width:70%;" maxlength="8"/>
                                    </div>
                                </div>	
                            </div>
                        </div>	
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ladaCelCliente" class="col-sm-4 control-label">Celular:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="ladaCelCliente" name="ladaCelCliente" style="width:30%; float:left;" maxlength="3"/>
                                        <input type="text" class="form-control" id="telCelCliente" name="telCelCliente" style="width:70%;" maxlength="8"/>
                                    </div>
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
                    <h4>Datos de acceso</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Agregar acceso</label>
                                    <div class="col-sm-8">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="accesoCliente" id="accesoCliente" value="1" checked>
                                                NO
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="accesoCliente" id="accesoCliente" value="2">
                                                SI
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divAcceso" style="display:none;">
                        <div class="col-sm-3">
                            <div class="form-horizontal">																						
                                <div class="form-group">
                                    <label for="login_usuario" class="col-sm-4 control-label">Usuario:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="login_usuario" name="login_usuario" maxlength="200" value="' . $_POST["login_usuario"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario" class="col-sm-4 control-label">Contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario" id="pswd_usuario" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario_c" class="col-sm-4 control-label">Confirmar contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario_c" id="pswd_usuario_c" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>									
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoCliente(\'Guardar\');">Guardar</i>
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

function clientes_formularioEditar() {
//DATOS DEL CLIENTE
    liberar_bd();
    $selectDatosCliente = 'CALL sp_sistema_select_datos_cliente(' . $_POST["idCliente"] . ');';
    $datosCliente = consulta($selectDatosCliente);
    $cli = siguiente_registro($datosCliente);

//DATOS DEL CONTACTO EN AGENDA
    liberar_bd();
    $selectMinAgenda = 'CALL sp_sistema_select_min_contacto_cliente(' . $_POST["idCliente"] . ');';
    $minAgenda = consulta($selectMinAgenda);
    $agen = siguiente_registro($minAgenda);

//DATOS DIRECCION DEL CLIENTE
    liberar_bd();
    $selectMinDireccion = 'CALL sp_sistema_select_min_direccion_cliente(' . $agen["id"] . ');';
    $minDireccion = consulta($selectMinDireccion);
    $minDic = siguiente_registro($minDireccion);

//DATOS CORREO DEL CLIENTE
    liberar_bd();
    $selectMinCorreo = 'CALL sp_sistema_select_min_correo_cliente(' . $agen["id"] . ');';
    $minCorreo = consulta($selectMinCorreo);
    $minCor = siguiente_registro($minCorreo);

//DATOS TELEFONO PERSONAL CLIENTE
    liberar_bd();
    $selectMinTelefono = 'CALL sp_sistema_select_min_telefono_cliente(' . $agen["id"] . ');';
    $minTelefono = consulta($selectMinTelefono);
    $minTel = siguiente_registro($minTelefono);

//DATOS TELEFONO CELULAR CLIENTE
    liberar_bd();
    $selectMinCelular = 'CALL sp_sistema_select_min_celular_cliente(' . $agen["id"] . ');';
    $minCelular = consulta($selectMinCelular);
    $minCel = siguiente_registro($minCelular);

//DATOS USUARIO LOGIN
    liberar_bd();
    $selectClienteUsuario = 'CALL sp_sistema_select_usuario_cliente(' . $_POST["idCliente"] . ');';
    $clienteUsuario = consulta($selectClienteUsuario);
    $ctaClienteUsuario = cuenta_registros($clienteUsuario);
    if ($ctaClienteUsuario != 0) {
        $cliUser = siguiente_registro($clienteUsuario);
        $selSiAcceso = 'checked';
        $styleAcceso = 'display:block;';
    } else {
        $selNoAcceso = 'checked';
        $styleAcceso = 'display:none;';
    }

//LISTA DE ESTADOS
    liberar_bd();
    $selectEstados = 'CALL sp_sistema_lista_estados();';
    $estados = consulta($selectEstados);
    while ($est = siguiente_registro($estados)) {
        $selEdo = '';
        if ($cli["idEdo"] == $est["id"])
            $selEdo = 'selected="selected"';
        $optEstados .= '<option ' . $selEdo . ' value="' . $est["id"] . '">' . utf8_convertir($est["nombre"]) . '</option>';
    }

//LISTA DE CIUDADES
    liberar_bd();
    $selectCiudades = 'CALL sp_sistema_lista_ciudades_edoId(' . $cli["idEdo"] . ');';
    $ciudades = consulta($selectCiudades);
    while ($ciu = siguiente_registro($ciudades)) {
        $selCity = '';
        if ($cli["idCity"] == $ciu["id"])
            $selCity = 'selected="selected"';
        $optCiudades .= '<option ' . $selCity . ' value="' . $ciu["id"] . '">' . utf8_convertir($ciu["nombre"]) . '</option>';
    }

//TIPO CLIENTE
    if ($cli["tipo"] == 1) {
//cliente
        $selCli = 'checked';
        $styleTipCli = 'display:none;';
    } elseif ($cli["tipo"] == 2) {
//empresa
        $selEmp = 'checked';
        $styleTipCli = '"display:block;';
    }

    $pagina = '		<div id="page-heading">	
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
            <input readonly="readonly" type="hidden" id="idCliente" name="idCliente" value="' . $_POST["idCliente"] . '" />
                   <input type="hidden" readonly="readonly" id="idAgenda" name="idAgenda" value="' . $agen["id"] . '"/>
                   <input type="hidden" readonly="readonly" id="idDireccion" name="idDireccion" value="' . $minDic["id"] . '"/>
                   <input type="hidden" readonly="readonly" id="idCorreo" name="idCorreo" value="' . $minCor["id"] . '"/>
                   <input type="hidden" readonly="readonly" id="idTel" name="idTel" value="' . $minTel["id"] . '"/>
                   <input type="hidden" readonly="readonly" id="idCel" name="idCel" value="' . $minCel["id"] . '"/>
                   <input type="hidden" readonly="readonly" id="idUserAcces" name="idUserAcces" value="' . $cliUser["idUser"] . '"/>										
        </div>
    </div>	
</div>									
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4>DATOS GENERALES</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Tipo</label>
                                    <div class="col-sm-6">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoCliente" id="tipoCliente" value="1" ' . $selCli . '>
                                                Persona
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="tipoCliente" id="tipoCliente" value="2" ' . $selEmp . '>
                                                Empresa
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreCliente" class="col-sm-4 control-label">Nombre comercial:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" maxlength="100" value="' . utf8_convertir($cli["nombre"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="rfcCliente" class="col-sm-4 control-label">RFC:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="rfcCliente" name="rfcCliente" maxlength="13" value="' . utf8_convertir($cli["rfc"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>	
                        <div class="col-sm-3" id="divDatosEmpresa" style="' . $styleTipCli . '">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="razon" class="col-sm-4 control-label">Razón social:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="razon" name="razon" maxlength="100" value="' . utf8_convertir($cli["razon"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="id_estado" class="col-sm-4 control-label">Estado:</label>
                                    <div class="col-sm-8">
                                        <select id="id_estado" name="id_estado" style="width:100% !important" class="selectSerch">
                                            <option value="0">Seleccione un estado</option>
                                            ' . $optEstados . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group" id="divCiudad">
                                    <label for="id_ciudad" class="col-sm-4 control-label">Ciudad:</label>
                                    <div class="col-sm-8">
                                        <select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">	
                                            <option value="0">Seleccione una ciudad</option>
                                            ' . $optCiudades . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="localCliente" class="col-sm-4 control-label">Localidad:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="localCliente" name="localCliente" maxlength="100" value="' . utf8_convertir($cli["localidad"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>											
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="calleCliente" class="col-sm-4 control-label">Calle:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="calleCliente" name="calleCliente" maxlength="100" value="' . utf8_convertir($minDic["calle"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numExtCliente" class="col-sm-4 control-label">Num Ext:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numExtCliente" name="numExtCliente" maxlength="100" value="' . utf8_convertir($minDic["numExt"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numIntCliente" class="col-sm-4 control-label">Num Int:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numIntCliente" name="numIntCliente" maxlength="100" value="' . utf8_convertir($minDic["numInt"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="coloniaCliente" class="col-sm-4 control-label">Colonia:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="coloniaCliente" name="coloniaCliente" maxlength="100" value="' . utf8_convertir($minDic["colonia"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="cpCliente" class="col-sm-4 control-label">CP:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="cpCliente" name="cpCliente" maxlength="5" value="' . $minDic["cp"] . '"/>
                                    </div>
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
                    <h4>DATOS DE CONTACTO</h4>
                </div>
                <div class="panel-body collapse in">
                    <div class="row">										
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreContactoCliente" class="col-sm-4 control-label">Nombre de contacto:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreContactoCliente" name="nombreContactoCliente" maxlength="100" value="' . utf8_convertir($agen["nombre"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="correoCliente" class="col-sm-4 control-label">Correo:</label>
                                    <div class="col-sm-8">																
                                        <input type="text" class="form-control frmCorreo" id="correoCliente" name="correoCliente" maxlength="255" value="' . utf8_convertir($minCor["correo"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ladaCliente" class="col-sm-4 control-label">Tel&eacute;fono:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="ladaCliente" name="ladaCliente" style="width:30%; float:left;" maxlength="3" value="' . $minTel["lada"] . '"/>
                                               <input type="text" class="form-control" id="telCliente" name="telCliente" style="width:70%;" maxlength="8" value="' . $minTel["telefono"] . '"/>
                                    </div>
                                </div>	
                            </div>
                        </div>	
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ladaCelCliente" class="col-sm-4 control-label">Celular:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" 
                                               id="ladaCelCliente" name="ladaCelCliente" style="width:30%; float:left;" maxlength="3" value="' . $minCel["lada"] . '"/>
                                               <input type="text" class="form-control" 
                                               id="telCelCliente" name="telCelCliente" style="width:70%;" maxlength="8" value="' . $minCel["telefono"] . '"/>
                                    </div>
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
                    <h4>Datos de acceso</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Agregar acceso</label>
                                    <div class="col-sm-8">
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="accesoCliente" id="accesoCliente" value="1" ' . $selNoAcceso . '>
                                                NO
                                            </label>
                                        </div>
                                        <div class="radio">
                                            <label>
                                                <input type="radio" name="accesoCliente" id="accesoCliente" value="2" ' . $selSiAcceso . '>
                                                SI
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="divAcceso" style="' . $styleAcceso . '">
                        <div class="col-sm-3">
                            <div class="form-horizontal">																						
                                <div class="form-group">
                                    <label for="login_usuario" class="col-sm-4 control-label">Usuario:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="login_usuario" name="login_usuario" maxlength="200" value="' . utf8_convertir($cliUser["login"]) . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario" class="col-sm-4 control-label">Contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario" id="pswd_usuario" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario_c" class="col-sm-4 control-label">Confirmar contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario_c" id="pswd_usuario_c" autocomplete="off"/>
                                    </div>
                                </div>
                            </div>
                        </div>									
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoCliente(\'GuardarEdit\');">Guardar</i>
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

function clientes_guardar() {
    $userTrue = true;
    if ($_POST["accesoCliente"] == 2) {
        $newUser = (strtoupper($_POST["login_usuario"]));
        liberar_bd();
        $selectLoginUsuario = "CALL sp_sistema_select_usuario_login('" . $newUser . "');";
        $loginUsuario = consulta($selectLoginUsuario);
        $ctaloginUsuario = cuenta_registros($loginUsuario);
        if ($ctaloginUsuario != 0)
            $userTrue = false;
    }

    if ($userTrue == true) {
//GUARDAMOS AL USUARIO				  
        liberar_bd();
        $insertCliente = " CALL sp_sistema_insert_cliente(	" . $_SESSION['idAgenteActual'] . ",
" . $_POST["tipoCliente"] . ",		
'" . (strtoupper($_POST["nombreCliente"])) . "',
'" . ($_POST["razon"]) . "', 
'" . ($_POST["rfcCliente"]) . "',
" . $_POST["id_ciudad"] . ",
'" . ($_POST["localCliente"]) . "',
" . $_SESSION[$varIdUser] . ");";
        $insert = consulta($insertCliente);

        if ($insert) {
//ULTIMO CLIENTE INSERTADO
            liberar_bd();
            $ultimoClienteId = 'CALL sp_sistema_select_ultimo_clienteId(' . $_SESSION[$varIdUser] . ');';
            $clienteId = consulta($ultimoClienteId);
            $clienId = siguiente_registro($clienteId);

//INSERTAMOS CONTACTO DE AGENDA
            liberar_bd();
            $insertNombreAgenda = 'CALL sp_sistema_insert_cliente_agenda(	' . $_SESSION['idAgenteActual'] . ', 
"' . ($_POST["nombreContactoCliente"]) . '", 
' . $_SESSION[$varIdUser] . ');';
            $insertNombre = consulta($insertNombreAgenda);

//ULTIMO CONTACTO
            liberar_bd();
            $ultimoAgendaId = 'CALL sp_sistema_select_ultimo_agendaId(' . $_SESSION[$varIdUser] . ');';
            $agendaId = consulta($ultimoAgendaId);
            $agenId = siguiente_registro($agendaId);

//INSERTAMOS ASIGNACION USUARIO-AGENDA
            liberar_bd();
            $insertAgendaCliente = 'CALL sp_sistema_insert_agenda_clienteId(' . $clienId["id"] . ', ' . $agenId["id"] . ')';
            $agendaCliente = consulta($insertAgendaCliente);

//INSERTAMOS EL CORREO DEL CONTACTO
            liberar_bd();
            $insertCorreo = 'CALL sp_sistema_insert_correo_contacto(' . $agenId["id"] . ',
"' . (strtolower($_POST["correoCliente"])) . '", 
' . $_SESSION[$varIdUser] . ');';
            $insertCor = consulta($insertCorreo);

//INSERTAMOS EL TELEFONO PERSONAL DEL CONTACTO
            liberar_bd();
            $insertTelefono = 'CALL sp_sistema_insert_telefono_contacto(' . $agenId["id"] . ',
2, 
' . $_POST["ladaCelCliente"] . ', 
' . $_POST["telCelCliente"] . ', 
' . $_SESSION[$varIdUser] . ');';
            $insertTel = consulta($insertTelefono);

//INSERTAMOS EL TELEFONO CELULAR DEL CONTACTO
            liberar_bd();
            $insertCelular = 'CALL sp_sistema_insert_telefono_contacto(' . $agenId["id"] . ',
1, 
' . $_POST["ladaCelCliente"] . ', 
' . $_POST["telCelCliente"] . ', 
' . $_SESSION[$varIdUser] . ');';
            $insertCel = consulta($insertCelular);

            if ($_POST["tipoCliente"] == 1)
                $tipoCat = 1;
            elseif ($_POST["tipoCliente"] == 2)
                $tipoCat = 2;

//INSERTAR LA DIRECCION DEL CONTACTO
            liberar_bd();
            $insertContacto = 'CALL sp_sistema_insert_direccion_contacto(	' . $agenId["id"] . ',
' . $_POST["id_ciudad"] . ', 
' . $tipoCat . ', 
"' . ($_POST["calleCliente"]) . '", 
"' . ($_POST["numExtCliente"]) . '", 
"' . ($_POST["numIntCliente"]) . '", 
"' . ($_POST["coloniaCliente"]) . '", 
"' . ($_POST["cpCliente"]) . '", 
' . $_SESSION[$varIdUser] . ');';
            $update = consulta($insertContacto);


            if ($_POST["accesoCliente"] == 2) {
//INSERTAMOS EL USUARIO		
                liberar_bd();
                $insertUsuarioId = ' CALL sp_sistema_insert_usuario("' . $newUser . '",
"' . (strtoupper($_POST["nombreCliente"])) . '",
md5("' . strtoupper($_POST["pswd_usuario"]) . '"),
3,
' . $_SESSION[$varIdUser] . ');';

                $insertUsuario = consulta($insertUsuarioId);
                if ($insertUsuario) {
//ULTIMO USUARIO INSERTADO
                    liberar_bd();
                    $selectUltimoUser = 'CALL sp_sistema_select_ultimo_usuario(' . $_SESSION[$varIdUser] . ');';
                    $ultimoUsuario = consulta($selectUltimoUser);
                    $ultUser = siguiente_registro($ultimoUsuario);

//ASIGNAMOS USUARIO-CLIENTE
                    liberar_bd();
                    $insertUserCliente = 'CALL sp_sistema_insert_cliente_usuario(	' . $clienId["id"] . ',
' . $ultUser["id"] . ');';
                    $userCli = consulta($insertUserCliente);

                    $res = $msj . clientes_menuInicio();
                } else {
                    $error = 'No se ha podido crear el usuario.';
                    $msj = sistema_mensaje("error", $error);
                    $res = $msj . clientes_formularioNuevo();
                }
            } else {
//ELIMINAMOS SI EXISTE ACCESO
                liberar_bd();
                $deleteAccesoCliente = 'CALL sp_sistema_delete_acceso_cliente(' . $_POST["idCliente"] . ');';
                $deleteAcces = consulta($deleteAccesoCliente);

                liberar_bd();
                $sqlUpdateUsuario = "CALL sp_sistema_delete_usuario('" . $_POST["idUserAcces"] . "');";
                $updateUsuario = consulta($sqlUpdateUsuario);

                if ($updateUsuario) {
                    $res = $msj . clientes_menuInicio();
                } else {
                    $error = 'No se ha podido eliminar usuario.';
                    $msj = sistema_mensaje("error", $error);
                    $res = $msj . clientes_formularioNuevo();
                }
            }
        } else {
            $error = 'No se ha podido guardar el cliente.';
            $msj = sistema_mensaje("error", $error);
            $res = $msj . clientes_formularioNuevo();
        }
    } else {
        $error = 'Ya existe un usuario con este nombre de acceso.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . clientes_formularioNuevo();
    }

    return $res;
}

function clientes_editar() {
    $userTrue = true;
    if ($_POST["accesoCliente"] == 2) {
        $newUser = (strtoupper($_POST["login_usuario"]));
        liberar_bd();
        $selectLoginUsuario = "CALL sp_sistema_select_usuario_loginEditar('" . $newUser . "', " . $_POST["idUserAcces"] . ");";
        $loginUsuario = consulta($selectLoginUsuario);
        $ctaloginUsuario = cuenta_registros($loginUsuario);
        if ($ctaloginUsuario != 0)
            $userTrue = false;
    }

    if ($userTrue == true) {
//EDITAMOS AL USUARIO				  
        liberar_bd();
        $updateCliente = " CALL sp_sistema_update_cliente(	" . $_POST['idCliente'] . ",
" . $_POST["tipoCliente"] . ",		
'" . (strtoupper($_POST["nombreCliente"])) . "',
'" . ($_POST["razon"]) . "', 
'" . ($_POST["rfcCliente"]) . "',
" . $_POST["id_ciudad"] . ",
'" . ($_POST["localCliente"]) . "',
" . $_SESSION[$varIdUser] . ");";
        $update = consulta($updateCliente);

        if ($updateCliente) {
//ACTUALIZAMOS CONTACTO DE AGENDA
            liberar_bd();
            $updateNombreAgenda = 'CALL sp_sistema_update_cliente_agenda(	' . $_POST['idAgente'] . ', 
"' . ($_POST["nombreContactoCliente"]) . '", 
' . $_SESSION[$varIdUser] . ');';
            $updateNombre = consulta($updateNombreAgenda);

//ACTUALIZAMOS EL CORREO DEL CONTACTO
            liberar_bd();
            $updateCorreo = 'CALL sp_sistema_update_correo_contacto(' . $_POST['idCorreo'] . ',
"' . (strtolower($_POST["correoCliente"])) . '", 
' . $_SESSION[$varIdUser] . ');';
            $updateCor = consulta($updateCorreo);

//ACTUALIZAMOS EL TELEFONO PERSONAL DEL CONTACTO
            liberar_bd();
            $updateTelefono = 'CALL sp_sistema_update_telefono_agenda(	' . $_POST['idTel'] . ',
' . $_POST["ladaCelCliente"] . ', 
' . $_POST["telCelCliente"] . ', 
' . $_SESSION[$varIdUser] . ');';
            $updateTel = consulta($updateTelefono);

//ACTUALIZAMOS EL TELEFONO CELULAR DEL CONTACTO
            liberar_bd();
            $updateCelular = 'CALL sp_sistema_update_telefono_agenda(	' . $_POST['idTel'] . ',
' . $_POST["ladaCelCliente"] . ', 
' . $_POST["telCelCliente"] . ', 
' . $_SESSION[$varIdUser] . ');';
            $updateCel = consulta($updateCelular);

            if ($_POST["tipoCliente"] == 1)
                $tipoCat = 1;
            elseif ($_POST["tipoCliente"] == 2)
                $tipoCat = 2;

//INSERTAR LA DIRECCION DEL CONTACTO
            liberar_bd();
            $updateContacto = 'CALL sp_sistema_update_direccion_agenda(	' . $_POST['idTel'] . ',
' . $_POST["id_ciudad"] . ', 
' . $tipoCat . ', 
"' . ($_POST["calleCliente"]) . '", 
"' . ($_POST["numExtCliente"]) . '", 
"' . ($_POST["numIntCliente"]) . '", 
"' . ($_POST["coloniaCliente"]) . '", 
"' . ($_POST["cpCliente"]) . '", 
' . $_SESSION[$varIdUser] . ');';
            $update = consulta($updateContacto);

            if ($_POST["accesoCliente"] == 2) {
//REVISAMOS SI EXISTE USUARIO
                liberar_bd();
                $selectClienteUsuario = 'CALL sp_sistema_select_usuario_cliente(' . $_POST["idCliente"] . ');';
                $clienteUsuario = consulta($selectClienteUsuario);
                $ctaClienteUsuario = cuenta_registros($clienteUsuario);
                if ($ctaClienteUsuario != 0) {
//ACTUALIZAMOS EL EXISTENTE
                    liberar_bd();
                    $update = "	UPDATE 
_usuarios 
SET 
login_usuario = '" . $newUser . "', 
nombre_usuario = '" . utf8_decode($_POST["nombreCliente"]) . "', 
id_usuarioCreate = " . $_SESSION[$varIdUser] . " ";

                    if ($_POST["pswd_usuario"] != "") {
                        $update .= ", password_usuario = '" . md5(strtoupper($_POST["pswd_usuario"])) . "'";
                    }
                    $update .= " WHERE id_usuario = '" . $_POST["idUserAcces"] . "'";
                    $updateUsuario = consulta($update);

                    if ($updateUsuario)
                        $res = $msj . clientes_menuInicio();
                    else {
                        $error = 'No se ha podido actualizar el usuario.';
                        $msj = sistema_mensaje("error", $error);
                        $res = $msj . clientes_menuInicio();
                    }
                } else {
//INSERTAMOS EL USUARIO		
                    liberar_bd();
                    $insertUsuarioId = ' CALL sp_sistema_insert_usuario("' . $newUser . '",
"' . (strtoupper($_POST["nombreCliente"])) . '",
md5("' . strtoupper($_POST["pswd_usuario"]) . '"),
3,
' . $_SESSION[$varIdUser] . ');';

                    $insertUsuario = consulta($insertUsuarioId);
                    if ($insertUsuario) {
//ULTIMO USUARIO INSERTADO
                        liberar_bd();
                        $selectUltimoUser = 'CALL sp_sistema_select_ultimo_usuario(' . $_SESSION[$varIdUser] . ');';
                        $ultimoUsuario = consulta($selectUltimoUser);
                        $ultUser = siguiente_registro($ultimoUsuario);

//ASIGNAMOS USUARIO-CLIENTE
                        liberar_bd();
                        $insertUserCliente = 'CALL sp_sistema_insert_cliente_usuario(' . $_POST["idCliente"] . ',
' . $ultUser["id"] . ');';
                        $userCli = consulta($insertUserCliente);

                        $res = $msj . clientes_menuInicio();
                    } else {
                        $error = 'No se ha podido crear el usuario.';
                        $msj = sistema_mensaje("error", $error);
                        $res = $msj . clientes_menuInicio();
                    }
                }
            } else {
//ELIMINAMOS SI EXISTE ACCESO
                liberar_bd();
                $deleteAccesoCliente = 'CALL sp_sistema_delete_acceso_cliente(' . $_POST["idCliente"] . ');';
                $deleteAcces = consulta($deleteAccesoCliente);
                if ($deleteAcces) {
                    liberar_bd();
                    $sqlUpdateUsuario = "CALL sp_sistema_delete_usuario('" . $_POST["idUserAcces"] . "');";
                    $updateUsuario = consulta($sqlUpdateUsuario);

                    $res = $msj . clientes_menuInicio();
                } else {
                    $error = 'No se ha podido eliminar el usuario.';
                    $msj = sistema_mensaje("error", $error);
                    $res = $msj . clientes_formularioNuevo();
                }
            }
        } else {
            $error = 'No se ha podido guardar el cliente.';
            $msj = sistema_mensaje("error", $error);
            $res = $msj . $pagina;
        }
    } else {
        $error = 'Ya existe un usuario con este nombre de acceso.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . clientes_formularioEditar();
    }

    return $res;
}

function clientes_eliminar() {
    liberar_bd();
    $deleteCliente = "CALL sp_sistema_delete_clientes('" . $_POST["idCliente"] . "');";
    $delete = consulta($deleteCliente);
    if ($delete) {
        $res = $msj . clientes_menuInicio();
    } else {
        $error = 'No se ha podido eliminar el cliente.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . clientes_menuInicio();
    }

    return $res;
}

function clientes_formaContacto() {
    $btnNuevoMedio = false;
    $btnEditaMedio = false;
    $btnEliminaMedio = false;

//PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_convertir($acciones["accion"])) {
            case 'Nuevo medio de contacto':
                $btnNuevoMedio = true;
                break;
            case 'Editar medio de contacto':
                $btnEditaMedio = true;
                break;
            case 'Eliminar medio de contacto':
                $btnEliminaMedio = true;
                break;
        }
    }

    if ($_POST["idCliente"] != '')
        $_SESSION["idContactoActual"] = $_POST["idCliente"];

//NOMBRE DEL CLIENTE
    liberar_bd();
    $selectNombreCliente = 'CALL sp_sistema_select_nombre_clienteId(' . $_SESSION["idContactoActual"] . ');';
    $nombreCliente = consulta($selectNombreCliente);
    $nomCli = siguiente_registro($nombreCliente);

    liberar_bd();
    $selectFormasContacto = '	SELECT cliTipo.id_cliente_tipo_contacto AS id
, tipo.nombre_tipo_contacto AS tipo
, cliTipo.nombre_cliente_tipo_contacto AS nombre
, cliTipo.puesto_cliente_tipo_contacto AS puesto
, cliTipo.valor_cliente_tipo_contacto AS valor
, cliTipo.estatus_cliente_tipo_contacto AS estatus
FROM
cliente_tipo_contacto cliTipo
INNER JOIN tipo_contacto tipo
ON cliTipo.id_tipo_contacto = tipo.id_tipo_contacto
WHERE
cliTipo.id_cliente = ' . $_SESSION["idContactoActual"] . '
AND cliTipo.estatus_cliente_tipo_contacto <> 0';

    $formasContacto = consulta($selectFormasContacto);

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Tablero</a></li> 
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>    
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol>  
    <h1>' . $_SESSION["moduloHijoActual"] . ': <strong>' . utf8_convertir($nomCli["nombre"]) . '</strong></h1>
    <div class="options">
        <div class="btn-toolbar"> 
            <input type="hidden" id="idContacto" name="idContacto" value="" />';
    if ($btnNuevoMedio)
        $pagina.= '	<i title="Nuevo medio de contacto" style="cursor:pointer;" onclick="navegar(\'Agregar\')" class="btn btn-warning" >
                Nuevo medio de contacto
            </i>';
    $pagina.= '			  </div>
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
                                    <th>NOMBRE</th>
                                    <th>PUESTO</th>
                                    <th>TIPO DE CONTACTO</th>
                                    <th>FORMA DE CONTACTO</th>												
                                    <th>ESTATUS</th>
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($for = siguiente_registro($formasContacto)) {
        //ESTATUS DE LA FORMA DE CLIENTE
        switch ($for['estatus']) {
            case 1:
                $estatus = '<span class="tdNegro">ACTIVO</span>';
                break;
            case 2:
                $estatus = '<span class="tdRojo">INACTIVO</span>';
                break;
        }

        $pagina.= ' <tr>
                                <td>' . utf8_convertir($for["nombre"]) . '</td>
                                <td>' . utf8_convertir($for["puesto"]) . '</td>
                                <td>' . utf8_convertir($for["tipo"]) . '</td>
                                <td>' . utf8_convertir($for["valor"]) . '</td>
                                <td>' . $estatus . '</td>
                                <td class="tdAcciones">';
        if ($btnEditaMedio)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idContacto.value = ' . $for["id"] . ';navegar(\'EditarAgregar\');">
                                                   <i title="Editar medio de contacto" class="fa fa-pencil"></i>
                                    </a> ';
        if ($btnEliminaMedio)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if (confirm(\'Desea eliminar esta forma de contacto\')){document.frmSistema.idContacto.value=' . $for["id"] . ';navegar(\'EliminarAgregar\')};">
                                                   <i title="Eliminar medio de contacto" class="fa fa-trash-o"></i>
                                    </a> ';
        $pagina.= '		</td>											
                            </tr>';
    }

    $pagina.= '								</tbody>
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-default btn" onclick="navegar();">Regresar</i>
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

function clientes_formularioNuevoContacto() {
//LISTA FORMAS DE CONTACTO DEL SUBAGENTE
    liberar_bd();
    $selectTiposContacto = 'CALL sp_sistema_lista_tiposContacto_subAgente(' . $_SESSION['idSubAgenteActual'] . ');';
    $tiposContacto = consulta($selectTiposContacto);
    while ($tips = siguiente_registro($tiposContacto)) {
        $optTiposContacto .= '<option value="' . $tips["id"] . '">' . utf8_convertir($tips["nombre"]) . '</option>';
    }

    $pagina = '		<div id="page-heading">	
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
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreContacto" class="col-sm-4 control-label">Nombre del contacto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" maxlength="100"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="puestoContacto" class="col-sm-4 control-label">Puesto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="puestoContacto" name="puestoContacto" maxlength="100"/>
                            </div>
                        </div>													
                        <div class="form-group">
                            <label for="id_tipo" class="col-sm-4 control-label">Forma de contacto:</label>
                            <div class="col-sm-8">
                                <select id="id_tipo" name="id_tipo" style="width:100% !important" class="selectSerch">
                                    <option value="0">Seleccione la forma de contacto</option>
                                    ' . $optTiposContacto . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formaContacto" class="col-sm-4 control-label">Detalle:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="formaContacto" name="formaContacto" maxlength="100"/>
                            </div>
                        </div>						
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Medios de contacto\');">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoMedioContacto(\'GuardarAgregar\');">Guardar</i>
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

function clientes_editarContacto() {
//DATOS DE LA FORMA DE CONTACTO
    liberar_bd();
    $selectDatosFormaContacto = 'CALL sp_sistema_select_datos_formaContacto(' . $_POST["idContacto"] . ');';
    $datosFormaContacto = consulta($selectDatosFormaContacto);
    $forma = siguiente_registro($datosFormaContacto);

//LISTA FORMAS DE CONTACTO DEL SUBAGENTE
    liberar_bd();
    $selectTiposContacto = 'CALL sp_sistema_lista_tiposContacto_subAgente(' . $_SESSION['idSubAgenteActual'] . ');';
    $tiposContacto = consulta($selectTiposContacto);
    while ($tips = siguiente_registro($tiposContacto)) {
        $selectFormCont = '';
        if ($forma["idTipo"] == $tips["id"])
            $selectFormCont = 'selected="selected"';
        $optTiposContacto .= '<option ' . $selectFormCont . ' value="' . $tips["id"] . '">' . utf8_convertir($tips["nombre"]) . '</option>';
    }

    $pagina = '		<div id="page-heading">	
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
            <input type="hidden" id="idContacto" name="idContacto" value="' . $_POST["idContacto"] . '" />
        </div>
    </div>	
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreContacto" class="col-sm-4 control-label">Nombre del contacto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" maxlength="100" value="' . utf8_convertir($forma["nombre"]) . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="puestoContacto" class="col-sm-4 control-label">Puesto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="puestoContacto" name="puestoContacto" maxlength="100" value="' . utf8_convertir($forma["puesto"]) . '"/>
                            </div>
                        </div>													
                        <div class="form-group">
                            <label for="id_tipo" class="col-sm-4 control-label">Forma de contacto:</label>
                            <div class="col-sm-8">
                                <select id="id_tipo" name="id_tipo" style="width:100% !important" class="selectSerch">
                                    <option value="0">Seleccione la forma de contacto</option>
                                    ' . $optTiposContacto . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formaContacto" class="col-sm-4 control-label">Detalle:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="formaContacto" name="formaContacto" maxlength="100" value="' . utf8_convertir($forma["valor"]) . '"/>
                            </div>
                        </div>						
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Medios de contacto\');">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoMedioContacto(\'GuardarEditarAgregar\');">Guardar</i>
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

function clientes_guardarContacto() {
//LISTA FORMAS DE CONTACTO DEL SUBAGENTE
    liberar_bd();
    $selectTiposContacto = 'CALL sp_sistema_lista_tiposContacto_subAgente(' . $_SESSION['idSubAgenteActual'] . ');';
    $tiposContacto = consulta($selectTiposContacto);
    while ($tips = siguiente_registro($tiposContacto)) {
        $selectFormCont = '';
        if ($_POST["id_tipo"] == $tips["id"])
            $selectFormCont = 'selected="selected"';
        $optTiposContacto .= '<option ' . $selectFormCont . ' value="' . $tips["id"] . '">' . utf8_convertir($tips["nombre"]) . '</option>';
    }

    $pagina = '		<div id="page-heading">	
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
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreContacto" class="col-sm-4 control-label">Nombre del contacto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" maxlength="100" value="' . $_POST["nombreContacto"] . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="puestoContacto" class="col-sm-4 control-label">Puesto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="puestoContacto" name="puestoContacto" maxlength="100" value="' . $_POST["puestoContacto"] . '"/>
                            </div>
                        </div>													
                        <div class="form-group">
                            <label for="id_tipo" class="col-sm-4 control-label">Forma de contacto:</label>
                            <div class="col-sm-8">
                                <select id="id_tipo" name="id_tipo" style="width:100% !important" class="selectSerch">
                                    <option value="0">Seleccione la forma de contacto</option>
                                    ' . $optTiposContacto . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formaContacto" class="col-sm-4 control-label">Detalle:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="formaContacto" name="formaContacto" maxlength="100" value="' . $_POST["formaContacto"] . '"/>
                            </div>
                        </div>						
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Medios de contacto\');">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoMedioContacto(\'GuardarAgregar\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    liberar_bd();
    $insertFormaContacto = " CALL sp_sistema_insert_contacto(	" . $_SESSION["idContactoActual"] . ",
'" . ($_POST["nombreContacto"]) . "',
'" . ($_POST["puestoContacto"]) . "', 
" . $_POST["id_tipo"] . ",
'" . ($_POST["formaContacto"]) . "',																	
" . $_SESSION[$varIdUser] . ");";
    $insert = consulta($insertFormaContacto);

    if ($insert) {
        $res = $msj . clientes_formaContacto();
    } else {
        $error = 'No se ha podido guardar la forma de contacto.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . $pagina;
    }

    return $res;
}

function clientes_guardarEditarContacto() {
//LISTA FORMAS DE CONTACTO DEL SUBAGENTE
    liberar_bd();
    $selectTiposContacto = 'CALL sp_sistema_lista_tiposContacto_subAgente(' . $_SESSION['idSubAgenteActual'] . ');';
    $tiposContacto = consulta($selectTiposContacto);
    while ($tips = siguiente_registro($tiposContacto)) {
        $selectFormCont = '';
        if ($_POST["id_tipo"] == $tips["id"])
            $selectFormCont = 'selected="selected"';
        $optTiposContacto .= '<option ' . $selectFormCont . ' value="' . $tips["id"] . '">' . utf8_convertir($tips["nombre"]) . '</option>';
    }

    $pagina = '	<div id="page-heading">	
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
            <input type="hidden" id="idContacto" name="idContacto" value="' . $_POST["idContacto"] . '" />
        </div>
    </div>	
</div>
<div class="container">							
    <div class="row">
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreContacto" class="col-sm-4 control-label">Nombre del contacto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" maxlength="100" value="' . $_POST["nombreContacto"] . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="puestoContacto" class="col-sm-4 control-label">Puesto:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="puestoContacto" name="puestoContacto" maxlength="100" value="' . $_POST["puestoContacto"] . '"/>
                            </div>
                        </div>													
                        <div class="form-group">
                            <label for="id_tipo" class="col-sm-4 control-label">Medio de contacto:</label>
                            <div class="col-sm-8">
                                <select id="id_tipo" name="id_tipo" style="width:100% !important" class="selectSerch">
                                    ' . $optTiposContacto . '
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="formaContacto" class="col-sm-4 control-label">Detalle:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="formaContacto" name="formaContacto" maxlength="100" value="' . $_POST["formaContacto"] . '"/>
                            </div>
                        </div>						
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Medios de contacto\');">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoMedioContacto(\'GuardarEditarAgregar\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    liberar_bd();
    $updateFormaContacto = " CALL sp_sistema_update_formaContacto(	" . $_POST["idContacto"] . ",
'" . ($_POST["nombreContacto"]) . "',
'" . ($_POST["puestoContacto"]) . "', 
" . $_POST["id_tipo"] . ",
'" . ($_POST["formaContacto"]) . "',																	
" . $_SESSION[$varIdUser] . ");";
    $update = consulta($updateFormaContacto);

    if ($update) {
        $res = $msj . clientes_formaContacto();
    } else {
        $error = 'No se ha podido guardar la forma de contacto.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . $pagina;
    }

    return $res;
}

function clientes_eliminarContacto() {
    liberar_bd();
    $deleteFormaContacto = "CALL sp_sistema_delete_formaContacto('" . $_POST["idContacto"] . "');";
    $delete = consulta($deleteFormaContacto);
    if ($delete) {
        /* $error='Se ha eliminado el cliente exitosamente.';
          $msj = sistema_mensaje("exito",$error); */
        $res = $msj . clientes_formaContacto();
    } else {
        $error = 'No se ha podido eliminar la forma de contacto.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . clientes_formaContacto();
    }

    return $res;
}

function clientes_detalles() {
    $_SESSION["idClienteActual"] = $_POST["idCliente"];
//DATOS DEL CLIENTE ACTUAL
    liberar_bd();
    $selectDatosCliente = 'CALL sp_sistema_select_datos_cliente(' . $_SESSION["idClienteActual"] . ');';
    $datosCliente = consulta($selectDatosCliente);
    $cliente = siguiente_registro($datosCliente);

    $ventasCliente = 0;

//VENTAS DEL CLIENTE
    liberar_bd();
    $selectVentasCliente = 'CALL sp_sistema_select_salidas_id_cliente(' . $_SESSION["idClienteActual"] . ');';
    $ventasCliente = consulta($selectVentasCliente);
    $ctaVentasCliente = cuenta_registros($ventasCliente);
    while ($vent = siguiente_registro($ventasCliente)) {
        $listaVentas.= '<tr>
    <td>' . $vent["folio"] . '</td>
    <td>' . $vent["factura"] . '</td>
    <td>' . normalize_date2($vent["fecha"]) . '</td>
    <td>' . format_moneda('$', $vent["total"]) . '</td>
</tr>';

        $ventasCliente = $ventasCliente + $vent["total"];
    }

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>    
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol>        
    <h1>' . $_SESSION["moduloHijoActual"] . '</h1>
    <div class="options">
        <div class="btn-toolbar">
            <input type="hidden" id="idCliente" name="idCliente" readonly="readonly" value="' . $_SESSION["idClienteActual"] . '"/>
        </div>
    </div>										
</div>	
<div class="container">							
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-midnightblue">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <h3><strong>' . utf8_convertir($cliente["nombre"]) . '</strong></h3>
                                    <tbody>
                                        <tr>
                                            <td><strong>Calle</strong></td>
                                            <td>' . utf8_convertir($cliente["calle"]) . ' ' . $cliente["numExt"] . ' ' . $cliente["numInt"] . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Colonia</strong></td>
                                            <td>' . utf8_convertir($cliente["col"]) . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Ciudad</strong></td>
                                            <td>' . utf8_convertir($cliente["ciudad"]) . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Tel&eacute;fono</strong></td>
                                            <td>' . utf8_convertir($cliente["tel"]) . '</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Correo</strong></td>
                                            <td><span class="correos">' . utf8_convertir($cliente["correo"]) . '</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <a class="info-tiles tiles-toyo" href="javascript:;">
                                        <div class="tiles-heading">
                                            <div class="pull-left">Ventas</div>
                                        </div>
                                        <div class="tiles-body">
                                            <div class="pull-right">' . format_moneda('$', $ventasCliente) . '</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <a class="info-tiles tiles-brown" href="javascript:;">
                                        <div class="tiles-heading">
                                            <div class="pull-left">Saldo</div>
                                        </div>
                                        <div class="tiles-body">
                                            <div class="pull-right">' . format_moneda('$', $vent["saldo"]) . '</div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-md-6 col-sm-6 col-lg-6">
                                    <a class="info-tiles tiles-brown" href="javascript:;">
                                        <div class="tiles-heading">
                                            <div class="pull-left">Corizado</div>
                                        </div>
                                        <div class="tiles-body">
                                            <div class="pull-right"></div>
                                        </div>
                                    </a>
                                </div>													                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="tab-container tab-success">
                                <ul class="nav nav-tabs">
                                    <li class="active"><a href="#polizas" data-toggle="tab" onclick="mostrarContenidoCliente(1);">Polizas</a></li>
                                    <li><a href="#pagos" data-toggle="tab" onclick="mostrarContenidoCliente(2);">Pagos</a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="polizas">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="panel panel-danger">
                                                    <div class="panel-body">
                                                        <div class="table-flipscroll">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>FECHA</th>
                                                                        <th>FOLIO</th>
                                                                        <th>CLIENTE</th>
                                                                        <th>TIPO</th>
                                                                        <th>PRIMA ANUAL</th>
                                                                        <th>TIPO DE MONEDA</th>
                                                                        <th>FRECUENCIA DE PAGO</th>
                                                                        <th>FECHA INICIAL</th>
                                                                        <th>FECHA LIMITE</th>
                                                                        <th>ESTATUS</th>								
                                                                    </tr>
                                                                </thead>	
                                                                <tbody>
                                                                    ';
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
                $selectEstatus = ' poli.estatus_poliza = 1 ';
                $selectActivas = ' selected="selected"';
                break;
            case 2:
                $selectEstatus = ' poli.estatus_poliza = 2 ';
                $selectInactivas = ' selected="selected"';
                break;
            case 3:
                $selectEstatus = ' poli.estatus_poliza = 0';
                $selectCanceladas = ' selected="selected"';
                break;
        }
    }
    liberar_bd();
    $selPol = '	
                                                                SELECT     poli.id_poliza            AS id, 
                                                                poli.id_cliente           AS idcliente, 
                                                                clien.nombre_cliente      AS cliente, 
                                                                poli.id_tipo_poliza       AS idtipopoliza, 
                                                                poli.prima_anual_poliza   AS prima, 
                                                                DATE_FORMAT(poli.fechainicial_poliza, "%d-%m-%Y") AS fechaIni,
                                                                DATE_FORMAT(poli.fechafin_poliza, "%d-%m-%Y") AS fechaFin,
                                                                poli.num_poliza           AS num, 
                                                                poli.estatus_poliza       AS estatus, 
                                                                poli.fecha_poliza         AS fecha, 
                                                                tippol.nombre_tipo_poliza AS tipo, 
                                                                poli.tipo_moneda_poliza   AS moneda,
                                                                frec.nombre_frecuencia AS frecuencia
                                                                FROM       poliza                    AS poli 
                                                                INNER JOIN tipo_poliza               AS tippol 
                                                                ON         poli.id_tipo_poliza = tippol.id_tipo_poliza 
                                                                INNER JOIN cliente AS clien 
                                                                ON         poli.id_cliente = clien.id_cliente
                                                                INNER JOIN frecuencia AS frec 
                                                                ON         poli.id_frecuencia = frec.id_frecuencia
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

        $pagina.='
                                                                <tr>
                                                                    <td>' . normalize_date2($poli["fecha"]) . '</td>
                                                                    <td>' . utf8_convertir($poli["num"]) . '</td>
                                                                    <td>' . utf8_convertir($poli["cliente"]) . '</td>
                                                                    <td>' . utf8_convertir($poli["tipo"]) . '</td>
                                                                    <td>' . number_format($poli["prima"], 2) . '</td>
                                                                    <td>' . $moneda . '</td>
                                                                    <td>' . utf8_convertir($poli["frecuencia"]) . '</td>
                                                                    <td>' . ($poli["fechaIni"]) . '</td>
                                                                    <td>' . ($poli["fechaFin"]) . '</td>
                                                                    <td>' . $estatus . '</td>
                                                                </tr>
                                                                ';
    }
    $pagina.='
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>													
                                    </div>	
                                    <div class="tab-pane" id="pagos">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="panel panel-danger">
                                                    <div class="panel-body">
                                                        <div class="table-flipscroll">
                                                            <table class="table">
                                                                <thead>
                                                                    <tr>
                                                                        <th>FECHA</th>
                                                                        <th>CLIENTE</th>
                                                                        <th>TIPO DE POLIZA</th>
                                                                        <th>FOLIO</th>
                                                                        <th>FORMA DE PAGO</th>
                                                                        <th>FRECUENCIA DE PAGO</th>
                                                                        <th>MONTO</th>								
                                                                    </tr>
                                                                </thead>	
                                                                <tbody>';
    $selectPagos = 'CALL sp_sistema_select_pagos();';
    $select = consulta($selectPagos);
    foreach ($select as $pago) {
        $pagina.='
                                                                    <tr>
                                                                        <td>' . normalize_date2($pago["fechaPago"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["nombre"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["tipo"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["folio"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["formaPago"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["frecuencia"]) . '</td>
                                                                        <td>' . utf8_convertir($pago["monto"]) . '</td>
                                                                    </tr>
                                                                    ';
    }
    $pagina.='
                                                                </tbody>
                                                            </table>
                                                        </div>	
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>	
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

    return $pagina;
}

function clientes_documentos() {
    if ($_POST["idCliente"] != '')
        $_SESSION["idContactoActual"] = $_POST["idCliente"];

//NOMBRE DEL CLIENTE
    liberar_bd();
    $selectNombreCliente = 'CALL sp_sistema_select_nombre_clienteId(' . $_SESSION["idContactoActual"] . ');';
    $nombreCliente = consulta($selectNombreCliente);
    $nomCli = siguiente_registro($nombreCliente);

    $btnAltaDoc = false;
    $btnEliminaDoc = false;

//PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_convertir($acciones["accion"])) {
            case 'Nuevo documento':
                $btnAltaDoc = true;
                break;
            case 'Eliminar documento':
                $btnEliminaDoc = true;
                break;
        }
    }

    liberar_bd();
    $selectArchivosCliente = '	SELECT
archCli.id_archivos_cliente AS id,
archCli.nombre_archivos_cliente AS nombre,
archCli.url_archivos_cliente AS url
FROM
archivos_cliente AS archCli
WHERE
archCli.id_cliente = ' . $_SESSION["idContactoActual"] . '
AND archCli.estatus_archivos_cliente <> 0';

    $archivosCliente = consulta($selectArchivosCliente);

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Tablero</a></li> 
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>    
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol>  
    <h1>Documentos de cliente <strong>' . utf8_convertir($nomCli["nombre"]) . '</strong></h1>
    <div class="options">
        <div class="btn-toolbar"> 
            <input type="hidden" id="idArchivo" name="idArchivo" value="" />';
    if ($btnAltaDoc)
        $pagina.= '	<i title="Nuevo documento" style="cursor:pointer;" onclick="navegar(\'Nuevo documento\')" class="btn btn-warning" >
                Nuevo documento
            </i>';
    $pagina.= '				</div>
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
                                    <th>NOMBRE</th>												
                                    <th>ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody>';
    while ($arch = siguiente_registro($archivosCliente)) {
        $pagina.= ' <tr>
                                    <td>' . utf8_convertir($arch["nombre"]) . '</td>
                                    <td class="tdAcciones">
                                        <a class="btn btn-default-alt btn-sm" style="cursor:pointer;" title="Ver recepcion" href="../documentos/' . $arch["url"] . '" download>
                                           <i class="fa fa-file-o"></i>
                                        </a>';
        if ($btnEliminaDoc)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if (confirm(\'Desea eliminar esta documento\')){document.frmSistema.idArchivo.value=' . $arch["id"] . '; navegar(\'EliminarAgregarDoc\');}">
                                                       <i title="Eliminar documento" class="fa fa-trash-o"></i>
                                        </a> ';
        $pagina.= '		</td>												
                                </tr>';
    }

    $pagina.= '								</tbody>												
                        </table>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Regresar</i>
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

function clientes_formularioNuevoDoc() {
    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
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
        <div class="col-sm-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreDoc" class="col-sm-4 control-label">Nombre del documento:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreDoc" name="nombreDoc" maxlength="100"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Adjuntar documento</label>
                            <div class="col-sm-8">
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
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Documentos de cliente\');">Cancelar</i>
                                <i class="btn-primary btn" onclick="nuevoDocumento(\'GuardarAgregarDoc\');">Guardar</i>
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

function clientes_guardarDocumento() {
    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
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
                <div class="panel-heading">
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="nombreDoc" class="col-sm-4 control-label">Nombre del documento:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombreDoc" name="nombreDoc" maxlength="100" value="' . $_POST["nombreDoc"] . '"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-4 control-label">Adjuntar documento</label>
                            <div class="col-sm-8">
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
                </div><div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar(\'Documentos de cliente\');">Cancelar</i>
                                <i class="btn-primary btn" onclick="nuevoDocumento(\'GuardarAgregarDoc\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

//GUARDAMOS EL DOCUMENTO
    if ($_FILES["adjunto"]["name"] != "") {
        if ($_FILES["adjunto"]["error"] == 0) {
            $ext = substr($_FILES["adjunto"]["name"], strrpos($_FILES["adjunto"]["name"], '.'));
            $src = date("YmdHis") . $ext;
            $ruta = "../documentos/" . $src;
            move_uploaded_file($_FILES["adjunto"]["tmp_name"], $ruta);
        }
    } else
        $src = '';

    liberar_bd();
    $insertDocumento = " CALL sp_sistema_insert_documento_cliente(	" . $_SESSION["idContactoActual"] . ",
'" . ($_POST["nombreDoc"]) . "',
'" . $src . "',
" . $_SESSION[$varIdUser] . ");";
    $insert = consulta($insertDocumento);

    if ($insert) {
        $res = $msj . clientes_documentos();
    } else {
        unlink($ruta);
        $error = 'No se ha podido guardar el documento.';
        $msj = sistema_mensaje("error", $error);
        $res = $msj . $pagina;
    }

    return $res;
}

function clientes_eliminarDocumento() {
//DATOS DEL DOCUMENTO
    liberar_bd();
    $selectDatosDocumento = 'CALL sp_sistema_select_datos_documentoCliente(' . $_POST["idArchivo"] . ');';
    $datosDocumento = consulta($selectDatosDocumento);
    $doc = siguiente_registro($datosDocumento);
    $rutaAnterior = "../documentos/" . $doc["url"];

    liberar_bd();
    $deleteDocumentoCliente = "CALL sp_sistema_delete_documentoCliente('" . $_POST["idArchivo"] . "');";
    $delete = consulta($deleteDocumentoCliente);
    if ($delete) {
        unlink($rutaAnterior);
        $res = $msj . clientes_documentos();
    } else {
        $error = 'No se ha podido eliminar el documento.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . clientes_documentos();
    }

    return $res;
}

function clientes_asiganrCliente() {
    if ($_POST["idCliente"] != '')
        $_SESSION["idContactoActual"] = $_POST["idCliente"];

//SUBAGENTE ACTUAL DEL CLIENTE
    liberar_bd();
    $selectDatosCliente = 'CALL sp_sistema_select_datos_cliente(' . $_SESSION["idContactoActual"] . ');';
    $datosCliente = consulta($selectDatosCliente);
    $datCli = siguiente_registro($datosCliente);

//LISTA DE SUBAGENTES DEL AGENTE
    liberar_bd();
    $selectListaSubAgente = 'CALL sp_sistema_select_lista_subagentes_agenteId(' . $_SESSION['idAgenteActual'] . ');';
    $listaSubAgente = consulta($selectListaSubAgente);
    while ($sub = siguiente_registro($listaSubAgente)) {
        if ($sub["id"] != $datCli["subAgen"])
            $optSubAgen .= '<option value="' . $sub["id"] . '">' . utf8_convertir($sub["nombre"]) . '</option>';
    }

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>    
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol> 
    <h1>' . $_SESSION["moduloHijoActual"] . ': <strong>' . utf8_convertir($datCli["nombre"]) . '</strong></h1>       
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
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="id_estado" class="col-sm-5 control-label">Subagente a asignar cliente:</label>
                            <div class="col-sm-7">
                                <select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
                                    <option value="0">Seleccione un subagente a asignar cliente</option>
                                    ' . $optSubAgen . '
                                </select>
                            </div>
                        </div>				
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-primary btn" onclick="guardarAsignacion(\'Guardar asignación\');">Guardar</i>
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

function clientes_guardarAsignacion() {
//SUBAGENTE ACTUAL DEL CLIENTE
    liberar_bd();
    $selectDatosCliente = 'CALL sp_sistema_select_datos_cliente(' . $_SESSION["idContactoActual"] . ');';
    $datosCliente = consulta($selectDatosCliente);
    $datCli = siguiente_registro($datosCliente);

//LISTA DE SUBAGENTES DEL AGENTE
    liberar_bd();
    $selectListaSubAgente = 'CALL sp_sistema_select_lista_subagentes_agenteId(' . $_SESSION['idAgenteActual'] . ');';
    $listaSubAgente = consulta($selectListaSubAgente);
    while ($sub = siguiente_registro($listaSubAgente)) {
        if ($sub["id"] != $datCli["subAgen"])
            $optSubAgen .= '<option value="' . $sub["id"] . '">' . utf8_convertir($sub["nombre"]) . '</option>';
    }

    $pagina = '	<div id="page-heading">	
    <ol class="breadcrumb">
        <li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
        <li><a href="javascript:navegar_modulo(' . $_SESSION["mod"] . ');">' . $_SESSION["moduloPadreActual"] . '</a></li>    
        <li class="active">
            ' . $_SESSION["moduloHijoActual"] . '
        </li>
    </ol> 
    <h1>' . $_SESSION["moduloHijoActual"] . ': <strong>' . utf8_convertir($datCli["nombre"]) . '</strong></h1>       
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
                    <h4></h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="form-horizontal">
                        <div class="form-group">
                            <label for="id_estado" class="col-sm-5 control-label">Subagente a asignar cliente:</label>
                            <div class="col-sm-7">
                                <select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
                                    <option value="0">Seleccione un subagente a asignar cliente</option>
                                    ' . $optSubAgen . '
                                </select>
                            </div>
                        </div>				
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-primary btn" onclick="guardarAsignacion(\'Guardar asignación\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

//ACTUALIZAMOS AL CLIENTE
    liberar_bd();
    $updateAsignacionCliente = 'CALL sp_sistema_update_asignacion_cliente(' . $_SESSION["idContactoActual"] . ', ' . $_POST["idSubAgente"] . ', ' . $_SESSION[$varIdUser] . ');';
    $update = consulta($updateAsignacionCliente);
    if ($update) {
        $res = $msj . clientes_documentos();
    } else {
        $error = 'No se ha podido reasignar al cliente.';
        $msj = sistema_mensaje("exito", $error);
        $res = $msj . $pagina;
    }

    return $res;
}

function clientes_guardar_error() {
//LISTA DE ESTADOS
    liberar_bd();
    $selectEstados = 'CALL sp_sistema_lista_estados();';
    $estados = consulta($selectEstados);
    while ($est = siguiente_registro($estados)) {
        $selecEdo = '';
        if ($est["id"] == 12)
            $selecEdo = 'selected="selected"';
        $optEstados .= '<option ' . $selecEdo . ' value="' . $est["id"] . '">' . utf8_convertir($est["nombre"]) . '</option>';
    }

//LISTA DE CIUDADES
    liberar_bd();
    $selectCiudades = 'CALL sp_sistema_lista_ciudades_edoId(12);';
    $ciudades = consulta($selectCiudades);
    while ($ciu = siguiente_registro($ciudades)) {
        $selecMpo = '';
        if ($ciu["id"] == 462)
            $selecMpo = 'selected="selected"';
        $optCiudades .= '<option ' . $selecMpo . ' value="' . $ciu["id"] . '">' . utf8_convertir($ciu["nombre"]) . '</option>';
    }

    $pagina = '	<div id="page-heading">	
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
                <div class="panel-heading">
                    <h4>Nuevo cliente</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreCliente" class="col-sm-4 control-label">Nombre comercial:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreCliente" name="nombreCliente" maxlength="100" value="' . $_POST["nombreCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="razon" class="col-sm-4 control-label">Razón social:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="razon" name="razon" maxlength="100" value="' . $_POST["razon"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="rfcCliente" class="col-sm-4 control-label">RFC:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="rfcCliente" name="rfcCliente" maxlength="13" value="' . $_POST["rfcCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="calleCliente" class="col-sm-4 control-label">Calle:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="calleCliente" name="calleCliente" maxlength="100" value="' . $_POST["calleCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numExtCliente" class="col-sm-4 control-label">Num Ext:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numExtCliente" name="numExtCliente" maxlength="100" value="' . $_POST["numExtCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="numIntCliente" class="col-sm-4 control-label">Num Int:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="numIntCliente" name="numIntCliente" maxlength="100" value="' . $_POST["numIntCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="coloniaCliente" class="col-sm-4 control-label">Colonia:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="coloniaCliente" name="coloniaCliente" maxlength="100" value="' . $_POST["coloniaCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="cpCliente" class="col-sm-4 control-label">CP:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="cpCliente" name="cpCliente" maxlength="5" value="' . $_POST["cpCliente"] . '"/>
                                    </div>
                                </div>	
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="id_estado" class="col-sm-4 control-label">Estado:</label>
                                    <div class="col-sm-8">
                                        <select id="id_estado" name="id_estado" style="width:100% !important" class="selectSerch">
                                            ' . $optEstados . '
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="id_ciudad" class="col-sm-4 control-label">Ciudad:</label>
                                    <div class="col-sm-8">
                                        <span id="city_spn" >
                                            <select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">
                                                ' . $optCiudades . '
                                            </select>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="localCliente" class="col-sm-4 control-label">Localidad:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="localCliente" name="localCliente" maxlength="100" value="' . $_POST["localCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="nombreContactoCliente" class="col-sm-4 control-label">Nombre de contacto:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="nombreContactoCliente" name="nombreContactoCliente" maxlength="100" value="' . $_POST["nombreContactoCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="correoCliente" class="col-sm-4 control-label">Correo:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control frmCorreo" 
                                               id="correoCliente" name="correoCliente" maxlength="255" value="' . $_POST["correoCliente"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">
                                <div class="form-group">
                                    <label for="ladaCliente" class="col-sm-4 control-label">Tel&eacute;fono:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" 
                                               id="ladaCliente" name="ladaCliente" style="width:30%; float:left;" maxlength="3" 
                                               value="' . $_POST["ladaCliente"] . '"/>
                                               <input type="text" class="form-control" 
                                               id="telCliente" name="telCliente" style="width:70%;" maxlength="8" value="' . $_POST["telCliente"] . '"/>
                                    </div>
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
                    <h4>Datos de acceso</h4>
                </div>
                <div class="panel-body" style="border-radius: 0px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-horizontal">																						
                                <div class="form-group">
                                    <label for="login_usuario" class="col-sm-4 control-label">Usuario:</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" id="login_usuario" name="login_usuario" maxlength="200" value="' . $_POST["login_usuario"] . '"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario" class="col-sm-4 control-label">Contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario" id="pswd_usuario" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-horizontal">	
                                <div class="form-group">
                                    <label for="pswd_usuario_c" class="col-sm-4 control-label">Confirmar contrase&ntilde;a:</label>
                                    <div class="col-sm-8">
                                        <input type="password" class="form-control" maxlength="200" name="pswd_usuario_c" id="pswd_usuario_c" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                        </div>									
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="btn-toolbar btnsGuarCan">
                                <i class="btn-danger btn" onclick="navegar();">Cancelar</i>
                                <i class="btn-success btn" onclick="nuevoCliente(\'Guardar\');">Guardar</i>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>
        </div>
    </div>
</div>';
}
