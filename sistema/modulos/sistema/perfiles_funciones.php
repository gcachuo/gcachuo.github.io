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
			case 'Editar':
				$btnEdita = true;					
			break;
			case 'Permisos de tablero':
				$btnPermisos = true;					
			break;
			case 'Eliminar':
				$btnElimina = true;
			break;
		}
	}
	
	liberar_bd();
	$selectPerfiles=' SELECT perf.id_perfil AS id
						   , perf.nombre_perfil AS nombre
					  FROM
						_perfiles perf
					  WHERE 
					  	perf.estatus_perfiles <> 0
					  AND 
					  	perf.id_agente = '.$_SESSION['idAgenteActual'];
						
	$perfiles = consulta($selectPerfiles);
					
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
								<input type="hidden" name="txtIndice" />';
								if($btnAlta)
									$pagina.= '	<a title="Nuevo Perfil" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
													Nuevo Perfil
												</a>';	
		
		$pagina.= '			</div>							
						</div>										
				  	</div>		
				  	<div class="container">						
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
										<div class="options">   
											<a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
										</div>
									</div>
									<div class="panel-body collapse in">
										<div class="table-responsive">
											<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
												<thead>
													<tr>
														<th>PERFIL</th>
														<th>ACCIONES</th> 
													</tr>
												</thead>	
												<tbody>';						
												while($perf = siguiente_registro($perfiles))
												{
													if($perf["id"] != 1 && $perf["id"] != 2 && $perf["id"] != 3)
													{
														$pagina.= ' <tr>
																		<td>'.$perf["nombre"].'</td>
																		<td class="tdAcciones">';
																			if($btnEdita)
																				$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idPerfil.value='.$perf["id"].';navegar(\'Editar\');">
																								<i title="Editar" style="cursor:pointer;" class="fa fa-pencil"></i>
																							</a>';
																			if($btnPermisos)
																				$pagina.= '	<a class="btn btn-default-alt btn-sm"  onClick="document.frmSistema.idPerfil.value='.$perf["id"].';navegar(\'Permisos\');" >
																								<i title="Permisos" style="cursor:pointer;" class="fa fa-cog"></i>
																							</a>';
																			if($btnElimina)
																				$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar esta perfil\')){document.frmSistema.idPerfil.value='.$perf["id"].';navegar(\'Eliminar perfil\')};">
																								<i title="Eliminar" style="cursor:pointer;" class="fa fa-trash-o"></i>
																							</a>';																	
														$pagina.= '	  	</td>
																	</tr>';	
													}
													
												}
						
		$pagina.= '								</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';		
		return $pagina;
		
	
}

function perfiles_formularioPerfil($accion)
{
	if($accion=="nuevo")
	{
		$boton = 'Guardar Perfil';
		$opcion = "Guardar";
		$opt0 = 'checked';
	}
	elseif($accion=="editar")
	{
		$boton = 'Actualizar Perfil';
		liberar_bd();
		$sql='SELECT * FROM _perfiles WHERE id_perfil ='.$_POST['idPerfil'];
		$idPerfil = '<input type="hidden" name="idPerfil" value="'.$_POST['idPerfil'].'" />';
		$res=consulta($sql);
		$fila=siguiente_registro($res);
		$_POST['txtNombre']=$fila['nombre_perfil'];
		$opcion = "GuardarEdit";
		//TIPO DE PERFIL
		switch($fila["tipo_perfil"])
		{
			case 4:
				$opt0 = 'checked';
			break;
			case 3:
				$opt1 = 'checked';
			break;
		}
	}
	
	$pagina = '		<div id="page-heading">	
							<ol class="breadcrumb">
								<li><a href="javascript:navegar_modulo(0);">Tablero</a></li> 
								<li><a href="javascript:navegar_modulo('.$_SESSION["mod"].');">'.$_SESSION["moduloPadreActual"].'</a></li>    
								<li class="active">
									'.$_SESSION["moduloHijoActual"].'
								</li>
							</ol>        
							<h1>'.$_SESSION["moduloHijoActual"].'</h1>
							<div class="options">
								<div class="btn-toolbar">
									'.$idPerfil.'
								</div>
							</div>										
						</div
						<div class="container">							
							<div class="row">
								<div class="col-sm-12">
									<div class="panel panel-danger">
										<div class="panel-heading">
											<h4>'.$titulo.'</h4>
										</div>
										<div class="panel-body" style="border-radius: 0px;">											
											<div class="form-horizontal">
												<div class="form-group">
													<label for="txtNombre" class="col-sm-3 control-label">Nombre del perfil:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" id="txtNombre" name="txtNombre" maxlength="200" value="'.utf8_convertir($fila["nombre_perfil"]).'" />
													</div>
												</div>
												<!--<div class="form-group">
													<label class="col-sm-3 control-label">Es subagente:</label>
													<div class="col-sm-6">
														<div class="radio">
														  	<label>
																<input type="radio" name="optTipo" id="optTipo" value="4" '.$opt0.'>NO														
														 	</label>
														</div>
														<div class="radio">
														  	<label>
																<input type="radio" name="optTipo" id="optTipo" value="3" '.$opt1.'>SI														
														  	</label>
														</div>
													</div>
												</div>	-->							
											</div>
											<div class="row">
												<div id="accordioninpanel" class="accordion-group">
													'. perfiles_muestraPermisos($_POST['idPerfil']) .'	
												</div>
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-12">
													<div class="btn-toolbar btnsGuarCan">
														<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
														<i class="btn-success btn" onclick="navegar(\''.$opcion.'\');">'.$boton.'</i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
		
		return $pagina;
	
}

