<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectEmpresaContacto = 'CALL sp_sistema_select_empresa_contacto('.$_SESSION["idContactoActual"].');';	
	$empresaContacto = consulta($selectEmpresaContacto);
	$contacto = siguiente_registro($empresaContacto);
	$encoded_rows = array_map('utf8_convertir', $contacto);
	echo json_encode($encoded_rows);
?>