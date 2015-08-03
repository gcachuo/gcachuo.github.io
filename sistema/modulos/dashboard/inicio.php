<?php

session_start();
include_once('inicio_funciones.php');
switch ($_POST['accion']) {
    case 'Recordatorios':
        $modulo .= inicio_recordatorios();
        break;

    case 'Enviar':
        $modulo .= inicio_recordatoriosEnviar();
        break;
    case 'EnviarCorreo':
        $modulo .= inicio_enviarCorreo();
        break;
    case 'EnviarR':
        $modulo .= inicio_menuInicio();
        break;
    case 'Guardar':
        $modulo .= inicio_guardar();
        break;
    default:
        $modulo .= inicio_menuInicio();
        $regresar = '';
        break;
}