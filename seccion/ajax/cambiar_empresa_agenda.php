<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS LA EMPRESA DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_empresa_contacto(	'.$_SESSION["idContactoActual"].',
																"'.($_POST["empresa"]).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>