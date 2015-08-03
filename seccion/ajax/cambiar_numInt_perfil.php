<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newNumInt"] != "")
		$newNumInt = ($_POST["newNumInt"]);
	else
		$newNumInt = "Num Int";
			
	//EDITAMOS NUM INT DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_numInt_perfil("'.$newNumInt.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>