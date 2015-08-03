<?php

function muestra_login($msjLogin) {
    switch ($msjLogin) {
        case 1:
            $alertLogin = '	<div class="alert alert-dismissable alert-success">
								<strong>Bienvenido</strong>
							</div>';
            break;
        case 2:
            $alertLogin = '	<div class="alert alert-dismissable alert-warning">
								<strong>El usuario y/o contrase&ntilde;a son incorrectos</strong>
							</div>';
            break;
        case 3:
            $alertLogin = '	<div class="alert alert-dismissable alert-danger">
								<strong>Se ha iniciado sesi&oacute;n en otro dispositivo</strong>
							</div>';
            break;
        default:
            $alertLogin = '	<div class="alert alert-dismissable alert-success">
								<strong>Bienvenido</strong>
							</div>';
            break;
    }

    //DATOS DE LA EMPRESA
    liberar_bd();
    $selecDatosEmpresa = 'CALL sp_sistema_select_datos_empresa(' . $_SESSION['idAgenteActual'] . ');';
    $datosEmpresa = consulta($selecDatosEmpresa);
    $empresa = siguiente_registro($datosEmpresa);

    //CARGAMOS CONFIGURACIONES INICALES
    liberar_bd();
    $selectConfigSistema = 'CALL sp_sistema_select_datos_configuracion(' . $_SESSION['idAgenteActual'] . ');';
    $configSistema = consulta($selectConfigSistema);
    $confSis = siguiente_registro($configSistema);

    $formulario = '	<!DOCTYPE html>
						<html lang="en">
						<head>
							<meta charset="utf-8" />
							<title>.::Sistema - Seguros::.</title>
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
							' . scripttag('assets/js/jquery-1.10.2.min.js')
            . scripttag('assets/js/jqueryui-1.10.3.min.js') . '
						<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
						<body class="focusedform">
						
						<div class="verticalcenter">
							<img src="imagenes/empresa/20150508172228.png" width="236px" alt="Logo" class="brand imgPng" />
							<div class="panel panel-primary">
								<div class="panel-body">
									<style>
										.btn-block {margin-bottom: 10px;}
									</style>
									' . $alertLogin . '				
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
								$(".panel-body").css("border-top","' . $confSis["colorCont"] . ' !important");
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
						</html>';

    return $formulario;
}

