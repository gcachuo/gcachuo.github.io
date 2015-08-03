<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectFechaContacto = 'CALL sp_sistema_select_fecha_contacto('.$_POST["idAgenda"].');';	
	$fechaContacto = consulta($selectFechaContacto);
	$contacto = siguiente_registro($fechaContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>