<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	if($_POST["newTel"] != "")
		$newTel = $_POST["newTel"];
	else
		$newTel = 7123456;
			
	//EDITAMOS EL TELEFONO DEL PERFIL
	liberar_bd();
	$updatePerfil = 'CALL sp_sistema_update_telefono_perfil("'.$newTel.'", '.$_SESSION[$varIdUser].');';
	$update = consulta($updatePerfil);
		
?>