<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newFecha"] != "")
		$newFecha = $_POST["newFecha"];
	else
		$newFecha = "0000-00-00";
			
	//EDITAMOS LA LADA DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_fecha_perfil("'.$newFecha.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>