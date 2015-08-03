<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectPuestoContacto = 'CALL sp_sistema_select_puesto_contacto('.$_SESSION["idContactoActual"].');';	
	$puestoContacto = consulta($selectPuestoContacto);
	$contacto = siguiente_registro($puestoContacto);
	
	$puestContact = '<a href="#myModalPuesto" id="bootbox-demo-5" data-toggle="modal" onclick="editarPuesto()">'.utf8_convertir($contacto["puesto"]).'</a>';	
					
	echo $puestContact;	
?>