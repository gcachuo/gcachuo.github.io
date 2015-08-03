<?php

	function inicioCaja_menuInicio()
	{
		//LISTA DE TIPOS DE METAS
		liberar_bd();
		$selectListaTiposMetas = 'CALL sp_sistema_lista_tiposMeta();';
		$listaTipoMetas = consulta($selectListaTiposMetas);
		while($met = siguiente_registro($listaTipoMetas))
		{
			$optMetas .=' <div class="form-group">
							  <label for="valorTipoMeta" class="col-sm-3 control-label">'.utf8_convertir($met["nombre"]).':</label>
							  <div class="col-sm-6">
							  	  <input type="text" class="form-control" id="valorTipoMeta" name="valorTipoMeta['.$met["id"].']" maxlength="100" placeholder="0"/>
							  </div>
						  </div>';
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
											<h4>Mis metas</h4>
										</div>
										<div class="panel-body" style="border-radius: 0px;">
											<div class="form-horizontal">
												'.$optMetas.'
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<div class="btn-toolbar">
														<i class="btn-primary btn" onclick="navegar(\'Guardar\');">Guardar</i>
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
	
	function inicioCaja_guardar()
	{
		$valorTipoMeta = $_POST['valorTipoMeta'];		
		foreach ($valorTipoMeta as $idValor => $valor) 
		{
			if($valor == '')
				$valor = 0;
			//GUARDAMOS LAS METAS DEL ROL
			liberar_bd();
			$insertMetaRol = 'CALL sp_sistema_insert_metaRol('.$_SESSION["idRolUser"].',
															 '.$idValor.',
															 "'.$valor.'",
															 '.$_SESSION[$varIdUser].');';
															 
			$insert = consulta($insertMetaRol);
  		}
		//ACTUALIZAMOS PRIMER ACCESO AL ROL
		liberar_bd();
		$updateAcceso = 'CALL sp_sistema_update_accesoRol('.$_SESSION["idRolUser"].');';
		$update = consulta($updateAcceso);
		
		$_SESSION['primerAcceso'] = 1;		
		$pagina = '<meta http-equiv="refresh" content="0">'	;	
		return $pagina;
	}

?>