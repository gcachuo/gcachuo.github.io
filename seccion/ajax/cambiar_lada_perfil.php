<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newLada"] != "")
		$newLada = $_POST["newLada"];
	else
		$newLada = 477;
			
	//EDITAMOS LA LADA DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_lada_perfil("'.$newLada.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>