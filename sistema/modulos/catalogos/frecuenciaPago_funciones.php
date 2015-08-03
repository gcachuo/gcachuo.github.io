<?php

	function frecuenciaPago_menuInicio()
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
		$selectFrecuencia = "SELECT
								frec.id_frecuencia AS id,
								frec.nombre_frecuencia AS nombre,
								frec.dias_frecuencia AS dias,
								frec.descripcion_frecuencia AS txt,
								frec.estatus_frecuencia AS estatus
							FROM
								frecuencia AS frec
							WHERE
								frec.id_agente = ".$_SESSION['idAgenteActual']."
							AND frec.estatus_frecuencia <> 0";
		
		$frecuencia = consulta($selectFrecuencia);
		
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
								<input type="hidden" id="idFrecuencia" name="idFrecuencia" value="" />
								<input type="hidden" name="txtIndice" />';
							  if($btnAlta)
								  $pagina.= '	<i title="Nueva frecuencia de pago" style="cursor:pointer;" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
													Nueva frecuencia de pago
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
														<th>N&Uacute;M. DIAS</th>
														<th>DESCRIPCI&Oacute;N</th>
														<th>ESTATUS</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>
													<tr>
														<td>ANUAL</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="tdAcciones"></td>											
													</tr>
													<tr>
														<td>BIMESTRAL</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="tdAcciones"></td>											
													</tr>
													<tr>
														<td>TRIMESTRAL</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="tdAcciones"></td>											
													</tr>
													<tr>
														<td>SEMESTRAL</td>
														<td></td>
														<td></td>
														<td></td>
														<td class="tdAcciones"></td>											
													</tr>';
												while($frec = siguiente_registro($frecuencia))
												{
													//ESTATUS DE FRECUENCIAS DE PAGO
													switch($frec['estatus'])
													{
														case 1:
															$estatus = 'ACTIVO';
														break;
														case 2:
															$estatus = 'INACTIVO';
														break;
													}
													
													$pagina.= ' <tr>
																	<td>'.utf8_convertir($frec["nombre"]).'</td>
																	<td>'.number_format($frec["dias"]).'</td>
																	<td>'.utf8_convertir($frec["txt"]).'</td>
																	<td>'.$estatus.'</td>
																	<td class="tdAcciones">';
																	if($btnEdita)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="document.frmSistema.idFrecuencia.value='.$frec["id"].';navegar(\'Editar\');">
																						<i title="Editar" class="fa fa-pencil"></i>
																					</a>';																																		
																	if($btnElimina)
																		$pagina.= '	<a class="btn btn-default-alt btn-sm" onClick="if(confirm(\'Desea eliminar esta frecuencia de pago\')){document.frmSistema.idFrecuencia.value='.$frec["id"].'; navegar(\'Eliminar\');}">
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
	
	function frecuenciaPago_formularioNuevo()
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
														<label for="nombreTipo" class="col-sm-4 control-label">N&uacute;mero de dias:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="numTipo" name="numTipo" maxlength="100"/>
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
	
	function frecuenciaPago_formularioEditar()
	{
		//DATOS DE LA FRECUENCIA DE PAGO
		liberar_bd();
		$selectDatosfrecuenciaPago = 'CALL sp_sistema_select_datos_frecuenciaPago('.$_POST["idFrecuencia"].');';
		$datosFrecuenciaPago = consulta($selectDatosfrecuenciaPago);
		$frec = siguiente_registro($datosFrecuenciaPago);
		
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
								<input type="hidden" id="idFrecuencia" name="idFrecuencia" value="'.$_POST["idFrecuencia"].'" />									
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
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.utf8_convertir($frec["nombre"]).'"/>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="row">											
											<div class="col-sm-12">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-4 control-label">N&uacute;mero de dias:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="numTipo" name="numTipo" maxlength="100" value="'.$frec["dias"].'"/>
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
															<textarea class="form-control autosize" name="txtTipo" id="txtTipo">'.utf8_convertir($frec["txt"]).'</textarea>											
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
	
	function frecuenciaPago_guardar()
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
														<label for="nombreTipo" class="col-sm-4 control-label">N&uacute;mero de dias:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="numTipo" name="numTipo" maxlength="100" value="'.$_POST["numTipo"].'"/>
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
		  $selectFrecuenciaPago =  "CALL sp_sistema_select_frecuencia_nombre('".($_POST["nombreTipo"])."', ".$_SESSION['idAgenteActual'].");";		
		  $frecuenciaPago = consulta($selectFrecuenciaPago);
		  $ctaFrecuenciaPago = cuenta_registros($frecuenciaPago);
		  if($ctaFrecuenciaPago == 0)
		  {
			  liberar_bd();
			  $insertFrecuenciaPago = " CALL sp_sistema_insert_frecuenciaPago(	".$_SESSION['idAgenteActual'].",
																				'".($_POST["nombreTipo"])."', 
																				".$_POST["numTipo"].", 
																				'".($_POST["txtTipo"])."',
																				".$_SESSION[$varIdUser].");";											  
			  $insert = consulta($insertFrecuenciaPago);
			  
			  if($insert)
			  		$res= $msj.frecuenciaPago_menuInicio();	
			  else
			  {
				  $error='No se ha podido guardar la frecuencia de pago.';
				  $msj = sistema_mensaje("error",$error);
				  $res= $msj.$pagina;									
			  }
		  }
		  else
		  {
			  $error='Ya existe una frecuencia de pago con este nombre.';
			  $msj = sistema_mensaje("error",$error);
			  $res= $msj.$pagina;
		  }
		
		return $res;
	}
	
	function frecuenciaPago_editar()
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
								<input type="hidden" id="idFrecuencia" name="idFrecuencia" value="'.$_POST["idFrecuencia"].'" />											
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
														<label for="nombreTipo" class="col-sm-4 control-label">N&uacute;mero de dias:</label>
														<div class="col-sm-8">
															<input type="text" class="form-control" id="numTipo" name="numTipo" maxlength="100" value="'.$_POST["numTipo"].'"/>
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
							  	
		liberar_bd();
		$selectFrecuenciaPago =  "CALL sp_sistema_select_frecuenciaPago_nombreId('".($_POST["nombreTipo"])."', ".$_SESSION['idAgenteActual'].", ".$_POST["idFrecuencia"].");";			
		$frecuenciaPago = consulta($selectFrecuenciaPago);
		$ctaFrecuenciaPago = cuenta_registros($frecuenciaPago);
		if($ctaFrecuenciaPago == 0)
		{
			liberar_bd();
			$updateFrecuenciaPago = " CALL sp_sistema_update_frecuenciaPago(".$_POST["idFrecuencia"].",
																			'".($_POST["nombreTipo"])."', 
																			".$_POST["numTipo"].", 
																			'".($_POST["txtTipo"])."',
																			".$_SESSION[$varIdUser].");";							  
			$update = consulta($updateFrecuenciaPago);
			
			if($update)
			{
				$res= $msj.frecuenciaPago_menuInicio();					
			}
			else
			{
				$error='No se ha podido editar la frecuencia de pago.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.$pagina;					
			}			  
		}
		else
		{
			$error='Ya existe una frecuencia de pago con este nombre.';
			$msj = sistema_mensaje("error",$error);
			$res= $msj.$pagina;
		}		  
		
		return $res;
	}
	
	function frecuenciaPago_eliminar()
	{
		liberar_bd();
		$deleteFrecuenciaPago = "CALL sp_sistema_delete_frecuenciaPago('".$_POST["idFrecuencia"]."', ".$_SESSION[$varIdUser].");";
		$delete = consulta($deleteFrecuenciaPago);
		if($delete)
			$res= $msj.frecuenciaPago_menuInicio();
		else
		{
			$error='No se ha podido eliminar la frecuencia de pago.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.frecuenciaPago_menuInicio();
		}
		
		return $res;
	}

?>