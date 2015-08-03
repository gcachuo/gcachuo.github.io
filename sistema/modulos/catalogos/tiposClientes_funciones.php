<?php

	function clientes_menuInicio()
	{
		$btnEdita = false;
		
		//PREMISOS DE ACCIONES
		liberar_bd();
		$selectPermisosAcciones = 'CALL sp_sistema_select_permisos_acciones_modulo('.$_SESSION["idPerfil"].', '.$_SESSION["mod"].');';
		$permisosAcciones = consulta($selectPermisosAcciones);
		while($acciones = siguiente_registro($permisosAcciones))
		{
			switch(utf8_convertir($acciones["accion"]))
			{
				case 'Modificación':
					$btnEdita = true;					
				break;
			}
		}
		
		liberar_bd();
		$selectTipoClientes = "	SELECT tiposCli.id_tipos_cliente AS id
									 , tiposCli.nombre_tipos_cliente AS nombre
									 , tiposCli.estatus_tipos_cliente AS estatus
								FROM
								  tipos_cliente tiposCli
								WHERE
									tiposCli.id_tipos_cliente <> 1
								AND
								  	tiposCli.estatus_tipos_cliente <> 0";
							  
		/*$paginar = paginar($selectTipoClientes,50);
		$selectTipoClientes .= " LIMIT ".$paginar[0].",".$paginar[2];	*/	
		$tiposClientes = consulta($selectTipoClientes);
		
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
								<input type="hidden" id="nombreTipo" name="nombreTipo" value="" />
								<input type="hidden" name="txtIndice" />
							</div>
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
											<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered datatables" id="example">
												<thead>
													<tr>
														<th>NOMBRE</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>';
												while($cli = siguiente_registro($tiposClientes))
												{
													$pagina.= ' <tr>
																	<td>'.utf8_convertir($cli["nombre"]).'</td>
																	<td>';
																	if($btnEdita)
																		$pagina.=' <a class="btn btn-default-alt btn-sm" onclick="document.frmSistema.idTipo.value='.$cli["id"].';navegar(\'Editar\');">
																					  <i title="Editar" class="fa fa-pencil"></i>
																				  </a> ';															
													$pagina.='	  	</td>											
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
	
	function clientes_formularioNuevo()
	{
		$pagina = '	<div id="page-heading">	
							<h1></h1>
							<div class="options">
								<div class="btn-toolbar">										
								</div>
							</div>										
						</div>
						<div class="container">							
							<div class="row">
								<div class="col-sm-12">
									<div class="panel panel-danger">
										<div class="panel-heading">
											<h4>Nuevo tipo de cliente</h4>
										</div>
										<div class="panel-body" style="border-radius: 0px;">
											<div class="form-horizontal">
												<div class="form-group">
													<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100"/>
													</div>
												</div>																					
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<i class="btn-primary btn" onclick="nuevoTipoCliente(\'Guardar\');">Guardar</i>
													<i class="btn-default btn" onclick="navegar();">Cancelar</i>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>';
		
		return $pagina;
	}
	
	function clientes_formularioEditar()
	{
		//DATOS DEL TIPO DE CLIENTE
		liberar_bd();
		$selectDatosTipo = 'CALL sp_sistema_select_datos_tipoCliente('.$_POST["idTipo"].');';
		$datosTipo = consulta($selectDatosTipo);
		$tip = siguiente_registro($datosTipo);
		
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
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>Editar tipo de cliente</h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.utf8_convertir($tip["nombre"]).'"/>
												</div>
											</div>
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
													<i class="btn-success btn" onclick="nuevoTipoCliente(\'GuardarEdit\');">Guardar</i>
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
	
	function clientes_guardar()
	{		
		  liberar_bd();
		  $selectTipoCliente =  "CALL sp_sistema_select_tipoCliente_nombre('".($_POST["nombreTipo"])."');";			
		  $tipo = consulta($selectTipoCliente);
		  $ctaTipo = cuenta_registros($tipo);
		  if($ctaTipo == 0)
		  {
			  liberar_bd();
			  $insertSucursal = " CALL sp_sistema_insert_tipoCliente('".($_POST["nombreTipo"])."');";									  
			  $insert = consulta($insertSucursal);
			  
			  if($insert)
			  {
				  $res= $msj.clientes_menuInicio();					
			  }
			  else
			  {
				  $error='No se ha podido guardar el tipo de cliente.';
				  $msj = sistema_mensaje("error",$error);
				 $pagina = '	<div id="page-heading">	
									<h1></h1>
									<div class="options">
										<div class="btn-toolbar">										
										</div>
									</div>										
								</div>
								<div class="container">							
									<div class="row">
										<div class="col-sm-12">
											<div class="panel panel-danger">
												<div class="panel-heading">
													<h4>Nuevo tipo de cliente</h4>
												</div>
												<div class="panel-body" style="border-radius: 0px;">
													<div class="form-horizontal">
														<div class="form-group">
															<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
															<div class="col-sm-6">
																<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
															</div>
														</div>									
													</div>
												</div>
												<div class="panel-footer">
													<div class="row">
														<div class="col-sm-6 col-sm-offset-3">
															<i class="btn-primary btn" onclick="nuevoTipoCliente(\'Guardar\');">Guardar</i>
															<i class="btn-default btn" onclick="navegar();">Cancelar</i>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';
				
				return $msj.$pagina;												
			  }
		  }
		  else
		  {
			  $error='Ya existe una tipo de cliente con este nombre.';
			  $msj = sistema_mensaje("error",$error);
			  $pagina = '	<div id="page-heading">	
								<h1></h1>
								<div class="options">
									<div class="btn-toolbar">										
									</div>
								</div>										
							</div>
							<div class="container">							
								<div class="row">
									<div class="col-sm-12">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<h4>Nuevo tipo de cliente</h4>
											</div>
											<div class="panel-body" style="border-radius: 0px;">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
														</div>
													</div>									
												</div>
											</div>
											<div class="panel-footer">
												<div class="row">
													<div class="col-sm-6 col-sm-offset-3">
														<i class="btn-primary btn" onclick="nuevoTipoCliente(\'Guardar\');">Guardar</i>
														<i class="btn-default btn" onclick="navegar();">Cancelar</i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';
		
			  $res= $msj.$pagina;
		  }
		
		return $res;
	}
	
	function clientes_editar()
	{		
		  liberar_bd();
		  $selectTipo =  "CALL sp_sistema_select_tipoCliente_nombreId(".$_POST["idTipo"].", '".($_POST["nombreTipo"])."');";			
		  $tipo = consulta($selectTipo);
		  $ctaTipo = cuenta_registros($tipo);
		  if($ctaTipo == 0)
		  {
			  liberar_bd();
			  $insertTipo = " CALL sp_sistema_update_tipoCliente(".$_POST["idTipo"].", '".($_POST["nombreTipo"])."');";									  
			  $insert = consulta($insertTipo);
			  
			  if($insert)
			  {
				  $res= $msj.clientes_menuInicio();					
			  }
			  else
			  {
				  $error='No se ha podido editar el tipo de cliente.';
				  $msj = sistema_mensaje("error",$error);
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
										<div class="col-sm-12">
											<div class="panel panel-danger">
												<div class="panel-heading">
													<h4>Editar tipo de cliente</h4>
												</div>
												<div class="panel-body" style="border-radius: 0px;">
													<div class="form-horizontal">
														<div class="form-group">
															<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
															<div class="col-sm-6">
																<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
															</div>
														</div>									
													</div>
												</div>
												<div class="panel-footer">
													<div class="row">
														<div class="col-sm-12">
															<div class="btn-toolbar btnsGuarCan">
																<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
																<i class="btn-success btn" onclick="nuevoTipoCliente(\'GuardarEdit\');">Guardar</i>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>';				 
							
			  		$res= $msj.$pagina;					
			  }			  
		  }
		  else
		  {
			  $error='Ya existe una tipo de cliente con este nombre.';
			  $msj = sistema_mensaje("error",$error);
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
									<div class="col-sm-12">
										<div class="panel panel-danger">
											<div class="panel-heading">
												<h4>Editar tipo de cliente</h4>
											</div>
											<div class="panel-body" style="border-radius: 0px;">
												<div class="form-horizontal">
													<div class="form-group">
														<label for="nombreTipo" class="col-sm-3 control-label">Nombre:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="nombreTipo" name="nombreTipo" maxlength="100" value="'.$_POST["nombreTipo"].'"/>
														</div>
													</div>									
												</div>
											</div>
											<div class="panel-footer">
												<div class="row">
													<div class="col-sm-12">
														<div class="btn-toolbar btnsGuarCan">
															<i class="btn-danger btn" onclick="navegar();">Cancelar</i>
															<i class="btn-success btn" onclick="nuevoTipoCliente(\'GuardarEdit\');">Guardar</i>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>';		 
							
			  $res= $msj.$pagina;
		  }		  
		
		return $res;
	}
	
	function clientes_eliminar()
	{
		liberar_bd();
		$deleteCliente = "CALL sp_sistema_delete_tipoClientes('".$_POST["idTipo"]."');";
		$delete = consulta($deleteCliente);
		if($delete)
		{
			$res= $msj.clientes_menuInicio();
			
		}
		else
		{
			$error='No se ha podido eliminar el tipo de cliente.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.clientes_menuInicio();
		}
		
		return $res;
	}
	
	function clientes_descuentos()
	{
		if($_POST["idTipo"] != '')
		{
			$_SESSION["idTipoActual"] = $_POST["idTipo"];
			$_SESSION["nombreTipoActual"] = $_POST["nombreTipo"];
		}
		
		//LISTA DE ESTILOS
		liberar_bd();
		$selectListaEstilos = 'CALL sp_sistema_lista_estilos();';
		$listaEstilos = consulta($selectListaEstilos);
					
		$pagina = '	<div id="page-heading">	
					  	<h1>Descuentos para tipo de cliente: '.$_SESSION["nombreTipoActual"].'</h1>
						<div class="options">
							<div class="btn-toolbar">								
							</div>
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
														<th>IM&Aacute;GEN</th>
														<th>ESTILO</th>
														<th>TIPO DE ETIQUETA</th>
														<th>DESCUENTO(&#37;)</th>														
													</tr>
												</thead>	
												<tbody>';						
												while($est = siguiente_registro($listaEstilos))
												{
													//CHECAMOS SI EXISTE UN DESCUENTO YA PARA EL ESTILO
													liberar_bd();
													$selectDescuentoTipo = 'CALL sp_sistema_select_descuento_tipo_id('.$_SESSION["idTipoActual"].', '.$est["id"].');';	
													$descuentoTipo = consulta($selectDescuentoTipo);
													$ctaDescuentoTipo = cuenta_registros($descuentoTipo);
													if($ctaDescuentoTipo != 0)
													{
														$descTipo = siguiente_registro($descuentoTipo);
														$valor = $descTipo["valor"];
													}
													else
														$valor = 0;
													$pagina.= ' <tr>
																	<td>
																		<img src="../imagenes/estilos/'.$est["imagen"].'" width="50px"/>
																	</td>
																	<td>'.utf8_convertir($est["nombre"]).'</td>
																	<td>'.utf8_convertir($est["etiqueta"]).'</td>
																	<td>
																		<input style="width:60px !important;" type="text" class="form-control" name="descuento['.$est["id"].']" id="descuento" value="'.$valor.'" maxlength="5"/>
																	</td>																											
																</tr>';
													 $j++;
												}
						
		$pagina.= '								</tbody>												
											</table>
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar">
													<i class="btn-primary btn" onclick="navegar(\'Agregar\');">Guardar</i>
													<i class="btn-default btn" onclick="navegar();">Regresar</i>
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
	
	function clientes_agregar()
	{
		//INSERTAMOS O ACTUALIZAMOS LOS DESCUENTOS POR TIPO
		liberar_bd();
		$descuento = $_POST['descuento'];
		//LISTA DE ESTILOS
		liberar_bd();
		$selectListaEstilos = 'CALL sp_sistema_lista_estilos();';
		$listaEstilos = consulta($selectListaEstilos);
		while($est = siguiente_registro($listaEstilos))
		{
			$i = $est["id"];
			if(!is_numeric($descuento[$i]) || $descuento[$i] == '')
				$descuento[$i] = 0;
			
			//CHECAMOS SI EXISTE UN DESCUENTO YA PARA EL ESTILO
			liberar_bd();
			$selectDescuentoTipo = 'CALL sp_sistema_select_descuento_tipo_id('.$_SESSION["idTipoActual"].', '.$i.');';	
			$descuentoTipo = consulta($selectDescuentoTipo);
			$ctaDescuentoTipo = cuenta_registros($descuentoTipo);
			if($ctaDescuentoTipo != 0)
			{
				//ACTUALIZAMOS EL YA EXISTENTE
				liberar_bd();
				$updateDescuentoTipo = 'CALL sp_sistema_update_descuento_tipo('.$_SESSION["idTipoActual"].', '.$i.', '.$descuento[$i].', '.$_SESSION["idUsuarioAddhoc"].');';
				$updateDesc = consulta($updateDescuentoTipo);
			}
			else
			{
				//INSERTAMOS EL NUEVO DESCUENTO
				liberar_bd();
				$insertDescuentoTipo = 'CALL sp_sistema_insert_descuento_tipo('.$_SESSION["idTipoActual"].', '.$i.', '.$descuento[$i].', '.$_SESSION["idUsuarioAddhoc"].');';
				$insertDesc = consulta($insertDescuentoTipo);
			}			
		}
		
		$res= $msj.clientes_menuInicio();
		return $res;
	}

?>