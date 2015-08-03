<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	$nombreAgenda = ($_POST["nombreAgenda"]);
	
	liberar_bd();
	$insertNombreAgenda = 'CALL sp_sistema_insert_nombre_agenda('.$_SESSION['idAgenteActual'].', "'.$nombreAgenda.'", '.$_SESSION[$varIdUser].');';	
	$insertNombre = consulta($insertNombreAgenda);
?>