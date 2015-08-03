<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newPass"] != "")
	{
		$newPass = md5(strtoupper($_POST["newPass"]));
				
		//EDITAMOS LA CONTRASEÑA DEL PERFIL
		liberar_bd();
		$updatePerfil = 'CALL sp_sistema_update_contrasenia_perfil("'.$newPass.'", '.$_SESSION[$varIdUser].');';
		$update = consulta($updatePerfil);
	}
		
?>