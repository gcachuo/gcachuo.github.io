<?php

function muestra_login($msj='Bienvenido', $tipo ='info')
{
	liberar_bd();
	$selecDatosEmpresa = "CALL sp_sistema_select_datos_empresa();";							  
	$datosEmpresa = consulta($selecDatosEmpresa);	
	$empresa = siguiente_registro($datosEmpresa);
	
	//CARGAMOS CONFIGURACIONES INICALES
	liberar_bd();
	$selectConfigSistema = 'CALL sp_sistema_select_datos_configuracion();';
	$configSistema = consulta($selectConfigSistema);	
	$confSis = siguiente_registro($configSistema);
	
	$formulario = '	<!DOCTYPE html>
						<html lang="en">
						<head>
							<meta charset="utf-8" />
							<title>.::CBIZ - Punto de venta::.</title>
							<meta http-equiv="X-UA-Compatible" content="IE=edge" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0" />
							<meta name="description" content="" />
							<meta name="author" content="" />
						
							<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
							<link href="../sistema/assets/css/styles.min.css" rel="stylesheet" type="text/css" />
							<script src="../sistema/script/sistema/globales.js"></script>
							<script src="../sistema/script/sistema/md5.js"></script>
							<script src="../sistema/script/sistema/sistema.js"></script>
							<!-- Scripts -->
							'.scripttag('../sistema/assets/js/jquery-1.10.2.min.js')
							 .scripttag('../sistema/assets/js/jqueryui-1.10.3.min.js').'
						
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
						<body class="focusedform">
						
						<div class="verticalcenter">
							<img src="../sistema/imagenes/empresa/'.$empresa["logo"].'" width="236px" alt="Logo" class="brand imgPng" />
							<div class="panel panel-primary">
								<div class="panel-body">
									<style>
										.btn-block {margin-bottom: 10px;}
									</style>
									<h4 class="text-center" style="margin-bottom: 25px;">Iniciar sesi&oacute;n para comenzar</h4>				
									<form id="frmAcceso" name="frmAcceso" method="post" action="./">
										<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-user"></i></span>
													<input type="text" class="form-control" name="txtUsuario" placeholder="Usuario" id="txtUsuario" />
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="col-sm-12">
												<div class="input-group">
													<span class="input-group-addon"><i class="fa fa-lock"></i></span>
													<input type="password" class="form-control" id="txtPassword" name="txtPassword" placeholder="Contrase&ntilde;a" />
												</div>
											</div>
										</div>						
									</form>
								</div>
								<div class="panel-footer">
									<div class="pull-right">
										<a id="btnEnvio" class="btn btn-primary">Iniciar sesi&oacute;n</a>
									</div>
								</div>
							</div>
						 </div>
						  <script>
						  	window.onload = function () 
							{
								$(".panel-body").css("border-top","'.$confSis["colorCont"].' !important");
							}
													 
							$(document).ready(function()
							{
								$("#txtPassword").keypress(function(event){
 
									var keycode = (event.keyCode ? event.keyCode : event.which);
									if(keycode == "13")
									{
										// Values
										var login = $.trim($("#txtUsuario").val()),
											pass = $.trim($("#txtPassword").val());
								
										// Check inputs
										if (login.length === 0)
											alert("Capture usuario del sistema");
										else if (pass.length === 0)
											alert("Capture su contraseña del sistema");
										else
											validarLogIn();
									}
								 
								});

								$("#btnEnvio").click(function()
								{
									// Values
										var login = $.trim($("#txtUsuario").val()),
											pass = $.trim($("#txtPassword").val());
								
										// Check inputs
										if (login.length === 0)
											alert("Capture usuario del sistema");
										else if (pass.length === 0)
											alert("Capture su contraseña del sistema");
										else
											validarLogIn();
								});
							}); 
						</script>
						</body>
						</html>
					';
	
	return $formulario;		
}

