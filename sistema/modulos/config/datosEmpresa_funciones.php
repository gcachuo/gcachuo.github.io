<?php

	function configuracion_menuInicio()
	{
		liberar_bd();
		$selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa('.$_SESSION['idAgenteActual'].');';							  
		$datosEmpresa = consulta($selecDatosEmpresa);	
		$ctaDatosEmpresa = cuenta_registros($datosEmpresa);
		/*if($ctaDatosEmpresa == 0)
		{
			//GUARDAMOS LOS NUEVOS DATOS DE LA EMPRESA
			liberar_bd();
			$insertDatosEmpresa = 'CALL sp_sistema_insert_datos_empresa('.$_SESSION['idAgenteActual'].', '.$_SESSION[$varIdUser].');';
			$insertDatEmp = consulta($insertDatosEmpresa);
			liberar_bd();
			$selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa('.$_SESSION['idAgenteActual'].');';							  
			$datosEmpresa = consulta($selecDatosEmpresa);
			$empresa = siguiente_registro($datosEmpresa);
		}
		else*/
			$empresa = siguiente_registro($datosEmpresa);	
		
		//CARGAMOS CONFIGURACIONES INICALES
		liberar_bd();
		$selectConfigSistema2 = 'CALL sp_sistema_select_datos_configuracion('.$_SESSION['idAgenteActual'].');';
		$configSistema2 = consulta($selectConfigSistema2);
		$ctaconfigSistema2 = cuenta_registros($configSistema2);
		/*if($ctaconfigSistema2 = 0)
		{
			//GUARDAMOS LA CONFIGURACION DE LA EMPRESA
			liberar_bd();
			$insertConfigEmpresa = 'CALL sp_sistema_insert_config_empresa('.$_SESSION['idAgenteActual'].', '.$_SESSION[$varIdUser].')';
			$insertConfEmp = consulta($insertConfigEmpresa);
			
			liberar_bd();
			$selectConfigSistema2 = 'CALL sp_sistema_select_datos_configuracion('.$_SESSION['idAgenteActual'].');';
			$configSistema2 = consulta($selectConfigSistema2);
			$confSis2 = siguiente_registro($configSistema2);
		}
		else*/
			$confSis2 = siguiente_registro($configSistema2);
		
		
		if($empresa["logo"] != '')
		{
			$imagenActual = ' <div class="alert alert-dismissable alert-danger">
								  <strong>No seleccione archivo para no modificar</strong>
								  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
							  </div>
							  <div class="form-group">
								  <label for="codigoInt" class="col-sm-3 control-label">Im&aacute;gen actual:</label>
								  <div class="col-sm-6">
									  <img src="imagenes/empresa/'.$empresa["logo"].'" width="236px" class="imgPng"/>
								  </div>
							  </div>';
		}
		
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
										<div class="form-horizontal">
											<div class="form-group">
												<label for="razonConf" class="col-sm-3 control-label">Raz&oacute;n social:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="razonConf" name="razonConf" maxlength="100" value="'.utf8_convertir($empresa["razon"]).'"/>
												</div>
											</div>
											<div class="form-group">
												<label for="rfcConf" class="col-sm-3 control-label">RFC:</label>
												<div class="col-sm-6">
													<input type="text" class="form-control" id="rfcConf" name="rfcConf" maxlength="100" value="'.utf8_convertir($empresa["rfc"]).'"/>
												</div>
											</div>
											<div class="form-group">
												<label for="domicilioConf" class="col-sm-3 control-label">Domicilio:</label>
												<div class="col-md-6">	
													<textarea class="form-control autosize" name="domicilioConf" id="domicilioConf">'.utf8_convertir($empresa["domicilio"]).'</textarea>											
												</div>																														
											</div>	
											'.$imagenActual.'
											<div class="form-group">
												<label class="col-sm-3 control-label">Adjuntar im&aacute;gen</label>
												<div class="col-sm-9">
													<div class="fileinput fileinput-new" data-provides="fileinput">
														<div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"></div>
														<div>
														  <span class="btn btn-default btn-file"><span class="fileinput-new">Elija archivo</span><span class="fileinput-exists">Cambiar</span><input type="file" name="adjunto" id="adjunto"></span>
														  <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Eliminar</a>
														</div>
													</div>
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-3 control-label">Color barra superior</label>
												<div class="col-sm-6">
													<input readonly="readonly" type="text" class="form-control cpicker" data-color-format="hex" value="'.$confSis2["colorTop"].'" name="barraSup" id="barraSup">
												</div>
											</div>	
											<div class="form-group">
												<label class="col-sm-3 control-label">Color barra contenidos</label>
												<div class="col-sm-6">
													<input readonly="readonly" type="text" class="form-control cpicker" data-color-format="hex" value="'.$confSis2["colorCont"].'" name="barraCont" id="barraCont">
												</div>
											</div>																		
										</div>										
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-success btn" onclick="guardaDatosEmpresa(\'Guardar\');">Guardar</i>
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
	
	function configuracion_guardar()
	{
		liberar_bd();
		$selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa('.$_SESSION['idAgenteActual'].');';							  
		$datosEmpresa = consulta($selecDatosEmpresa);	
		$empresa = siguiente_registro($datosEmpresa);
		$rutaOld = 'imagenes/empresa/'.$empresa["logo"];
		
		$continue = 1;
		if($_FILES["adjunto"]["name"] != '')
		{
			if($_FILES["adjunto"]["error"] == 0)
			{
				$ext = end(explode(".", $_FILES["adjunto"]["name"]));	
				$src = date("YmdHis").".".$ext;					
				$ruta = "imagenes/empresa/".$src;
				$upload_filename = "imagenes/empresa/new_image.". $ext;	
									 
				$img = new image;
				$img->source($_FILES["adjunto"]);
			
				$erros = $img->error();
				if(count($erros) == 0) 
				{
					$img->resize(NULL,NULL);
					$img->create($ruta);
					//CHECAMOS TAMAÑO DE LA IMAGEN
					$size = getimagesize($ruta);
					if($size[0] < 236 || $size[1] < 236)
					{
						$continue = 0;
						unlink($ruta);
						$error='El tamaño de la imágen debe ser mínimo de 236px por 236px';
						$msj = sistema_mensaje("error",$error);
						$res= $msj.configuracion_menuInicio();	
					}
					else
					{
						if($size[0] > 500 || $size[1] > 500)
						{
							$continue = 0;
							unlink($ruta);
							$error='El tamaño de la imágen debe ser máximo de 500px por 500px';
							$msj = sistema_mensaje("error",$error);
							$res= $msj.configuracion_menuInicio();
						}
						else
						{
							$continue = 2;					
						}
					}
				}
				else
				{
					$continue = 0;
					unlink($ruta);
					$error='No se ha podido guardar la imágen.';
					$msj = sistema_mensaje("error",$error);
					$res= $msj.configuracion_menuInicio();	
				}
			}
			else
			{
				$continue = 0;
				unlink($ruta);
				$error='El archivo esta dañado.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.configuracion_menuInicio();	
			}
		}
		else
			$src = $empresa["logo"];
		
		if($continue != 0)
		{
			liberar_bd();
			$insertDatosEmpresa = 'CALL sp_sistema_insert_datosEmpresa( '.$_SESSION['idAgenteActual'].',
																		"'.(strtoupper($_POST["razonConf"])).'", 
																		"'.(strtoupper($_POST["rfcConf"])).'", 
																		"'.(strtoupper($_POST["domicilioConf"])).'",
																		"'.$src.'");';									  
			$insert = consulta($insertDatosEmpresa);			
			if($insert)
			{
				//GUARDAMOS CONFIGURACIONES 
				liberar_bd();
				$updateConfigSistema = 'CALL sp_sistema_update_config_sistema(	'.$_SESSION['idAgenteActual'].',
																				"'.$_POST["barraSup"].'",
																			  	"'.$_POST["barraCont"].'");';
				
				$update = consulta($updateConfigSistema);															  
			
				if($continue == 2)
				{
					unlink($rutaOld);
					$res= $msj.configuracion_guardarImagen();	
				}
				else
					$res= $msj.configuracion_menuInicio();									
			}
			else
			{
				unlink($ruta);
				$error='No se ha podido guardar la configuración de la empresa.';
				$msj = sistema_mensaje("error",$error);
				$res= $msj.configuracion_menuInicio();
			}
		}	
		
		return $res;	
	}
	
	function configuracion_guardarImagen()
	{
		liberar_bd();
		$selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa('.$_SESSION['idAgenteActual'].');';							  
		$datosEmpresa = consulta($selecDatosEmpresa);	
		$empresa = siguiente_registro($datosEmpresa);
		
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
							</div>
						</div>										
					</div>
					<div class="container">							
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger">
									<div class="panel-heading">
										<h4>Editar im&aacute;gen</h4>
									</div>
									<div class="panel-body"> 
										<div class="tab-pane" id="crop-handler-tab">
											<img src="imagenes/empresa/'.$empresa["logo"].'" class="img-responsive imgPng" id="crop-handler3">
											<input type="hidden" class="form-control" name="X1" id="x1">
											<input type="hidden" class="form-control" name="Y1" id="y1">
											<input type="hidden" class="form-control" name="X2" id="x2">
											<input type="hidden" class="form-control" name="Y2" id="y2">
											<input type="hidden" class="form-control" name="W" id="w">
											<input type="hidden" class="form-control" name="H" id="h">									
										</div>				
									</div>
									<div class="panel-footer">
										<div class="row">
											<div class="col-sm-12">
												<div class="btn-toolbar btnsGuarCan">
													<i class="btn-success btn" onclick="navegar(\'GuardarEditar\');">Guardar</i>
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
	
	function configuracion_editar()
	{
		liberar_bd();
		$selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa('.$_SESSION["idAgenteActual"].');';							  
		$datosEmpresa = consulta($selecDatosEmpresa);	
		$empresa = siguiente_registro($datosEmpresa);
		
		$ruta = "imagenes/empresa/".$empresa["logo"];						 
		// crop
		$img = new image();
		$img->source($ruta);
		$img->crop($_POST["X1"],$_POST["Y1"],$_POST["W"],$_POST["H"]);
		$img->create($ruta);
		// resize
		$img->source($ruta);
		$img->resize(236,236);
		$img->create($ruta);
		  
		return configuracion_menuInicio();
	}
	
?>