<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS EL NOMBRE DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_nombre_contacto(	'.$_SESSION["idContactoActual"].',
																"'.($_POST["prefijo"]).'",
																"'.($_POST["nombre"]).'",
																"'.($_POST["segNombre"]).'",
																"'.($_POST["apellido"]).'",
																"'.($_POST["sufijo"]).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>