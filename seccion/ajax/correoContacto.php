<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//LISTA DE CORREOS
	liberar_bd();
	$selectListaCorreos = 'CALL sp_sistema_select_correos_agenda_id('.$_SESSION["idContactoActual"].');';
	$listaCorreos = consulta($selectListaCorreos);
	$ctaListaCorreso = cuenta_registros($listaCorreos);
	if($ctaListaCorreso != 0)
	{
		while($listCor = siguiente_registro($listaCorreos))
		{
			$optListaCorreos.= '<a class="frmCorreo" href="#myModalCorreo" id="bootbox-demo-5" data-toggle="modal" onclick="editarCorreo('.$listCor["id"].')">'.utf8_convertir($listCor["correo"]).'</a><br>';
		}
	}
	
	echo $optListaCorreos.'<a class="btn btn-default btn-xs" href="#myModalNewCorreo" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade una dirección de correo electrónico.</a>';	
?>