function muestra_sistema() {
    //COMPROBAMOS RECORDATORIOS
    liberar_bd();
    $selectRecordatorios = 'CALL sp_sistema_select_recordatorios();';
    $recordatorios = consulta($selectRecordatorios);
    $records = siguiente_registro($recordatorios);
    foreach ($recordatorios as $record) {
        if (new DateTime() >= new DateTime($record["fecha"])) {
            
        }
    }

    //CARGAMOS CONFIGURACIONES INICALES
    liberar_bd();
    $selectConfigSistema = 'CALL sp_sistema_select_datos_configuracion(' . $_SESSION['idAgenteActual'] . ');';
    $configSistema = consulta($selectConfigSistema);
    $confSis = siguiente_registro($configSistema);

    //PERMISO PARA AGENDA DE CONTACTOS
    liberar_bd();
    $selectPermisoAgenda = 'CALL sp_sistema_select_permiso_modulo(' . $_SESSION["idPerfil"] . ', 3012);';
    $permisoAgenda = consulta($selectPermisoAgenda);
    $ctaAgenda = cuenta_registros($permisoAgenda);
    if ($ctaAgenda != 0) {
        $btnAgenda = '	<li>
							<a class="hasnotifications" title="Agenda de contactos" href="javascript:;" onclick="navegar_modulo(3012);">
								<i class="fa fa-book"></i>
							</a>
						</li>';
    } else {
        $btnAgenda = '';
    }

    //PERMISO PARA CALENDARIO
    liberar_bd();
    $selectPermisoCalendario = 'CALL sp_sistema_select_permiso_modulo(' . $_SESSION["idPerfil"] . ', 6000);';
    $permisoCalendario = consulta($selectPermisoCalendario);
    $ctaCalendario = cuenta_registros($permisoCalendario);
    if ($ctaCalendario != 0) {
        $btnCalendario = '	<li class="dropdown">
							  <a href="javascript:;" class="hasnotifications dropdown-toggle" data-toggle="dropdown" title="Calendario" onclick="navegar_modulo(6000);">
								  <i class="fa fa-calendar"></i>
							  </a>
							</li>';
    } else {
        $btnCalendario = '';
    }

    $msj = '';
    if (trim($_POST['modulo']) == "-1") {
        cerrarSesion();
        $sistema = '<meta http-equiv="refresh" content="0;url=http://ruizquezada.com/sistema">';
    } else {
        if (trim($_POST['modulo']) != "") {
            $_SESSION['mod'] = $_POST['modulo'];
        }

        if ($_POST['accion'] == "") {
            $_POST['accion'] = "Inicio";
        }

        if (isset($_COOKIE["admin_leftbar_collapse"])) {
            $classBody.= $_COOKIE['admin_leftbar_collapse'] . " ";
        }
        if (isset($_COOKIE["admin_rightbar_show"])) {
            $classBody.= $_COOKIE['admin_rightbar_show'];
        }
        if (isset($_COOKIE["fixed-header"])) {
            $classBody.= ' static-header';
        }

        $sistema = '	<!DOCTYPE html>
							<html lang="en">
							<head>
								<meta charset="utf-8" />
								<title>.::Sistema - Seguros::.</title>
								<meta name="viewport" content="width=device-width, initial-scale=1.0" />
								<meta name="description" content="" />
								<meta name="author" content="" />							
								<!-- <link href="assets/less/styles.less" rel="stylesheet/less" media="all">  -->
   								<link rel="stylesheet" href="assets/css/styles.css?=121">
								<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css">
								';
        if (isset($_COOKIE["theme"])) {
            $sistema .= "<link href='assets/demo/variations/" . $_COOKIE["theme"] . "' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
        } else {
            $sistema .=" <link href='assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='styleswitcher'>";
        }
        if (isset($_COOKIE["headerstyle"])) {
            $sistema .="<link href='assets/demo/variations/" . $_COOKIE["headerstyle"] . "' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
        } else {
            $sistema .="<link href='assets/demo/variations/default.css' rel='stylesheet' type='text/css' media='all' id='headerswitcher'>";
        }

        $sistema .='
								<!--[if lt IE 9]>
									<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
									<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
									<script type="text/javascript" src="assets/plugins/charts-flot/excanvas.min.js"></script>
									<link rel="stylesheet" href="assets/css/ie8.css">
								<![endif]-->
								' . heade($_SESSION['mod'], $_POST['accion']) . '								
								<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
								<script type="text/javascript" src="assets/js/less.js"></script>
								</head>
								<!--
								<script src="//ajax.googleapi.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
								<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
								<script>!window.jQuery && document.write(unescape(\'%3Cscript src="assets/js/jquery-1.10.2.min.js"%3E%3C/script%3E\'))</script>
								<script type="text/javascript">!window.jQuery.ui && document.write(unescape(\'%3Cscript src="assets/js/jqueryui-1.10.3.min.js\'))</script>
								-->
								' . scripttag('assets/js/jquery-1.10.2.min.js') .
                scripttag('assets/js/jqueryui-1.10.3.min.js') .
                scripttag('assets/js/bootstrap.min.js') .
                scripttag('assets/js/enquire.js') .
                scripttag('assets/js/jquery.cookie.js') .
                scripttag('assets/js/jquery.nicescroll.min.js') .
                scripttag('assets/plugins/codeprettifier/prettify.js') .
                scripttag('assets/plugins/easypiechart/jquery.easypiechart.min.js') .
                scripttag("assets/plugins/sparklines/jquery.sparklines.min.js") .
                scripttag("assets/plugins/form-toggle/toggle.min.js").
                scripttag("assets/js/jquery.dataTables.columnFilter.js")
                . '
								<body class="' . $classBody . '">	
								<form name="frmSistema" id="frmSistema" method="post" action="./" enctype="multipart/form-data">
									<span name="campos" id="campos"></span>									
									<input type="hidden" id="modulo" name="modulo" value="' . $_POST['modulo'] . '" />	
									<input type="hidden" name="accion" />						
									<div id="headerbar">
										<div class="container">
											<div class="row">
											</div>
										</div>
									</div>								
									<header class="navbar navbar-inverse navbar-fixed-top" role="banner">
										<a id="leftmenu-trigger" class="pull-left" data-toggle="tooltip" data-placement="bottom" title="Toggle Left Sidebar"></a>
										
										<div class="navbar-header pull-left">
											<a class="navbar-brand" href="javascript:;">CBIZ SEGUROS</a>
										</div>
								
										<ul class="nav navbar-nav pull-right toolbar">											
											<li class="dropdown">
												<a href="#" class="dropdown-toggle username" data-toggle="dropdown">
													<span class="hidden-xs">
														' . $_SESSION["usuario"] . '
														<i class="fa fa-angle-down icon-scale"></i>
													</span>
													<img src="" alt="" />
												</a>
												<ul class="dropdown-menu userinfo arrow">
													<li class="username">
														<a href="#">
															<div class="pull-left"><img class="userimg" src="" alt="" /></div>
															<div class="pull-right"><h5>' . recortar_texto($_SESSION["usuario"], 20) . '</h5><small>Conectado como <span>' . $_SESSION["login"] . '</span></small></div>
														</a>
													</li>
													<li class="userlinks">
														<ul class="dropdown-menu">
															<li><a href="javascript:;" onclick="navegar_modulo(1008);">Editar perfil<i class="pull-right icon-pencil"></i></a></li>
															<li class="divider"></li>
															<li><a href="javascript:;" onclick="frmSistema.modulo.value =\'-1\'; frmSistema.submit();" class="text-right">Cerrar sesi&oacute;n</a></li>
														</ul>
													</li>
												</ul>
											</li>
											' . $btnCalendario . '
											' . $btnAgenda . '																													
										</ul>
									</header>
								
									<div id="page-container">
										<!-- BEGIN SIDEBAR -->
										<nav id="page-leftbar" role="navigation">
											<!-- BEGIN SIDEBAR MENU -->
											<ul class="acc-menu" id="sidebar">
												<li>
													<a href="javascript:;" onclick="frmSistema.modulo.value =\'0\'; frmSistema.accion.value =\'\';frmSistema.submit();">
														<i class="fa fa-home"></i><span>Inicio</span>
													</a>
												</li>
												' . muestra_menu($_SESSION['idPerfil']) . '
											</ul>
											<!-- END SIDEBAR MENU -->
										</nav>								
										<!-- BEGIN RIGHTBAR -->
										<div id="page-rightbar">								
										</div>
										<!-- END RIGHTBAR -->
										<div id="page-content">
											<div id="wrap">													
												' . muestra_modulo($_SESSION['mod']) . '
											</div> <!--wrap -->
										</div> <!-- page-content -->								
										<footer role="contentinfo">
											<div class="clearfix">
												<ul class="list-unstyled list-inline">
													<li>ELEVEN TI &copy; ' . date("Y") . '</li>
													<!--li class="pull-right"><a href="javascript:;" id="back-to-top">Top <i class="icon-arrow-up"></i></a></li-->
													<button class="pull-right btn btn-inverse btn-xs" id="back-to-top" style="margin-top: -1px; text-transform: uppercase;">
														<i class="fa fa-arrow-up"></i>
													</button>
												</ul>
											</div>
										</footer>
								</div> <!-- page-container -->	
								</form>	
								' . footer($_SESSION['mod'], $_POST['accion']) . '
								<script>						
									window.onload = function () 
									{
										$(".panel-danger").css("background-color","' . $confSis["colorCont"] . ' !important");
										$(".panel-danger .panel-body").css("border-top","' . $confSis["colorCont"] . ' !important");
										$(".navbar").css("background-color","' . $confSis["colorTop"] . ' !important");										
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
								' . scripSpecial($_SESSION['mod'], $_POST['accion']) . '								
								<!-- Scripts Sistema-->						
								<script src="script/sistema/globales.js"></script>
								<script src="script/sistema/md5.js"></script>
								<script src="script/sistema/sistema.js"></script>					
								<!-- Template functions -->								
															
								</body>
							</html>';
    }

    return $sistema;
}

function cerrarSesion() {
    session_unset();
    session_destroy();
}

function muestra_menu($idPerfil) {
    liberar_bd();
    $selectModulosPadre = 'CALL sp_sistema_select_lista_modulos_padre();';
    $modulosPadre = consulta($selectModulosPadre);

    // REVISAR LOS PERMISOS CONCEDIDOS
    liberar_bd();
    $selectPermisosModulos = 'CALL sp_sistema_select_permisos_modulos(' . $idPerfil . ');';
    $permisosModulos = consulta($selectPermisosModulos);

    while ($modulo = siguiente_registro($modulosPadre)) {
        $checkedParent = false;
        while ($p = siguiente_registro($permisosModulos)) {
            if ($p["id_modulo"] == $modulo["id_modulo"]) {
                $checkedParent = true;
            }
        }
        mysqli_data_seek($permisosModulos, 0);

        if ($checkedParent == true) {
            if ($modulo['icono_modulo'] != '')
                $iconoMenu = '<i class="' . $modulo['icono_modulo'] . '"></i>';
            else
                $iconoMenu = '';

            $menu.='	<li>
							<a href="javascript:;">
								' . $iconoMenu . '
								<span>' . utf8_convertir($modulo['nombre_modulo']) . '</span>
							</a>';

            //SUBMODULOS HIJO 
            liberar_bd();
            $selectModulosHijo = 'CALL sp_sistema_select_modulos_hijo(' . $modulo["id_modulo"] . ')';
            $modulosHijo = consulta($selectModulosHijo);

            $menu.='	<ul class="acc-menu">';

            while ($subMod = siguiente_registro($modulosHijo)) {
                //CHECAMOS SI TIENE NIETOS
                liberar_bd();
                $selectModulosNietos = 'CALL sp_sistema_select_modulos_hijo(' . $subMod["id_modulo"] . ')';
                $modulosNietos = consulta($selectModulosNietos);
                $ctaModulosNietos = cuenta_registros($modulosNietos);

                if ($ctaModulosNietos != 0) {
                    $checkedSon = false;
                    while ($p = siguiente_registro($permisosModulos)) {
                        if ($p["id_modulo"] == $subMod["id_modulo"]) {
                            $checkedSon = true;
                        }
                    }
                    mysqli_data_seek($permisosModulos, 0);

                    if ($checkedSon == true) {
                        if ($subMod['icono_modulo'] != '')
                            $iconoMenu = '<i class="' . $subMod['icono_modulo'] . '"></i>';
                        else
                            $iconoMenu = '';

                        $menu.='	<li>
										<a href="javascript:;">
											' . $iconoMenu . '
											<span>' . utf8_convertir($subMod['nombre_modulo']) . '</span>
										</a>';

                        $menu.='		<ul class="acc-menu">';

                        while ($nietoMod = siguiente_registro($modulosNietos)) {
                            $checkedParent = false;
                            while ($p = siguiente_registro($permisosModulos)) {
                                if ($p["id_modulo"] == $nietoMod["id_modulo"]) {
                                    $checkedParent = true;
                                }
                            }
                            mysqli_data_seek($permisosModulos, 0);

                            if ($checkedParent == true) {
                                if ($nietoMod['icono_modulo'] != '')
                                    $iconoMenu = '<i class="' . $nietoMod['icono_modulo'] . '"></i>';
                                else
                                    $iconoMenu = '';

                                $menu.='	<li>
												<a href="javascript:navegar_modulo(' . $nietoMod['id_modulo'] . ');" id="menu_' . $nietoMod['id_padre'] . '_' . $nietoMod['id_modulo'] . '">
													' . $iconoMenu . '
													<span>' . utf8_convertir($nietoMod['nombre_modulo']) . '</span>
												</a>';
                            }
                        }

                        $menu.='</ul>';
                    }
                }
                else {
                    $checkedSon = false;
                    while ($p = siguiente_registro($permisosModulos)) {
                        if ($p["id_modulo"] == $subMod["id_modulo"]) {
                            $checkedSon = true;
                        }
                    }
                    mysqli_data_seek($permisosModulos, 0);

                    if ($checkedSon == true) {
                        $menu.='	<li>
										<a href="javascript:navegar_modulo(' . $subMod['id_modulo'] . ');" id="menu_' . $subMod['id_padre'] . '_' . $subMod['id_modulo'] . '">
											<span>' . utf8_convertir($subMod['nombre_modulo']) . '</span>
										</a>';
                    }
                }
            }

            $menu.='		</ul>
						</li>';
        }
    }

    return $menu;
}

function muestra_modulo($id) {
    liberar_bd();
    $selectDatosModulo = "CALL sp_sistema_select_datos_moduloID(" . $id . ");";
    $datosModulo = consulta($selectDatosModulo);
    $ctaDatosMosulo = cuenta_registros($datosModulo);
    if ($ctaDatosMosulo != 0) {
        $datMod = siguiente_registro($datosModulo);
        include_once($datMod['archivo']);
    } else
        include_once('./modulos/dashboard/inicio.php');

    return $modulo;
}

function scripttag($address) {
    $scriptReturn = "<script type='text/javascript' src='$address'></script> \n";
    return $scriptReturn;
}

function linktag($address) {
    $cssReturn = "<link rel='stylesheet' type='text/css' href='$address' /> \n";
    return $cssReturn;
}

function footer($pageModu, $pageMode) {

    if ($pageMode == "Nuevo" || $pageMode == "Editar" || $pageMode == "Nueva" || $pageMode == "Guardar" || $pageMode == "GuardarEdit" || $pageMode == 'Agregar' || $pageMode == 'GuardarAgregar' || $pageMode == 'EditarAgregar' || $pageMode == 'EliminarAgregar' || $pageMode == "GuardarSeg" || $pageMode == "Pagar" || $pageMode == 'AgregarDoc' || $pageMode == "GuardarEditarAgregar") {
        $scriptPagina .=scripttag("assets/plugins/form-multiselect/js/jquery.multi-select.min.js") .
                scripttag("assets/plugins/quicksearch/jquery.quicksearch.min.js") .
                scripttag("assets/plugins/form-typeahead/typeahead.min.js") .
                scripttag("assets/plugins/form-select2/select2.min.js") .
                scripttag("assets/plugins/form-autosize/jquery.autosize-min.js") .
                scripttag("assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js") .
                scripttag("assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.min.js") .
                scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js") .
                scripttag("assets/plugins/form-daterangepicker/moment.min.js") .
                scripttag("assets/plugins/form-fseditor/jquery.fseditor-min.js") .
                scripttag("assets/demo/demo-formcomponents.js");
    } else {
        if ($pageModu == 0 || $pageModu == 1004 || $pageModu == 1008 || $pageModu == 3001 || $pageModu == 3012 || $pageModu == 6000 || $pageModu == 1004) {
            $scriptPagina .=scripttag("assets/plugins/form-multiselect/js/jquery.multi-select.min.js") .
                    scripttag("assets/plugins/quicksearch/jquery.quicksearch.min.js") .
                    scripttag("assets/plugins/form-typeahead/typeahead.min.js") .
                    scripttag("assets/plugins/form-select2/select2.min.js") .
                    scripttag("assets/plugins/form-autosize/jquery.autosize-min.js") .
                    scripttag("assets/plugins/form-colorpicker/js/bootstrap-colorpicker.min.js") .
                    scripttag("assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.min.js") .
                    scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js") .
                    scripttag("assets/plugins/form-daterangepicker/moment.min.js") .
                    scripttag("assets/plugins/form-fseditor/jquery.fseditor-min.js") .
                    scripttag("assets/demo/demo-formcomponents.js");
        } elseif ($pageModu == 0 && $pageMode = 'Inicio') {
            $scriptPagina .=scripttag("assets/plugins/fullcalendar/fullcalendar.min.js") .
                    scripttag("assets/plugins/form-daterangepicker/daterangepicker.min.js") .
                    scripttag("assets/plugins/form-daterangepicker/moment.min.js") .
                    scripttag("assets/plugins/charts-flot/jquery.flot.min.js") .
                    scripttag("assets/plugins/charts-flot/jquery.flot.resize.min.js") .
                    scripttag("assets/plugins/charts-flot/jquery.flot.orderBars.min.js") .
                    scripttag("assets/plugins/pulsate/jQuery.pulsate.min.js") .
                    scripttag("assets/demo/demo-index.js");
        }
    }

    if ($pageModu == 1004 || $pageModu == 1008) {
        $scriptPagina .=scripttag("assets/plugins/form-jasnyupload/fileinput.min.js") .
                scripttag("assets/plugins/jcrop/js/jquery.Jcrop.min.js") .
                scripttag("assets/demo/demo-imagecrop.js");
    }

    if ($pageModu == 1008) {
        $scriptPagina .=scripttag("assets/plugins/bootbox/bootbox.min.js") .
                scripttag("assets/demo/demo-modals.js") .
                scripttag("assets/plugins/form-daterangepicker/moment.min.js") .
                scripttag("assets/plugins/form-xeditable/bootstrap3-editable/js/bootstrap-editable.min.js");
    }

    if ($pageModu == 3001) {
        $scriptPagina .=scripttag("assets/plugins/form-jasnyupload/fileinput.min.js");
    }

    if ($pageModu == 6000) {
        $scriptPagina .=scripttag("assets/plugins/fullcalendar/fullcalendar.min.js") .
                scripttag("assets/demo/demo-calendar.js");
    }

    $scriptPagina .=scripttag("assets/plugins/datatables/jquery.dataTables.min.js") .
            scripttag("assets/plugins/datatables/dataTables.bootstrap.js") .
            scripttag("assets/demo/demo-datatables.js") .
            scripttag('assets/js/placeholdr.js') .
            scripttag('assets/js/application.js') .
            scripttag('assets/demo/demo.js') .
            scripttag('assets/js/bootstrap-datetimepicker.js') .
            scripttag('assets/js/locales/bootstrap-datetimepicker.es.js');

    return $scriptPagina;
}

function heade($pageModu, $pageMode) {
    $cssPagina = '';
    if ($pageMode == "Nuevo" || $pageMode == "Editar" || $pageMode == "Nueva" || $pageMode == "Guardar" || $pageMode == "GuardarEdit" || $pageMode == 'Agregar' || $pageMode == 'GuardarAgregar' || $pageMode == 'EditarAgregar' || $pageMode == 'EliminarAgregar' || $pageMode == "GuardarSeg" || $pageMode == "Pagar" || $pageMode == 'AgregarDoc' || $pageMode == "GuardarEditarAgregar") {
        $cssPagina.= linktag('assets/plugins/form-select2/select2.css') .
                linktag('assets/plugins/form-multiselect/css/multi-select.css') .
                linktag('assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css') .
                linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css') .
                linktag('assets/plugins/form-fseditor/fseditor.css') .
                linktag('assets/js/jqueryui.css'); // <!-- jquery ui -->
    } else {
        if ($pageModu == 0 || $pageModu == 1004 || $pageModu == 1008 || $pageModu == 3001 || $pageModu == 3012 || $pageModu == 6000 || $pageModu == 1004) {
            $cssPagina.= linktag('assets/plugins/form-select2/select2.css') .
                    linktag('assets/plugins/form-multiselect/css/multi-select.css') .
                    linktag('assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css') .
                    linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css') .
                    linktag('assets/plugins/form-fseditor/fseditor.css') .
                    linktag('assets/js/jqueryui.css'); // <!-- jquery ui -->
        } elseif ($pageModu == 0 && $pageMode = 'Inicio') {
            $cssPagina.= linktag('assets/plugins/form-daterangepicker/daterangepicker-bs3.css') .
                    linktag('assets/plugins/fullcalendar/fullcalendar.css') .
                    linktag('assets/plugins/form-markdown/css/bootstrap-markdown.min.css');
        }
    }

    if ($pageModu == 1004 || $pageModu == 1008) {
        $cssPagina.= linktag('assets/plugins/jcrop/css/jquery.Jcrop.min.css');
    }

    if ($pageModu == 1008) {
        $cssPagina .= linktag('assets/plugins/form-xeditable/bootstrap3-editable/css/bootstrap-editable.css'); //<!-- Toggles -->
    }

    if ($pageModu == 6000) {
        $cssPagina .= linktag('assets/plugins/fullcalendar/fullcalendar.css');
    }

    $cssPagina.= linktag('assets/plugins/datatables/dataTables.css') .
            linktag('assets/plugins/codeprettifier/prettify.css') .
            linktag('assets/plugins/form-toggle/toggles.css') .
            linktag('assets/css/datetimepicker/bootstrap-datetimepicker.min.css');

    return $cssPagina;
}

function scripSpecial($pageModu, $pageMode) {
    if ($pageModu == '0' || $pageModu == '') {
        $scriptSpecial = '	<script>
								function buscarVentas()
								{
									var fechaReporte = $("#fechaReporte").val();
									$(".bodyVentas").html(\'<div style="height:100px !important; width:100px !important;" id="cargando"></div>\');
									$(".bodyVentas").load("../seccion/ajax/bodyVentas.php",
									{fechaReporte:fechaReporte},
									 function(){																											
									  });		
								}
								
								function buscarDescuentos()
								{
									var fechaReporteDescuentos = $("#fechaReporteDescuentos").val();
									$(".bodyDescuentos").html(\'<div style="height:100px !important; width:100px !important;" id="cargando"></div>\');
									$(".bodyDescuentos").load("../seccion/ajax/bodyDescuentos.php",
									{fechaReporteDescuentos:fechaReporteDescuentos},
									 function(){																											
									  });		
								}								
								
							</script>';
    }

    if ($pageModu == '1008') {
        $scriptSpecial = '	<script>
								$("#dob").editable({
										 url: "../seccion/ajax/cambiar_fecha_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newFecha"] = params.value;
											return push;
										 }	
								   });		
										 					     
								function editarNombrePerfil()
								{
									$("#inline-nombre").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de nombre",
										 url: "../seccion/ajax/cambiar_nombre_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newNombre"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarUsuarioPerfil()
								{
									$("#inline-usuario").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de usuario",
										 url: "../seccion/ajax/cambiar_usuario_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newUsuario"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarPasswordPerfil()
								{
									$("#inline-pass").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de contraseña",
										 url: "../seccion/ajax/cambiar_contrasenia_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newPass"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarCorreoPerfil()
								{
									$("#inline-correo").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de correo",
										 url: "../seccion/ajax/cambiar_correo_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newCorreo"] = params.value;
											return push;
										 }	
								   });
								}		
								
								function editarLadaPerfil()
								{
									$("#inline-lada").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de lada",
										 url: "../seccion/ajax/cambiar_lada_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newLada"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarTelefonoPerfil()
								{
									$("#inline-telefono").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de teléfono",
										 url: "../seccion/ajax/cambiar_telefono_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newTel"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarCallePerfil()
								{
									$("#inline-calle").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de calle",
										 url: "../seccion/ajax/cambiar_calle_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newCalle"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarNumExtPerfil()
								{
									$("#inline-numExt").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de número exterior",
										 url: "../seccion/ajax/cambiar_numExt_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newNumExt"] = params.value;
											return push;
										 }	
								   });
								}		
								
								function editarNumIntPerfil()
								{
									$("#inline-numInt").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de número exterior",
										 url: "../seccion/ajax/cambiar_numInt_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newNumInt"] = params.value;
											return push;
										 }	
								   });
								}		
								
								function editarColoniaPerfil()
								{
									$("#inline-colonia").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de colonia",
										 url: "../seccion/ajax/cambiar_colonia_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newColonia"] = params.value;
											return push;
										 }	
								   });
								}	
								
								function editarCPPerfil()
								{
									$("#inline-codigo").editable({
										 type: "text",
										 pk: 1,									 
										 title: "Cambio de código postal",
										 url: "../seccion/ajax/cambiar_cp_perfil.php",
										 params: function(params)
										 {
											var push = {};
											push["newCP"] = params.value;
											return push;
										 }	
								   });
								}
								
								function valideFile(adjunto)
								{
									var archivo = document.getElementById("adjunto").value;
									splitName = archivo.split(".");
									fileType = splitName[1];
									fileType = fileType.toLowerCase();
									if (fileType != "jpg" && fileType != "png")
									{
										document.getElementById("adjunto").value = "";
										$("#btnGuardarImagen").css("display","none");
										alert("Archivo con extensión no válida.");
									}
									else
										$("#btnGuardarImagen").css("display","inline-block");
								}
								
								function guardarImagen()
								{
									$("form#frmImagen").submit(); 
									$("#divImgNew").css("display","block");
									$("#divImgNew").html(\'<div style="height:100px !important; width:100px !important;" id="cargando"></div>\');									
								}
							
								// Variable to store your files
								var files;
							
								// Add events
								$("input[type=file]").on("change", prepareUpload);
								$("form#frmImagen").on("submit", uploadFiles);
							
								// Grab the files and set them to our variable
								function prepareUpload(event)
								{
									files = event.target.files;
								}
							
								// Catch the form submit and upload the files
								function uploadFiles(event)
								{
									event.stopPropagation(); // Stop stuff happening
									event.preventDefault(); // Totally stop stuff happening
							
									// START A LOADING SPINNER HERE
							
									// Create a formdata object and add the files
									var data = new FormData();
									$.each(files, function(key, value)
									{
										data.append(key, value);
									});
									
									$.ajax({
										url: "submit.php?files",
										type: "POST",
										data: data,
										cache: false,
										dataType: "json",
										processData: false, // Don not process the files
										contentType: false, // Set content type to false as jQuery will tell the server its a query string request
										success: function(data, textStatus, jqXHR)
										{
											if(typeof data.error === "undefined")
											{
												// Success so call function to process the form
												submitForm(event, data);
											}
											else
											{
												// Handle errors here
												console.log("ERRORS: " + data.error);
											}
										},
										error: function(jqXHR, textStatus, errorThrown)
										{
											// Handle errors here
											console.log("ERRORS: " + textStatus);
											// STOP LOADING SPINNER
										}
									});
								}
							
								function submitForm(event, data)
								{
									// Create a jQuery object from the form
									$form = $(event.target);
									
									// Serialize the form data
									var formData = $form.serialize();
									
									// You should sterilise the file names
									$.each(data.files, function(key, value)
									{
										formData = formData + "&filenames[]=" + value;
									});
							
									$.ajax({
										url: "submit.php",
										type: "POST",
										data: formData,
										cache: false,
										dataType: "json",
										success: function(data, textStatus, jqXHR)
										{
											if(typeof data.error === "undefined")
											{
												// Success so call function to process the form
												console.log("SUCCESS: " + data.success);
											}
											else
											{
												// Handle errors here
												console.log("ERRORS: " + data.error);
											}
										},
										error: function(jqXHR, textStatus, errorThrown)
										{
											// Handle errors here
											console.log("ERRORS: " + textStatus);
										},
										complete: function()
										{
											// STOP LOADING SPINNER
										}
									});
								}
								
							</script>';
    }
    if ($pageModu == '1004') {
        ?>
        <script>
            function editarAviso()
            {
                var descripcion = $("#descripcion_aviso").val();
                var dias = $("#cantidad_aviso").val();
                if (descripcion !== "" && dias !== "" && dias !== 0)
                {
                    $.ajax
                            ({
                                type: "POST",
                                url: "../seccion/ajax/cambiar_aviso.php",
                                data: {descripcion_aviso: descripcion, cantidad_aviso: dias},
                                cache: false,
                                dataType: "json",
                                success: function (data)
                                {
                                    alert("Success");
                                    $("#tddescripcion").html("Success");
                                },
                                error: function () {
                                    //alert("Error");
                                    location.reload();
                                }
                            });
                }
            }

        </script>
        <?php
    }
    if ($pageModu == '3012') {
        $scriptSpecial = '	<script>
                                                                function guardarAgenda()
                                                                {
                                                                    var nombreAgenda = $("#nombreAgenda").val();
									if(nombreAgenda != "")
									{									
										$.post("../seccion/ajax/guardarAgenda.php", {nombreAgenda:nombreAgenda}, 
											function(){
												$.ajax
												({
													type: "POST",
													url: "../seccion/ajax/datos_agenda.php",
													data: {},
													cache: false,
													dataType: "json",
													success: function(data)
													{
														$("#idAgenda").val(data.id);	
														navegar(\'Editar\');
                                                                                                        }			
												});	
											});	
									}
									else
										alert("Capture nombre del contacto de agenda");
								}
								
								function editNomAgenda()
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_nombre_contacto.php",
										data: {idAgenda:idAgenda},
										cache: false,
										dataType: "json",
										success: function(data)
										{
											$("#prefijoContacto").val(data.prefijo);
											$("#nombreContacto").val(data.nombre);
											$("#segNombreContacto").val(data.segNombre);
											$("#apellidoContacto").val(data.apellido);
											$("#sufijoContacto").val(data.sufijo);											
										}			
									});		
								}
								
								function guardarNombreContacto()
								{
									var prefijo = $("#prefijoContacto").val();
									var nombre = $("#nombreContacto").val();
									var segNombre = $("#segNombreContacto").val();
									var apellido = $("#apellidoContacto").val();
									var sufijo = $("#sufijoContacto").val();
									$.post("../seccion/ajax/cambiar_nombre_agenda.php", {prefijo:prefijo, nombre:nombre, segNombre:segNombre, apellido:apellido, sufijo:sufijo}, function(data)
									{
										$("#h3Nombre").load("../seccion/ajax/nombreContacto.php");
									});	
								}
								
								function editarPuesto()
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_puesto_contacto.php",
										data: {idAgenda: idAgenda},
										cache: false,
										dataType: "json",
										success: function(data)
										{
											$("#puestoContacto").val(data.puesto);									
										}			
									});		
								}
								
								function guardarPuestoContacto()
								{
									var puesto = $("#puestoContacto").val();
									$.post("../seccion/ajax/cambiar_puesto_agenda.php", {puesto:puesto}, function(data)
									{
										$("#h3Puesto").load("../seccion/ajax/puestoContacto.php");
										$("#puestoContacto").val("");
									});	
								}
								
								function editarEmpresa()
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_empresa_contacto.php",
										data: {idAgenda: idAgenda},
										cache: false,
										dataType: "json",
										success: function(data)
										{
											$("#empresaContacto").val(data.empresa);									
										}			
									});		
								}
								
								function guardarEmpresaContacto()
								{
									var empresa = $("#empresaContacto").val();
									$.post("../seccion/ajax/cambiar_empresa_agenda.php", {empresa:empresa}, function(data)
									{
										$("#h3Empresa").load("../seccion/ajax/empresaContacto.php");
										$("#empresaContacto").val("");
									});	
								}
								
								function editarCorreo(idCorreo)
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_correo_contacto.php",
										data: {idCorreo:idCorreo},
										cache: false,
										dataType: "json",
										success: function(data)
										{
											$("#correoContacto").val(data.correo);	
											$("#idCorreo").val(data.id);									
										}			
									});		
								}
								
								function guardarCorreoContacto()
								{
									var correo = $("#correoContacto").val();
									var idCorreo = $("#idCorreo").val();
									$.post("../seccion/ajax/cambiar_correo_agenda.php", {idCorreo:idCorreo, correo:correo}, function(data)
									{
										$("#h3Corre").load("../seccion/ajax/correoContacto.php");
										$("#correoContacto").val("");	
										$("#idCorreo").val("");
									});	
								}
								
								function editarTelefono(idTelefono)
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_telefono_contacto.php",
										data: {idTelefono:idTelefono},
										cache: false,
										dataType: "json",
										success: function(data)
										{
											$("#tipTelContacto option[value="+ data.idTipo +"]").attr("selected",true);
											$("#s2id_tipTelContacto .select2-chosen").html(data.categoria);
											$("#ladaContacto").val(data.lada);	
											$("#telefonoContacto").val(data.telefono);	
											$("#idTelefono").val(data.id);									
										}			
									});		
								}
								
								function guardarTelefonoContacto()
								{
									var tiTel = $("#tipTelContacto").val();
									if(tiTel != 0)
									{
										var idTelefono = $("#idTelefono").val();
										var lada = $("#ladaContacto").val();
										var telefono = $("#telefonoContacto").val();
										$.post("../seccion/ajax/cambiar_telefono_agenda.php", {idTelefono:idTelefono, tiTel:tiTel, lada:lada, telefono:telefono}, function(data)
										{
											$("#h3Telefono").load("../seccion/ajax/telefonoContacto.php");
											$("#tipTelContacto option[value="+ 0 +"]").attr("selected",true);
											$("#s2id_tipTelContacto .select2-chosen").html("Tipo de teléfono");
											$("#ladaContacto").val("");	
											$("#telefonoContacto").val("");	
											$("#idTelefono").val("");
										});	
									}
									else
										alert("Seleccione tipo de teléfono");
								}
								
								function editarDireccion(idDir)
								{
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_dir_contacto.php",
										data: {idDir:idDir},
										cache: false,
										dataType: "json",
										success: function(data)
										{											
											$("#idDireccion").val(data.id);
											$("#calleContacto").val(data.calle);	
											$("#numExtContacto").val(data.numExt);	
											$("#numIntContacto").val(data.numInt);
											$("#coloniaContacto").val(data.colonia);	
											$("#cpContacto").val(data.cp);	
											
											$("#tipDirContacto option[value="+ data.idCat +"]").attr("selected",true);
											$("#s2id_tipDirContacto .select2-chosen").html(data.categoria);
											
											$("#id_estado option[value="+ data.idEdo +"]").attr("selected",true);
											$("#s2id_id_estado .select2-chosen").html(data.estado);
											
											$("#id_ciudad option[value="+ data.idCity +"]").attr("selected",true);
											$("#s2id_id_ciudad .select2-chosen").html(data.ciudad);
																				
										}			
									});		
								}
								
								function guardarDirContacto()
								{
									var id_estado = $("#id_estado").val();
									var id_ciudad = $("#id_ciudad").val();
									var idCategoria = $("#tipDirContacto").val();
									if(id_estado != 0 && id_ciudad != 0 && idCategoria != 0)
									{
										var idDireccion = $("#idDireccion").val();
										var calle = $("#calleContacto").val();
										var numExt = $("#numExtContacto").val();
										var numInt = $("#numIntContacto").val();
										var colonia = $("#coloniaContacto").val();
										var cp = $("#cpContacto").val();
											
										$.post("../seccion/ajax/cambiar_direccion_agenda.php", {idDireccion:idDireccion, id_ciudad:id_ciudad, idCategoria:idCategoria, calle:calle, numExt:numExt, numInt:numInt, colonia:colonia, cp:cp}, function(data)
										{
											$("#h3Direccion").load("../seccion/ajax/direccionContacto.php");
											$("#idDireccion").val("");
											$("#calleContacto").val("");
											$("#numExtContacto").val("");
											$("#numIntContacto").val("");
											$("#coloniaContacto").val("");
											$("#cpContacto").val("");
											
											$("#tipDirContacto option[value="+ 0 +"]").attr("selected",true);
											$("#s2id_tipDirContacto .select2-chosen").html(data.categoria);
											
											$("#id_estado option[value="+ 12 +"]").attr("selected",true);
											$("#s2id_id_estado .select2-chosen").html("Guanajuato");
											
											$("#id_ciudad option[value="+ 462 +"]").attr("selected",true);
											$("#s2id_id_ciudad .select2-chosen").html("Leon");
										});	
									}
									else
										alert("Seleccione estado, municipio y categoría de la dirección");
								}
								
								function editarFecha()
								{
									var fechaContacto = "";
									var idAgenda = $("#idAgenda").val();
									$.ajax
									({
										type: "POST",
										url: "../seccion/ajax/cargar_fecha_contacto.php",
										data: {idAgenda,idAgenda},
										cache: false,
										dataType: "json",
										success: function(data)
										{	
											if(data.fecha == "0000-00-00")	
												fechaContacto = "1987-05-05";
											else
												fechaContacto = data.fecha;
																			
											$("#datepicker").val(fechaContacto);	
										}			
									});		
								}
								
								function guardarFechaContacto()
								{									
									var fecha = $("#datepicker").val();
									$.post("../seccion/ajax/cambiar_fecha_agenda.php", {fecha:fecha}, function(data)
									{
										$("#h3Fecha").load("../seccion/ajax/fechaContacto.php");
										$("#datepicker").val();
									});											
								}
								
								$("#txtAgenda").blur(function(){
									var txtAgenda = $("#txtAgenda").val();
									if(txtAgenda == "")
										$("#txtAgenda").val("Añade una nota");
									else
									{
										$.post("../seccion/ajax/cambiar_nota_agenda.php", {txtAgenda:txtAgenda}, function(data)
										{											
										});	
									}
								});
								
								function guardarNewCorreoContacto()
								{
									var correo = $("#newCorreoContacto").val();
									if(correo != "")
									{
										$.post("../seccion/ajax/newCorreoContacto.php", {correo:correo}, function(data)
										{
											$("#h3Corre").load("../seccion/ajax/correoContacto.php");
											$("#newCorreoContacto").val("");	
										});	
									}
									else
										alert("Captura el correo del contacto");
								}

								function guardarNewTelefonoContacto()
								{
									var tiTel = $("#newTipTelContacto").val();
									if(tiTel != 0)
									{
										var lada = $("#newLadaContacto").val();
										var telefono = $("#newTelefonoContacto").val();
										$.post("../seccion/ajax/newTelefonoContacto.php", {tiTel:tiTel, lada:lada, telefono:telefono}, function(data)
										{
											$("#h3Telefono").load("../seccion/ajax/telefonoContacto.php");
											$("#newTipTelContacto option[value="+ 0 +"]").attr("selected",true);
											$("#s2id_newTipTelContacto .select2-chosen").html("Tipo de teléfono");
											$("#newLadaContacto").val("");	
											$("#newTelefonoContacto").val("");	
										});	
									}
									else
										alert("Seleccione tipo de teléfono");
								}

								function guardarNewDirContacto()
								{
									var id_estado = $("#newId_estado").val();
									var id_ciudad = $("#newId_ciudad").val();
									var idCategoria = $("#newTipDirContacto").val();
									if(id_estado != 0 && id_ciudad != 0 && idCategoria != 0)
									{
										var calle = $("#newCalleContacto").val();
										var numExt = $("#newNumExtContacto").val();
										var numInt = $("#newNumIntContacto").val();
										var colonia = $("#newColoniaContacto").val();
										var cp = $("#newCPContacto").val();
											
										$.post("../seccion/ajax/newDireccionContacto.php", {id_ciudad:id_ciudad, idCategoria:idCategoria, calle:calle, numExt:numExt, numInt:numInt, colonia:colonia, cp:cp}, function(data)
										{
											$("#h3Direccion").load("../seccion/ajax/direccionContacto.php");
											$("#newCalleContacto").val("");
											$("#newNumExtContacto").val("");
											$("#newNumIntContacto").val("");
											$("#newColoniaContacto").val("");
											$("#newCPContacto").val("");
											
											$("#newId_estado option[value="+ 0 +"]").attr("selected",true);
											$("#s2id_newTipDirContacto .select2-chosen").html(data.categoria);
											
											$("#newId_ciudad option[value="+ 12 +"]").attr("selected",true);
											$("#s2id_newId_estado .select2-chosen").html("Guanajuato");
											
											$("#newTipDirContacto option[value="+ 462 +"]").attr("selected",true);
											$("#s2id_newId_ciudad .select2-chosen").html("Leon");
										});	
									}
									else
										alert("Seleccione estado, municipio y categoría de la dirección");
								}								
								
								$("input[name=\'optTipo\']:radio").change(function () 
								{
									$("#divContAgenda").css("display","none");
									elegido = $("input[name=\'optTipo\']:checked").val();
									if(elegido == 1)
									{	
										$("#nombreOpcion").css("display","none");									
										$.post("../seccion/ajax/listaCliente.php", {}, function(data){
											$("#s2id_idOpcion .select2-chosen").html("Seleccione cliente");
											$("#idOpcion").html(data);
											$("#spnOpcion").html("Cliente:");
											$("#s2id_idOpcion").css("display","block");
											$("#idOpcion").css("display","block");	
										});										
									}
									else
									{
										if(elegido == 2)
										{
											$("#nombreOpcion").css("display","none");
											$("#divServicio").css("display","none");
											$.post("../seccion/ajax/listaProveedor.php", {}, function(data){
												$("#s2id_idOpcion .select2-chosen").html("Seleccione proveedor");
												$("#idOpcion").html(data);
												$("#spnOpcion").html("Proveedor:");
												$("#s2id_idOpcion").css("display","block");
												$("#idOpcion").css("display","block");	
											});
										}
										else
										{
											if(elegido == 3)
											{
												$("#idOpcion").css("display","none");
												$("#s2id_idOpcion").css("display","none");
												$("#spnOpcion").html("Capture nombre:");
												$("#nombreOpcion").css("display","block");
											}
										}
									}									
									
									$("#divContAgenda").css("display","block");								
								});	
								
								function guardarMedioContacto()
								{
									var idAgenda = $("#idAgenda").val();
									var idTipo = $("#idTipo").val();
									var detalleCon = $("#detalleCon").val();
									$.post("../seccion/ajax/guardarContacto.php", {idAgenda:idAgenda, idTipo:idTipo, detalleCon:detalleCon}, function(data)
									{
											$.post("../seccion/ajax/listaTiposContacto.php", {}, function(data)
											{
												$("#s2id_idTipo .select2-chosen").html("Seleccione tipo de contacto");
												$("#idTipo").html(data);
												$("#s2id_idTipo").css("display","block");
												$("#idTipo").css("display","block");	
											});
											$("#detalleCon").val("");
											$("#divMediosContacto").load("../seccion/ajax/cargaListaMediosContacto.php",{idAgenda:idAgenda});
									});	
								}
								
								function eliminarMedioContacto(idContacto)
								{
									var idAgenda = $("#idAgenda").val();
									$.post("../seccion/ajax/eliminarContacto.php", {idContacto:idContacto}, function(data)
									{
										$("#divMediosContacto").load("../seccion/ajax/cargaListaMediosContacto.php",{idAgenda:idAgenda});
									});	
								}
							</script>';
    }

    if ($pageModu == '3001') {
        $scriptSpecial = '	<script>
								$("input[name=tipoCliente]").change(function () 
								{
									elegido=$(this).val();
									if(elegido == 1)
									{	
										$("#divDatosEmpresa").css("display","none");
										$("#razon").val("");
									}
									else
									{
										if(elegido == 2)
										{
											$("#divDatosEmpresa").css("display","block");
										}
									}
								});
								
								$("input[name=accesoCliente]").change(function () 
								{
									elegido=$(this).val();
									if(elegido == 1)
									{	
										$("#divAcceso").css("display","none");
										$("#login_usuario").val("");
										$("#pswd_usuario").val("");
										$("#pswd_usuario_c").val("");
									}
									else
									{
										if(elegido == 2)
										{
											$("#divAcceso").css("display","block");
										}
									}
								});								
								
								$("#id_estado").change(function () 
		                        {	
									$("#id_estado option:selected").each(function () 
		                            {
		                                elegido=$(this).val();
		                                $("#divCiudad").load("../seccion/ajax/cargarCiudades.php",{ elegido: elegido },
		                                function(){
				                                	$("#id_ciudad").select2({
											            placeholder: "Seleccione una ciudad",
											            width: "100%"
											        });							                                    
		                                  });
		                            });                                
		                        }); 
								
								function verDocumento(idArchivo)
								{
									var archivo = "../documentos/"+idArchivo;
									$("#divVerDocumento").html("");
									$("#divVerDocumento").load(archivo);
									/*$("#divVerDocumento").html(\'<embed src="" style="width:100%; height:500px;">\');
									$("#divVerDocumento embed").attr("src", archivo);*/
								}
								  
								 function nuevoCliente(opcion)
								 {
									 var tipoCliente =  $("input[name=tipoCliente]:checked").val();
									 if(tipoCliente == 2)
									 {
										  var razon = $("#razon").val();
										  if(razon == "")
										  {
											  alert("Capture la razón social de la empresa");
											  return false;
										  }
									 }
									 
									 var accesoCliente =  $("input[name=accesoCliente]:checked").val();
									 if(accesoCliente == 2)
									 {
										  var login_usuario = $("#login_usuario").val();
										  if(login_usuario == "")
										  {
											  alert("Capture el usuario de acceso");
											  return false;
										  }
										  
										  if(opcion == \'Guardar\')
										  {										  
											  var pswd_usuario = $("#pswd_usuario").val();
											  var pswd_usuario_c = $("#pswd_usuario_c").val();
											  if(pswd_usuario == "" || pswd_usuario_c == "")
											  {
												  alert("Capture la contraseña de acceso");
												  return false;
											  }
											  else
											  {
												  if(pswd_usuario != pswd_usuario_c)
												  {
													  alert("Las claves de acceso no coinciden");
													  return false;
												  }
											  }
										  }
										  else
										  {
											  if(pswd_usuario != pswd_usuario_c)
											  {
												  alert("Las claves de acceso no coinciden");
												  return false;
											  }
										  }
									 }
									 
									 var nombreCliente = $("#nombreCliente").val();
									 var correoCliente = $("#correoCliente").val();
									 var nombreContactoCliente = $("#nombreContactoCliente").val();
									 if(nombreCliente != "" && correoCliente != "" && nombreContactoCliente != "")
									 {
										 var id_estado = $("#id_estado").val();
										 var id_ciudad = $("#id_ciudad").val();
										 if(id_estado != 0 && id_ciudad != 0)
										 	navegar(opcion);
										 else
										 	alert("Seleccione el estado y la ciudad");	
									 }
									 else
								 	 	alert("Capture nombre, nombre de contacto y correo");									 
								 }
							</script>';
    }

    if ($pageModu == '6000') {
        $scriptSpecial = '	<script>
								function revisarEvento()
								{
									var tituloEvento = $("#tituloEvento").val();
									var fechaIniEvento = $(".fechaIniEvento").val();
									var fechaFinEvento = $(".fechaFinEvento").val();
									if(tituloEvento != "" && fechaIniEvento != "" && fechaFinEvento != "")
									{
										dA = fechaIniEvento.split(" ");
										
										dArr = dA[0].split("-");
										dArrIni = new Date(dArr[2] + "-" + dArr[1] + "-" + dArr[0]);
    									ts = dArrIni.getTime();
										
										dA2 = fechaFinEvento.split(" ");
										dArr2 = dA2[0].split("-");
										dArrFin = new Date(dArr2[2] + "-" + dArr2[1] + "-" + dArr2[0]);
    									ts2 = dArrFin.getTime();
										if(ts <= ts2)
										{
											var diaIni = dArr[0];											
											var diaFin = dArr2[0];												
											
											if(diaIni == diaFin)
											{
												hArr = dA[1].split(":");
												hArr2 = dA2[1].split(":");
												
												if(hArr[0]==hArr2[0])
												{
													var difMinutos = hArr2[1] - hArr[1];
													if(difMinutos <= 15)
													{
														$("#btnGuardarEvento").css("display","none");
														alert("El intervalo de fechas debe ser mayor a 15 minutos");
													}
													else
														$("#btnGuardarEvento").css("display","inline-block");
												}												
												else
													$("#btnGuardarEvento").css("display","inline-block");
											}
											else
												$("#btnGuardarEvento").css("display","inline-block");
										}
										else
										{
											$("#btnGuardarEvento").css("display","none");
											alert("La fecha final debe ser mayor a la fecha de inicio");
										}
									}
									else
										$("#btnGuardarEvento").css("display","none");
								}
								
								function guardarEvento()
								{
									var tituloEvento = $("#tituloEvento").val();
									var fechaIniEvento = $(".fechaIniEvento").val();
									var fechaFinEvento = $(".fechaFinEvento").val();
									var urlEvento = $("#urlEvento").val();
									
									$("#div_articulos").load("../seccion/ajax/agregarEvento.php",
									{tituloEvento:tituloEvento, fechaIniEvento:fechaIniEvento, fechaFinEvento:fechaFinEvento, urlEvento:urlEvento},
									 function(){
										 renderCalendar();
										 restaura_eventos();
									  });
								}	
								
								function restaura_eventos()
								{	
									$("#btnGuardarEvento").css("display","none");								
									$("#tituloEvento").val("");
									$(".fechaIniEvento").val("");
									$(".fechaFinEvento").val("");
									$("#urlEvento").val("");
								}
								
								function renderCalendar() 
								{
									$("#calendar-drag").fullCalendar("destroy");
									$("#calendar-drag").fullCalendar({
										header: {
											left: "prev,next today",
											center: "title",
											right: "month,agendaWeek,agendaDay"
										},
										allDayDefault: false,
										editable: false,
										eventLimit: true, // allow "more" link when too many events
										events: {
											url: "php/get-events.php",
											error: function() {
												$("#script-warning").show();
											}
										},
										eventClick: function(calEvent) {
											if (confirm("Desea eliminar este evento")) 
											{
												$("#div_articulos").load("../seccion/ajax/eliminarEvento.php",
												{idEvento:calEvent.id},
												 function(){
													 renderCalendar();
													 restaura_eventos();
												  });
											}
										}
									});
								}
							</script>';
    }

    $scriptSpecial .= '<script>		
						  /*$("#id_estado").change(function () 
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
						  
						  $("#newId_estado").change(function () 
						  {
							  $("#formCiudad").css("display","none");
							  $("#newId_estado option:selected").each(function () 
							  {
								  elegido=$(this).val();
								  $.post("ajax/ciudadesAjax.php", { elegido: elegido }, function(data){
								  $("#newId_ciudad").html(data);
								  $("#formCiudad").css("display","block");								  
								  });			
							  });
						  });*/					 		
							
					 	$("#datepicker,#datepicker3,#datepicker5").datetimepicker(
						{	language:  "es",
							format: "dd-mm-yyyy",
							autoclose: true,
							todayBtn: true,
							minView: 2,
							maxView: 4
						});
						
						var today = new Date();
						$(".fechaInicidencia, .fechaEntrega, .fechaAceptacion").datetimepicker(
						{	language:  "es",
							format: "dd-mm-yyyy hh:mm:ss",
							autoclose: true,
							todayBtn: true,
							minView: 0,
							maxView: 4	,
							endDate: new Date(today.getFullYear(), today.getMonth(), today.getDate(), today.getHours(), today.getMinutes(), today.getSeconds())						
						});
						
						$(".fechaProxInic, .datepickerEntrega, .fechaIniEvento, .fechaFinEvento").datetimepicker(
						{	
							language:  "es",
							autoclose: true,
							pickTime: true					
						});
						
					  </script> ';

    return $scriptSpecial;
}
?>