function perfiles_muestraPermisos($idPerfil)
{
	liberar_bd();
	$selectModulosPadre = 'CALL sp_sistema_select_lista_modulos_padre();';				
	$modulosPadre = consulta($selectModulosPadre);
	
	// REVISAR LOS PERMISOS CONCEDIDOS
	liberar_bd();
	$selectPermisosModulos = 'CALL sp_sistema_select_permisos_modulos('.$idPerfil.');';	
	$permisosModulos = consulta($selectPermisosModulos);
							
	//PREMISOS DE ACCIONES
	liberar_bd();
	$selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones('.$idPerfil.')';
	$permisosAcciones = consulta($selectPermisosAcciones);
	
	while($modulo = siguiente_registro($modulosPadre))
	{
		$checkedParent = '';
		while($p = siguiente_registro($permisosModulos))
		{
			if($p["id_modulo"] == $modulo["id_modulo"])
			{
				$checkedParent = ' checked="checked" ';
			}
		}
		mysqli_data_seek($permisosModulos,0);
				
		$pagina.='	<div class="accordion-item">
						<a class="accordion-title" data-toggle="collapse" data-parent="#accordioninpanel" href="#collapsein'.$modulo['id_modulo'].'"><h4>'.utf8_convertir($modulo['nombre_modulo']).'</h4></a>												
						<div id="collapsein'.$modulo['id_modulo'].'" class="collapse">
							<div class="accordion-body">
								<h3 class="h3Menu"> 
									<input '.$checkedParent.' type="checkbox" name="modulo_'.$modulo['id_modulo'].'" id="modulo_'.$modulo['id_modulo'].'" onclick="checar_submodulos(this);" value="ok">
									<strong>'.utf8_convertir($modulo['nombre_modulo']).'</strong>
								</h3>';
					
		//SUBMODULOS HIJO 
		liberar_bd();
		$selectModulosHijo = 'CALL sp_sistema_select_modulos_hijo('.$modulo["id_modulo"].')';
		$modulosHijo = consulta($selectModulosHijo);	
		
			$pagina.='	<ul class="list-unstyled">';
			
			while($subMod = siguiente_registro($modulosHijo))
			{
				//CHECAMOS SI TIENE NIETOS
				liberar_bd();
				$selectModulosNietos = 'CALL sp_sistema_select_modulos_hijo('.$subMod["id_modulo"].')';
				$modulosNietos = consulta($selectModulosNietos);
				$ctaModulosNietos = cuenta_registros($modulosNietos);
				
				if($ctaModulosNietos != 0)
				{
					$checkedSon = '';
					while($p = siguiente_registro($permisosModulos))
					{
						if($p["id_modulo"] == $subMod["id_modulo"])
						{
							$checkedSon = ' checked="checked" ';
						}
					}
					mysqli_data_seek($permisosModulos,0);
					
					$pagina.='	<li class="menuHijos">
									 <input '.$checkedSon.' type="checkbox" name="modulo_'.$subMod['id_modulo'].'" id="modulo_'.$modulo['id_modulo'].'_'.$subMod['id_modulo'].'" onclick="
									 " value="ok">
									 <strong>'.utf8_convertir($subMod['nombre_modulo']).'</strong>
								</li>';
								
					$pagina.='<ul class="listaNietos">';
					
					while($nietoMod = siguiente_registro($modulosNietos))
					{
						$checkedParent = '';
						while($p = siguiente_registro($permisosModulos))
						{
							if($p["id_modulo"] == $nietoMod["id_modulo"])
							{
								$checkedParent = ' checked="checked" ';
							}
						}
						mysqli_data_seek($permisosModulos,0);
								
						$pagina.='	<li class="menuNietos">
										 <input '.$checkedParent.' type="checkbox" name="modulo_'.$nietoMod['id_modulo'].'" id="modulo_'.$modulo['id_modulo'].'_'.$subMod['id_modulo'].'_'.$nietoMod['id_modulo'].'" onclick="" value="ok">
										 '.utf8_convertir($nietoMod['nombre_modulo']).'
									</li>';
							
						//ACCIONES DEL MODULO
						liberar_bd();
						$selectAccionesModulo = 'CALL sp_sistem_select_acciones_modulo('.$nietoMod["id_modulo"].');';
						$accionesModulo = consulta($selectAccionesModulo);
						
						$pagina.='<ul class="listaAcciones">';
								
						while($acciones = siguiente_registro($accionesModulo))
						{
							$checkedAccion = '';
							while($pA = siguiente_registro($permisosAcciones))
							{
								if($pA["id_acciones"] == $acciones["id_acciones"])
								{
									$checkedAccion = ' checked="checked" ';
								}
							}
							mysqli_data_seek($permisosAcciones,0);
							
							$pagina.='<li class="menuAcciones">
										  <input '.$checkedAccion.' type="checkbox" name="accion_'.$acciones['id_acciones'].'" id="modulo_'.$modulo['id_modulo'].'_'.$subMod['id_modulo'].'_'.$nietoMod['id_modulo'].'_'.$acciones['id_acciones'].'" value="ok">
										  '.utf8_convertir($acciones['nombre_acciones']).'	
									  </li>';
						}
								
						$pagina.='</ul>';
					}
					
					$pagina.='</ul>';
				}
				else
				{
					$checkedSon = '';
					while($p = siguiente_registro($permisosModulos))
					{
						if($p["id_modulo"] == $subMod["id_modulo"])
						{
							$checkedSon = ' checked="checked" ';
						}
					}
					mysqli_data_seek($permisosModulos,0);
					
					$pagina.='	<li class="menuHijos">
									 <input '.$checkedSon.' type="checkbox" name="modulo_'.$subMod['id_modulo'].'" id="modulo_'.$modulo['id_modulo'].'_'.$subMod['id_modulo'].'" onclick="" value="ok">
									 <strong>'.utf8_convertir($subMod['nombre_modulo']).'</strong>
								</li>';
								
					//ACCIONES DEL MODULO
					liberar_bd();
					$selectAccionesModulo = 'CALL sp_sistem_select_acciones_modulo('.$subMod["id_modulo"].');';
					$accionesModulo = consulta($selectAccionesModulo);
					
					$pagina.='<ul class="listaAcciones">';
					
					while($acciones = siguiente_registro($accionesModulo))
					{
						$checkedAccion = '';
						while($pA = siguiente_registro($permisosAcciones))
						{
							if($pA["id_acciones"] == $acciones["id_acciones"])
							{
								$checkedAccion = ' checked="checked" ';
							}
						}
						mysqli_data_seek($permisosAcciones,0);
						
						$pagina.='<li class="menuAcciones">
									  <input '.$checkedAccion.' type="checkbox" name="accion_'.$acciones['id_acciones'].'" id="modulo_'.$modulo['id_modulo'].'_'.$subMod['id_modulo'].'_'.$acciones['id_acciones'].'" value="ok">
									  '.utf8_convertir($acciones['nombre_acciones']).'	
								  </li>';
					}				
					$pagina.='</ul>';
				}		
			}
				
		$pagina .='				</ul>
							</div>
						</div>
					</div>';
	}
	
		
	return $pagina;
}

