<?php
	session_start();
	error_reporting(E_ERROR);
	include_once("../../sistema/motor/conexionSitio.php");
	conectarSistema();
	
	//LISTA DE TELEFONOS
	liberar_bd();
	$selectListaTelefonos = 'CALL sp_sistema_select_telefonos_agenda_id('.$_SESSION["idContactoActual"].');';
	$listaTelefonos = consulta($selectListaTelefonos);
	$ctaListaTelefonos = cuenta_registros($listaTelefonos);
	if($ctaListaTelefonos != 0)
	{
		while($listTel = siguiente_registro($listaTelefonos))
		{
			$optListaTelefonos.= '	<tr>
										<td class="tdTituloAgenda">'.utf8_convertir($listTel["categoria"]).':</td>
										<td>
											<a href="#myModalTelefono" id="bootbox-demo-5" data-toggle="modal" onclick="editarTelefono('.$listTel["id"].')">'.utf8_convertir($listTel["lada"]).'-'.utf8_convertir($listTel["telefono"]).'</a>
										</td>											
									</tr>';
		}
	}
	
	echo $optListaTelefonos.'<tr>
								<td></td>
								<td><a class="btn btn-default btn-xs" href="#myModalNewTelefono" id="bootbox-demo-5" data-toggle="modal">A&ntilde;ade un tel&eacute;fono.</a></td>
							</tr>';
?>