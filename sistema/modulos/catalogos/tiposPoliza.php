<?php

	session_start();
	include_once('tiposPoliza_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
	switch($_POST['accion'])
	{
		case 'Nuevo':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tiposPoliza_formularioNuevo();
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tiposPoliza_formularioEditar();
			break;
		
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tiposPoliza_guardar();
			break;
			
		case 'GuardarEdit':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tiposPoliza_editar();
			break;
			
		case 'Eliminar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo.=tiposPoliza_eliminar();
			break;
			
		default:
			$modulo .= tiposPoliza_menuInicio();
		break;
		
	}
	
?>