function perfiles_guardaPerfil($accion)
{
	$continue = false;
	if($accion == 'nuevo')	
	{
		liberar_bd();
		$selectPerfiles= "	SELECT 
								* 
							FROM 
								_perfiles 
							WHERE 
								nombre_perfil = '".$_POST['txtNombre']."'
							AND
								id_agente = ".$_SESSION['idAgenteActual'];

		$perfiles = consulta($selectPerfiles);
		$numPerfiles =  cuenta_registros($perfiles);
		if($numPerfiles == 0)
		{
			liberar_bd();
			$insertPerfiles = "INSERT INTO _perfiles (nombre_perfil, id_agente, tipo_perfil) VALUES ('".$_POST['txtNombre']."', ".$_SESSION['idAgenteActual'].", 3)";
			$insertP = consulta($insertPerfiles);
	
			if($insertP)
			{
				liberar_bd();
				$selectIdPerfil = "SELECT id_perfil FROM _perfiles ORDER BY id_perfil DESC";
				$idPerfil = consulta($selectIdPerfil);
				$perfilNuevo = siguiente_registro($idPerfil);
				$_POST["idPerfil"] = $perfilNuevo["id_perfil"];
				$continue = true;
			}
			else
			{
				$error='No se ha podido agregar el perfil.';
				$msj = sistema_mensaje("error",$error);
				$pagina= $msj.perfiles_menuInicio();	
			}
		}
		else
		{
			$error='Ya existe un perfil con este nombre.';
			$msj = sistema_mensaje("error",$error);
			$pagina= $msj.perfiles_menuInicio();			
		}
	}
	elseif($accion == 'editar')
	{
		liberar_bd();
		$selectPerfiles= "	SELECT 
								* 
							FROM 
								_perfiles 
							WHERE 
								nombre_perfil = '".$_POST['txtNombre']."'
							AND
								id_agente = ".$_SESSION['idAgenteActual']."
							AND
								id_perfil <> ".$_POST['idPerfil'];
		
		$perfiles = consulta($selectPerfiles);
		$numPerfiles =  cuenta_registros($perfiles);
		if($numPerfiles == 0)
		{
			liberar_bd();
			$updatePerfiles = '	UPDATE _perfiles
								SET
									nombre_perfil = "'.$_POST["txtNombre"].'",
									tipo_perfil = 3
								WHERE
									id_perfil = '.$_POST["idPerfil"];									
	
			$updateP = consulta($updatePerfiles);
	
			if($updateP)
			{
				$continue = true;
			}
			else
			{
				$error='No se ha podido actualizar el perfil.';
				$msj = sistema_mensaje("error",$error);
				$pagina= $msj.perfiles_menuInicio();	
			}
		}
		else
		{
			$error='Ya existe un perfil con este nombre.';
			$msj = sistema_mensaje("error",$error);
			$pagina= $msj.perfiles_menuInicio();			
		}
	}
	
	if($continue == true)
	{	
		//BORRAMOS LOS PERMISOS DE MODULO
		liberar_bd();
		$deletePermisosId = 'CALL sp_sistema_delete_permisos_modulos('.$_POST["idPerfil"].');';
		$permisosId = consulta($deletePermisosId);
		if($permisosId && $_POST['idPerfil'] != 0)
		{
			//BORRAMOS LOS PERMISOS DE ACCIONES ACTUALES
			liberar_bd();
			$deletePermisosAcciones = 'CALL sp_sistema_delete_permisos_acciones('.$_POST["idPerfil"].');';
			$delete = consulta($deletePermisosAcciones);
			
			foreach ($_POST as $control => $valor)
			{
				$info = explode("_",$control);
				liberar_bd();
				if($info[0]=="modulo" &&  count($info)==2 )
				{
					//GUARDAMOS EL PERMISO DE MODULO				
					$insertPermisos = "CALL sp_sistema_insert_permiso_modulo('".$_POST["idPerfil"]."','".$info[1]."')";
					$insertPer = consulta($insertPermisos);				
				}
				elseif($info[0]=="accion" &&  count($info)==2 )
				{
					//GUARDAMOS EL PERMISO DE ACCION
					$insertPermisosAccion = "CALL sp_sistema_insert_permiso_accion('".$_POST["idPerfil"]."','".$info[1]."')";
					$insertPerAcci = consulta($insertPermisosAccion);	
				}
			}
				
			$pagina= $msj.perfiles_menuInicio();
		}
		else
		{
			$error='No se han podido guardar los permisos.';
			$msj = sistema_mensaje("error",$error);
			$pagina= $msj.perfiles_menuInicio();
		}
	}
	
	return $pagina;
}

