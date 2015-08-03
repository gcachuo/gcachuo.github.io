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
		$sistema = '<meta http-equiv="refresh" content="0;url=http://www.cbiz.mx/ferreobra/punto">';
		
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
											</ul>
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
						scripttag("../sistema/assets/demo/demo-formcomponents.js");
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
					linktag('../sistema/assets/js/jqueryui.css'); //<!-- Toggles -->
	
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
									cargar_clientes();		
									$("#codigoProd").focus();
									$("#codigoProd").keypress(function(event){ 
										var keycode = (event.keyCode ? event.keyCode : event.which);
										if(keycode == "13")
										{
											carga_producto();
										}	
									});
									
									$("#btnPorcentaje").focus();												
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
								
								function carga_producto()
								{
									$("#carga_descripcion").html("");
									$("#carga_precio").html("");
									var cantidadProd = $("#cantidadProd").val();
									var codigoProd = $("#codigoProd").val();
									var var_tipCli = $("#idTipoCli").val();
									if (codigoProd!=""  && cantidadProd!="" && var_tipCli!="")
									{
										var request =$.ajax
													({
														type: "POST",
														url: "../seccion/ajax/carga_precio.php",
														data: {codigoProd: codigoProd, var_tipCli: var_tipCli},
														cache: false,
														dataType: "json",
														success: function(data)
														{	
															$("#precioPrdo").val(data.precio);
															$("#carga_precio").html(data.precio);	
															$("#carga_descripcion").html(data.nombre);
															
														}			
													});	
										
										request.done(function() {
											agrega_venta();
										});	
										
										request.fail(function() {
											alert("Producto no encontrado");
											$("#codigoProd").val("");
										});																		
									}								
								}
								
								function agrega_venta()
								{
									var var_codigo = $("#codigoProd").val();
									var var_cantidad = $("#cantidadProd").val();
									var var_precio = $("#precioPrdo").val();
									var var_iva = $("#factorIvaVenta").val();									
							
									if (var_codigo!="" && var_cantidad!="" && var_precio !="")
									{	
										$("#div_articulos").load("../seccion/ajax/guarda_salida.php",
																 {codigo:var_codigo,cantidad:var_cantidad,precio:var_precio,iva:var_iva},
																 function(){																	
																			var respuesta = $("#respuesta").val(); 
																			if(respuesta == "")
																			{
																				agregar_totales();
																				$("#ticket").load("../seccion/ajax/detalles_salida.php");
																				$(".btnEliminar").removeAttr("disabled");
																				$(".btnDesuento").removeAttr("disabled");
																				$(".btnPagar").removeAttr("disabled");	
																				ocultar_clientes();	
																			}
																			else
																			{
																				alert(respuesta);
																				$("#codigoProd").val("");
																				$("#cantidadProd").val("1");
																				$("#precioPrdo").val("");
																				$("#carga_precio").html("");	
																				$("#carga_descripcion").html("");																				
																				$("#codigoProd").focus();
																			}	
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
											$("#txtRecibido").val(total_totales);										
											
											var iva_totales =  total_totales - importe_totales;
											importe_totalesNew = parseFloat(importe_totales).toFixed(2);
											total_totales = parseFloat(total_totales).toFixed(2);
											iva_totales = parseFloat(iva_totales).toFixed(2); 
											
											importe_totalesNew = addCommas(importe_totalesNew);
											total_totales = addCommas(total_totales);
											iva_totales = addCommas(iva_totales);
											$("#subTxt").html(importe_totalesNew);	
											$("#ivaTxt").html(iva_totales);
											$("#totalTxt").html(total_totales);	
											
											$("#spnTotalPagar").html(total_totales);	
											$("#txtSaldoTotal2").val(total_totales);											 
											
											if(data.importeAct)
											{
												var importe_totales_actual = data.importeAct;
												importe_totales_actual = parseFloat(importe_totales_actual).toFixed(2);																								
												$("#txtSubOriginal").val(importe_totales_actual);
											}
											else
											{
												$("#txtSubOriginal").val(importe_totales);
											}
											
											if(total_totales == "0.00")
											{
												$(".btnEliminar").attr("disabled", true); 
												$(".btnDesuento").attr("disabled", true); 
												$(".btnPagar").attr("disabled", true);
												$("#txtSaldoTotal").val("0.00");
												$("#spnTotalPagar").html("0.00");
												cargar_clientes();
												cargar_categorias();
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
									$(".btnPagar").attr("disabled", true); 
									eliminar_cliente();
									cargar_clientes();
									cargar_categorias();
									$("#codigoProd").focus();
									
								}
								
								function cargar_categorias()
								{
									$("#tabArticulos").html(\'<div class="row breadcrumb" id="divListaClientes"><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div><div class="col-md-4 optionPunto"><h6></h6></div></div><div id="cargando"></div>\');
									$("#tabArticulos").load("../seccion/ajax/cargar_categorias.php");	
								}
								
								function cargar_clientes()
								{
									$("#tabClientes").load("../seccion/ajax/cargar_clientes.php");	
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
								
								function nuevo_producto()
								{
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
									$("#id_pasillo option:selected").each(function () 
									{
										pasillo=$(this).val();
									});
									$("#id_planta option:selected").each(function () 
									{
										planta=$(this).val();
									});
									$("#id_anaquel option:selected").each(function () 
									{
										anaquel=$(this).val();
									});
									
									isFloatPrecio = true;
									isFloatCosto = true;									
									
									if(!/^(\d)+((\.)(\d){1,2})?$/.test(precioGeneral))
										isFloatPrecio = false;
									if(!/^(\d)+((\.)(\d){1,2})?$/.test(costo))
										isFloatCosto = false;
									
									if(marca!="" && unidad!="" && pasillo!="" && planta!="" && anaquel!="")	
									{
										if (codigo!="" && nombre!="" && stock!="")
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
																				cargar_subCategorias(padre);
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
											alert("Capture código de barras, nombre y stock mínimo del producto.");
										}
									}
									else
									{
										alert("Capture clasificaión y localización del producto.");
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
											$("#idCli").val(idClien);
											$("#idTipoCli").val(idTipoClien);
											$("#limitCredCli").val(limitCredCli);	
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
												saldo:saldo, credito:credito
										},
									    function(){
										  	cargar_clientes();
											cargar_categorias();
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
										});								
									}
									else
									{
										alert("Capture nombre y correo del cliente");
									}
								}
								
								function cargar_producto_detalle(idProducto)
								{
									var var_iva = $("#factorIvaVenta").val();
									var var_tipCli = $("#idTipoCli").val();
									$("#div_articulos").load("../seccion/ajax/guarda_producto_salida.php",
															  {idProducto:idProducto,iva:var_iva,var_tipCli:var_tipCli},
															   function(){
																  agregar_totales();
																  $("#ticket").load("../seccion/ajax/detalles_salida.php");																	
																});																  
										
									$(".btnEliminar").removeAttr("disabled");
									$(".btnDesuento").removeAttr("disabled");
									$(".btnPagar").removeAttr("disabled");
									ocultar_clientes();
								}
								
								function aplicar_descuento_monto()
								{
									var txtSubOriginal = $("#txtSubOriginal").val();
									var txtDescMonto = $("#txtDescMonto").val();
									var newTotalDescAplica = -txtDescMonto;
									var porIva1 = txtDescMonto * 100;
									var totalIva1 = porIva1 / txtSubOriginal;
									var totalIva1Mostar = parseFloat(totalIva1).toFixed(2);
									var newTotal1 = txtSubOriginal - txtDescMonto;
									var newTotal1Mostar = parseFloat(newTotal1).toFixed(2);
									$("#txtDescNuevo").val(txtDescMonto);
									$("#txtDescAplica").val(newTotalDescAplica);
									$("#txtNuevoSubtotal").val(newTotal1Mostar);	
									$("#spnPorcentaje").html(totalIva1Mostar);
									$("#txtPorcAplica").val(totalIva1Mostar);
									if(newTotal1 < 0)
									{									
									 	$("#divValidaMonto").css("display","block");
									  	$("#spnDivDesc").html(txtSubOriginal);
										$("#btnAgregaDesc").css("display","none");
									}
									else
									{
										$("#divValidaMonto").css("display","none");
										$("#spnDivDesc").html("");
										$("#btnAgregaDesc").css("display","");										
									}
								}
								
								function aplicar_descuento_porc()
								{
									var txtSubOriginal = $("#txtSubOriginal").val();
									var txtDescPorc = $("#txtDescPorc").val();
									var newTotal1 = txtSubOriginal - txtDescMonto;	
									var porIva = txtDescPorc / 100;
									var totalIva = porIva * txtSubOriginal;	
									var newTotal = 	txtSubOriginal - totalIva;
									newTotal = parseFloat(newTotal).toFixed(2);
									totalIva = parseFloat(totalIva).toFixed(2);
									var newTotalDesc = "$-"+ totalIva;
									var newTotalDescAplica = -totalIva;
									$("#txtDescNuevo").val(newTotalDesc);
									$("#txtDescAplica").val(newTotalDescAplica);
									$("#txtNuevoSubtotal").val(newTotal);
									var txtMostar = parseFloat(txtDescPorc).toFixed(2);
									$("#spnPorcentaje").html(txtMostar);
									$("#txtPorcAplica").val(txtMostar);
									if(txtDescPorc > 100)
									{
										$("#divValidaPor").css("display","block");
										$("#btnAgregaDesc").css("display","none");
									}
									else
									{
										$("#divValidaPor").css("display","none");	
										$("#btnAgregaDesc").css("display","");											
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
															  {txtDesc:txtDesc,txtAplica:txtAplica,txtNuevo:txtNuevo,txtDesc:txtDesc,porcAplica:porcAplica,iva:var_iva},
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
								
								function revisar_cambio()
								{
									var txtSaldoTotal = $("#txtSaldoTotal").val();
									var txtRecibido = $("#txtRecibido").val();
									var cambioPrin = txtRecibido - txtSaldoTotal;
									cambio = parseFloat(cambioPrin).toFixed(2);
									cambio = addCommas(cambio);								
									if(cambioPrin < 0)
									{
										cambio = -cambioPrin;
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
										if(cambioPrin == 0)
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
								
								function cerrar_venta_simple()
								{
									var idPago = $("#txtFormaPago").val();
									var idCli = $("#idCli").val();
									var var_iva = $("#factorIvaVenta").val();
									var totalPagar = $("#txtSaldoTotal").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_venta.php",
															  {idPago:idPago,idCli:idCli,totalPagar:totalPagar},
															   function(){
																  	location.reload();																
																});		
								}
								
								function cerrar_venta_ticket()
								{
									var idPago = $("#txtFormaPago").val();
									var idCli = $("#idCli").val();
									var totalPagar = $("#txtSaldoTotal").val();
									var var_iva = $("#factorIvaVenta").val();
									
									$("#div_articulos").load("../seccion/ajax/cerrar_venta.php",
															  {idPago:idPago,idCli:idCli,totalPagar:totalPagar},
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
									if(var_limCambio = 0.00)
									{
										alert("El cliente agotó su límite de crédito");
									}
									else
									{
										if(var_cambioSin > var_limCambio)
										{
											alert("El cliente solo tiene un límite de $"+comasLimite);
										}
										else
										{
											if(var_cambioSin <= var_limCambio)
											{
												var idPago = $("#txtFormaPago").val();
												var idCli = $("#idCli").val();
												var totalPagar = $("#txtSaldoTotal").val();
												var var_iva = $("#factorIvaVenta").val();
												var var_recib =  $("#txtRecibido").val();
												$("#div_articulos").load("../seccion/ajax/cerrar_venta_saldo.php",
																		  {idPago:idPago,idCli:idCli,totalPagar:totalPagar,var_recib:var_recib},
																		   function(){
																				location.reload();																
																			});
											}
										}
									}
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
					  </script>';
	
	return $scriptSpecial;
}

?>