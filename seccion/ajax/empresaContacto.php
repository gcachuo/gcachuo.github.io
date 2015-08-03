<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectEmpresaContacto = 'CALL sp_sistema_select_empresa_contacto('.$_SESSION["idContactoActual"].');';	
	$empresaContacto = consulta($selectEmpresaContacto);
	$contacto = siguiente_registro($empresaContacto);
	
	$empreContact = '<a href="#myModalEmpresa" id="bootbox-demo-5" data-toggle="modal" onclick="editarEmpresa()">'.utf8_convertir($contacto["empresa"]).'</a>';	
					
	echo $empreContact;	
?>