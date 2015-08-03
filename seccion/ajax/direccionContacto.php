<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//LISTA DE DIRECCIONES
	liberar_bd();
	$selectListaDirecciones = 'CALL sp_sistema_select_direcciones_agenda_id('.$_SESSION["idContactoActual"].');';
	$listaDirecciones = consulta($selectListaDirecciones);
	$ctaListaDirecciones = cuenta_registros($listaDirecciones);
	if($ctaListaDirecciones != 0)
	{
		while($listDir = siguiente_registro($listaDirecciones))
		{
			$direccion = utf8_convertir($listDir["calle"]).' '.utf8_convertir($listDir["numExt"]).' '.utf8_convertir($listDir["numInt"]).' '.utf8_convertir($listDir["colonia"]).' '.utf8_convertir($listDir["cp"]).' '.utf8_convertir($listDir["ciudad"]).', '.utf8_convertir($listDir["estado"]);
			$optListaDirecciones.= '<tr>
										<td class="tdTituloAgenda">'.utf8_convertir($listDir["categoria"]).':</td>
										<td>
											<a href="#myModalDireccion" id="bootbox-demo-5" data-toggle="modal" onclick="editarDireccion('.$listDir["id"].')">'.$direccion.'</a>
										</td>											
									</tr>';
		}
	}
	
	echo $optListaDirecciones.'	<tr>
									<td></td>
									<td><a class="btn btn-default btn-xs" href="#myModalNewDireccion" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade una direcci&oacute;n.</a></td>
								</tr>';
?>