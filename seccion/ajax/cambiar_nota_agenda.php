<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	include_once("../../sistema/motor/globales.php");
	conectarSistema();
	
	//EDITAMOS LA NOTA DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_nota_contacto(	'.$_SESSION["idContactoActual"].',
																"'.($_POST["txtAgenda"]).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>