function muestra_punto()
{	
	//CARGAMOS CONFIGURACIONES INICALES
	liberar_bd();
	$selectConfigSistema = 'CALL sp_sistema_select_datos_configuracion();';
	$configSistema = consulta($selectConfigSistema);	
	$confSis = siguiente_registro($configSistema);
	
	$msj = '';	
	if(trim($_POST['moduloPunto'])=="-1")
	{
		cerrarSesion();
		$sistema = '<meta http-equiv="refresh" content="0;url=http://www.cbiz.mx/comercios/punto">';
		
	}
	else
	{
		if($_POST['moduloPunto'] == "")
			$_POST['moduloPunto'] = 4;
			
		if(isset($_POST['moduloPunto']) && trim($_POST['moduloPunto'])!="")
		{
			if($_POST['moduloPunto'] != "")
			{
				$_SESSION['moduloPunto'] = $_POST['moduloPunto'];	
			}			
		}
		if($_POST['accionPunto'] == "")
		{
			$_POST['accionPunto'] = "Inicio";
		}	
		if (isset($_COOKIE["admin_leftbar_collapse"])) 
			$classBody.= $_COOKIE['admin_leftbar_collapse'] . " "; 
        if (isset($_COOKIE["admin_rightbar_show"])) 
			$classBody.= $_COOKIE['admin_rightbar_show'];
        if (isset($_COOKIE["fixed-header"])) 
			$classBody.= ' static-header';
		if (isset($_COOKIE["fixed-header"])) 
			$classHeader =' navbar-static-top';
		else 
			$classHeader =' navbar-fixed-top';
		
		if(isset($_POST['menuSelectPunto']) && trim($_POST['menuSelectPunto'])!="")
		{
			if($_POST['menuSelectPunto'] != "")
			{
				$_SESSION['menuSelectPunto'] = $_POST['menuSelectPunto'];	
			}			
		}
		
		$sistema = '	<!DOCTYPE html>
							<html lang="en">
							<head>
								<meta charset="utf-8" />
								<title>.::CBIZ - Punto de venta::.</title>
								<meta name="viewport" content="width=device-width, initial-scale=1.0" />
								<meta name="description" content="" />
								<meta name="author" content="" />							
								<!-- <link href="../sistema/assets/less/styles.less" rel="stylesheet/less" media="all">  -->
   								<link rel="stylesheet" href="../sistema/assets/css/styles.css?=121">
								<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css">
								';
								if (isset($_COOKIE["theme"])) 
								{
									$sistema .= "<link href='../sistema/assets/demo/variations/". $_COOKIE["theme"] ."' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
								} 
								else 
								{  
									$sistema .=" <link href='../sistema/assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
								}
								if (isset($_COOKIE["headerstyle"])) 
								{
									$sistema .="<link href='../sistema/assets/demo/variations/". $_COOKIE["headerstyle"] ."' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
								} 
								else 
								{ 								
									$sistema .="<link href='../sistema/assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
								} 								
					
					$sistema .='
								<!--[if lt IE 9]>
									<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
									<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
									<script type="text/javascript" src="../sistema/assets/plugins/charts-flot/excanvas.min.js"></script>
									<link rel="stylesheet" href="../sistema/assets/css/ie8.css">
								<![endif]-->
								'.heade($_SESSION['moduloPunto'], $_POST['accionPunto']).'								
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								<script type="text/javascript" src="../sistema/assets/js/less.js"></script>
								</head>
								<!--
								<script src="//ajax.googleapi.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
								<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
								<script>!window.jQuery && document.write(unescape(\'%3Cscript src="../sistema/assets/js/jquery-1.10.2.min.js"%3E%3C/script%3E\'))</script>
								<script type="text/javascript">!window.jQuery.ui && document.write(unescape(\'%3Cscript src="../sistema/assets/js/jqueryui-1.10.3.min.js\'))</script>
								-->
								'.	scripttag('../sistema/assets/js/jquery-1.10.2.min.js').
									scripttag('../sistema/assets/js/jqueryui-1.10.3.min.js').
									scripttag('../sistema/assets/js/bootstrap.min.js').
									scripttag('../sistema/assets/js/enquire.js').
									scripttag('../sistema/assets/js/jquery.cookie.js').
									scripttag('../sistema/assets/js/jquery.nicescroll.min.js').
									scripttag('../sistema/assets/plugins/codeprettifier/prettify.js').
									scripttag('../sistema/assets/plugins/easypiechart/jquery.easypiechart.min.js').
									scripttag("../sistema/assets/plugins/sparklines/jquery.sparklines.min.js").
									scripttag("../sistema/assets/plugins/form-toggle/toggle.min.js")
								.'
								<body class="horizontal-nav '.$classBody.'">
									<form name="frmSistemaPunto" id="frmSistemaPunto" method="post" action="./" enctype="multipart/form-data">
										<span name="campos" id="campos"></span>
										<input type="hidden" id="menuSelectPunto" name="menuSelectPunto" value="'.$_POST['menuSelectPunto'].'" />	
										<input type="hidden" id="moduloPunto" name="moduloPunto" value="'.$_POST['moduloPunto'].'" />	
										<div id="headerbar">
											<div class="container">
											</div>
										</div>
										<header class="navbar navbar-inverse '.$classHeader.'" role="banner">
											<div class="navbar-header pull-left">
												<a class="navbar-brand" href="javascript:;">CBIZ</a>
											</div>								
											<ul class="nav navbar-nav pull-right toolbar">
												<li class="dropdown">
													<a href="#" class="dropdown-toggle username" data-toggle="dropdown">
														<span class="hidden-xs">
															'.$_SESSION["usuario"].'
															<i class="fa fa-angle-down icon-scale"></i>
														</span>
														<img src="" alt="" />
													</a>
													<ul class="dropdown-menu userinfo arrow">
														<li class="username">
															<a href="#">
																<div class="pull-left"><img class="userimg" src="" alt="" /></div>
																<div class="pull-right"><h5>'.recortar_texto($_SESSION["usuario"], 20).'</h5><small>Conectado como <span>'.$_SESSION["login"].'</span></small></div>
															</a>
														</li>
														<li class="userlinks">
															<ul class="dropdown-menu">
																<li><a href="#">Editar perfil<i class="pull-right icon-pencil"></i></a></li>
																<li class="divider"></li>
																<li><a href="javascript:;" onclick="frmSistemaPunto.moduloPunto.value =\'-1\'; frmSistemaPunto.submit();" class="text-right">Cerrar sesi&oacute;n</a></li>
															</ul>
														</li>
													</ul>
												</li>
												';
												if($_SESSION["primerAcceso"] != 1)
												{
													$sistema .='	<li>
																		<a class="hasnotifications" title="Agregar producto miscelaneo" href="#myModalMisc" data-toggle="modal">
																			<i class="fa fa-puzzle-piece"></i>
																		</a>
																	</li>
																	<li>
																		<a class="hasnotifications" title="Pagos a cliente" href="#myModalMisc2" data-toggle="modal">
																			<i class="fa fa-usd"></i>
																		</a>
																	</li>
																	<li>
																		<a class="hasnotifications" title="Cotizaciones" href="#myModalMisc3" data-toggle="modal">
																			<i class="fa fa-list-alt"></i>
																		</a>
																	</li>';
												}
						$sistema .='		</ul>
										</header>									
										<div id="page-container">
											<div id="page-content">
												<div id="wrap">	
													';
													if($_SESSION["primerAcceso"] != 1)													
														$sistema .= muestra_moduloPunto($_SESSION['moduloPunto']);
													else
														$sistema .= muestra_formulario_metas();	
								$sistema .='	</div>
											</div>
										</div>									
										'.footer($_SESSION['moduloPunto'], $_POST['accionPunto']).'
										<script>	
											window.onload = function () 
											{
												$(".panel-danger .panel-heading").css("background-color","'.$confSis["colorCont"].' !important");
												$(".panel-body").css("border-top","'.$confSis["colorCont"].' !important");
												$(".navbar").css("background-color","'.$confSis["colorTop"].' !important");
												if (typeof history.pushState === "function") {
													history.pushState("jibberish", null, null);
													window.onpopstate = function () {
														history.pushState("newjibberish", null, null);
														// Handle the back (or forward) buttons here
														// Will NOT handle refresh, use onbeforeunload for this.
													};
												}
												else {
													var ignoreHashChange = true;
													window.onhashchange = function () {
														if (!ignoreHashChange) {
															ignoreHashChange = true;
															window.location.hash = Math.random();
															// Detect and redirect change here
															// Works in older FF and IE9
															// * it does mess with your hash symbol (anchor?) pound sign
															// delimiter on the end of the URL
														}
														else {
															ignoreHashChange = false;   
														}
													};
												}
											}
										</script>
										'.scripSpecial($_SESSION['moduloPunto'], $_POST['accionPunto']).'								
										<!-- Scripts Sistema-->						
										<script src="../sistema/script/sistema/globales.js"></script>
										<script src="../sistema/script/sistema/md5.js"></script>
										<script src="../sistema/script/sistema/sistema.js"></script>		
									</form>
								</body>
							</html>';
					
								
	}
	
	return $sistema;		
}

function cerrarSesion()
{
	session_unset();
	session_destroy();
}
	
function muestra_moduloPunto($id)
{
	liberar_bd();
	$sqlPunto="SELECT archivo_moduloPunto as archivo FROM moduloPunto WHERE id_moduloPunto = " . $id;
	$resPunto = consulta($sqlPunto);
	$ctaResPunto = cuenta_registros($resPunto);
	$modulo='<input type="hidden" name="accionPunto" />';
	if($ctaResPunto != 0)
	{
		$filaPunto = siguiente_registro($resPunto);		
		include_once($filaPunto['archivo']);
	}
	else
	{
		include_once('./modulos/punto/punto.php');
	}
	
	return $modulo;
}

function muestra_formulario_metas()
{
	$modulo='<input type="hidden" name="accion" />';
	include_once("./modulos/formulario/inicioCaja.php");	
	return $modulo;
}

function scripttag($address) 
{
	$scriptReturn = "<script type='text/javascript' src='$address'></script> \n";
	return $scriptReturn;
}

function linktag($address) 
{ 
	$cssReturn = "<link rel='stylesheet' type='text/css' href='$address' /> \n";
	return $cssReturn;
}

function footer($pageModu, $pageMode)
{
	if($pageModu == 4)
	{
		$scriptPagina .=scripttag("../sistema/assets/plugins/bootbox/bootbox.min.js").
    					scripttag("../sistema/assets/demo/demo-modals.js").
						scripttag("../sistema/assets/plugins/form-multiselect/js/jquery.multi-select.min.js").
						scripttag("../sistema/assets/plugins/quicksearch/jquery.quicksearch.min.js").
						scripttag("../sistema/assets/plugins/form-typeahead/typeahead.min.js").
						scripttag("../sistema/assets/plugins/form-select2/select2.min.js").
						scripttag("../sistema/assets/plugins/form-autosize/jquery.autosize-min.js").
						scripttag("../sistema/assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js").
						scripttag("../sistema/assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.min.js").
						scripttag("../sistema/assets/plugins/form-daterangepicker/daterangepicker.min.js").
						scripttag("../sistema/assets/plugins/form-daterangepicker/moment.min.js").
						scripttag("../sistema/assets/plugins/form-fseditor/jquery.fseditor-min.js").
						scripttag("../sistema/assets/demo/demo-formcomponents.js").
						scripttag("../sistema/assets/plugins/form-daterangepicker/moment.min.js").
						scripttag("../sistema/assets/plugins/form-xeditable/bootstrap3-editable/js/bootstrap-editable.min.js");
	}
	
		$scriptPagina .=scripttag("../sistema/assets/plugins/datatables/jquery.dataTables.min.js").
						scripttag("../sistema/assets/plugins/datatables/dataTables.bootstrap.js").
						scripttag("../sistema/assets/demo/demo-datatables.js").
						scripttag('../sistema/assets/js/placeholdr.js').
						scripttag('../sistema/assets/js/application.js').
						scripttag('../sistema/assets/demo/demo.js');
						
	return $scriptPagina;
}

function heade($pageModu, $pageMode)
{
	$cssPagina .= 	linktag('../sistema/assets/plugins/datatables/dataTables.css').
					linktag('../sistema/assets/plugins/codeprettifier/prettify.css').
					linktag('../sistema/assets/plugins/form-toggle/toggles.css').
					linktag('../sistema/assets/plugins/form-select2/select2.css').
					linktag('../sistema/assets/plugins/form-multiselect/css/multi-select.css').
					linktag('../sistema/assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css').
					linktag('../sistema/assets/plugins/form-daterangepicker/daterangepicker-bs3.css').
					linktag('../sistema/assets/plugins/form-fseditor/fseditor.css').
					linktag('../sistema/assets/js/jqueryui.css').
					linktag('../sistema/assets/plugins/form-xeditable/bootstrap3-editable/css/bootstrap-editable.css'); //<!-- Toggles -->
	
	return $cssPagina;
}

function scripSpecial($pageModu, $pageMode)
{
	
	if($pageModu == 4)
	{
		$scriptSpecial.= '	<script>
								$(document).ready(function()
								{	
									cargar_categorias();
									cargar_servicios();
									cargar_clientes();	
									cargar_kits();
									/*cargar_promos();*/	
									$("#codigoProd").focus();
									$("#codigoProd").keypress(function(event){ 
										var keycode = (event.keyCode ? event.keyCode : event.which);
										if(keycode == "13")
										{
											carga_producto();
										}	
									});
									
									$("#btnPorcentaje").focus();	
									
									$("#btnEfectivoFin").tooltip({
										"show": true,
										"placement": "bottom",
										"title": "Efectivo"
									});									
									$("#btnCreDebFin").tooltip({
										"show": true,
										"placement": "bottom",
										"title": "Crédito / débito"
									});
									$("#btnCancelarFin").tooltip({
										"show": true,
										"placement": "bottom",
										"title": "Cancelar"
									});											
								});
								
								$("#btnPorcentaje").click(function () 
							  	{
									if ($("#divPorcentaje").is(":visible")) 
									{
									} 
									else
									{
										$("#divMonto").toggle();
										$("#divPorcentaje").toggle();
										$("#btnMonto").removeClass("btnDefaultActivo");	
										$("#chkTipo").prop("checked", true);
										$("#txtDescMonto").val("");
										$("#txtDescNuevo").val("");
										$("#spnPorcentaje").html("");	
										$("#txtNuevoSubtotal").val("");
										$("#divValidaMonto").css("display","none");	
										$("#btnAgregaDesc").css("display","");	
										$("#txtDescAplica").val("");	
									}
							  	});
								
								$("#btnMonto").click(function () 
							  	{
									
									if ($("#divMonto").is(":visible")) 
									{
									} 
									else
									{
										$("#divMonto").toggle();
										$("#divPorcentaje").toggle();
										$("#btnMonto").addClass("btnDefaultActivo");
										$("#chkTipo").prop("checked", false);
										$("#txtDescPorc").val("");
										$("#txtDescNuevo").val("");
										$("#spnPorcentaje").html("");	
										$("#txtNuevoSubtotal").val("");
										$("#divValidaPor").css("display","none");
										$("#btnAgregaDesc").css("display","");
										$("#txtDescAplica").val("");
									}																																					
							  	});
								
								$("#idCliPago").change(function()
								{
									var id=$(this).val();
									var dataString = \'id=\'+ id; 
									$("#idClientePago").val(id);									
									if(id != 0)
									{									
										$.ajax
										({
											type: "POST",
											url: "../seccion/ajax/saldoCliente.php",
											data: dataString,
											cache: false,
											dataType: "json",
											success: function(data)
											{	
												var txtSaldoActualCli = addCommas(data.saldo);
												$("#txtSaldoActualCli").val(txtSaldoActualCli);
												$("#saldoActualCli").val(data.saldo);
												
												var txtLimiteActualCli = addCommas(data.limite);
												$("#txtLimiteActualCli").val(txtLimiteActualCli);
												$("#limiteActualCli").val(data.limite);																														
											}			
										});	
									}
									else
									{
										reset_pago_cliente();
									}
								});
								
								function cargar_new_saldo_cliente()
								{
									var saldoActualCli = $("#saldoActualCli").val();
									var txtAbonoCli = $("#txtAbonoCli").val();
									
									if(txtAbonoCli != 0 && !isNaN(txtAbonoCli))
									{
										var newSaldoCli = saldoActualCli - txtAbonoCli;
										if(newSaldoCli == 0)
										{
											$("#newSaldoCli").val("0.00");
											$("#txtNewSaldoCli").val("0.00");	
											$("#cambioCliente").val("0.00");
											$("#txtCambioCliente").val("0.00");
										}
										else
										{
											if(newSaldoCli < 0)
											{
												$("#newSaldoCli").val("0.00");
												$("#txtNewSaldoCli").val("0.00");
												newSaldoCli = -newSaldoCli;	
												newSaldoCli = parseFloat(newSaldoCli).toFixed(2);
												txtCambioCliente = addCommas(newSaldoCli);
												$("#cambioCliente").val(newSaldoCli);
												$("#txtCambioCliente").val(txtCambioCliente);
											}
											else
											{
												if(newSaldoCli > 0)
												{
													newSaldoCli = parseFloat(newSaldoCli).toFixed(2);
													var txtNewSaldoCli = addCommas(newSaldoCli);
													$("#txtNewSaldoCli").val(txtNewSaldoCli);	
													$("#newSaldoCli").val(newSaldoCli);	
													$("#cambioCliente").val("0.00");
													$("#txtCambioCliente").val("0.00");
												}
											}
										}	
										$("#btnAgregarPago").removeAttr("disabled");					
									}
									else
									{
										alert("Capture un valor correcto para el abono.");
										$("#btnAgregarPago").attr("disabled", true);
										$("#newSaldoCli").val("0.00");
										$("#txtNewSaldoCli").val("0.00");
										$("#cambioCliente").val("0.00");
										$("#txtCambioCliente").val("0.00");
									}
								}
								
								$("#idCliPagoCta").change(function () 
								{
									$("#idCliPagoCta option:selected").each(function () 
									{
										idCliente=$(this).val();
										var dataString = \'idCliente=\'+ idCliente; 
										$("#idClienteCtaPago").val(idCliente);
										if(idCliente != 0)
										{
											$.post("../seccion/ajax/ctasCliente.php", { idCliente: idCliente }, 
											function(data)
											{
												$("#idCtaCliente").html(data);
												$("#s2id_idCtaCliente .select2-chosen").html("Seleccione cuenta");
												$("#divCtaCliente").css("display","block");	
												
												$.ajax
												({
													type: "POST",
													url: "../seccion/ajax/revisar_cliente.php",
													data: dataString,
													cache: false,
													dataType: "json",
													success: function(data)
													{	
														var txtLimiteActualCtaCli = addCommas(data.monto);
														$("#txtLimiteActualCtaCli").val(txtLimiteActualCtaCli);
														$("#limiteActualCtaCli").val(data.monto);																														
													}			
												});							  
											});	
										}
										else
										{
											reset_pago_cuenta_cliente();
										}
									});
								});
								
								$("#idCtaCliente").change(function () 
								{
									$("#idCtaCliente option:selected").each(function () 
									{
										idCta=$(this).val();
										var dataString = \'id=\'+ idCta; 
										if(idCta != 0)
										{
											$.ajax
											({
												type: "POST",
												url: "../seccion/ajax/datosCtaCliente.php",
												data: dataString,
												cache: false,
												dataType: "json",
												success: function(data)
												{	
													var txtSaldoActualCtaCli = addCommas(data.saldo);
													$("#txtSaldoActualCtaCli").val(txtSaldoActualCtaCli);
													$("#saldoActualCtaCli").val(data.saldo);																													
												}			
											});
										}
										else
										{	
											$("#saldoActualCtaCli").val("");										
										}
									});
								});
								
								$("#idCliCotiza").change(function () 
								{
									$("#idCliCotiza option:selected").each(function () 
									{
										$("#tablaCotizaciones").html(\'<div style="height:100px !important; width:100px !important;" id="cargando"></div>\');
										idCliente=$(this).val();
										var dataString = \'idCliente=\'+ idCliente; 
										$("#idClienteCtaPago").val(idCliente);
										if(idCliente != 0)
										{
											$.post("../seccion/ajax/cotizacionesCliente.php", { idCliente: idCliente }, 
											function(data)
											{
												$("#tablaCotizaciones").html(data);
												$("#divDatosCotizacion").css("display","block");
												$("#btnGuardarCotizacion").removeAttr("disabled");												
											});	
										}
										else
										{
											reset_venta_cotizacion();
										}
									});
								});
								
								function cargar_new_saldo_cta_cliente()
								{
									var saldoActualCtaCli = $("#saldoActualCtaCli").val();
									var txtAbonoCtaCli = $("#txtAbonoCtaCli").val();
									
									if(txtAbonoCtaCli != 0 && !isNaN(txtAbonoCtaCli))
									{
										var newSaldoCtaCli = saldoActualCtaCli - txtAbonoCtaCli;
										if(newSaldoCtaCli == 0)
										{
											$("#newSaldoCtaCli").val("0.00");
											$("#txtNewSaldoCtaCli").val("0.00");	
											$("#cambioCtaCliente").val("0.00");
											$("#txtCambioCtaCliente").val("0.00");
										}
										else
										{
											if(newSaldoCtaCli < 0)
											{
												$("#newSaldoCtaCli").val("0.00");
												$("#txtNewSaldoCtaCli").val("0.00");
												newSaldoCtaCli = -newSaldoCtaCli;	
												newSaldoCtaCli = parseFloat(newSaldoCtaCli).toFixed(2);
												txtCambioCtaCliente = addCommas(newSaldoCtaCli);
												$("#cambioCtaCliente").val(newSaldoCtaCli);
												$("#txtCambioCtaCliente").val(txtCambioCtaCliente);
											}
											else
											{
												if(newSaldoCtaCli > 0)
												{
													newSaldoCtaCli = parseFloat(newSaldoCtaCli).toFixed(2);
													var txtNewSaldoCtaCli = addCommas(newSaldoCtaCli);
													$("#txtNewSaldoCtaCli").val(txtNewSaldoCtaCli);	
													$("#newSaldoCtaCli").val(newSaldoCtaCli);	
													$("#cambioCtaCliente").val("0.00");
													$("#txtCambioCtaCliente").val("0.00");
												}
											}
										}	
										$("#btnAgregarPagoCta").removeAttr("disabled");					
									}
									else
									{
										alert("Capture un valor correcto para el abono.");
										$("#btnAgregarPagoCta").attr("disabled", true);
										$("#newSaldoCtaCli").val("0.00");
										$("#txtNewSaldoCtaCli").val("0.00");
										$("#cambioCtaCliente").val("0.00");
										$("#txtCambioCtaCliente").val("0.00");
									}
								}
								
								function agregar_pago_cliente()
								{
									var saldoActualCli = $("#saldoActualCli").val();
									var txtAbonoCli = $("#txtAbonoCli").val();
									var idClientePago = $("#idClientePago").val();									
									var optTipoPago = $("input:radio[name=optTipoPago]:checked").val();
									var cambioCliente = $("#cambioCliente").val();	
									txtAbonoCli = parseFloat(txtAbonoCli) - parseFloat(cambioCliente);
																	
									$("#div_articulos").load("../seccion/ajax/guarda_pago_cliente.php",
										 {saldoActualCli:saldoActualCli,txtAbonoCli:txtAbonoCli,idClientePago:idClientePago,optTipoPago:optTipoPago},
										 function(){
											 		reset_pago_cliente();
													reset_pago_cuenta_cliente();																				
										 });	
								}
								
								function agregar_pago_cta_cliente()
								{
									var saldoActualCtaCli = $("#saldoActualCtaCli").val();
									var txtAbonoCtaCli = $("#txtAbonoCtaCli").val();
									var idClientePago = $("#idClienteCtaPago").val();
									var idCtaCliente = $("#idCtaCliente").val();
									var optTipoPagoCta = $("input:radio[name=optTipoPagoCta]:checked").val();
									var cambioCtaCliente = $("#cambioCtaCliente").val();	
									txtAbonoCtaCli = parseFloat(txtAbonoCtaCli) - parseFloat(cambioCtaCliente);
																	
									$("#div_articulos").load("../seccion/ajax/guarda_pago_cta_cliente.php",
										 {saldoActualCtaCli:saldoActualCtaCli,txtAbonoCtaCli:txtAbonoCtaCli,idClientePago:idClientePago,idCtaCliente:idCtaCliente,optTipoPagoCta:optTipoPagoCta},
										 function(){
													reset_pago_cuenta_cliente();
													reset_pago_cliente();							
										 });	
								}
								
								function reset_pago_cliente()
								{
									$("#txtLimiteActualCli").val("");
									$("#txtSaldoActualCli").val("");	
									$("#txtAbonoCli").val("");
									$("#abonoCli").val("0.00");
										
									$("#btnAgregarPago").attr("disabled", true);
									$("#idClientePago").val("");
									$("#txtNewSaldoCli").val("0.00");
									$("#newSaldoCli").val("0.00");
									
									$("#txtCambioCliente").val("0.00");
									$("#cambioCliente").val("0.00");
									$.post("../seccion/ajax/listaCliente.php", {}, function(data){
											$("#s2id_idCliPago .select2-chosen").html("Seleccione cliente");
											$("#idCliPago").html(data);												
										});	
								}
								
								function reset_pago_cuenta_cliente()
								{
									$("#txtLimiteActualCtaCli").val("");
									$("#txtSaldoActualCtaCli").val("");	
									$("#txtAbonoCtaCli").val("");
									$("#abonoCtaCli").val("0.00");
										
									$("#btnAgregarPagoCta").attr("disabled", true);
									$("#idClientePago").val("");
									$("#txtNewSaldoCtaCli").val("0.00");
									$("#newSaldoCtaCli").val("0.00");
									
									$("#txtCambioCtaCliente").val("0.00");
									$("#cambioCtaCliente").val("0.00");
									$.post("../seccion/ajax/listaCliente.php", {}, function(data){
											$("#s2id_idCliPagoCta .select2-chosen").html("Seleccione cliente");
											$("#idCliPagoCta").html(data);											
										});	
									
									$("#divCtaCliente").css("display","none");
								}
								
								function reset_venta_cotizacion()
								{
									$.post("../seccion/ajax/listaCliente.php", {}, function(data){
											$("#s2id_idCliCotiza .select2-chosen").html("Seleccione cliente");
											$("#idCliCotiza").html(data);												
										});	
									
									$("#tablaCotizaciones").html("");
									$("#divDatosCotizacion").css("display","none");	
									$("#btnGuardarCotizacion").attr("disabled", true);
									$("#spnTotalPagarCotiza").html("");
									$("#txtSaldoTotalCotiza").val("");
									$("#txtRecibidoCotiza").val("");
									$("#txtCambioCotiza").val("");
									$("#txtCambioSinComasCotiza").val("");									
								}
								
								function carga_producto()
								{
									$("#carga_descripcion").html("");
									$("#carga_precio").html("");
									var cantidadProd = $("#cantidadProd").val();
									var codigoProd = $("#codigoProd").val();
									var var_tipCli = $("#idTipoCli").val();
									var idProd =  $("#idProdActual").val();
									if (cantidadProd!="" && var_tipCli!="")
									{
										var request =$.ajax
													({
														type: "POST",
														url: "../seccion/ajax/carga_precio.php",
														data: {codigoProd:codigoProd, var_tipCli:var_tipCli, idProd:idProd},
														cache: false,
														dataType: "json",
														success: function(data)
														{
															$("#idKitActual").val(data.idKit);
															$("#idProdActual").val(data.id);	
															$("#precioPrdo").val(data.precio);
															$("#carga_precio").html(data.precio);	
															$("#carga_descripcion").html(data.nombre);
															var idProdReturn =  $("#idProdActual").val();
															if(idProdReturn == "")
																$("#idProdActual").val("3");		
															agrega_venta();
															
														},
														error:function (data) 
														{
															alert("Producto no encontrado");
															$("#codigoProd").val("");
														}			
													});													
									}								
								}
								
								function agrega_venta()
								{
									var idKit = $("#idKitActual").val();
									var var_codigo = $("#codigoProd").val();
									var var_cantidad = $("#cantidadProd").val();
									var var_precio = $("#precioPrdo").val();
									var var_iva = $("#factorIvaVenta").val();	
									var idProd =  $("#idProdActual").val();
									var idCliente = $("#idCli").val();
									
									if (var_cantidad!="" && var_precio !="")
									{	
										$("#div_articulos").load("../seccion/ajax/guarda_salida.php",
																 {codigo:var_codigo,cantidad:var_cantidad,precio:var_precio,iva:var_iva,idProd:idProd,idKit:idKit,idCliente:idCliente},
																 function(){																	
																			var respuesta = $("#respuesta").val(); 
																			if(respuesta == "")
																			{
																				agregar_totales();
																				$("#ticket").load("../seccion/ajax/detalles_salida.php");
																				$(".btnEliminar").removeAttr("disabled");
																				if(idCliente != 1)
																					$(".btnCotizar").removeAttr("disabled");
																				else
																					$(".btnCotizar").attr("disabled", true); 
																					
																				$(".btnPagar").removeAttr("disabled");	
																				ocultar_clientes();	
																				$("#idProd").css("display","none");
																				$.post("../seccion/ajax/listaProductos.php", {}, function(data)
																				{
																					$("#s2id_idProd .select2-chosen").html("Seleccione producto");
																					$("#idProd").html(data);
																					$("#s2id_idProd").css("display","block");
																					$("#idProd").css("display","block");	
																				});
																			}
																			else
																			{
																				alert(respuesta);
																				
																			}	
																				$("#idKitActual").val("");
																				$("#idProdActual").val("");
																				$("#codigoProd").val("");
																				$("#cantidadProd").val("1");
																				$("#precioPrdo").val("");
																				$("#carga_precio").html("");	
																				$("#carga_descripcion").html("");																				
																				$("#codigoProd").focus();
																 });																		
									}									
								}
								
								function agregar_totales()
								{
									var codigoProd = $("#codigoProd").val();
									var dataString = \'codigoProd=\'+ codigoProd;
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/totales_salida.php",
										data: dataString,
										cache: false,
										dataType: "json",
										success: function(data)
										{	
											var importe_totales = data.importe;																								
											var total_totales = data.total;	
											
											$("#txtSaldoTotal").val(total_totales);	
											$("#txtNewSaldoTotal").val(total_totales);
											$("#txtNewSaldoTotalCotiza").val(total_totales);	
											$("#txtSaldoTotalCotiza").val(total_totales);	
											$("#txtRecibido").val(total_totales);										
											
											var iva_totales =  total_totales - importe_totales;											
											importe_totalesNew = parseFloat(importe_totales).toFixed(2);
											total_totales = parseFloat(total_totales).toFixed(2);
											iva_totales = parseFloat(iva_totales).toFixed(2); 
											
											var ivas = iva_totales;
											var totales = total_totales;
											var importes = importe_totalesNew;
											
											importe_totalesNew = addCommas(importe_totalesNew);
											total_totales = addCommas(total_totales);
											iva_totales = addCommas(iva_totales);
											$("#subTxt").html(importe_totalesNew);	
											$("#ivaTxt").html(iva_totales);
											$("#totalTxt").html(total_totales);	
											
											$("#subTotal").val(importes);	
											$("#iva").val(ivas);
											$("#total").val(totales);
											
											$("#spnTotalPagar").html(total_totales);
											$("#spnTotalPagarCotizar").html(total_totales);	
											$("#txtSaldoTotal2").val(total_totales);
																						 
											
											if(data.totalAct)
											{
												var total_totales_actual = data.totalAct;
												total_totales_actual = parseFloat(total_totales_actual).toFixed(2);																								
												$("#txtSubOriginal").val(total_totales_actual);
											}
											else
											{
												$("#txtSubOriginal").val(total_totales);
											}
											
											if(total_totales == "0.00")
											{
												$(".btnEliminar").attr("disabled", true); 
												$(".btnDesuento").attr("disabled", true); 
												$(".btnCotizar").attr("disabled", true); 
												$(".btnPagar").attr("disabled", true);
												$("#txtSaldoTotal").val("0.00");
												$("#txtNewSaldoTotal").val("0.00");
												$("#txtSaldoTotalCotiza").val("0.00");	
												$("#txtNewSaldoTotalCotiza").val("0.00");
												$("#spnTotalPagar").html("0.00");
												$("#spnTotalPagarCotizar").html("0.00");
												cargar_clientes();
												cargar_categorias();
												cargar_servicios();
											}
											
											$("#codigoProd").val("");
											$("#cantidadProd").val("1");
											$("#precioPrdo").val("");
											$("#carga_precio").html("");	
											$("#carga_descripcion").html("");
											
											$("#codigoProd").focus();
										}			
									});	
								}
								
								function eliminar_detalle(idDetalle)
								{
									var var_iva = $("#factorIvaVenta").val();
									$("#div_articulos").load("../seccion/ajax/eliminar_detalle.php",
															 {idDetalle:idDetalle,iva:var_iva},
															 function(){
																agregar_totales();
																$("#ticket").load("../seccion/ajax/detalles_salida.php");																	
															  });	
										
								}
								
								function eliminar_salida()
								{
									$("#div_articulos").load("../seccion/ajax/eliminar_salida.php");
									$("#ticket").html("");
									$("#codigoProd").val("");
									$("#cantidadProd").val("1");
									$("#precioPrdo").val("");
									$("#carga_precio").html("");	
									$("#carga_descripcion").html("");
									$("#subTxt").html("0.00");	
									$("#ivaTxt").html("0.00");
									$("#totalTxt").html("0.00");
									$("#txtCambio").val("0");
									$("#txtCambioSinComas").val("0");
									$(".btnEliminar").attr("disabled", true); 
									$(".btnDesuento").attr("disabled", true); 
									$(".btnCotizar").attr("disabled", true); 
									$(".btnPagar").attr("disabled", true); 
									eliminar_cliente();
									cargar_clientes();
									cargar_categorias();
									cargar_servicios();
									$("#codigoProd").focus();
									
								}
								
								function cargar_categorias()
								{
									$("#tabArticulos").html(\'<div class="row breadcrumb" id="divListaClientes"><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div></div><div id="cargando"></div>\');
									$("#tabArticulos").load("../seccion/ajax/cargar_categorias.php");	
								}
								
								function cargar_servicios()
								{
									$("#tabServicios").html(\'<div class="row breadcrumb" id="divListaClientes"><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div></div><div id="cargando"></div>\');
									$("#tabServicios").load("../seccion/ajax/cargar_servicios.php");	
								}
								
								function cargar_clientes()
								{
									$("#tabClientes").load("../seccion/ajax/cargar_clientes.php");	
								}
								
								function cargar_kits()
								{
									$("#tabKits").load("../seccion/ajax/cargar_kits.php");	
								}
								
								function cargar_promociones()
								{
									$("#tabPromos").load("../seccion/ajax/cargar_promociones.php");	
								}
								
								function ocultar_clientes()
								{
									$("#tabClientes").html("");	
									$("#nombreCliente i").remove();	
								}
								
								function cargar_subCategorias(idCat)
								{
									var var_tipCli = $("#idTipoCli").val();
									$("#tabArticulos").html(\'<div class="row breadcrumb" id="divListaClientes"><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div></div><div id="cargando"></div>\');
									$("#tabArticulos").load("../seccion/ajax/cargar_subCategorias.php",{idCat:idCat,var_tipCli:var_tipCli});
									$("#id_marca").load("../seccion/ajax/cargar_marcas.php");	
									$("#id_unidad").load("../seccion/ajax/cargar_unidades.php");	
									$("#id_pasillo").load("../seccion/ajax/cargar_pasillos.php");	
									$("#id_planta").load("../seccion/ajax/cargar_plantas.php");	
									$("#id_anaquel").load("../seccion/ajax/cargar_anaquel.php");																											
								}
								
								function cargar_subServicios(idCat)
								{
									var var_tipCli = $("#idTipoCli").val();
									$("#tabServicios").html(\'<div class="row breadcrumb" id="divListaClientes"><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div></div><div id="cargando"></div>\');
									$("#tabServicios").load("../seccion/ajax/cargar_subServicios.php",{idCat:idCat,var_tipCli:var_tipCli});
									$("#id_marca").load("../seccion/ajax/cargar_marcas.php");	
									$("#id_unidad").load("../seccion/ajax/cargar_unidades.php");	
									$("#id_pasillo").load("../seccion/ajax/cargar_pasillos.php");	
									$("#id_planta").load("../seccion/ajax/cargar_plantas.php");	
									$("#id_anaquel").load("../seccion/ajax/cargar_anaquel.php");																											
								}
								
								function agregar_tipoCat(tipo)
								{
									$("#txtNewCat").val(tipo);
								}
								
								function nuevo_producto()
								{
									var tipoCat = $("#txtNewCat").val();
									var nivel = $("#nivelCat").val();
									var padre = $("#padreCat").val();
									var nombre = $("#nombreProducto").val();
									var codigo = $("#codigo").val();
									var codigoInt = $("#codigoInt").val();
									var precioGeneral = $("#precioGeneral").val();
									var costo = $("#costoProducto").val();
									var stock = $("#stockProducto").val();
									var stockMin = $("#stockMinProducto").val();
									$("#id_marca option:selected").each(function () 
									{
										marca=$(this).val();
									});
									
									$("#id_unidad option:selected").each(function () 
									{
										unidad=$(this).val();
									});
									
									if ($("#id_pasillo").length)
									{
									 	$("#id_pasillo option:selected").each(function () 
										{
											pasillo=$(this).val();												
										});
									}
									else
										pasillo = 1;
																			
									if ($("#id_planta").length)
									{
									 	$("#id_planta option:selected").each(function () 
										{
											planta=$(this).val();												
										});
									}
									else
										planta = 1;
										
									if ($("#id_anaquel").length)
									{
									 	$("#id_anaquel option:selected").each(function () 
										{
											anaquel=$(this).val();												
										});
									}
									else
										anaquel = 1;
										
									isFloatPrecio = true;
									isFloatCosto = true;									
									
									if(!/^(\d)+((\.)(\d){1,2})?$/.test(precioGeneral))
										isFloatPrecio = false;
									if(!/^(\d)+((\.)(\d){1,2})?$/.test(costo))
										isFloatCosto = false;
									
									if(marca!="" && unidad!="")	
									{
										if (nombre!="" && stock!="")
										{
											if(isFloatPrecio == true)	
											{
												if(isFloatCosto == true)	
												{
													$("#div_articulos").load("../seccion/ajax/nuevo_producto.php",
																			{nivel:nivel,padre:padre,nombre:nombre,codigo:codigo,codigoInt:codigoInt,precioGeneral:precioGeneral,
																			 costo:costo,stock:stock,stockMin:stockMin,marca:marca,unidad:unidad,pasillo:pasillo,
																			 planta:planta,anaquel:anaquel},
																			 function(){
																				if(tipoCat == 1)
																					cargar_subCategorias(padre);
																				else
																				{
																					if(tipoCat == 2)
																						cargar_subServicios(padre);
																				}
																				
																				$("#txtNewCat").val();
																				$("#nombreProducto").val();
																				$("#codigo").val();
																				$("#codigoInt").val();
																				$("#precioProducto").val();
																				$("#costoProducto").val();
																				$("#stockProducto").val();
																				$("#stockMinProducto").val();
																				$("#id_marca :nth-child(1)").prop("selected", true);
																				$("#id_unidad :nth-child(1)").prop("selected", true);
																				$("#id_pasillo :nth-child(1)").prop("selected", true);
																				$("#id_planta :nth-child(1)").prop("selected", true);
																				$("#selecid_anaqueltBox :nth-child(1)").prop("selected", true);																	
																			});
													
												}
												else
												{
													alert("El formato de costo no es correcto.");
												}
											}
											else
											{
												alert("El formato de precio no es correcto.");
											}
										}
										else
										{
											alert("nombre y stock mínimo del producto.");
										}
									}
									else
									{
										alert("Capture clasificaión y localización del producto.");
									}
								}
								
								function agregar_productoMisc()
								{
									var var_iva = $("#factorIvaVenta").val();
									var txtCantMisc = $("#txtCantMisc").val();
									var nombreMisc = $("#nombreMisc").val();
									var precioMisc = $("#precioMisc").val();
									var idCliente = $("#idCli").val();
									var var_tipCli = $("#idTipoCli").val();
									$("#idUniMisc option:selected").each(function () 
									{
										idUniMisc=$(this).val();
									});
									isFloatPrecio = true;
									if(!/^(\d)+((\.)(\d){1,2})?$/.test(precioMisc))
										isFloatPrecio = false;
										
									if(txtCantMisc!="" && nombreMisc!="")
									{
										if(idUniMisc != 0)
										{
											if(isFloatPrecio == true)	
											{
												$("#div_articulos").$sal["id"]("../seccion/ajax/nuevo_producto_misc.php",
																			{cantidad:txtCantMisc,nombre:nombreMisc,precio:precioMisc,unidad:idUniMisc,iva:var_iva,var_tipCli:var_tipCli,idCliente:idCliente},
																			 function(){
																				$("#txtCantMisc").val("");
																				$("#nombreMisc").val("");
																				$("#precioMisc").val("");
																				$("#s2id_idUniMisc .select2-chosen").val("");	
																				$("#s2id_idUniMisc .select2-chosen").html("SELECCIONE PRODUCTO");
																				$(".selectSerch option[value=\'0\']").prop("selected", "selected");
																				
																				agregar_totales();
																  				$("#ticket").load("../seccion/ajax/detalles_salida.php");															
																			});
												
												$(".btnEliminar").removeAttr("disabled");
												$(".btnDesuento").removeAttr("disabled");
												if(idCliente != 1)
													$(".btnCotizar").removeAttr("disabled");
												else
													$(".btnCotizar").attr("disabled", true); 
													
												$(".btnPagar").removeAttr("disabled");
												ocultar_clientes();
											}
											else
											{
												alert("El formato de precio no es correcto.");												
											}
										}
										else
										{
											alert("Seleccione un tipo de unidad.");
										}
									}
									else
									{
										alert("Capture catidad y nombre del producto.");
									}
								}
								
								function nueva_subCategorias()
								{
									var var_nivel = $("#nivelCat").val();
									var var_padre = $("#padreCat").val();
									var nombreCatMod = $("#nombreCatMod").val();
									var descCatMod = $("#descCatMod").val();
									$("#div_articulos").load("../seccion/ajax/nueva_subCategorias.php",{nivel:var_nivel,idPadre:var_padre,nombreCatMod:nombreCatMod,descCatMod:descCatMod});
								}
								
								function cargar_cliente(idCliente)
								{
									var idCliente = \'idCliente=\'+ idCliente;
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/guardar_cliente.php",
										data: idCliente,
										cache: false,
										dataType: "json",
										success: function(data)
										{
											var idClien = data.id;	
											var idTipoClien = data.idTipo;
											var limitCredCli = data.credito;
											var correo = data.correo;
											$("#idCli").val(idClien);
											$("#idTipoCli").val(idTipoClien);
											$("#limitCredCli").val(limitCredCli);
											$("#correoCli").val(correo);	
											$("#nombreCliente").html(data.nombre + \' <i title="Eliminar" style="cursor:pointer;" onClick="eliminar_cliente(\'+idClien+\')" class="fa fa-times"></i>\');
											cargar_categorias();
										}			
									});									
								}
								
								function nuevo_cliente()
								{
									var nombre = $("#nombreCli").val();
									var tipoCli = $("#id_tipos").val();
									var razon = $("#razon").val();
									var rfc = $("#rfcCliente").val();
									var calle = $("#calleCliente").val();
									var numExt = $("#numExtCliente").val();
									var numInt = $("#numIntCliente").val();
									var colonia = $("#coloniaCliente").val();
									var localidad = $("#localidad").val();
									var cp = $("#cpCliente").val();
									$("#id_ciudad option:selected").each(function () 
									{
										ciudad=$(this).val();
									});
									var contacto = $("#nombreContactoCliente").val();
									var correo = $("#correoCliente").val();
									var lada = $("#ladaCliente").val();
									var tel = $("#telCliente").val();
									var saldo = $("#saldo").val();
									var credito = $("#credito").val();
									
									if (nombre!="" && correo!="")
									{
										$("#div_articulos").load("../seccion/ajax/nuevo_cliente.php",
										{
												nombre:nombre,tipoCli:tipoCli,razon:razon,rfc:rfc,calle:calle,
												numExt:numExt,numInt:numInt,colonia:colonia,cp:cp,
												ciudad:ciudad,contacto:contacto,correo:correo,lada:lada,tel:tel,
												saldo:saldo, credito:credito, localidad:localidad
										},
									    function(){
										  	cargar_clientes();
											cargar_categorias();
											cargar_servicios();
											$("#nombreCli").val("");
											$("#razon").val("");
											$("#rfcCliente").val("");
											$("#calleCliente").val("");
											$("#numExtCliente").val("");
											$("#numIntCliente").val("");
											$("#coloniaCliente").val("");
											$("#cpCliente").val("");
											$("#id_ciudad").val("");
											$("#nombreContactoCliente").val("");
											$("#correoCliente").val("");
											$("#ladaCliente").val("");
											$("#telCliente").val("");	
											$("#saldo").val("");
											$("#credito").val("");
											$("#localidad").val("");																								
										});								
									}
									else
									{
										alert("Capture nombre y correo del cliente");
									}
								}
								
								function cargar_producto_detalle(idProducto)
								{
									var idCliente = $("#idCli").val();
									var var_iva = $("#factorIvaVenta").val();
									var var_tipCli = $("#idTipoCli").val();
									$("#div_articulos").load("../seccion/ajax/guarda_producto_salida.php",
															  {idProducto:idProducto,iva:var_iva,var_tipCli:var_tipCli,idCliente:idCliente},
															   function(){
																  agregar_totales();
																  $("#ticket").load("../seccion/ajax/detalles_salida.php");																	
																});																  
										
									$(".btnEliminar").removeAttr("disabled");
									$(".btnDesuento").removeAttr("disabled");
									if(idCliente != 1)
										$(".btnCotizar").removeAttr("disabled");
									else
										$(".btnCotizar").attr("disabled", true); 
										
									$(".btnPagar").removeAttr("disabled");
									ocultar_clientes();
								}
								
								function cargar_kit_detalle(idKit)
								{
									var idCliente = $("#idCli").val();
									var var_iva = $("#factorIvaVenta").val();
									var var_tipCli = $("#idTipoCli").val();
									$("#div_articulos").load("../seccion/ajax/guarda_kit_salida.php",
															  {idKit:idKit,iva:var_iva,var_tipCli:var_tipCli,idCliente:idCliente},
															   function(){
																  agregar_totales();
																  $("#ticket").load("../seccion/ajax/detalles_salida.php");																	
																});																  
										
									$(".btnEliminar").removeAttr("disabled");
									$(".btnDesuento").removeAttr("disabled");
									if(idCliente != 1)
										$(".btnCotizar").removeAttr("disabled");
									else
										$(".btnCotizar").attr("disabled", true); 
										
									$(".btnPagar").removeAttr("disabled");
									ocultar_clientes();
								}
								
								function aplicar_descuento_monto()
								{
									var txtSaldoTotal = $("#txtSaldoTotal").val();
									var txtDescMonto = $("#txtDescMonto").val();
									if(txtDescMonto == "")
										txtDescMonto = 0.00;
										
									if(parseFloat(txtSaldoTotal) >= parseFloat(txtDescMonto))
									{
										$("#divValidaMonto").css("display","none");
										$("#spnDivDesc").html("");
										var txtNewSaldoTotal = txtSaldoTotal - txtDescMonto;	
										var txtDescPorc = txtDescMonto * 100;	
										txtDescPorc = txtDescMonto / txtSaldoTotal;	
										txtDescPorc = txtDescPorc * 100;
										
										txtNewSaldoTotal = parseFloat(txtNewSaldoTotal).toFixed(2);
										txtDescPorc = parseFloat(txtDescPorc).toFixed(2);						
										
										$("#txtNewSaldoTotal").val(txtNewSaldoTotal);
										$("#txtDescPorc").val(txtDescPorc);	
										$("#txtRecibido").val(txtNewSaldoTotal);
										revisar_cambio();																				
									}
									else
									{										
										$("#divValidaMonto").css("display","block");
										$("#spnDivDesc").html(txtSaldoTotal);
										$("#txtDescMonto").val("");
										$("#txtDescPorc").val("");
										$("#txtNewSaldoTotal").val(txtSaldoTotal);
										revisar_cambio();
									}
								}
								
								function aplicar_descuento_monto_cotiza()
								{
									var txtSaldoTotalCotiza = $("#txtSaldoTotalCotiza").val();
									var txtDescMontoCotiza = $("#txtDescMontoCotiza").val();
									if(txtDescMontoCotiza == "")
										txtDescMontoCotiza = 0.00;
										
									if(parseFloat(txtSaldoTotalCotiza) >= parseFloat(txtDescMontoCotiza))
									{
										$("#divValidaMontoCotiza").css("display","none");
										$("#spnDivDescCotiza").html("");
										var txtNewSaldoTotalCotiza = txtSaldoTotalCotiza - txtDescMontoCotiza;	
										var txtDescPorcCotiza = txtDescMontoCotiza * 100;	
										txtDescPorcCotiza = txtDescMontoCotiza / txtSaldoTotalCotiza;	
										txtDescPorcCotiza = txtDescPorcCotiza * 100;
										
										txtNewSaldoTotalCotiza = parseFloat(txtNewSaldoTotalCotiza).toFixed(2);
										txtDescPorcCotiza = parseFloat(txtDescPorcCotiza).toFixed(2);						
										
										$("#txtNewSaldoTotalCotiza").val(txtNewSaldoTotalCotiza);
										$("#txtDescPorcCotiza").val(txtDescPorcCotiza);	
										$("#txtRecibidoCotiza").val(txtNewSaldoTotal);	
										$("#btnCobroTicket").attr("disabled", true);																						
									}
									else
									{										
										$("#divValidaMontoCotiza").css("display","block");
										$("#spnDivDescCotiza").html(txtSaldoTotalCotiza);
										$("#txtDescMontoCotiza").val("");
										$("#txtDescPorcCotiza").val("");
										$("#txtNewSaldoTotalCotiza").val(txtSaldoTotalCotiza);
									}
								}
								
								function aplicar_descuento_porc()
								{									
									var txtSaldoTotal = $("#txtSaldoTotal").val();
									var txtDescPorc = $("#txtDescPorc").val();
									if(txtDescPorc == "")
										txtDescPorc = 0.00;
										
									if(txtDescPorc <= 100 && txtDescPorc >= 0)
									{
										$("#divValidaPor").css("display","none");
										var txtDescMonto = txtSaldoTotal * txtDescPorc;
										var txtDescMonto = txtDescMonto / 100;
										var txtNewSaldoTotal = txtSaldoTotal - txtDescMonto;
										txtNewSaldoTotal = parseFloat(txtNewSaldoTotal).toFixed(2);
										txtDescMonto = parseFloat(txtDescMonto).toFixed(2);
										
										$("#txtDescMonto").val(txtDescMonto);
										$("#txtNewSaldoTotal").val(txtNewSaldoTotal);
										$("#txtRecibido").val(txtNewSaldoTotal);
										revisar_cambio();
									}
									else
									{
										$("#divValidaPor").css("display","block");
										$("#txtDescPorc").val("");
										$("#txtNewSaldoTotal").val(txtSaldoTotal);	
										$("#txtRecibido").val(txtSaldoTotal);	
										revisar_cambio();							
									}
								}
								
								function aplicar_descuento_porc_cotiza()
								{									
									var txtSaldoTotalCotiza = $("#txtSaldoTotalCotiza").val();
									var txtDescPorcCotiza = $("#txtDescPorcCotiza").val();
									if(txtDescPorcCotiza == "")
										txtDescPorcCotiza = 0.00;
										
									if(txtDescPorcCotiza <= 100 && txtDescPorcCotiza >= 0)
									{
										$("#divValidaPorCotiza").css("display","none");
										var txtDescMonto = txtSaldoTotalCotiza * txtDescPorcCotiza;
										txtDescMontoCotiza = txtDescMonto / 100;
										var txtNewSaldoTotalCotiza = txtSaldoTotalCotiza - txtDescMontoCotiza;
										txtNewSaldoTotalCotiza = parseFloat(txtNewSaldoTotalCotiza).toFixed(2);
										txtDescMontoCotiza = parseFloat(txtDescMontoCotiza).toFixed(2);
										
										$("#txtDescMontoCotiza").val(txtDescMontoCotiza);
										$("#txtNewSaldoTotalCotiza").val(txtNewSaldoTotalCotiza);
										$("#txtRecibido").val(txtNewSaldoTotal);
										$(".btnCobroTicket").attr("disabled", true);
									}
									else
									{
										$("#divValidaPorCotiza").css("display","block");
										$("#txtDescPorcCotiza").val("");
										$("#txtDescMontoCotiza").val("");
										$("#txtNewSaldoTotalCotiza").val(txtSaldoTotalCotiza);							
									}
								}
								
								function agregar_descuento()
								{
									if($("#chkTipo").is(":checked")) 
									{  
										var txtDesc = $("#txtDescMonto").val();
									} 
									else
									{	
										var txtDesc = $("#txtDescPorc").val();										
									}
									var txtAplica = $("#txtDescAplica").val();
									var porcAplica = $("#txtPorcAplica").val();
									
									var txtNuevo = $("#txtNuevoSubtotal").val();
									var txtDesc = $("#txtDescPorc").val();									
									var var_iva = $("#factorIvaVenta").val();
																		
									$("#div_articulos").load("../seccion/ajax/guarda_descuento.php",
															  {txtDesc:txtDesc,txtAplica:txtAplica,txtNuevo:txtNuevo,porcAplica:porcAplica,iva:var_iva},
															   function(){
																  	agregar_totales();
																  	$("#ticket").load("../seccion/ajax/detalles_salida.php");
																		
																  	$("#txtDescMonto").val("");
																	$("#txtDescNuevo").val("");
																	$("#spnPorcentaje").html("");	
																	$("#txtNuevoSubtotal").val("");
																	$("#btnAgregaDesc").css("display","");	
																	$("#txtDescAplica").val("");																	
																});									
								}
								
								function foma_pago(tipo)
								{									
									$("#txtRecibido").focus();
									$("#txtFormaPago").val(tipo);
									$("#divFormPago").toggle();
									$("#divFormPagoTotal").toggle();
									$("#spnPagoCheque").css("display", "none");
									$("#spnPagoTarjeta").css("display", "none");
									$("#spnPagoEfectivo").css("display", "none");
									if(tipo == 1)
									{
										$("#spnPagoCheque").css("display", "block");
									}
									else
									{
										if(tipo == 2)
										{
											$("#spnPagoTarjeta").css("display", "block");
										}
										else
										{
											if(tipo == 3)
											{
												$("#spnPagoEfectivo").css("display", "block");
											}											
										}
									}
									
								}
								
								function regresa_forma_pago()
								{
									$("#txtFormaPago").val("");
									$("#divFormPago").toggle();
									$("#divFormPagoTotal").toggle();
									$("#spnPagoCheque").css("display", "none");
									$("#spnPagoTarjeta").css("display", "none");
									$("#spnPagoEfectivo").css("display", "none");
								}
								
								function restaura_forma_pago()
								{
									var idCli = $("#idCli").val();
									var idCliente = \'idCliente=\'+ idCli;
									if(idCli == 1)
										$("#btnCredito").css("display", "none");
									else
										$("#btnCredito").css("display", "inline");										
										
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/revisar_cliente.php",
										data: idCliente,
										cache: false,
										dataType: "json",
										success: function(data)
										{	
											$("#txtNameCliente").val(data.nombre);
											$("#divFormPago").css("display", "block");
											$("#divFormPagoTotal").css("display", "none");
										}			
									});										
								}
								
								function foma_cotizar()
								{									
									$("#divFormCotizar").toggle();
									$("#divFormCotizarTotal").toggle();								
								}
								
								function regresa_forma_cotiza()
								{
									$("#divFormCotizar").toggle();
									$("#divFormCotizarTotal").toggle();
								}
								
								function revisar_cambio()
								{
									var txtNewSaldoTotal = $("#txtNewSaldoTotal").val();
									var txtRecibido = $("#txtRecibido").val();
									if(txtRecibido == "")
										txtRecibido = 0;
									
									var txtCambio = txtRecibido - txtNewSaldoTotal;
									txtCambio = parseFloat(txtCambio).toFixed(2);
									if(parseFloat(txtRecibido) < parseFloat(txtNewSaldoTotal))
									{
										var cambio = -txtCambio;
										cambio = parseFloat(cambio).toFixed(2);
										var cambioSin = cambio;
									    cambio = addCommas(cambio);
										$(".btnCredito").removeAttr("disabled");
										$("#spnTxtCambio").html("Saldo restante:");
										$(".spnTxtCambio").css("color","#4D4D4D"); 
										$(".btnCobroSimple").attr("disabled", true);
										$(".btnCobroTicket").attr("disabled", true);
									}
									else
									{
										var cambio = txtCambio;
										cambio = parseFloat(cambio).toFixed(2);
										var cambioSin = cambio;
									    cambio = addCommas(cambio);
										
										if(parseFloat(txtRecibido) == parseFloat(txtNewSaldoTotal))
										{	
											$(".btnCredito").attr("disabled", true);
											$(".spnTxtCambio").css("color","#85C744"); 	
											$("#spnTxtCambio").html("Cambio:");
											$(".btnCobroSimple").removeAttr("disabled");
											$(".btnCobroTicket").removeAttr("disabled");							
										}
										else
										{
											$(".btnCredito").attr("disabled", true); 
											$(".spnTxtCambio").css("color","#E73C3C");
											$("#spnTxtCambio").html("Cambio:");
											$(".btnCobroSimple").removeAttr("disabled");
											$(".btnCobroTicket").removeAttr("disabled");			
										}
									}						
									
									$("#txtCambio").val(cambio);
									$("#txtCambioSinComas").val(cambioSin);
								}
								
								function guardar_cotizacion()
								{
									var txtDesc = $("#txtDescMontoCotiza").val();
									var txtAplica = $("#txtDescPorcCotiza").val();									
									var var_iva = $("#factorIvaVenta").val();
									var notaCotiza = $("#notaCotiza").val();
									var idCli = $("#idCli").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_cotizacion.php",
															   {txtDesc:txtDesc,txtAplica:txtAplica,iva:var_iva,notaCotiza:notaCotiza,idCli:idCli},
															   function(){
																  	location.reload();																
																});		
								}
								
								function guardar_cotizacion_enviar()
								{
									var txtDesc = $("#txtDescMontoCotiza").val();
									var txtAplica = $("#txtDescPorcCotiza").val();									
									var var_iva = $("#factorIvaVenta").val();
									var correoCli = $("#correoCli").val();
									var notaCotiza = $("#notaCotiza").val();
									var idCli = $("#idCli").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_cotizacion_enviar.php",
															   {txtDesc:txtDesc,txtAplica:txtAplica,iva:var_iva,correoCli:correoCli,notaCotiza:notaCotiza,idCli:idCli},
															   function(){																   
																  	location.reload();																
																});		
								}
								
								function cerrar_venta_simple()
								{
									var idPago = $("#txtFormaPago").val();
									var idCli = $("#idCli").val();
									var var_iva = $("#factorIvaVenta").val();
									var totalPagar = $("#txtSaldoTotal").val();
									var descNota = $("#descNota").val();
									
									var txtDesc = $("#txtDescMonto").val();
									var txtAplica = $("#txtDescPorc").val();
									var idTipoDoc = $("#idTipoDoc").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_venta.php",
															  {idPago:idPago,idCli:idCli,totalPagar:totalPagar,txtDesc:txtDesc,txtAplica:txtAplica,iva:var_iva,descNota:descNota,idTipoDoc:idTipoDoc},
															   function(){
																  	location.reload();																
																});		
								}
								
								function cerrar_venta_ticket()
								{
									var idPago = $("#txtFormaPago").val();
									var idCli = $("#idCli").val();
									var var_iva = $("#factorIvaVenta").val();
									var totalPagar = $("#txtSaldoTotal").val();
									var txtDesc = $("#txtDescMonto").val();
									var descNota = $("#descNota").val();
									
									
									var txtAplica = $("#txtDescPorc").val();
									var idTipoDoc = $("#idTipoDoc").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_venta.php",
															  {idPago:idPago,idCli:idCli,totalPagar:totalPagar,txtDesc:txtDesc,txtAplica:txtAplica,iva:var_iva,descNota:descNota,idTipoDoc:idTipoDoc},
															   function(){																   
																   var ref = window.open("../seccion/ajax/crea_ticket.php?idPago="+idPago+"&idCli="+idCli+"&var_iva="+var_iva,"_blank", "height=700,width=800,scrollTo,resizable=1,scrollbars=1,location=0");
																	if(ref.attachEvent) 
																	{
																		ref.attachEvent("onunload", ClosePopup);
																	} 
																	else 
																	{
																		if(ref.addEventListener) 
																		{
																			ref.addEventListener("unload", ClosePopup, false);
																		} 
																		else 
																		{
																			ref.onunload = ClosePopup;
																		}
																	}																  																
																});	
								}
								
								function cerrar_venta_saldo()
								{
									var var_cambio =  $("#txtCambio").val();
									var var_cambioSin =  $("#txtCambioSinComas").val();
									var var_limCambio =  $("#limitCredCli").val();
									var_limCambio = parseFloat(var_limCambio).toFixed(2);
									var comasLimite = addCommas(var_limCambio);									
									if(parseFloat(var_limCambio) == 0.00)
									{
										alert("El cliente agotó su límite de crédito");
									}
									else
									{
										if(parseFloat(var_cambioSin) > parseFloat(var_limCambio))
										{
											alert("El cliente solo tiene un límite de $"+comasLimite);
										}
										else
										{
											if(parseFloat(var_cambioSin) <= parseFloat(var_limCambio))
											{
												var idPago = $("#txtFormaPago").val();
												var idCli = $("#idCli").val();
												var totalPagar = $("#txtSaldoTotal").val();
												var var_iva = $("#factorIvaVenta").val();
												var var_recib =  $("#txtRecibido").val();
												var txtDesc = $("#txtDescMonto").val();												
												var txtAplica = $("#txtDescPorc").val();
												var descNota =  $("#descNota").val();
												var idTipoDoc = $("#idTipoDoc").val();
												
												$("#div_articulos").load("../seccion/ajax/cerrar_venta_saldo.php",
																		  {idPago:idPago,idCli:idCli,totalPagar:totalPagar,var_recib:var_recib,txtDesc:txtDesc,txtAplica:txtAplica,iva:var_iva,descNota:descNota,idTipoDoc:idTipoDoc},
																		   function(){
																				location.reload();																
																			});
											}
										}
									}
								}
								
								function cerrar_venta_cotiza_simple()
								{
									var idPago = $("input[name=\'optFormaPago\']:checked").val(); 
									if($("#creaNota").is(":checked")) 
									{  
										var idTipoDoc = 0;
									} 
									else
									{	
										var idTipoDoc = 1;							
									}
									var checkboxSalida = [];
									$(".checkboxSalida:checked").each(function(i, e) 
									{
										checkboxSalida.push($(this).val());
									});	
									
									var jsonString = JSON.stringify(checkboxSalida);
									$("#div_articulos").load("../seccion/ajax/cerrar_venta_cotiza.php",
															  {idPago:idPago,idTipoDoc:idTipoDoc,data:jsonString},
															   function(){
																  	reset_venta_cotizacion();
																	regresa_cotizaciones_venta();															
																});		
								}
								
								function cerrar_venta_cotiza_ticket()
								{
									var idPago = $("input[name=\'optFormaPago\']:checked").val(); 
									if($("#creaNota").is(":checked")) 
									{  
										var idTipoDoc = 0;
									} 
									else
									{	
										var idTipoDoc = 1;							
									}
									var checkboxSalida = [];
									$(".checkboxSalida:checked").each(function(i, e) 
									{
										checkboxSalida.push($(this).val());
									});	
									
									var jsonString = JSON.stringify(checkboxSalida);
									$("#div_articulos").load("../seccion/ajax/cerrar_venta_cotiza_ticket.php",
															  {idPago:idPago,idTipoDoc:idTipoDoc,data:jsonString},
															   function(){																   
																  	reset_venta_cotizacion();
																	regresa_cotizaciones_venta();													  																
																});	
								}
								
								function ClosePopup()
								{
									location.reload();
								}
								
								function eliminar_cliente(idCliente)
								{
									$("#idCli").val(1);	
									$("#idTipoCli").val(1);
									$("#limitCredCli").val("0.00");	
									$("#nombreCliente").html("Cliente General");
									cargar_categorias();
									cargar_clientes();
									cargar_servicios();
								}
								
								$("#id_estado").change(function () 
								{
									$("#formCiudad").css("display","none");
									$("#id_estado option:selected").each(function () 
									{
										elegido=$(this).val();
										$.post("../seccion/ajax/ciudadesAjax.php", { elegido: elegido }, function(data){
										$("#id_ciudad").html(data);
										$("#formCiudad").css("display","block");								  
										});			
									});
								});
								
								function editarDetallesPrecio(idDetalles)
								{
									var var_tipCli = $("#idTipoCli").val();
									var var_iva = $("#factorIvaVenta").val();
									$("#inline-precio"+idDetalles).editable({
										 type: "text",
										 pk: idDetalles,									 
										 title: "Nuevo precio",
										 mode: "popup",										 
										 url: "../seccion/ajax/cambiar_precio_detalle.php",
										 data: {var_tipCli:var_tipCli},
										 success: function()
										 {
											 agregar_totales();
											 $("#ticket").load("../seccion/ajax/detalles_salida.php");
										 },
										 params: function(params)
										 {
											var push = {};
											push["idDetalle"] = params.pk;
											push["ivaProd"] = var_iva;
											push["newPrecio"] = params.value;
											push["var_tipCli"] = var_tipCli;
											return push;
										  }																  	
								   });
								}
								
								function editarDetallesCantidad(idDetalles)
								{
									var var_tipCli = $("#idTipoCli").val();
									var var_iva = $("#factorIvaVenta").val();
									$("#inline-cantidad"+idDetalles).editable({
										 type: "text",
										 pk: idDetalles,									 
										 title: "Nueva cantidad",
										 mode: "popup",
										 url: "../seccion/ajax/cambiar_cantidad_detalle.php",
										 success: function()
										 {
											 agregar_totales();
											 $("#ticket").load("../seccion/ajax/detalles_salida.php");
										 },
										  params: function(params)
										 {
											var push = {};
											push["idDetalle"] = params.pk;
											push["ivaProd"] = var_iva;
											push["cantidad"] = params.value;
											push["var_tipCli"] = var_tipCli;
											return push;
										  }										 
								   });
								}
								
								$("#creaCuenta").click(function() 
								{  
									if($("#creaCuenta").is(":checked")) 
									{  
										var importes = $("#subTotal").val();	
										var ivas = $("#iva").val();
										var totales = $("#total").val();
										var importesComas = addCommas(importes);
										var ivasComas =  addCommas(ivas);
										var totalesComas =  addCommas(totales);
										
										$("#txtSaldoTotal2").val(importesComas);
										$("#spnTotalPagar").html(importesComas);
										$("#txtSaldoTotal").val(importes);
										$("#txtNewSaldoTotal").val(importes);
										$("#txtRecibido").val(importes);
										
										$("#txtDescMonto").val("");
										$("#txtDescPorc").val("");
										$("#txtCambio").val("0");
										$("#txtCambioSinComas").val("0");
										$("#idTipoDoc").val("2");
									} 
									else 
									{  
										var importes = $("#subTotal").val();	
										var ivas = $("#iva").val();
										var totales = $("#total").val();
										var importesComas = addCommas(importes);
										var ivasComas =  addCommas(ivas);
										var totalesComas =  addCommas(totales);
										
										$("#txtSaldoTotal2").val(totalesComas);
										$("#spnTotalPagar").html(totalesComas);
										$("#txtSaldoTotal").val(totales);
										$("#txtNewSaldoTotal").val(totales);
										$("#txtRecibido").val(totales);
										
										$("#txtDescMonto").val("");
										$("#txtDescPorc").val("");
										$("#txtCambio").val("0");
										$("#txtCambioSinComas").val("0");
										$("#idTipoDoc").val("1");
									}  
								}); 
								
								$("#toggle-all").on("click", function(){
									$(".checkboxSalida").prop("checked", this.checked);
								}); 
								
								function guardarVentaCotizaciones()
								{
									var checkboxSalida = [];
									$(".checkboxSalida:checked").each(function(i, e) 
									{
										checkboxSalida.push($(this).val());
									});	
									
									var jsonString = JSON.stringify(checkboxSalida);
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/totales_cotizacion_venta.php",
										data: {data : jsonString},
										cache: false,
										dataType: "json",
										success: function(data)
										{	
											if($("#creaNota").is(":checked")) 
											{  
												var saldoTotal = addCommas(data.importeVenta);
												$("#txtSaldoTotalCotiza").val(data.importeVenta);												
												$("#spnTotalPagarCotiza").html(saldoTotal);
											} 
											else
											{	
												var saldoTotal = addCommas(data.totalVenta);
												$("#txtSaldoTotalCotiza").val(data.totalVenta);												
												$("#spnTotalPagarCotiza").html(saldoTotal);									
											}
											$("#divFormasCotizaciones").toggle();
											$("#divFormasPagoCotizaciones").toggle();
										}			
									});										
									
								}
								
								function regresa_cotizaciones_venta()
								{
									$("#divFormasCotizaciones").toggle();
									$("#divFormasPagoCotizaciones").toggle();
									$("#txtRecibidoCotiza").val("");
									$("#txtRecibidoCotiza").val("");
									$(".btnCobroCotizaSimple").attr("disabled", true);
									$(".btnCobroCotizaTicket").attr("disabled", true);
								}
								
								function revisar_cambio_venta_cotizacion()
								{
									var txtSaldoTotalCotiza = $("#txtSaldoTotalCotiza").val();
									var txtRecibidoCotiza = $("#txtRecibidoCotiza").val();
									if(txtRecibidoCotiza == "")
										txtRecibidoCotiza = 0;
									
									var txtCambioCotiza = txtRecibidoCotiza - txtSaldoTotalCotiza;
									txtCambioCotiza = parseFloat(txtCambioCotiza).toFixed(2);
									if(parseFloat(txtRecibidoCotiza) < parseFloat(txtSaldoTotalCotiza))
									{
										$(".btnCobroCotizaSimple").attr("disabled", true);
										$(".btnCobroCotizaTicket").attr("disabled", true);
									}
									else
									{
										var cambioCotiza = txtCambioCotiza;
										cambioCotiza = parseFloat(cambioCotiza).toFixed(2);
										var cambioCotizaSin = cambioCotiza;
									    cambioCotiza = addCommas(cambioCotiza);
										
										if(parseFloat(txtRecibidoCotiza) == parseFloat(txtSaldoTotalCotiza))
										{	
											$(".spnTxtCambio").css("color","#85C744"); 	
											$("#spnTxtCambio").html("Cambio:");
											$(".btnCobroCotizaSimple").removeAttr("disabled");
											$(".btnCobroCotizaTicket").removeAttr("disabled");							
										}
										else
										{
											$(".spnTxtCambio").css("color","#E73C3C");
											$("#spnTxtCambio").html("Cambio:");
											$(".btnCobroCotizaSimple").removeAttr("disabled");
											$(".btnCobroCotizaTicket").removeAttr("disabled");			
										}
									}						
									
									$("#txtCambioCotiza").val(cambioCotiza);
									$("#txtCambioSinComasCotiza").val(cambioCotizaSin);
								}
								
							</script>';
	}
	$scriptSpecial .= '<script>	
						  $("#tableBodyScroll").niceScroll();
						  $("#tabArticulos").niceScroll();
						  $("#tabClientes").niceScroll();
						  $("#tabRecibos").niceScroll();
						  $("#id_estado").change(function () 
						  {
							  $("#formCiudad").css("display","none");
							  $("#id_estado option:selected").each(function () 
							  {
								  elegido=$(this).val();
								  $.post("../seccion/ajax/ciudadesAjax.php", { elegido: elegido }, function(data){
								  $("#id_ciudad").html(data);
								  $("#formCiudad").css("display","block");								  
								  });			
							  });
						  });
						  
						  $("#idProd").change(function () 
						  {
							  $("#idProd option:selected").each(function () 
							  {
								  	elegido=$(this).val();
									var elem = elegido.split("_");
									if(elem[1] == "kit")
									{
										var isKit = 1;
										elegido = elem[0];
									}
									else
										var isKit = 0;
									
									if(elegido != 0)
									{
										if(isKit == 0)
										{
											$("#codigoProd").val("");
											$("#idProdActual").val(elegido);										
											carga_producto();
										}
										else
										{
											cargar_kit_detalle(elegido);
										}
									}
							  });
						  });
					  </script>';
	
	return $scriptSpecial;
}

?>