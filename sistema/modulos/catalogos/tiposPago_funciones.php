<?php

	function tipoPago_menuInicio()
	{
		$btnEdita = false;
		$btnAlta = false;
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
		
		if($_SESSION['tipoPerfil'] == 2)
		{
			liberar_bd();
			$selectTipoPago = "	SELECT
									tipPag.id_tipo_pago AS id,
									tipPag.nombre_tipo_pago AS nombre,
									tipPag.descripcion_tipo_pago AS descripcion,
									tipPag.estatus_tipo_pago AS estatus,
									usuar.nombre_usuario AS nomSubAgen
								FROM
									tipo_pago AS tipPag
								INNER JOIN subagente AS subAge ON tipPag.id_subagente = subAge.id_subagente
								INNER JOIN _usuarios AS usuar ON subAge.id_usuario = usuar.id_usuario
								INNER JOIN _perfiles AS perf ON usuar.id_perfil = perf.id_perfil
								WHERE
									perf.id_agente = ".$_SESSION['idAgenteActual']."
								AND
									tipPag.estatus_tipo_pago <> 0";
			
			$colSubAgen = '<th>SUBAGENTE</th>';
		}
		else
		{
			if($_SESSION['tipoPerfil'] == 3)
			{
				liberar_bd();
				$selectTipoPago = "	SELECT
										tipPag.id_tipo_pago AS id,
										tipPag.nombre_tipo_pago AS nombre,
										tipPag.descripcion_tipo_pago AS descripcion,
										tipPag.estatus_tipo_pago AS estatus
									FROM
										tipo_pago AS tipPag
									WHERE
										tipPag.id_subagente = ".$_SESSION["idSubAgenteActual"]."
									AND
										tipPag.estatus_tipo_pago <> 0";
				
				$colSubAgen = '';
			}
		}
		
		$tipoPago = consulta($selectTipoPago);
		
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
								<input type="hidden" id="idTipo" name="idTipo" value="" />
								<input type="hidden" name="txtIndice" />';
							  if($btnAlta)
								  $pagina.= '	<i title="Nueva forma de pago" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
													  Nueva forma de pago
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
														'.$colSubAgen.'
														<th>NOMBRE</th>
														<th>ESTATUS</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>';
												while($tip = siguiente_registro($tipoPago))
												{
													//ESTATUS DE TIPOS DE PAGO
													switch($tip['estatus'])
													{
														case 1:
															$estatus = 'ACTIVO';
														break;
														case 2:
															$estatus = 'INACTIVO';
														break;
													}
													
													if($_SESSION['tipoPerfil'] == 2)
														$rowSubAgen = '<td>'.utf8_convertir($tip["nomSubAgen"]).'</td>';
													else
													{
														if($_SESSION['tipoPerfil'] == 3)
															$rowSubAgen = "";
													}
													
													$pagina.= ' <tr>
																	'.$rowSubAgen.'
																	<td>'.utf8_convertir($tip["nombre"]).'</td>
																	<td>'.$estatus.'</td>
																	<td class="tdAcciones">';
																	if($btnEdita)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idTipo.value='.$tip["id"].';navegar(\'Editar\');">
																						<i title="Editar" class="fa fa-pencil"></i>
																					</a>';																																		
																	if($btnElimina)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar esta forma de pago\')){document.frmSistema.idTipo.value='.$tip["id"].'; navegar(\'Eliminar\');}">
																						<i title="Eliminar" class="fa fa-trash-o"></i>
																					</a>';
													$pagina.= '		</td>											
																</tr>';		
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
	
	function tipoPago_formularioNuevo()
	{
		/*if($_SESSION["idSubAgenteActual"] != "")
		{
			$listaSubAgentes = '<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$_SESSION["idSubAgenteActual"].'"/>';
		}
		else
		{
			//LISTA DE SUBAGENTES DEL AGENTE
			liberar_bd();
			$selectListaSubAgentes = 'CALL sp_sistema_select_lista_subagentes_agenteId('.$_SESSION['idAgenteActual'].');';
			$listaSubAgentes = consulta($selectListaSubAgentes);
			while($subAge = siguiente_registro($listaSubAgentes))
			{
				$optSubAgent .= '<option value="'.$subAge["id"].'">'.utf8_convertir($subAge["nombre"]).'</option>';
			}
			
			$listaSubAgentes = '<div class="row">
									<div class="col-sm-12">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="id_estado" class="col-sm-4 control-label">Subagente:</label>
												<div class="col-sm-8">
													<select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
														<option value="0">Seleccione un subagente</option>
														'.$optSubAgent.'
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>';
		}*/
		
		$pagina = '	<div id="page-heading">	
							<ol class="breadcrumb">
								<li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
								<li><a href="javascript:navegar_modulo('.$_SESSION["mod"].');">'.$_SESSION["moduloPadreActual"].'</a></li>    
								<li class="active">
									'.$_SESSION["moduloHijoActual"].'
								</li>
							</ol>  
					  <h1>'.$_SESSION["moduloHijoActual"].'</h1>
						<div class="options">
							<div class="btn-toolbar">										
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-6">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="row">											
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-4 control-label">Nombre:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">											
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="txtTipo" class="col-sm-4 control-label">Descripci&oacute;n:</label>
														<div class="col-md-8">	
															<textarea class="form-control autosize" name="txtTipo" id="txtTipo"></textarea>											
														</div>													
													</div>
												</div>
											</div>									
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="nuevoTipoPago(\'Guardar\');">Guardar</i>
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
	
	function tipoPago_formularioEditar()
	{
		//DATOS DEL TIPO DE PAGO
		liberar_bd();
		$selectDatosTipoPago = 'CALL sp_sistema_select_datos_tipoPago('.$_POST["idTipo"].');';
		$datosTipoPago = consulta($selectDatosTipoPago);
		$tip = siguiente_registro($datosTipoPago);
		
		/*if($_SESSION["idSubAgenteActual"] != "")
		{
			$listaSubAgentes = '<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$_SESSION["idSubAgenteActual"].'"/>';
		}
		else
		{
			//LISTA DE SUBAGENTES DEL AGENTE
			liberar_bd();
			$selectListaSubAgentes = 'CALL sp_sistema_select_lista_subagentes_agenteId('.$_SESSION['idAgenteActual'].');';
			$listaSubAgentes = consulta($selectListaSubAgentes);
			while($subAge = siguiente_registro($listaSubAgentes))
			{
				$selectSubAgen = '';
				if($subAge["id"] == $tip["subAgen"])
					$selectSubAgen = 'selected="selected"';
				$optSubAgent .= '<option '.$selectSubAgen.' value="'.$subAge["id"].'">'.utf8_convertir($subAge["nombre"]).'</option>';
			}
			
			$listaSubAgentes = '<div class="row">
									<div class="col-sm-12">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="id_estado" class="col-sm-4 control-label">Subagente:</label>
												<div class="col-sm-8">
													<select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
														<option value="0">Seleccione un subagente</option>
														'.$optSubAgent.'
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>';
		}*/
		
		$pagina = '	<div id="page-heading">	
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
								<input type="hidden" id="idTipo" name="idTipo" value="'.$_POST["idTipo"].'" />	
								<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$tip["subAgen"].'"/>										
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-6">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-4 control-label">Nombre:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.utf8_convertir($tip["nombre"]).'"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="txtTipo" class="col-sm-4 control-label">Descripci&oacute;n:</label>
														<div class="col-md-8">	
															<textarea class="form-control autosize" name="txtTipo" id="txtTipo">'.utf8_convertir($tip["txt"]).'</textarea>											
														</div>													
													</div>
												</div>
											</div>									
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="nuevoTipoPago(\'GuardarEdit\');">Guardar</i>
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
	
	function tipoPago_guardar()
	{
		/*if($_SESSION["idSubAgenteActual"] != "")
		{
			$listaSubAgentes = '<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$_SESSION["idSubAgenteActual"].'"/>';
		}
		else
		{
			//LISTA DE SUBAGENTES DEL AGENTE
			liberar_bd();
			$selectListaSubAgentes = 'CALL sp_sistema_select_lista_subagentes_agenteId('.$_SESSION['idAgenteActual'].');';
			$listaSubAgentes = consulta($selectListaSubAgentes);
			while($subAge = siguiente_registro($listaSubAgentes))
			{
				$selectSubAgen = '';
				if($subAge["id"] == $_POST["idSubAgente"])
					$selectSubAgen = 'selected="selected"';
				$optSubAgent .= '<option '.$selectSubAgen.' value="'.$subAge["id"].'">'.utf8_convertir($subAge["nombre"]).'</option>';
			}
			
			$listaSubAgentes = '<div class="row">
									<div class="col-sm-12">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="id_estado" class="col-sm-4 control-label">Subagente:</label>
												<div class="col-sm-8">
													<select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
														<option value="0">Seleccione un subagente</option>
														'.$optSubAgent.'
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>';
		}*/
		
		$pagina = '	<div id="page-heading">	
						<ol class="breadcrumb">
							<li><a href="javascript:navegar_modulo(0);">Dashboard</a></li> 
							<li><a href="javascript:navegar_modulo('.$_SESSION["mod"].');">'.$_SESSION["moduloPadreActual"].'</a></li>    
							<li class="active">
								'.$_SESSION["moduloHijoActual"].'
							</li>
						</ol>  
					  	<h1>'.$_SESSION["moduloHijoActual"].'</h1>
						<div class="options">
							<div class="btn-toolbar">										
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-6">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-4 control-label">Nombre:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="txtTipo" class="col-sm-4 control-label">Descripci&oacute;n:</label>
														<div class="col-md-8">	
															<textarea class="form-control autosize" name="txtTipo" id="txtTipo">'.$_POST["nombreTipo"].'</textarea>											
														</div>													
													</div>
												</div>
											</div>									
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="nuevoTipoPago(\'Guardar\');">Guardar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
			
		  liberar_bd();
		  $selectTipoPago =  "CALL sp_sistema_select_tipoPago_nombre('".($_POST["nombreTipo"])."', ".$_SESSION['idSubAgenteActual'].");";		
		  $tipoPago = consulta($selectTipoPago);
		  $ctaTipoPago = cuenta_registros($tipoPago);
		  if($ctaTipoPago == 0)
		  {
			  liberar_bd();
			  $insertTipoPago = " CALL sp_sistema_insert_tipoPago(	".$_SESSION['idSubAgenteActual'].",
			  														'".($_POST["nombreTipo"])."', 
																	'".($_POST["txtTipo"])."');";										  
			  $insert = consulta($insertTipoPago);
			  
			  if($insert)
			  {
				  $res= $msj.tipoPago_menuInicio();					
			  }
			  else
			  {
				  $error='No se ha podido guardar el tipo de pago.';
				  $msj = sistema_mensaje("error",$error);
				  $res= $msj.$pagina;									
			  }
		  }
		  else
		  {
			  $error='Ya existe un tipo de pago con este nombre.';
			  $msj = sistema_mensaje("error",$error);
			  $res= $msj.$pagina;
		  }
		
		return $res;
	}
	
	function tipoPago_editar()
	{
		/*if($_SESSION["idSubAgenteActual"] != "")
		{
			$listaSubAgentes = '<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$_SESSION["idSubAgenteActual"].'"/>';
		}
		else
		{
			//LISTA DE SUBAGENTES DEL AGENTE
			liberar_bd();
			$selectListaSubAgentes = 'CALL sp_sistema_select_lista_subagentes_agenteId('.$_SESSION['idAgenteActual'].');';
			$listaSubAgentes = consulta($selectListaSubAgentes);
			while($subAge = siguiente_registro($listaSubAgentes))
			{
				$selectSubAgen = '';
				if($subAge["id"] == $_POST["idSubAgente"])
					$selectSubAgen = 'selected="selected"';
				$optSubAgent .= '<option '.$selectSubAgen.' value="'.$subAge["id"].'">'.utf8_convertir($subAge["nombre"]).'</option>';
			}
			
			$listaSubAgentes = '<div class="row">
									<div class="col-sm-12">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="id_estado" class="col-sm-4 control-label">Subagente:</label>
												<div class="col-sm-8">
													<select id="idSubAgente" name="idSubAgente" style="width:100% !important" class="selectSerch">
														<option value="0">Seleccione un subagente</option>
														'.$optSubAgent.'
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>';
		}*/
		
		$pagina = '	<div id="page-heading">	
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
								<input type="hidden" id="idTipo" name="idTipo" value="'.$_POST["idTipo"].'" />	
								<input name="idSubAgente" id="idSubAgente" type="hidden" readonly="readonly" value="'.$_POST["idSubAgente"].'"/>											
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-4 control-label">Nombre:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="txtTipo" class="col-sm-4 control-label">Descripci&oacute;n:</label>
														<div class="col-md-8">	
															<textarea class="form-control autosize" name="txtTipo" id="txtTipo">'.$_POST["txtTipo"].'</textarea>											
														</div>													
													</div>
												</div>
											</div>									
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="nuevoTipoPago(\'GuardarEdit\');">Guardar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
							  	
		liberar_bd();
		$selectTipoPago =  "CALL sp_sistema_select_tipoPago_nombreId(".$_POST["idTipo"].", ".$_POST["idSubAgente"].", '".($_POST["nombreTipo"])."');";			
		$tipoPago = consulta($selectTipoPago);
		$ctaTipoPago = cuenta_registros($tipoPago);
		if($ctaTipoPago == 0)
		{
			liberar_bd();
			$updateTipoPago = " CALL sp_sistema_update_tipoPago(".$_POST["idTipo"].",
																".$_POST["idSubAgente"].", 
																'".($_POST["nombreTipo"])."', 
																'".($_POST["txtTipo"])."');";								  
			$update = consulta($updateTipoPago);
			
			if($update)
			{
				$res= $msj.tipoPago_menuInicio();					
			}
			else
			{
				$error='No se ha podido editar el tipo de pago.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.$pagina;					
			}			  
		}
		else
		{
			$error='Ya existe un tipo de pago con este nombre.';
			$msj = sistema_mensaje("error",$error);
			$res= $msj.$pagina;
		}		  
		
		return $res;
	}
	
	function tipoPago_eliminar()
	{
		liberar_bd();
		$deleteTipoPago = "CALL sp_sistema_delete_tipoPago('".$_POST["idTipo"]."');";
		$delete = consulta($deleteTipoPago);
		if($delete)
		{
			$res= $msj.tipoPago_menuInicio();
			
		}
		else
		{
			$error='No se ha podido eliminar el tipo de pago.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.tipoPago_menuInicio();
		}
		
		return $res;
	}

?>