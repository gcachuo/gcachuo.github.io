<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//INSERTAMOS EL CORREO DEL CONTACTO
	liberar_bd();
	$insertContacto = 'CALL sp_sistema_insert_correo_contacto(	'.$_SESSION["idContactoActual"].',
																"'.(strtolower($_POST["correo"])).'", 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($insertContacto);
?>