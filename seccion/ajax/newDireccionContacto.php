<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//INSERTAR LA DIRECCION DEL CONTACTO
	liberar_bd();
	$insertContacto = 'CALL sp_sistema_insert_direccion_contacto(	'.$_SESSION["idContactoActual"].',
																	'.$_POST["id_ciudad"].', 
																	'.$_POST["idCategoria"].', 
																	"'.($_POST["calle"]).'", 
																	"'.($_POST["numExt"]).'", 
																	"'.($_POST["numInt"]).'", 
																	"'.($_POST["colonia"]).'", 
																	"'.($_POST["cp"]).'", 
																	'.$_SESSION[$varIdUser].');';
	$update = consulta($insertContacto);
?>