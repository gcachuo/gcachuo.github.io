<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	include_once("../../sistema/motor/globales.php");
	conectarSistema();
	
	//EDITAMOS LA FECHA DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_fecha_contacto(	'.$_SESSION["idContactoActual"].',
																"'.normalize_date2($_POST["fecha"]).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>