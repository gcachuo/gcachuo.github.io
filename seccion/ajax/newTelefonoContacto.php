<?php 
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//INSERTAMOS EL TELEFONO DEL CONTACTO
	liberar_bd();
	$insertContacto = 'CALL sp_sistema_insert_telefono_contacto('.$_SESSION["idContactoActual"].',
																'.$_POST["tiTel"].', 
																'.$_POST["lada"].', 
																'.$_POST["telefono"].', 
																'.$_SESSION[$varIdUser].');';
	$update = consulta($insertContacto);
	//echo $insertContacto;
?>