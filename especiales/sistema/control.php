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
							<title>.::CBIZ - Sistema::.</title>
							<meta http-equiv="X-UA-Compatible" content="IE=edge" />
							<meta name="viewport" content="width=device-width, initial-scale=1.0" />
							<meta name="description" content="" />
							<meta name="author" content="" />
						
							<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
							<link href="assets/css/styles.min.css" rel="stylesheet" type="text/css" />
							<script src="script/sistema/globales.js"></script>
							<script src="script/sistema/md5.js"></script>
							<script src="script/sistema/sistema.js"></script>
							<!-- Scripts -->
							'.scripttag('assets/js/jquery-1.10.2.min.js')
							 .scripttag('assets/js/jqueryui-1.10.3.min.js').'
						
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
						<body class="focusedform">
						
						<div class="verticalcenter">
							<img src="imagenes/empresa/'.$empresa["logo"].'" width="236px" alt="Logo" class="brand imgPng" />
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

function muestra_sistema()
{
	//CHECAMOS SI HAY PRODUCTOS SIN STOCK
	liberar_bd();
	$selectProductosSinStock = 'CALL sp_sistema_select_productos_sinStock();';
	$productosSinStock = consulta($selectProductosSinStock);
	$ctaProdSinSTock = cuenta_registros($productosSinStock);
	
	//CARGAMOS CONFIGURACIONES INICALES
	liberar_bd();
	$selectConfigSistema = 'CALL sp_sistema_select_datos_configuracion();';
	$configSistema = consulta($selectConfigSistema);	
	$confSis = siguiente_registro($configSistema);
	
	
	$msj = '';	
	if(trim($_POST['modulo'])=="-1")
	{
		cerrarSesion();
		$sistema = '<meta http-equiv="refresh" content="0;url=http://www.cbiz.mx/ferreobra/sistema/">';		
	}
	else
	{
		if(isset($_POST['modulo']) && trim($_POST['modulo'])!="")
		{
			if($_POST['modulo'] != "")
			{
				$_SESSION['mod'] = $_POST['modulo'];	
			}			
		}
		if($_POST['accion'] == "")
		{
			$_POST['accion'] = "Inicio";
		}	
		if (isset($_COOKIE["admin_leftbar_collapse"])) 
			$classBody.= $_COOKIE['admin_leftbar_collapse'] . " "; 
        if (isset($_COOKIE["admin_rightbar_show"])) 
			$classBody.= $_COOKIE['admin_rightbar_show'];
        if (isset($_COOKIE["fixed-header"])) 
			$classBody.= ' static-header';
        
		$sistema = '	<!DOCTYPE html>
							<html lang="en">
							<head>
								<meta charset="utf-8" />
								<title>.::CBIZ - Sistema::.</title>
								<meta name="viewport" content="width=device-width, initial-scale=1.0" />
								<meta name="description" content="" />
								<meta name="author" content="" />							
								<!-- <link href="assets/less/styles.less" rel="stylesheet/less" media="all">  -->
   								<link rel="stylesheet" href="assets/css/styles.css?=121">
								<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css">
								';
								if (isset($_COOKIE["theme"])) 
								{
									$sistema .= "<link href='assets/demo/variations/". $_COOKIE["theme"] ."' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
								} 
								else 
								{  
									$sistema .=" <link href='assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
								}
								if (isset($_COOKIE["headerstyle"])) 
								{
									$sistema .="<link href='assets/demo/variations/". $_COOKIE["headerstyle"] ."' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
								} 
								else 
								{ 								
									$sistema .="<link href='assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
								} 								
					
					$sistema .='
								<!--[if lt IE 9]>
									<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
									<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
									<script type="text/javascript" src="assets/plugins/charts-flot/excanvas.min.js"></script>
									<link rel="stylesheet" href="assets/css/ie8.css">
								<![endif]-->
								'.heade($_SESSION['mod'], $_POST['accion']).'								
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								<script type="text/javascript" src="assets/js/less.js"></script>
								</head>
								<!--
								<script src="//ajax.googleapi.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
								<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
								<script>!window.jQuery && document.write(unescape(\'%3Cscript src="assets/js/jquery-1.10.2.min.js"%3E%3C/script%3E\'))</script>
								<script type="text/javascript">!window.jQuery.ui && document.write(unescape(\'%3Cscript src="assets/js/jqueryui-1.10.3.min.js\'))</script>
								-->
								'.	scripttag('assets/js/jquery-1.10.2.min.js').
									scripttag('assets/js/jqueryui-1.10.3.min.js').
									scripttag('assets/js/bootstrap.min.js').
									scripttag('assets/js/enquire.js').
									scripttag('assets/js/jquery.cookie.js').
									scripttag('assets/js/jquery.nicescroll.min.js').
									scripttag('assets/plugins/codeprettifier/prettify.js').
									scripttag('assets/plugins/easypiechart/jquery.easypiechart.min.js').
									scripttag("assets/plugins/sparklines/jquery.sparklines.min.js").
									scripttag("assets/plugins/form-toggle/toggle.min.js")
								.'
								<body class="'.$classBody.'">	
								<form name="frmSistema" id="frmSistema" method="post" action="./" enctype="multipart/form-data">
									<span name="campos" id="campos"></span>
									<input type="hidden" id="menuSelect" name="menuSelect" value="'.$_POST['menuSelect'].'" />	
									<input type="hidden" id="modulo" name="modulo" value="'.$_POST['modulo'].'" />						
									<div id="headerbar">
										<div class="container">
											<div class="row">
											</div>
										</div>
									</div>								
									<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
										<a id="leftmenu-trigger" class="pull-left" data-toggle="tooltip" data-placement="bottom" title="Toggle Left Sidebar"></a>
										
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
															<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'-1\'; frmSistema.submit();" class="text-right">Cerrar sesi&oacute;n</a></li>
														</ul>
													</li>
												</ul>
											</li>
											<li class="dropdown">
												<a href="javascript:;" class="hasnotifications dropdown-toggle" data-toggle="dropdown" title="Productos agotados">
													<i class="fa fa-info-circle"></i>
													<span class="badge">'.$ctaProdSinSTock.'</span>
												</a>
											</li>																						
										</ul>
									</header>
								
									<div id="page-container">
										<!-- BEGIN SIDEBAR -->
										<nav id="page-leftbar" role="navigation">
											<!-- BEGIN SIDEBAR MENU -->
											<ul class="acc-menu" id="sidebar">
												<li>
													<a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.menuSelect.value =\'\'; frmSistema.accion.value =\'\';frmSistema.submit();">
														<i class="fa fa-home"></i><span>Inicio</span>
													</a>
												</li>
												'.muestra_menu(0,$_SESSION['idPerfil']).'
											</ul>
											<!-- END SIDEBAR MENU -->
										</nav>								
										<!-- BEGIN RIGHTBAR -->
										<div id="page-rightbar">								
										</div>
										<!-- END RIGHTBAR -->
										<div id="page-content">
											<div id="wrap">													
												'.muestra_modulo($_SESSION['mod']).'
											</div> <!--wrap -->
										</div> <!-- page-content -->								
										<footer role="contentinfo">
											<div class="clearfix">
												<ul class="list-unstyled list-inline">
													<li>ELEVEN TI &copy; 2013</li>
													<!--li class="pull-right"><a href="javascript:;" id="back-to-top">Top <i class="icon-arrow-up"></i></a></li-->
													<button class="pull-right btn btn-inverse btn-xs" id="back-to-top" style="margin-top: -1px; text-transform: uppercase;">
														<i class="fa fa-arrow-up"></i>
													</button>
												</ul>
											</div>
										</footer>
								</div> <!-- page-container -->	
								'.footer($_SESSION['mod'], $_POST['accion']).'
								<script>						
									window.onload = function () 
									{
										$(".panel-danger").css("background-color","'.$confSis["colorCont"].' !important");
										$(".panel-danger .panel-body").css("border-top","'.$confSis["colorCont"].' !important");
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
									
									$("#idProductoAsignacion").change(function()
									{
										var id=$(this).val();
										var dataString = \'id=\'+ id; 
										
										$.ajax
										({
											type: "POST",
											url: "../seccion/ajax/productosAsignacion_ajax.php",
											data: dataString,
											cache: false,
											dataType: "json",
											success: function(data)
											{	
												$("#stock").val(data.stock);
												$("#precio").val(data.precio);
												/*obtener_importe("importe",document.frmSistema.cantidad,document.frmSistema.precio);*/								
											}			
										});								
									});
									$("#idPago").change(function()
									{
										var valorCambiado =$(this).val();
										if((valorCambiado == "2"))
										{
										   $("#divClientesVenta").css("display","block");
										   $("#abonoVenta").css("display","block");
										   $("#cantidadVenta").css("display","none");
										}
										else if(valorCambiado == "1")
										{
											$("#divClientesVenta").css("display","none");
											$("#abonoVenta").css("display","none");
											$("#cantidadVenta").css("display","block");
										}
									});
								</script>
								'.scripSpecial($_SESSION['mod'], $_POST['accion']).'								
								<!-- Scripts Sistema-->						
								<script src="script/sistema/globales.js"></script>
								<script src="script/sistema/md5.js"></script>
								<script src="script/sistema/sistema.js"></script>					
								<!-- Template functions -->								
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
	
function muestra_menu($padre,$perfil=0,$nivel=0)
{
	liberar_bd();
	$archivoSeleccionado = ' class="current navigable-current" ';
	if($perfil!=0)
	{ 
		$sqlMenu = 'SELECT * FROM _modulos m
						INNER JOIN _permisos p ON m.id_modulo = p.id_modulo
					WHERE id_padre = ' . $padre . ' 
						AND id_perfil = '.$perfil.'
					ORDER BY m.orden_modulo ASC';
	}
	else
	{
		$sqlMenu = 'SELECT * FROM _modulos 
					WHERE id_padre = '.$padre.' 
					ORDER BY  orden_modulo ASC';
	}
	
	$resMenu = consulta($sqlMenu);
	
	if (cuenta_registros($resMenu) != 0)
	{	
		while ($fila = siguiente_registro($resMenu))
		{
			$padreModulo = "menu_".$fila['id_padre']."_".$fila['id_modulo'];
			$sqlTipo = 'SELECT * FROM _modulos m
						INNER JOIN _permisos p ON m.id_modulo = p.id_modulo
					WHERE id_padre = ' .  $fila['id_modulo'] . ' AND id_perfil = '. $perfil .'
					ORDER BY  m.orden_modulo ASC';
			$resTipo = consulta($sqlTipo);
			$numResTipo = cuenta_registros($resTipo);
			
			if ($numResTipo==0 )
			{
				if($fila["id_modulo"] == 400)
				{
					if($fila["id_modulo"] == 400 && $nivel != 0)
						$menu .= '		</ul>
									</li>';							
					
						$menu .= '	<li id="menu_'.$fila['id_padre'].'_'.$fila['id_modulo'].'">
										<a class="open" href="../punto" target="_blank">
											<i class="'.$fila['icono_modulo'].'"></i>
											<span>'.utf8_convertir($fila['nombre_modulo']).'</span>
										</a>
									</li>';						
				}
				else
				{		
					$menu .= '	<li>
									<a class="open" href="javascript:navegar_modulo('.$fila['id_modulo'].',this.id);;" id="menu_'.$fila['id_padre'].'_'.$fila['id_modulo'].'">
										<span>'.utf8_convertir($fila['nombre_modulo']).'</span>
									</a>';
				}
			}
			else
			{	
				if($fila["id_modulo"] == 500)
					$menu .= '';
				else
				{
					if($padre==0 && $nivel>0)
						$menu .= '</ul>
								</li>';	
				}
					
				$menu .= '	<li id="menu_'.$fila['id_padre'].'_'.$fila['id_modulo'].'">
							 	<a href="javascript:;">
									<i class="'.$fila['icono_modulo'].'"></i>
									<span>'.utf8_convertir($fila['nombre_modulo']).'</span> 
								</a>
								<ul class="acc-menu">';
			}
	
			$nivel++;
			$menu .=   muestra_menu($fila['id_modulo'],$perfil,$nivel);
			
		}
	} 											
	return $menu;
}

function muestra_modulo($id)
{
	liberar_bd();
	$sql="SELECT archivo_modulo as archivo FROM _modulos WHERE id_modulo = " . $id;
	$res = consulta($sql);
	$ctaRes = cuenta_registros($res);
	$modulo='<input type="hidden" name="accion" />';
	if($ctaRes != 0)
	{
		$fila = siguiente_registro($res);		
		include_once($fila['archivo']);
	}
	else
	{
		include_once('./modulos/dashboard/inicio.php');
	}
	
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
	
	if($pageMode == "Nuevo" || $pageMode == "Editar" || $pageMode == "Nueva" || $pageMode == "Guardar" || $pageMode == "GuardarEdit" || $pageMode == 'Agregar' || $pageMode == 'GuardarAgregar' || $pageMode == 'EditarAgregar' || $pageMode == 'EliminarAgregar' || $pageMode == "GuardarSeg" || $pageMode == "Pagar" || $pageMode == 'AgregarDoc')
	{
		$scriptPagina .=scripttag("assets/plugins/form-multiselect/js/jquery.multi-select.min.js").
						scripttag("assets/plugins/quicksearch/jquery.quicksearch.min.js").
						scripttag("assets/plugins/form-typeahead/typeahead.min.js").
						scripttag("assets/plugins/form-select2/select2.min.js").
						scripttag("assets/plugins/form-autosize/jquery.autosize-min.js").
						scripttag("assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js").
						scripttag("assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.min.js").
						scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js").
						scripttag("assets/plugins/form-daterangepicker/moment.min.js").
						scripttag("assets/plugins/form-fseditor/jquery.fseditor-min.js").
						scripttag("assets/demo/demo-formcomponents.js");
	} 
	else
	{
		if($pageModu == 3 || $pageModu == 203 || $pageModu == 503 || $pageModu == 504 || $pageModu == 1002 || $pageModu == 1003 || $pageModu == 1004 || $pageModu == 202 || $pageModu == 1006)
		{
			$scriptPagina .=scripttag("assets/plugins/form-multiselect/js/jquery.multi-select.min.js").
							scripttag("assets/plugins/quicksearch/jquery.quicksearch.min.js").
							scripttag("assets/plugins/form-typeahead/typeahead.min.js").
							scripttag("assets/plugins/form-select2/select2.min.js").
							scripttag("assets/plugins/form-autosize/jquery.autosize-min.js").
							scripttag("assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js").
							scripttag("assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.min.js").
							scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js").
							scripttag("assets/plugins/form-daterangepicker/moment.min.js").
							scripttag("assets/plugins/form-fseditor/jquery.fseditor-min.js").
							scripttag("assets/demo/demo-formcomponents.js");
		}	
		elseif($pageModu == 0 && $pageMode = 'Inicio')
		{
			$scriptPagina .=scripttag("assets/plugins/fullcalendar/fullcalendar.min.js").
							scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js").
							scripttag("assets/plugins/form-daterangepicker/moment.min.js").
							scripttag("assets/plugins/charts-flot/jquery.flot.min.js").
							scripttag("assets/plugins/charts-flot/jquery.flot.resize.min.js").
							scripttag("assets/plugins/charts-flot/jquery.flot.orderBars.min.js").
							scripttag("assets/plugins/pulsate/jQuery.pulsate.min.js").
							scripttag("assets/demo/demo-index.js");
		}	
	}
	if($pageModu == 40)
	{
		$scriptPagina .=scripttag("assets/plugins/form-jasnyupload/fileinput.min.js");
	}
	if($pageModu == 3 || $pageModu == 201 || $pageModu == 204)
	{
		$scriptPagina .=scripttag("assets/plugins/form-jasnyupload/fileinput.min.js").
						scripttag("assets/plugins/jcrop/js/jquery.Jcrop.min.js").
    					scripttag("assets/demo/demo-imagecrop.js");
	}	
	
		$scriptPagina .=scripttag("assets/plugins/datatables/jquery.dataTables.min.js").
						scripttag("assets/plugins/datatables/dataTables.bootstrap.js").
						scripttag("assets/demo/demo-datatables.js").
						scripttag('assets/js/placeholdr.js').
						scripttag('assets/js/application.js').
						scripttag('assets/demo/demo.js');
						
	return $scriptPagina;
}

function heade($pageModu, $pageMode)
{
	$cssPagina = '';
	if($pageMode == "Nuevo" || $pageMode == "Editar" || $pageMode == "Nueva" || $pageMode == "Guardar" || $pageMode == "GuardarEdit" || $pageMode == 'Agregar' || $pageMode == 'GuardarAgregar' || $pageMode == 'EditarAgregar' || $pageMode == 'EliminarAgregar' || $pageMode == "GuardarSeg" || $pageMode == "Pagar" || $pageMode == 'AgregarDoc')
	{
		$cssPagina.= 	linktag('assets/plugins/form-select2/select2.css').
						linktag('assets/plugins/form-multiselect/css/multi-select.css').
						linktag('assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css').
						linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css').
						linktag('assets/plugins/form-fseditor/fseditor.css').
						linktag('assets/js/jqueryui.css'); // <!-- jquery ui -->
	}
	else
	{
		if($pageModu == 3  || $pageModu == 203 || $pageModu == 503 || $pageModu == 504 || $pageModu == 1002 || $pageModu == 1003 || $pageModu == 1004 || $pageModu == 202 || $pageModu == 1006)
		{
			$cssPagina.= 	linktag('assets/plugins/form-select2/select2.css').
							linktag('assets/plugins/form-multiselect/css/multi-select.css').
							linktag('assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css').
							linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css').
							linktag('assets/plugins/form-fseditor/fseditor.css').
							linktag('assets/js/jqueryui.css'); // <!-- jquery ui -->
		}	
		elseif($pageModu == 0 && $pageMode = 'Inicio')
		{
			$cssPagina.=	linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css').
							linktag('assets/plugins/fullcalendar/fullcalendar.css').
							linktag('assets/plugins/form-markdown/css/bootstrap-markdown.min.css');
		}
	}
	
	if($pageModu == 3 || $pageModu == 201 || $pageModu == 204)
	{
		$cssPagina.= linktag('assets/plugins/jcrop/css/jquery.Jcrop.min.css');
	}
	
	$cssPagina.= 	linktag('assets/plugins/datatables/dataTables.css').
					linktag('assets/plugins/codeprettifier/prettify.css').
					linktag('assets/plugins/form-toggle/toggles.css'); //<!-- Toggles -->
	
	return $cssPagina;
}

function scripSpecial($pageModu, $pageMode)
{
	if($pageModu == '2')
	{
		$scriptSpecial = '	<script>
								function guardaConfig()
								{
									var var_ancho = $("#anchoConf").val();
									var var_moneda = $("#monedaConf").val();
									var var_iva = $("#ivaConf").val();
									if($("#chkIva").is(":checked")) 
										var_inciva = 1;
									else  
										var_inciva = 0;
									
									$("#resultado").load("../seccion/ajax/guarda_configuracion.php",{ancho:var_ancho,moneda:var_moneda,iva:var_iva,incIva:var_inciva});									
									alert("Se han guardado la configuración del sistema");
								}
							
							</script>'; 
	}
	
	if($pageModu == '202')
	{
		$scriptSpecial = '	<script>
								$(document).ready(function()
								{			
									$("#codigoUpdate").focus();																				
								});
								
								/*function guarda_existencia()
								{
									var codigoUpdate = $("#codigoUpdate").val();
									var codigoUpdate = $("#codigoUpdate").val();
									var existenciaUpdate = $("#existenciaUpdate").val();
									var observacionUpdate = $("#observacionUpdate").val();
									
									if(codigoUpdate!="" && existenciaUpdate!="" && conceptoUpdate!="")
									{
										if($("#chkCodigo").is(":checked")) 
										{
											$("#resultado").load("../seccion/ajax/actualiza_existencia.php",{codigoUpdate:codigoUpdate,existenciaUpdate:existenciaUpdate,observacionUpdate:observacionUpdate},
											function(){
												$("#prodTrue").val("1"); 																						
											});																		
										}
										else
										{
											$("#idProducto option:selected").each(function () 
											{
												idProducto=$(this).val();
											});
											$("#resultado").load("../seccion/ajax/actualiza_existencia.php",{idProducto:idProducto,existenciaUpdate:existenciaUpdate,observacionUpdate:observacionUpdate},
											function(){
												$("#prodTrue").val("1"); 																						
											});			
										}
										
										alert("Se ha actualizado la existencia del producto");											
									}
									else
									{
										alert("Capture todos los campos");
									}
									
									$("#carga_existencia").html(\'<input type="text" class="form-control" id="existenciaUpdate" name="existenciaUpdate" maxlength="100"/>\');
									$("#codigoUpdate").val("");
									$("#existenciaUpdate").val("");
									$("#codigoUpdate").focus();
								}*/
								
								$("#codigoUpdate").keypress(function(event){ 
									var keycode = (event.keyCode ? event.keyCode : event.which);
									if(keycode == "13")
									{
										var codigoUpdate = $("#codigoUpdate").val();
										$("#carga_existencia").load("../seccion/ajax/revisa_update_existencia.php",{codigoUpdate:codigoUpdate},
									    function(){																																	
										});	
									}
								});	
								
								$("#idProducto").change(function(event){ 
									$("#idProducto option:selected").each(function () 
									{
										idProducto=$(this).val();
										$("#carga_existencia").load("../seccion/ajax/revisa_update_existencia.php",{idProducto:idProducto},
									    function(){																								
										});	
									});
								});			
								
								$("#chkCodigo").click(function () 
							  	{
								  	$(".divCodigo").toggle();
									$(".divProductos").toggle();									
									if($("#chkCodigo").is(":checked")) 
									{ 
										$("#prodTrue").val("0"); 
										$("#codigoUpdate").val("");										 
										$("#existenciaUpdate").val("");
										$("#observacionUpdate").val("");
										$("#conceptoUpdate").remove(); 
										$("#prodTrue").remove(); 
										$(".select2-chosen").val("");	
										$(".select2-chosen").html("SELECCIONE PRODUCTO");
										$(".selectSerch option[value=\'0\']").prop("selected", "selected");
									} 
									else
									{
										$("#prodTrue").val("0"); 
										$("#conceptoUpdate").remove(); 
										$("#prodTrue").remove(); 
										$("#observacionUpdate").val("");
										$("#existenciaUpdate").val("");
									}									
							  	});			
							</script>'; 
	}
	
	if($pageModu == '203')
	{
		$scriptSpecial = '	<script>
								$(document).ready(function()
								{			
									$("#codigoInsert").focus();	
									$("#codigoInsert").keypress(function(event){ 
										var keycode = (event.keyCode ? event.keyCode : event.which);
										if(keycode == "13")
										{
											var codigoInsert = $("#codigoInsert").val();
											$("#descripcion_entrada").load("../seccion/ajax/revisa_codigo_entrada.php",{codigoInsert:codigoInsert});
										}	
									});											
								});
								
								function registra_entrada()
								{
									var idProducto = $("#idProducto").val();
									var codigoInsert = $("#codigoInsert").val();
									var cantidadInsert = $("#cantidadInsert").val();
									var conceptoInsert = $("#conceptoInsert").val();
									var costoInsert = $("#costoInsert").val();
									
									if(codigoInsert!="" && cantidadInsert!="" && conceptoInsert!="" && costoInsert!="")
									{
										$("#resultado").load("../seccion/ajax/guarda_entrada.php",{idProducto:idProducto,codigoInsert:codigoInsert,cantidadInsert:cantidadInsert,conceptoInsert:conceptoInsert,costoInsert:costoInsert});
										alert("Se ha registrado la entrada del artículo: "+codigoInsert);
									}
									else
									{
										alert("Por favor capture todos los campos");
									}
									
									$("#idProducto").val("");
									$("#codigoInsert").val("");
									$("#cantidadInsert").val("");
									$("#conceptoInsert").val("");
									$("#conceptoUpdate").val("");
									$("#costoInsert").val("");
									$("#codigoInsert").focus();
								}	
								
								$("#idProducto").change(function(event){ 
									$("#idProducto option:selected").each(function () 
									{
										idProducto=$(this).val();
										$("#carga_existencia").load("../seccion/ajax/revisa_update_existencia.php",{idProducto:idProducto},
									    function(){																								
										});	
									});
								});			
								
								$("#chkCodigo").click(function () 
							  	{
								  	$(".divCodigo").toggle();
									$(".divProductos").toggle();									
									if($("#chkCodigo").is(":checked")) 
									{ 
										$("#prodTrue").val("0"); 
										$("#codigoUpdate").val("");										 
										$("#existenciaUpdate").val("");
										$("#observacionUpdate").val("");
										$("#conceptoUpdate").remove(); 
										$("#prodTrue").remove(); 
										$(".select2-chosen").val("");	
										$(".select2-chosen").html("SELECCIONE PRODUCTO");
										$(".selectSerch option[value=\'0\']").prop("selected", "selected");
									} 
									else
									{
										$("#prodTrue").val("0"); 
										$("#conceptoUpdate").remove(); 
										$("#prodTrue").remove(); 
										$("#observacionUpdate").val("");
										$("#existenciaUpdate").val("");
									}									
							  	});															
							</script>'; 
	}
	
	if($pageModu == '1006')
	{
		$scriptSpecial = '	<script>
								function cargaActualizados()
								{
									$(".divFiltrador").css("display","block");
									var fecha = $("#fechaReporte2").val();
									if(fecha!="")
									{
										$("#tablaNoti").load("../seccion/ajax/cargaActualizados.php",{fecha:fecha},
															   function(){
																  	cargaListaNoti();																
																});	
									}
									else
										alert("Por favor seleccione rango de fecha");
								}	
								
								function cargaListaNoti()
								{
									var fecha = $("#fechaReporte2").val();
									$("#divNoti").load("../seccion/ajax/cargaListaNoti.php",{fecha:fecha});
								}
								
								function cargaAgotados()
								{
									$(".divFiltrador").css("display","none");
									$("#tablaNoti").load("../seccion/ajax/cargaAgotados.php",{});									
								}
								
								function eliminarActualizacion(idActualiza)
								{
									$("#alertNoti").load("../seccion/ajax/eliminarNotiActualiza.php",
															  {idActualiza:idActualiza},
															   function(){
																  	alert("Notificación eliminada");
																	cargaListaNoti();
																	cargaActualizados();																
																});	
								}
							</script>'; 
	}
	if($pageModu == 507)
	{
		$scriptSpecial.= '	<script>
								$("#chkCaja").click(function () 
							  	{
									if($("#chkCaja").is(":checked")) 
										$("#divTiemposConexion").css("display","block");
									else
									{
										$("#divTiemposConexion").css("display","none");
										$("#timepicker1").val("");
										$("#timepicker3").val("");
									}
								});
							</script>';
	}
	
	$scriptSpecial .= '<script>		
						  $("#id_estado").change(function () 
						  {
							  $("#formCiudad").css("display","none");
							  $("#id_estado option:selected").each(function () 
							  {
								  elegido=$(this).val();
								  $.post("ajax/ciudadesAjax.php", { elegido: elegido }, function(data){
								  $("#id_ciudad").html(data);
								  $("#formCiudad").css("display","block");								  
								  });			
							  });
						  });
					  </script>';
	
	return $scriptSpecial;
}

?>