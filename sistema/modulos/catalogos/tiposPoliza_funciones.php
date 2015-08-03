<?php

	function tiposPoliza_menuInicio()
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
		
		liberar_bd();
		$selectTipoPoliza = "	SELECT
									tipPol.id_tipo_poliza AS id,
									tipPol.nombre_tipo_poliza AS nombre,
									tipPol.descripcion_tipo_poliza AS txt,
									tipPol.estatus_tipo_poliza AS estatus
								FROM
									tipo_poliza AS tipPol
								WHERE
									tipPol.id_agente = ".$_SESSION['idAgenteActual']."
								AND tipPol.estatus_tipo_poliza <> 0";
			
		$tipoPoliza = consulta($selectTipoPoliza);
		
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
								  $pagina.= '	<i title="Nuevo tipo de p&oacute;liza" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
													  Nuevo tipo de p&oacute;liza
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
														<th>NOMBRE</th>
														<th>DESCRIPCI&Oacute;N</th>
														<th>ESTATUS</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>';
												while($tip = siguiente_registro($tipoPoliza))
												{
													//ESTATUS DE TIPOS DE POLIZA
													switch($tip['estatus'])
													{
														case 1:
															$estatus = 'ACTIVO';
														break;
														case 2:
															$estatus = 'INACTIVO';
														break;
													}
													
													$pagina.= ' <tr>
																	<td>'.utf8_convertir($tip["nombre"]).'</td>
																	<td>'.utf8_convertir($tip["txt"]).'</td>
																	<td>'.$estatus.'</td>
																	<td class="tdAcciones">';
																	if($btnEdita)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idTipo.value='.$tip["id"].';navegar(\'Editar\');">
																						<i title="Editar" class="fa fa-pencil"></i>
																					</a>';																																		
																	if($btnElimina)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar este tipo de póliza\')){document.frmSistema.idTipo.value='.$tip["id"].'; navegar(\'Eliminar\');}">
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
	
	function tiposPoliza_formularioNuevo()
	{
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
													<i class="btn-success btn" onclick="nuevoTipoPoliza(\'Guardar\');">Guardar</i>
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
	
	function tiposPoliza_formularioEditar()
	{
		//DATOS DEL TIPO DE POLZA
		liberar_bd();
		$selectDatosTipoPoliza = 'CALL sp_sistema_select_datos_tipoPoliza('.$_POST["idTipo"].');';
		$datosTipoPoliza = consulta($selectDatosTipoPoliza);
		$tip = siguiente_registro($datosTipoPoliza);
		
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
										'.$listaSubAgentes.'
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
													<i class="btn-success btn" onclick="nuevoTipoPoliza(\'GuardarEdit\');">Guardar</i>
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
	
	function tiposPoliza_guardar()
	{	
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
													<i class="btn-success btn" onclick="nuevoTipoPoliza(\'Guardar\');">Guardar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
		
		  liberar_bd();
		  $selectTipoPoliza =  "CALL sp_sistema_select_tipoPoliza_nombre('".($_POST["nombreTipo"])."', ".$_SESSION['idAgenteActual'].");";			
		  $tipoPoliza = consulta($selectTipoPoliza);
		  $ctaTipoPoliza = cuenta_registros($tipoPoliza);
		  if($ctaTipoPoliza == 0)
		  {
			  liberar_bd();
			  $insertTipoPoliza = " CALL sp_sistema_insert_tipoPoliza(	".$_SESSION['idAgenteActual'].",
			  															'".($_POST["nombreTipo"])."', 
																		'".($_POST["txtTipo"])."',
																		".$_SESSION[$varIdUser].");";										  
			  $insert = consulta($insertTipoPoliza);
			  
			  if($insert)
			  		$res= $msj.tiposPoliza_menuInicio();	
			  else
			  {
				  $error='No se ha podido guardar el tipo de poliza.';
				  $msj = sistema_mensaje("error",$error);
				  $res= $msj.$pagina;									
			  }
		  }
		  else
		  {
			  $error='Ya existe un tipo de póliza con este nombre.';
			  $msj = sistema_mensaje("error",$error);
			  $res= $msj.$pagina;
		  }
		
		return $res;
	}
	
	function tiposPoliza_editar()
	{
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
														<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
														<div class="col-sm-6">
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
														<label for="txtTipo" class="col-sm-3 control-label">Descripci&oacute;n:</label>
														<div class="col-md-6">	
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
													<i class="btn-success btn" onclick="nuevoTipoPoliza(\'GuardarEdit\');">Guardar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
			
		liberar_bd();
		$selectTipoPoliza =  "CALL sp_sistema_select_tiposPoliza_nombreId(".$_POST["idTipo"].", ".$_SESSION['idAgenteActual'].", '".($_POST["nombreTipo"])."');";			
		$tipoPoliza = consulta($selectTipoPoliza);
		$ctaTipoPoliza = cuenta_registros($tipoPoliza);
		if($ctaTipoPoliza == 0)
		{
			liberar_bd();
			$updateTipoPoliza = " CALL sp_sistema_update_tipoPoliza(".$_POST["idTipo"].", 
																	'".($_POST["nombreTipo"])."', 
																	'".($_POST["txtTipo"])."',
																	".$_SESSION[$varIdUser].");";								  
			$update = consulta($updateTipoPoliza);
			
			if($update)
				$res= $msj.tiposPoliza_menuInicio();	
			else
			{
				  $error='No se ha podido editar el tipo de póliza.';
				  $msj = sistema_mensaje("error",$error);
				  $res= $msj.$pagina;					
			  }			  
		  }
		  else
		  {
			  $error='Ya existe un tipo de póliza con este nombre.';
			  $msj = sistema_mensaje("error",$error);
			  $res= $msj.$pagina;
		  }		  
		
		return $res;
	}
	
	function tiposPoliza_eliminar()
	{
		liberar_bd();
		$deleteTipoPoliza = "CALL sp_sistema_delete_tipoPoliza('".$_POST["idTipo"]."', ".$_SESSION[$varIdUser].");";
		$delete = consulta($deleteTipoPoliza);
		if($delete)
			$res= $msj.tiposPoliza_menuInicio();
		else
		{
			$error='No se ha podido eliminar el tipo de póliza.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.tiposPoliza_menuInicio();
		}
		
		return $res;
	}

?>