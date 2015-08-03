<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newNumExt"] != "")
		$newNumExt = ($_POST["newNumExt"]);
	else
		$newNumExt = "Num Ext";
			
	//EDITAMOS NUM EXT DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_numExt_perfil("'.$newNumExt.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>