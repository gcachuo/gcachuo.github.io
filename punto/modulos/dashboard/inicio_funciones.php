<?php

	function inicio_menuInicio()
	{
		$pagina = '	<div id="page-heading">	
							<h1>Dashboard</h1>
							<div class="options">
								<div class="btn-toolbar">
									<input type="hidden" id="idCuentaReporte" name="idCuentaReporte">
									<input type="hidden" id="idCliente" name="idCliente" value="" />
									<!--<button class="btn btn-default" id="daterangepicker2">
										<i class="fa fa-calendar-o"></i> 
										<span class="hidden-xs hidden-sm">December 9, 2013 - January 8, 2014</span> <b class="caret"></b>
									</button>-->
								</div>
							</div>										
						</div>	
						<div class="container">	
							'.$contenidoTablero.'			
						</div>';
		
		
		return $pagina;		
		
	}
	
	function inicio_recordatorios()
	{
		//CHECAMOS QUE CONTENIDO SE CARGARA
		if($_POST['seccionCuenta'] != '')
		{
			switch($_POST['seccionCuenta'])
			{
				case 'todos':				
					$infoMenuCuenta = '	<li class="active"><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'todos\')">Todos</a></li>
										<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'record\')">Por enviar</a></li>
										<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'enviado\')">Enviados</a></li>';
					
					$btnEnviar = '	<a title="Enviar recordatorios" onclick="navegar(\'Enviar\')" class="btn btn-warning" >
										<i class="icon-plus-sign"></i>Enviar recordatorios
									</a>';	
																									
					$infoCuenta = record_todos();
				break;	
				case 'record':				
					$infoMenuCuenta = '	<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'todos\')">Todos</a></li>
										<li class="active"><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'record\')">Por enviar</a></li>
										<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'enviado\')">Enviados</a></li>';
					
					$btnEnviar = '	<a title="Enviar recordatorios" onclick="navegar(\'Enviar\')" class="btn btn-warning" >
										<i class="icon-plus-sign"></i>Enviar recordatorios
									</a>';	
																									
					$infoCuenta = record_enviar();
				break;				
				case 'enviado':
					$infoMenuCuenta = '	<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'todos\')">Todos</a></li>
										<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'record\')">Por enviar</a></li>
										<li class="active"><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'enviado\')">Enviados</a></li>';
					
					$btnEnviar = '';
											
					$infoCuenta = record_enviados();
				break;	
				default :					
					$infoMenuCuenta = '	<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'todos\')">Todos</a></li>
										<li class="active"><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'record\')">Por enviar</a></li>
										<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'enviado\')">Enviados</a></li>';
											
					$btnEnviar = '	<a title="Enviar recordatorios" onclick="navegar(\'Enviar\')" class="btn btn-warning" >
										<i class="icon-plus-sign"></i>Enviar recordatorios
									</a>';	
					
					$infoCuenta = record_enviar();
				break;
			}
		}
		else
		{
			$infoMenuCuenta = '	<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'todos\')">Todos</a></li>
								<li class="active"><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'record\')">Por enviar</a></li>
								<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'Recordatorios\'; navegarCuenta(\'enviado\')">Enviados</a></li>';
											
			$btnEnviar = '	<a title="Enviar recordatorios" onclick="navegar(\'Enviar\')" class="btn btn-warning" >
								<i class="icon-plus-sign"></i>Enviar recordatorios
							</a>';	
			
			$infoCuenta = record_enviar();
		}	
		
		$pagina = '	<div id="page-heading">	
					  	<h1>Recordatorios</h1>
						<div class="options">
							<div class="btn-toolbar">
								'.$btnEnviar.'	
								<input type="hidden" id="seccionCuenta" name="seccionCuenta" value="'.$_POST["seccionCuenta"].'" />
								<input type="hidden" id="idEmpleado" name="idEmpleado" value="" />	
								<input type="hidden" id="idCliente" name="idCliente" value="" />
								<input type="hidden" name="txtIndice" />
							</div>							
						</div>										
				  	</div>									
				  	<div class="container">						
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>
											<ul class="nav nav-tabs">
												'.$infoMenuCuenta.'
											</ul>
										</h4>
										<h4></h4>
										<div class="options">   
											<a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
										</div>
									</div>
									<div class="panel-body collapse in">
										<div class="tab-content">
											'.$infoCuenta.'												
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>';
							
		return $pagina;
	}
	
	function record_todos()
	{
		liberar_bd();
		$selectRecordEnviar = 'CALL sp_sistema_select_recordatorios_todos();';
		$recordaEnviar = consulta($selectRecordEnviar);
		$ctaRecordEnviar = cuenta_registros($recordaEnviar);		
		
		$datosClientes  = '		<div class="tab-pane active">
									<div class="table-responsive">
										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
											<thead>
												<tr>
													<th>FECHA PARA RECORDATORIO</th>
													<th>P&Oacute;LIZA</th>
													<th>CLIENTE</th>
													<th>ASUNTO</th>
												</tr>
											</thead>	
											<tbody>';
											while($record = siguiente_registro($recordaEnviar))
											{
												$datosClientes.= '	<tr>
																		<td>'.normalize_date2($record["fecha"]).'</td>
																		<td>'.$record["idPoliza"].'</td>
																		<td>'.utf8_convertir($record["cliente"]).'</td>
																		<td>'.utf8_convertir($record["asunto"]).'</td>																													
																	</tr>';	
											}	
					
			$datosClientes.= '				</tbody>											
										</table>
									</div>
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>';
		
		return $datosClientes;
	}
	
	function record_enviar()
	{
		//CORREOS POR ENVIAR
		$fechaRecord = date("Y-m-d");
		$fechaRecord = $fechaRecord.' 23:59:59';
		liberar_bd();
		$selectRecordEnviar = 'CALL sp_sistema_select_recordatorios_porEnviar("'.$fechaRecord.'");';
		$recordaEnviar = consulta($selectRecordEnviar);
		$ctaRecordEnviar = cuenta_registros($recordaEnviar);		
		
		$datosClientes  = '		<div class="tab-pane active">
									<div class="table-responsive">
										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
											<thead>
												<tr>
													<th>FECHA PARA RECORDATORIO</th>
													<th>P&Oacute;LIZA</th>
													<th>CLIENTE</th>
													<th>ASUNTO</th>
												</tr>
											</thead>	
											<tbody>';
											while($record = siguiente_registro($recordaEnviar))
											{
												$datosClientes.= '	<tr>
																		<td>'.normalize_date2($record["fecha"]).'</td>
																		<td>'.$record["idPoliza"].'</td>
																		<td>'.utf8_convertir($record["cliente"]).'</td>
																		<td>'.utf8_convertir($record["asunto"]).'</td>																													
																	</tr>';	
											}	
					
			$datosClientes.= '				</tbody>											
										</table>
									</div>
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>';
		
		return $datosClientes;
	}
	
	function record_enviados()
	{
		liberar_bd();
		$selectRecordEnviar = 'CALL sp_sistema_select_recordatorios_enviados();';
		$recordaEnviar = consulta($selectRecordEnviar);
		$ctaRecordEnviar = cuenta_registros($recordaEnviar);		
		
		$datosClientes  = '		<div class="tab-pane active">
									<div class="table-responsive">
										<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="example">
											<thead>
												<tr>
													<th>FECHA PARA RECORDATORIO</th>
													<th>P&Oacute;LIZA</th>
													<th>CLIENTE</th>
													<th>ASUNTO</th>
												</tr>
											</thead>	
											<tbody>';
											while($record = siguiente_registro($recordaEnviar))
											{
												$datosClientes.= '	<tr>
																		<td>'.normalize_date2($record["fecha"]).'</td>
																		<td>'.$record["idPoliza"].'</td>
																		<td>'.utf8_convertir($record["cliente"]).'</td>
																		<td>'.utf8_convertir($record["asunto"]).'</td>																													
																	</tr>';	
											}	
					
			$datosClientes.= '				</tbody>											
										</table>
									</div>
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>
								<div class="tab-pane">
								</div>';
		
		return $datosClientes;
	}
	
	function inicio_recordatoriosEnviar()
	{
		//CORREOS POR ENVIAR
		$fechaRecord = date("Y-m-d");
		$fechaRecord = $fechaRecord.' 23:59:59';
		liberar_bd();
		$selectRecordEnviar = 'CALL sp_sistema_select_recordatorios_enviarCorreo("'.$fechaRecord.'");';
		$recordaEnviar = consulta($selectRecordEnviar);
		$ctaRecordEnviar = cuenta_registros($recordaEnviar);
		//ENVIO DE CORREOS
		if($ctaRecordEnviar != 0)
		{
			while($record = siguiente_registro($recordaEnviar))
			{			
				$name = $record['nombre'];
				$email = $record['correo'];
				$message = $record['txt'];
				$asubject = $record['asunto'];
		
				// ====== Your mail here  ====== //
				$to = 'francisco.almanza@ruizquezada.com';	
				//sender
				$from = $name . ' <' . $email . '>';		
				//subject and the html message
				$subject = $asubject;	
				$message = '
				<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
				"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
				<html xmlns="http://www.w3.org/1999/xhtml">
				<head></head>
				<body>
				<table>
					<tr>
						<td>' . nl2br($message) . '</td>
					</tr>
				</table>
				</br></br></br></br>
				<table>
					<tr><td>_______________________________</td></tr>
					<tr><td><strong>Lic. Francisco Almanza Esquivias</strong></td></tr>
					<tr><td>Asesor en Riesgos Patrimoniales</td></tr>
					<tr><td>Ruiz Quezada Administradores de Riesgos S.C.</td></tr>
					<tr><td>AXA Seguros</td></tr>
					<tr><td>Tel. (411)111-3574</td></tr>
				</table>
				<img src="http://www.cbiz.mx/almanza/images/firma.png">
				</body>
				</html>';
			
				//send the mail
				$result = sendmail($to, $subject, $message, $from);	
				
				if($result)
				{
					//MARCAMOS EL RECORADTORIO COMO YA ENVIADO
					liberar_bd();
					$updateRecordatorioEnviado = 'CALL sp_sistema_update_recordatorio_enviado('.$record["id"].');';
					$recordatorioEnviado = consulta($updateRecordatorioEnviado);
				}
			}
			
			$res= $msj.inicio_recordatorios();	
		}
		else
		{
			$error='No se hay correos por enviar.';
			$msj = sistema_mensaje("error",$error);
			$res= $msj.inicio_recordatorios();	
		}
		
		return $res;
	}
	
		
		

	//Simple mail function with HTML header
	function sendmail($from, $subject, $message, $to) 
	{
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
		$headers .= 'From: ' . $from . "\r\n";		
		$result = mail($to,$subject,$message,$headers);		
		if ($result) return 1;
		else return 0;
	}

	
?>