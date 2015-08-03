<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newCP"] != "")
		$newCP = $_POST["newCP"];
	else
		$newCP = "37000";
			
	//EDITAMOS CP DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_cp_perfil("'.$newCP.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>