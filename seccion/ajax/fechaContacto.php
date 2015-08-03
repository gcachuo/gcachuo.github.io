<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	include_once("../../sistema/motor/globales.php");
	conectarSistema();
	
	liberar_bd();
	$selectFechaContacto = 'CALL sp_sistema_select_fecha_contacto('.$_SESSION["idContactoActual"].');';	
	$fechaContacto = consulta($selectFechaContacto);
	$contacto = siguiente_registro($fechaContacto);
	
	$fechContact = '<a href="#myModalFecha" id="bootbox-demo-5" data-toggle="modal" onclick="editarFecha()">'.normalize_date($contacto["fecha"]).'</a>';	
					
	echo $fechContact;	
?>