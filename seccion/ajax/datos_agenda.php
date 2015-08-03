<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//DATOS DE LA ULTIMA AGENDA
	liberar_bd();
	$selectDatosAgenda = 'CALL sp_sistema_select_ultima_agendaId( '.$_SESSION[$varIdUser].');';	
	$datosAgenda = consulta($selectDatosAgenda);
	$agen = siguiente_registro($datosAgenda);
	$encoded_rows = array_map('utf8_convertir', $agen);
	echo json_encode($encoded_rows);
	
?>