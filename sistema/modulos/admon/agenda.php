<?php

	session_start();
	include_once('agenda_funciones.php');
	//DATOS DEL MODULO
	liberar_bd();
	$selectDatosModulo = 'CALL sp_sistema_select_datos_modulo('.$_SESSION["mod"].');';
	$datosModulo = consulta($selectDatosModulo);
	$datMod = siguiente_registro($datosModulo);
	$_SESSION["moduloPadreActual"] = utf8_convertir($datMod["nombre"]);
	
	switch($_POST['accion'])
	{
		case 'Detalles':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= agenda_detalles();
			break;
		
		case 'Editar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= agenda_editar();
		break;	
		
		case 'Eliminar':
			$_SESSION["moduloHijoActual"] = utf8_convertir($_POST['accion']);
			$modulo .= agenda_eliminar();
		break;
		
		default:
			$modulo .= agenda_menuInicio();
		break;
		
	}
	
?>