function perfiles_permisosPerfil()
{
	//LISTA DE PERMISOS
	liberar_bd();
	$selectPermisos = 'CALL sp_sistema_select_permisos_tablero();';
	$permisos = consulta($selectPermisos);
	//PERMISOS DEL PERFIL
	liberar_bd();
	$selectPermisosPerfil = 'CALL sp_sistema_select_permisos_tablero_modulo('.$_POST["idPerfil"].');';
	$permisosPerfil = consulta($selectPermisosPerfil);
	
	while($per = siguiente_registro($permisos))
	{
		$checkedParent = '';
		while($p = siguiente_registro($permisosPerfil))
		{
			if($per["id"] == $p["id"])
			{
				$checkedParent = ' checked="checked" ';
			}
		}
		mysqli_data_seek($permisosPerfil,0);
		
		$listaPermisos .='	<tr>
								<td>
									<label class="checkbox-inline">
										<input '.$checkedParent.' type="checkbox" name="contenido_'.$per['id'].'" id="contenido_'.$per['id'].'" value="ok">'.utf8_convertir($per['nombre']).'
									</label>
								</td>
							</tr>';
	}
	
	$pagina = '		<div id="page-heading">	
						<ol class="breadcrumb">
							<li><a href="javascript:navegar_modulo(0);">Tablero</a></li> 
							<li><a href="javascript:navegar_modulo('.$_SESSION["mod"].');">'.$_SESSION["moduloPadreActual"].'</a></li>    
							<li class="active">
								'.$_SESSION["moduloHijoActual"].'
							</li>
						</ol>        
						<h1>'.$_SESSION["moduloHijoActual"].'</h1>
						<div class="options">
							<div class="btn-toolbar">
								<input type="hidden" id="idPerfil" name="idPerfil" value="'.$_POST["idPerfil"].'" />
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>Permisos de tablero</h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="form-horizontal">											
											<div class="form-group">
												<label for="txtPermisos" class="col-sm-3 control-label">Permisos:</label>
												<div class="col-sm-6">
													<table>
														'.$listaPermisos.'	
													</table>													
												</div>
											</div>										
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="navegar(\'Guarda Permisos\');">Guardar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
	
	return $pagina;
}

