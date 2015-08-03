<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS EL TELEFONO DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_telefono_contacto('.$_POST["idTelefono"].',
																'.$_POST["tiTel"].', 
																'.$_POST["lada"].', 
																'.$_POST["telefono"].', 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>