<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//EDITAMOS LA DIRECCION DEL CONTACTO
	liberar_bd();
	$updateContacto = 'CALL sp_sistema_update_direccion_contacto(	'.$_POST["idDireccion"].',
																	'.$_POST["id_ciudad"].', 
																	'.$_POST["idCategoria"].', 
																	"'.($_POST["calle"]).'", 
																	"'.($_POST["numExt"]).'", 
																	"'.($_POST["numInt"]).'", 
																	"'.($_POST["colonia"]).'", 
																	"'.($_POST["cp"]).'", 
																	'.$_SESSION[$varIdUser].');';
	$update = consulta($updateContacto);
?>