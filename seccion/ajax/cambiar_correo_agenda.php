<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS EL CORREO DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_correo_contacto(	'.$_POST["idCorreo"].',
																"'.(strtolower($_POST["correo"])).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>