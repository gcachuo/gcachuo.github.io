<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newCorreo"] != "")
		$newCorreo = (strtolower($_POST["newCorreo"]));
	else
		$newCorreo = "Correo";
			
	//EDITAMOS EL CORREO DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_correo_perfil("'.$newCorreo.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>