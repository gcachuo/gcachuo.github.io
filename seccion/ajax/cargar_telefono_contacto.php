<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectTelefonoContacto = 'CALL sp_sistema_select_telefono_contacto('.$_POST["idTelefono"].');';	
	$telefonoContacto = consulta($selectTelefonoContacto);
	$contacto = siguiente_registro($telefonoContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>