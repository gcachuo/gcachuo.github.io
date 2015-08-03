<?php

session_start();
include_once('avisosPagos_funciones.php');

$accion = $_POST['accion'];

//DATOS DEL MODULO
liberar_bd();
$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo(' . $_SESSION["mod"] . ');';
$datosModulo = consulta($selectDatosModulo);
$datMod = siguiente_registro($datosModulo);
$_SESSION["moduloPadreActual"] = utf8_encode($datMod["nombre"]);

switch ($accion) {
    case 'Editar':
        $modulo .= avisosPagos_editar();
        break;
    case 'Guardar':
        $_SESSION["moduloHijoActual"] = utf8_encode($_POST['accion']);
        $modulo .= avisosPagos_guardar();
        break;

    case 'GuardarEditar':
        $_SESSION["moduloHijoActual"] = utf8_encode($_POST['accion']);
        $modulo .= avisosPagos_editar();
        break;

    default:
        $modulo .= avisosPagos_menuInicio();
        break;
}
	
