<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS EL PUESTO DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_puesto_contacto(	'.$_SESSION["idContactoActual"].',
																"'.($_POST["puesto"]).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>