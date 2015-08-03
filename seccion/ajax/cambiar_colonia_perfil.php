<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newColonia"] != "")
		$newColonia = ($_POST["newColonia"]);
	else
		$newColonia = "Colonia";
			
	//EDITAMOS COLONIA DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_colonia_perfil("'.$newColonia.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>