function perfiles_permisosGuardarPerfil()
{
	liberar_bd();
	$deletePermisosId = ' CALL sp_sistema_delete_permisos_tablero('.$_POST["idPerfil"].');';
	$permisosId = consulta($deletePermisosId);
	if($permisosId)
	{
		foreach ($_POST as $control => $valor)
		{
			$info = explode("_",$control);
			if($info[0]=="contenido" &&  count($info)==2 )
			{
				liberar_bd();
				$insertPermisos = "CALL sp_sistema_insert_permisos_tablero('".$_POST["idPerfil"]."','".$info[1]."')";
				$insertPer = consulta($insertPermisos);				
			}
		}
		
		$pagina= $msj.perfiles_menuInicio();
	}
	else
	{
		$error='No se han podido guardar los permisos.';
		$msj = sistema_mensaje("error",$error);
		$pagina= $msj.perfiles_menuInicio();
	}
	
	return $pagina;
}

function perfiles_eliminarPerfil()
{
	liberar_bd();
	$deletePerfil = 'CALL sp_sistema_delete_perfil('.$_POST["idPerfil"].');';
	$delete = consulta($deletePerfil);
	
	if($delete)
	{
		/*$error='Se ha eliminado el perfil.';
		$msj = sistema_mensaje("exito",$error);*/
		$pagina= $msj.perfiles_menuInicio();
	}
	else
	{
		$error='No se ha podido eliminar el perfil.';
		$msj = sistema_mensaje("error",$error);
		$pagina= $msj.perfiles_menuInicio();
	}
	
	return $pagina;
}

?>