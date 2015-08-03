<?php

	function ingresosmenuInicio()
	{
		//CHECAMOS FILTROS
		$fechaFormt = date('Y-m-d');
		$primerDia = date('Y-m');
		if($_POST["fechaReporte2"] != '')
		{
			$iparr = split (" - ", $_POST["fechaReporte2"]); 
			$iparr[0] = str_replace('/',"-",$iparr[0]);
			$iparr[1] = str_replace('/',"-",$iparr[1]);
			$fechaIniFormat = normalize_date2($iparr[0]);
			$fechaFinFormat = normalize_date2($iparr[1]);
			$fechaIni = $fechaIniFormat.' 00:00:00';
			$fechaFin = $fechaFinFormat.' 23:59:59';			
		}
		else
		{
			$fechaIni = $primerDia.'-01 00:00:00';
			$fechaFin = $fechaFormt.' 23:59:59';
		}
		
		liberar_bd();
		$selecIngresos = "	SELECT ingr.id_ingresos AS id
								 , ctaBn.banco_ctas_banco AS banco
								 , ingr.cantidad_egreso AS cantidad
								 , ingr.fecha_ingresos AS fecha
								 , ingr.estatus_ingresos AS estatus
								 , ingr.entidad_ingreso AS entidad
							FROM
							  ingresos ingr
							INNER JOIN ctas_banco ctaBn
							ON ingr.id_ctas_banco = ctaBn.id_ctas_banco
							WHERE
							  ingr.estatus_ingresos <> 0
							AND ingr.fecha_ingresos <= '".$fechaFin."'
							AND ingr.fecha_ingresos >= '".$fechaIni."'
							ORDER BY
							  fecha";
							  
		$ingresos = consulta($selecIngresos);		
		
		$pagina = '	<div id="page-heading">	
					  	<h1>Ingresos</h1>
						<div class="options">
							<div class="btn-toolbar">
								<a title="Nuevo ingreso" onclick="navegar(\'Nuevo\')" class="btn btn-warning" >
									<i class="icon-plus-sign"></i>Nuevo ingreso
								</a>
								<input type="hidden" id="idIngreso" name="idIngreso" value="" />
								<input type="hidden" name="txtIndice" />
							</div>
						</div>										
				  	</div>										
				  	<div class="container">	
						<div class="row">
							<div class="col-sm-6">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>Filtrar por</h4>
										<div class="options">   
											<a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
										</div>
									</div>
									<div class="panel-body collapse in">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="fechaReporte2" class="col-sm-3 control-label">Fecha:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control" id="fechaReporte2" name="fechaReporte2" value="'.$_POST["fechaReporte2"].'"/>
													</div>
												</div>																														
											</div>
											<div class="form-group">
												<div class="col-sm-9" style="text-align:right;">
													<div class="btn-group">
														<button type="button" class="btn btn-default" onclick="navegar();">Buscar</button>
													</div>												
												</div>																												
											</div>	
										</div>									
									</div>
								</div>
							</div>							
						</div>						
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4></h4>
										<div class="options">   
											<a href="javascript:;" class="panel-collapse"><i class="icon-chevron-down"></i></a>
										</div>
									</div>
									<div class="panel-body collapse in">
										<div class="table-responsive">
											<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
												<thead>
													<tr>
														<th>FECHA</th>
														<th>BANCO</th>
														<th>ENTIDAD</th>
														<th>CANTIDAD</th>														
														<th>ESTATUS</th>
														<th>ACCIONES</th>
													</tr>
												</thead>	
												<tbody>';
												while($ingr = siguiente_registro($ingresos))
												{
													//ESTATUS DE INGRESOS
													switch($ingr['estatus'])
													{
														case 1:
															$estatus = '<span class="tdNegro">ACTIVA</span>';
														break;
														case 2:
															$estatus = '<span class="tdRojo">PAGADO</span>';
														break;
													}													
																										
													$pagina.= ' <tr>
																	<td>'.normalize_date2($ingr["fecha"]).'</td>
																	<td>'.utf8_convertir($ingr["banco"]).'</td>
																	<td>'.utf8_convertir($ingr["entidad"]).'</td>
																	<td>'.number_format($ingr["cantidad"],2).'</td>																	
																	<td>'.$estatus.'</td>
																	<td>
																		<i title="Ver detalles" style="cursor:pointer;" onclick="document.frmSistema.idIngreso.value='.$ingr["id"].';navegar(\'Ver detalles\');" class="fa fa-eye"></i>																		
																	</td>											
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
	
	function ingresosformularioNuevo()
	{
		//LISTA DE CUENTAS 
		liberar_bd();
		$selectListCuentas = 'CALL sp_sistema_lista_ctas_bancos();';
		$listaCuentas = consulta($selectListCuentas);
		while($cue = siguiente_registro($listaCuentas))
		{
			$optCuentas .= '<option value="'.$cue["id"].'">'.utf8_convertir($cue["nombre"]).'('.$cue["numero"].')</option>';
		}
						
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
										<h4>Nuevo ingreso</h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="entidadEgr" class="col-sm-3 control-label">Entidad:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="entidadEgr" name="entidadEgr" maxlength="100"/>
												</div>
											</div>
											<div class="form-group">
												<label for="idCuenta" class="col-sm-3 control-label">Cuenta:</label>
												<div class="col-sm-6">
													<select id="idCuenta" name="idCuenta" style="width:100% !important" class="selectSerch">
														'.$optCuentas.'
													</select>
												</div>
											</div>
											<div class="form-group">
												<label for="montoEgr" class="col-sm-3 control-label">Monto:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon">$</span>
														<input type="text" class="form-control" id="montoEgr" name="montoEgr"/>
													</div>
												</div>																														
											</div>
											<div class="form-group">
												<label for="datepicker" class="col-sm-3 control-label">Fecha de ingreso:</label>
												<div class="col-sm-6">
													<div class="input-group">
														<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
														<input type="text" class="form-control" id="datepicker" name="datepicker"/>
													</div>
												</div>																														
											</div>
											<div class="form-group">
												<label for="conceptoEgr" class="col-sm-3 control-label">Concepto:</label>
												<div class="col-md-6">	
													<textarea class="form-control autosize" name="conceptoEgr" id="conceptoEgr"></textarea>											
												</div>													
											</div>										
											<div class="form-group">
												<label for="txtEgreso" class="col-sm-3 control-label">Observaciones:</label>
												<div class="col-md-6">	
													<textarea class="form-control autosize" name="txtEgreso" id="txtEgreso"></textarea>											
												</div>													
											</div>									
										</div>										
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<div class="btn-toolbar">
													<i class="btn-primary btn" onclick="nuevoIngreso(\'Guardar\');">Guardar</i>
													<i class="btn-default btn" onclick="navegar();">Cancelar</i>
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
	
	function ingresosguardar()
	{	
		if($_POST["datepicker"] == '')
			$_POST["datepicker"] = '01-01-1000';
			
		$_POST["datepicker"] = str_replace('/',"-",$_POST["datepicker"]);	
		$fechaPago = normalize_date2($_POST["datepicker"]);
		$fechaPago = $fechaPago.' 00:00:00';
		
		liberar_bd();
	 	$insertIngreso = " CALL sp_sistema_insert_ingreso(	'".$_POST["entidadEgr"]."',
															".$_POST["idCuenta"].",
															".$_POST["montoEgr"].", 
															'".$fechaPago."', 
															'".($_POST["conceptoEgr"])."',
															'".($_POST["txtEgreso"])."', 
															".$_SESSION['idUsuarioMay'].");";									  
	  	$insert = consulta($insertIngreso);
	  
	  	if($insert)
	  	{
			//ACTUALIZAMOS EL SALDO DE LA CUENTA DE BANCO
			//SALDO ACTUAL DE LA CUENTA
			liberar_bd();
			$selectActualSaldoCuenta = 'CALL sp_sistema_select_datos_cuentas('.$_POST["idCuenta"].');';
			$actualSaldoCuenta = consulta($selectActualSaldoCuenta);
			$actSalCue = siguiente_registro($actualSaldoCuenta);
			$nuevoSaldo = $_POST["montoEgr"] + $actSalCue["monto"];
			
			//GUARDAMOS LA CANTIDAD EN LA CUENTA
			liberar_bd();
			$updateSaldoCuenta = 'CALL sp_sistema_update_saldo_cuenta(	'.$_POST["idCuenta"].',
																		"'.$nuevoSaldo.'",
																		'.$_SESSION["idUsuarioMay"].')';
																		
			$saldoCuenta = consulta($updateSaldoCuenta);
						
		  	$res= $msj.ingresosmenuInicio();					
	  	}
	  	else
	  	{
		  	$error='No se ha podido guardar el ingreso.';
		  	$msj = sistema_mensaje("error",$error);
			
		  	//LISTA DE CUENTAS 
			liberar_bd();
			$selectListCuentas = 'CALL sp_sistema_lista_ctas_bancos();';
			$listaCuentas = consulta($selectListCuentas);
			while($cue = siguiente_registro($listaCuentas))
			{
				$optCuentas .= '<option value="'.$cue["id"].'">'.utf8_convertir($cue["nombre"]).'('.$cue["numero"].')</option>';
			}
			
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
											<h4>Nuevo ingreso</h4>
										</div>
										<div class="panel-body" style="border-radius: 0px;">										
											<div class="form-horizontal">
												<div class="form-group">
													<label for="entidadEgr" class="col-sm-3 control-label">Entidad:</label>
													<div class="col-sm-6">
														<input type="text" class="form-control" id="entidadEgr" name="entidadEgr" maxlength="100"/>
													</div>
												</div>
												<div class="form-group">
													<label for="idCuenta" class="col-sm-3 control-label">Cuenta:</label>
													<div class="col-sm-6">
														<select id="idCuenta" name="idCuenta" style="width:100% !important" class="selectSerch">
															'.$optCuentas.'
														</select>
													</div>
												</div>
												<div class="form-group">
													<label for="montoEgr" class="col-sm-3 control-label">Monto:</label>
													<div class="col-sm-6">
														<div class="input-group">
															<span class="input-group-addon">$</span>
															<input type="text" class="form-control" id="montoEgr" name="montoEgr"/>
														</div>
													</div>																														
												</div>
												<div class="form-group">
													<label for="datepicker" class="col-sm-3 control-label">Fecha de ingreso:</label>
													<div class="col-sm-6">
														<div class="input-group">
															<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
															<input type="text" class="form-control" id="datepicker" name="datepicker"/>
														</div>
													</div>																														
												</div>
												<div class="form-group">
													<label for="conceptoEgr" class="col-sm-3 control-label">Concepto:</label>
													<div class="col-md-6">	
														<textarea class="form-control autosize" name="conceptoEgr" id="conceptoEgr"></textarea>											
													</div>													
												</div>										
												<div class="form-group">
													<label for="txtEgreso" class="col-sm-3 control-label">Observaciones:</label>
													<div class="col-md-6">	
														<textarea class="form-control autosize" name="txtEgreso" id="txtEgreso"></textarea>											
													</div>													
												</div>									
											</div>									
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<div class="btn-toolbar">
														<i class="btn-primary btn" onclick="nuevoIngreso(\'Guardar\');">Guardar</i>
														<i class="btn-default btn" onclick="navegar();">Cancelar</i>
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
	
	function ingresos_detalles()
	{
		//DATOS DEL INGRESO
		liberar_bd();
		$selectDatosIngreso = 'CALL sp_sistema_select_datos_ingreso('.$_POST["idIngreso"].');';
		$datosIngreso = consulta($selectDatosIngreso);
		$ingr = siguiente_registro($datosIngreso);
		
		//CUENTA
		liberar_bd();
		$selectCuenta = 'CALL sp_sistema_select_datos_cuentas('.$ingr["idCta"].');';
		$cuenta = consulta($selectCuenta);
		$cue = siguiente_registro($cuenta);
				
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
										<h4>Detalles del ingreso</h4>
									</div>
									<div class="panel-body" style="border-radius: 0px;">
										<div class="form-horizontal">
											<div class="form-group">
												<label for="entidadEgr" class="col-sm-3 control-label">Entidad:</label>
												<div class="col-sm-6">
													<input type="text" readonly="readonly" class="form-control" id="entidadEgr" name="entidadEgr" maxlength="100" value="'.utf8_convertir($ingr["entidad"]).'"/>
												</div>
											</div>
											<div class="form-group">
												<label for="idCuenta" class="col-sm-3 control-label">Cuenta:</label>
												<div class="col-sm-6">
													<input type="text" readonly="readonly" class="form-control" id="idCuenta" name="idCuenta" maxlength="100" value="'.utf8_convertir($ingr["banco"]).'('.$ingr["numero"].')"/>
												</div>
											</div>
											<div class="form-group">
												<label for="montoEgr" class="col-sm-3 control-label">Monto:</label>
												<div class="col-sm-6">
													<input type="text" readonly="readonly" class="form-control" id="montoEgr" name="montoEgr" maxlength="100" value="'.number_format($ingr["cantidad"],2).'"/>
												</div>				
											</div>
											<div class="form-group">
												<label for="datepicker" class="col-sm-3 control-label">Fecha de egreso:</label>
												<div class="col-sm-6">
													<input type="text" readonly="readonly" class="form-control" id="datepicker" name="datepicker" maxlength="100" value="'.normalize_date($ingr["fecha"]).'"/>
												</div>						
											</div>	
											<div class="form-group">
												<label for="conceptoEgr" class="col-sm-3 control-label">Concepto:</label>
												<div class="col-md-6">	
													<textarea readonly="readonly" class="form-control autosize" name="conceptoEgr" id="conceptoEgr">'.utf8_convertir($ingr["concepto"]).'</textarea>											
												</div>													
											</div>									
											<div class="form-group">
												<label for="txtEgreso" class="col-sm-3 control-label">Observaciones:</label>
												<div class="col-md-6">	
													<textarea readonly="readonly" class="form-control autosize" name="txtEgreso" id="txtEgreso">'.utf8_convertir($ingr["txt"]).'</textarea>											
												</div>													
											</div>									
										</div>
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<div class="btn-toolbar">
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
	
	function ingresoseliminar()
	{
		liberar_bd();
		$deleteIngreso = "CALL sp_sistema_delete_ingreso('".$_POST["idIngreso"]."');";
		$delete = consulta($deleteIngreso);
		if($delete)
		{
			$res= $msj.ingresosmenuInicio();
			
		}
		else
		{
			$error='No se ha podido eliminar el ingreso.';
			$msj = sistema_mensaje("exito",$error);
			$res= $msj.ingresosmenuInicio();
		}
		
		return $res;
	}

?>