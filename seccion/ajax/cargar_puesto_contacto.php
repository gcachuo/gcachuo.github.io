<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectPuestoContacto = 'CALL sp_sistema_select_puesto_contacto('.$_SESSION["idContactoActual"].');';	
	$puestoContacto = consulta($selectPuestoContacto);
	$contacto = siguiente_registro($puestoContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>