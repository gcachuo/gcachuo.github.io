<?php

	function inicioCaja_menuInicio()
	{
		//LISTA DE CAJAS REGISTRADORAS
		liberar_bd();
		$selectListaCajas = 'CALL sp_sistema_lista_cajas_registradoras();';
		$listaCajas = consulta($selectListaCajas);
		while($caja = siguiente_registro($listaCajas))
		{
			$optCajas .= '<option value="'.$caja["id"].'">'.utf8_convertir($caja["banco"]).'</option>';
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
								<div class="col-sm-8">
									<div class="panel panel-danger">
										<div class="panel-heading">
											<h4>Configuraci&oacute;n inicial</h4>
										</div>
										<div class="panel-body" style="border-radius: 0px;">
											<div class="form-horizontal">
												<div class="form-group">
													<label for="id_caja" class="col-sm-3 control-label">Caja actual:</label>
													<div class="col-sm-6">
														<select id="id_caja" name="id_caja" style="width:100% !important" class="selectSerch">
															<option value="">Seleccione una caja</option>
															'.$optCajas.'
														</select>
													</div>
												</div>
												<div class="form-group">
													<label for="saldoCaja" class="col-sm-3 control-label">Total actual en caja:</label>
													<div class="col-sm-6" id="carga_total">
														<input type="text" class="form-control" id="saldoCaja" name="saldoCaja" maxlength="100" readonly="readonly"/>
													</div>
												</div>	
											</div>
										</div>
										<div class="panel-footer">
											<div class="row">
												<div class="col-sm-6 col-sm-offset-3">
													<div class="btn-toolbar">
														<i id="btnComenzar" disabled="disabled" class="btn-primary btn" onclick="navegarPunto(\'Guardar\');">Comenzar</i>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>						
						<script>
							$("#id_caja").change(function(event){ 
								$("#id_caja option:selected").each(function () 
								{
									id_caja=$(this).val();
									var request = $("#carga_total").load("../seccion/ajax/revisar_total_caja.php",{id_caja:id_caja},
									function(){	
										var txtComenzar = $("#txtComenzar").val();	
										if(txtComenzar == 1)
											$("#btnComenzar").removeAttr("disabled");
										else
										{
											if(txtComenzar == 0)
												$("#btnComenzar").attr("disabled", true);
										}
									});										
								});
							});	
						</script>';
		
		return $pagina;
	}
	
	function inicioCaja_guardar()
	{
		$hora = date("H:i");		
		//CHECAMOS HORARIO DE CAJA
		liberar_bd();
		$selectHorariosCaja = 'CALL sp_sistema_select_horarios_caja('.$_POST["id_caja"].');';
		$horariosCaja = consulta($selectHorariosCaja);
		$ctaHorariosCaja = cuenta_registros($horariosCaja);
		if($ctaHorariosCaja != 0)
		{
			$horarios = siguiente_registro($horariosCaja);	
			if($horarios["inicio"] != '')
			{	
				$horaInicial = normalize_time($horarios["inicio"]);
				$horaFinal = normalize_time($horarios["fin"]);	
				
				//CHECAMOS SI YA PUEDE INICIAL CAJA
				if($horaInicial <= $hora)
				{	
					if($horaFinal >= $hora)
					{
						liberar_bd();	
						$selectDatosCaja = 'CALL sp_sistema_select_datos_cuentas('.$_POST["id_caja"].');';
						$datosCaja = consulta($selectDatosCaja);
						$caja = siguiente_registro($datosCaja);	
						
						//GUARDAMOS DATOS INICIALES DE HISTORILA DE CAJA
						liberar_bd();
						$insertApertura = 'CALL sp_sistema_insert_apertura_caja('.$_POST["id_caja"].', '.$_SESSION[$varIdUser].', "'.$caja["monto"].'");';
						$insert = consulta($insertApertura);
						
						$_SESSION['primerAcceso'] = 0;
						$_SESSION['idCajaActual'] = $_POST["id_caja"];		
						$res = '<meta http-equiv="refresh" content="0">';
					}
					else
					{
						$error='No puede iniciar operaciones en este horario.';
						$msj = sistema_mensaje("error",$error);
						$res = $msj.inicioCaja_menuInicio();
					}
				}
				else
				{
					$error='No puede iniciar operaciones en este horario.';
					$msj = sistema_mensaje("error",$error);
					$res = $msj.inicioCaja_menuInicio();
				}
			}
			else
			{
				$error='Esta caja no tiene horario de inicio.';
				$msj = sistema_mensaje("error",$error);
				$res = $msj.inicioCaja_menuInicio();
			}
		}
		else
		{
			$error='Esta caja no tiene horario de inicio.';
		  	$msj = sistema_mensaje("error",$error);
			$res = $msj.inicioCaja_menuInicio();
		}
			
		return $res;
	}

?>