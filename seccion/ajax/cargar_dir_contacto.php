<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectDirContacto = 'CALL sp_sistema_select_direccion_contacto('.$_POST["idDir"].');';	
	$dirContacto = consulta($selectDirContacto);
	$contacto = siguiente_registro($dirContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>