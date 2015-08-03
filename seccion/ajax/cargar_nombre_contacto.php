<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectNombreContacto = 'CALL sp_sistema_select_nombre_contacto('.$_SESSION["idContactoActual"].');';	
	$nombreContacto = consulta($selectNombreContacto);
	$contacto = siguiente_registro($nombreContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>