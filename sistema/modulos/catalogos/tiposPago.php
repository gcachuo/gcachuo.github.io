<?php

	session_start();
	include_once('tiposPago_funciones.php');
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
			$modulo .= tipoPago_formularioNuevo();
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tipoPago_formularioEditar();
			break;
		
		case 'Guardar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tipoPago_guardar();
			break;
			
		case 'GuardarEdit':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= tipoPago_editar();
			break;
			
		case 'Eliminar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo.=tipoPago_eliminar();
			break;
			
		default:
			$modulo .= tipoPago_menuInicio();
		break;
		
	}
	
?>