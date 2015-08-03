<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectCorreoContacto = 'CALL sp_sistema_select_correo_contacto('.$_POST["idCorreo"].');';	
	$correoContacto = consulta($selectCorreoContacto);
	$contacto = siguiente_registro($correoContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>