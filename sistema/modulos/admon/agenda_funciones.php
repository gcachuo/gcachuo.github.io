<?php

function agenda_menuInicio() {
    $btnAlta = false;
    $btnEdita = false;
    $btnElimina = false;
//PREMISOS DE ACCIONES
    liberar_bd();
    $selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo(' . $_SESSION["idPerfil"] . ', ' . $_SESSION["mod"] . ');';
    $permisosAcciones = consulta($selectPermisosAcciones);
    while ($acciones = siguiente_registro($permisosAcciones)) {
        switch (utf8_convertir($acciones["accion"])) {
            case 'Alta':
                $btnAlta = true;
                break;
            case 'Ver detalles':
                $btnDetalles = true;
                break;
            case 'Modificación':
                $btnEdita = true;
                break;
            case 'Eliminación':
                $btnElimina = true;
                break;
        }
    }

//LISTA DE CONTACTOS DE AGENDA
    liberar_bd();
    $selectContactos = 'SELECT
agen.id_agenda AS id,
agen.prefijo_agenda AS prefijo,
agen.nombre_agenda AS nombre,
agen.segundo_nombre_agenda AS segNombre,
agen.apellido_agenda AS apellido,
agen.sufijo_agenda AS sufijo,
agen.opcion_contacto AS opcion,
agen.otro_agenda AS otro
FROM
agenda AS agen
WHERE
agen.id_agente = ' . $_SESSION['idAgenteActual'] . '
AND agen.estatus_agenda = 1';

    $contactos = consulta($selectContactos);

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
            <input type="hidden" id="idAgenda" name="idAgenda" readonly="readonly"/>													
            <input type="hidden" name="txtIndice" />';
    if ($btnAlta)
        $pagina.= '	<i title="Nuevo contacto" style="cursor:pointer;" href="#myModalAgenda" id="bootbox-demo-5" data-toggle="modal" class="btn btn-warning" >
                Nuevo contacto
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
                        <a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
                    </div>
                </div>
                <div class="panel-body collapse in">
                    <div class="table-responsive">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered " id="example">
                            <thead>
                                <tr>
                                    <th>NOMBRE</th>
                                    <th>CORREO</th>
                                    <th>TEL&Eacute;FONO</th>
                                    <th>DIRECCIÓN</th>
                                    <th>CLIENTE/EMPRESA</th>
                                    <th>PROVEEDOR</th>
                                    <th>OTRO</th>
                                    <th class="thAcciones">ACCIONES</th>
                                </tr>
                            </thead>	
                            <tbody id="divListaContacto">';
    while ($cont = siguiente_registro($contactos)) {
        $cliente = '';
        $proveedor = '';
        $contacto = '';

        switch ($cont["opcion"]) {
            case 1:
                //CLIENTE
                liberar_bd();
                $selectContactoCliente = 'CALL sp_sistema_select_cliente_contacto(' . $cont["id"] . ');';
                $contactoCliente = consulta($selectContactoCliente);
                $conCli = siguiente_registro($contactoCliente);
                $cliente = utf8_convertir($conCli["nombre"]);
                break;
            case 2:
                //PROVEEDOR
                liberar_bd();
                $selectContactoProveedor = 'CALL sp_sistema_select_proveedor_contacto(' . $cont["id"] . ');';
                $contactoProveedor = consulta($selectContactoProveedor);
                $conProv = siguiente_registro($contactoProveedor);
                $proveedor = utf8_convertir($conProv["nombre"]);
                break;
            case 3:
                $contacto = utf8_convertir($cont["otro"]);
                break;
        }

        //CHECAMOS CORREO
        liberar_bd();
        $selectCorreoContacto = 'CALL sp_sistema_select_lista_correo_agenda_id(' . $cont["id"] . ');';
        $correoContacto = consulta($selectCorreoContacto);
        $ctaCorreoContacto = cuenta_registros($correoContacto);
        if ($ctaCorreoContacto > 1) {
            mysqli_data_seek($correoContacto, 0);
            $correoCon = siguiente_registro($correoContacto);
            $ctaCorreo = $ctaCorreoContacto - 1;
            $correoPrincipal = $correoCon["nombre"] . '(+' . $ctaCorreo . ')';
        } else {
            if ($ctaCorreoContacto == 1) {
                $correoCon = siguiente_registro($correoContacto);
                $ctaCorreo = $ctaCorreoContacto;
                $correoPrincipal = $correoCon["nombre"];
            } else {
                $correoPrincipal = '';
            }
        }

        //CHECAMOS TELEFONO
        liberar_bd();
        $selectTelefonoContacto = 'CALL sp_sistema_select_lista_telefono_agenda_id(' . $cont["id"] . ');';
        $telefonoContacto = consulta($selectTelefonoContacto);
        $ctaTelefonoContacto = cuenta_registros($telefonoContacto);
        if ($ctaTelefonoContacto > 1) {
            mysqli_data_seek($telefonoContacto, 0);
            $telefonoCon = siguiente_registro($telefonoContacto);
            $ctaTelefono = $ctaTelefonoContacto - 1;
            $telefonoPrincipal = $telefonoCon["telefono"] . '(+' . $ctaTelefono . ')';
        } else {
            if ($ctaTelefonoContacto == 1) {
                $telefonoCon = siguiente_registro($telefonoContacto);
                $ctaTelefono = $ctaTelefonoContacto;
                $telefonoPrincipal = $telefonoCon["telefono"];
            } else {
                $telefonoPrincipal = '';
            }
        }

        //CHECAMOS DIRECCION
        liberar_bd();
        $selectDireccionContacto = 'CALL sp_sistema_select_lista_direccion_agenda_id(' . $cont["id"] . ');';
        $direccionContacto = consulta($selectDireccionContacto);
        $ctaDireccionContacto = cuenta_registros($direccionContacto);
        if ($ctaDireccionContacto > 1) {
            mysqli_data_seek($direccionContacto, 0);
            $direccionCon = siguiente_registro($direccionContacto);
            $ctaDireccion = $ctaDireccionContacto - 1;
            $direccionPrincipal = utf8_convertir($direccionCon["direccion"]) . '(+' . $ctaDireccion . ')';
        } else {
            if ($ctaDireccionContacto == 1) {
                $direccionCon = siguiente_registro($direccionContacto);
                $ctaDireccion = $ctaDireccionContacto;
                $direccionPrincipal = utf8_convertir($direccionCon["direccion"]);
            } else
                $direccionPrincipal = '';
        }

        if ($cont["prefijo"] == "-")
            $prefijo = "";
        else
            $prefijo = utf8_convertir($cont["prefijo"]);

        if ($cont["segNombre"] == "-")
            $segundoNombre = "";
        else
            $segundoNombre = utf8_convertir($cont["segNombre"]);

        if ($cont["apellido"] == "-")
            $apellido = "";
        else
            $apellido = utf8_convertir($cont["apellido"]);

        if ($cont["sufijo"] == "-")
            $sufijo = "";
        else
            $sufijo = utf8_convertir($cont["sufijo"]);

        $nombreContacto = $prefijo . ' ' . utf8_convertir($cont["nombre"]) . ' ' . $segundoNombre . ' ' . $apellido . ' ' . $sufijo;


        $pagina.= ' <tr>
                                    <td>' . $nombreContacto . '</td>
                                    <td class="frmCorreo">' . $correoPrincipal . '</td>
                                    <td>' . $telefonoPrincipal . '</td>																	
                                    <td>' . $direccionPrincipal . '</td>
                                    <td>' . $cliente . '</td>
                                    <td>' . $proveedor . '</td>
                                    <td>' . $contacto . '</td>
                                    <td class="tdAcciones">';
        if ($btnEdita)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idAgenda.value = ' . $cont["id"] . ';navegar(\'Editar\');">
                                                       <i title="Editar" style="cursor:pointer;" class="fa fa-pencil"></i>
                                        </a>';
        if ($btnDetalles)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idAgenda.value = ' . $cont["id"] . ';navegar(\'Detalles\');">
                                                       <i title="Ver detalles" style="cursor:pointer;" class="fa fa fa-eye"></i>
                                        </a>';
        if ($btnElimina)
            $pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if (confirm(\'Desea eliminar este contacto de la agenda\')){document.frmSistema.idAgenda.value=' . $cont["id"] . '; navegar(\'Eliminar\');}">	
                                                       <i title="Eliminar" style="cursor:pointer;" class="fa fa-trash-o"></i>	
                                        </a>';
        $pagina.= '	  	</td>											
                                </tr>';
    }

    $pagina.= '	</tbody>																					
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalAgenda" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo contacto</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="nombreCliente" class="col-sm-4 control-label">Nombre:</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control" id="nombreAgenda" name="nombreAgenda" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnGuardarAgenda" onclick="guardarAgenda()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>';

    return $pagina;
}

function agenda_editar() {
    $_SESSION["idContactoActual"] = $_POST["idAgenda"];
//DATOS DE CONTACTO
    liberar_bd();
    $selectDatosContacto = 'CALL sp_sistema_select_datos_contacto(' . $_SESSION["idContactoActual"] . ')';
    $datosContacto = consulta($selectDatosContacto);
    $con = siguiente_registro($datosContacto);

    if ($con["prefijo"] == "-")
        $prefijo = "";
    else
        $prefijo = utf8_convertir($con["prefijo"]);

    if ($con["segNombre"] == "-")
        $segundoNombre = "";
    else
        $segundoNombre = utf8_convertir($con["segNombre"]);

    if ($con["apellido"] == "-")
        $apellido = "";
    else
        $apellido = utf8_convertir($con["apellido"]);

    if ($con["sufijo"] == "-")
        $sufijo = "";
    else
        $sufijo = utf8_convertir($con["sufijo"]);

    $nombreContacto = $prefijo . ' ' . utf8_convertir($con["nombre"]) . ' ' . $segundoNombre . ' ' . $apellido . ' ' . $sufijo;

    $opcion = '';
    if ($con["opcion"] == 1) {
        $optionCliente = 'checked = "checked"';
        $spnConAgenda = '<span id="spnOpcion">Cliente:</span>';
        $styleListaOpcion = 'display:block;';
        $styleOpcion = 'display:none;';
        $styleDivServicio = 'display:block;';
        $selTipo = 'Selecccione cliente';
//CLIENTE DEL CONTACTO
        liberar_bd();
        $selectClienteContacto = 'CALL sp_sistema_select_cliente_contacto(' . $_SESSION["idContactoActual"] . ');';
        $clienteContacto = consulta($selectClienteContacto);
        $cliCon = siguiente_registro($clienteContacto);

//LISTA DE CLIENTES
        liberar_bd();
        $selectClientes = 'CALL sp_sistema_select_lista_clientes()';
        $clientes = consulta($selectClientes);
        while ($cli = siguiente_registro($clientes)) {
            if ($cliCon["id"] == $cli["id"])
                $selCli = 'selected="selected"';
            else
                $selCli = '';
            $listaClientes.= '<option ' . $selCli . ' value="' . $cli["id"] . '">' . utf8_convertir($cli["nombre"]) . '</option>';
        }
    }
    elseif ($con["opcion"] == 3) {
        $optionOtro = 'checked = "checked"';
        $opcion = utf8_convertir($_POST["nombreOpcion"]);
        $spnConAgenda = '<span id="spnOpcion">Capture nombre:</span>';
        $styleListaOpcion = 'display:none;';
        $styleOpcion = 'display:block;';
        $txtNombreOpcion = utf8_convertir($contacto["otro"]);
    }

//LISTA DE CORREOS
    liberar_bd();
    $selectListaCorreos = 'CALL sp_sistema_select_correos_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaCorreos = consulta($selectListaCorreos);
    $ctaListaCorreso = cuenta_registros($listaCorreos);
    $spnNewCorreo = '<a class="btn btn-default btn-xs" href="#myModalNewCorreo" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade una dirección de correo electrónico.</a>';
    if ($ctaListaCorreso != 0) {
        while ($listCor = siguiente_registro($listaCorreos)) {
            $optListaCorreos.= '<a class="frmCorreo" href="#myModalCorreo" id="bootbox-demo-5" data-toggle="modal" onclick="editarCorreo(' . $listCor["id"] . ')">' . utf8_convertir($listCor["correo"]) . '</a><br>';
        }
    }

//LISTA DE TELEFONO
    liberar_bd();
    $selectListaTelefonos = 'CALL sp_sistema_select_telefonos_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaTelefonos = consulta($selectListaTelefonos);
    $ctaListaTelefonos = cuenta_registros($listaTelefonos);
    $spnNewTelefono = '	<tr>
    <td></td>
    <td><a class="btn btn-default btn-xs" href="#myModalNewTelefono" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade un tel&eacute;fono.</a></td>
</tr>';
    if ($ctaListaTelefonos != 0) {
        while ($listTel = siguiente_registro($listaTelefonos)) {
            $optListaTelefonos.= '	<tr>
    <td class="tdTituloAgenda">' . utf8_convertir($listTel["categoria"]) . ':</td>
    <td>
        <a href="#myModalTelefono" id="bootbox-demo-5" data-toggle="modal" onclick="editarTelefono(' . $listTel["id"] . ')">' . utf8_convertir($listTel["lada"]) . '-' . utf8_convertir($listTel["telefono"]) . '</a>
    </td>											
</tr>';
        }
    }

//LISTA DE DIRECCIONES
    liberar_bd();
    $selectListaDirecciones = 'CALL sp_sistema_select_direcciones_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaDirecciones = consulta($selectListaDirecciones);
    $ctaListaDirecciones = cuenta_registros($listaDirecciones);
    $spnNewDireccion = '	<tr>
    <td></td>
    <td><a class="btn btn-default btn-xs" href="#myModalNewDireccion" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade una direcci&oacute;n.</a></td>
</tr>';
    if ($ctaListaDirecciones != 0) {
        while ($listDir = siguiente_registro($listaDirecciones)) {
            $direccion = utf8_convertir($listDir["calle"]) . ' ' . utf8_convertir($listDir["numExt"]) . ' ' . utf8_convertir($listDir["numInt"]) . ' ' . utf8_convertir($listDir["colonia"]) . ' ' . utf8_convertir($listDir["cp"]) . ' ' . utf8_convertir($listDir["ciudad"]) . ', ' . utf8_convertir($listDir["estado"]);
            $optListaDirecciones.= '<tr>
    <td class="tdTituloAgenda">' . utf8_convertir($listDir["categoria"]) . ':</td>
    <td>
        <a href="#myModalDireccion" id="bootbox-demo-5" data-toggle="modal" onclick="editarDireccion(' . $listDir["id"] . ')">' . $direccion . '</a>
    </td>											
</tr>';
        }
    }

//LISTA DE TIPOS DE TELEFONO
    liberar_bd();
    $selectTiposTelefonos = 'CALL sp_sistema_select_lista_tipos_telefono()';
    $tiposTelefono = consulta($selectTiposTelefonos);
    while ($tiTel = siguiente_registro($tiposTelefono)) {
        $listaTiposTelefonos.= '<option value="' . $tiTel["id"] . '">' . utf8_convertir($tiTel["nombre"]) . '</option>';
    }

//LISTA DE TIPOS DE DIRECCIONES
    liberar_bd();
    $selectTiposDirecciones = 'CALL sp_sistema_select_lista_tipos_direcciones()';
    $tiposDirecciones = consulta($selectTiposDirecciones);
    while ($tirDir = siguiente_registro($tiposDirecciones)) {
        $listaTiposDirecciones.= '<option value="' . $tirDir["id"] . '">' . utf8_convertir($tirDir["nombre"]) . '</option>';
    }

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
        <input type="hidden" id="idAgenda" name="idAgenda" value="' . $_SESSION["idContactoActual"] . '">
               <div class="btn-toolbar">
            <i class="btn-danger btn" onclick="navegar();">Regresar</i>
        </div>							
    </div>										
</div>		
<div class="container">						
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <img src="imagenes/contactos/default.jpg" alt="" class="pull-left" style="margin: 0 20px 20px 0">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <h3 id="h3Nombre">
                                        <strong>
                                            <a href="#myModalNombre" id="bootbox-demo-5" data-toggle="modal" onclick="editNomAgenda()">' . $nombreContacto . '</a>
                                        </strong>
                                    </h3>
                                    <tbody>
                                        <tr>
                                            <td class="tdTituloAgenda">Puesto:</td>
                                            <td id="h3Puesto">
                                                <a href="#myModalPuesto" id="bootbox-demo-5" data-toggle="modal" onclick="editarPuesto()">' . utf8_convertir($con["puesto"]) . '</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tdTituloAgenda">Empresa:</td>
                                            <td id="h3Empresa">
                                                <a href="#myModalEmpresa" id="bootbox-demo-5" data-toggle="modal" onclick="editarEmpresa()">' . utf8_convertir($con["empresa"]) . '</a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="tdTituloAgenda">Correo:</td>	
                                            <td id="h3Corre">															
                                                ' . $optListaCorreos . '
                                                ' . $spnNewCorreo . '
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Tel&eacute;fonos:
                                    </h4>
                                    <tbody id="h3Telefono">
                                        ' . $optListaTelefonos . '
                                        ' . $spnNewTelefono . '
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Direcci&oacute;nes:
                                    </h4>
                                    <tbody id="h3Direccion">
                                        ' . $optListaDirecciones . '
                                        ' . $spnNewDireccion . '
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Extras:
                                    </h4>
                                    <tbody>
                                        <tr>
                                            <td class="tdTituloAgenda">Fecha de nacimiento:</td>
                                            <td id="h3Fecha">
                                                <a href="#myModalFecha" id="bootbox-demo-5" data-toggle="modal" onclick="editarFecha()">' . normalize_date($con["cumple"]) . '</a>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>	
                        </div>
                        <div class="col-md-3">
                            <h3>Nota</h3>
                            <textarea name="txtAgenda" id="txtAgenda" class="form-control autosize">' . utf8_convertir($con["txt"]) . '</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="myModalNombre" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar nombre</h4>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="prefijoContacto" class="col-sm-3 control-label">Prefijo:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="prefijoContacto" name="prefijoContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="nombreContacto" class="col-sm-3 control-label">Nombre:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="nombreContacto" name="nombreContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="segNombreContacto" class="col-sm-3 control-label">Segundo nombre:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="segNombreContacto" name="segNombreContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="apellidoContacto" class="col-sm-3 control-label">Apellidos:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="apellidoContacto" name="apellidoContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="sufijoContacto" class="col-sm-3 control-label">Sufijo:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="sufijoContacto" name="sufijoContacto" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnNombreContacto" onclick="guardarNombreContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="myModalPuesto" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar puesto</h4>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="puestoContacto" class="col-sm-3 control-label">Puesto:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="puestoContacto" name="puestoContacto" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnPuestoContacto" onclick="guardarPuestoContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="myModalEmpresa" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar empresa</h4>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="empresaContacto" class="col-sm-3 control-label">Empresa:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="empresaContacto" name="empresaContacto" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnEmpresaContacto" onclick="guardarEmpresaContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="myModalNewCorreo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo correo</h4>
                <input readonly="readonly" type="hidden" id="idCorreo" name="idCorreo" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="newCorreoContacto" class="col-sm-3 control-label">Correo:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control frmCorreo" id="newCorreoContacto" name="newCorreoContacto" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnCorreoContacto" onclick="guardarNewCorreoContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>	
<div class="modal fade" id="myModalCorreo" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar correo</h4>
                <input readonly="readonly" type="hidden" id="idCorreo" name="idCorreo" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="correoContacto" class="col-sm-3 control-label">Correo:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control frmCorreo" id="correoContacto" name="correoContacto" maxlength="100"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnCorreoContacto" onclick="guardarCorreoContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="myModalNewTelefono" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nuevo tel&eacute;fono</h4>
                <input readonly="readonly" type="hidden" id="idTelefono" name="idTelefono" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="newTipTelContacto" class="col-sm-3 control-label">Tipo:</label>
                                <div class="col-sm-6">
                                    <select id="newTipTelContacto" name="newTipTelContacto" style="width:100% !important" class="selectSerch">
                                        <option value="0">Tipo de tel&eacute;fono</option>
                                        ' . $listaTiposTelefonos . '
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="newLadaContacto" class="col-sm-3 control-label">Lada:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newLadaContacto" name="newLadaContacto" maxlength="3"/>
                                </div>
                            </div>	
                            <div class="form-group">
                                <label for="newTelefonoContacto" class="col-sm-3 control-label">Tel&eacute;fono:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newTelefonoContacto" name="newTelefonoContacto" maxlength="7"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnTelefonoContacto" onclick="guardarNewTelefonoContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>		
<div class="modal fade" id="myModalTelefono" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar tel&eacute;fono</h4>
                <input readonly="readonly" type="hidden" id="idTelefono" name="idTelefono" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="tipTelContacto" class="col-sm-3 control-label">Tipo:</label>
                                <div class="col-sm-6">
                                    <select id="tipTelContacto" name="tipTelContacto" style="width:100% !important" class="selectSerch">
                                        <option value="0">Tipo de tel&eacute;fono</option>
                                        ' . $listaTiposTelefonos . '
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="ladaContacto" class="col-sm-3 control-label">Lada:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="ladaContacto" name="ladaContacto" maxlength="3"/>
                                </div>
                            </div>	
                            <div class="form-group">
                                <label for="telefonoContacto" class="col-sm-3 control-label">Tel&eacute;fono:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="telefonoContacto" name="telefonoContacto" maxlength="7"/>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnTelefonoContacto" onclick="guardarTelefonoContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>
<div class="modal fade" id="myModalNewDireccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Nueva direcci&oacute;n</h4>
                <input readonly="readonly" type="hidden" id="idDireccion" name="idDireccion" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="newTipDirContacto" class="col-sm-3 control-label">Tipo:</label>
                                <div class="col-sm-6">
                                    <select id="newTipDirContacto" name="newTipDirContacto" style="width:100% !important" class="selectSerch">
                                        <option value="0">Tipo de direcci&oacute;n</option>
                                        ' . $listaTiposDirecciones . '
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="newCalleContacto" class="col-sm-3 control-label">Calle:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newCalleContacto" name="newCalleContacto" maxlength="255"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newNumExtContacto" class="col-sm-3 control-label">N&uacute;m Ext.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newNumExtContacto" name="newNumExtContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newNumIntContacto" class="col-sm-3 control-label">N&uacute;m Int.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newNumIntContacto" name="newNumIntContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newColoniaContacto" class="col-sm-3 control-label">Colonia:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newColoniaContacto" name="newColoniaContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newCPContacto" class="col-sm-3 control-label">CP.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="newCPContacto" name="newCPContacto" maxlength="5"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="newId_estado" class="col-sm-3 control-label">Estado:</label>
                                <div class="col-sm-6">
                                    <select id="newId_estado" name="newId_estado" style="width:100% !important" class="selectSerch">
                                        ' . $optEstados . '
                                    </select>
                                </div>
                            </div>
                            <div class="form-group" id="formCiudad">
                                <label for="newId_ciudad" class="col-sm-3 control-label">Ciudad:</label>
                                <div class="col-sm-6">
                                    <span id="city_spn" >
                                        <select id="newId_ciudad" name="newId_ciudad" style="width:100% !important" class="selectSerch">
                                            ' . $optCiudades . '
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnDirContacto" onclick="guardarNewDirContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>		
<div class="modal fade" id="myModalDireccion" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Modificar direcci&oacute;n</h4>
                <input readonly="readonly" type="hidden" id="idDireccion" name="idDireccion" maxlength="100"/>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label for="tipDirContacto" class="col-sm-3 control-label">Tipo:</label>
                                <div class="col-sm-6">
                                    <select id="tipDirContacto" name="tipDirContacto" style="width:100% !important" class="selectSerch">
                                        <option value="0">Tipo de direcci&oacute;n</option>
                                        ' . $listaTiposDirecciones . '
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label for="calleContacto" class="col-sm-3 control-label">Calle:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="calleContacto" name="calleContacto" maxlength="255"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="numExtContacto" class="col-sm-3 control-label">N&uacute;m Ext.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="numExtContacto" name="numExtContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="numIntContacto" class="col-sm-3 control-label">N&uacute;m Int.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="numIntContacto" name="numIntContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="coloniaContacto" class="col-sm-3 control-label">Colonia:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="coloniaContacto" name="coloniaContacto" maxlength="100"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="cpContacto" class="col-sm-3 control-label">CP.:</label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="cpContacto" name="cpContacto" maxlength="5"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_estado" class="col-sm-3 control-label">Estado:</label>
                                <div class="col-sm-6">
                                    <select id="id_estado" name="id_estado" style="width:100% !important" class="selectSerch">
                                        ' . $optEstados . '
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="id_ciudad" class="col-sm-3 control-label">Ciudad:</label>
                                <div class="col-sm-6">
                                    <span id="city_spn" >
                                        <select id="id_ciudad" name="id_ciudad" style="width:100% !important" class="selectSerch">
                                            ' . $optCiudades . '
                                        </select>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnDirContacto" onclick="guardarDirContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>	
<div class="modal fade" id="myModalFecha" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Cumplea&ntilde;os</h4>
            </div>
            <div class="modal-body">										
                <div class="row">
                    <div class="col-sm-12">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Fecha de cumplea&ntilde;os:</label>
                                <div class="col-sm-6">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input readonly="readonly" data-date-format="dd-mm-yyyy" type="text" class="form-control" id="datepicker" name="datepicker"/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>											
                </div>																		
            </div>
            <div class="modal-footer">									
                <i class="btn-danger btn" data-dismiss="modal">Cancelar</i>
                <i class="btn-success btn" data-dismiss="modal" id="btnDirContacto" onclick="guardarFechaContacto()">Guardar</i> 
            </div>
        </div>
    </div>						
</div>										
<div id="div_articulos"></div>';

    return $pagina;
}

function agenda_detalles() {
    $_SESSION["idContactoActual"] = $_POST["idAgenda"];
//DATOS DE CONTACTO
    liberar_bd();
    $selectDatosContacto = 'CALL sp_sistema_select_datos_contacto(' . $_SESSION["idContactoActual"] . ')';
    $datosContacto = consulta($selectDatosContacto);
    $con = siguiente_registro($datosContacto);

    if ($con["prefijo"] == "-")
        $prefijo = "";
    else
        $prefijo = utf8_convertir($con["prefijo"]);

    if ($con["segNombre"] == "-")
        $segundoNombre = "";
    else
        $segundoNombre = utf8_convertir($con["segNombre"]);

    if ($con["apellido"] == "-")
        $apellido = "";
    else
        $apellido = utf8_convertir($con["apellido"]);

    if ($con["sufijo"] == "-")
        $sufijo = "";
    else
        $sufijo = utf8_convertir($con["sufijo"]);

    $nombreContacto = $prefijo . ' ' . utf8_convertir($con["nombre"]) . ' ' . $segundoNombre . ' ' . $apellido . ' ' . $sufijo;

    $opcion = '';
    if ($con["opcion"] == 1) {
        $optionCliente = 'checked = "checked"';
        $spnConAgenda = '<span id="spnOpcion">Cliente:</span>';
        $styleListaOpcion = 'display:block;';
        $styleOpcion = 'display:none;';
        $styleDivServicio = 'display:block;';
        $selTipo = 'Selecccione cliente';
//CLIENTE DEL CONTACTO
        liberar_bd();
        $selectClienteContacto = 'CALL sp_sistema_select_cliente_contacto(' . $_SESSION["idContactoActual"] . ');';
        $clienteContacto = consulta($selectClienteContacto);
        $cliCon = siguiente_registro($clienteContacto);

//LISTA DE CLIENTES
        liberar_bd();
        $selectClientes = 'CALL sp_sistema_select_lista_clientes()';
        $clientes = consulta($selectClientes);
        while ($cli = siguiente_registro($clientes)) {
            if ($cliCon["id"] == $cli["id"])
                $selCli = 'selected="selected"';
            else
                $selCli = '';
            $listaClientes.= '<option ' . $selCli . ' value="' . $cli["id"] . '">' . utf8_convertir($cli["nombre"]) . '</option>';
        }
    }
    elseif ($con["opcion"] == 3) {
        $optionOtro = 'checked = "checked"';
        $opcion = utf8_convertir($_POST["nombreOpcion"]);
        $spnConAgenda = '<span id="spnOpcion">Capture nombre:</span>';
        $styleListaOpcion = 'display:none;';
        $styleOpcion = 'display:block;';
        $txtNombreOpcion = utf8_convertir($contacto["otro"]);
    }

//LISTA DE CORREOS
    liberar_bd();
    $selectListaCorreos = 'CALL sp_sistema_select_correos_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaCorreos = consulta($selectListaCorreos);
    $ctaListaCorreso = cuenta_registros($listaCorreos);
    $spnNewCorreo = '<a class="btn btn-default btn-xs" href="#myModalNewCorreo" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade una dirección de correo electrónico.</a>';
    if ($ctaListaCorreso != 0) {
        while ($listCor = siguiente_registro($listaCorreos)) {
            $optListaCorreos.= utf8_convertir($listCor["correo"]) . '<br>';
        }
    }

//LISTA DE TELEFONO
    liberar_bd();
    $selectListaTelefonos = 'CALL sp_sistema_select_telefonos_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaTelefonos = consulta($selectListaTelefonos);
    $ctaListaTelefonos = cuenta_registros($listaTelefonos);
    if ($ctaListaTelefonos != 0) {
        while ($listTel = siguiente_registro($listaTelefonos)) {
            $optListaTelefonos.= '	<tr>
    <td class="tdTituloAgenda">' . utf8_convertir($listTel["categoria"]) . ':</td>
    <td>' . utf8_convertir($listTel["lada"]) . '-' . utf8_convertir($listTel["telefono"]) . '</td>											
</tr>';
        }
    }

//LISTA DE DIRECCIONES
    liberar_bd();
    $selectListaDirecciones = 'CALL sp_sistema_select_direcciones_agenda_id(' . $_SESSION["idContactoActual"] . ');';
    $listaDirecciones = consulta($selectListaDirecciones);
    $ctaListaDirecciones = cuenta_registros($listaDirecciones);
    if ($ctaListaDirecciones != 0) {
        while ($listDir = siguiente_registro($listaDirecciones)) {
            $direccion = utf8_convertir($listDir["calle"]) . ' ' . utf8_convertir($listDir["numExt"]) . ' ' . utf8_convertir($listDir["numInt"]) . ' ' . utf8_convertir($listDir["colonia"]) . ' ' . utf8_convertir($listDir["cp"]) . ' ' . utf8_convertir($listDir["ciudad"]) . ', ' . utf8_convertir($listDir["estado"]);
            $optListaDirecciones.= '<tr>
    <td class="tdTituloAgenda">' . utf8_convertir($listDir["categoria"]) . ':</td>
    <td>' . $direccion . '</td>											
</tr>';
        }
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
        <input type="hidden" id="idAgenda" name="idAgenda" value="' . $_SESSION["idContactoActual"] . '">
               <div class="btn-toolbar">
            <i class="btn-danger btn" onclick="navegar();">Regresar</i>
        </div>							
    </div>										
</div>		
<div class="container">						
    <div class="row">
        <div class="col-sm-12">
            <div class="panel panel-danger">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-9">
                            <img src="imagenes/contactos/default.jpg" alt="" class="pull-left" style="margin: 0 20px 20px 0">
                            <div class="table-responsive">
                                <table class="table table-condensed">
                                    <h3>
                                        <strong>' . $nombreContacto . '</strong>
                                    </h3>
                                    <tbody>
                                        <tr>
                                            <td class="tdTituloAgenda">Puesto:</td>
                                            <td>' . utf8_convertir($con["puesto"]) . '</td>
                                        </tr>
                                        <tr>
                                            <td class="tdTituloAgenda">Empresa:</td>
                                            <td>' . utf8_convertir($con["empresa"]) . '</td>
                                        </tr>
                                        <tr>
                                            <td class="tdTituloAgenda">Correo:</td>	
                                            <td>' . $optListaCorreos . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Tel&eacute;fonos:
                                    </h4>
                                    <tbody>
                                        ' . $optListaTelefonos . '
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Direcci&oacute;nes:
                                    </h4>
                                    <tbody>
                                        ' . $optListaDirecciones . '
                                    </tbody>
                                </table>
                                <hr>
                                <table class="table table-condensed">
                                    <h4>
                                        Extras:
                                    </h4>
                                    <tbody>
                                        <tr>
                                            <td class="tdTituloAgenda">Fecha de nacimiento:</td>
                                            <td>' . normalize_date($con["cumple"]) . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>	
                        </div>
                        <div class="col-md-3">
                            <h3>Nota</h3>
                            <textarea class="form-control autosize" readonly>' . utf8_convertir($con["txt"]) . '</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';

    return $pagina;
}

function agenda_eliminar() {
//ELIMINAMOS EL CONTACTO DE LA AGENDA
    liberar_bd();
    $deleteContacto = 'CALL sp_sistema_delete_contacto_agenda(	' . $_POST["idAgenda"] . ',
' . $_SESSION[$varIdUser] . ');';
    $delete = consulta($deleteContacto);

    if ($delete) {
        $pagina = agenda_menuInicio();
    } else {
        $error = 'No se han podido eliminar al contacto de la agenda.';
        $msj = sistema_mensaje("error", $error);
        $pagina = agenda_menuInicio();
    }

    return $msj . $pagina;
}
