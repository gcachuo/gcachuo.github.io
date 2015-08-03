<?php

	function agentes_menuInicio()
	{
		$btnAlta = false;
		$btnEdita = false;
		$btnElimina = false;
		
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
				case 'Eliminación':
					$btnElimina = true;
				break;
			}
		}
		
		liberar_bd();
		$selectUsuarios = "	    SELECT usuar.id_usuario AS id
									 , usuar.login_usuario AS login
									 , usuar.password_usuario AS pass
									 , usuar.nombre_usuario AS nombre
									 , perf.nombre_perfil AS perfil
									 , usuar.estado_usuario AS estatus
								FROM
								  _perfiles perf
								INNER JOIN _usuarios usuar
								ON perf.id_perfil = usuar.id_perfil
								WHERE
								  usuar.estado_usuario <> 0 ";
							  
		$usuarios = consulta($selectUsuarios);
		$cabezalModal= "Usuarios";
			
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
								<input type="hidden" id="idUsuario" name="idUsuario" value="" />
								<input type="hidden" name="txtIndice" />';
								if($btnAlta)
									$pagina.= '	<i title="Nuevo Usuario" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
													Nuevo Usuario
												</i>';								
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
														<th>NOMBRE(login)</th>
														<th>PERFIL</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>';						
												while($user = siguiente_registro($usuarios))
												{
													if($user["id"] != 1)
													{
														$pagina.= ' <tr>
																		<td>'.utf8_convertir($user["nombre"]).' ('.utf8_convertir($user["login"]).')</td>
																		<td>'.utf8_convertir($user["perfil"]).'</td>
																		<td class="tdAcciones">';
																			if($btnEdita)
																				$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idUsuario.value='.$user["id"].';navegar(\'Editar\');">
																								<i title="Editar" class="fa fa-pencil"></i>
																							</a>';
																			if($btnElimina)
																				$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar este usuario\')){document.frmSistema.idUsuario.value='.$user["id"].'; navegar(\'Eliminar\');}">
																								<i title="Eliminar" class="fa fa-trash-o"></i>
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
	
	function agentes_nuevoAgente()
	{
		//lista de perfiles
		liberar_bd();
		$selectPerfil = "CALL sp_sistema_lista_perfiles();";
		$perfiles = consulta($selectPerfil);
		while($per = siguiente_registro($perfiles))
		{
			$sel = '';
			if($user["idPerfil"] == $per["id"])
			{
				$sel = ' selected="selected" ';
			}
			$optPerfiles .= '<option '.$sel.' value="'.$per["id"].'">'.utf8_convertir($per["nombre"]).'</option>';
		}
		
		$pagina .= '	<div id="page-heading">	
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
									<input type="hidden" name="tipo" value="'.$tipo.'" />	
								</div>
							</div>										
						</div>
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
													<label for="nombre_usuario" class="col-sm-3 control-label">Nombre completo de usuario:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" name="nombre_usuario" id="nombre_usuario" maxlength="150" value="'.utf8_convertir($user["nombre"]).'" />
													</div>
												</div>
												<div class="form-group">
													<label for="login_usuario" class="col-sm-3 control-label">Usuario:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" id="login_usuario" name="login_usuario" maxlength="200" value="'.$user["login"].'" />
													</div>
												</div>
												'.$alertPass.'
												<div class="form-group">
													<label for="pswd_usuario" class="col-sm-3 control-label">Contrase&ntilde;a:</label>
													<div class="col-sm-6">
														<input type="password" class="form-control" maxlength="200" name="pswd_usuario" id="pswd_usuario" autocomplete="off" />
													</div>
												</div>
												<div class="form-group">
													<label for="pswd_usuario_c" class="col-sm-3 control-label">Confirmar contrase&ntilde;a:</label>
													<div class="col-sm-6">
														<input type="password" class="form-control" maxlength="200" name="pswd_usuario_c" id="pswd_usuario_c" autocomplete="off" />
													</div>
												</div>
												<div class="form-group">
													<label for="id_perfil" class="col-sm-3 control-label">Perfil de usuario:</label>
													<div class="col-sm-6">
														<select id="id_perfil" name="id_perfil" style="width:100% !important" class="selectSerch">
															'.$optPerfiles.'
														</select>
													</div>
												</div>											
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-12">
													<div class="btn-toolbar btnsGuarCan">
														<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
														<i class="btn-success btn" onclick="validaFormularioUsuario(\''.$opcionBtn.'\');">Guardar</i>
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
	
	function agentes_guardaUsuario()
	{
		if($_POST["tipo"] == "nuevo")
		{
			$newUser = (strtoupper($_POST["login_usuario"]));
			liberar_bd();
			$selectLoginUsuario =  "CALL sp_sistema_select_usuario_login('".$newUser."');";			
			$loginUsuario = consulta($selectLoginUsuario);
			$ctaloginUsuario = cuenta_registros($loginUsuario);
			if($ctaloginUsuario == 0)
			{
				liberar_bd();
				$insertUsuarioId = " CALL sp_sistema_insert_usuario('".$newUser."',
																	'".($_POST["nombre_usuario"])."',
																	md5('".strtoupper($_POST["pswd_usuario"])."'),
																	".$_POST["id_perfil"].",
																	".$_SESSION[$varIdUser].");";
										
				$insertUsuario = consulta($insertUsuarioId);
				if($insertUsuario)
				{
					/*$error='Se ha creado el usuario exitosamente.';
					$msj = sistema_mensaje("exito",$error);*/
					$res= $msj.agentes_menuInicio();					
				}
				else
				{
					$error='No se ha podido crear el usuario.';
					$msj = sistema_mensaje("error",$error);
					$res= $msj.agentes_formularioUsuario($_POST["tipo"]);					
				}
				
				
			}
			else
			{
				$error='Ya existe un usuario con este nombre de acceso.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.agentes_formularioUsuario($_POST["tipo"]);
			}
		}
		else
		{
			$newUser = (strtoupper($_POST["login_usuario"]));
			
			liberar_bd();
			$selectLoginUsuario = "CALL sp_sistema_select_usuario_loginEditar('".$newUser."', '".$_POST["idUsuario"]."');";
			$loginUsuario = consulta($selectLoginUsuario);
			$ctaLoginUsuario = cuenta_registros($loginUsuario);
			if($ctaLoginUsuario == 0)
			{
				liberar_bd();
				$update = "	UPDATE 
								_usuarios 
							SET 
								login_usuario = '".$newUser."', 
								nombre_usuario = '".($_POST["nombre_usuario"])."', 
								id_perfil = '".$_POST["id_perfil"]."',
								id_usuarioCreate = ".$_SESSION[$varIdUser]." ";
								
				if($_POST["pswd_usuario"] != "")
				{
					$update .= ", password_usuario = '".md5(strtoupper($_POST["pswd_usuario"]))."'";
				}
				$update .= " WHERE id_usuario = '".$_POST["idUsuario"]."'";
				$updateUsuario = consulta($update);
				
				if($updateUsuario)
				{
					/*$error='Se ha actualizado el usuario exitosamente.';
					$msj = sistema_mensaje("exito",$error);*/
					$res= $msj.agentes_menuInicio();					
				}
				else
				{
					$error='No se ha podido actualizadar el usuario.';
					$msj = sistema_mensaje("error",$error);
					$res= $msj.agentes_formularioUsuario($_POST["tipo"]);
				}
			}
			else
			{
				$error='Ya existe un usuario con este nombre de acceso.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.agentes_formularioUsuario($_POST["tipo"]);				
			}
		}
		
		return $res;
	}
	
	function agentes_eliminaUsuario()
	{
		liberar_bd();
		$sqlUpdateUsuario = "CALL sp_sistema_delete_usuario('".$_POST["idUsuario"]."');";
		$updateUsuario = consulta($sqlUpdateUsuario);
		if($updateUsuario)
		{
			/*$error='Se ha eliminado el usuario exitosamente.';
			$msj = sistema_mensaje("exito",$error);*/
			$res= $msj.agentes_menuInicio();
			
		}
		else
		{
			$error='No se ha podido eliminar el usuario.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.agentes_menuInicio();
		}
		
		return $res;
	}

?>