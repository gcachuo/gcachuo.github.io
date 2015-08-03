<?php

	session_start();
	include_once('datosEmpresa_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
	switch($_POST['accion'])
	{
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= configuracion_guardar();
		break;
		
		case 'GuardarEditar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= configuracion_editar();
		break;
			
		default:
			$modulo .= configuracion_menuInicio();
		break;
		
	}
	
?>