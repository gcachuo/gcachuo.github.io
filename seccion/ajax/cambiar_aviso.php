<?php
session_start();
error_reporting(E_ERROR);
include_once("../../sistema/motor/conexionSitio.php");
conectarSistema();

//EDITAMOS EL AVISO
liberar_bd();
$updateAviso = 'CALL sp_sistema_update_aviso( "' . ($_POST["descripcion_aviso"]) . '", ' . $_POST["cantidad_aviso"] . ',1);';
$update = consulta($updateAviso);
if (!$update) {
   "ERROR AL EJECUTAR".$updateAviso;
}
        