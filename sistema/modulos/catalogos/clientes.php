<?php

session_start();
include_once('clientes_funciones.php');
//DATOS DEL MODULO
liberar_bd();
$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo(' . $_SESSION["mod"] . ');';
$datosModulo = consulta($selectDatosModulo);
$datMod = siguiente_registro($datosModulo);
$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
switch ($_POST['accion']) {
    case 'Nuevo':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_formularioNuevo();
        break;

    case 'Editar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_formularioEditar();
        break;

    case 'Guardar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_guardar();
        break;

    case 'GuardarEdit':
        $modulo .= clientes_editar();
        break;

    case 'Eliminar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo.=clientes_eliminar();
        break;

    case 'Medios de contacto':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_formaContacto();
        break;

    case 'Agregar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_formularioNuevoContacto();
        break;

    case 'EditarAgregar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_editarContacto();
        break;

    case 'GuardarAgregar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_guardarContacto();
        break;

    case 'GuardarEditarAgregar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_guardarEditarContacto();
        break;

    case 'EliminarAgregar':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo.= clientes_eliminarContacto();
        break;

    case 'Documentos de cliente':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_documentos();
        break;

    case 'Nuevo documento':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_formularioNuevoDoc();
        break;

    case 'GuardarAgregarDoc':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_guardarDocumento();
        break;

    case 'EliminarAgregarDoc':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo.= clientes_eliminarDocumento();
        break;

    case 'Detalles':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_detalles();
        break;

    case 'Asignar cliente':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_asiganrCliente();
        break;

    case 'Guardar asignación':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_guardarAsignacion();
        break;
    case 'Kardex':
        $_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
        $modulo .= clientes_kardex();
        break;
    default:
        $modulo .= clientes_menuInicio();
        break;
}
