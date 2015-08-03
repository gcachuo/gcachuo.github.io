<?php

	function calendario_menuInicio()
	{
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
								<i title="Nuevo evento" class="btn btn-warning" href="#myModal1" id="bootbox-demo-5" data-toggle="modal">
									Nuevo evento
								</i>
							</div>
						</div>										
				  	</div>
					<div class="container">						
						<div class="row">
							<div class="col-sm-12">
								<div class="panel panel-danger calendar">
									<div class="panel-heading">				
										<h4></h4>
										<div class="options">											
										</div>
									</div>
									<div class="panel-body">
										<div class="legend">
											<table>
												<tbody>
													<tr>
														<td class="legendColorBox">
															<div style="border:1px solid transparent;padding:1px">
															<div style="width:4px;height:0;border:5px solid rgb(133,199,68);overflow:hidden"></div>
															</div>
														</td>
														<td class="legendLabel">Evento</td>
													</tr>
												</tbody>
											</table>
										</div>
										<br>										
										<div id="calendar-drag"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-dialog">
							<div class="modal-content">
								<div id="divFormPago">
									<div class="modal-header">
										<h4 class="modal-title">Nuevo evento</h4>
									</div>
									<div class="modal-body">
										<div class="row">
											<div class="col-sm-12 well">
												<h3>Actividad actual</h3>
												<div class="form-horizontal">
													<div class="form-group">
														<label for="obs" class="col-sm-3 control-label">T&iacute;tulo:</label>
														<div class="col-md-6">	
															<textarea class="form-control autosize" name="tituloEvento" id="tituloEvento" onkeyup="revisarEvento()"></textarea>											
														</div>													
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Fecha de inicio:</label>
														<div class="col-sm-6">
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input data-date-format="dd-mm-yyyy hh:ii" type="text" class="form-control fechaIniEvento" id="fechaIniEvento" name="datepicker" onchange="revisarEvento()"/>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="col-sm-3 control-label">Fecha de t&eacute;rmino:</label>
														<div class="col-sm-6">
															<div class="input-group">
																<span class="input-group-addon"><i class="fa fa-calendar"></i></span>
																<input data-date-format="dd-mm-yyyy hh:ii" type="text" class="form-control fechaFinEvento" id="fechaFinEvento" name="datepicker3" onchange="revisarEvento()"/>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label for="nombreLinea" class="col-sm-3 control-label">Url:</label>
														<div class="col-sm-6">
															<input type="text" class="form-control" id="urlEvento" name="urlEvento" maxlength="100"/>
														</div>
													</div>
												</div>
											</div>											
										</div>									
									</div>
									<div class="modal-footer">									
										<i class="btn-danger btn" onclick="" data-dismiss="modal">Cancelar</i>
										<i class="btn-success btn" data-dismiss="modal" data-dismiss="modal" onclick="guardarEvento()" style="display:none;" id="btnGuardarEvento">Guardar</i>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div id="div_articulos"></div>';
							
		return $pagina;
	}
?>