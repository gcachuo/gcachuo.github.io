<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newCalle"] != "")
		$newCalle = ($_POST["newCalle"]);
	else
		$newCalle = "Calle";
			
	//EDITAMOS CALLE DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_calle_perfil("'.$newCalle.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>