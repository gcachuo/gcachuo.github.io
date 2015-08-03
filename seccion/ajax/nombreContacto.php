<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	liberar_bd();
	$selectNombreContacto = 'CALL sp_sistema_select_nombre_contacto('.$_SESSION["idContactoActual"].');';	
	$nombreContacto = consulta($selectNombreContacto);
	$contacto = siguiente_registro($nombreContacto);
	
	if($contacto["prefijo"] == "-")
			$prefijo = "";
	else
		$prefijo = utf8_convertir($contacto["prefijo"]);
	
	if($contacto["segNombre"] == "-")
		$segundoNombre = "";
	else
		$segundoNombre = utf8_convertir($contacto["segNombre"]);
	
	if($contacto["apellido"] == "-")
		$apellido = "";
	else
		$apellido = utf8_convertir($contacto["apellido"]);
	
	if($contacto["sufijo"] == "-")
		$sufijo = "";
	else
		$sufijo = utf8_convertir($contacto["sufijo"]);
		
	$nombreContacto = $prefijo.' '.utf8_convertir($contacto["nombre"]).' '.$segundoNombre.' '.$apellido.' '.$sufijo;
	
	$nomContact = '	<strong>
						<a href="#myModalNombre" id="bootbox-demo-5" data-toggle="modal" onclick="editNomAgenda()">'.$nombreContacto.'</a>
					</strong>';	
					
	echo $nomContact;	
?>