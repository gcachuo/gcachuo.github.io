<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	include_once("../../sistema/motor/globales.php");
	conectarSistema();
	
	$fechaIniEvento = normalize_date_complete2($_POST["fechaIniEvento"]);
	$fechaFinEvento = normalize_date_complete2($_POST["fechaFinEvento"]);
	
	
	//GUARDAMOS EN CALENDARIO
	liberar_bd();
	$insertEventoCalendario = 'CALL sp_sistema_insert_evento_calendario( "'.($_POST["tituloEvento"]).'",
																		 "'.$fechaIniEvento.'",
																		 "'.$fechaFinEvento.'",
																		 "'.($_POST["urlEvento"]).'",
																		 "#85C744",
																		 '.$_SESSION[$varIdUser].');';
	$eventoCalendario = consulta($insertEventoCalendario);
	
	//echo $insertEventoCalendario;
?>