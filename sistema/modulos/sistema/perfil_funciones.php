<?php

function perfiles_menuInicio()
{
	$btnAlta = false;
	$btnEdita = false;
	$btnElimina = false;
	$btnPermisos = false;
	
	//PREMISOS DE ACCIONES
	liberar_bd();
	$selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo('.$_SESSION["idPerfil"].', '.$_SESSION["mod"].');';
	$permisosAcciones = consulta($selectPermisosAcciones);
	while($acciones = siguiente_registro($permisosAcciones))
	{
		switch(utf8_convertir($acciones["accion"]))
		{
			case 'Alta':
				$btnAlta = true;					
			break;				
			case 'Modificación':
				$btnEdita = true;					
			break;
			case 'Permisos de tablero':
				$btnPermisos = true;					
			break;
			case 'Eliminación':
				$btnElimina = true;
			break;
		}
	}
	
	//DATOS USUARIO
	liberar_bd();
	$selectDatUsuario='CALL sp_sistema_select_datos_usuario('.$_SESSION[$varIdUser].');';
	$datUsuario = consulta($selectDatUsuario);	
	$ctaDatUsuario  =cuenta_registros($datUsuario);
	if($ctaDatUsuario == 0)
	{
		//INSERTAMOS NUEVOS DATOS DEFAULT USUARIO
		liberar_bd();
		$insertDatosUsuario = 'CALL sp_sistema_insert_datos_usuario('.$_SESSION[$varIdUser].');';
		$insertDatUser = consulta($insertDatosUsuario);	
		
		//DATOS USUARIO
		liberar_bd();
		$selectDatUsuario='CALL sp_sistema_select_datos_usuario('.$_SESSION[$varIdUser].');';
		$datUsuario = consulta($selectDatUsuario);	
	}
	
	$usu = siguiente_registro($datUsuario);
	if($usu["cumple"] != "0000-00-00")
		$fechaCumple = normalize_date2($usu["cumple"]);
	else
		$fechaXumple = "0000-00-00";
	
	//DATOS GENERALES
	liberar_bd();
	$selectPerfil='CALL sp_sistema_select_datos_mi_perfil('.$_SESSION[$varIdUser].');';
	$perfil = consulta($selectPerfil);	
	$per = siguiente_registro($perfil);
					
	$pagina = '	<div id="page-heading">	
					  	<ol class="breadcrumb">
							<li><a href="javascript:navegar_modulo(0);">Tablero</a></li>    
							<li class="active">
								'.$_SESSION["moduloPadreActual"].'
							</li>
						</ol>        
						<h1>'.$_SESSION["moduloPadreActual"].'</h1>
						<div class="options">
							<div class="btn-toolbar">								
								<input type="hidden" id="idPerfil" name="idPerfil" value="" />
								<input type="hidden" name="txtIndice" />
							</div>							
						</div>										
				  	</div>		
				  	<div class="container">						
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-body">
										<div class="row">
											<div class="col-md-6">
												<img href="#myModalImg" id="bootbox-demo-5" data-toggle="modal" src="imagenes/usuarios/default.jpg" alt="" class="pull-left" style="cursor:pointer; margin: 0 20px 20px 0">
												<div class="table-responsive">
													<table class="table table-condensed">
														<h3>
															<strong>
																<a id="inline-nombre" class="editable editable-click" data-title="Cambiar nombre" data-pk="1" data-type="text" onclick="editarNombrePerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($per["nombre"]).'</a>
															</strong>
														</h3>
														<tbody>
															<tr>
																<td>Usuario:</td>
																<td><a id="inline-usuario" class="editable editable-click" data-title="Cambiar usuario" data-pk="1" data-type="text" onclick="editarUsuarioPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($per["login"]).'</a></td>
															</tr>
															<tr>
																<td>Contraseña:</td>
																<td><a id="inline-pass" class="editable editable-click" data-title="Cambiar contraseña" data-pk="1" data-type="text" onclick="editarPasswordPerfil();" href="javascript:;" data-original-title="" title="">*********</a></td>
															</tr>
															<tr>
																<td>Correo:</td>
																<td class="frmCorreo"><a id="inline-correo" class="editable editable-click" data-title="Cambiar correo" data-pk="1" data-type="text" onclick="editarCorreoPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["correo"]).'</a></td>
															</tr>
															<tr>
																<td>Tel&eacute;fono:</td>
																<td>
																	<a id="inline-lada" class="editable editable-click" data-title="Cambiar lada" data-pk="1" data-type="text" onclick="editarLadaPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["lada"]).'</a> <a id="inline-telefono" class="editable editable-click" data-title="Cambiar teléfono" data-pk="1" data-type="text" onclick="editarTelefonoPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["telefono"]).'</a>
																</td>
															</tr>
															<tr>
																<td>Calle:</td>
																<td><a id="inline-calle" class="editable editable-click" data-title="Cambiar calle" data-pk="1" data-type="text" onclick="editarCallePerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["calle"]).'</a></td>
															</tr>
															<tr>
																<td>N&uacute;m Ext.</td>
																<td><a id="inline-numExt" class="editable editable-click" data-title="Cambiar número exterior" data-pk="1" data-type="text" onclick="editarNumExtPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["numExt"]).'</a></td>
															</tr>
															<tr>
																<td>N&uacute;m Int:</td>
																<td><a id="inline-numInt" class="editable editable-click" data-title="Cambiar número interior" data-pk="1" data-type="text" onclick="editarNumIntPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["numInt"]).'</a></td>
															</tr>
															<tr>
																<td>Colonia:</td>
																<td><a id="inline-colonia" class="editable editable-click" data-title="Cambiar colonia" data-pk="1" data-type="text" onclick="editarColoniaPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["colonia"]).'</a></td>
															</tr>
															<tr>
																<td>CP:</td>
																<td><a id="inline-codigo" class="editable editable-click" data-title="Cambiar código postal" data-pk="1" data-type="text" onclick="editarCPPerfil();" href="javascript:;" data-original-title="" title="">'.utf8_convertir($usu["cp"]).'</a></td>
															</tr>
															<tr>
																<td>Fecha de nacimiento:</td>
																<td><a href="javascript:;" id="dob" data-type="combodate" data-value="'.$fechaCumple.'" data-format="YYYY-MM-DD" data-viewformat="DD/MM/YYYY" data-template="D / MMM / YYYY" data-pk="1"  data-title="Seleccione fecha de nacimiento"></a></td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
										<hr>
										<div class="row" id="divImgNew" style="display:none;">
																
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>					
					<div id="div_articulos"></div>';
							
		return $pagina;
		
	
}

?>